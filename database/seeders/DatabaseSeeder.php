<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

//        User::factory()->create([
//            'name' => 'Test User',
//            'email' => 'test@example.com',
//        ]);

        $user = new User();
        $user->firstname = "Developer";
        $user->lastname = "Developer";
        $user->email = "developer123@gmail.com";
        $user->gender = "Male";
        $user->phone_number = "0965051014";
        $user->address = "Takev";
        $user->position_id = null;
        $user->password = Hash::make('developer@123');
        $user->save();

        Company::create([
           "name" => "Space X",
           "email" => "spacex168@gmail.com",
           "phone_number" => "0123456789",
        ]);


        $developer = Role::create(['name' => 'developer']);
        $ceo = Role::create(['name' => 'ceo']);
        $admin = Role::create(['name' => 'admin']);
        $employee = Role::create(['name' => 'employee']);

        $fullControl = Permission::create(['name' => 'fullControl']);
        $manageEmployee = Permission::create(['name' => 'manageEmployee']);
        $manageQrCode = Permission::create(['name' => 'manageQrCode']);
        $managePosition = Permission::create(['name' => 'managePosition']);

        $developer->givePermissionTo($fullControl);
        $ceo->givePermissionTo($manageEmployee);
        $admin->givePermissionTo($manageQrCode);
        $ceo->givePermissionTo($managePosition);

        $admin->givePermissionTo($manageEmployee);
        $admin->givePermissionTo($manageQrCode);

        $user = User::find(1);
        $user->assignRole($developer);
    }
}
