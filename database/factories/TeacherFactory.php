<?php

namespace Database\Factories;

use App\Models\Teacher;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Teacher>
 */
class TeacherFactory extends Factory
{
    protected $model = Teacher::class;

    public function definition()
    {
        return [
            'user_id' => \App\Models\User::factory(),
            'department_id' => \App\Models\Department::factory(),
            'qualification' => $this->faker->sentence,
        ];
    }
}
