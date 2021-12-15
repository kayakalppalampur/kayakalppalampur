<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RomSubCategory extends Model
{
    protected $table= "rom_sub_category";

    public static function getSubCatByJointID($joint_id){
    	return RomSubCategory::where('rom_joint_id',$joint_id)->get();
    }

    public static function getNormalByID($id){
    	return RomSubCategory::where('id',$id)->pluck('normal_rom')->first();
    }
}
