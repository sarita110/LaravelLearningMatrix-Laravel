<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ConceptFactory extends Factory
{
    public function definition(): array
    {
        $title = fake()->unique()->words(3, true);

        return [
            'title'         => ucwords($title),
            'slug'          => Str::slug($title),
            'description'   => fake()->paragraph(),
            'explanation'   => fake()->paragraphs(3, true),
            'code_example'  => "<?php\n\n// Example code for this concept\necho 'Hello, Laravel!';",
            'code_language' => fake()->randomElement(['php', 'blade', 'bash', 'json']),
            'phase'         => fake()->numberBetween(1, 7),
            'is_published'  => fake()->boolean(70), // 70% chance published
            'view_count'    => fake()->numberBetween(0, 500),
            'category_id'   => Category::factory(),
            'created_by'    => User::factory(),
        ];
    }

    /** State: force is_published = true. */
    public function published(): static
    {
        return $this->state(['is_published' => true]);
    }

    /** State: force is_published = false (draft). */
    public function draft(): static
    {
        return $this->state(['is_published' => false]);
    }

    /** State: pin to a specific phase. */
    public function forPhase(int $phase): static
    {
        return $this->state(['phase' => $phase]);
    }
}
