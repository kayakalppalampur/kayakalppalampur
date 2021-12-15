<?php

use Illuminate\Database\Seeder;
// use DB;

class RomJointSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
            DB::table('rom_joint')->truncate();
        DB::table('rom_joint')->insert([
            ['id'=>1,'joint_name' => 'Cervical Spine'],
            ['id'=>2,'joint_name' => 'Shoulder'],
            ['id'=>3,'joint_name' => 'Elbow'],
            ['id'=>4,'joint_name' => 'Wrist'],
            ['id'=>5,'joint_name' => 'Thumb'],
            ['id'=>6,'joint_name' => 'Fingers'],
            ['id'=>7,'joint_name' => 'Thoracolumbar Spine'],
            ['id'=>8,'joint_name' => 'Hip'],
            ['id'=>9,'joint_name' => 'Knee'],
            ['id'=>10,'joint_name' => 'Ankle'],
            ['id'=>11,'joint_name' => 'Foot'],
            ['id'=>12,'joint_name' => 'Great Toe']
        ]);
    }
}
