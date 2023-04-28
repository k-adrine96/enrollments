<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Course;
use App\Models\Enrollment;
use Illuminate\Database\Seeder;

class EnrollmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
        Course::all()->map(function ($course) {
            // $status = Enrollment::STATUS;
            $status = ['in progress', 'completed'];
            for ($i=0; $i < rand(20, 40); $i++) { 
                $course->users()->attach(User::find(rand(1, 50)), ['status' => $status[rand(0,1)]]);
            }
        });

    }
}
