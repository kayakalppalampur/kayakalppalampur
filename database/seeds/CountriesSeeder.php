<?php

use Illuminate\Database\Seeder;

class CountriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Eloquent::unguard();
        DB::table('countries')->truncate();
        DB::table('cities')->truncate();
        DB::table('states')->truncate();
        $path1 = 'app/dev_docs/countries.sql';
        $path2 = 'app/dev_docs/states.sql';
        $path3 = 'app/dev_docs/cities.sql';
        DB::unprepared(file_get_contents($path1));
        DB::unprepared(file_get_contents($path2));
        DB::unprepared(file_get_contents($path3));
        $this->command->info('Country state cities table seeded!');
    }
}
