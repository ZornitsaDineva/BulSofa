<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Faker\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $faker = Factory::create();
        $faker->locale('en_GB');

        $numberOfUsers = 329;
        $numberOfAds = 329;
        $numberOfPromoted = ceil($numberOfAds / 5);

        $this->command->info("Generating $numberOfUsers fake users..");

        //DB::table('Users')->delete()

        $pass = Hash::make('123456');

        for ($nc = 1; $nc <= $numberOfUsers; $nc++) {


            \App\Models\User::create([
                'name' => $faker->name,
                'email' => $nc . $faker->email,
                'password' => $pass,
                'mobile' => $faker->phoneNumber,
                'user_type' => $faker->numberBetween(0, 1),
                'city_id' => $faker->numberBetween(1, 329),
                'user_balance' => "2000"

            ]);
        }

        $this->command->info("Generating $numberOfAds fake posts..");

        for ($index = 1; $index <= $numberOfAds; $index++) {

            $rndUser = $faker->numberBetween(1, $numberOfUsers);
            $imgCount = $faker->numberBetween(2, 4);

            $types = ['New', 'Used'];

            $postId = \App\Models\Post::create([
                'user_id' => $rndUser,
                'city_id' => $faker->numberBetween(1, 329),
                'subcategory_id' => $faker->numberBetween(2, 65),
                'ad_type' => "newsell",
                'ad_title' => "newsell",
                'item_condition' => $types[$faker->numberBetween(0, 1)],
                'item_price' => $faker->randomFloat(2, 10, 500000),
                'price_negotiable' => '0',
                'delivery' => "In Person",
                'status' => 1,
                'views' => mt_rand(0, 1000),
                'short_description' => "loren ipsum",
                'long_description' => "loren ipsum long....",
                'created_at' => $faker->dateTimeBetween('-5 months', 'now')
            ])->post_id;


            for ($i = 1; $i <= $imgCount; $i++) {

                $filename = uniqid();
                $randomImageFile = mt_rand(1, 10);

                $tempPath = base_path("resources/images/" . $randomImageFile . ".jpeg");
                $newPath = base_path("public/images/" . $rndUser . "_" . "$filename.jpeg");

                //move file
                copy($tempPath, $newPath);

                $tempPathThumb = base_path("resources/images/thumb/" . $randomImageFile . ".jpeg");
                $newPathThumb = base_path("public/images/thumb/" . $rndUser . "_" . "$filename.jpeg");

                //move thumbnail
                copy($tempPathThumb, $newPathThumb);

                \App\Models\Postimage::create([
                    "post_id" => $postId,
                    "postimage_file" => "images/" . $rndUser . "_" . "$filename.jpeg",
                    "postimage_thumbnail" => "images/thumb/" . $rndUser . "_" . "$filename.jpeg"
                ]);
            }
        }

        /* Promote some random ads */

        $this->command->info("Generating $numberOfPromoted Promoted posts..");


        for ($pc = 0; $pc < $numberOfPromoted; $pc++) {

            \App\Models\Featured::create([
                'post_id' => $faker->numberBetween(1, $numberOfAds),
                'created_at' => $faker->dateTimeBetween("-7 days", "now")
            ]);
        }
        $this->command->info("Finished!");
    }
}
