<?php

namespace Database\Seeders;

use App\Models\TaskStatus;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        TaskStatus::insert([
            ['name' => 'Новый', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'В работе', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'На тестировании', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Завершен', 'created_at' => now(), 'updated_at' => now()]
        ]);
    }
}
