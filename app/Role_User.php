<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Role_User extends Model
{
    protected $table = 'role_user';

    public function role()
    {
        return $this->belongsTo('App\Role', 'role_id');
    }
}
