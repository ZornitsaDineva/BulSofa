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
        $nummberOfPromoted = ceil($numberOfAds / 5);

        $this->command->info("Generating $numberOfUsers fake users..")

        //DB::table('Users')->delete();

        $pass = Hash::make('123456');

        for ($nc=1; $nc<=$numberOfUsers; $nc++){


            App\User::create([
                'name'=>$faker->name,
                'email'=>$nc.$faker->email,
                'password'=>$pass,
                'mobile'=>$faker->numberBetween(0,1),
                'user_type'=>$faker->numberBetween(1,329),
                'useer_balance'=>"2000"

            ]);

        }

        $this->command->info("Generating $numberOfAds fake posts..");

        for ($index =1; $index<=$numberOfAds;$index++)
        {

            $rndUser = $fakaer->numberBetween(1, $numberOfUsers);
            $imgCount=$faker->numberBetween(2,4);

            $types=['New','Used'];

            $postId=App\Models\Post::create([
                   'user_id'=>$rndUseer,
                   'city_id'=>$faker->numberBetween(1,329),
                   'subcategory_id'=>$faker->numberBetween(2,65),
                   'ad_type'=>"newsell",
                   'ad_title'=>"newsell",
                   'item_condition'=>$types[$faker->numberBetween(2,65)],
                   'item_price'=>$faker->price(10,500000),
                   'price_negotiable'=>'0',
                   //'model'=>$faker->size." ".$faker->material,
                   //'brand'=>$faker->company,
                   'delivery'=>"In Person",
                   'status'=>1,
                   'view'=>mt_rand(0,1000),
                   'short_description'=>$faker->elaborateProduct,
                   'long_description'=>$faker->elaborateProduct,
                   'created_at'=>$faker->dateTimeBetween('-5 months','now')
            ])->post_id;

        }



        for ($i=1; $i<=$imgCount; $i++){

            $filename=uniqid();
            $randomImageFile=mt_rand(1,10);

            $tempPath=base_path($faker->imageOffline($randomImageFile));
            $newPath=base_path("public/images/" .$rndUser . "_" ."$filename.jpeg");

            //move file
            copy($tempPath, $newPath);

            $tempPathTumb=base_path($faker->imageOffline($randomImageFile, true));
            $newPathThumb=base_path("public/images/thumb/" .$rndUser. "_"."$filename.jpeg");


            copy($tempPathThumb, $newPathThumb);


            App\Models\Postimage::create([
                "post_id"=>$postId,
                "postimage_file"=>"images/" .$rndUser . "_" . "$filename.jpeg",
                "postimage_thumbnail"=>"images/thumb/" .$rndUser . "_" ."$filename.jpeg"
            ]);


        }

        $this->command->info("Finished!");

    }

}
