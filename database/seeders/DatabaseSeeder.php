<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            CategorySeeder::class,
            UnitSeeder::class,
            SupplierSeeder::class,
            CustomerSeeder::class,
            ProductSeeder::class,
            TransactionSeeder::class,
            StockHistorySeeder::class,
            StockTriggerSeeder::class,
            // NotificationSeeder::class,
            // StockAlertSeeder::class,
        ]);
    }
}
