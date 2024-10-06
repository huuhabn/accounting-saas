<?php

namespace Database\Seeders;

use App\Models\User;
use Database\Factories\CompanyFactory;
use Illuminate\Database\Seeder;

class BusinessSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create a single admin user and their personal company
        $adminUser = User::factory()
            ->withPersonalCompany(function (CompanyFactory $factory) {
                return $factory->withTransactions();
            })
            ->create([
                'name' => 'Admin',
                'email' => 'huuhadev@gmail.com',
                'password' => bcrypt('password'),
                'current_company_id' => 1,  // Assuming this will be the ID of the created company
                'created_at' => now(),
            ]);

        // Optionally, set additional properties or create related entities specific to this company
        $adminUser->ownedCompanies->first()->update([
            'name' => 'MNC Company',
            'created_at' => now(),
        ]);
    }
}
