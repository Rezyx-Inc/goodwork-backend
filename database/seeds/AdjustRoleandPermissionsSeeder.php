<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\NuRole;
use App\Models\NuPermission;

class AdjustRoleandPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $role = NuRole::findByName('Administrator');
        $role->givePermissionTo('admin-delete');
        $role->givePermissionTo('worker-show');
        $role->givePermissionTo('worker-create');
        $role->givePermissionTo('worker-delete');
        $role->givePermissionTo('worker-edit');

        $facility = NuRole::findByName('Facility');
        $facility->revokePermissionTo('admin-create');
        $facility->revokePermissionTo('admin-edit');
        $facility->revokePermissionTo('admin-show');
        $facility->revokePermissionTo('delete');
        $facility->revokePermissionTo('create');
        $facility->revokePermissionTo('edit');

        $admin = NuRole::findByName('Admin');
        $admin->revokePermissionTo('admin-delete');
        $admin->revokePermissionTo('delete');
        $admin->givePermissionTo('worker-show');
        $admin->givePermissionTo('worker-create');
        $admin->givePermissionTo('worker-delete');
        $admin->givePermissionTo('worker-edit');

        $supervisor = NuRole::findByName('Supervisor');
        $supervisor->revokePermissionTo('admin-create');
        $supervisor->revokePermissionTo('admin-edit');
        $supervisor->revokePermissionTo('admin-show');
        $supervisor->revokePermissionTo('create');
        $supervisor->revokePermissionTo('delete');
        $supervisor->revokePermissionTo('edit');
        $supervisor->givePermissionTo('worker-show');
        $supervisor->givePermissionTo('worker-create');
        $supervisor->givePermissionTo('worker-delete');
        $supervisor->givePermissionTo('worker-edit');
    }
}
