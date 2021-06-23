<?php
namespace SoapBox\SerializedPayloads\Factories;

use SoapBox\SerializedPayloads\Payload;
use Illuminate\Database\Eloquent\Factories\Factory;

class PayloadFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Payload::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'data' => '',
        ];
    }

    public function unprocessed()
    {
        return $this->state(function (array $attributes) {
            return [
                'processed_at' => null,
            ];
        });
    }

    public function recentlyProcessed()
    {
        return $this->state(function (array $attributes) {
            return [
                'processed_at' => now(),
            ];
        });
    }

    public function shouldDelete()
    {
        return $this->state(function (array $attributes) {
            return [
                'processed_at' => now()->subDay()->subSecond(),
            ];
        });
    }
}
