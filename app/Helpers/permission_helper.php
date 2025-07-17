<?php
use App\Models\RolePermissionModel;
use App\Models\PermissionModel;

if (!function_exists('has_permission')) {
    function has_permission($role_id, $module, $action)
    {
        $store_id = session('store_id');
        error_log('DEBUG: role_id=' . $role_id . ', module=' . $module . ', action=' . $action . ', store_id=' . $store_id);

        // Superadmin ko hamesha true
        $superadminRole = model('App\\Models\\RoleModel')->where('name', 'superadmin')->first();
        if ($superadminRole && $role_id == $superadminRole['id']) {
            return true;
        }

        $permissionModel = new PermissionModel();
        $permission = $permissionModel->where(['module' => $module, 'action' => $action])->first();
        if (!$permission) return false;

        $rolePermissionModel = new RolePermissionModel();
        $rolePermission = $rolePermissionModel->where([
            'role_id' => $role_id,
            'permission_id' => $permission['id'],
            'store_id' => $store_id
        ])->first();

        return $rolePermission ? true : false;
    }
}
