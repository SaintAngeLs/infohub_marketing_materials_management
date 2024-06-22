<?php

namespace Database\Factories;

use App\Models\UsersGroup;
use Illuminate\Database\Eloquent\Factories\Factory;

class UsersGroupFactory extends Factory
{
    protected $model = UsersGroup::class;

    public function definition()
    {
        return [
            'name' => $this->faker->word,
        ];
    }
}
?>
