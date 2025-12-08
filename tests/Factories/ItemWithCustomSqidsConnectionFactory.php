<?php

namespace ErikSulymosi\EloquentSqids\Tests\Factories;

use ErikSulymosi\EloquentSqids\Tests\Models\ItemWithCustomSqidsConnection;
use ErikSulymosi\EloquentSqids\Tests\Models\Vendor;
use Illuminate\Database\Eloquent\Factories\Factory;

class ItemWithCustomSqidsConnectionFactory extends Factory
{
	/**
     * The name of the factory's corresponding model.
     *
     * @var class-string<\Illuminate\Database\Eloquent\Model>
     */
    protected $model = ItemWithCustomSqidsConnection::class;
	
	/**
	 * Define the model's default state.
	 * @return array
	 */
	public function definition()
	{
		return [
			'slug' => fake()->unique()->word(),
			'vendor_id' => fn () => Vendor::factory()->create(),
		];
	}

	/**
	 * Indicate that the item is deleted.
	 */
	public function softDeleted(): Factory
	{
		return $this->state(fn() => [
			'deleted_at' => now(),
		]);
	}
}
