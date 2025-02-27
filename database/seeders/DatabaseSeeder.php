<?php

namespace Database\Seeders;

use App\Models\course;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();
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
        User::factory()->create([
            'name' => 'abc',
            'email' => 'abc@example.com',
            'password'=> '123',
            'role' => 'admin',
            'course_id' => 1,
        ]);

        
    }
}
