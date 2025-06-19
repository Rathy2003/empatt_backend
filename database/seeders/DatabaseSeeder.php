<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\Position;
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
        // developer user
        $devUser = new User();
        $devUser->firstname = "Developer";
        $devUser->lastname = "Developer";
        $devUser->email = "developer123@gmail.com";
        $devUser->gender = "Male";
        $devUser->phone_number = "0965051014";
        $devUser->address = "Takev";
        $devUser->position_id = null;
        $devUser->password = Hash::make('developer@123');
        $devUser->save();


        Company::create([
           "name" => "Electronic.INC Cambodia",
           "email" => "electronic.inc.kh@gmail.com",
           "phone_number" => "012 345 6789",
        ]);

        // ceo user
        $ceoUser = new User();
        $ceoUser->firstname = "Sok";
        $ceoUser->lastname = "Dara";
        $ceoUser->email = "sokdara@gmail.com";
        $ceoUser->gender = "Male";
        $ceoUser->phone_number = "011 345 6789";
        $ceoUser->address = "Steng Meanchey, Phnom Penh";
        $ceoUser->position_id = null;
        $ceoUser->password = Hash::make('12345678');
        $ceoUser->company_id = 1;
        $ceoUser->save();

        // position
        Position::create([
            "name" => "IT Manager",
            "company_id" => 1
        ]);
        Position::create([
            "name" => "Engineer",
            "company_id" => 1
        ]);
        Position::create([
            "name" => "Accountant",
            "company_id" => 1
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

        $devUser->assignRole($developer);
        $ceoUser->assignRole($ceo);
    }
}
