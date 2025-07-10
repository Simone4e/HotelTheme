<?php

namespace Database\Factories;

use App\Models\Room;
use App\Enum\ReservationStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Reservation>
 */
class ReservationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $faker = fake();
        $checkIn = now()->addDays(fake()->numberBetween(0, 15));
        $checkOut = (clone $checkIn)->addDays(fake()->numberBetween(1, 7));
        return [
            'name_client' => $faker->name(),
            'email' => $faker->email(),
            'phone' => $faker->phoneNumber(),
            'room_id' => Room::inRandomOrder()->first()?->id ?? Room::factory(),
            'date_checkin' => $checkIn,
            'date_checkout' => $checkOut,
            'messages' => $faker->sentence(rand(2, 10)),
            'status' => $faker->randomElement(ReservationStatus::cases())->value
        ];
    }

    /**
     * Imposto un id specifico
     *
     * @return static
     */
    public function roomId(int $id): static
    {
        return $this->state(function (array $attributes) use ($id) {
            return [
                'room_id' => $id
            ];
        });
    }

    /**
     * Metodi per sovrascrivere lo status
     *
     * @return static
     */
    public function pending(): static
    {
        return $this->state(fn() => ['status' => ReservationStatus::PENDING->value]);
    }

    public function confirmed(): static
    {
        return $this->state(fn() => ['status' => ReservationStatus::CONFIRMED->value]);
    }

    public function canceled(): static
    {
        return $this->state(fn() => ['status' => ReservationStatus::CANCELED->value]);
    }
}
