<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        //Create Divisions
        $path = base_path('database/seeders/divisions.sql');
        $this->command->info("Seeding $path");
        DB::unprepared(file_get_contents($path));
        $this->command->info('Divisions Seeded!');

        //Create Cities
        $path = base_path('database/seeders/cities.sql');
        $this->command->info("Seeding $path");
        DB::unprepared(file_get_contents($path));
        $this->command->info('Cities Seeded!');

        //Create Categories
        $path = base_path('database/seeders/categories.sql');
        $this->command->info("Seeding $path");
        DB::unprepared(file_get_contents($path));
        $this->command->info('Categories Seeded!');

        //Create SubCategories
        $path = base_path('database/seeders/subcategories.sql');
        $this->command->info("Seeding $path");
        DB::unprepared(file_get_contents($path));
        $this->command->info('Sub Categories Seeded!');

        Schema::disableForeignKeyConstraints();
        $this->command->info("Emptying Tables..");
        DB::table("featureds")->truncate();
        DB::table("postimages")->truncate();
        DB::table("favourites")->truncate();
        DB::table("posts")->truncate();
        DB::table("users")->truncate();
        Schema::enableForeignKeyConstraints();

        $this->command->info("Emptying Image Folders..");
        /* Delete previous images*/
        $files = glob('public/images/*');
        foreach ($files as $file) {
            if (is_file($file))
                unlink($file);
        }
        /* Delete previous thumbnails*/
        $files = glob('public/images/thumb/*');
        foreach ($files as $file) {
            if (is_file($file))
                unlink($file);
        }

    }
}
