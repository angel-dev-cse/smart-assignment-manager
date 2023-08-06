<?php

namespace Database\Factories;

use App\Models\Course;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Course>
 */
class CourseFactory extends Factory
{
    protected $model = Course::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'department_id' => \App\Models\Department::factory(),
            'course_name' => $this->faker->unique()->words(3, true),
            'course_code' => $this->faker->unique()->word,
            'semester' => $this->faker->numberBetween(1, 8),
        ];
    }
}
