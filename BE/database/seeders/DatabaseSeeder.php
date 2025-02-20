<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Listing;
use App\Models\Transaction;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Factories\Sequence;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        User::factory()->create([
            'name' => 'admin',
            'email' => 'admin@admin.com',
            'role' => 'admin',
            // 'password' => bcrypt('123456789'),
        ]);
        $user = User::factory(10)->create();
        $listings = Listing::factory(10)->create();

        Transaction::factory(10)->state(
            new Sequence(
                fn(
                    Sequence $sequence
                ) => [
                    'user_id' => $user->random(),
                    'listing_id' => $listings->random(),
                ]
            )
        )->create();
    }
}
