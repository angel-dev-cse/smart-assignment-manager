<?php

namespace Database\Factories;

use App\Models\Course;
use App\Models\Teacher;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Teach>
 */
class TeachFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'teacher_id' => Teacher::factory(),
            'course_id' => Course::factory(),
            'status' => $this->faker->randomElement(['approved', 'pending', 'declined']),
        ];
    }
}
