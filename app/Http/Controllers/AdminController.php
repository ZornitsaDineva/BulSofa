<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session;
use Illuminate\Support\Facades\Redirect;
use App\Http\Middleware\CheckAdmin;
use Illuminate\Support\Facades\Input;
use DataTables;
use App\Models\Admin;
use App\Models\AdminMessage;
use App\Models\Category;
use App\Models\Subcategory;
use App\Models\Division;
use App\Models\City;
use App\Models\Post;
use App\Models\Report;
use App\Models\Page;
use App\Models\RechargeRequest;
use DB;
use Cache;
use Mail;
use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Mail\Mailer;

session_start();

class AdminController extends Controller
{

    //Layout holder
    private $layout;

    //Construct Common Items and Check Auth
    public function __construct()
    {
        //        $this->middleware(CheckAdmin::class);

        Cache::flush();

        $this->layout['adminNotification'] = view('admin.common.notification');
    }

    /**
     * Show dashboard
     * @return type
     */
    public function index()
    {

        //Load Component
        $this->layout['adminContent'] = view('admin.partials.dashboard');

        //return view
        return view('admin.master', $this->layout);
    }

    /**
     * Show Posts in Datatable
     * @return type
     */
    public function adsDatatable()
    {

        //Load Component
        $this->layout['adminContent'] = view('admin.partials.ads.datatable');

        //return view
        return view('admin.master', $this->layout);
    }

    /**
     * datatables/getdata handler
     */
    public function adsDatatableGetData()
    {
        $posts = Post::select(['posts.post_id', 'users.name', 'posts.ad_title', 'cities.city_title_en', 'subcategories.subcategory_title_en', 'posts.short_description', 'posts.status', 'posts.created_at'])
            ->join('subcategories', 'subcategories.subcategory_id', '=', 'posts.subcategory_id')
            ->join('users', 'users.id', '=', 'posts.user_id')
            ->join('cities', 'cities.city_id', '=', 'users.city_id')
            ->orderBy('posts.post_id', "ASC");


        return \DataTables::of($posts)
            ->editColumn('status', function ($row) {
                $status = 'something';
                if ($row->status == 1) {
                    $status = '<span class="label label-success">Published</span>';
                } elseif ($row->status == 0) {
                    $status = '<span class="label label-warning">Unpublished</span>';
                }
                return $status;
            })
            ->addColumn('actions', function ($row) {
                $buttons = "";

                if ($row->status == 1) {
                    $buttons .= "<button title='Unpublish This Post'  class='btn btn-xs btn-warning dtbutton' data-href='" . url('admin/ads/changeStatus/unpublish') . "/$row->post_id'><i class='glyphicon glyphicon-stop'></i></button>";
                } elseif ($row->status == 0) {
                    $buttons .= "<button title='Publish This Post' class='btn btn-xs btn-success dtbutton' data-href='" . url('admin/ads/changeStatus/publish') . "/$row->post_id'><i class='glyphicon glyphicon-play'></i></button>";
                }

                $buttons .= "<button title='Delete This Post' class='btn btn-xs btn-danger dtbutton' data-href='" . url('admin/ads/changeStatus/delete') . "/$row->post_id'><i class='fa fa-times'></i></button>";

                return "<div class='btn-group'>$buttons</div>";
            })
            ->rawColumns(['actions', 'status'])
            ->make(true);
    }

    public function adsChangeStatus($status, $id)
    {
        $post = Post::find($id);

        switch ($status) {
            case "publish":
                $post->status = 1;
                $post->save();
                break;
            case "unpublish":
                $post->status = 0;
                $post->save();
                break;
            case "delete":
                foreach ($post->postimages as $aPostImage) {
                    //remove images
                    $image = base_path("public/$aPostImage->postimage_file");
                    $thumbnail = base_path("public/$aPostImage->postimage_thumbnail");

                    unlink($image);
                    unlink($thumbnail);
                }
                $post->delete();
                break;
            default:
                break;
        }

        return Redirect::to('admin/ads');
    }

    /**
     * User Management
     */

    /**
     * Show users in datatable
     * @return type
     */
    public function usersDatatable()
    {

        //Load Component
        $this->layout['adminContent'] = view('admin.partials.users.datatable');

        //return view
        return view('admin.master', $this->layout);
    }

    /**
     * datatables/usersgetdata handler
     */
    public function usersDatatableGetData()
    {
        $users = User::select(['users.id', DB::raw("COUNT(posts.post_id) as post_count"), 'users.name', 'users.mobile', 'users.account_status', 'cities.city_title_en', 'users.created_at'])
            ->join('posts', 'posts.user_id', '=', 'users.id')
            ->join('cities', 'cities.city_id', '=', 'users.city_id')
            ->groupBy('users.id');


        return \DataTables::of($users)
            ->editColumn('account_status', function ($row) {
                $status = 'something';
                if ($row->account_status == 1) {
                    $status = "<span class='label label-success'>Active</span>";
                } elseif ($row->account_status == 0) {
                    $status = '<span class="label label-warning">Banned</span>';
                }
                return $status;
            })
            ->addColumn('actions', function ($row) {
                $buttons = "";

                if ($row->account_status == 1) {
                    $buttons .= "<button title='Ban This user and unpublish all post' class='btn btn-xs btn-warning dtbutton' data-href='" . url('admin/users/changeStatus/ban') . "/$row->id'><i class='glyphicon glyphicon-stop'></i></button>";
                } elseif ($row->account_status == 0) {
                    $buttons .= "<button title='Un Ban This user' class='btn btn-xs btn-success dtbutton' data-href='" . url('admin/users/changeStatus/unban') . "/$row->id'><i class='glyphicon glyphicon-play'></i></button>";
                }

                return "<div class='btn-group'>$buttons</div>";
            })
            ->rawColumns(['actions', 'account_status'])
            ->make(true);
    }

    public function usersChangeStatus($status, $id)
    {
        $user = User::find($id);

        switch ($status) {
            case "ban":
                $user->account_status = 0;
                $user->save();
                break;
            case "unban":
                $user->account_status = 1;
                $user->save();
                break;
            default:
                break;
        }

        return Redirect::to('admin/users');
    }

    /**
     * Show Posts in Datatable
     * @return type
     */
    public function reportsDatatable()
    {

        //Load Component
        $this->layout['adminContent'] = view('admin.partials.reports.datatable');

        //return view
        return view('admin.master', $this->layout);
    }

    /**
     * datatables/getdata handler
     */
    public function reportsDatatableGetData()
    {
        $reports = Report::select([
            'reports.report_id',
            'users.name',
            'posts.ad_title',
            'posts.status',
            'posts.post_id',
            'reports.reason',
            'reports.report_status',
            'reports.message',
            'reports.created_at'
        ])
            ->join('users', 'users.id', '=', 'reports.user_id')
            ->join('posts', 'posts.post_id', '=', 'reports.post_id')
            ->orderBy("report_status", 'asc')
            //->where('report_status', '=', 1)
        ;


        return \DataTables::of($reports)
            ->editColumn('status', function ($row) {
                $status = 'something';
                if ($row->status == 1) {
                    $status = '<span class="label label-success">Published</span>';
                } elseif ($row->status == 0) {
                    $status = '<span class="label label-warning">Unpublished</span>';
                }
                return $status;
            })
            ->editColumn('report_status', function ($row) {
                $status = 'something';
                if ($row->report_status == 1) {
                    $status = '<span class="label label-success">Reviewed</span>';
                } elseif ($row->report_status == 0) {
                    $status = '<span class="label label-warning">New</span>';
                }
                return $status;
            })
            ->addColumn('actions', function ($row) {
                $buttons = "";

                /* View Complain */
                $buttons .= "<button  title='View Original Post' id='external' class='btn btn-xs btn-primary dtbutton' data-href='" . url('ad') . "/$row->post_id/report'><i class='fa fa-eye'></i></button>";

                if ($row->status == 1) {
                    $buttons .= "<button title='Unpublish This Post' class='btn btn-xs btn-warning dtbutton' data-href='" . url('admin/ads/changeStatus/unpublish') . "/$row->post_id'><i class='glyphicon glyphicon-stop'></i></button>";
                } elseif ($row->status == 0) {
                    $buttons .= "<button title='Re Publish This Post' class='btn btn-xs btn-success dtbutton' data-href='" . url('admin/ads/changeStatus/publish') . "/$row->post_id'><i class='glyphicon glyphicon-play'></i></button>";
                }

                /* End Report */
                $buttons .= "<button title='Mark this complain as read' class='btn btn-xs btn-danger dtbutton confirmalert' data-href='" . url('admin/ad/complain/end') . "/$row->report_id'><i class='fa fa-times'></i></button>";

                return "<div class='btn-group'>$buttons</div>";
            })
            ->rawColumns(['actions', 'status', 'report_status'])
            ->make(true);
    }

    public function reportsEnd($id)
    {
        $report = Report::find($id);
        $report->report_status = 1;
        $report->save();

        return Redirect::to('admin/ads');
    }

    /**
     * Admin Message
     */

    /**
     * Show Comment in Data table
     *@return type
     */

    /**
     * datatables/getdata handler
     */
    public function adminMessagesDatatableGetData()
    {
        $admin_messages = AdminMessage::select([
            'admin_messages.admin_message_id',
            'users.name',
            'admin_messages.comment',
            'admin_messages.response',
            'admin_messages.read_status',
            'admin_messages.created_at'
        ])
            ->join('users', 'users.id', '=', 'admin_messages.sender_id')
            ->orderBy("read_status", 'asc');


        return DataTables::of($admin_messages)

            ->editColumn('read_status', function ($row) {
                $status = '';
                if ($row->read_status == 1) {
                    $status = '<span class="label label-success">Reviewed</span>';
                } elseif ($row->read_status == 0) {
                    $status = '<span class="label label-warning">New</span>';
                }
                return $status;
            })
            ->addColumn('actions', function ($row) {
                $buttons = "";

                /* Sent response */
                $buttons .= "<button  title='Send response' id='external' class='btn btn-xs btn-success dtbutton' data-href='" . url('admin/admin_messages/respond') . "/$row->admin_message_id'><i class='fa fa-reply'></i></button>";


                /* read status */
                $buttons .= "<button title='Mark this comment as read' class='btn btn-xs btn-primary dtbutton confirmalert' data-href='" . url('admin/admin_messages/end') . "/$row->admin_message_id'><i class='fa fa-eye'></i></button>";

                return "<div >$buttons</div>";
            })
            ->rawColumns(['actions', 'status', 'read_status'])
            ->make(true);
    }


    /**
     * Show Comment in Datatable
     * @return type
     */
    public function adminMessagesDatatable()
    {

        //Load Component
        $this->layout['adminContent'] = view('admin.partials.admin_message.datatable');

        //return view
        return view('admin.master', $this->layout);
    }

    public function adminMessagesEnd($id)
    {
        $admin_messages = AdminMessage::find($id);
        $admin_messages->read_status = 1;
        $admin_messages->save();

        return Redirect::to('admin/admin_messages');
    }


    public function showAdminMessage($id)
    {

        $admin_message_respond = AdminMessage::select([
            'admin_messages.admin_message_id',
            'users.name',
            'admin_messages.comment'
        ])
            ->join('users', 'users.id', '=', 'admin_messages.sender_id')
            ->where('admin_message_id', '=', $id)
            ->first();

        //Load Component
        $this->layout['adminContent'] = view('admin.partials.admin_message.respond_form')
            ->with('admin_message', $admin_message_respond);

        //return view
        return view('admin.master', $this->layout);
    }

    /**
     * Show admin_messages/respond page
     * @return \Illuminate\Http\RedirectResponse
     */
    public function adminMessageRespond(Request $request)
    {

        $request->validate([
            'id' => 'required|int',
            'response' => 'required|string|max:500'
        ]);

        $admin_message = AdminMessage::find($request->id);
        $admin_message->response = $request->response;
        $admin_message->read_status = 1;
        $admin_message->save();

        $this->sendEmailAdminResponse($request, $admin_message);

        return Redirect::to('admin/admin_messages');
    }

    /**
     * Send an e-mail reminder to the user.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    private function sendEmailAdminResponse(Request $request,$admin_message) {
        $user = User::findOrFail($admin_message->sender_id);

        Mail::send('response', ['user' => $user,'admin_message'=>$admin_message], function ($m) use ($user) {
        //Mail::send('response', [], function ($m) {
            $m->from('hello@app.com', 'BulSofa');

            $m->to($user->email, $user->name)->subject('Admin Respose!');
            //$m->to('me@help.com', 'me')->subject('Admin Respose!');
        });
    }

    /**
     * Recharge
     */

    /**
     * Show Posts in Datatable
     * @return type
     */
    public function rechargeDatatable()
    {

        //Load Component
        $this->layout['adminContent'] = view('admin.partials.recharge.datatable');

        //return view
        return view('admin.master', $this->layout);
    }

    /**
     * datatables/getdata handler
     */
    public function rechargeDatatableGetData()
    {
        $recharges = RechargeRequest::select([
            'recharge_requests.recharge_request_id',
            'users.name',
            'recharge_requests.recharge_amount',
            'recharge_requests.request_status',
            'recharge_requests.created_at'
        ])
            ->join('users', 'users.id', '=', 'recharge_requests.user_id')
            ->orderBy('recharge_requests.request_status', 'DESC');


        return DataTables::of($recharges)
            ->editColumn('request_status', function ($row) {
                $status = 'something wrong';
                if ($row->request_status == 1) {
                    $status = '<span class="label label-success">New</span>';
                } elseif ($row->request_status == 0) {
                    $status = '<span class="label label-info">Processed</span>';
                }
                return $status;
            })
            ->addColumn('actions', function ($row) {
                $buttons = "";

                /* View Complain */

                if ($row->request_status == 1) {
                    $buttons .= "<button title='Mark as Received' class='btn btn-xs btn-success dtbutton' data-href='" . url('admin/payment/changeStatus/received') . "/$row->recharge_request_id'><i class='fa fa-check'></i></button>";
                } elseif ($row->request_status == 0) {
                    $buttons .= "<button title='Mark as Not Received/new' class='btn btn-xs btn-warning dtbutton' data-href='" . url('admin/payment/changeStatus/new') . "/$row->recharge_request_id'><i class='fa fa-undo'></i></button>";
                }


                return "<div class='btn-group'>$buttons</div>";
            })
            ->rawColumns(['actions', 'request_status'])
            ->make(true);
    }

    /* Accept recharge, or undo */
    public function rechargeChangeStatus($status, $id)
    {
        switch ($status) {
            case 'received':
                $recharge = RechargeRequest::find($id);
                $recharge->request_status = 0;
                $recharge->save();

                $user = User::find($recharge->user_id);
                $user->user_balance = $user->user_balance + $recharge->recharge_amount;
                $user->save();

                break;
            case 'new':
                $recharge = RechargeRequest::find($id);
                $recharge->request_status = 1;
                $recharge->save();

                $user = User::find($recharge->user_id);
                $user->user_balance = $user->user_balance - $recharge->recharge_amount;
                $user->save();

                break;
            default:
                break;
        }


        return Redirect::to('admin/ads');
    }



    /**
     * Category Management Start
     */

    /**
     * List Category
     * @return type
     */
    public function categoryView()
    {
        $categories = Category::orderBy('category_weight', 'ASC')->get();

        //Load Component
        $this->layout['adminContent'] = view('admin.partials.category.list')
            ->with('categories', $categories);

        //return view
        return view('admin.master', $this->layout);
    }

    /**
     * Edit Category Form
     * @param type $id
     * @return type
     */
    public function categoryEdit($id)
    {
        $oldCategoryData = Category::find($id);

        //Load Component
        $this->layout['adminContent'] = view('admin.partials.category.categorycreate')
            ->with('oldCategoryData', $oldCategoryData);

        //return view
        return view('admin.master', $this->layout);
    }

    /**
     * Create Category Form
     * @return type
     */
    public function categoryCreate()
    {

        //Load Component
        $this->layout['adminContent'] = view('admin.partials.category.categorycreate');

        //return view
        return view('admin.master', $this->layout);
    }

    /**
     * Save Category POST handler
     * @param Request $request
     * @return type
     */
    public function categorySaveCategory(Request $request)
    {
        $redirectUrl = '/admin/categories';

        if (isset($request->category_id)) {
            $redirectUrl = '/admin/category/edit/' . $request->category_id;

            $category = Category::find($request->category_id);

            Session::put('message', array(
                'title' => 'Category Updated',
                'body' => "Category Info Updated",
                'type' => 'info'
            ));
        } else {
            $validatedData = $request->validate([
                'category_title_en' => 'required|string|unique:categories|max:50',
                'category_title_bg' => 'required|string|unique:categories|max:50',
                'category_image' => 'required',
                'category_icon' => 'required'
            ]);

            $category = new Category;

            Session::put('message', array(
                'title' => 'Category Created',
                'body' => "Created New Category",
                'type' => 'success'
            ));

            //$category->category_image = "";
        }



        $category->category_title_en = $request->category_title_en;
        $category->category_title_bg = $request->category_title_bg;

        $category->category_icon = $request->category_icon;

        $category->category_weight = $request->category_weight;
        //$category->category_caption = $request->category_caption;


        /*
         * Image Upload
         */
        $files = $request->file('category_image');

        //File Is Selected, Proceed with upload
        if ($files) {
            $extension = $files->extension();

            $allowedExtensions = ['png'];

            if (!($request->file('category_image')->isValid() && (in_array($extension, $allowedExtensions)))) {

                //File Upload Failed,
                Session::put('message', array(
                    'title' => 'Invalid File Selected',
                    'body' => "Please select image file with png extension. With less than 10kb size",
                    'type' => 'danger'
                ));

                return Redirect::to($redirectUrl);
            }

            $filename = $files->getClientOriginalName();
            $customName = str_replace(' ', '_', strtolower($request->category_title_en)) . "." . $extension;
            $imgUrl = 'images/category/' . $customName;
            $destinationPath = base_path() . "/public/images/category/";

            //Try upload
            $success = $files->move($destinationPath, $customName);

            if ($success) {

                //Delete Old iMage if edit and has old image
                if (isset($request->category_id) && ($request->category_image_old != "")) {
                    $oldFileName = $request->category_image_old;
                    unlink($oldFileName);
                }

                $category->category_image = $imgUrl;

                //If it is an edit , remove old file
            } else {

                //File Upload Failed,
                Session::put('message', array(
                    'title' => 'Error',
                    'body' => "File Upload Failed",
                    'type' => 'danger'
                ));
            }
        }

        $category->save();

        return Redirect::to($redirectUrl);
    }

    /**
     * Sub Category Edit Form
     * @param type $subcategory_id
     * @return type
     */
    public function subcategoryEdit($subcategory_id)
    {
        $oldCategoryData = Subcategory::find($subcategory_id);

        //Load Component
        $this->layout['adminContent'] = view('admin.partials.subcategory.form')
            ->with('oldCategoryData', $oldCategoryData);

        //return view
        return view('admin.master', $this->layout);
    }

    /**
     * Sub Category Create Form
     * @return type
     */
    public function subcategoryCreate()
    {

        //Load Component
        $this->layout['adminContent'] = view('admin.partials.subcategory.form');

        //return view
        return view('admin.master', $this->layout);
    }

    /**
     * Sub Category Save POST handler
     * @param Request $request
     * @return type
     */
    public function subcategorySave(Request $request)
    {
        $redirectUrl = '/admin/subcategory/create';

        if (isset($request->subcategory_id)) {
            $redirectUrl = '/admin/subcategory/edit/' . $request->subcategory_id;

            $subcat = Subcategory::find($request->subcategory_id);

            Session::put('message', array(
                'title' => 'Sub Category Updated',
                'body' => "Sub Category Info Updated",
                'type' => 'info'
            ));


            $validatedData = $request->validate([
                'parent_category_id' => 'required',
                'subcategory_title_en' => 'required|string',
                'subcategory_title_bg' => 'required|string'
            ]);
        } else {
            $validatedData = $request->validate([
                'parent_category_id' => 'required',
                'subcategory_title_en' => 'required|string|unique:subcategories|max:50',
                'subcategory_title_bg' => 'required|string|unique:subcategories|max:50'
            ]);


            $subcat = new Subcategory;

            Session::put('message', array(
                'title' => 'Sub Category Created',
                'body' => "Created New Sub Category $request->subcategory_title_en ($request->subcategory_title_bg)",
                'type' => 'success'
            ));
        }


        $subcat->parent_category_id = $request->parent_category_id;
        $subcat->subcategory_title_en = $request->subcategory_title_en;
        $subcat->subcategory_title_bg = $request->subcategory_title_bg;

        if ($request->has('subcategory_weight')) {
            $subcat->subcategory_weight = $request->subcategory_weight;
        } else {
            $subcat->subcategory_weight = 0;
        }
        $subcat->subcategory_caption = $request->subcategory_caption;

        $subcat->save();

        return Redirect::to($redirectUrl);
    }

    /**
     * Category Management End
     */
    /**
     * Location Management Start
     */

    /**
     * List Locations
     * @return type
     */
    public function locationView()
    {
        $divisions = Division::orderBy('division_weight', 'ASC')->get();

        //Load Component
        $this->layout['adminContent'] = view('admin.partials.location.list')
            ->with('divisions', $divisions);

        //return view
        return view('admin.master', $this->layout);
    }

    /**
     * Show Create Division FOrm
     * @return type
     */
    public function divisionCreate()
    {

        //Load Component
        $this->layout['adminContent'] = view('admin.partials.location.divisionform');

        //return view
        return view('admin.master', $this->layout);
    }

    /**
     * Show Division Edit Form
     * @param type $id
     * @return type
     */
    public function divisionEdit($id)
    {
        $oldDivisionData = Division::find($id);

        //Load Component
        $this->layout['adminContent'] = view('admin.partials.location.divisionform')
            ->with('oldDivisionData', $oldDivisionData);

        //return view
        return view('admin.master', $this->layout);
    }

    /**
     * Save Division Data, POST handler
     * @param Request $request
     * @return type
     */
    public function divisionSave(Request $request)
    {
        $redirectUrl = '/admin/division/create';

        if (isset($request->division_id)) {
            $redirectUrl = '/admin/division/edit/' . $request->division_id;

            $validatedData = $request->validate([
                'division_title_en' => 'required|string',
                'division_title_bg' => 'required|string'
            ]);

            $division = Division::find($request->division_id);

            Session::put('message', array(
                'title' => 'Division Updated',
                'body' => "Division Info Updated",
                'type' => 'info'
            ));
        } else {
            $validatedData = $request->validate([
                'division_title_en' => 'required|string|unique:divisions|max:50',
                'division_title_bg' => 'required|string|unique:divisions|max:50'
            ]);

            $division = new Division;

            Session::put('message', array(
                'title' => 'Division Created',
                'body' => "Created New Division $request->division_title_en ($request->division_title_bg)",
                'type' => 'success'
            ));
        }

        $division->division_title_en = $request->division_title_en;
        $division->division_title_bg = $request->division_title_bg;
        $division->division_weight = $request->division_weight;
        $division->division_icon = $request->division_icon;

        $division->save();

        return Redirect::to($redirectUrl);
    }

    /**
     * Show Create City Form
     * @return type
     */
    public function cityCreate()
    {

        //Load Component
        $this->layout['adminContent'] = view('admin.partials.location.cityform');

        //return view
        return view('admin.master', $this->layout);
    }

    public function cityEdit($id)
    {
        $oldCityData = City::find($id);

        //Load Component
        $this->layout['adminContent'] = view('admin.partials.location.cityform')
            ->with('oldCityData', $oldCityData);

        //return view
        return view('admin.master', $this->layout);
    }

    public function citySave(Request $request)
    {
        $redirectUrl = '/admin/city/create';

        if (isset($request->city_id)) {
            $redirectUrl = '/admin/city/edit/' . $request->city_id;

            $validatedData = $request->validate([
                'city_title_en' => 'required|string',
                'city_title_bg' => 'required|string'
            ]);

            $city = City::find($request->city_id);

            Session::put('message', array(
                'title' => 'City Updated',
                'body' => "City Info Updated",
                'type' => 'info'
            ));
        } else {
            $validatedData = $request->validate([
                'city_title_en' => 'required|string|unique:cities|max:50',
                'city_title_bg' => 'required|string|unique:cities|max:50'
            ]);

            $city = new City;

            Session::put('message', array(
                'title' => 'City Created',
                'body' => "Created New City $request->city_title_en ($request->city_title_bg)",
                'type' => 'success'
            ));

            $redirectUrl = '/admin/city/create?division_id=' . $request->division_id;
        }

        $city->city_title_en = $request->city_title_en;
        $city->city_title_bg = $request->city_title_bg;
        $city->city_weight = $request->city_weight;

        $city->division_id = $request->division_id;

        $city->save();

        return Redirect::to($redirectUrl);
    }

    /**
     * Location Management End
     */


    /*
     * Sample page with a table
     */



    public function logout()
    {


        //Admin informations
        Session::put('admin_id', 0);


        Session::forget('admin_username');
        Session::forget('admin_name');
        Session::forget('admin_privilage');

        //Message for Notification Builder
        Session::put('message', array(
            'title' => 'Logged Out, ',
            'body' => 'You are no longer logged in',
            'type' => 'warning'
        ));

        return Redirect::to('/')->send();
    }

}
