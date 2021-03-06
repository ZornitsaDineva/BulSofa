<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use DateTime;

class Post extends Model
{
    use HasFactory;

    protected $primaryKey = "post_id";
    protected $table = 'posts';

    public function Postimages()
    {
        return $this->hasMany('App\Models\Postimage', 'post_id', 'post_id');
    }

    public function Subcategory()
    {
        return $this->hasOne('App\Models\Subcategory', 'subcategory_id', 'subcategory_id');
    }

    public function User()
    {

        return $this->hasOne('App\Models\User', 'id', 'user_id');
    }

    public static function findPublished($id)
    {
        return self::join('users', 'users.id', '=', 'posts.user_id')
            ->where("post_id", $id)
            ->where("status", 1)
            ->where("users.account_status", 1)
            ->first();
    }

    public function isPromoted()
    {

        //7 day ago
        $startDate = date('Y-m-d 00:00:00', strtotime('-7days'));


        //today
        $endDate = date('Y-m-d 23:59:59', time());

        $featuredJoin = DB::table("featureds")
            ->where("post_id", $this->post_id)
            ->where('featureds.created_at', '>', $startDate)
            ->where('featureds.created_at','<',$endDate)
            ->first();

        if($featuredJoin){

            $offerStarted = $featuredJoin->created_at;
            $offerStarted = strtotime($offerStarted);
            $offerEnds = strtotime("+7 day", $offerStarted);

            date('Y-m-d 23:59:59', time());

            $offerStartObj = new DateTime(date('Y-m-d h:i:s', $offerStarted));
            $offerEndObj = new DateTime(date('Y-m-d 23:59:59', $offerEnds));
            $todayObj = new DateTime("now");

            $validityLeft = $todayObj->diff($offerEndObj);
            /*
            echo "Start = $offerStarted###";
            echo "End = $offerEnds###";
            echo "Diff = ".($offerEnds - $offerStarted);*/


            // shows the total amount of days (not divided into years, months and days like above)
            return $validityLeft->days;
        } else {
            return false;

        }
    }


public static function countPostsByMonth($startDate, $endDate)
    {
        $count = DB::table('posts')
                ->where('posts.created_at', '>', $startDate)
                ->where('posts.created_at', '<', $endDate)
                ->get()->count();

        return $count;
    }

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($post) { // before delete() method call this
            $post->Postimages()->delete();
            // do the rest of the cleanup...
        });
    }


}
