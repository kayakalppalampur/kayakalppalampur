<?php

use Illuminate\Database\Seeder;

class AdminSettingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('admin_settings')->truncate();
         DB::table('admin_settings')->insert([
             ['setting_name'=>'Advance Payment','setting_name_slug' => 'advance_payment', 'price'=>'2000'],
            ['setting_name'=>'Consultation charges','setting_name_slug' => 'consultation_charges', 'price'=>'500'],
            ['setting_name'=>'Child','setting_name_slug' => 'child', 'price'=>'200'],
            ['setting_name'=>'Driver','setting_name_slug' => 'driver', 'price'=>'200'],
        ]);
    }
}
