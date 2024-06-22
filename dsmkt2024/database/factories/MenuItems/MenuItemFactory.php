<?php

namespace Database\Factories\MenuItems;

use App\Models\MenuItems\MenuItem;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class MenuItemFactory extends Factory
{
    protected $model = MenuItem::class;

    public function definition()
    {
        return [
            'name' => $this->faker->word,
            'slug' => $this->faker->slug,
            'parent_id' => null,
            'user_id' => User::factory(),
            'banner_id' => 1,
            'position' => $this->faker->numberBetween(1, 10),
            'start' => $this->faker->date,
            'end' => $this->faker->date,
            'status' => 1,
            'archived' => false,
            'archived_at' => null,
            'archived_by' => null,
        ];
    }
}
?>
