<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        DB::table('administrators')->insert([
            [
                'name' => 'Super Admin',
                'avatar' => 'https://static.wikia.nocookie.net/worldtrigger/images/b/b4/ReplicaAnime.jpg/revision/latest?cb=20220101231904',
                'email' => 'truyenstudy1a@gmail.com',
                'phone' => '0375761577',
                'active' => 1,
                'role' => 0,
                'password' => Hash::make('admin123'),
            ]
        ]);
    }
}
