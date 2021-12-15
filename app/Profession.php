<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Profession extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    const IS_PRIVATE = 1;
    protected $fillable = [
        'name', 'slug', 'is_private'
    ];

    public static function getDepartmentsDropdown($id = null)
    {
        $professions = Profession::orderBy('id','asc')->whereNull('is_private')->pluck('name','id');

        if ($id != null) {
            $profile = UserProfile::find($id);
            if ($profile != null) {
                $pro = Profession::find($profile->profession_id);
               if ($pro != null) {
                   if ($pro->is_private == self::IS_PRIVATE) {
                       $pid = $pro->id;
                       $professions = Profession::orderBy('id', 'asc')->whereNull('is_private')->orWhere('id', $pid)->pluck('name', 'id');
                   }
               }
            }
        }

        return $professions;
    }

    public function profiles()
    {
        return $this->hasMany('App\UserProfile', 'profession_id');
    }

    public function customDelete()
    {
        $profiles = $this->profiles;
        if ($profiles->count() > 0) {
            foreach ($profiles as $profile) {
                $profile->update([
                    'profession_id' => null
                ]);
            }
        }

        $this->delete();
        return true;
    }

    public static function getRules()
    {
        return [
            'name' => 'required'
        ];
    }

    public function setData($data)
    {
        $this->name = $data->get('name');
        $this->slug = $data->get('slug');
        return $this;
    }

    public static function getRoutesArray()
    {
        return [
            'Laralum::professions',
            'Laralum::profession_create',
            'Laralum::profession_edit',
            'Laralum::professions.view',
            'Laralum::profession_delete'
        ];
    }
}
