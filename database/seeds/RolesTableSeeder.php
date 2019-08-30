<?php

use App\Role;
use App\User;
use Illuminate\Support\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      DB::table('roles')->insert(
        [
          [
            'name' => 'SuperAdmin',
            'rank' => 1,
            'slug' => 'super-admin',
            'description' => 'Head',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
          ],
          [
            'name' => 'Admin',
            'rank' => 2,
            'slug' => 'admin',
            'description' => 'Ops',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
          ],
          [
            'name' => 'Member',
            'rank' => 3,
            'slug' => 'member',
            'description' => 'Registered user',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
          ],
          [
            'name' => 'Guest',
            'rank' => 0,
            'slug' => 'guest',
            'description' => 'Non-registered user',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
          ],
        ]
      );

    }
}
