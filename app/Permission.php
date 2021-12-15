<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    protected $table = "permissions";

    public function roles()
    {
        return $this->belongsToMany('App\Role');
    }

    public function getRoleNames($attr = 'name')
    {
        $names = [];
        foreach ($this->roles as $role) {
            $names[] = $role->$attr;
        }
        return implode(',', $names);
    }

    public function isChecked($id)
    {
        $roles = explode(',', $this->getRoleNames('id'));
        if(in_array($id, $roles)) {
            return "checked";
        }
        return "";
    }

    public function deleteOldRoles()
    {
        $perm_roles = Permission_Role::where('permission_id', $this->id)->get();
        foreach ($perm_roles as $role) {
            $role->delete();
        }
    }

    public function saveRoles($request)
    {
        foreach ($request->get('roles') as $key => $role) {
            Permission_Role::firstOrcreate([
                'role_id' => $role,
                'permission_id' => $this->id
            ]);
        }
    }

    public static function getRoutesArray()
    {
        return [
            'Laralum::permissions',
            'Laralum::permissions_create',
            'Laralum::permissions_edit',
            'Laralum::permissions_roles_edit',
            'Laralum::permissions_delete',
        ];
    }
}
