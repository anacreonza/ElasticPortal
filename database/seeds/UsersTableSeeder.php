<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => 'Admin',
            'email' => 'admin@media24.com',
            'password' => bcrypt('Media24Def@ult'),
            'created_at' => Carbon::now(),
            'updated_at' => carbon::now(),
            'role_id' => '1'
        ]);
    }
}
