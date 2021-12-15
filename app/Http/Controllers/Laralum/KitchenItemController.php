<?php

namespace App\Http\Controllers\Laralum;

use App\AdminSetting;
use App\Booking;
use App\DietChart;
use App\DietChartItems;
use App\DietDailyStatus;
use App\KitchenItem;
use App\Room;
use App\Settings;
use App\Stock;
use App\StockItemRequest;
use App\User;
use App\Wallet;
use Hamcrest\Core\Set;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Excel;
use Monolog\Handler\IFTTTHandler;
use Schema;
use DB;
use Laralum;
use PDF;

class KitchenItemController extends Controller
{
    //
    /**
     * kitchen_item listing
     * @return View
     */
    public function index(Request $request)
    {
        Laralum::permissionToAccess('admin.kitchen_items');
        $kitchen_items = KitchenItem::select('*')->orderBy('created_at', "DESC");

        if (!\Auth::user()->isAdmin()) {
            $kitchen_items = $kitchen_items->orderBy('created_at', "DESC")->where('created_by', \Auth::user()->id);
        }
        $per_page = $request->get("per_page") ? $request->get("per_page") : 10;
        $pagination = true;
        if ($per_page == "All") {
            $pagination = false;
        }
        $count = $kitchen_items->count();
        if ($pagination == true) {
            $kitchen_items = $kitchen_items->paginate($per_page);
        } else {
            $kitchen_items = $kitchen_items->get();
        }

        return view('laralum.kitchen_item.index', compact('kitchen_items', 'count'));
    }


    public function printItems(Request $request)
    {
        Laralum::permissionToAccess('admin.kitchen_items');

        $per_page = $request->get("per_page") ? $request->get("per_page") : 10;
        $pagination = true;
        if ($per_page == "All") {
            $pagination = false;
        }
        $matchThese = [];
        $matchTheseN = [];
        $search = false;
        $option_ar = [];

        if ($request->s && $request->s != 'null') {
            $search_data = json_decode($request->s, true);

            if (!empty($search_data['name'])) {
                $search = true;
                $option_ar[] = "Name";
                $matchThese['name'] = $search_data['name'];
            }
            if (!empty($search_data['price'])) {
                $option_ar[] = "Price";
                $search = true;
                $matchThese['price'] = $search_data['price'];
            }

            if (!empty($search_data['type'])) {
                $option_ar[] = "Type";
                $search = true;
                $matchThese['type'] = $search_data['type'];
            }
            $ingredients = "";
            if (!empty($search_data['ingredients'])) {
                $option_ar[] = "ingredients";
                $search = true;
                $matchTheseN['ingredients'] = $search_data['ingredients'];
                $ingredients = $search_data['ingredients'];
            }
        }
        $options = implode(", ", $option_ar);

        $error = "Entered " . $options . " is not valid,
make sure that you are entering valid " . $options . " 
or search by other options";

        $per_page = $request->get("per_page") ? $request->get("per_page") : 10;
        $pagination = true;
        if ($per_page == "All") {
            $pagination = false;
        }

        $kitchen_items = KitchenItem::select('kitchen_items.*')->orderBy('kitchen_items.created_at', "DESC");
        if ($search == true) {
            $kitchen_items = KitchenItem::select('kitchen_items.*')
                ->orderBy('kitchen_items.created_at', "DESC")->where(function ($query) use ($matchThese) {
                    foreach ($matchThese as $key => $match) {
                        $query->where($key, 'like', "%$match%");
                    }
                });

            if ($ingredients != "") {
                $kitchen_items = $kitchen_items->leftjoin('stock', 'stock.product_id', '=', 'kitchen_items.id')
                    ->where('stock.name', 'like', "%$ingredients%")->distinct();
            }

        }

        $count = $kitchen_items->count();

        if ($count <= 10) {
            $pagination = false;
        }
        if ($pagination == true) {
            $kitchen_items = $kitchen_items->paginate($per_page);
        } else {
            $kitchen_items = $kitchen_items->get();
        }
        $print = true;
        return view('laralum.kitchen_item.print-kitchen-items', compact('kitchen_items', 'print'));
    }

    /**
     * kitchen_item details with replies
     * @return View
     */
    public function view($id)
    {
        Laralum::permissionToAccess('admin.kitchen_items');
        $kitchen_item = KitchenItem::find($id);

        return view('laralum.kitchen_item.view', compact('kitchen_item'));
    }

    public function edit($id)
    {
        # Check permissions
        Laralum::permissionToAccess('admin.kitchen_items');

        # Find the kitchen_item
        $row = KitchenItem::findOrFail($id);
        \Session::put('kitchen_item_id', $id);

        # Get all the data
        $data_index = 'kitchen_items';
        require('Data/Edit/Get.php');

        # Return the view
        return view('laralum/kitchen_item/edit', [
            'row' => $row,
            'fields' => $fields,
            'confirmed' => $confirmed,
            'empty' => $empty,
            'encrypted' => $encrypted,
            'hashed' => $hashed,
            'masked' => $masked,
            'table' => $table,
            'code' => $code,
            'wysiwyg' => $wysiwyg,
            'relations' => $relations,
        ]);
    }

    public function update($id, Request $request)
    {
        # Check permissions
        Laralum::permissionToAccess('admin.kitchen_items');

        $request_ar = $request->all();
        $request_ar['ingredients'] = array_filter($request->ingredients);
        if ($request_ar['ingredients'] == null) {
            unset($request_ar['ingredients']);
        }
        # Find the row
        $kitchen_item = KitchenItem::findOrFail($id);
        $rules = KitchenItem::getRules(true);

        $validator = \Validator::make($request_ar, $rules);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput()->with(['error' => 'Please check the errors below and try again.']);
        }

        try {

            if ($kitchen_item->setData($request)) {
                $kitchen_item->save();
                $kitchen_item->saveItems($request->ingredients);
                return redirect()->route('Laralum::kitchen-items')->with('success', 'Kitchen Item edited successfully.');
            } else {
                return redirect()->route('Laralum::kitchen-items')->with('error', 'Something went wrong. Please try again later.');
            }

        } catch (\Exception $e) {

            \Log::error("Failed to add the kitchen_item, possible causes: " . $e->getMessage());
            //print_r($e->getMessage());exit;
            return redirect()->route('Laralum::kitchen-items')->with('error', 'Something went wrong. Please try again later.');
        }

    }

    /**
     * add kitchen_item for the staff
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        Laralum::permissionToAccess('admin.kitchen_items');

        # Get all the data
        $data_index = 'kitchen_items';
        require('Data/Create/Get.php');

        return view('laralum.kitchen_item.create',
            [
                'fields' => $fields,
                'confirmed' => $confirmed,
                'encrypted' => $encrypted,
                'hashed' => $hashed,
                'masked' => $masked,
                'table' => $table,
                'code' => $code,
                'wysiwyg' => $wysiwyg,
                'relations' => $relations,
            ]);
    }

    public function store(Request $request)
    {
        Laralum::permissionToAccess('admin.kitchen_items');

        $rules = KitchenItem::getRules(true);
        $request_ar = $request->all();
        $request_ar['ingredients'] = array_filter($request->ingredients);
        if ($request_ar['ingredients'] == null) {
            unset($request_ar['ingredients']);
        }

        $validator = \Validator::make($request_ar, $rules);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput()->with(['error' => 'Please check the errors below and try again.']);
        }

        try {
            $kitchen_item = KitchenItem::where('name', $request->get("name"))->first();
            if ($kitchen_item == null)
                $kitchen_item = new KitchenItem();

            if ($kitchen_item->setData($request)) {
                $kitchen_item->save();
                $kitchen_item->saveItems($request->ingredients);
                return redirect()->route('Laralum::kitchen-items')->with('success', 'Kitchen Item added successfully.');
            } else {
                return redirect()->route('Laralum::kitchen-items')->with('error', 'Something went wrong. Please try again later.');
            }

        } catch (\Exception $e) {

            \Log::error("Failed to add the feedback question, possible causes: " . $e->getMessage());
            //print_r($e->getMessage());exit;
            return redirect()->route('Laralum::kitchen-items')->with('error', 'Something went wrong. Please try again later.');
        }

    }


    public function destroy($id)
    {   # Check permissions
        Laralum::permissionToAccess('admin.kitchen_items');

        # Select KitchenItem
        $kitchen_item = KitchenItem::findOrFail($id);
        # Delete KitchenItem
        $kitchen_item->customDelete();
        # Redirect the admin
        return redirect()->route('Laralum::kitchen-items')->with('success', trans('laralum.msg_kitchen_item_deleted'));

    }

    public function patientDiets(Request $request, $id = null)
    {
        Laralum::permissionToAccess('kitchen.diet_management');
        $matchThese = [];
        $search = false;
        $option_ar = [];
        $date = (string)date("Y-m-d");

        $option_ar = [];
        $kid = "";
        $filter_uh_id = "";
        if ($request->has('filter_patient_id') && $request->get('filter_patient_id') != "") {
            $option_ar[] = "Patient Id";
            $search = true;
            $kid = $request->get('filter_patient_id');
            $matchThese['kid'] = $request->get('filter_patient_id');
        }

        $uhid = "";
        if ($request->has('filter_uh_id') && $request->get('filter_uh_id') != "") {
            $option_ar[] = "UHID";
            $search = true;
            //$matchThese['uhid'] = $request->get('filter_uh_id');
            $filter_uh_id = $request->get('filter_uh_id');
        }


        $filter_name = "";

        if ($request->has('filter_name') && $request->get('filter_name') != "") {
            $option_ar[] = "Name";
            $search = true;
            $matchThese['first_name'] = $request->get('filter_name');

            $array = explode(' ', $request->get('filter_name'));

            $matchThese['first_name'] = $array[0];
            $matchThese['last_name'] = '';

            if (isset($array[1])) {
                $matchThese['last_name'] = $array[1];
            }
            // $filter_name = $request->get('filter_name');
        }

        if ($request->has('filter_first_name') && $request->get('filter_first_name') != "") {
            $option_ar[] = "First Name";
            $search = true;
            $matchThese['first_name'] = $request->get('filter_first_name');
        }

        if ($request->has('filter_last_name') && $request->get('filter_last_name') != "") {
            $option_ar[] = "Last Name";
            $search = true;
            $matchThese['last_name'] = $request->get('filter_last_name');
        }

        $filter_mobile = "";
        if ($request->has('filter_mobile') && $request->get('filter_mobile') != "") {
            $option_ar[] = "Mobile";
            $search = true;
            $filter_mobile = $request->get('filter_mobile');
            $matchThese['mobile'] = $request->get('filter_mobile');
        }

        $filter_email = "";

        if ($request->has('filter_email')) {
            $option_ar[] = "Email";
            $search = true;
            $filter_email = $request->get('filter_email');
        }
        $options = implode(", ", $option_ar);

        $error = "Entered " . $options . " is not valid,
make sure that you are entering valid " . $options . " 
or search by other options";

        $user = [];
        $diet = new DietChart();

        //print_r($matchThese);exit;
        if ($search == true) {
            $diet = DietChart::select('diet_chart.*')
                /* ->where('start_date', (string)date('Y-m-d'))*/
                ->join('bookings', 'bookings.id', '=', 'diet_chart.booking_id')
                ->join('user_profiles', 'user_profiles.id', '=', 'bookings.profile_id')
                ->join('users', 'users.id', '=', 'diet_chart.patient_id')
                ->where(function ($query) use ($matchThese, $filter_email, $filter_name, $filter_uh_id) {
                    foreach ($matchThese as $key => $match) {
                        $query->where('user_profiles.' . $key, 'like', "%$match%");
                    }
                    if ($filter_email != "") {
                        $query->where('users.email', 'like', "%$filter_email%");
                    }

                    if ($filter_uh_id != "") {
                        $query->where('users.uhid', 'like', "%$filter_uh_id%");
                    }


                })
                ->where('diet_chart.start_date', '<=', $date)->where('diet_chart.end_date', '>=', $date)
                ->orderBy('diet_chart.created_at', 'DESC');

            if ($filter_email != '') {
                $diet = $diet->where('users.email', 'like', "%" . $filter_email . "%")
                    ->where('user_profiles.mobile', 'like', "%" . $filter_mobile . "%");
            }

            if ($kid != null) {
                $diet = $diet->where('user_profiles.kid', $kid);
            }

            $diet = $diet->first();

        } elseif ($id != null) {
            $diet = DietChart::select('diet_chart.*')
                ->join('bookings', 'bookings.id', '=', 'diet_chart.booking_id')
                ->join('user_profiles', 'user_profiles.id', '=', 'bookings.profile_id')
                ->join('users', 'users.id', '=', 'user_profiles.user_id')
                ->where('diet_chart.booking_id', $id)
                ->where('diet_chart.start_date', '<=', $date)->where('diet_chart.end_date', '>=', $date)/*
                ->where('diet_chart.start_date', '<=', $date)->where('diet_chart.end_date', '>=', $date)*/
                ->orderBy('diet_chart.created_at', 'DESC')->first();

        }

        $patients = Booking::select('bookings.*')->join('diet_chart', 'diet_chart.booking_id', '=', 'bookings.id')->where('diet_chart.status', DietChart::STATUS_PENDING)->where('diet_chart.start_date', '<=', $date)->where('diet_chart.end_date', '>=', $date)->orderBy('diet_chart.created_at', 'DESC');
        $per_page = $request->get("per_page") ? $request->get("per_page") : 10;
        $pagination = true;
        if ($per_page == "All") {
            $pagination = false;
        }

        $count = $patients->count();

        if ($pagination == true) {
            $patients = $patients->paginate($per_page);
        } else {
            $patients = $patients->get();
        }

        if ($diet == null) {
            $diet = new DietChart();
        }

        return view('laralum.kitchen_item.patient_diet', compact('diet', 'search', 'error', 'patients', 'count'));
    }

    public function patientDietToggle(Request $request, $id)
    {
        Laralum::permissionToAccess('kitchen.diet_management');
        $diet = DietChart::find($id);
        $date = (string)date("Y-m-d");
        $daily_diet = DietDailyStatus::where([
            'date' => $date,
            'diet_id' => $id])->first();


        if ($daily_diet == null)
            $daily_diet = new DietDailyStatus();


        $daily_diet->diet_id = $id;
        $daily_diet->date = $date;

        if ($request->get("is_breakfast") !== null) {
            $daily_diet->is_breakfast = $request->get("is_breakfast");
        } else {
            $daily_diet->is_breakfast = null;
        }
        if ($request->get("is_lunch") !== null) {
            $daily_diet->is_lunch = $request->get("is_lunch");
        } else {
            $daily_diet->is_lunch = null;
        }

        if ($request->get("is_post_lunch") !== null) {
            $daily_diet->is_post_lunch = $request->get("is_post_lunch");
        } else {
            $daily_diet->is_post_lunch = null;
        }

        if ($request->get("is_dinner") !== null) {
            $daily_diet->is_dinner = $request->get("is_dinner");
        } else {
            $daily_diet->is_dinner = null;
        }

        if ($request->get("is_special") !== null) {
            $daily_diet->is_special = $request->get("is_special");
        } else {
            $daily_diet->is_special = null;
        }

        $daily_diet->created_by = \Auth::user()->id;
        $daily_diet->save();


        $total_price = (int)$daily_diet->is_breakfast + (int)$daily_diet->is_lunch + (int)$daily_diet->is_post_lunch + (int)$daily_diet->is_dinner + (int)$daily_diet->is_special;

        /*$wallet = Wallet::where(
            ['user_id' => $diet->patient_id,
                'status' => Wallet::STATUS_PENDING,
                "model_id" => $daily_diet->id,
            'model_type' => get_class($daily_diet)
            ])->first();

        if ($wallet == null) {
            $refund_wallet =  Wallet::where(
                [   'user_id' => $diet->patient_id,
                    'status' => Wallet::STATUS_PENDING,
                    "type" => Wallet::TYPE_REFUND
                ])->first();

            if ($refund_wallet != null) {
                if ($total_price < $refund_wallet->amount) {
                    $refundable_amount = $refund_wallet->amount - $total_price;
                    $refund_wallet->update([
                        'amount' => $refundable_amount
                    ]);
                }else{
                    $payable_amount =  $total_price - $refund_wallet->amount ;
                    $refund_wallet->update([
                        'amount' => 0
                    ]);
                    Wallet::create([
                        'user_id' => $diet->patient_id,
                        'status' => Wallet::STATUS_PENDING,
                        "model_id" => $daily_diet->id,
                        'model_type' => get_class($daily_diet),
                        'amount' => $payable_amount,
                        'created_by' => \Auth::user()->id
                    ])->first();
                }
            }else{
                Wallet::create([
                    'user_id' => $diet->patient_id,
                    'status' => Wallet::STATUS_PENDING,
                    "model_id" => $daily_diet->id,
                    'model_type' => get_class($daily_diet),
                    'amount' => $total_price,
                    'created_by' => \Auth::user()->id
                ])->first();
            }
        }*/

        return redirect()->route('Laralum::kitchen-patient.diet-chart', ['id' => $diet->booking_id])->with('status', 'Successfully Marked the meal statuses');
    }


    public function patientDietToggleAjax(Request $request)
    {
        Laralum::permissionToAccess('kitchen.diet_management');

        $data = [];
        $data['status'] = 'NOK';

        $id = $request->get('id');
        $meal_type = $request->get('meal_type');
        $diet = DietChart::find($id);
        $date = (string)date("Y-m-d");
        $daily_diet = DietDailyStatus::where([
            'date' => $date,
            'diet_id' => $id])->first();
        if ($daily_diet == null)
            $daily_diet = new DietDailyStatus();
        $daily_diet->diet_id = $id;
        $daily_diet->date = $date;
        if (DietDailyStatus::TYPE_BREAKFAST == $meal_type) {

            $daily_diet->is_breakfast = (string)(DietDailyStatus::STATUS_DONE . '-' . $diet->getDietPrice($meal_type));
        }
        if (DietDailyStatus::TYPE_LUNCH == $meal_type) {

            $daily_diet->is_lunch = (string)(DietDailyStatus::STATUS_DONE . '-' . $diet->getDietPrice($meal_type));
        }
        if (DietDailyStatus::TYPE_POST_LUNCH == $meal_type) {
            $daily_diet->is_post_lunch = (string)(DietDailyStatus::STATUS_DONE . '-' . $diet->getDietPrice($meal_type));
        }
        if (DietDailyStatus::TYPE_DINNER == $meal_type) {
            $daily_diet->is_dinner = (string)(DietDailyStatus::STATUS_DONE . '-' . $diet->getDietPrice($meal_type));
        }
        if (DietDailyStatus::TYPE_SPECIAL == $meal_type) {
            $daily_diet->is_special = (string)(DietDailyStatus::STATUS_DONE . '-' . $diet->getDietPrice($meal_type));
        }
        $daily_diet->created_by = \Auth::user()->id;
        if ($daily_diet->save()) {
            $data['status'] = 'OK';
        }

        $total_price = (int)$daily_diet->is_breakfast + (int)$daily_diet->is_lunch + (int)$daily_diet->is_post_lunch + (int)$daily_diet->is_dinner + (int)$daily_diet->is_special;

        /*$wallet = Wallet::where(
            ['user_id' => $diet->patient_id,
                'status' => Wallet::STATUS_PENDING,
                "model_id" => $daily_diet->id,
            'model_type' => get_class($daily_diet)
            ])->first();

        if ($wallet == null) {
            $refund_wallet =  Wallet::where(
                [   'user_id' => $diet->patient_id,
                    'status' => Wallet::STATUS_PENDING,
                    "type" => Wallet::TYPE_REFUND
                ])->first();

            if ($refund_wallet != null) {
                if ($total_price < $refund_wallet->amount) {
                    $refundable_amount = $refund_wallet->amount - $total_price;
                    $refund_wallet->update([
                        'amount' => $refundable_amount
                    ]);
                }else{
                    $payable_amount =  $total_price - $refund_wallet->amount ;
                    $refund_wallet->update([
                        'amount' => 0
                    ]);
                    Wallet::create([
                        'user_id' => $diet->patient_id,
                        'status' => Wallet::STATUS_PENDING,
                        "model_id" => $daily_diet->id,
                        'model_type' => get_class($daily_diet),
                        'amount' => $payable_amount,
                        'created_by' => \Auth::user()->id
                    ])->first();
                }
            }else{
                Wallet::create([
                    'user_id' => $diet->patient_id,
                    'status' => Wallet::STATUS_PENDING,
                    "model_id" => $daily_diet->id,
                    'model_type' => get_class($daily_diet),
                    'amount' => $total_price,
                    'created_by' => \Auth::user()->id
                ])->first();
            }
        }*/
        return $data;

    }

    public function requirements(Request $request)
    {
        Laralum::permissionToAccess('kitchen.requirements');
        $kitchen_items = KitchenItem::select('*')->orderBy('created_at', "DESC");
        $per_page = $request->get("per_page") ? $request->get("per_page") : 10;

        $pagination = true;
        if ($per_page == "All") {
            $pagination = false;
        }
        $count = $kitchen_items->count();

        if ($pagination == true) {
            $kitchen_items = $kitchen_items->paginate($per_page);
        } else {
            $kitchen_items = $kitchen_items->get();
        }

        $date_array = [];
        $date_array_ymd = [];
        $date_1 = date('Y-m-d');

        for ($i = 0; $i < 7; $i++) {
            $date_array[] = date("d, M", strtotime("+" . $i . "days"));
            $date_array_ymd[] = date("Y-m-d", strtotime("+" . $i . "days"));
            $date_2 = date('Y-m-d', strtotime("+" . $i . "days"));
        }

        $value = date('d/m/Y') . ' - ' . date('d/m/Y', strtotime("+7 days"));

        // print_r($value);exit;
        if (!empty($request->daterange)) {

            $value = $request->daterange;
            $date_ar = array_filter(explode('-', $request->daterange));
            $date_1 = date("Y-m-d", strtotime($date_ar[0]));
            $date_2 = date("Y-m-d", strtotime($date_ar[1]));

            $datediff = strtotime($date_2) - strtotime($date_1);
            $days = floor($datediff / (60 * 60 * 24));
            $days = $days + 1;
            $date_array = [];
            $date_array_ymd = [];

            for ($i = 0; $i < $days; $i++) {
                $date_array[] = date("d, M", strtotime($date_1 . " + " . $i . "days"));
                $date_array_ymd[] = date("Y-m-d", strtotime($date_1 . " +" . $i . "days"));
            }
        }

        return view('laralum.kitchen_item.requirements', compact('kitchen_items', 'count', 'date_array', 'date_array_ymd', 'value', 'date_1', 'date_2'));
    }

    public function ajaxRequirements(Request $request)
    {
        Laralum::permissionToAccess('kitchen.requirements');
        $count = 0;

        $per_page = $request->get("per_page") ? $request->get("per_page") : 10;
        $pagination = true;
        if ($per_page == "All") {
            $pagination = false;
        }
        $matchThese = [];
        $search = false;
        $option_ar = [];

        if ($request->get('name')) {
            $search = true;
            $option_ar[] = "Name";
            $matchThese['name'] = $request->get('name');
        }

        $options = implode(", ", $option_ar);

        $error = "Entered " . $options . " is not valid,
make sure that you are entering valid " . $options . " 
or search by other options";

        $per_page = $request->get("per_page") ? $request->get("per_page") : 10;
        $pagination = true;
        if ($per_page == "All") {
            $pagination = false;
        }


        $kitchen_items = KitchenItem::select('kitchen_items.*')->orderBy('created_at', "DESC");

        $per_page = $request->get("per_page") ? $request->get("per_page") : 10;

        $pagination = true;
        if ($per_page == "All") {
            $pagination = false;
        }
        $count = $kitchen_items->count();

        if ($pagination == true) {
            $kitchen_items = $kitchen_items->paginate($per_page);
        } else {
            $kitchen_items = $kitchen_items->get();
        }

        if ($search == true) {
            $kitchen_items = KitchenItem::select('kitchen_items.*')->orderBy('created_at', "DESC")->where(function ($query) use ($matchThese) {
                foreach ($matchThese as $key => $match) {
                    $query->where($key, 'like', "%$match%");
                }
            });
            $kitchen_items = $kitchen_items->get();
        }
        $date_array = [];
        $date_array_ymd = [];
        $date_1 = date('Y-m-d');

        for ($i = 0; $i < 7; $i++) {
            $date_array[] = date("d, M", strtotime("+" . $i . "days"));
            $date_array_ymd[] = date("Y-m-d", strtotime("+" . $i . "days"));
            $date_2 = date('Y-m-d', strtotime("+" . $i . "days"));
        }

        $value = date('d/m/Y') . ' - ' . date('d/m/Y', strtotime("+7 days"));

        // print_r($value);exit;
        if (!empty($request->daterange)) {

            $value = $request->daterange;
            $date_ar = array_filter(explode('-', $request->daterange));
            $date_1 = date("Y-m-d", strtotime($date_ar[0]));
            $date_2 = date("Y-m-d", strtotime($date_ar[1]));

            $datediff = strtotime($date_2) - strtotime($date_1);
            $days = floor($datediff / (60 * 60 * 24));
            $days = $days + 1;
            $date_array = [];
            $date_array_ymd = [];

            for ($i = 0; $i < $days; $i++) {
                $date_array[] = date("d, M", strtotime($date_1 . " + " . $i . "days"));
                $date_array_ymd[] = date("Y-m-d", strtotime($date_1 . " +" . $i . "days"));
            }
        }

        /*echo '<pre>'; print_r($matchThese['role_id']);exit;*/
        # Return the view
        return [
            'html' => view('laralum/kitchen_item.requirements_list', ['kitchen_items' => $kitchen_items, 'count' => $count, 'error' => $error, 'search' => $search, 'search_data' => $matchThese, 'date_1' => $date_1, 'date_2' => $date_2, 'date_array' => $date_array, 'date_array_ymd' => $date_array_ymd  ])->render()
        ];
    }


    public function printRequirements(Request $request)
    {
        //return $request->get('name');
        Laralum::permissionToAccess('kitchen.requirements');
        $kitchen_items = KitchenItem::select('*')->orderBy('created_at', "DESC");
        $kitchen_items = $kitchen_items->get();
        $print = true;
        $date_array = [];
        $date_array_ymd = [];
        $date_1 = date('Y-m-d');
        $matchThese = [];
        $matchTheseN = [];
        $search = false;
        $option_ar = [];

        if ($request->s && $request->s != 'null') {
            $search_data = json_decode($request->s, true);

            if (!empty($search_data['name'])) {
                $search = true;
                $option_ar[] = "Name";
                $matchThese['name'] = $search_data['name'];
                $namesearch  = $search_data['name'];
            }

            /*if ($request->get('name')) {
                return "sgdhsagfdhgasfdhg";
                $search = true;
                $option_ar[] = "Name";
                $matchThese['name'] = $request->get('name');
                $namesearch  = $request->get('name');
            }*/
        }

        $options = implode(", ", $option_ar);

        $error = "Entered " . $options . " is not valid,
make sure that you are entering valid " . $options . " 
or search by other options";

        
        if ($search == true) {
            $kitchen_items = KitchenItem::select('kitchen_items.*')->orderBy('created_at', "DESC")->where(function ($query) use ($matchThese) {
                foreach ($matchThese as $key => $match) {
                    $query->where($key, 'like', "%$match%");
                }
            });
        }
        else{
            $kitchen_items = KitchenItem::select('kitchen_items.*')->orderBy('created_at', "DESC");
        }

        $per_page = $request->get("per_page") ? $request->get("per_page") : 10;

        $pagination = true;
        if ($per_page == "All") {
            $pagination = false;
        }
        $count = $kitchen_items->count();

        if ($pagination == true) {
            $kitchen_items = $kitchen_items->paginate($per_page);
        } else {
            $kitchen_items = $kitchen_items->get();
        }

        //return $kitchen_items;

        $date_array = [];
        $date_array_ymd = [];
        $date_1 = date('Y-m-d');


        for ($i = 0; $i < 7; $i++) {
            $date_array[] = date("d, M", strtotime("+" . $i . "days"));
            $date_array_ymd[] = date("Y-m-d", strtotime("+" . $i . "days"));
            $date_2 = date('Y-m-d', strtotime("+" . $i . "days"));
        }

        $value = date('d/m/Y') . ' - ' . date('d/m/Y', strtotime("+7 days"));

        if (!empty($request->daterange)) {
            $value = $request->daterange;
            $date_ar = array_filter(explode('-', $request->daterange));
            $date_1 = date("Y-m-d", strtotime($date_ar[0]));
            $date_2 = date("Y-m-d", strtotime($date_ar[1]));

            $datediff = strtotime($date_2) - strtotime($date_1);
            $days = floor($datediff / (60 * 60 * 24));
            $days = $days + 1;
            $date_array = [];
            $date_array_ymd = [];

            for ($i = 0; $i < $days; $i++) {
                $date_array[] = date("d, M", strtotime($date_1 . " + " . $i . "days"));
                $date_array_ymd[] = date("Y-m-d", strtotime($date_1 . " +" . $i . "days"));
            }
        }

        return view('laralum.kitchen_item.print-requirements', compact('date_array_ymd', 'date_array', 'kitchen_items', 'print', 'date_1', 'date_2'));
    }

    public function request(Request $request, $id = null)
    {
        Laralum::permissionToAccess('kitchen_dashboard');
        $item = KitchenItem::find($id);

        if ($item == null)
            $item = new KitchenItem();

        if ($request->get("product_id") != null) {
            $item = KitchenItem::find($request->get("product_id"));

            if ($id == null)
                $item = KitchenItem::find($request->get("product_id"));

            $stock_items = Stock::where("product_id", $item->id)->get();

            if ($stock_items->count() > 0) {
                foreach ($stock_items as $stock_item) {
                    $quant = $request->get("item_" . $stock_item->id);
                    if ($quant != null) {
                        $stock_request = StockItemRequest::where([
                            'item_id' => $stock_item->id,
                            'quantity' => $quant,
                            'created_by' => \Auth::user()->id,
                            'status' => StockItemRequest::STATUS_PENDING
                        ])->first();
                        if ($stock_request == null)
                            $stock_request = new StockItemRequest();

                        $stock_request->item_id = $stock_item->id;
                        $stock_request->quantity = $quant;
                        $stock_request->created_by = \Auth::user()->id;
                        $stock_request->status = StockItemRequest::STATUS_PENDING;
                        $stock_request->save();
                    }
                }
            } else {
                return redirect()->back()->with('error', "No stock item added for this kitchen product");
            }

            return redirect()->back()->with('success', "Successfully Requested");
        }
        return view('laralum.kitchen_item.request', compact('item'));
    }

    public function requestStore(Request $request, $id = null)
    {
        $item = KitchenItem::find($request->get("product_id"));

        if ($id == null)
            $item = KitchenItem::find($request->get("product_id"));

        $stock_items = Stock::where("product_id", $item->id)->get();

        if ($stock_items->count() > 0) {
            foreach ($stock_items as $stock_item) {
                $quant = $request->get("item_" . $stock_item->id);
                if ($quant != null) {
                    $stock_request = StockItemRequest::where([
                        'item_id' => $stock_item->id,
                        'quantity' => $quant,
                        'created_by' => \Auth::user()->id,
                        'status' => StockItemRequest::STATUS_PENDING
                    ])->first();
                    if ($stock_request == null)
                        $stock_request = new StockItemRequest();
                    $stock_request->item_id = $stock_item->id;
                    $stock_request->quantity = $quant;
                    $stock_request->created_by = \Auth::user()->id;
                    $stock_request->status = StockItemRequest::STATUS_PENDING;
                    $stock_request->save();
                }
            }
        } else {
            return redirect()->back()->with('error', "No stock item added for this kitchen product");
        }

        return redirect()->back()->with("success", "Successfully requested items for " . $item->name);
    }

    public function getStockItems($id)
    {
        $item = KitchenItem::find($id);
        return $item->getStockItemList();
    }

    public function export(Request $request, $type)
    {
        Laralum::permissionToAccess('admin.kitchen_items');

        $per_page = $request->get("per_page") ? $request->get("per_page") : 10;
        $pagination = true;
        if ($per_page == "All") {
            $pagination = false;
        }
        $matchThese = [];
        $matchTheseN = [];
        $search = false;
        $option_ar = [];

        if ($request->s && $request->s != 'null') {
            $search_data = json_decode($request->s, true);

            if (!empty($search_data['name'])) {
                $search = true;
                $option_ar[] = "Name";
                $matchThese['name'] = $search_data['name'];
            }
            if (!empty($search_data['price'])) {
                $option_ar[] = "Price";
                $search = true;
                $matchThese['price'] = $search_data['price'];
            }

            if (!empty($search_data['type'])) {
                $option_ar[] = "Type";
                $search = true;
                $matchThese['type'] = $search_data['type'];
            }
            $ingredients = "";
            if (!empty($search_data['ingredients'])) {
                $option_ar[] = "ingredients";
                $search = true;
                $matchTheseN['ingredients'] = $search_data['ingredients'];
                $ingredients = $search_data['ingredients'];
            }
        }
        $options = implode(", ", $option_ar);

        $error = "Entered " . $options . " is not valid,
make sure that you are entering valid " . $options . " 
or search by other options";

        $per_page = $request->get("per_page") ? $request->get("per_page") : 10;
        $pagination = true;
        if ($per_page == "All") {
            $pagination = false;
        }

        $kitchen_items = KitchenItem::select('kitchen_items.*')->orderBy('kitchen_items.created_at', "DESC");
        if ($search == true) {
            $kitchen_items = KitchenItem::select('kitchen_items.*')
                ->orderBy('kitchen_items.created_at', "DESC")->where(function ($query) use ($matchThese) {
                    foreach ($matchThese as $key => $match) {
                        $query->where($key, 'like', "%$match%");
                    }
                });

            if ($ingredients != "") {
                $kitchen_items = $kitchen_items->leftjoin('stock', 'stock.product_id', '=', 'kitchen_items.id')
                    ->where('stock.name', 'like', "%$ingredients%")->distinct();
            }

        }

        $count = $kitchen_items->count();

        if ($count <= 10) {
            $pagination = false;
        }
        if ($pagination == true) {
            $kitchen_items = $kitchen_items->paginate($per_page);
        } else {
            $kitchen_items = $kitchen_items->get();
        }

        $kitchen_items_array[] = [
            'Name', 'Meal Type', 'Price(in INR)'
        ];
        foreach ($kitchen_items as $kitchen_item) {
            $kitchen_items_array[] = [
                $kitchen_item->name,
                $kitchen_item->getTypeOptions($kitchen_item->type),
                $kitchen_item->price
            ];
        }

        // Generate and return the spreadsheet
        $excel = \App::make('excel');
        $excel = $excel->create('kitchen_items', function ($excel) use ($kitchen_items_array) {

            // Set the spreadsheet title, creator, and description
            $excel->setTitle('Kitchen Items');

            // Build the spreadsheet, passing in the payments array
            $excel->sheet('sheet1', function ($sheet) use ($kitchen_items_array) {
                $sheet->fromArray($kitchen_items_array, null, 'A1', false, false);
            });

        });
        if ($type == Settings::EXPORT_CSV) {
            $excel->download('csv');
        } elseif ($type == Settings::EXPORT_EXCEL) {
            $excel->download('xls');
        } else {
            // $excel->download('pdf');
            $pdf = \PDF::loadView('booking.pdf', array('data' => $kitchen_items_array));
            return $pdf->download('kitchen_items.pdf');
        }

        return redirect()->back()->with('error', 'Something went Wrong!!!');
    }

    public function exportRequirements(Request $request, $type)
    {
        Laralum::permissionToAccess('kitchen.requirements');
        $count = 0;

        $per_page = $request->get("per_page") ? $request->get("per_page") : 10;
        $pagination = true;
        if ($per_page == "All") {
            $pagination = false;
        }
        $matchThese = [];
        $search = false;
        $option_ar = [];

        if ($request->get('name')) {
            $search = true;
            $option_ar[] = "Name";
            $matchThese['name'] = $request->get('name');
        }

        if ($request->s && $request->s != 'null') {
            $search_data = json_decode($request->s, true);

            if (!empty($search_data['name'])) {
                $search = true;
                $option_ar[] = "Name";
                $matchThese['name'] = $search_data['name'];
            }
        }

        $kitchen_items = KitchenItem::select('kitchen_items.*')->orderBy('created_at', "DESC");
        if ($search == true) {
            $kitchen_items = KitchenItem::select('kitchen_items.*')->orderBy('created_at', "DESC")->where(function ($query) use ($matchThese) {
                foreach ($matchThese as $key => $match) {
                    $query->where($key, 'like', "%$match%");
                }
            });
            $kitchen_items = $kitchen_items->get();
        } else {
            $kitchen_items = $kitchen_items->get();
        }

        $print = true;

        $date_array = [];
        $date_array_ymd = [];
        $date_1 = date('Y-m-d');

        for ($i = 0; $i < 7; $i++) {
            $date_array[] = date("d, M", strtotime("+" . $i . "days"));
            $date_array_ymd[] = date("Y-m-d", strtotime("+" . $i . "days"));
            $date_2 = date('Y-m-d', strtotime("+" . $i . "days"));
        }

        $value = date('d/m/Y') . ' - ' . date('d/m/Y', strtotime("+7 days"));

        // print_r($value);exit;
        if (!empty($request->daterange)) {
            $value = $request->daterange;
            $date_ar = array_filter(explode('-', $request->daterange));
            $date_1 = date("Y-m-d", strtotime($date_ar[0]));
            $date_2 = date("Y-m-d", strtotime($date_ar[1]));

            $datediff = strtotime($date_2) - strtotime($date_1);
            $days = floor($datediff / (60 * 60 * 24));
            $days = $days + 1;
            $date_array = [];
            $date_array_ymd = [];

            for ($i = 0; $i < $days; $i++) {
                $date_array[] = date("d, M", strtotime($date_1 . " + " . $i . "days"));
                $date_array_ymd[] = date("Y-m-d", strtotime($date_1 . " +" . $i . "days"));
            }
        }
        $kitchen_headings = [
            'name', 'type'
        ];

        foreach ($date_array_ymd as $date) {
            $kitchen_headings[] = $date;
        }


        $kitchen_items_array[] = $kitchen_headings;

        foreach ($kitchen_items as $kitchen_item) {
            $kitchen_items_array_req = [
                $kitchen_item->name,
                $kitchen_item->getTypeOptions($kitchen_item->type)
            ];

            foreach ($date_array_ymd as $date) {
                $kitchen_items_array_req[] = $kitchen_item->getRequiredItems($date);
            }

            $kitchen_items_array[] = $kitchen_items_array_req;
        }

        //echo '<pre>';print_r($kitchen_items_array);exit;

        // Generate and return the spreadsheet
        $excel = \App::make('excel');
        $excel = $excel->create('requirements', function ($excel) use ($kitchen_items_array) {

            $excel->setTitle('Requirements');

            // Build the spreadsheet, passing in the payments array
            $excel->sheet('sheet1', function ($sheet) use ($kitchen_items_array) {
                $sheet->fromArray($kitchen_items_array, null, 'A1', false, false);
            });

        });

        if ($type == Settings::EXPORT_CSV) {
            $excel->download('csv');
        } elseif ($type == Settings::EXPORT_EXCEL) {
            $excel->download('xls');
        } else {
            // $excel->download('pdf');
            $pdf = \PDF::loadView('booking.pdf', array('data' => $kitchen_items_array));
            return $pdf->download();
        }

        return redirect()->back()->with('error', 'Something went Wrong!!!');
    }

    public function mealStatus(Request $request)
    {
        $data['breakfast'] = DietChart::getDailyStatus(DietChartItems::TYPE_BREAKFAST, $request);
        $data['lunch'] = DietChart::getDailyStatus(DietChartItems::TYPE_LUNCH, $request);
        $data['post_lunch'] = DietChart::getDailyStatus(DietChartItems::TYPE_POST_LUNCH, $request);
        $data['dinner'] = DietChart::getDailyStatus(DietChartItems::TYPE_DINNER, $request);
        $data['special'] = DietChart::getDailyStatus(DietChartItems::TYPE_SPECIAL, $request);
        $data['patients'] = Booking::has('diets')->where('Status', Booking::STATUS_COMPLETED)/*->orderBy('id', 'DESC')*/
        ->get();
        /*
                $data['patients'] = DietChart::where('status', DietChart::STATUS_PENDING)->where('start_date', (string) date("Y-m-d"))->get();*/
        return view('laralum.kitchen_item.meal-status', $data);
    }


    public function printMealStatusCombined(Request $request)
    {
        $data['breakfast'] = DietChart::getDailyStatus(DietChartItems::TYPE_BREAKFAST, $request);
        $data['lunch'] = DietChart::getDailyStatus(DietChartItems::TYPE_LUNCH, $request);
        $data['post_lunch'] = DietChart::getDailyStatus(DietChartItems::TYPE_POST_LUNCH, $request);
        $data['dinner'] = DietChart::getDailyStatus(DietChartItems::TYPE_DINNER, $request);
        $data['special'] = DietChart::getDailyStatus(DietChartItems::TYPE_SPECIAL, $request);
        $data['back_url'] = url('/admin/meal-status');
        $data['print'] = true;
        $data['combined'] = true;
        return view('laralum.kitchen_item.print_meal_status', $data);
    }

    public function exportMealStatusCombined(Request $request, $type)
    {
        $breakfast = DietChart::getDailyStatus(DietChartItems::TYPE_BREAKFAST, $request);
        $lunch = DietChart::getDailyStatus(DietChartItems::TYPE_LUNCH, $request);
        $post_lunch = DietChart::getDailyStatus(DietChartItems::TYPE_POST_LUNCH, $request);
        $dinner = DietChart::getDailyStatus(DietChartItems::TYPE_DINNER, $request);
        $special = DietChart::getDailyStatus(DietChartItems::TYPE_SPECIAL, $request);
        $diet_chart_headers[] = [
            '',
            'Total Patient',
            'Had Meal',
            'Pending',
            'Didn\'t Come',
        ];
        $diet_chart_ar[] = [
            'Breakfast',
            $breakfast['total_patient'],
            $breakfast['had_meal'],
            $breakfast['pending'] ,
            $breakfast['not_come']
        ];

        $diet_chart_ar[] = [
            'Lunch',
            $lunch['total_patient'],
            $lunch['had_meal'],
            $lunch['pending'] ,
            $lunch['not_come']
        ];

        $diet_chart_ar[] = [
            'Post Lunch',
            $post_lunch['total_patient'],
            $post_lunch['had_meal'],
            $post_lunch['pending'] ,
            $post_lunch['not_come']
        ];

        $diet_chart_ar[] = [
            'Dinner',
            $dinner['total_patient'],
            $dinner['had_meal'],
            $dinner['pending'] ,
            $dinner['not_come']
        ];
        $diet_chart_ar[] = [
            'Special',
            $special['total_patient'],
            $special['had_meal'],
            $special['pending'] ,
            $special['not_come']
        ];

        $diet_ar = array_merge($diet_chart_headers, $diet_chart_ar);

        // Generate and return the spreadsheet
        $excel = \App::make('excel');
        $excel = $excel->create('daily_meal_servings-' . date("Y-m-d"), function ($excel) use ($diet_ar) {

            // Set the spreadsheet title, creator, and description
            $excel->setTitle('Daily Meal Servings-' . date("Y-m-d"));
            // Build the spreadsheet, passing in the payments array
            $excel->sheet('sheet1', function ($sheet) use ($diet_ar) {
                $sheet->fromArray($diet_ar, null, 'A1', false, false);
            });
        });

        if ($type == Settings::EXPORT_CSV) {
            $excel->download('csv');
        } elseif ($type == Settings::EXPORT_EXCEL) {
            $excel->download('xls');
        } else {
            // $excel->download('pdf');
            $pdf = \PDF::loadView('booking.pdf', array('data' => $diet_ar));
            return $pdf->download();
        }

        return redirect()->back()->with('error', 'Something went Wrong!!!');
    }

    public function printMealStatus()
    {
        $data['patients'] = Booking::has('diets')->where('Status', Booking::STATUS_COMPLETED)/*->orderBy('id', 'DESC')*/
        ->get();
        $data['back_url'] = url('/admin/meal-status');
        $data['print'] = true;
        return view('laralum.kitchen_item.print_meal_status', $data);
    }

    public function exportMealStatus(Request $request, $type)
    {
        $date = $request->date ? date("Y-m-d", strtotime($request->date)) : date('Y-m-d');
        $patients = Booking::has('diets')->where('Status', Booking::STATUS_COMPLETED)/*->orderBy('id', 'DESC')*/
        ->get();

        $diet_chart_headers[] = [
            'Patient Name',
            'UHID',
            'Breakfast',
            'Lunch',
            'Post Lunch',
            'Dinner',
            'Special',
        ];
        $diet_chart_ar = [];

        foreach ($patients as $patient) {
            $diet_chart_ar[] = [
                $patient->userProfile->first_name . ' ' . $patient->userProfile->last_name,
                $patient->user->uhid,
                $patient->getDietStatus(\App\DietChartItems::TYPE_BREAKFAST),
                $patient->getDietStatus(\App\DietChartItems::TYPE_LUNCH),
                $patient->getDietStatus(\App\DietChartItems::TYPE_POST_LUNCH),
                $patient->getDietStatus(\App\DietChartItems::TYPE_DINNER),
                $patient->getDietStatus(\App\DietChartItems::TYPE_SPECIAL),
            ];
        }

        $diet_ar = array_merge($diet_chart_headers, $diet_chart_ar);

        // Generate and return the spreadsheet
        $excel = \App::make('excel');
        $excel = $excel->create('daily_meal_servings-' . date("Y-m-d"), function ($excel) use ($diet_ar) {

            // Set the spreadsheet title, creator, and description
            $excel->setTitle('Daily Meal Servings-' . date("Y-m-d"));
            // Build the spreadsheet, passing in the payments array
            $excel->sheet('sheet1', function ($sheet) use ($diet_ar) {
                $sheet->fromArray($diet_ar, null, 'A1', false, false);
            });

        });

        if ($type == Settings::EXPORT_CSV) {
            $excel->download('csv');
        } elseif ($type == Settings::EXPORT_EXCEL) {
            $excel->download('xls');
        } else {
            // $excel->download('pdf');
            $pdf = \PDF::loadView('booking.pdf', array('data' => $diet_ar));
            return $pdf->download();
        }

        return redirect()->back()->with('error', 'Something went Wrong!!!');
    }



    public function mealServing(Request $request, $meal_type = null)
    {
        $diet_chart = [];
        $i = 1;
        $print_url = "";
        $export_pdf_url = "";
        $export_csv_url = "";
        $export_xsl_url = "";
        if ($meal_type == null) {
            $meal_type = $request->get('filter_meal_type');
        }
        $date = date("Y-m-d", strtotime($request->start_date));

        if ($meal_type != "") {
            $diet_chart = DietChart::getDailyServings($meal_type, $date);
            $print_url = url('admin/print-meal-servings/' . $meal_type);
            $export_pdf_url = url('admin/export-meal-servings/' . $meal_type . '/' . Settings::EXPORT_PDF);
            $export_csv_url = url('admin/export-meal-servings/' . $meal_type . '/' . Settings::EXPORT_CSV);
            $export_xsl_url = url('admin/export-meal-servings/' . $meal_type . '/' . Settings::EXPORT_EXCEL);
        }

        return view('laralum.kitchen_item.meal-servings', compact('diet_chart', 'print_url', 'export_pdf_url', 'export_csv_url', 'export_xsl_url', 'meal_type'));
    }

    public function mealServingsAjax(Request $request, $meal_type = null)
    {
        if ($meal_type == null) {
            $meal_type = $request->get('filter_meal_type');
        }

        if ($meal_type != null) {
            $date = $request->date ? date("Y-m-d", strtotime($request->date)) : date('Y-m-d');

            $diet_chart = DietChart::getDailyServings($meal_type, $date);

            $print_url = url('admin/print-meal-servings/' . $meal_type);
            $export_pdf_url = url('admin/export-meal-servings/' . $meal_type . '/' . Settings::EXPORT_PDF);
            $export_csv_url = url('admin/export-meal-servings/' . $meal_type . '/' . Settings::EXPORT_CSV);
            $export_xsl_url = url('admin/export-meal-servings/' . $meal_type . '/' . Settings::EXPORT_EXCEL);

            return [
                'print_url' => $print_url,
                'export_pdf_url' => $export_pdf_url,
                'export_csv_url' => $export_csv_url,
                'export_xsl_url' => $export_xsl_url,
                'html' => view('laralum.kitchen_item._servings', compact('diet_chart', 'meal_type', 'print_url', 'export_pdf_url', 'export_csv_url', 'export_xsl_url', 'meal_type'))->render()
            ];
        }

        return [
            'html' => ""
        ];
    }

    public function printMealServing(Request $request, $type)
    {
        $date = $request->date ? date("Y-m-d", strtotime($request->date)) : date('Y-m-d');
        $diet_chart = DietChart::getDailyServings($type, $date);
        $back_url = url('/admin/meal-servings/' . $type);
        $meal_type = $type;
        $print = true;
        return view('laralum.kitchen_item.print_meal_servings', compact('diet_chart', 'meal_type', 'back_url', 'print'));
    }


    public function exportMealServings(Request $request, $meal_type, $type)
    {
        $date = $request->date ? date("Y-m-d", strtotime($request->date)) : date('Y-m-d');
        $diet_chart = DietChart::getDailyServings($meal_type, $date);
        $diet_ar = [];
        $diet_chart_headers[] = [
            'Sno',
            'Patient Id',
            'Patient Name',
            'Item 1',
            'Item 2',
            'Item 3',
            'Item 4',
            'Item 5',
            'Item 6',
            'Item 7',
            'Notes'
        ];
        $diet_chart_ar = [];
        foreach ($diet_chart as $diet) {
            unset($diet['id']);
            $diet_chart_ar[] = $diet;
        }
        $diet_ar = array_merge($diet_chart_headers, $diet_chart_ar);

        // Generate and return the spreadsheet
        $excel = \App::make('excel');
        $excel = $excel->create('daily_meal_servings-' . date("Y-m-d"), function ($excel) use ($diet_ar) {

            // Set the spreadsheet title, creator, and description
            $excel->setTitle('Daily Meal Servings-' . date("Y-m-d"));
            $excel->setCreator('Laravel')->setCompany('WJ Gilmore, LLC');
            $excel->setDescription('Meal servings');

            // Build the spreadsheet, passing in the payments array
            $excel->sheet('sheet1', function ($sheet) use ($diet_ar) {
                $sheet->fromArray($diet_ar, null, 'A1', false, false);
            });

        });

        if ($type == Settings::EXPORT_CSV) {
            $excel->download('csv');
        } elseif ($type == Settings::EXPORT_EXCEL) {
            $excel->download('xls');
        } else {
            // $excel->download('pdf');
            $pdf = \PDF::loadView('booking.pdf', array('data' => $diet_ar));
            return $pdf->download();
        }

        return redirect()->back()->with('error', 'Something went Wrong!!!');
    }

    public function ajaxUpdate(Request $request)
    {
        Laralum::permissionToAccess('admin.kitchen_items');

        $per_page = $request->get("per_page") ? $request->get("per_page") : 10;
        $pagination = true;
        if ($per_page == "All") {
            $pagination = false;
        }
        $matchThese = [];
        $matchTheseN = [];
        $search = false;
        $option_ar = [];

        if ($request->get('name')) {
            $search = true;
            $option_ar[] = "Name";
            $matchThese['name'] = $request->get('name');
        }
        if ($request->get('type') !== null) {
            $option_ar[] = "Price";
            $search = true;
            $matchThese['price'] = $request->get('price');
        }
        if ($request->get('type') !== null) {
            $option_ar[] = "Type";
            $search = true;
            $matchThese['type'] = $request->get('type');
        }
        $ingredients = "";
        if ($request->get('ingredients') !== null) {
            $option_ar[] = "ingredients";
            $search = true;
            $matchTheseN['ingredients'] = $request->get('ingredients');
            $ingredients = $request->get('ingredients');
        }
        $options = implode(", ", $option_ar);

        $error = "Entered " . $options . " is not valid,
make sure that you are entering valid " . $options . " 
or search by other options";

        $per_page = $request->get("per_page") ? $request->get("per_page") : 10;
        $pagination = true;
        if ($per_page == "All") {
            $pagination = false;
        }

        $kitchen_items = KitchenItem::select('kitchen_items.*')->orderBy('kitchen_items.created_at', "DESC");
        if ($search == true) {
            $kitchen_items = KitchenItem::select('kitchen_items.*')
                ->orderBy('kitchen_items.created_at', "DESC")->where(function ($query) use ($matchThese) {
                    foreach ($matchThese as $key => $match) {
                        $query->where($key, 'like', "%$match%");
                    }
                });

            if ($ingredients != "") {
                $kitchen_items = $kitchen_items->leftjoin('stock', 'stock.product_id', '=', 'kitchen_items.id')
                    ->where('stock.name', 'like', "%$ingredients%")->distinct();
            }

        }

        $count = $kitchen_items->count();

        if ($count <= 10) {
            $pagination = false;
        }
        if ($pagination == true) {
            $kitchen_items = $kitchen_items->paginate($per_page);
        } else {
            $kitchen_items = $kitchen_items->get();
        }
        /*echo '<pre>'; print_r($matchThese['role_id']);exit;*/
        # Return the view
        return [
            'html' => view('laralum/kitchen_item/_list', ['kitchen_items' => $kitchen_items, 'count' => $count, 'error' => $error, 'search' => $search, 'search_data' => array_merge($matchThese, $matchTheseN)])->render()
        ];
    }


    public function itemRequests(Request $request)
    {
        //Laralum::permissionToAccess('admin.stock_item_request');
        $per_page = $request->get("per_page") ? $request->get("per_page") : 10;
        $pagination = true;
        if ($per_page == "All") {
            $pagination = false;
        }

        $item_requests = StockItemRequest::has('item')->where('created_by',\Auth::user()->id)->orderBy('created_at', "DESC");

        $count = $item_requests->count();
        if ($pagination == true) {
            $item_requests = $item_requests->paginate($per_page);
        } else {
            $item_requests = $item_requests->get();
        }

        return view('laralum.kitchen_item.request_list', compact('item_requests', 'count'));
    }


    public function destroyRequest(Request $request, $id)
    {   # Check permissions
        //Laralum::permissionToAccess('admin.stock_item_request');
        //return 'jfgdfghdgdfg';
        # Select Stock
         $stock = StockItemRequest::findOrFail($id);
        # Delete Stock
        $stock->delete();
        # Redirect the admin
        return redirect()->route('Laralum::kitchen-item.requests')->with('success', trans('laralum.msg_item_request_deleted'));

    }

    public function printItemRequests(Request $request)
    {
        //Laralum::permissionToAccess('admin.stock_item_request');
        $per_page = $request->get("per_page") ? $request->get("per_page") : 10;
        $pagination = true;
        if ($per_page == "All") {
            $pagination = false;
        }

        $item_requests = StockItemRequest::has('item')->where('created_by',\Auth::user()->id)
        ->orderBy('created_at', "DESC");

        $count = $item_requests->count();
        if ($pagination == true) {
            $item_requests = $item_requests->paginate($per_page);
        } else {
            $item_requests = $item_requests->get();
        }
        $print = true;
        $back_url = url('/admin/kitchen-item/requests');

        return view('laralum.stock.print_item_requests', compact('print','item_requests', 'count', 'back_url'));
    }

    public function exportItemRequests(Request $request, $type)
    {
       // Laralum::permissionToAccess('admin.stock_item_request');
        $per_page = $request->get("per_page") ? $request->get("per_page") : 10;
        $pagination = true;
        if ($per_page == "All") {
            $pagination = false;
        }

        $item_requests = StockItemRequest::has('item')->where('created_by',\Auth::user()->id)
        ->orderBy('created_at', "DESC");

        $count = $item_requests->count();
        if ($pagination == true) {
            $item_requests = $item_requests->paginate($per_page);
        } else {
            $item_requests = $item_requests->get();
        }

        $all_ar[] = [
            'Name',
            'Requested By',
            'Requested On',
            'Current Quantity (when requested)',
            'Required Quantity',
            'Approved Quantity',
            'Approved Date',
            'Status',
        ];

        foreach ($item_requests as $item_request)
        {
            $all_ar[] = [
                $item_request->item->name,
                $item_request->createUser->name,
                $item_request->created_at != null ? date("d-m-Y h:i a", strtotime($item_request->created_at->setTimezone(env('TIMEZONE'))->toDateTimeString())) : "",
                $item_request->status == \App\StockItemRequest::STATUS_PENDING ? $item_request->item->current_quantity : $item_request->item_qty,
                $item_request->quantity,
                $item_request->approved_qty,
                $item_request->approved_date != null ? date("d-m-Y h:i a", strtotime($item_request->approved_date)) : '',
                $item_request->getStatusOptions($item_request->status)
            ];
        }


        // Generate and return the spreadsheet
        $excel = \App::make('excel');
        $excel = $excel->create('Stock Items Requests', function ($excel) use ($all_ar) {

            // Set the spreadsheet title, creator, and description
            $excel->setTitle('Stock Items Requests');

            // Build the spreadsheet, passing in the payments array
            $excel->sheet('sheet1', function ($sheet) use ($all_ar) {
                $sheet->fromArray($all_ar, null, 'A1', false, false);
            });

        });
        if ($type == Settings::EXPORT_CSV) {
            $excel->download('csv');
        } elseif ($type == Settings::EXPORT_EXCEL) {
            $excel->download('xls');
        } else {
            $pdf = PDF::loadView('booking.pdf', array('data' => $all_ar));
            return $pdf->download('stock_item_requests.pdf');
        }

        return redirect()->back()->with('error', 'Something went Wrong!!!');
    }


}
