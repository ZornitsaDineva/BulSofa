<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Page;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;

class SiteController extends Controller {

    //Layout holder
    private $layout;

    //Construct Common Items and Check Auth
    public function __construct() {
//        $this->middleware(CheckAdmin::class);
        $this->layout['siteContent'] = view('site.pages.home');
        $this->layout['notification'] = view('site.common.notification');

        $usertype = [];
        $usertype[0] = __('Individual');
        $usertype[1] = __('Dealer');
        View::share('usertype', $usertype);

        View::share('category_title', __('category_title_en'));
        View::share('subcategory_title', __('subcategory_title_en'));
        View::share('division_title', __('division_title_en'));
        View::share('city_title', __('city_title_en'));
    }

    /**
     * Show dashboard
     * @return type
     */
    public function index() {


        $query = DB::table('posts')
                ->select(
                        'posts.*', 'subcategories.subcategory_title_en', 'subcategories.subcategory_title_bg', 'categories.category_id', 'categories.category_title_en', 'categories.category_title_bg', 'users.name', 'users.user_type', 'cities.city_id', 'cities.city_title_en', 'cities.city_title_bg', 'divisions.division_id', 'divisions.division_title_en', 'divisions.division_title_bg', 'postimages.postimage_thumbnail'
                )
                ->join('subcategories', 'subcategories.subcategory_id', '=', 'posts.subcategory_id')
                ->join('categories', 'categories.category_id', '=', 'subcategories.parent_category_id')
                ->join('users', 'users.id', '=', 'posts.user_id')
                ->join('cities', 'cities.city_id', '=', 'users.city_id')
                ->join('divisions', 'divisions.division_id', '=', 'cities.division_id')
                ->join('postimages', 'postimages.post_id', '=', 'posts.post_id')
                ->where("users.account_status", 1)
                ->where("posts.status", 1)
                ->groupBy('postimages.post_id');

        $query1 = clone $query;

        /* Get Top Views */
        $topViewedPosts = $query->orderBy('posts.views', 'DESC')->limit(3)->get();

        /* Get Recent Posts */
        $latestPosts = $query1->orderBy('posts.created_at', 'DESC')->limit(3)->get();


        //Load Component
        $this->layout['siteContent'] = view('site.pages.home')
                ->with('topViewedPosts', $topViewedPosts)
                ->with('latestPosts', $latestPosts);

        //return view
        return view('site.master', $this->layout);
    }

    public function ajaxCategoryModal() {
        return view('site.ajax.listcategories');
    }

    public function ajaxLocationModal() {
        return view('site.ajax.listlocations');
    }



}
