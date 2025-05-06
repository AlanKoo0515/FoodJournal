<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Recipe;
use App\Models\CulinaryExperience;
use App\Models\Review;
use App\Models\Comment;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::create([
            'username' => 'John Doe',
            'email' => 'john@example.com',
            'password' => bcrypt('password123'),
            'profile_picture' => 'https://via.placeholder.com/150',
            'bio' => 'A food enthusiast who loves to share recipes and culinary experiences.',
        ]);

        Recipe::create([
            'user_id' => 1, // Assuming user ID 1 exists in the users table
            'title' => 'Spaghetti Bolognese',
            'ingredients' => 'Spaghetti, Tomato Sauce, Ground Beef, Onion',
            'instructions' => 'Boil spaghetti, cook beef, add sauce and mix.',
            'calories_per_serving' => 500,
            'servings' => 4,
            'cook_time' => 30,
            'image_url' => 'https://via.placeholder.com/300x200',
        ]);

        CulinaryExperience::create([
            'user_id' => 1, // Assuming user ID 1 exists in the users table
            'title' => 'My First Cooking Class',
            'description' => 'I attended a cooking class where I learned how to make traditional pasta.',
            'location' => 'Cooking School, New York',
            'category' => 'Cooking Class',
            'date' => '2025-05-01',
            'image_url' => 'https://via.placeholder.com/300x200',
        ]);

        Review::create([
            'user_id' => 1, // Assuming user ID 1 exists in the users table
            'recipe_id' => 1, // Assuming recipe ID 1 exists in the recipes table
            'rating' => 5,
            'comment' => 'Delicious! One of the best spaghetti bolognese I have made.',
        ]);

        Comment::create([
            'user_id' => 1, // Assuming user ID 1 exists in the users table
            'review_id' => 1, // Assuming review ID 1 exists in the reviews table
            'content' => 'I agree, this is absolutely amazing!',
        ]);
    }
}
