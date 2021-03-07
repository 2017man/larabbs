<?php

namespace Database\Factories;

use App\Models\Topic;
use Illuminate\Database\Eloquent\Factories\Factory;

class TopicFactory extends Factory
{
    protected $model = Topic::class;

    public function definition()
    {
        $sentence    = $this->faker->sentence();
        $userIds     = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10];
        $categoryIds = [1, 2, 3, 4];

        return [
            'title'       => $sentence,
            'body'        => $this->faker->text(),
            'excerpt'     => $sentence,
            'user_id'     => $this->faker->randomElement($userIds),
            'category_id' => $this->faker->randomElement($categoryIds),
        ];
    }
}
