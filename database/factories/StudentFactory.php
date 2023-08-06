<?php

namespace Database\Factories;

use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Student>
 */
class StudentFactory extends Factory
{
    protected $model = Student::class;

    public function definition()
    {
        return [
            'user_id' => \App\Models\User::factory(),
            'department_id' => \App\Models\Department::factory(),
            'roll' => $this->faker->unique()->numberBetween(1000, 9999),
            'semester' => $this->faker->numberBetween(1, 8),
            'session' => $this->faker->randomElement(['2019-2020', '2020-2021', '2021-2022']),
        ];
    }
}
