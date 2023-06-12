<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // User::truncate();
        // DB::table('role_user')->truncate();

        $admin = User::create([
            'name' => 'Soukeur',
            'email' => 'soukeur@gmail.com',
            'password' => Hash::make('password')
        ]);
        $coordinateur = User::create([
            'name' => 'Haichour',
            'email' => 'haichour@gmail.com',
            'password' => Hash::make('password')
        ]);
        $inspecteur = User::create([
            'name' => 'Kahal',
            'email' => 'kahal@gmail.com',
            'password' => Hash::make('password')
        ]);
        $secrétaire = User::create([
            'name' => 'Moussaoui',
            'email' => 'moussaoui@gmail.com',
            'password' => Hash::make('password')
        ]);

        $admin_role = Role::where('role','admin')->first();
        $coordinateur_role = Role::where('role','Coordinateur gestion social')->first();
        $inspécteur_role = Role::where('role','Inspécteur de prévention')->first();
        $secrétaire_role = Role::where('role','Secrétaire médicale')->first();

        $admin->roles()->attach($admin_role);
        $coordinateur->roles()->attach($coordinateur_role);
        $inspecteur->roles()->attach($inspécteur_role);
        $secrétaire->roles()->attach($secrétaire_role);

    }
}
