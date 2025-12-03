<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Attendance>
 */
class AttendanceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'employee_id' => User::factory(),
            'type_attendance' => $this->faker->numberBetween(1, 3),
            'extra_hours' => $this->faker->numberBetween(0, 8),
            'driver_tuck_trip' => $this->faker->numberBetween(0, 10),
            'date' => $this->faker->date(),
            'status' => 1,
        ];
    }
}