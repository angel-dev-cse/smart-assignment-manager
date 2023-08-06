<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        \App\Models\User::factory(25)->state(['role' => 'student'])->create()->each(function ($user) {
            $user->student()->save(\App\Models\Student::factory()->make());
        });
        
        \App\Models\User::factory(25)->state(['role' => 'teacher'])->create()->each(function ($user) {
            $user->teacher()->save(\App\Models\Teacher::factory()->make());
        });
        

        \App\Models\Department::factory(5)->create();
        \App\Models\Course::factory(20)->create();
        \App\Models\Enrollment::factory(30)->create();
        \App\Models\Teach::factory(20)->create();
    }
}
