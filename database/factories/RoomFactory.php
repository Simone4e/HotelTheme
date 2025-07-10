<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Room>
 */
class RoomFactory extends Factory
{

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $faker = fake();
        $peoples = $faker->numberBetween(2, 4);
        $beds = $peoples > 2 ? 2 : 1;

        return [
            'name' => $faker->bothify('Room ##'),
            'description' => $faker->paragraph(),
            'price' => $faker->randomFloat(2, 50, 300),
            'meters' => $faker->numberBetween(40, 60),
            'beds' => $beds,
            'peoples' => $peoples,
            'preview' => '/rooms/default.jpg',
            'actived' => true
        ];
    }

    /**
     * Disattivo activated se no voglio disattivate
     *
     * @return static
     */
    public function deactive(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'actived' => false
            ];
        });
    }
}
