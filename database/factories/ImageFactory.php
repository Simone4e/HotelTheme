<?php

namespace Database\Factories;

use App\Models\Image;
use App\Models\Room;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Image>
 */
class ImageFactory extends Factory
{
    protected $model = Image::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'path' => 'images/' . $this->faker->word . '.jpg',
            'descrizione' => $this->faker->sentence(),
            'room_id' => Room::factory(),
        ];
    }
}
