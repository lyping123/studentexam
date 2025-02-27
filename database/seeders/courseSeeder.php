<?php

namespace Database\Seeders;

use App\Models\course;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class courseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $courses = [
                "Pragramming",
                 "Networking",
                "Multimedia",
                "Electronics",
                 "Accounting"
        ];

        foreach ($courses as $course) {
            course::create([
                "course_name" => $course,
            ]);
        }
        
    }
}
