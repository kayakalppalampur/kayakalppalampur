<?php

use Illuminate\Database\Seeder;
// use DB;

class RomSubCategory extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('rom_sub_category')->truncate();
        DB::table('rom_sub_category')->insert([
            ['rom_joint_id'=>1,'sub_category' => 'Flexion', 'normal_rom'=>'0-80'],
            ['rom_joint_id'=>1,'sub_category' => 'Total flexion / extension', 'normal_rom'=>'0-130'],
            ['rom_joint_id'=>1,'sub_category' => 'Lateral side flexion', 'normal_rom'=>'0-45'],
            ['rom_joint_id'=>1,'sub_category' => 'Rotation to each side', 'normal_rom'=>'0-80'],
            

            ['rom_joint_id'=>2,'sub_category' => 'Flexion', 'normal_rom'=>'0-165'],
            ['rom_joint_id'=>2,'sub_category' => 'Extension', 'normal_rom'=>'0-60'],
            ['rom_joint_id'=>2,'sub_category' => 'Abduction', 'normal_rom'=>'0-170'],
            ['rom_joint_id'=>2,'sub_category' => 'Internal rotation at 90° abduction ', 'normal_rom'=>'0-70'],
            ['rom_joint_id'=>2,'sub_category' => 'External rotation at 90° abduction 	', 'normal_rom'=>'0-100'],
            
            ['rom_joint_id'=>3,'sub_category' => 'Flexion', 'normal_rom'=>'0-145'],
            ['rom_joint_id'=>3,'sub_category' => 'Extension', 'normal_rom'=>'0'],
            ['rom_joint_id'=>3,'sub_category' => 'Pronation', 'normal_rom'=>'0-75'],
            ['rom_joint_id'=>3,'sub_category' => 'Supination', 'normal_rom'=>'0-80'],


            ['rom_joint_id'=>4,'sub_category' => 'Flexion', 'normal_rom'=>'0-75'],
            ['rom_joint_id'=>4,'sub_category' => 'Extension', 'normal_rom'=>'0-75'],
            ['rom_joint_id'=>4,'sub_category' => 'Radial deviation', 'normal_rom'=>'0-20'],
            ['rom_joint_id'=>4,'sub_category' => 'Ulnar deviation', 'normal_rom'=>'0-35'],
            ['rom_joint_id'=>4,'sub_category' => 'Pronation', 'normal_rom'=>'0-75'],
            ['rom_joint_id'=>4,'sub_category' => 'Supination', 'normal_rom'=>'0-80'],
            

            ['rom_joint_id'=>5,'sub_category' => 'IP joint flexion', 'normal_rom'=>'0-80'],
            ['rom_joint_id'=>5,'sub_category' => 'IP joint extension', 'normal_rom'=>'0-20'],
            ['rom_joint_id'=>5,'sub_category' => 'MP joint flexion', 'normal_rom'=>'0-55'],
            ['rom_joint_id'=>5,'sub_category' => 'Carpo-metacarpal abduction', 'normal_rom'=>'0-20'],
            ['rom_joint_id'=>5,'sub_category' => 'Carpo-metacarpal flexion', 'normal_rom'=>'0-15'],


            ['rom_joint_id'=>6,'sub_category' => 'MP joints, flexion', 'normal_rom'=>'0-90'],
            ['rom_joint_id'=>6,'sub_category' => 'MP joints, passive hyperextension', 'normal_rom'=>'Up to 45'],
            ['rom_joint_id'=>6,'sub_category' => 'Proximal IP joints, flexion', 'normal_rom'=>'0-100'],
            ['rom_joint_id'=>6,'sub_category' => 'Distal IP joints, flexion', 'normal_rom'=>'0-80'],	


            ['rom_joint_id'=>7,'sub_category' => 'Thoracic spine flexion', 'normal_rom'=>'0-45'],
            ['rom_joint_id'=>7,'sub_category' => 'Lumbar spine flexion', 'normal_rom'=>'0-60'],
            ['rom_joint_id'=>7,'sub_category' => 'Combined lateral flexion to each side', 'normal_rom'=>'0-30'],
            ['rom_joint_id'=>7,'sub_category' => 'Thoracic spine rotation to each side', 'normal_rom'=>'0-40'],
            

            ['rom_joint_id'=>8,'sub_category' => 'Flexion', 'normal_rom'=>'0-120'],
            ['rom_joint_id'=>8,'sub_category' => 'Extension', 'normal_rom'=>'5-20'],
            ['rom_joint_id'=>8,'sub_category' => 'Abduction', 'normal_rom'=>'0-25'],
            ['rom_joint_id'=>8,'sub_category' => 'Internal rotation at 90° flexion', 'normal_rom'=>'0-45'],
            ['rom_joint_id'=>8,'sub_category' => 'External rotation at 90° flexion', 'normal_rom'=>'0-45'],
            ['rom_joint_id'=>8,'sub_category' => 'Internal rotation in extension', 'normal_rom'=>'0-35'],
            ['rom_joint_id'=>8,'sub_category' => 'External rotation in extension', 'normal_rom'=>'0-45'],

            ['rom_joint_id'=>9,'sub_category' => 'Flexion', 'normal_rom'=>'0-135 +'],
            ['rom_joint_id'=>9,'sub_category' => 'Extension', 'normal_rom'=>'0'],

            ['rom_joint_id'=>10,'sub_category' => 'Dorsiflexion', 'normal_rom'=>'0-15'],
            ['rom_joint_id'=>10,'sub_category' => 'Plantarflexion', 'normal_rom'=>'0-55'],


            ['rom_joint_id'=>11,'sub_category' => 'Inversion of heel', 'normal_rom'=>'0-20'],
            ['rom_joint_id'=>11,'sub_category' => 'Eversion of heel', 'normal_rom'=>'0-10'],
            ['rom_joint_id'=>11,'sub_category' => 'Total supination at forefoot level', 'normal_rom'=>'0-35'],
            ['rom_joint_id'=>11,'sub_category' => 'Total pronation at forefoot level', 'normal_rom'=>'0-20'],


            ['rom_joint_id'=>12,'sub_category' => 'Flexion at MP joint', 'normal_rom'=>'0-40'],
            ['rom_joint_id'=>12,'sub_category' => 'Extension at MP joint', 'normal_rom'=>'0-65'],
            ['rom_joint_id'=>12,'sub_category' => 'Flexion at IP joint', 'normal_rom'=>'0-60'],
            ['rom_joint_id'=>12,'sub_category' => 'Extension at IP joint', 'normal_rom'=>'0']
        ]);
    }
}
