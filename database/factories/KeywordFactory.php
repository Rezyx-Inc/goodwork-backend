<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
namespace Database\Factories;

use App\Models\Keyword;
use App\Models\User;
use App\Enums\KeywordEnum;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class KeywordFactory extends Factory
{
    protected $model = Keyword::class;

    public function definition()
    {
        return [
            'created_by' => User::factory(),
            'filter' => KeywordEnum::getKey(KeywordEnum::Certification),
            'title' => 'Advanced HIV/AIDS Certified Registered Nurse ' . Str::random(4),
            'description' => null,
            'dateTime' => null,
            'amount' => null,
            'count' => null,
            'active' => true,
        ];
    }
}
