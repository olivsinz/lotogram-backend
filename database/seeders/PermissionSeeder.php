<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Permission;

class PermissionSeeder extends Seeder
{
    protected $permissionList = [
        // User
        ['name' => 'user.store'],
        ['name' => 'user.update'],
        ['name' => 'user.destroy'],
        ['name' => 'user.index'],
        ['name' => 'user.show'],
        ['name' => 'user.add-user-group'],
        ['name' => 'user.get-role'],
        ['name' => 'user.assign-role'],
        ['name' => 'user.revoke-role'],
        ['name' => 'user.get-permission'],
        ['name' => 'user.assign-permission'],
        ['name' => 'user.revoke-permission'],
        ['name' => 'user.history'],
        ['name' => 'user.get-method'],
        ['name' => 'user.assign-method'],
        ['name' => 'user.revoke-method'],
        ['name' => 'user.get-site'],
        ['name' => 'user.assign-site'],
        ['name' => 'user.revoke-site'],
        ['name' => 'user.get-ip_address'],
        ['name' => 'user.assign-ip_address'],
        ['name' => 'user.revoke-ip_address'],

        // Role
        ['name' => 'role.store'],
        ['name' => 'role.update'],
        ['name' => 'role.destroy'],
        ['name' => 'role.index'],
        ['name' => 'role.show'],
        ['name' => 'role.history'],
        ['name' => 'role.get-permission'],
        ['name' => 'role.assign-permission'],
        ['name' => 'role.revoke-permission'],

        // Permission
        ['name' => 'permission.index'],

        //Method
        ['name' => 'method.update'],
        ['name' => 'method.index'],
        ['name' => 'method.show'],
        ['name' => 'method.history'],
        ['name' => 'method.destroy'],

        //Setting
        ['name' => 'setting.index'],
        ['name' => 'setting.update'],
        ['name' => 'setting.history'],

        // Tag
        ['name' => 'tag.store'],
        ['name' => 'tag.update'],
        ['name' => 'tag.destroy'],
        ['name' => 'tag.index'],
        ['name' => 'tag.show'],
        ['name' => 'tag.history'],

        // Planned Competition
        ['name' => 'planned-competition.index'],
        ['name' => 'planned-competition.store'],
        ['name' => 'planned-competition.show'],
        ['name' => 'planned-competition.update'],
        ['name' => 'planned-competition.assign-reward'],
        ['name' => 'planned-competition.get-reward'],
        ['name' => 'planned-competition.edit-reward'],
        ['name' => 'planned-competition.revoke-reward'],
    ];

    public function run(): void
    {
        foreach ($this->permissionList as $permission)
        {
            Permission::firstOrCreate(['name' => $permission['name']],
            [
                'name' => $permission['name'],
            ]);
        }

    }
}
