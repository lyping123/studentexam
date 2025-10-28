<?php

namespace App\Http\Controllers;

use App\Models\exam_question;
use App\Models\question_paper;
use App\Models\subject;
use Illuminate\Http\Request;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\TemplateProcessor;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DocxController extends Controller
{
    public function downloadDocx($id)
    {
        $question_paper =question_paper::find($id);
        // dd($question_paper);
        
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();
        function question_text($section, $data) {
            $section->addText(htmlspecialchars($data), [
               'size' => 12
            ]);
        }
        

        // header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
        $section->addText("MULTIPLE CHOICE QUESTIONS",[
            'bold' => true, 
            'size' => 14
        ]);
        $section->addTextBreak(); // Add spacing
        $count=$question_paper->exam_question->count();


        $section->addText("This test consists of ".$count." objective questions based on Modules which are compulsory. Each question will carry 1 mark. Answer on the answer sheet given. If you want to change your answer, please cross the previous answer");
        $section->addTextBreak(); 

        foreach ($question_paper->exam_question as  $index=>$question) {
            $content=json_decode($question->subject->sub_content);
            if ($content->type == "picture") {
                $imagePath = public_path($content->content); // Assuming the image path is stored in $content->path
                // dd(file_exists($imagePath));
                if (file_exists($imagePath)) {
                    $section->addImage($imagePath, [
                        'width' => 300, // Adjust width as needed
                        'height' => 200, // Adjust height as needed
                    ]);
                }
            }
            if (strpos($question->subject->sub_title, "\n") !== false) {
                $lines = explode("\n", $question->subject->sub_title);
                question_text($section, ($index + 1) . ". " . array_shift($lines));
                foreach ($lines as $line) {
                    question_text($section, "     " . $line);
                }
            } else {
                question_text($section, ($index + 1) . ". " . $question->subject->sub_title);
            }
            // question_text($section,($index + 1) . ". " . $question->subject->sub_title);
            foreach($question->subject->questions as $subQuestion){
                question_text($section, "     " . $subQuestion->question_title);
            }
            
            $section->addTextBreak(); // Add spacing
        }
        $tableStyle = [
            'borderSize' => 6,
            'borderColor' => '999999',
            'cellMargin' => 80
        ];
        $phpWord->addTableStyle('Answer Table', $tableStyle);
        $table = $section->addTable('Answer Table');

        $table->addRow();
        $table->addCell(2000)->addText('Q.No', ['bold' => true]);
        $table->addCell(4000)->addText('Answer', ['bold' => true]);

        foreach ($question_paper->exam_question as $index => $question) {
            $table->addRow();
            $table->addCell(2000)->addText($index + 1);
            $answer = isset($question->subject->correct_ans) ? $question->subject->correct_ans : '';
            $table->addCell(4000)->addText($answer);
        }
        $section->addTextBreak();
        $papername = preg_replace('/[^A-Za-z0-9_\-]/', '_', $question_paper->paper_name);
        $filePath = storage_path("app/public/{$papername}.docx");
        $filePath = storage_path("app/public/".$papername.".docx");
        $objWriter = IOFactory::createWriter($phpWord, 'Word2007');
        $objWriter->save($filePath);
        unset($phpWord); // free memory
        if (ob_get_length()) ob_end_clean();

        return response()->download($filePath)->deleteFileAfterSend(true);
    }

    public function downloadwithTemplate($id)
    {
        $question_paper = question_paper::find($id);
    $templatePath = public_path('storage/TemplateA.docx');
    $templateProcessor = new TemplateProcessor($templatePath);

    // Helper to escape XML-breaking chars
    $safe = fn($text) => htmlspecialchars($text ?? '', ENT_QUOTES | ENT_XML1, 'UTF-8');

    // Fill simple fields
    $templateProcessor->setValue('paper_name', $safe($question_paper->paper_name));
    $templateProcessor->setValue('total_questions', $question_paper->exam_question->count());
    $templateProcessor->setValue('time', now()->format('d/m/Y'));

    // ---- Build Question List ----
    $questionText = '';
    foreach ($question_paper->exam_question as $index => $question) {
        $content = json_decode($question->subject->sub_content);

        $title = $question->subject->sub_title ?? '';

        // escape early
        $title = $safe($title);

        // handle multiline
        if (strpos($title, "\n") !== false) {
            $lines = explode("\n", $title);
            $questionText .= ($index + 1) . '. ' . trim(array_shift($lines)) . "\n";
            foreach ($lines as $line) {
                $questionText .= "      " . trim($line) . "\n";
            }
        } else {
            $questionText .= ($index + 1) . '. ' . trim($title) . "\n";
        }

        // sub questions
        foreach ($question->subject->questions as $sub) {
            $questionText .= "      " . $safe($sub->question_title) . "\n";
        }

        $questionText .= "\n";
    }

    // ---- Build Answer Table ----
    $answerTable = "Q.No\tAnswer\n";
    foreach ($question_paper->exam_question as $index => $question) {
        $ans = $safe($question->subject->correct_ans ?? '');
        $answerTable .= ($index + 1) . "\t" . $ans . "\n";
    }

    // ---- Set values properly ----
    // TemplateProcessor expects plain strings. Convert newlines to Word line breaks.
    $templateProcessor->setValue('questions', str_replace("\n", '</w:t><w:br/><w:t>', $safe($questionText)));
    $templateProcessor->setValue('answer_table', str_replace("\n", '</w:t><w:br/><w:t>', $safe($answerTable)));

    // ---- Save ----
    $filePath = storage_path("app/public/{$question_paper->paper_name}.docx");
    $templateProcessor->saveAs($filePath);

    return response()->download($filePath)->deleteFileAfterSend(true);
    }

}
