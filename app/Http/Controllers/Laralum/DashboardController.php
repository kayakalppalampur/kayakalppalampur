<?php

namespace App\Http\Controllers\Laralum;

use App\AdminSetting;
use App\Booking;
use App\BookingRoom;
use App\Building;
use App\DietChartItems;
use App\KitchenItem;
use App\PatientToken;
use App\User;
use Doctrine\DBAL\Schema\Schema;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Laralum;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // dd(\Auth::user()->isLabAttendant());
         //return $request->all();
         $items = DietChartItems::get();

         foreach ($items as $item) {
             $ki = KitchenItem::find($item->item_id);
             if ($ki) {
                 $item->item_price = $ki->price;
                 $item->save();
             }
         }

        if (\Auth::user()->isSuperAdmin()) {
            $user = \Auth::user();
            return view('laralum/dashboard/index');
        }

        if (\Auth::user()->isLabAttendant()) {
            $user = \Auth::user();
            return view('laralum/dashboard/index');
        }

        if (\Auth::user()->isReception()) {
            $user = \Auth::user();
            return view('laralum/dashboard/reception', compact('user'));
        }

        if (\Auth::user()->isKitchen())
            return view('laralum/dashboard/kitchen');

        if (\Auth::user()->isInventory())
            return view('laralum/dashboard/inventory');

        if (\Auth::user()->isAccount())
            return view('laralum/dashboard/account');

        if (\Auth::user()->isDoctor()) {
            $search = false;
            $error = false;
            $patient = [];
            $matchThese = [];
            $option_ar = [];

            if ($request->has('filter_patient_id') && $request->get('filter_patient_id') != "") {
                $option_ar[] = "Patient Id";
                $search = true;
                $matchThese['kid'] = $request->get('filter_patient_id');
            }
            $filter_name = "";
            if ($request->has('filter_name') && $request->get('filter_name') != "") {
                $option_ar[] = "Name";
                $search = true;
                $filter_name = $request->get('filter_name');
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
            if ($request->has('filter_mobile') && $request->get('filter_mobile') != "") {
                $option_ar[] = "Mobile";
                $search = true;
                $matchThese['mobile'] = $request->get('filter_mobile');
            }

            $filter_email = "";

            if ($request->has('filter_email')) {
                $option_ar[] = "Email";
                $search = true;
                $filter_email = $request->get('filter_email');
            }

            $booking = [];
            if ($search == true) {
                $booking = Booking::select("bookings.*")
                    ->leftJoin('users', 'users.id', '=', 'bookings.user_id')
                    ->leftJoin('user_profiles', 'bookings.user_id', '=', 'user_profiles.user_id')
                    ->where('bookings.status', Booking::STATUS_COMPLETED)
                    ->where(function ($query) use ($matchThese, $filter_email, $filter_name) {
                        foreach ($matchThese as $key => $match) {
                            $query->where('user_profiles.' . $key, 'like', "%$match%");
                        }
                        if ($filter_email != "") {
                            $query->where('users.email', 'like', "%$filter_email%");
                        }
                        if ($filter_name != "") {
                            $query->where('users.name', 'like', "%$filter_name%");
                        }
                    })->orderBy('users.created_at', "DESC")->first();
            }
            $options = implode(", ", $option_ar);

            $error = "Entered " . $options . " is not valid,
    make sure that you are entering valid " . $options . " 
    or search by other options";

            return view('laralum/dashboard/doctor', compact('search', 'error', 'booking'));
        }

        return view('laralum/dashboard/index');
    }

    public function dailyBuildingStatus(Request $request)
    {
        $date = $request->date ? $request->date : date("Y-m-d");
        $building_id = $request->building_id;
        $building = Building::find($building_id);

        if (empty($building)) {
            $building = Building::first();
            $building_id = $building->id;
        }
        return view('laralum.dashboard.daily_building_status', compact('date', 'building_id', 'building'));
    }

    public function dailyBuildingStatusPrint(Request $request)
    {
        $date = $request->date ? $request->date : date("Y-m-d");
        $building_id = $request->building_id;
        $building = Building::find($building_id);
        if (empty($building)) {
            $building = Building::first();
            $building_id = $building->id;
        }

        return view('laralum.dashboard.daily_building_status_print', compact('date', 'building_id', 'building'));
    }

    public function dailySituationReport(Request $request)
    {
        return view('laralum.dashboard.daily_situation_report');
    }

    public function dailySituationReportPrint()
    {
        return view('laralum.dashboard.daily_situation_report_print');
    }

    public function priceSettings(Request $request)
    {
        if ($request->get("setting_id") != null) {
            //return $request->all();
            $setting = AdminSetting::find($request->get("setting_id"));

            $setting->update([
                'price' => $request->get("price_" .$request->get("setting_id"))
            ]);
        }

        $admin_settings = AdminSetting::paginate(10);

        return view('laralum.dashboard.admin_settings', compact('admin_settings'));
    }

    public function adminSettingStore(Request $request)
    {
        $admin_setting = AdminSetting::where('setting_name_slug', $request->attr)->first();

        if ($admin_setting) {
            $value = $request->value;
            $admin_setting->price = $value;
            $admin_setting->save();
            return [
                'status' => 'success',
                'message' => 'Successfully saved'
            ];
        }

        return [
            'status' => 'error',
            'message' => 'Something went wrong'
        ];
    }
}
