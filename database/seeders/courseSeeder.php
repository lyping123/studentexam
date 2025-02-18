<?php

namespace Database\Seeders;

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
                "course_name" => "Pragramming",
                "course_name" => "Networking",
                "course_name" => "Multimedia",
                "course_name" => "Electronics",
                "course_name" => "Accounting",
        ];

        foreach ($courses as $course) {
            \App\Models\Course::create($course);
        }
    }
}
