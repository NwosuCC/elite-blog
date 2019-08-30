<?php

use App\Role;
use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsersTableSeeder extends Seeder
{
    public static $test_admin = 'claudie9@aol.com';
    public static $test_admin_role = Role::ADMIN;


    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            [
                'name' => $name = 'Claudie',
                'slug' => str_slug($name),
                'email' => static::$test_admin,
                'email_verified_at' => now(),
                'password' => bcrypt('claudie9'),
                'remember_token' => str_random(10),
            ]
        ]);

        // Grant Admin role to seeded user
        if($admin_user = User::where('email', static::$test_admin)->first()){
            if($role = Role::where('rank', static::$test_admin_role)->first()){
                $admin_user->roles()->attach($role);
            }
        }
    }
}
