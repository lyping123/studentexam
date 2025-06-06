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
            $section->addText($data, [
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
            foreach($question->subject->questions as $question){
                // $section->addText("     ".$question->question_title);
                question_text($section, "     " . $question->question_title);
            }
            
            $section->addTextBreak(); // Add spacing
        }
        $papername= $question_paper->paper_name;
        $filePath = storage_path("app/public/".$papername.".docx");
        $objWriter = IOFactory::createWriter($phpWord, 'Word2007');
        $objWriter->save($filePath);

        return response()->download($filePath)->deleteFileAfterSend(true);
    }

    public function downloadwithTemplate($id)
    {
        $question_paper =question_paper::find($id);
        $templatePath = public_path('storage/TemplateA.docx');
        $templateProcessor = new TemplateProcessor($templatePath);
        
        
        $templateProcessor->setValue('paper_name', $question_paper->paper_name);
        $templateProcessor->setValue('total_questions', $question_paper->exam_question->count());
        $templateProcessor->setValue('time', now()->format('d/m/Y'));
        $questionText = "";
        foreach ($question_paper->exam_question as $index => $question) {

            if(strpos($question->subject->sub_title,"\n") !== false){
                $lines = explode("\n", $question->subject->sub_title);
                $questionText .= ($index + 1) . ". " . array_shift($lines) . "\n";
                foreach ($lines as $line) {
                    $questionText .= "\xA0\xA0\xA0\xA0\xA0\xA0" . $line . "\n";
                }
            }else{
                $questionText .= ($index + 1) . ". " . $question->subject->sub_title . "\n";
            }
            
            $content=json_decode($question->subject->sub_content);
            if ($content->type == "picture") {
                $imagePath = public_path($content->content); // Assuming the image path is stored in $content->path
                if (file_exists($imagePath)) {
                    // $questionText .= "\xA0\xA0\xA0\xA0\xA0\xA0[Image: " . $content->path . "]\n"; // Add a placeholder for the image
                    $templateProcessor->setImageValue('image_' . $index, [
                        'path' => $imagePath,
                        'width' => 300, // Adjust width as needed
                        'height' => 200, // Adjust height as needed
                    ]);
                }
            }
            foreach ($question->subject->questions as $q) {
                $questionText .= "\xA0\xA0\xA0\xA0\xA0\xA0" . $q->question_title . "\n";
            }
            $questionText .= "\n"; // Add spacing between questions
        }

        $templateProcessor->setValue('questions', $questionText);

        // ✅ Save the updated document
        $filePath = storage_path("app/public/{$question_paper->paper_name}.docx");
        $templateProcessor->saveAs($filePath);
        return response()->download($filePath)->deleteFileAfterSend(true);
    }
}
