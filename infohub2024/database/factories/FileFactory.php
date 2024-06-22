<?php

namespace Database\Factories;

use App\Models\File;
use App\Models\MenuItems\MenuItem;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class FileFactory extends Factory
{
    protected $model = File::class;

    public function definition()
    {
        return [
            'menu_id' => MenuItem::factory(), // Assuming you have a factory for MenuItem
            'auto_id' => null, // Adjust based on your application's logic
            'add_by' => User::factory(), // Assuming you have a factory for User
            'update_by' => User::factory(), // Assuming you have a factory for User
            'display_order' => $this->faker->numberBetween(1, 100),
            'name' => $this->faker->word . '.' . $this->faker->fileExtension,
            'path' => 'files/' . $this->faker->word . '.' . $this->faker->fileExtension,
            'extension' => $this->faker->fileExtension,
            'weight' => $this->faker->randomFloat(2, 0.1, 100),
            'hosted' => $this->faker->boolean,
            'start' => $this->faker->date('Y-m-d', 'now'),
            'end' => $this->faker->date('Y-m-d', 'now'),
            'key_words' => $this->faker->words(3, true),
            'status' => $this->faker->boolean,
            'archived' => $this->faker->boolean,
            'archived_at' => $this->faker->dateTime('now'),
            'archived_by' => User::factory(), // Assuming you have a factory for User
        ];
    }
}
