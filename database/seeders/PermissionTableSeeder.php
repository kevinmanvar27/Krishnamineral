<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            'view-employees',
            'add-employee',
            'edit-employees',
            'view-sales',
            'add-sale',
            'edit-sales',
            'pending-load-sales',
            'audit-sales',
            'rate-sales',
            'statement-sales',
            'view-carting',
            'edit-carting',
            'view-materials',
            'add-material',
            'edit-materials',
            'view-places',
            'add-place',
            'edit-places',
            'view-vehicles',
            'add-vehicle',
            'edit-vehicles',
            'view-royalty',
            'add-royalty',
            'edit-royalty',
            'view-party',
            'add-party',
            'edit-party',
            'view-loading',
            'add-loading',
            'edit-loading',
            'view-driver',
            'add-driver',
            'edit-driver',
            'view-purchase',
            'add-purchase',
            'edit-purchase',
            'pending-load-purchase',
            'view-purchaseQuarry',
            'add-purchaseQuarry',
            'edit-purchaseQuarry',
            'view-purchaseMaterials',
            'add-purchaseMaterials',
            'edit-purchaseMaterials',
            'view-purchaseVehicles',
            'add-purchaseVehicles',
            'edit-purchaseVehicles',
            'view-purchaseLoading',
            'add-purchaseLoading',
            'edit-purchaseLoading',
            'view-purchaseReceiver',
            'add-purchaseReceiver',
            'edit-purchaseReceiver',
            'view-purchaseDriver',
            'add-purchaseDriver',
            'edit-purchaseDriver',
            'view-attendance',
            'add-attendance',
            'view-activity-log',
            'view-blasting',
            'add-blasting',
            'edit-blasting',
            'view-blasterName',
            'add-blasterName',
            'edit-blasterName',
            'view-drilling',
            'add-drilling',
            'edit-drilling',
            'view-drillingName',
            'add-drillingName',
            'edit-drillingName',
            'view-vendor',
            'add-vendor',
            'edit-vendor'
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(
                ['name' => $permission, 'guard_name' => 'web']
            );
        }
    }
}