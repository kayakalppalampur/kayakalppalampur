<?php

namespace App\Http\Controllers\Laralum;

use App\AyurvedaAshtvidhExamination;
use App\AyurvedAturExamination;
use App\AyurvedDhatuExamination;
use App\AyurvedDoshExamination;
use App\Booking;
use App\Bill;
use App\BookingDiscount;
use App\BookingRoom;
use App\CardiovascularExamination;
use App\ConsultationCharge;
use App\DietChart;
use App\DietChartItems;
use App\DietDailyStatus;
use App\DischargePatient;
use App\DiscountOffer;
use App\EmailTemplate;
use App\ExternalService;
use App\Feedback;
use App\FeedbackQuestion;
use App\GastrointestinalExamination;
use App\GenitourinaryExamination;
use App\HealthIssue;
use App\Http\Controllers\Controller;
use App\Member;
use App\NeurologicalExamination;
use App\Notification;
use App\OpdTokens;
use App\PatientDetails;
use App\PatientFollowUp;
use App\PatientToken;
use App\PaymentDetail;
use App\PhysicalExamination;
use App\PhysiotherapyMotorExamination;
use App\PhysiotherapyPainAssesment;
use App\PhysiotherapyPainExamination;
use App\PhysiotherapySensoryExamination;
use App\PhysiotherapySystemicExamination;
use App\Profession;
use App\RespiratoryExamination;
use App\Role;
use App\Room;
use App\Settings;
use App\Misc;
use App\TreatmentToken;
use App\User;
use App\UserAddress;
use App\UserProfile;
use App\VitalData;
use App\Wallet;
use App\AdminSetting;
use App\UserExtraService;
use App\State;
use App\Country;
use App\PatientLabTest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Input;
use Laralum;
use Milon\Barcode\DNS1D;
use Milon\Barcode\DNS2D;
use PDF;
use SnappyPDF;

class BookingController extends Controller
{
    /**
     * get all resource listing
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */

    public function __construct()
    {
        Input::merge(array_map(function ($v) {
            return is_string($v) ? trim($v) : $v;
        }, Input::all()));
    }

    public function ipdIndex(Request $request)
    {
        Laralum::permissionToAccess(['admin.patients.list', 'doctor.patients', 'admin.bookings.list']);
        $matchThese = [];
        $bookingmatchThese = [];
        $usermatchThese = [];
        $matchTheseAddress = [];

        $search = false;
        $option_ar = [];

        if (!empty($request->get('kid'))) {
            $option_ar[] = "Patient Id";
            $search = true;
            $matchThese['kid'] = $request->get('kid');
        }

        if (!empty($request->get('uhid'))) {
            $option_ar[] = "UHID";
            $search = true;
            $usermatchThese['uhid'] = $request->get('uhid');
        }
        if (!empty($request->get('booking_id'))) {
            $option_ar[] = "Booking Id";
            $search = true;
            $bookingmatchThese['booking_id'] = $request->get('booking_id');
        }

        $filter_name = '';
        if (!empty($request->get('first_name'))) {
            $option_ar[] = "Name";
            $search = true;
            $filter_name = $request->first_name;
        }

        $search_string = '';
        if (!empty($request->get('city'))) {
            $option_ar[] = "City";
            $search = true;
            $search_string = $request->city;
            $array = explode(',', $request->city);

            $matchTheseAddress['city'] = $array[0];
            if (isset($array[1])) {
                $matchTheseAddress['state'] = $array[1];
            }

            if (isset($array[2])) {
                $matchTheseAddress['country'] = $array[2];
            }
        }

        if (!empty($request->get('state'))) {
            $option_ar[] = "State";
            $search = true;
            $matchTheseAddress['state'] = $request->get('state');
        }

        if (!empty($request->get('country'))) {
            $option_ar[] = "Country";
            $search = true;
            $matchTheseAddress['country'] = $request->get('country');
        }

        //print_r($matchTheseAddress);exit;

        if (!empty($request->get('mobile'))) {
            $option_ar[] = "Mobile";
            $search = true;
            $matchThese['mobile'] = $request->get('mobile');
        }

        if (!empty($request->get('patient_type'))) {
            $option_ar[] = "Patient Type";
            $search = true;
            $bookingmatchThese['patient_type'] = $request->get('patient_type');
        }
        $acm_status = "";
        if (!empty($request->get('accommodation_status'))) {
            $option_ar[] = "Accommodation Status";
            $search = true;
            $bookingmatchThese['accommodation_status'] = $request->get('accommodation_status');
            $acm_status = $request->get('accommodation_status');
        }
        $booking_status = "";
        if (!empty($request->get('status'))) {
            $option_ar[] = "Status";
            $search = true;
            $bookingmatchThese['status'] = $request->get('status');
            $booking_status = $request->get('status');
        }

        if (!empty($request->get('email'))) {
            $option_ar[] = "Email";
            $search = true;
            $usermatchThese['email'] = $request->get('email');
        }

        $options = implode(", ", $option_ar);

        $error = "Entered " . $options . " is not valid,
make sure that you are entering valid " . $options . " 
or search by other options";
        $user = [];
        $per_page = $request->get("per_page") ? $request->get("per_page") : 10;
        $pagination = true;
        if ($per_page == "All") {
            $pagination = false;
        }

        $models_query = Booking::select('bookings.*')->join('users', 'users.id', '=', 'bookings.user_id')->join('user_profiles', 'user_profiles.id', 'bookings.profile_id')->orderBy('bookings.created_at', 'DESC')->whereIn('accommodation_status', [Booking::ACCOMMODATION_STATUS_CONFIRMED])->whereIn('status', [Booking::STATUS_COMPLETED, Booking::STATUS_PENDING]);


        $malefemale_query = clone $models_query;

        $models = clone $malefemale_query;

        $males = $models_query->where('user_profiles.gender', UserProfile::GENDER_MALE)->count();
        // print_r($males);exit;
        $females = $malefemale_query->where('user_profiles.gender', UserProfile::GENDER_FEMALE)->count();
        //  print_r($models->count());exit;
//print_r($males);exit;

        if ($search == true) {
            $models_query = Booking::select('bookings.*')->join('users', 'users.id', '=', 'bookings.user_id')->join('user_profiles', 'user_profiles.id', 'bookings.profile_id')->join('user_addresses', 'user_addresses.profile_id', 'bookings.profile_id')->orderBy('bookings.created_at', 'DESC')->whereIn('accommodation_status', [Booking::ACCOMMODATION_STATUS_CONFIRMED])->whereIn('status', [Booking::STATUS_COMPLETED])->where('user_profiles.patient_type', UserProfile::PATIENT_TYPE_IPD)
                ->where(function ($query) use ($matchThese, $bookingmatchThese, $usermatchThese, $matchTheseAddress, $filter_name) {
                foreach ($matchTheseAddress as $key => $match) {
                    $query->where('user_addresses.' . $key, 'like', "%$match%");
                }
                foreach ($matchThese as $key => $match) {
                    $query->where('user_profiles.' . $key, 'like', "%$match%");
                }
                foreach ($bookingmatchThese as $key => $match) {
                    $query->where('bookings.' . $key, 'like', "%$match%");
                }
                foreach ($usermatchThese as $key => $match) {
                    $query->where('users.' . $key, 'like', "%$match%");
                }

                if ($filter_name != "") {
                    $query->WhereRaw("concat(user_profiles.first_name, ' ', user_profiles.last_name) like '%$filter_name%' ");
                }
            })
                ->orderBy('bookings.created_at', 'DESC');



            $malefemale_query = clone $models_query;

            $models = clone $malefemale_query;

            $males = $models_query->where('user_profiles.gender', UserProfile::GENDER_MALE)->count();
            // print_r($males);exit;
            $females = $malefemale_query->where('user_profiles.gender', UserProfile::GENDER_FEMALE)->count();

            $count = $models->count();
            $models = $models->distinct()->get();
        } else {
            if ($pagination == true) {
                $count = $models->count();
                $models = $models->paginate($per_page);
            } else {
                $count = $models->count();
                $models = $models->distinct()->get();
            }
        }

        Notification::updateNotification(User::class);
        $ipd = true;

        $matchTheseAddress['city'] = $search_string;
        $matchThese['first_name'] = $request->get('first_name');

        if ($request->ajax()) {
            return [
                'html' => view('laralum/booking/_index', ['models' => $models, 'count' => $count, 'error' => $error, 'males' => $males, 'females' => $females, 'search' => $search, 'ipd' => $ipd, 'search_data' => array_merge($matchThese, $bookingmatchThese, $usermatchThese, $matchTheseAddress)])->render()
            ];
        }

        return view('laralum.booking.index', compact('models', 'search', 'error', 'count', 'males', 'females', 'ipd'));
    }

    public function index(Request $request)
    {
        Laralum::permissionToAccess(['admin.patients.list', 'doctor.patients', 'admin.bookings.list']);
        $matchThese = [];
        $search = false;
        $option_ar = [];

        $matchTheseAddress = [];

        if ($request->has('filter_patient_id') && $request->get('filter_patient_id') != "") {
            $option_ar[] = "Patient Id";
            $search = true;
            $search = true;
            $matchThese['kid'] = $request->get('filter_patient_id');
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
        if ($request->has('mobile') && $request->get('mobile') != "") {
            $option_ar[] = "Mobile";
            $search = true;
            $filter_mobile = $request->get('mobile');
            $matchThese['mobile'] = $request->get('mobile');
        }

        $filter_type = "";
        if ($request->has('filter_patient_type') && $request->get('filter_patient_type') != "") {
            $option_ar[] = "Patient Type";
            $search = true;
            $filter_type = $request->get('filter_patient_type');
            $matchThese['patient_type'] = $request->get('filter_patient_type');
        }

        if ($request->has('city') && $request->get('city') != "") {
            $option_ar[] = "City";
            $search = true;
            $filter_type = $request->get('city');
            $matchTheseAddress['city'] = $request->get('city');
        }

        $filter_accommodation_staus = "";
        if ($request->has('filter_accommodation_staus') && $request->get('filter_accommodation_staus') != "") {
            $option_ar[] = "Accommodation Status";
            $search = true;
            $filter_accommodation_staus = $request->get('filter_accommodation_staus');
        }

        $filter_name = "";
        if ($request->has('filter_name') && $request->get('filter_name') != "") {
            $option_ar[] = "Name";
            $search = true;
            $filter_name = $request->get('filter_name');
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
        $per_page = $request->get("per_page") ? $request->get("per_page") : 10;
        $pagination = true;
        if ($per_page == "All") {
            $pagination = false;
        }
        if (\Auth::user()->isDoctor()) {
            $models = Booking::select('bookings.*')->where('bookings.check_in_date', '<=', date('Y-m-d h:i:s'))->join('users', 'users.id', '=', 'bookings.user_id')->join('user_profiles', 'user_profiles.id', 'bookings.profile_id')->orderBy('bookings.created_at', 'DESC');
        } else {
            $models = Booking::select('bookings.*')->join('users', 'users.id', '=', 'bookings.user_id')->join('user_profiles', 'user_profiles.id', 'bookings.profile_id')->orderBy('bookings.created_at', 'DESC');
        }

        if (\Auth::user()->isDoctor()) {
            $models = $models->whereIn('status', [Booking::STATUS_COMPLETED]);
        } else {
            $models = $models->whereIn('status', [Booking::STATUS_COMPLETED, Booking::STATUS_PENDING]);

        }

        $models_query = $models->where('user_profiles.patient_type', UserProfile::PATIENT_TYPE_OPD);
        //echo '<pre>'; print_r($models->get());exit;

        $malefemale_query = clone $models_query;
        $models = clone $models_query;


        $males = $models_query->where('user_profiles.gender', UserProfile::GENDER_MALE)->count();
        // print_r($males);exit;
        $females = $malefemale_query->where('user_profiles.gender', UserProfile::GENDER_FEMALE)->count();
        //  print_r($models->count());exit;

        if ($search == true) {
            $models_query = Booking::select('bookings.*')->join('users', 'users.id', '=', 'bookings.user_id')->join('user_profiles', 'user_profiles.id', 'bookings.profile_id')->leftJoin('user_addresses', 'user_addresses.profile_id', 'bookings.profile_id')->where(function ($query) use ($matchThese, $filter_email, $filter_name, $matchTheseAddress) {
                foreach ($matchThese as $key => $match) {
                    $query->where('user_profiles.' . $key, 'like', "%$match%");
                }
                foreach ($matchTheseAddress as $key => $match) {
                    $query->where('user_addresses.' . $key, 'like', "%$match%");
                }
                if ($filter_email != "") {
                    $query->where('users.email', 'like', "%$filter_email%");
                }
                /*if ($filter_name != "") {
                    $query->where('users.name', 'like', "%$filter_name%");
                }*/
            })/*

                ->where('users.email', 'like', "%" . $filter_email . "%")->where('users.name', 'like', "%" . $filter_name . "%")->where('user_profiles.mobile', 'like', "%" . $filter_mobile . "%")*/
            ->orderBy('bookings.created_at', 'DESC');

            if ($filter_type != "") {
                $models_query = $models_query->where('bookings.patient_type', $filter_type);
            }

            if ($filter_accommodation_staus != "") {
                $models_query = $models_query->where('bookings.accommodation_status', $filter_accommodation_staus);
            }

            if (\Auth::user()->isDoctor()) {
                $models_query = $models_query->whereIn('status', [Booking::STATUS_COMPLETED]);
            } else {
                $models_query = $models_query->whereIn('status', [Booking::STATUS_COMPLETED, Booking::STATUS_PENDING]);
            }
            $models_query = $models_query->where('user_profiles.patient_type', UserProfile::PATIENT_TYPE_OPD);


            $malefemale_query = clone $models_query;
            $models = clone $models_query;

            $males = $models_query->where('user_profiles.gender', UserProfile::GENDER_MALE)->count();
            // print_r($males);exit;
            $females = $malefemale_query->where('user_profiles.gender', UserProfile::GENDER_FEMALE)->count();
            //  print_r($models->count());exit;

        }

        if ($pagination == true) {
            $count = $models->count();
            $models = $models->paginate($per_page);
        } else {
            $count = $models->count();
            $models = $models->get();
        }
        Notification::updateNotification(User::class);
        return view('laralum.booking.index', compact('models', 'search', 'error', 'count', 'males', 'females'));
    }

    public function patientsWithAccomodation(Request $request)
    {
        Laralum::permissionToAccess(['admin.patients.list', 'doctor.patients', 'admin.bookings.list']);
        $matchThese = [];
        $matchTheseB = [];
        $search = false;
        $option_ar = [];
        if ($request->has('kid') && $request->get('kid') != "") {
            $option_ar[] = "Patient Id";
            $search = true;
            $search = true;
            $matchThese['kid'] = $request->get('kid');
        }

        $uhid = "";
        if ($request->has('uhid') && $request->get('uhid') != "") {
            $option_ar[] = "UHID";
            $search = true;
            $uhid = $request->get('uhid');
        }

        if (!empty($request->get('mobile'))) {
            $option_ar[] = "Mobile";
            $search = true;
            $matchThese['mobile'] = $request->get('mobile');
        }


        /* if ($request->has('filter_first_name') && $request->get('filter_first_name') != "") {
             $option_ar[] = "First Name";
             $search = true;
             $matchThese['first_name'] = $request->get('filter_first_name');
         }

         if ($request->has('filter_last_name') && $request->get('filter_last_name') != "") {
             $option_ar[] = "Last Name";
             $search = true;
             $matchThese['last_name'] = $request->get('filter_last_name');
         }*/

        $date = "";
        if (!empty($request->date)) {
            $option_ar[] = "Created On";
            $search = true;
            $date = date("Y-m-d", strtotime($request->date));
        }

        $filter_mobile = "";
        if ($request->has('filter_mobile') && $request->get('filter_mobile') != "") {
            $option_ar[] = "Mobile";
            $search = true;
            $filter_mobile = $request->get('filter_mobile');
            $matchThese['mobile'] = $request->get('filter_mobile');
        }

        $filter_type = "";
        if ($request->has('filter_patient_type') && $request->get('filter_patient_type') != "") {
            $option_ar[] = "Patient Type";
            $search = true;
            $filter_type = $request->get('filter_patient_type');
            $matchThese['patient_type'] = $request->get('filter_patient_type');
        }

        $filter_accommodation_staus = "";
        if ($request->has('accommodation_status') && $request->get('accommodation_status') != "") {
            $option_ar[] = "Accommodation Status";
            $search = true;
            $matchTheseB['accommodation_status'] = $request->get('accommodation_status');
        }

        if ($request->has('status') && $request->get('status') != "") {
            $option_ar[] = "Status";
            $search = true;
            $matchTheseB['status'] = $request->get('status');
        }

        $filter_name = "";
        if ($request->has('first_name') && $request->get('first_name') != "") {
            $option_ar[] = "Name";
            $search = true;
            $filter_name = $request->get('first_name');

        }

        $search_string = '';
        if (!empty($request->get('city'))) {
            $option_ar[] = "City";
            $search = true;
            $search_string = $request->city;
            $array = explode(',', $request->city);

            $matchTheseAddress['city'] = $array[0];
            if (isset($array[1])) {
                $matchTheseAddress['state'] = $array[1];
            }

            if (isset($array[2])) {
                $matchTheseAddress['country'] = $array[2];
            }
        }

        $filter_email = "";

        if ($request->has('email')) {
            $option_ar[] = "Email";
            $search = true;
            $filter_email = $request->get('email');
        }
        $options = implode(", ", $option_ar);

        $error = "Entered " . $options . " is not valid,
make sure that you are entering valid " . $options . " 
or search by other options";
        $user = [];
        $per_page = $request->get("per_page") ? $request->get("per_page") : 10;
        $pagination = true;
        if ($per_page == "All") {
            $pagination = false;
        }

        $models = Booking::select('bookings.*')->join('users', 'users.id', '=', 'bookings.user_id')->join('user_profiles', 'user_profiles.id', 'bookings.profile_id')->where('user_profiles.patient_type', '=', UserProfile::PATIENT_TYPE_IPD)->orderBy('bookings.created_at', 'DESC')->whereIn('status', [Booking::STATUS_COMPLETED]);


        if ($search == true) {
            $models = Booking::select('bookings.*')->join('users', 'users.id', '=', 'bookings.user_id')->join('user_profiles', 'user_profiles.id', 'bookings.profile_id')->where('user_profiles.patient_type', '=', UserProfile::PATIENT_TYPE_IPD)
                ->join('user_addresses', 'user_addresses.profile_id', '=', 'bookings.profile_id')->orderBy('bookings.created_at', 'DESC')->whereIn('status', [Booking::STATUS_COMPLETED])->where(function ($query) use ($matchThese, $filter_email, $filter_name, $date, $uhid, $matchTheseB, $matchTheseAddress) {
                    foreach ($matchTheseB as $key => $match) {
                        $query->where('bookings.' . $key, 'like', "%$match%");
                    }
                    foreach ($matchThese as $key => $match) {
                        $query->where('user_profiles.' . $key, 'like', "%$match%");
                    }

                    foreach ($matchTheseAddress as $key => $match) {
                        $query->where('user_addresses.' . $key, 'like', "%$match%");
                    }
                    if ($filter_email != "") {
                        $query->where('users.email', 'like', "%$filter_email%");
                    }

                    if (!empty($date)) {
                        $query->whereDate('bookings.created_at', $date);
                    }

                    if ($uhid) {
                        $query->where('users.uhid', $uhid);
                    }

                    if ($filter_name != "") {
                        $query->WhereRaw("concat(user_profiles.first_name, ' ', user_profiles.last_name) like '%$filter_name%' ");
                    }
                });
            if ($uhid) {
                $matchThese['uhid'] = $uhid;
            }
            if ($date) {
                $matchThese['date'] = $date;
            }
            if ($filter_name != "") {
                $matchThese['first_name'] = $request->get('first_name');
            }

            $matchTheseAddress['city'] = $search_string;
            $matchThese['email'] = $filter_email;
        }

        if ($pagination == true) {
            $count = $models->count();
            $models = $models->paginate($per_page);
        } else {
            $count = $models->count();
            $models = $models->get();
        }

        Notification::updateNotification(User::class);

        if ($request->ajax()) {
            return [
                'html' => view('laralum/booking/_patient_with_acc', ['models' => $models, 'count' => $count, 'error' => $error, 'search' => $search, 'search_data' => array_merge($matchThese, $matchTheseB, $matchTheseAddress)])->render()
            ];
        }

        return view('laralum.booking.patient_with_acc', compact('models', 'search', 'error', 'count'));

    }

    public function printTokenList(Request $request)
    {
        $matchThese = [];
        $profileMatchThese = [];
        $matchTheseAddress = [];
        $doctorMatchThese = [];
        $userMatchThese = [];
        $search = false;
        $option_ar = [];

        if ($request->s && $request->s != 'null') {
            $search_data = json_decode($request->s, true);

            if (!empty($search_data['city'])) {
                $option_ar[] = "City";
                $search = true;
                $matchTheseAddress['city'] = $search_data['city'];
            }

            if (!empty($search_data['token_no'])) {
                $option_ar[] = "Token Number";
                $search = true;
                $matchThese['token_no'] = $search_data['token_no'];
            }
            if (!empty($search_data['kid'])) {
                $option_ar[] = "Patient Id";
                $search = true;
                $profileMatchThese['kid'] = $search_data['kid'];
            }
            if (!empty($search_data['uhid'])) {
                $option_ar[] = "UH Id";
                $search = true;
                $userMatchThese['uhid'] = $search_data['uhid'];
            }

            if (!empty($search_data['department_id'])) {
                $option_ar[] = "Department";
                $search = true;
                $matchThese['department_id'] = $search_data['department_id'];
            }

            $filter_name = '';
            if (!empty($search_data['first_name'])) {
                $option_ar[] = "Name";
                $search = true;
                $filter_name = $search_data['first_name'];
                //$profileMatchThese['first_name'] = $request->get('first_name');
            }

            $start_date = "";
            if (!empty($search_data['start_date'])) {
                $option_ar[] = "Date";
                $search = true;
                $start_date = date("Y-m-d", strtotime($search_data['start_date']));
            }

            $doc_name = "";

            if (!empty($search_data['doctor_name'])) {
                $option_ar[] = "Doctor Name";
                $search = true;
                $doctorMatchThese['doctor_name'] = $search_data['doctor_name'];
                $doc_name = $search_data['doctor_name'];
            }
        }

        $options = implode(", ", $option_ar);

        $error = "Entered " . $options . " is not valid,
make sure that you are entering valid " . $options . " 
or search by other options";
        $user = [];
        $per_page = $request->get("per_page") ? $request->get("per_page") : 10;
        $pagination = true;
        if ($per_page == "All") {
            $pagination = false;
        }

        $models = PatientToken::select('patient_tokens.*')->where(\DB::raw('date(start_date)'), ">=", date("Y-m-d"))->where(\DB::raw('date(end_date)'), "<=", date("Y-m-d", strtotime("+24 hours")))->orderBy('patient_tokens.created_at', 'DESC');
        $count = 0;


        if ($search == true) {
            $models = PatientToken::select('patient_tokens.*')->where(\DB::raw('date(start_date)'), ">=", date("Y-m-d"))->where(\DB::raw('date(end_date)'), "<=", date("Y-m-d", strtotime("+24 hours")))->join('bookings', 'bookings.id', '=', 'patient_tokens.booking_id')
                ->join('user_profiles', 'user_profiles.id', '=', 'bookings.profile_id')
                ->join('users', 'users.id', '=', 'bookings.user_id')
                ->join('users as doctors', 'doctors.id', '=', 'patient_tokens.doctor_id')
                ->leftJoin('user_addresses', 'user_addresses.profile_id', '=', 'bookings.profile_id')
                ->where(function ($query) use ($matchThese, $profileMatchThese, $doc_name, $matchTheseAddress, $start_date, $filter_name, $userMatchThese) {
                    foreach ($matchTheseAddress as $key => $match) {
                        $query->where('user_addresses.' . $key, 'like', "%$match%");
                    }

                    foreach ($matchThese as $key => $match) {
                        $query->where('patient_tokens.' . $key, 'like', "%$match%");
                    }

                    foreach ($profileMatchThese as $key => $match) {
                        if ($key == 'kid') {
                            $query->where('user_profiles.kid', $match);
                        } else {
                            $query->where('user_profiles.' . $key, 'like', "%$match%");
                        }
                    }
                    if ($start_date != "") {
                        $query->whereDate('patient_tokens.start_date', $start_date);
                    }

                    if ($filter_name != "") {
                        $query->WhereRaw("concat(user_profiles.first_name, ' ', user_profiles.last_name) like '%$filter_name%' ");
                    }

                    foreach ($userMatchThese as $key => $match) {
                        $query->where('users.' . $key, 'like', '%' . $match . '%');
                    }

                    if ($doc_name != "") {
                        $query->where('doctors.name', 'LIKE', '%' . $doc_name . '%');
                    }
                })->orderBy('patient_tokens.created_at', 'DESC');


            //   echo '<pre>'; print_r($models->get());exit;
            if ($start_date != "") {
                $models = $models->where(\DB::raw('date(start_date)'), "=", $start_date);
                $matchThese['start_date'] = date("d-m-Y", strtotime($start_date));
            }
            $models = $models->get();
            $count = $models->count();
            if ($filter_name != "") {
                $profileMatchThese['first_name'] = $filter_name;
            }
        } else {
            if ($pagination == true) {
                $count = $models->count();
                $models = $models->paginate($per_page);
            } else {
                $count = $models->count();
                $models = $models->get();
            }
        }

        $data['models'] = $models;
        $print = true;

        return view('laralum.booking.print-token-list', compact('models', 'print', 'count'));
    }

    public function exportTokenList(Request $request, $type, $per_page = 10, $page = 1)
    {
        $matchThese = [];
        $profileMatchThese = [];
        $matchTheseAddress = [];
        $doctorMatchThese = [];
        $userMatchThese = [];
        $search = false;
        $option_ar = [];

        if ($request->s && $request->s != 'null') {
            $search_data = json_decode($request->s, true);

            if (!empty($search_data['city'])) {
                $option_ar[] = "City";
                $search = true;
                $matchTheseAddress['city'] = $search_data['city'];
            }

            if (!empty($search_data['token_no'])) {
                $option_ar[] = "Token Number";
                $search = true;
                $matchThese['token_no'] = $search_data['token_no'];
            }
            if (!empty($search_data['kid'])) {
                $option_ar[] = "Patient Id";
                $search = true;
                $profileMatchThese['kid'] = $search_data['kid'];
            }
            if (!empty($search_data['uhid'])) {
                $option_ar[] = "UH Id";
                $search = true;
                $userMatchThese['uhid'] = $search_data['uhid'];
            }

            if (!empty($search_data['department_id'])) {
                $option_ar[] = "Department";
                $search = true;
                $matchThese['department_id'] = $search_data['department_id'];
            }

            $filter_name = '';
            if (!empty($search_data['first_name'])) {
                $option_ar[] = "Name";
                $search = true;
                $filter_name = $search_data['first_name'];
                //$profileMatchThese['first_name'] = $request->get('first_name');
            }

            $start_date = "";
            if (!empty($search_data['start_date'])) {
                $option_ar[] = "Date";
                $search = true;
                $start_date = date("Y-m-d", strtotime($search_data['start_date']));
            }

            $doc_name = "";

            if (!empty($search_data['doctor_name'])) {
                $option_ar[] = "Doctor Name";
                $search = true;
                $doctorMatchThese['doctor_name'] = $search_data['doctor_name'];
                $doc_name = $search_data['doctor_name'];
            }
        }

        $options = implode(", ", $option_ar);

        $error = "Entered " . $options . " is not valid,
make sure that you are entering valid " . $options . " 
or search by other options";
        $user = [];
        $per_page = $request->get("per_page") ? $request->get("per_page") : 10;
        $pagination = true;
        if ($per_page == "All") {
            $pagination = false;
        }

        $models = PatientToken::select('patient_tokens.*')->where(\DB::raw('date(start_date)'), ">=", date("Y-m-d"))->where(\DB::raw('date(end_date)'), "<=", date("Y-m-d", strtotime("+24 hours")))->orderBy('patient_tokens.created_at', 'DESC');
        $count = 0;


        if ($search == true) {
            $models = PatientToken::select('patient_tokens.*')->where(\DB::raw('date(start_date)'), ">=", date("Y-m-d"))->where(\DB::raw('date(end_date)'), "<=", date("Y-m-d", strtotime("+24 hours")))->join('bookings', 'bookings.id', '=', 'patient_tokens.booking_id')
                ->join('user_profiles', 'user_profiles.id', '=', 'bookings.profile_id')
                ->join('users', 'users.id', '=', 'bookings.user_id')
                ->join('users as doctors', 'doctors.id', '=', 'patient_tokens.doctor_id')
                ->leftJoin('user_addresses', 'user_addresses.profile_id', '=', 'bookings.profile_id')
                ->where(function ($query) use ($matchThese, $profileMatchThese, $doc_name, $matchTheseAddress, $start_date, $filter_name, $userMatchThese) {
                    foreach ($matchTheseAddress as $key => $match) {
                        $query->where('user_addresses.' . $key, 'like', "%$match%");
                    }

                    foreach ($matchThese as $key => $match) {
                        $query->where('patient_tokens.' . $key, 'like', "%$match%");
                    }

                    foreach ($profileMatchThese as $key => $match) {
                        if ($key == 'kid') {
                            $query->where('user_profiles.kid', $match);
                        } else {
                            $query->where('user_profiles.' . $key, 'like', "%$match%");
                        }
                    }
                    if ($start_date != "") {
                        $query->whereDate('patient_tokens.start_date', $start_date);
                    }

                    if ($filter_name != "") {
                        $query->WhereRaw("concat(user_profiles.first_name, ' ', user_profiles.last_name) like '%$filter_name%' ");
                    }

                    foreach ($userMatchThese as $key => $match) {
                        $query->where('users.' . $key, 'like', '%' . $match . '%');
                    }

                    if ($doc_name != "") {
                        $query->where('doctors.name', 'LIKE', '%' . $doc_name . '%');
                    }

                })->orderBy('patient_tokens.created_at', 'DESC');


            //   echo '<pre>'; print_r($models->get());exit;
            if ($start_date != "") {
                $models = $models->where(\DB::raw('date(start_date)'), "=", $start_date);
                $matchThese['start_date'] = date("d-m-Y", strtotime($start_date));
            }
            $models = $models->get();
            $count = $models->count();
            if ($filter_name != "") {
                $profileMatchThese['first_name'] = $filter_name;
            }
        } else {
            if ($pagination == true) {
                $count = $models->count();
                $models = $models->paginate($per_page);
            } else {
                $count = $models->count();
                $models = $models->get();
            }
        }
        $bookings_array[] = [
            'Token Number',
            'Name of the Patient',
            'Registration Id',
            'UHID',
            'Department',
            'Doctor',
            'Date'
        ];

        foreach ($models as $row) {
            $bookings_array[] = [
                $row->token_no,
                $row->booking->getProfile('first_name') . ' ' . $row->booking->getProfile('last_name'),
                $row->booking != null ? $row->booking->getProfile('kid') : '',
                $row->booking->getProfile('uhid'),
                $row->department->title,
                @$row->doctor->name,
                date("d-m-Y", strtotime($row->start_date))
            ];
        }

        //return $bookings_array;

        // Generate and return the spreadsheet
        $excel = \App::make('excel');
        $excel = $excel->create('Patient Tokens List', function ($excel) use ($bookings_array) {

            // Set the spreadsheet title, creator, and description
            $excel->setTitle('Patient Tokens List');

            // Build the spreadsheet, passing in the payments array
            $excel->sheet('sheet1', function ($sheet) use ($bookings_array) {
                $sheet->fromArray($bookings_array, null, 'A1', false, false);
            });

        });
        if ($type == Settings::EXPORT_CSV) {
            $excel->download('csv');
        } elseif ($type == Settings::EXPORT_EXCEL) {
            $excel->download('xls');
        } else {
            // $excel->download('pdf');
            $pdf = PDF::loadView('booking.pdf', array('data' => $bookings_array));
            return $pdf->download();
        }

        return redirect()->back()->with('error', 'Something went Wrong!!!');
    }

    public function printTreatmentTokenList(Request $request)
    {
        $matchThese = [];
        $search = false;
        $option_ar = [];

        if ($request->s && $request->s != 'null') {
            $search_data = json_decode($request->s, true);

            if (!empty($search_data['kid'])) {
                $option_ar[] = "Registration Id";
                $search = true;
                $matchThese['kid'] = $search_data['kid'];
            }

            $filter_name = "";

            if (!empty($search_data['first_name'])) {
                $option_ar[] = "First Name";
                $search = true;
                $filter_name = $search_data['first_name'];

                //  $matchThese['first_name'] = $request->get('filter_first_name');
            }
            $dep_id = '';

            if (!empty($search_data['department_id'])) {
                $option_ar[] = "Department";
                $search = true;
                $dep_id = $search_data['department_id'];
            }

            $t_id = '';

            if (!empty($search_data['treatment_id'])) {
                $option_ar[] = "Treatment";
                $search = true;
                $t_id = $search_data['treatment_id'];
            }

            if (!empty($search_data['filter_mobile'])) {
                $option_ar[] = "Mobile";
                $search = true;
                $matchThese['mobile'] = $search_data['filter_mobile'];
            }

            $filter_email = "";

            if (!empty($search_data['filter_email'])) {
                $option_ar[] = "Email";
                $search = true;
                $filter_email = $search_data['filter_email'];
            }
        }

        $options = implode(", ", $option_ar);

        $error = "Entered " . $options . " is not valid,
make sure that you are entering valid " . $options . " 
or search by other options";
        $user = [];
        $per_page = $request->get("per_page") ? $request->get("per_page") : 10;
        $pagination = true;
        if ($per_page == "All") {
            $pagination = false;
        }

        if ($search == true) {
            $tokens = TreatmentToken::select('treatment_tokens.*')
                ->join('user_profiles', 'user_profiles.user_id', '=', 'treatment_tokens.patient_id')
                ->join('users', 'treatment_tokens.patient_id', '=', 'users.id')
                ->where(function ($query) use ($dep_id, $matchThese, $filter_email, $filter_name, $t_id) {
                    foreach ($matchThese as $key => $match) {
                        $query->where('user_profiles.' . $key, 'like', "%$match%");
                    }
                    if ($filter_email != "") {
                        $query->where('users.email', 'like', "%$filter_email%");
                    }
                    if ($filter_name != "") {
                        $query->WhereRaw("concat(user_profiles.first_name, ' ', user_profiles.last_name) like '%$filter_name%' ");
                    }
                    if ($dep_id) {
                        $query->where('treatment_tokens.department_id', $dep_id);
                    }
                    if ($t_id) {
                        $query->whereHas('treatments', function ($q) use ($t_id) {
                            $q->where('treatment_id', $t_id);
                        });
                    }
                })->where('treatment_date', (string)date("Y-m-d"));

            $count = $tokens->count();
            $tokens = $tokens->get();
            $matchThese['department_id'] = $request->get('department_id');
            $matchThese['first_name'] = $request->get('first_name');
            $matchThese['treatment_id'] = $t_id;
        } else {
            $tokens = TreatmentToken::select('treatment_tokens.*')->where('treatment_date', (string)date("Y-m-d"));

            $count = $tokens->count();

            if ($pagination == true) {
                $tokens = $tokens->paginate($per_page);
            } else {
                $tokens = $tokens->get();
            }
        }
        $data['tokens'] = $tokens;
        $print = true;
        return view('laralum.booking.print-treatment-token-list', compact('tokens', 'print', 'count'));
    }

    public function exportTreatmentTokenList(Request $request, $type, $per_page = 10, $page = 1)
    {
        $matchThese = [];
        $search = false;
        $option_ar = [];

        if ($request->s && $request->s != 'null') {
            $search_data = json_decode($request->s, true);

            if (!empty($search_data['kid'])) {
                $option_ar[] = "Registration Id";
                $search = true;
                $matchThese['kid'] = $search_data['kid'];
            }

            $filter_name = "";

            if (!empty($search_data['first_name'])) {
                $option_ar[] = "First Name";
                $search = true;
                $filter_name = $search_data['first_name'];

                //  $matchThese['first_name'] = $request->get('filter_first_name');
            }
            $dep_id = '';

            if (!empty($search_data['department_id'])) {
                $option_ar[] = "Department";
                $search = true;
                $dep_id = $search_data['department_id'];
            }

            $t_id = '';

            if (!empty($search_data['treatment_id'])) {
                $option_ar[] = "Treatment";
                $search = true;
                $t_id = $search_data['treatment_id'];
            }

            if (!empty($search_data['filter_mobile'])) {
                $option_ar[] = "Mobile";
                $search = true;
                $matchThese['mobile'] = $search_data['filter_mobile'];
            }

            $filter_email = "";

            if (!empty($search_data['filter_email'])) {
                $option_ar[] = "Email";
                $search = true;
                $filter_email = $search_data['filter_email'];
            }
        }

        $options = implode(", ", $option_ar);

        $error = "Entered " . $options . " is not valid,
make sure that you are entering valid " . $options . " 
or search by other options";
        $user = [];
        $per_page = $request->get("per_page") ? $request->get("per_page") : 10;
        $pagination = true;
        if ($per_page == "All") {
            $pagination = false;
        }

        if ($search == true) {
            $tokens = TreatmentToken::select('treatment_tokens.*')
                ->join('user_profiles', 'user_profiles.user_id', '=', 'treatment_tokens.patient_id')
                ->join('users', 'treatment_tokens.patient_id', '=', 'users.id')
                ->where(function ($query) use ($dep_id, $matchThese, $filter_email, $filter_name, $t_id) {
                    foreach ($matchThese as $key => $match) {
                        $query->where('user_profiles.' . $key, 'like', "%$match%");
                    }
                    if ($filter_email != "") {
                        $query->where('users.email', 'like', "%$filter_email%");
                    }
                    if ($filter_name != "") {
                        $query->WhereRaw("concat(user_profiles.first_name, ' ', user_profiles.last_name) like '%$filter_name%' ");
                    }
                    if ($dep_id) {
                        $query->where('treatment_tokens.department_id', $dep_id);
                    }
                    if ($t_id) {
                        $query->whereHas('treatments', function ($q) use ($t_id) {
                            $q->where('treatment_id', $t_id);
                        });
                    }
                })->where('treatment_date', (string)date("Y-m-d"));

            $count = $tokens->count();
            $tokens = $tokens->get();
            $matchThese['department_id'] = $request->get('department_id');
            $matchThese['first_name'] = $request->get('first_name');
            $matchThese['treatment_id'] = $t_id;
        } else {
            $tokens = TreatmentToken::select('treatment_tokens.*')->where('treatment_date', (string)date("Y-m-d"));

            $count = $tokens->count();

            if ($pagination == true) {
                $tokens = $tokens->paginate($per_page);
            } else {
                $tokens = $tokens->get();
            }
        }
        $data['tokens'] = $tokens;

        $bookings_array[] = [
            'Registration Id',
            'Name of the Patient',
            'Department',
            'Treatments',
        ];


        foreach ($tokens as $row) {
            $treatments = [];

            foreach ($row->treatments as $pat_treat) {
                $treatments[] = $pat_treat->treatment->title . " (" . $pat_treat->treatment->getDuration() . ')';
            }

            $treatments = implode(',', $treatments);

            $bookings_array[] = [
                $row->booking->getProfile('kid'),
                $row->booking->getProfile('first_name') . ' ' . $row->booking->getProfile('last_name'),
                $row->department->title,
                $treatments
            ];
        }

        //return $bookings_array;

        // Generate and return the spreadsheet
        $excel = \App::make('excel');
        $excel = $excel->create('Treatment Tokens', function ($excel) use ($bookings_array) {

            // Set the spreadsheet title, creator, and description
            $excel->setTitle('Treatment Tokens');

            // Build the spreadsheet, passing in the payments array
            $excel->sheet('sheet1', function ($sheet) use ($bookings_array) {
                $sheet->fromArray($bookings_array, null, 'A1', false, false);
            });

        });
        if ($type == Settings::EXPORT_CSV) {
            $excel->download('csv');
        } elseif ($type == Settings::EXPORT_EXCEL) {
            $excel->download('xls');
        } else {
            // $excel->download('pdf');
            $pdf = PDF::loadView('booking.pdf', array('data' => $bookings_array));
            return $pdf->download('treatments_tokens.pdf');
        }

        return redirect()->back()->with('error', 'Something went Wrong!!!');
    }

    public function printOpdTokenList(Request $request)
    {
        Laralum::permissionToAccess('admin.bookings.opd.tokens.list');
        $matchThese = [];
        $profileMatchThese = [];
        $matchTheseAddress = [];
        $doctorMatchThese = [];

        $search = false;
        $option_ar = [];

        if ($request->s && $request->s != 'null') {
            $search_data = json_decode($request->s, true);

            if (!empty($search_data['city'])) {
                $option_ar[] = "City";
                $search = true;
                $matchTheseAddress['city'] = $search_data['city'];
            }

            if (!empty($search_data['kid'])) {
                $option_ar[] = "Registration Id";

                $search = true;
                $profileMatchThese['kid'] = $search_data['kid'];
            }

            if (!empty($search_data['department_id'])) {
                $option_ar[] = "Department";
                $search = true;
                $matchThese['department_id'] = $search_data['department_id'];
            }

            $filter_name = "";
            if (!empty($search_data['first_name'])) {
                $option_ar[] = "Name";
                $search = true;
                $filter_name = $search_data['first_name'];
            }

            $doc_name = "";


            if (!empty($search_data['doctor_name'])) {
                $option_ar[] = "Doctor Name";
                $search = true;
                $doctorMatchThese['doctor_name'] = $search_data['doctor_name'];
                $doc_name = $search_data['doctor_name'];
            }
        }

        $options = implode(", ", $option_ar);

        $error = "Entered " . $options . " is not valid,
make sure that you are entering valid " . $options . " 
or search by other options";
        $user = [];
        $per_page = $request->get("per_page") ? $request->get("per_page") : 10;
        $pagination = true;
        if ($per_page == "All") {
            $pagination = false;
        }

        $models = OpdTokens::select('opd_tokens.*')->orderBy('opd_tokens.created_at', 'DESC');
        $count = 0;
        if ($search == true) {
            $models = OpdTokens::select('opd_tokens.*')->leftJoin('bookings', 'bookings.id', '=', 'opd_tokens.booking_id')->leftJoin('user_profiles', 'user_profiles.id', '=', 'bookings.profile_id')->join('users', 'users.id', '=', 'opd_tokens.doctor_id');
            if (!empty($matchThese) || !empty($profileMatchThese) || !empty($doc_name) || !empty($filter_name)) {

                $models = $models->where(function ($query) use ($matchThese, $profileMatchThese, $doc_name, $filter_name) {
                    foreach ($matchThese as $key => $match) {
                        $query->where('opd_tokens.' . $key, 'like', "%$match%");
                    }
                    foreach ($profileMatchThese as $key => $match) {
                        $query->where('user_profiles.' . $key, 'like', "%$match%");
                    }
                    if ($filter_name != "") {
                        $query->whereRaw("concat(opd_tokens.first_name, ' ', opd_tokens.last_name) like '%$filter_name%' ");
                    }

                    if ($doc_name != "") {
                        $query->where('users.name', 'LIKE', '%' . $doc_name . '%');
                    }
                });
            }

            $models = $models->orderBy('opd_tokens.created_at', 'DESC');

            $models = $models->get();
            $count = $models->count();

            if (!empty($filter_name)) {
                $matchThese['first_name'] = $filter_name;
            }
        } else {
            if ($pagination == true) {
                $count = $models->count();
                $models = $models->paginate($per_page);
            } else {
                $count = $models->count();
                $models = $models->get();
            }
        }

        $data['models'] = $models;
        $print = true;
        return view('laralum.booking.print-opd-token-list', compact('models', 'print', 'count'));
    }

    public function exportOpdTokenList(Request $request, $type, $per_page = 10, $page = 1)
    {
        Laralum::permissionToAccess('admin.bookings.opd.tokens.list');
        $matchThese = [];
        $profileMatchThese = [];
        $matchTheseAddress = [];
        $doctorMatchThese = [];

        $search = false;
        $option_ar = [];

        if ($request->s && $request->s != 'null') {
            $search_data = json_decode($request->s, true);

            if (!empty($search_data['city'])) {
                $option_ar[] = "City";
                $search = true;
                $matchTheseAddress['city'] = $search_data['city'];
            }

            if (!empty($search_data['kid'])) {
                $option_ar[] = "Registration Id";

                $search = true;
                $profileMatchThese['kid'] = $search_data['kid'];
            }

            if (!empty($search_data['department_id'])) {
                $option_ar[] = "Department";
                $search = true;
                $matchThese['department_id'] = $search_data['department_id'];
            }

            $filter_name = "";
            if (!empty($search_data['first_name'])) {
                $option_ar[] = "Name";
                $search = true;
                $filter_name = $search_data['first_name'];
            }

            $doc_name = "";


            if (!empty($search_data['doctor_name'])) {
                $option_ar[] = "Doctor Name";
                $search = true;
                $doctorMatchThese['doctor_name'] = $search_data['doctor_name'];
                $doc_name = $search_data['doctor_name'];
            }
        }

        $options = implode(", ", $option_ar);

        $error = "Entered " . $options . " is not valid,
make sure that you are entering valid " . $options . " 
or search by other options";
        $user = [];
        $per_page = $request->get("per_page") ? $request->get("per_page") : 10;
        $pagination = true;
        if ($per_page == "All") {
            $pagination = false;
        }

        $models = OpdTokens::select('opd_tokens.*')->orderBy('opd_tokens.created_at', 'DESC');
        $count = 0;
        if ($search == true) {
            $models = OpdTokens::select('opd_tokens.*')->leftJoin('bookings', 'bookings.id', '=', 'opd_tokens.booking_id')->leftJoin('user_profiles', 'user_profiles.id', '=', 'bookings.profile_id')->join('users', 'users.id', '=', 'opd_tokens.doctor_id');
            if (!empty($matchThese) || !empty($profileMatchThese) || !empty($doc_name) || !empty($filter_name)) {

                $models = $models->where(function ($query) use ($matchThese, $profileMatchThese, $doc_name, $filter_name) {
                    foreach ($matchThese as $key => $match) {
                        $query->where('opd_tokens.' . $key, 'like', "%$match%");
                    }
                    foreach ($profileMatchThese as $key => $match) {
                        $query->where('user_profiles.' . $key, 'like', "%$match%");
                    }
                    if ($filter_name != "") {
                        $query->whereRaw("concat(opd_tokens.first_name, ' ', opd_tokens.last_name) like '%$filter_name%' ");
                    }

                    if ($doc_name != "") {
                        $query->where('users.name', 'LIKE', '%' . $doc_name . '%');
                    }
                });
            }

            $models = $models->orderBy('opd_tokens.created_at', 'DESC');

            $models = $models->get();
            $count = $models->count();

            if (!empty($filter_name)) {
                $matchThese['first_name'] = $filter_name;
            }
        } else {
            if ($pagination == true) {
                $count = $models->count();
                $models = $models->paginate($per_page);
            } else {
                $count = $models->count();
                $models = $models->get();
            }
        }

        $bookings_array[] = [
            'Name of the Patient',
            'Registration Id',
            'Department',
            'Doctor',
            'Complaints',
        ];

        foreach ($models as $row) {
            $bookings_array[] = [
                $row->first_name . ' ' . $row->last_name,
                $row->booking != null ? $row->booking->getProfile('kid') : '',
                $row->department->title,
                @$row->doctor->name,
                $row->complaints
            ];
        }

        //return $bookings_array;

        // Generate and return the spreadsheet
        $excel = \App::make('excel');
        $excel = $excel->create('OPD Tokens', function ($excel) use ($bookings_array) {

            // Set the spreadsheet title, creator, and description
            $excel->setTitle('OPD Tokens');

            // Build the spreadsheet, passing in the payments array
            $excel->sheet('sheet1', function ($sheet) use ($bookings_array) {
                $sheet->fromArray($bookings_array, null, 'A1', false, false);
            });

        });
        if ($type == Settings::EXPORT_CSV) {
            $excel->download('csv');
        } elseif ($type == Settings::EXPORT_EXCEL) {
            $excel->download('xls');
        } else {
            // $excel->download('pdf');
            $pdf = PDF::loadView('booking.pdf', array('data' => $bookings_array));
            return $pdf->download();
        }

        return redirect()->back()->with('error', 'Something went Wrong!!!');
    }

    public function printPatientsWithAccomodation()
    {

        $models = Booking::select('bookings.*')->join('users', 'users.id', '=', 'bookings.user_id')->join('user_profiles', 'user_profiles.id', 'bookings.profile_id')->where('user_profiles.patient_type', '=', UserProfile::PATIENT_TYPE_IPD)->orderBy('bookings.created_at', 'DESC')->whereIn('status', [Booking::STATUS_COMPLETED]);
        $count = $models->count();
        $models = $models->get();
        $print = true;
        return view('laralum.booking.patient-print-acc', compact('models', 'print', 'count'));
    }

    public function exportPatientsWithAccomodation(Request $request, $type, $per_page = 10, $page = 1)
    {
        $per_page = $request->get('per_page') ? $request->get('per_page') : 10;
        $pagination = true;
        if ($per_page == "All") {
            $pagination = false;
        }

        $models = Booking::select('bookings.*')->join('users', 'users.id', '=', 'bookings.user_id')->join('user_profiles', 'user_profiles.id', 'bookings.profile_id')->where('user_profiles.patient_type', '=', UserProfile::PATIENT_TYPE_IPD)->orderBy('bookings.created_at', 'DESC')->whereIn('status', [Booking::STATUS_COMPLETED]);


        if ($pagination == true) {
            $booking_count = $models->count();
            $models = $models->paginate($per_page);
        } else {
            $booking_count = $models->count();
            $models = $models->get();
        }

        $bookings_array[] = [
            'UHID',
            'Registration Id',
            'Booking Id',
            'Type',
            'Name of the Person',
            'Email ID',
            'Contact No. ',
            'City, State, Country',
            'Created On',
            'Booking Status',
            'Accommodation Status'
        ];

        foreach ($models as $booking) {

            $status = $booking->status != null ? Booking::getStatusOptions($booking->status) : Booking::getStatusOptions(Booking::STATUS_PENDING);
            $bookings_array[] = [
                $booking->getProfile('uhid'),
                $booking->getProfile('kid'),
                $booking->booking_id,
                $booking->patient_type != null ? $booking->getPatientType($booking->patient_type) : "OPD",
                $booking->getProfile('first_name') . ' ' . $booking->getProfile('last_name'),
                isset($booking->user->email) ? $booking->user->email : "",
                $booking->getProfile('mobile') ? $booking->getProfile('mobile') : "",
                $booking->getAddress('city') ? $booking->getAddress('city') . ',' . $booking->getAddress('state') . ',' . $booking->getAddress('country') : "",
                date("d-m-Y h:i a", strtotime($booking->created_at)),
                $status,
                $booking->accommodationStatus()
            ];
        }


        //return $bookings_array;

        // Generate and return the spreadsheet
        $excel = \App::make('excel');
        $excel = $excel->create('Accomodation', function ($excel) use ($bookings_array) {

            // Set the spreadsheet title, creator, and description
            $excel->setTitle('Bookings Patients');

            // Build the spreadsheet, passing in the payments array
            $excel->sheet('sheet1', function ($sheet) use ($bookings_array) {
                $sheet->fromArray($bookings_array, null, 'A1', false, false);
            });

        });
        if ($type == Settings::EXPORT_CSV) {
            $excel->download('csv');
        } elseif ($type == Settings::EXPORT_EXCEL) {
            $excel->download('xls');
        } else {
            // $excel->download('pdf');
            $pdf = PDF::loadView('booking.pdf', array('data' => $bookings_array));
            return $pdf->download();
        }

        return redirect()->back()->with('error', 'Something went Wrong!!!');
    }

    public function printBookings(Request $request)
    {
        Laralum::permissionToAccess(['admin.patients.list', 'doctor.patients', 'admin.bookings.list']);

        $matchThese = [];
        $bookingmatchThese = [];
        $usermatchThese = [];
        $matchTheseAddress = [];

        $search = false;
        $option_ar = [];

        if ($request->s && $request->s != 'null') {
            $search_data = json_decode($request->s, true);

            if (!empty($search_data['kid'])) {
                $option_ar[] = "Patient Id";
                $search = true;
                $matchThese['kid'] = $search_data['kid'];
            }

            if (!empty($search_data['uhid'])) {
                $option_ar[] = "UHID";
                $search = true;
                $usermatchThese['uhid'] = $search_data['uhid'];
            }

            if (!empty($search_data['booking_id'])) {
                $option_ar[] = "Booking Id";
                $search = true;
                $bookingmatchThese['booking_id'] = $search_data['booking_id'];
            }

            $filter_name = '';

            if (!empty($search_data['first_name'])) {
                $option_ar[] = "Name";
                $search = true;
                //trim($search_data['first_name']);
                $filter_name = trim($search_data['first_name']);
            }

            $address_string = '';
            if (!empty($search_data['city'])) {
                $option_ar[] = "City";
                $search = true;
                $address_array = explode(',', $search_data['city']);
                $address_string = $search_data['city'];
                $matchTheseAddress['city'] = $address_array[0];
                if (isset($address_array[1])) {
                    $matchTheseAddress['state'] = $address_array[1];
                }

                if (isset($address_array[2])) {
                    $matchTheseAddress['country'] = $address_array[2];
                }
            }

            if (!empty($search_data['state'])) {
                $option_ar[] = "State";
                $search = true;
                $matchTheseAddress['state'] = $search_data['state'];
            }

            if (!empty($search_data['country'])) {
                $option_ar[] = "Country";
                $search = true;
                $matchTheseAddress['country'] = $search_data['country'];
            }

            if (!empty($search_data['mobile'])) {
                $option_ar[] = "Mobile";
                $search = true;
                $matchThese['mobile'] = $search_data['mobile'];
            }


            if (!empty($search_data['patient_type'])) {
                $option_ar[] = "Patient Type";
                $search = true;
                $bookingmatchThese['patient_type'] = $search_data['patient_type'];
            }
            $acm_status = "";
            if (!empty($search_data['accommodation_status'])) {
                $option_ar[] = "Accommodation Status";
                $search = true;
                $bookingmatchThese['accommodation_status'] = $search_data['accommodation_status'];
                $acm_status = $search_data['accommodation_status'];
            }
            $booking_status = "";
            if (!empty($search_data['status'])) {
                $option_ar[] = "Status";
                $search = true;
                $bookingmatchThese['status'] = $search_data['status'];
                $booking_status = $search_data['status'];
            }

            if (!empty($search_data['email'])) {
                $option_ar[] = "Email";
                $search = true;
                $usermatchThese['email'] = $search_data['email'];
            }
        }

        $options = implode(", ", $option_ar);

        $error = "Entered " . $options . " is not valid,
make sure that you are entering valid " . $options . " 
or search by other options";
        $user = [];
        $per_page = $request->get("per_page") ? $request->get("per_page") : 10;
        $pagination = true;
        if ($per_page == "All") {
            $pagination = false;
        }

        $models_query = Booking::select('bookings.*')->join('users', 'users.id', '=', 'bookings.user_id')->join('user_profiles', 'user_profiles.id', 'bookings.profile_id')->orderBy('bookings.created_at', 'DESC');

        if (\Auth::user()->isDoctor()) {
            $models_query = $models_query->whereIn('status', [Booking::STATUS_COMPLETED]);
        } else {
            $models_query = $models_query->whereIn('status', [Booking::STATUS_COMPLETED, Booking::STATUS_PENDING]);
        }
        $models_query = $models_query->where('bookings.patient_type', Booking::PATIENT_TYPE_OPD);

        $malefemale_query = clone $models_query;
        $models = clone $models_query;


        $males = $models_query->where('user_profiles.gender', UserProfile::GENDER_MALE)->count();
        // print_r($males);exit;
        $females = $malefemale_query->where('user_profiles.gender', UserProfile::GENDER_FEMALE)->count();
        //  print_r($models->count());exit;


        if ($search == true) {
            $models_query = Booking::select('bookings.*')->join('users', 'users.id', '=', 'bookings.user_id')->join('user_profiles', 'user_profiles.id', 'bookings.profile_id')
                ->leftJoin('user_addresses', 'user_addresses.profile_id', 'bookings.profile_id')
                ->where('user_profiles.patient_type', UserProfile::PATIENT_TYPE_OPD)
                ->where('bookings.status', Booking::STATUS_COMPLETED)
                ->where(function ($query) use ($matchThese, $bookingmatchThese, $usermatchThese, $matchTheseAddress, $filter_name) {
                    foreach ($matchTheseAddress as $key => $match) {
                        $query->where('user_addresses.' . $key, 'like', "%$match%");
                    }
                    foreach ($matchThese as $key => $match) {
                        $query->where('user_profiles.' . $key, 'like', "%$match%");
                    }
                    foreach ($bookingmatchThese as $key => $match) {
                        $query->where('bookings.' . $key, 'like', "%$match%");
                    }
                    foreach ($usermatchThese as $key => $match) {
                        $query->where('users.' . $key, 'like', "%$match%");
                    }
                    if ($filter_name != "") {
                        $query->WhereRaw("concat(user_profiles.first_name, ' ', user_profiles.last_name) like '%$filter_name%' ");
                    }

                })
                ->orderBy('bookings.created_at', 'DESC');

            $count = $models_query->count();
            //print_r($models);


            $malefemale_query = clone $models_query;
            $models = clone $models_query;

            $males = $models_query->where('user_profiles.gender', UserProfile::GENDER_MALE)->count();
            // print_r($males);exit;
            $females = $malefemale_query->where('user_profiles.gender', UserProfile::GENDER_FEMALE)->count();


            $models = $models->distinct()->get();

            if (!empty($filter_name)) {
                $matchThese['first_name'] = $request->get('first_name');
            }

            if (!empty($address_string)) {
                $matchTheseAddress['city'] = $address_string;
            }
            //print_r($models);
        } else {
            if ($pagination == true) {
                $count = $models->count();
                $models = $models->paginate($per_page);
            } else {
                $count = $models->count();
                $models = $models->distinct()->get();
            }
        }

        if (isset($matchThese['first_name'])) {
            $matchThese['first_name'] = $matchThese['first_name'] . ' ' . $matchThese['last_name'];
        }

        $print = true;

        return view('laralum.booking.print-booking', compact('models', 'print', 'count', 'males', 'females'));
    }

    public function printIpdBookings(Request $request)
    {
        Laralum::permissionToAccess(['admin.patients.list', 'doctor.patients', 'admin.bookings.list']);
        $matchThese = [];
        $bookingmatchThese = [];
        $usermatchThese = [];
        $matchTheseAddress = [];

        $search = false;
        $option_ar = [];

        if ($request->s && $request->s != 'null') {
            $search_data = json_decode($request->s, true);

            if (!empty($search_data['kid'])) {
                $option_ar[] = "Registration Id";
                $search = true;
                $matchThese['kid'] = $search_data['kid'];
            }

            if (!empty($search_data['uhid'])) {
                $option_ar[] = "UHID";
                $search = true;
                $usermatchThese['uhid'] = $search_data['uhid'];
            }
            if (!empty($search_data['booking_id'])) {
                $option_ar[] = "Booking Id";
                $search = true;
                $bookingmatchThese['booking_id'] = $search_data['booking_id'];
            }

            $filter_name = '';
            if (!empty($search_data['first_name'])) {
                $option_ar[] = "Name";
                $search = true;
                $filter_name = $search_data['first_name'];
            }

            $search_string = '';
            if (!empty($search_data['city'])) {
                $option_ar[] = "City";
                $search = true;
                $search_string = $search_data['city'];
                $array = explode(',', $search_data['city']);

                $matchTheseAddress['city'] = $array[0];
                if (isset($array[1])) {
                    $matchTheseAddress['state'] = $array[1];
                }

                if (isset($array[2])) {
                    $matchTheseAddress['country'] = $array[2];
                }
            }

            if (!empty($search_data['state'])) {
                $option_ar[] = "State";
                $search = true;
                $matchTheseAddress['state'] = $search_data['state'];
            }

            if (!empty($search_data['country'])) {
                $option_ar[] = "Country";
                $search = true;
                $matchTheseAddress['country'] = $search_data['country'];
            }

            //print_r($matchTheseAddress);exit;
            if (!empty($search_data['mobile'])) {
                $option_ar[] = "Mobile";
                $search = true;
                $matchThese['mobile'] = $search_data['mobile'];
            }

            if (!empty($search_data['patient_type'])) {
                $option_ar[] = "Patient Type";
                $search = true;
                $bookingmatchThese['patient_type'] = $search_data['patient_type'];
            }

            $acm_status = "";
            if (!empty($search_data['accommodation_status'])) {
                $option_ar[] = "Accommodation Status";
                $search = true;
                $bookingmatchThese['accommodation_status'] = $search_data['accommodation_status'];
                $acm_status = $request->get('accommodation_status');
            }

            $booking_status = "";
            if (!empty($search_data['status'])) {
                $option_ar[] = "Status";
                $search = true;
                $bookingmatchThese['status'] = $search_data['status'];
                $booking_status = $search_data['status'];
            }

            if (!empty($search_data['email'])) {
                $option_ar[] = "Email";
                $search = true;
                $usermatchThese['email'] = $search_data['email'];
            }
        }

        $options = implode(", ", $option_ar);

        $error = "Entered " . $options . " is not valid,
make sure that you are entering valid " . $options . " 
or search by other options";
        $user = [];
        $per_page = $request->get("per_page") ? $request->get("per_page") : 10;
        $pagination = true;
        if ($per_page == "All") {
            $pagination = false;
        }

        $models_query = Booking::select('bookings.*')->join('users', 'users.id', '=', 'bookings.user_id')->join('user_profiles', 'user_profiles.id', 'bookings.profile_id')->orderBy('bookings.created_at', 'DESC')->whereIn('accommodation_status', [Booking::ACCOMMODATION_STATUS_CONFIRMED])->whereIn('status', [Booking::STATUS_COMPLETED, Booking::STATUS_PENDING]);


        $malefemale_query = clone $models_query;

        $models = clone $malefemale_query;

        $males = $models_query->where('user_profiles.gender', UserProfile::GENDER_MALE)->count();
        // print_r($males);exit;
        $females = $malefemale_query->where('user_profiles.gender', UserProfile::GENDER_FEMALE)->count();
        //  print_r($models->count());exit;
//print_r($males);exit;

        if ($search == true) {
            $models_query = Booking::select('bookings.*')->join('users', 'users.id', '=', 'bookings.user_id')->join('user_profiles', 'user_profiles.id', 'bookings.profile_id')->join('user_addresses', 'user_addresses.profile_id', 'bookings.profile_id')->orderBy('bookings.created_at', 'DESC')->whereIn('accommodation_status', [Booking::ACCOMMODATION_STATUS_CONFIRMED])->whereIn('status', [Booking::STATUS_COMPLETED])->where('user_profiles.patient_type', UserProfile::PATIENT_TYPE_IPD)
                ->where(function ($query) use ($matchThese, $bookingmatchThese, $usermatchThese, $matchTheseAddress, $filter_name) {
                    foreach ($matchTheseAddress as $key => $match) {
                        $query->where('user_addresses.' . $key, 'like', "%$match%");
                    }
                    foreach ($matchThese as $key => $match) {
                        $query->where('user_profiles.' . $key, 'like', "%$match%");
                    }
                    foreach ($bookingmatchThese as $key => $match) {
                        $query->where('bookings.' . $key, 'like', "%$match%");
                    }
                    foreach ($usermatchThese as $key => $match) {
                        $query->where('users.' . $key, 'like', "%$match%");
                    }

                    if ($filter_name != "") {
                        $query->WhereRaw("concat(user_profiles.first_name, ' ', user_profiles.last_name) like '%$filter_name%' ");
                    }
                })
                ->orderBy('bookings.created_at', 'DESC');


            $malefemale_query = clone $models_query;

            $models = clone $malefemale_query;

            $males = $models_query->where('user_profiles.gender', UserProfile::GENDER_MALE)->count();
            // print_r($males);exit;
            $females = $malefemale_query->where('user_profiles.gender', UserProfile::GENDER_FEMALE)->count();

            $count = $models->count();
            $models = $models->distinct()->get();
        } else {
            if ($pagination == true) {
                $count = $models->count();
                $models = $models->paginate($per_page);
            } else {
                $count = $models->count();
                $models = $models->distinct()->get();
            }
        }

        $print = true;
        $back_url = url('admin/ipd-bookings');
        return view('laralum.booking.print-booking', compact('models', 'print', 'count', 'males', 'females', 'ipd', 'back_url'));
    }

    public function printFutureBookings(Request $request)
    {
        Laralum::permissionToAccess(['admin.future_patients_management']);
        $matchThese = [];
        $bookingmatchThese = [];
        $usermatchThese = [];
        $matchTheseAddress = [];
        $others = [];

        $search = false;
        $option_ar = [];

        if ($request->s && $request->s != 'null') {
            $search_data = json_decode($request->s, true);

            if (!empty($search_data['kid'])) {
                $option_ar[] = "Patient Id";
                $search = true;
                $matchThese['kid'] = $search_data['kid'];
            }
            if (!empty($search_data['uhid'])) {
                $option_ar[] = "UHID";
                $search = true;
                $usermatchThese['uhid'] = $search_data['uhid'];
            }
            if (!empty($search_data['booking_id'])) {
                $option_ar[] = "Booking Id";
                $search = true;
                $bookingmatchThese['booking_id'] = $search_data['booking_id'];
            }

            if (!empty($search_data['name'])) {
                $option_ar[] = "Name";
                $search = true;

                $array = explode(' ', $search_data['name']);

                $matchThese['first_name'] = $array[0];
                $matchThese['last_name'] = '';

                if (isset($array[1])) {
                    $matchThese['last_name'] = $array[1];
                }
            }

            $filter_name = "";
            if (!empty($search_data['first_name'])) {
                $option_ar[] = "Name";
                $search = true;
                $filter_name = $search_data['first_name'];
            }

            $address_string = '';
            if (!empty($search_data['city'])) {
                $option_ar[] = "City";
                $search = true;
                $address_array = explode(',', $search_data['city']);
                $address_string = $search_data['city'];
                $matchTheseAddress['city'] = $address_array[0];
                if (isset($address_array[1])) {
                    $matchTheseAddress['state'] = $address_array[1];
                }

                if (isset($address_array[2])) {
                    $matchTheseAddress['country'] = $address_array[2];
                }
            }

            if (!empty($search_data['state'])) {
                $option_ar[] = "State";
                $search = true;
                $matchTheseAddress['state'] = $search_data['state'];
            }

            if (!empty($search_data['country'])) {
                $option_ar[] = "Country";
                $search = true;
                $matchTheseAddress['country'] = $search_data['country'];
            }

            if (!empty($search_data['mobile'])) {
                $option_ar[] = "Mobile";
                $search = true;
                $matchThese['mobile'] = $search_data['mobile'];
            }

            if (!empty($search_data['patient_type'])) {
                $option_ar[] = "Patient Type";
                $search = true;
                $bookingmatchThese['patient_type'] = $search_data['patient_type'];
            }

            $acm_status = "";
            if (!empty($search_data['accommodation_status'])) {
                $option_ar[] = "Accommodation Status";
                $search = true;
                $bookingmatchThese['accommodation_status'] = $search_data['accommodation_status'];
                $acm_status = $search_data['accommodation_status'];
            }
            $booking_status = "";

            if (!empty($search_data['status'])) {
                $option_ar[] = "Status";
                $search = true;
                $bookingmatchThese['status'] = $search_data['status'];
                $booking_status = $search_data['status'];
            }
            if (!empty($search_data['email'])) {
                $option_ar[] = "Email";
                $search = true;
                $usermatchThese['email'] = $search_data['email'];
            }

            if (!empty($search_data['check_in_date'])) {
                $search = true;
                $check_in_date = date("Y-m-d", strtotime($search_data['check_in_date']));
            }

            if (!empty($search_data['check_out_date'])) {
                $search = true;
                $check_out_date = date("Y-m-d", strtotime($search_data['check_out_date']));
            }
        }

        $options = implode(", ", $option_ar);

        $error = "Entered " . $options . " is not valid,
make sure that you are entering valid " . $options . " 
or search by other options";
        $user = [];
        $per_page = $request->get("per_page") ? $request->get("per_page") : 10;
        $pagination = true;
        if ($per_page == "All") {
            $pagination = false;
        }

        $models_query = Booking::select('bookings.*')->join('users', 'users.id', '=', 'bookings.user_id')->join('user_profiles', 'user_profiles.id', 'bookings.profile_id')->orderBy('bookings.created_at', 'DESC');

        $models_query = $models_query->whereIn('status', [Booking::STATUS_COMPLETED])->where('user_profiles.patient_type', UserProfile::PATIENT_TYPE_IPD)->where(function ($q) {
            $q->orWhereNull('accommodation_status')->orWhere('accommodation_status', Booking::ACCOMMODATION_STATUS_PENDING);
        });

        // echo '<pre>'; print_r($models_query->get());exit;

        $malefemale_query = clone $models_query;

        $models = clone $malefemale_query;

        $males = $models_query->where('user_profiles.gender', UserProfile::GENDER_MALE)->count();
        // print_r($males);exit;
        $females = $malefemale_query->where('user_profiles.gender', UserProfile::GENDER_FEMALE)->count();
        //  print_r($models->count());exit;

        if ($search == true) {
            $models_query = Booking::select('bookings.*')->join('users', 'users.id', '=', 'bookings.user_id')->join('user_profiles', 'user_profiles.id', 'bookings.profile_id')
                ->join('user_addresses', 'user_addresses.profile_id', 'bookings.profile_id')->where(function ($query) use ($matchThese, $bookingmatchThese, $usermatchThese, $matchTheseAddress, $filter_name) {
                    foreach ($matchTheseAddress as $key => $match) {
                        $query->where('user_addresses.' . $key, 'like', "%$match%");
                    }
                    foreach ($matchThese as $key => $match) {
                        $query->where('user_profiles.' . $key, 'like', "%$match%");
                    }
                    foreach ($bookingmatchThese as $key => $match) {
                        $query->where('bookings.' . $key, 'like', "%$match%");
                    }
                    foreach ($usermatchThese as $key => $match) {
                        $query->where('users.' . $key, 'like', "%$match%");
                    }
                    if ($filter_name != "") {
                        $query->WhereRaw("concat(user_profiles.first_name, ' ', user_profiles.last_name) like '%$filter_name%' ");
                    }
                })
                ->orderBy('bookings.created_at', 'DESC');


            if (\Auth::user()->isDoctor()) {
                $models_query = $models_query->whereIn('status', [Booking::STATUS_COMPLETED]);

                if ($booking_status != "") {
                    $models_query = $models_query->where('status', $booking_status);
                }
            } else {
                $models_query = $models_query->whereIn('status', [Booking::STATUS_COMPLETED]);

                if ($booking_status != "") {
                    $models_query = $models_query->where('status', $booking_status);
                }
            }

            $models_query = $models_query->where('user_profiles.patient_type', UserProfile::PATIENT_TYPE_IPD);

            if ($acm_status != "") {
                $models_query = $models_query->where('accommodation_status', $acm_status);
            }

            $models_query = $models_query->whereIn('status', [Booking::STATUS_COMPLETED])->where('user_profiles.patient_type', UserProfile::PATIENT_TYPE_IPD)->whereNotIn('accommodation_status', [Booking::ACCOMMODATION_STATUS_CONFIRMED]);


            if (isset($check_in_date)) {
                $others['check_in_date'] = date('d-m-Y', strtotime($check_in_date));
                $models_query = $models_query->whereDate('check_in_date', $check_in_date);
            }

            if (isset($check_out_date)) {
                $others['check_out_date'] = date('d-m-Y', strtotime($check_out_date));
                $models_query = $models_query->whereDate('check_out_date', $check_out_date);
            }
            //->where('check_in_date', '>', date("Y-m-d H:i:s"));


            $malefemale_query = clone $models_query;

            $models = clone $malefemale_query;

            $males = $models_query->where('user_profiles.gender', UserProfile::GENDER_MALE)->count();
            // print_r($males);exit;
            $females = $malefemale_query->where('user_profiles.gender', UserProfile::GENDER_FEMALE)->count();

            if(!empty($search_data['first_name'])) {
                $matchThese['first_name'] = $search_data['first_name'];
            }

            if(!empty($address_string)) {
                $matchTheseAddress['city'] = $address_string;
            }
        }

        if ($pagination == true) {
            $count = $models->count();
            $models = $models->paginate($per_page);
        } else {
            $count = $models->count();
            $models = $models->get();
        }
        $print = true;
        $back_url = url('admin/future-patient-list');
        $future = true;
        return view('laralum.booking.print-booking', compact('models', 'print', 'count', 'males', 'females', 'back_url', 'future'));
    }

    public function printPendingBooking(Request $request)
    {
        Laralum::permissionToAccess(['admin.future_patients_management']);
        $matchThese = [];
        $search = false;
        $option_ar = [];
        $matchTheseAddress = [];

        if ($request->s && $request->s != 'null') {
            $search_data = json_decode($request->s, true);

            if (!empty($search_data['kid'])) {
                $option_ar[] = "Registration Id";
                $search = true;
                $search = true;
                $matchThese['kid'] = $search_data['kid'];
            }
            $uhid = '';
            if (!empty($search_data['uhid'])) {
                $option_ar[] = "UHID";
                $search = true;
                $search = true;
                $uhid = $search_data['uhid'];
            }
            $filter_name = "";
            if (!empty($search_data['first_name'])) {
                $option_ar[] = "First Name";
                $search = true;
                //$matchThese['first_name'] = $request->get('first_name');
                $filter_name = $search_data['first_name'];
            }

            if (!empty($search_data['filter_last_name'])) {
                $option_ar[] = "Last Name";
                $search = true;

                $matchThese['last_name'] = $search_data['filter_last_name'];
            }
            $filter_mobile = "";
            if (!empty($search_data['mobile'])) {
                $option_ar[] = "Mobile";
                $search = true;
                $filter_mobile = $search_data['mobile'];
                $matchThese['mobile'] = $search_data['mobile'];
            }

            $filter_type = "";
            if (!empty($search_data['filter_patient_type'])) {
                $option_ar[] = "Patient Type";
                $search = true;
                $filter_type = $search_data['filter_patient_type'];
                $matchThese['patient_type'] = $search_data['filter_patient_type'];
            }

            $address_string = '';
            if (!empty($search_data['city'])) {
                $option_ar[] = "City";
                $search = true;
                $address_array = explode(',', $search_data['city']);
                $address_string = $search_data['city'];
                $matchTheseAddress['city'] = $address_array[0];
                if (isset($address_array[1])) {
                    $matchTheseAddress['state'] = $address_array[1];
                }

                if (isset($address_array[2])) {
                    $matchTheseAddress['country'] = $address_array[2];
                }

            }

            $filter_accommodation_staus = "";
            if (!empty($search_data['filter_accommodation_staus'])) {
                $option_ar[] = "Accommodation Status";
                $search = true;
                $filter_accommodation_staus = $search_data['filter_accommodation_staus'];
            }

            /* $filter_name = "";
             if ($request->has('filter_name') && $request->get('filter_name') != "") {
                 $option_ar[] = "Name";
                 $search = true;
                 $filter_name = $request->get('filter_name');
             }*/

            $filter_email = "";
            if (!empty($search_data['filter_email'])) {
                $option_ar[] = "Email";
                $search = true;
                $filter_email = $search_data['filter_email'];
            }
        }
        $options = implode(", ", $option_ar);

        $error = "Entered " . $options . " is not valid,
make sure that you are entering valid " . $options . " 
or search by other options";
        $user = [];
        $per_page = $request->get("per_page") ? $request->get("per_page") : 10;
        $pagination = true;
        if ($per_page == "All") {
            $pagination = false;
        }
        if (\Auth::user()->isDoctor()) {
            $models_query = Booking::select('bookings.*')->where('bookings.check_in_date', '<=', date('Y-m-d h:i:s'))->join('users', 'users.id', '=', 'bookings.user_id')->leftjoin('user_profiles', 'user_profiles.id', 'bookings.profile_id')->orderBy('bookings.created_at', 'DESC');
        } else {
            $models_query = Booking::select('bookings.*')->join('users', 'users.id', '=', 'bookings.user_id')->leftjoin('user_profiles', 'user_profiles.id', 'bookings.profile_id')->orderBy('bookings.created_at', 'DESC');
        }

        $models_query = $models_query->whereIn('status', [Booking::STATUS_PENDING]);

        // echo '<pre>'; print_r($models_query->get());exit;

        $malefemale_query = clone $models_query;

        $models = clone $malefemale_query;

        $males = $models_query->where('user_profiles.gender', UserProfile::GENDER_MALE)->count();
        // print_r($males);exit;
        $females = $malefemale_query->where('user_profiles.gender', UserProfile::GENDER_FEMALE)->count();
        //  print_r($models->count());exit;
//print_r($matchThese);
        if ($search == true) {
            $models_query = Booking::select('bookings.*')->join('users', 'users.id', '=', 'bookings.user_id')->leftjoin('user_profiles', 'user_profiles.id', 'bookings.profile_id')->leftJoin('user_addresses', 'user_addresses.profile_id', '=', 'bookings.profile_id')->where(function ($query) use ($matchThese, $filter_email, $filter_name, $matchTheseAddress, $uhid) {
                foreach ($matchThese as $key => $match) {
                    $query->where('user_profiles.' . $key, 'like', "%$match%");
                }
                foreach ($matchTheseAddress as $key => $match) {
                    $query->where('user_addresses.' . $key, 'like', "%$match%");
                }
                if ($filter_email != "") {
                    $query->where('users.email', 'like', "%$filter_email%");
                }
                if ($uhid != "") {
                    $query->where('users.uhid', $uhid);
                }
                if ($filter_name != "") {
                    $query->where(function ($q) use ($filter_name) {
                        $q->WhereRaw("concat(user_profiles.first_name, ' ', user_profiles.last_name) like '%$filter_name%' ")->orWhere("users.name", 'like', "%$filter_name%");
                    });
                }
            })/*->where('users.email', 'like', "%" . $filter_email . "%")->where('users.name', 'like', "%" . $filter_name . "%")->where('user_profiles.mobile', 'like', "%" . $filter_mobile . "%")*/
            ->orderBy('bookings.created_at', 'DESC');

            $models_query = $models_query->whereIn('status', [Booking::STATUS_PENDING]);

            $models_query = $models_query->whereIn('status', [Booking::STATUS_PENDING]);

            // echo '<pre>'; print_r($models_query->get());exit;

            $malefemale_query = clone $models_query;

            $models = clone $malefemale_query;

            $males = $models_query->where('user_profiles.gender', UserProfile::GENDER_MALE)->count();
            // print_r($males);exit;
            $females = $malefemale_query->where('user_profiles.gender', UserProfile::GENDER_FEMALE)->count();

            //  echo '<pre>'; print_r($models->get()->toArray());exit;

            //->where('check_in_date', '>', date("Y-m-d H:i:s"));
            if(!empty($filter_name)) {
                $matchThese['first_name'] = $filter_name;
            }
            if(!empty($search_data['uhid'])) {
                $matchThese['uhid'] = $search_data['uhid'];
            }
            if(!empty($address_string)) {
                $matchTheseAddress['city'] = $address_string;
            }
        }

        if ($pagination == true) {
            $count = $models->count();
            $models = $models->paginate($per_page);
        } else {
            $count = $models->count();
            $models = $models->get();
        }
        $print = true;
        $back_url = url('admin/pending-list');
        $pending = true;
        return view('laralum.booking.print-booking', compact('models', 'print', 'count', 'males', 'females', 'back_url', 'pending'));
    }

    public function printTokens()
    {
        $models = PatientToken::where(\DB::raw('date(start_date)'), ">=", date("Y-m-d"))->where(\DB::raw('date(end_date)'), "<=", date("Y-m-d", strtotime("+24 hours")));
        $models = $models->get();
        $print = true;
        return view('laralum.booking.print-tokens', compact('models', 'print'));
    }

    /**
     * show resource in details
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show($id)
    {
        $booking = Booking::find($id);

        if ($booking->status == Booking::STATUS_PENDING) {
            return redirect('admin/booking/registration/personal_details/' . $booking->user->id);
        }

        $now = date("Y-m-d");

        $user = $booking->user;

        return view('laralum.booking.view', compact('booking', 'user', 'now'));
    }

    public function create($id = null)
    {
        $user = new User();

        if ($id != null) {
            $user = User::find($id);
        }

        return view('laralum.booking.signup', compact('admin', 'user'));
    }

    public function signupStore(Request $request)
    {
        $error_messages = User::getErrorMessages(true);
        $user = new User();
        /*
        if (\Session::get('user_id') != null) {
            $user = User::find(\Session::get('user_id'));
        }*/

        $rules = $user->getRules(true);

        $validator = \Validator::make($request->all(), $rules, $error_messages);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput()->with(['status' => 'danger', 'message' => 'Please check the errors below.']);
        }
        try {
            /* add user data */


            $data = $request->get('user');

            if ($user->setData($data)) {
                # Setup a random activation key
                $activation_key = str_random(25);
                $user->password = bcrypt($data['password']);
                $user->activation_key = $activation_key;
                $user->country_code = "IN";
                $user->save();
                \Session::put('user_id', $user->id);
                $user->saveRole(Role::ROLE_PATIENT);
                try {

                    $user->SendActivationEmail(); // send activation mail to user

                } catch (\Exception $e) {
                    Log::error("Failed to send account activation mail, possible causes: " . $e->getMessage());
                }

                return redirect()->route('Laralum::booking.personalDetails', ['user_id' => $user->id])->with(['status' => 'success', 'message' => 'Signup is completed successfully, please fill personal details now.']);
            }

            return redirect()->back()->with(['status' => 'danger', 'message' => 'Something went wrong .']);

        } catch (\Exception $e) {

            print_r($e->getMessage());
            exit;
            Log::error("Failed to add the user data during booking process. Possible causes: " . $e->getMessage());
            return redirect()->back()->with(['status' => 'danger', 'message' => 'Failed to process your request. Please try again later.']);
        }
    }

    public function personalDetails($id)
    {
        $booking = Booking::find($id);

        if (!$booking->isAllowed()) {
            abort(401, "You don't have permissions to access this area");
        }

        if (!$booking->isEditable())
            return redirect("admin/booking/" . $id . "/show")->back()->with('error', 'Patient is not active.');

        $user = $booking->user;
        $profile = $booking->userProfile;
        $address = $profile->address;
        $countries = Laralum::countries();
        $no_flags = Laralum::noFlags();
        $user_country = $address->country;
        $country = Country::where('sortname', $user_country)->first();
        $country_id = $country->id;
        $states = State::where('country_id', $country_id)->pluck('name')->toArray();
        return view('laralum.booking.personal-details', compact('user', 'profile', 'address', 'no_flags', 'countries', 'booking', 'states'));
    }

    public function personalDetailsStore(Request $request, $id)
    {
        // try {
        $booking = Booking::find($id);

        if (!$booking->isEditable())
            return redirect("admin/booking/" . $id . "/show")->back()->with('error', 'Patient is not active.');

        $user = $booking->user;


        // print_r($request->all());exit;
        /* add user profile */

        $userProfile = $booking->userProfile;
        if ($userProfile == null) {
            $userProfile = new UserProfile();
        }

        $userProfileData = $request->get('userProfile');
        $userProfileData['file'] = $request->file('profile_picture');

        /* add user address */
        $userAddress = $userProfile->address;

        if ($userAddress == null) {
            $userAddress = new UserAddress();
            $userAddress->profile_id = $userProfile->id;
        }

        $userAddressData = $request->get('userAddress');

        $error_messages = UserProfile::getErrorMessages(true);
        $rules = $userProfile->getRules(true);
        $rules['userProfile.patient_type'] = '';
        $address_rules = $userAddress->getRules();
        $rule_array = array_merge($address_rules, $rules);

        $validator = \Validator::make($request->all(), $rule_array, $error_messages);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput()->with('error', 'Please check following errors');
        }

        if (!empty($request->file('profile_picture'))) {
            if ($request->file('profile_picture')->getSize() > 2097152 || $request->file('profile_picture')->getSize() == "") {
                return redirect()->back()->withErrors($validator)->withInput()->with('error', 'One or more files exceeds the max size limit of 2MB.');
            }
        }

        if (!$userProfile->checkDocuments($request)) {
            return redirect()->back()->withErrors($validator)->withInput()->with('error', 'One or more files exceeds the max size limit of 2MB.');
        }


if ($user->uhid == null ){
$user->update([
'uhid' => $user->getUhid()]);
}

        if ($userProfile->setData($userProfileData, $user->id)) {
            if (isset($request->get('userProfile')['profession_name'])) {
                if ($request->get('userProfile')['profession_name']) {
                    $profession = Profession::where('name', $request->get('userProfile')['profession_name'])->first();
                    if ($profession == null) {
                        $profession = Profession::create([
                            'name' => $request->get('userProfile')['profession_name'],
                            'slug' => str_slug($request->get('userProfile')['profession_name']),
                            'is_private' => Profession::IS_PRIVATE
                        ]);
                    }
                    $userProfile->profession_id = $profession->id;
                    $userProfile->save();
                }
            }

            $userProfile->save();
            $userProfile->saveDocuments($request);
        }


        if ($userAddress->setData($userAddressData, $user->id)) {
            $userAddress->profile_id = $userProfile->id;
            $userAddress->save();
        }

        if (isset($userProfileData['patient_type'])) {
            $booking->update([
                'patient_type' => $userProfileData['patient_type'],
                'profile_id' => $userProfile->id
            ]);
        }

         if ($userProfile->patient_type == UserProfile::PATIENT_TYPE_OPD && $booking->booking_kid == null) {
	     $booking->update([
                 'booking_kid' => User::getId("K-OPD", $booking->getKIdNumber())
             ]);
           }
        return redirect()->back()->with('success', 'Successfully Saved Details.');
        /*
        if (\Auth::user()->isPatient())
            return redirect()->route('Laralum::user.booking.health_issues', ['booking_id' => $booking->id]);

        return redirect()->route('Laralum::booking.health_issues', ['booking_id' => $booking->id]);*/
        /*} catch (\Exception $e) {

            Log::error("Failed to add the personal details during booking process. Possible causes: " . $e->getMessage());
            return redirect()->back()->withInput()->with(['status' => 'danger', 'message' => 'Something went wrong .']);
        }*/

    }

    public function getStates($id)
    {
        $country_id = Country::where('sortname', $id)->pluck('id')->first();
        return State::where('country_id', $country_id)->pluck('name')->toArray();
        // return $country_id;
    }

    public function healthIssues($id = null)
    {
        $booking = Booking::find($id);

        if (!$booking->isAllowed()) {
            abort(401, "You don't have permissions to access this area");
        }

        if (!$booking->isEditable())
            return redirect("admin/booking/" . $id . "/show")->back()->with('error', 'Patient is not active.');

        $user = $booking->user;
        $profile = $booking->userProfile;
        return view('laralum.booking.health_issues', compact('user', 'profile', 'booking'));
    }

    public function healthIssuesStore(Request $request, $id)
    {
        $booking = Booking::find($id);

        if (!$booking->isEditable())
            return redirect("admin/booking/" . $id . "/show")->back()->with('error', 'Patient is not active.');

        $user = $booking->user;

        if (!empty($request->all())) {
            if (isset($request->health_issues) && !empty($request->health_issues)) {
                try {

                    $profile = $booking->userProfile;
                    if ($profile != null) {
                        $profile->update(['health_issues' => $request->health_issues]);
                        $booking->update(['health_issues' => $request->health_issues]);
                    }

                    $healthIssues = HealthIssue::where([
                        'user_id' => $user->id,
                        'status' => Booking::STATUS_PENDING,
                        'booking_id' => $booking->id
                    ])->first();
                    if ($healthIssues == null) {
                        $healthIssues = new HealthIssue();
                    }
                    $healthIssues->user_id = $user->id;
                    $healthIssues->booking_id = $booking->id;
                    $healthIssues->status = HealthIssue::STATUS_PENDING;
                    $healthIssues->health_issues = $request->health_issues;
                    $healthIssues->save();

                    \Session::put('health_issues', $healthIssues->id);

                } catch (\Exception $e) {
                    Log::error("Failed to add the health issues during booking process. Possible causes: " . $e->getMessage());
                    return redirect()->back()->with(['status' => 'danger', 'message' => 'Something went wrong .']);
                }

                return redirect()->back()->with('success', 'Successfully Saved Details.');
                /* if ($user->checkAccommodation($booking->id)) {
                     return redirect()->route('Laralum::booking.accommodation', ['user_id' => $booking->id]);
                 } else {
                     return redirect()->route('Laralum::booking.payment', ['user_id' => $booking->id]);
                 }*/
            }
        }

        return redirect()->back()->with('error', 'Please input health issues');
    }

    public function accommodation(Request $request, $id)
    {
        $booking = Booking::find($id);

        if (!$booking->isAllowed()) {
            abort(401, "You don't have permissions to access this area");
        }

        if (!$booking->isEditable())
            return redirect("admin/booking/" . $id . "/show")->with('error', 'Patient is not active.');

        $user = $booking->user;
        $profile = $user->userProfile;
        $members = [];
        $booking->check_in_date = $booking->check_in_date != "0000-00-00 00:00:00" ? date("Y-m-d", strtotime($booking->check_in_date)) : "";
        $booking->check_out_date = $booking->check_out_date != "0000-00-00 00:00:00" ? date("Y-m-d", strtotime($booking->check_out_date)) : "";
        $booking->external_services = explode(',', $booking->external_services);

        $data['booking'] = $booking;

        if ($booking->members->count() > 0) {
            $members = $booking->members;
        }
        $data['members'] = $members;
        $data['user'] = $user;
        $data['user_id'] = $user->id;
        //dd($data);
        return view('laralum.booking.accommodation', $data);
    }

    public function accommodationRequest(Request $request, $id)
    {
        /*echo '<pre>';
        print_r($request->all());
        exit;*/
        $model = Booking::find($id);

        if (!$model->isAllowed()) {
            abort(401, "You don't have permissions to access this area");
        }

        if (!$model->isEditable())
            return redirect("admin/booking/" . $id . "/show")->back()->with('error', 'Patient is not active.');

        $user = $model->user;

        if ($model->accommodation_status != Booking::ACCOMMODATION_STATUS_CONFIRMED) {

            \Validator::extend('greater_than', function ($attribute, $value, $parameters, $validator) {
                $min_field = $parameters[0];
                $data = $validator->getData();
                $min_value = $data[$min_field];
                return $value > $min_value;
            });
            \Validator::replacer('greater_than', function ($message, $attribute, $rule, $params) {
                return str_replace('_', ' ', 'The ' . $attribute . ' must be greater than the ' . $params[0]);
            });


            $rules = array_merge($model->rules(), [
                'check_out_date' => 'required|date_format:d-m-Y|greater_than:check_in_date',
                'check_in_date' => 'required|date_format:d-m-Y'
            ]);


            $validator = \Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator->errors());
            }
            $booking_id = $model->booking_id;
            $model->setData($request);
            $model->booking_id = $booking_id;
        }

        $check = $model->checkBooking();

        if ($check == true) {
            $model->save();
            $model->saveMembers($request);
            \Session::put('booking_id', $model->id);
            return redirect()->back()->with('success', 'Successfully Saved Details.');
            /*  return redirect(route('Laralum::booking.payment', ['booking_id' => $id]))->with('success', 'Booking has been saved successfully.');*/
        }

        return redirect()->back()->with('error', "No Room Available for these dates");
    }

    public function payment($id)
    {
        $booking = Booking::find($id);
        /*
                if (!$booking->isAllowed()) {
                    abort(401, "You don't have permissions to access this area");
                }*/

        if (!$booking->isEditable())
            return redirect("admin/booking/" . $id . "/show")->back()->with('error', 'Patient is not active.');

        $user = $booking->user;
        $profile = $user->userProfile;
        return view('laralum.booking.payment', compact('user', 'booking'));
    }

    public function paymentStore(Request $request, $id)
    {
        $booking = Booking::find($id);

        /*  if (!$booking->isAllowed()) {
              abort(401, "You don't have permissions to access this area");
          }*/

        if (!$booking->isEditable())
            return redirect("admin/booking/" . $id . "/show")->back()->with('error', 'Patient is not active.');

        $user = $booking->user;
        $profile = $user->userProfile;
        if ($user != null) {
            $payment_detail = PaymentDetail::where([
                'user_id' => $user->id,
                'booking_id' => $booking->id,
            ])->first();

            if ($payment_detail == null) {
                PaymentDetail::create([
                    'user_id' => $user->id,
                    'booking_id' => $booking->id,
                    'type' => $request->get('payment_method'),
                ]);
            }

            if ($request->get('amount') > 0) {
                $wallet = Wallet::where([
                    'user_id' => $request->get('user_id'),
                    'type' => Wallet::TYPE_PAID,
                    'status' => Wallet::STATUS_PENDING,
                    'booking_id' => $request->get('booking_id'),
                ])->first();

                if ($wallet == null) {
                    $wallet = Wallet::create([
                        'user_id' => $request->get('user_id'),
                        'amount' => $request->get('amount'),
                        'type' => Wallet::TYPE_PAID,
                        'created_by' => \Auth::user()->id,
                        'status' => Wallet::STATUS_PAID,
                        'payment_method' => $request->get('payment_method'),
                        'booking_id' => $request->get('booking_id'),
                        'description' => $request->get('description'),
                    ]);
                } else {
                    $wallet->amount = $request->get('amount');
                    $wallet->save();
                }
            }
            return redirect()->back()->with('success', 'Successfully Saved Details.');/*

            return redirect()->route('Laralum::booking.confirm', ['user_id' => $id]);*/
        }

        return redirect()->back()->withInput()->with('error', "Something went wrong!!!");
    }

    public function confirm($id)
    {
        //return "gfsjgfshdfjhsgfjsgfjsgd";
        $booking = Booking::find($id);

        if (!$booking->isAllowed()) {
            abort(401, "You don't have permissions to access this area");
        }

        if (!$booking->isEditable())
            return redirect("admin/booking/" . $id . "/show")->back()->with('error', 'Patient is not active.');

        $user = $booking->user;
        $profile = $user->userProfile;
        $healthIssues = HealthIssue::where([
            'user_id' => $user->id,
            'status' => HealthIssue::STATUS_PENDING
        ])->first();

        if ($user->checkAccommodation($id)) {
            return view('laralum.booking.confirm', compact('user', 'booking', 'healthIssues'));
        }

        return view('laralum.booking.confirm', compact('user', 'booking', 'healthIssues'));
    }

    public function confirmStore(Request $request, $id)
    {
        return "hfhgfghfghf";
        $booking = Booking::find($id);
        $data = $booking->setMailData();

        if (!empty($booking->user->email)) {
            EmailTemplate::sendEmail(EmailTemplate::EVENT_BOOKING, $data, $booking->user->email);
        }

        EmailTemplate::sendEmail(EmailTemplate::EVENT_BOOKING, $data, \Auth::user()->email);

        if (!$booking->isAllowed()) {
            abort(401, "You don't have permissions to access this area");
        }

        if (!$booking->isEditable())
            return redirect("admin/booking/" . $id . "/show")->back()->with('error', 'Patient is not active.');

        $user = $booking->user;
        $profile = $booking->userProfile;

        if ($profile->kid == null) {
            if ($profile->patient_type == UserProfile::PATIENT_TYPE_OPD && $profile->kid == null) {
                $profile->update([
                    'kid' => User::getId("K-OPD", $profile->getIdNumber())
                ]);
            }/* else if ($profile->kid == null) {
                $profile->update([
                    'kid' => User::getId("K-IPD", $profile->getIdNumber())
                ]);
            }*/
        }

        if ($user->uhid == null) {
            $user->update([
                'uhid' => $user->getUhid()
            ]);
        }
        if ($booking->booking_id == null || $booking->booking_id == "B0000" || $booking->booking_id == 0) {
            $booking->update([
                'booking_id' => $booking->getIdNumber(),
                'status' => Booking::STATUS_COMPLETED
            ]);

            $data = $booking->setMailData();
            if (!empty($booking->user->email)) {
                EmailTemplate::sendEmail(EmailTemplate::EVENT_BOOKING, $data, $user->email);
            }
            EmailTemplate::sendEmail(EmailTemplate::EVENT_BOOKING, $data, \Auth::user()->email);

        } else {
            $booking->update([
                'status' => Booking::STATUS_COMPLETED
            ]);
        }

        $wallet = Wallet::where([
            'user_id' => $user->id,
            'type' => Wallet::TYPE_PAID,
            'status' => Wallet::STATUS_PENDING,
            'booking_id' => $booking->id,
        ])->first();

        if ($wallet) {
            $wallet->status = Wallet::STATUS_PAID;
            $wallet->save();
        }

        return redirect()->back()->with('success', 'Successfully Saved Details.');
        /*if (\Auth::user()->isPatient()) {
            return redirect()->route('Laralum::booking.show', ['booking_id' => $booking->id])->with(['status' => 'success', 'message' => 'Booking has been saved successfully.']);
        }
        return redirect()->route('Laralum::booking.print_kid', ['booking_id' => $booking->id])->with(['status' => 'success', 'message' => 'Booking has been completed successfully.']);*/
    }

    public function tokenList(Request $request)
    {
        Laralum::permissionToAccess('admin.bookings.tokens.list');
        $models = PatientToken::where(\DB::raw('date(start_date)'), ">=", date("Y-m-d"))->where(\DB::raw('date(end_date)'), "<=", date("Y-m-d", strtotime("+24 hours")))->orderBy('patient_tokens.created_at', 'DESC');
        $per_page = $request->get("per_page") ? $request->get("per_page") : 10;
        $pagination = true;
        if ($per_page == "All") {
            $pagination = false;
        }
        $count = $models->count();
        if ($pagination == true) {
            $models = $models->paginate($per_page);
        } else {
            $models = $models->get();
        }

        $data['models'] = $models;
        $data['count'] = $count;
        return view('laralum.booking.generate-token-list', $data);
    }

    public function generateToken(Request $request, $id = null)
    {
        //return $request->all();
        Laralum::permissionToAccess('admin.bookings.tokens.list');
        $data = [];
        $matchThese = [];
        $search = false;
        $patient = [];
        $user = [];

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


        if ($id != null) {
            $booking = Booking::find($id);

            if (!$booking->isAllowed()) {
                abort(401, "You don't have permissions to access this area");
            }

            if (!$booking->isEditable())
                return redirect("admin/booking/" . $id . "/show")->back()->with('error', 'Patient is not active.');

            if (!$booking->bookingValidity()) {
                return redirect('admin/booking/' . $booking->id . '/show')->with('error', "Can not generate token, as booking is not available on current date");
            }
            $user = $booking->user;
        } else {
            $booking = new Booking();
        }

        //echo '<pre>';print_r($matchThese);exit;

        if ($search == true) {
            $booking = Booking::select('bookings.*')
                ->where('status', Booking::STATUS_COMPLETED)
                ->join('users', 'users.id', '=', 'bookings.user_id')
                ->leftJoin('user_profiles', 'bookings.profile_id', '=', 'user_profiles.id')
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
                    /*if ($filter_name != "") {
                        $query->where('users.name', 'like', "%$filter_name%");
                    }*/
                });
            /*echo '<pre>';
                        print_r($booking->first());
            exit;*/
            if ($filter_email != '') {
                $booking = $booking->where('users.email', 'like', "%" . $filter_email . "%")
                    /*->where('users.name', 'like', "%" . $filter_name . "%")*/
                    ->where('user_profiles.mobile', 'like', "%" . $filter_mobile . "%");
            }

            if ($kid != null) {
                $booking = $booking->where('user_profiles.kid', $kid);
            }

            $booking = $booking->first();


            $user = new User();
            if ($booking != null) {
                if (!$booking->bookingValidity()) {
                    return redirect('admin/booking/' . $booking->id . '/show')->with('error', "Can not generate token, as booking is not available on current date");
                }
                $user = $booking->user;
            } else {
                $booking = new Booking();
            }
        }

        if (!$booking->isEditable())
            return redirect()->back()->with('error', 'Patient is not active.');

        $date = (string)date('Y-m-d');
        $token = PatientToken::where(\DB::raw('date(`start_date`)'), $date)->orderBy('created_at', 'DESC')->first();
        $data['token_no'] = 1;


        if ($user == null) {
            $user = new User();
        }

        if ($token != null) {
            if ($token->Status == PatientToken::STATUS_PENDING && $token->patient_id == $user->id) {
                $data['token_no'] = $token->token_no;
            } else {
                $data['token_no'] = $token->token_no + 1;
            }
        }

        if (isset($matchThese['first_name'])) {
            $matchThese['first_name'] = $matchThese['first_name'] . ' ' . $matchThese['last_name'];
        }

        $data['patient'] = $user;
        $data['booking'] = $booking;
        $data['user'] = $data['patient'];
        $data['search'] = $search;
        $data['error'] = $error;

        return view('laralum.booking.generate-token', $data);
    }

    public function tokenAjaxUpdate(Request $request)
    {
        //Laralum::permissionToAccess('admin.patients.list');
        $matchThese = [];
        $profileMatchThese = [];
        $matchTheseAddress = [];
        $doctorMatchThese = [];
        $userMatchThese = [];
        $search = false;
        $option_ar = [];
        if (!empty($request->get('city'))) {
            $option_ar[] = "City";
            $search = true;
            $matchTheseAddress['city'] = $request->get('city');
        }

        if (!empty($request->get('token_no'))) {
            $option_ar[] = "Token Number";
            $search = true;
            $matchThese['token_no'] = $request->get('token_no');
        }
        if (!empty($request->get('kid'))) {
            $option_ar[] = "Patient Id";
            $search = true;
            $profileMatchThese['kid'] = $request->get('kid');
        }
        if (!empty($request->get('uhid'))) {
            $option_ar[] = "UH Id";
            $search = true;
            $userMatchThese['uhid'] = $request->get('uhid');
        }

        if (!empty($request->get('department_id'))) {
            $option_ar[] = "Department";
            $search = true;
            $matchThese['department_id'] = $request->get('department_id');
        }

        $filter_name = '';
        if (!empty($request->get('first_name'))) {
            $option_ar[] = "Name";
            $search = true;
            $filter_name = $request->get('first_name');
            //$profileMatchThese['first_name'] = $request->get('first_name');
        }

        $start_date = "";
        if (!empty($request->get('start_date'))) {
            $option_ar[] = "Date";
            $search = true;
            $start_date = date("Y-m-d", strtotime($request->get('start_date')));
        }

        $doc_name = "";

        if (!empty($request->get('doctor_name'))) {
            $option_ar[] = "Doctor Name";
            $search = true;
            $doctorMatchThese['doctor_name'] = $request->get('doctor_name');
            $doc_name = $request->get('doctor_name');
        }

        $options = implode(", ", $option_ar);

        $error = "Entered " . $options . " is not valid,
make sure that you are entering valid " . $options . " 
or search by other options";
        $user = [];
        $per_page = $request->get("per_page") ? $request->get("per_page") : 10;
        $pagination = true;
        if ($per_page == "All") {
            $pagination = false;
        }

        $models = PatientToken::select('patient_tokens.*')->where(\DB::raw('date(start_date)'), ">=", date("Y-m-d"))->where(\DB::raw('date(end_date)'), "<=", date("Y-m-d", strtotime("+24 hours")))->orderBy('patient_tokens.created_at', 'DESC');
        $count = 0;


        if ($search == true) {
            $models = PatientToken::select('patient_tokens.*')->where(\DB::raw('date(start_date)'), ">=", date("Y-m-d"))->where(\DB::raw('date(end_date)'), "<=", date("Y-m-d", strtotime("+24 hours")))->join('bookings', 'bookings.id', '=', 'patient_tokens.booking_id')
                ->join('user_profiles', 'user_profiles.id', '=', 'bookings.profile_id')
                ->join('users', 'users.id', '=', 'bookings.user_id')
                ->join('users as doctors', 'doctors.id', '=', 'patient_tokens.doctor_id')
                ->leftJoin('user_addresses', 'user_addresses.profile_id', '=', 'bookings.profile_id')
                ->where(function ($query) use ($matchThese, $profileMatchThese, $doc_name, $matchTheseAddress, $start_date, $filter_name, $userMatchThese) {
                    foreach ($matchTheseAddress as $key => $match) {
                        $query->where('user_addresses.' . $key, 'like', "%$match%");
                    }

                    foreach ($matchThese as $key => $match) {
                        $query->where('patient_tokens.' . $key, 'like', "%$match%");
                    }

                    foreach ($profileMatchThese as $key => $match) {
                        if ($key == 'kid') {
                            $query->where('user_profiles.kid', $match);
                        } else {
                            $query->where('user_profiles.' . $key, 'like', "%$match%");
                        }
                    }
                    if ($start_date != "") {
                        $query->whereDate('patient_tokens.start_date', $start_date);
                    }

                    if ($filter_name != "") {
                        $query->WhereRaw("concat(user_profiles.first_name, ' ', user_profiles.last_name) like '%$filter_name%' ");
                    }

                    foreach ($userMatchThese as $key => $match) {
                        $query->where('users.' . $key, 'like', '%' . $match . '%');
                    }

                    if ($doc_name != "") {
                        $query->where('doctors.name', 'LIKE', '%' . $doc_name . '%');
                    }
                })->orderBy('patient_tokens.created_at', 'DESC');

            //   echo '<pre>'; print_r($models->get());exit;
            if ($start_date != "") {
                $models = $models->where(\DB::raw('date(start_date)'), "=", $start_date);
                $matchThese['start_date'] = date("d-m-Y", strtotime($start_date));
            }
            $models = $models->get();
            $count = $models->count();
            if ($filter_name != "") {
                $profileMatchThese['first_name'] = $filter_name;
            }
        } else {
            if ($pagination == true) {
                $count = $models->count();
                $models = $models->paginate($per_page);
            } else {
                $count = $models->count();
                $models = $models->get();
            }
        }

        /*echo '<pre>'; print_r($matchThese['role_id']);exit;*/
        # Return the view
        return [
            'html' => view('laralum/booking/_tokens', ['models' => $models, 'count' => $count, 'error' => $error, 'search' => $search, 'search_data' => array_merge($matchThese, $profileMatchThese, $doctorMatchThese, $userMatchThese)])->render()
        ];
    }

    public function printToken(Request $request, $id = null)
    {
        Laralum::permissionToAccess('admin.bookings.tokens.list');

        if ($id == null)
            $id = $request->get('booking_id');

        $booking = Booking::find($id);

        if (!$booking->isAllowed()) {
            abort(401, "You don't have permissions to access this area");
        }

        if (!$booking->isEditable())
            return redirect("admin/booking/" . $id . "/show")->back()->with('error', 'Patient is not active.');

        /*$token = PatientToken::where([
            'booking_id' => $id,
            'doctor_id' => $request->get('doctor_id'),
            'department_id' => $request->get('department_id'),
            'status' => PatientToken::STATUS_PENDING
        ])->where(\DB::raw('date(`start_date`)'), date('Y-m-d'))->first();*/

        //if ($token == null) {
        $token = new PatientToken();
        $token->setData($request);
        $token->booking_id = $id;
        $token->patient_id = $booking->user_id;
        $token->save();
        //}

        return view('laralum.booking.print-token', compact('token'));
    }

    public function printPatientToken($id = null)
    {
        Laralum::permissionToAccess('admin.bookings.tokens.list');
        $token = PatientToken::find($id);
        $back_url = url('admin/token-list');
        return view('laralum.booking.print-token', compact('token', 'back_url'));
    }

    public function deleteToken($id)
    {
        Laralum::permissionToAccess('admin.bookings.tokens.list');
        $token = PatientToken::find($id);
        if ($token != null) {
            $token->delete();
            return redirect('admin/token-list')->with('success', 'Successfully Deleted Token!');
        }
        return redirect('admin/token-list')->with('error', 'Something went wrong!');
    }

    public function generatePatientCard(Request $request, $id = null)
    {
        $matchThese = [];
        $search = false;
        $option_ar = [];
        if ($request->has('filter_patient_id') && $request->get('filter_patient_id') != "") {
            $option_ar[] = "Patient Id";
            $search = true;
            $matchThese['kid'] = $request->get('filter_patient_id');
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
        $options = implode(", ", $option_ar);

        $error = "Entered " . $options . " is not valid,
make sure that you are entering valid " . $options . " 
or search by other options";
        $user = [];
        $booking = new Booking();
        if ($search == true) {
            $booking = Booking::select('bookings.*')->where('status', Booking::STATUS_COMPLETED)->join('users', 'users.id', '=', 'bookings.user_id')->join('user_profiles', 'user_profiles.booking_id', 'bookings.id')->where('users.email', 'like', "%" . $filter_email . "%")->where(function ($query) use ($matchThese, $filter_email) {
                foreach ($matchThese as $key => $match) {
                    $query->where('user_profiles.' . $key, 'like', "%$match%");
                }
                if ($filter_email != "") {
                    $query->where('users.email', 'like', "%$filter_email%");
                }
            });
            $booking = $booking->first();

            /* $user = User::select('users.*')
                 ->join('user_profiles', 'users.id', '=', 'user_profiles.user_id')
                 ->where(function ($query) use ($matchThese, $filter_email) {
                     foreach ($matchThese as $key => $match) {
                         $query->where('user_profiles.' . $key, 'like', "%$match%");
                     }
                     if ($filter_email != "") {
                         $query->where('users.email', 'like', "%$filter_email%");
                     }
                 })->join('role_user', 'role_user.user_id', 'users.id')->where('role_user.role_id', Role::getPatientId())->first();*/
        }

        if ($id != null) {
            $booking = Booking::find($id);

            if (!$booking->isAllowed()) {
                abort(401, "You don't have permissions to access this area");
            }
        }

        if (!$booking->isEditable())
            return redirect("admin/booking/" . $id . "/show")->back()->with('error', 'Patient is not active.');
        $user = new User();

        if (!empty($booking->id)) {
            $user = $booking->user;
        }


       /* $d = new DNS1D();
        $twod = new DNS2D();
        $barcode = null;
        $qrcode = null;*/

        /*if (!empty($booking->id)) {
            //$barcode = $d->getBarcodePNG($booking->getProfile('kid'), "C39+");
            //$qrcode = $twod->getBarcodePNG($booking->getProfile('kid'), "QRCODE");
        } else {
            $user = new User();
        }*/
        if ($id != null)
            return view('laralum.booking.patient-card', compact( 'user', 'booking'));

        return view('laralum.booking.generate-patient-card', compact( 'user', 'search', 'error', 'booking'));
    }

    public function printPatientCard(Request $request, $id)
    {
        $booking = Booking::find($id);


        if (!$booking->isAllowed()) {
            abort(401, "You don't have permissions to access this area");
        }

        if (!$booking->isEditable())
            return redirect("admin/booking/" . $id . "/show")->back()->with('error', 'Patient is not active.');
        //$kid = $booking->getProfile('kid');
        $user = User::find($id);
        /*$d = new DNS1D();
        $twod = new DNS2D();*/
        //$barcode = $d->getBarcodePNG($kid, "C39+");
        //$qrcode = $twod->getBarcodePNG($kid, "QRCODE");
        $back_url = "";
        if ($request->get("backurl")) {
            $back_url = $request->get("backurl");
        }
        return view('laralum.booking.print-patient-card', compact('booking', 'user', 'back_url'));
    }

    public function dischargeBillings(Request $request, $id = null)
    {
        //return $request->all();
        $data = [];
        $amount = [
            'accomodation_amount' => 0,
            'amount' => 0,
            'pending_amount' => 0,
            'paid_amount' => 0,
            'service_amount' => 0,
            'discount_amount' => 0,
            'refund_amount' => 0
        ];
        $option_ar = [];
        $search = false;
        $booking = new Booking();
        $user = new User();
        $discharge_date = '-';

        if ($id != null) {
            $booking = Booking::find($id);

            if (!$booking->isAllowed()) {
                abort(401, "You don't have permissions to access this area");
            }

            if (!$booking->isEditable()) {
                return redirect("admin/booking/" . $id . "/show")->with('error', 'Patient is not active.');
            }

            $user = $booking->user;

            $discharge_patient = DischargePatient::where([
                'booking_id' => $booking->id,
                'status' => DischargePatient::STATUS_PENDING
            ])->first();

            if ($discharge_patient != null) {
                $discharge_date = $discharge_patient->date_of_discharge;
                $user = $booking->user;
            } else {
                $url = url('admin/booking/discharge-patient-billing/' . $id);

                return redirect('admin/bookings')->with('error', 'Please make sure the booking is not archived.');
            }
        } else {
            $matchThese = [];
            if ($request->has('filter_patient_id') && $request->get('filter_patient_id') != "") {
                $option_ar[] = "Patient Id";
                $search = true;
                $matchThese['kid'] = $request->get('filter_patient_id');
            }
            $uhid = "";
            if ($request->has('filter_uh_id') && $request->get('filter_uh_id') != "") {
                $option_ar[] = "UH Id";
                $search = true;
                $uhid = $request->get('filter_uh_id');
                //$matchThese['uhid'] = $request->get('filter_uh_id');
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

            if ($search == true) {
                $discharge = DischargePatient::select('discharge_patients.*')->where('discharge_patients.status', DischargePatient::STATUS_PENDING)
                    ->join('users', 'users.id', '=', 'discharge_patients.patient_id')
                    ->join('bookings', 'discharge_patients.booking_id', '=', 'bookings.id')
                    ->join('user_profiles', 'bookings.profile_id', '=', 'user_profiles.id')
                    ->where('bookings.status', Booking::STATUS_COMPLETED)
                    /*   ->where('users.is_discharged', User::ADMIT)*/
                    ->where(function ($query) use ($matchThese, $filter_email, $filter_name, $uhid) {
                        foreach ($matchThese as $key => $match) {
                            $query->where('user_profiles.' . $key, 'like', "%$match%");
                        }
                        if ($filter_email != "") {
                            $query->where('users.email', 'like', "%$filter_email%");
                        }

                        if ($filter_name != "") {
                            $query->WhereRaw("concat(user_profiles.first_name, ' ', user_profiles.last_name) like '%$filter_name%' ");
                        }

                        if ($uhid != "") {
                            $query->where('users.uhid', $uhid);
                        }
                    })->first();


                if ($discharge != null) {
                    $discharge_date = $discharge->date_of_discharge;
                    $booking = $discharge->booking;

                    if (!$booking->isAllowed()) {
                        abort(401, "You don't have permissions to access this area");
                    }

                    /* if (!$booking->isEditable())
                         $booking = [];
                         return redirect("admin/booking/".$id."/show")->back()->with('error', 'Patient is not active.');*/
                } else {
                    $discharge_date = '-';
                }
            }
        }


        $options = implode(", ", $option_ar);

        $error = "Entered " . $options . " is not valid,
make sure that you are entering valid " . $options . " 
or search by other options";
        $data['user'] = $user;
        $data['booking'] = $booking;
        $data['error'] = $error;
        $data['search'] = $search;
        $data['discharge_date'] = $discharge_date;
       // return dd($data);

        return view('laralum.booking.discharge_patient', $data);

    }

    public function getAccommodationDetails($id, $discharge = false)
    {
        $booking = Booking::find($id);

        /*if (!$booking->isAllowed()) {
            abort(401, "You don't have permissions to access this area");
        }*/

        $data['booking'] = $booking;
        $booking_rooms = $booking->bookingRooms;
        $data['booking_rooms'] = $booking_rooms;
        $data['discharge'] = $discharge;
        // $data['user_info'] = $user_info;
        return view('laralum.booking.get-booking-info', $data);
    }

    public function getServicesDetails($id, $discharge = false)
    {
        $booking = Booking::find($id);

        /*if (!$booking->isAllowed()) {
            abort(401, "You don't have permissions to access this area");
        }*/

        $user = $booking->user;
        $services = true;
        $data['discharge'] = $discharge;
        return view('laralum.booking.get-booking-info', compact('user', 'services', 'booking', 'discharge'));
    }

    public function getPaidDetails($id)
    {
        $booking = Booking::find($id);
        /* if (!$booking->isAllowed()) {
             abort(401, "You don't have permissions to access this area");
         }*/
        $user = $booking->user;
        $items = Wallet::where('booking_id', $booking->id)->where('status', Wallet::STATUS_PAID)->where('type', Wallet::TYPE_PAID)->get();
        $discharge = true;
        return view('laralum.booking.get-booking-info', compact('user', 'items', 'booking', 'discharge'));
    }

    public function getRefundDetails($id)
    {
        $booking = Booking::find($id);
        /* if (!$booking->isAllowed()) {
             abort(401, "You don't have permissions to access this area");
         }*/
        $user = $booking->user;
        $discharge = true;
        $items = Wallet::where('user_id', $booking->user_id)->where('status', Wallet::STATUS_PAID)->where('type', Wallet::TYPE_REFUND)->get();
        return view('laralum.booking.get-booking-info', compact('user', 'items', 'booking', 'discharge'));
    }

    public function getDiscountDetails($id)
    {
        $booking = Booking::find($id);
        /*  if (!$booking->isAllowed()) {
              abort(401, "You don't have permissions to access this area");
          }*/
        $user = $booking->user;
        $discount = true;
        $discharge = false;
        return view('laralum.booking.get-booking-info', compact('user', 'discount', 'booking', 'discharge'));
    }

    public function getDiscountDetailsWithoutBill($id)
    {
        $booking = Booking::find($id);
        /*  if (!$booking->isAllowed()) {
              abort(401, "You don't have permissions to access this area");
          }*/
        $user = $booking->user;
        $discount = true;
        $discharge = true;
        return view('laralum.booking.get-booking-info', compact('user', 'discount', 'booking', 'discharge'));
    }
    public function addDiscount($id)
    {
        $booking = Booking::find($id);
        /*  if (!$booking->isAllowed()) {
              abort(401, "You don't have permissions to access this area");
          }*/
        $user = $booking->user;
        $addDiscount = true;
        $discharge = true;

        return view('laralum.booking.get-booking-info', compact('user', 'addDiscount', 'booking', 'discharge'));
    }

    public function availDiscount(Request $request, $id)
    {
        $booking = Booking::find($id);


        $pending = $booking->getPendingAmount(true);


        /*if (!$booking->isAllowed()) {
            abort(401, "You don't have permissions to access this area");
        }*/
        if (!$booking->isEditable())
            return redirect("admin/booking/" . $id . "/show")->back()->with('error', 'Patient is not active.');
        $offer = [];
        if ($request->get('code') != null) {
            $offer = DiscountOffer::where('code', $request->get('code'))->where('status', DiscountOffer::STATUS_ACTIVE)->first();
        } elseif ($request->get('discount_flat') != null) {
            $offer = DiscountOffer::where([
                'type' => DiscountOffer::TYPE_FLAT,
                'discount_value' => $request->get('discount_flat'),
            ])->first();


            if ($offer == null) {
                $offer = DiscountOffer::create([
                    'code' => 'FlatDiscount-' . time(),
                    'type' => DiscountOffer::TYPE_FLAT,
                    'discount_value' => $request->get('discount_flat'),
                ]);
            }else{
                if ($offer->type == DiscountOffer::TYPE_FLAT) {
                    $discount = $offer->discount_value;
                } else {
                    $discount = $offer->discount_value * $request->get("price") / 100;
                }

                $amount = $request->get("price") - $discount;
                if($pending < $amount) {
                    return [
                        'success' => 'NOK'
                    ];
                }
            }
        } elseif ($request->get('discount_perc') != null) {
            $offer = DiscountOffer::where([
                'type' => DiscountOffer::TYPE_PERC,
                'discount_value' => $request->get('discount_perc'),
            ])->first();
            if ($offer == null) {
                $offer = DiscountOffer::create([
                    'code' => 'PercDiscount-' . time(),
                    'type' => DiscountOffer::TYPE_PERC,
                    'discount_value' => $request->get('discount_perc'),
                ]);
            }else{
                if ($offer->type == DiscountOffer::TYPE_FLAT) {
                    $discount = $offer->discount_value;
                } else {
                    $discount = $offer->discount_value * $request->get("price") / 100;
                }

                $amount = $request->get("price") - $discount;
                if($pending < $amount) {
                    return [
                        'success' => 'NOK'
                    ];
                }
            }
        }

        if ($offer != null) {
            $discount = 0;

            if ($offer->type == DiscountOffer::TYPE_FLAT) {
                $discount = $offer->discount_value;
            } else {
                $discount = $offer->discount_value * $request->get("price") / 100;
            }

            $amount = $pending - $discount;

            if($amount > 0) {
                $booking_discount = BookingDiscount::create([
                    'booking_id' => $id,
                    'discount_id' => $offer->id,
                    'discount_amount' => $discount,
                    'basic_amount' => $amount,
                    'user_id' => $booking->user_id,
                    'description' => $request->get('description'),
                    'created_by' => \Auth::user()->id
                ]);

                return [
                    'success' => 'OK'
                ];
            }
        }
        return [
            'success' => 'NOK'
        ];
    }

    public function deleteDiscount(Request $request, $id)
    {
        $booking = Booking::find($request->get('booking_id'));
        $user = $booking->user;
        $discount = true;
        $result['success'] = 'NOK';
        $booking_discount = BookingDiscount::find($id);
        $discharge = false;

        if ($booking_discount != null) {
            if ($booking_discount->delete()) {
                $discharge = true;

                $result['html'] = view('laralum.booking.get-booking-info', compact('booking', 'discount', 'user', 'discharge'));
                $result['success'] = 'OK';
            }
        }

        return $result;
    }

    public function payDueAmount($id, $discharge = false)
    {
        $booking = Booking::find($id);

        if (!$booking->isEditable()) {
            abort(401, "You don't have permissions to access this area");
        }
        $user = $booking->user;
        $pay = true;

        return view('laralum.booking.get-booking-info', compact('user', 'pay', 'booking', 'discharge'));
    }

    public function payDueAmountStore(Request $request, $id)
    {
        $method = $request->get('payment_method');
        $type = $request->get('type');
        $booking = Booking::find($id);

        if ($type == Wallet::TYPE_REFUND) {
            $paid_amount = $booking->getPaidAmount();
            $amt = $request->get('amount');
            if ($paid_amount < $amt) {
                return ['success' => 'NOK'];
            }
        }

        $wallet = Wallet::create([
            'user_id' => $request->get('user_id'),
            'amount' => $request->get('amount'),
            'type' => $request->get('type'),
            'created_by' => \Auth::user()->id,
            'status' => Wallet::STATUS_PAID,
            'payment_method' => $request->get('payment_method'),
            'booking_id' => $request->get('booking_id'),
            'description' => $request->get('description'),
        ]);

        return ['success' => 'OK'];
    }

    public function getFeedbackForm($id)
    {
        $booking = Booking::find($id);
        $user = $booking->user;
        $feedback = Feedback::where('booking_id', $id)->orderBy("created_at", "DESC")->first();
        if ($feedback == null) {
            $feedback = new Feedback();
        }
        // $data['user_info'] = $user_info;
        return view('laralum.booking.feedback-form', compact('user', 'feedback', 'booking'));
    }

    public function submitFeedbackForm(Request $request, $id)
    {
        $booking = Booking::find($id);
        $user = $booking->user;
        if ($request->get('feedback') != null) {
            /*$feedback = Feedback::where('user_id', $id)->first();
            if ($feedback == null) {*/
            $feedback = new Feedback();
            //}
            $question_ans = [];
            foreach (FeedbackQuestion::all() as $question) {
                $question_ans[$question->id] = $request->get('rate_' . $question->id);;
            }
            $feedback->question_id = implode(",", array_keys($question_ans));
            $feedback->rate = implode(",", array_values($question_ans));
            $feedback->feedback = $request->get('feedback');
            $feedback->user_id = $user->id;
            $feedback->booking_id = $booking->id;
            $feedback->save();
        }
        return 'success';
        // $data['user_info'] = $user_info;
    }

    public function getNoc($id)
    {
        //return "hsdgsagdshadhajghdasjghdjasgh";
        $booking = Booking::find($id);
        $user = $booking->user;

        $noc = true;
        $feedback = Feedback::where('user_id', $user->id)->where('booking_id', $booking->id)->orderBy('created_at', 'DESC')->first();
        $error = false;
        if ($feedback == null) {
            $error = true;
        }
        $discharge = true;
        // $data['user_info'] = $user_info;
        return view('laralum.booking.get-booking-info', compact('user', 'noc', 'error', 'booking', 'discharge'));
    }

    public function followups(Request $request)
    {
        $matchThese = [];
        $search = false;
        $option_ar = [];
        if ($request->has('filter_patient_id') && $request->get('filter_patient_id') != "") {
            $option_ar[] = "Patient Id";
            $search = true;
            $matchThese['kid'] = $request->get('filter_patient_id');
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
        $options = implode(", ", $option_ar);

        $error = "Entered " . $options . " is not valid,
make sure that you are entering valid " . $options . " 
or search by other options";
        $user = [];
        $per_page = $request->get("per_page") ? $request->get("per_page") : 10;
        $pagination = true;
        if ($per_page == "All") {
            $pagination = false;
        }
        $count = 0;
        if ($search == true) {
            $date = (string)date("Y-m-d");
            $followups = PatientFollowUp::select('patient_followups.*')
                ->join('discharge_patients', 'patient_followups.patient_id', '=', 'discharge_patients.id')
                ->join('user_profiles', 'user_profiles.user_id', '=', 'discharge_patients.patient_id')->join('role_user', 'role_user.user_id', 'discharge_patients.patient_id')->where('role_user.role_id', Role::getPatientId())->where(function ($query) use ($matchThese, $filter_email) {
                    foreach ($matchThese as $key => $match) {
                        $query->where('user_profiles.' . $key, 'like', "%$match%");
                    }
                    if ($filter_email != "") {
                        $query->where('users.email', 'like', "%$filter_email%");
                    }
                })->join('role_user', 'role_user.user_id', 'users.id')->where('role_user.role_id', Role::getPatientId())->where('patient_followups.followup_date', '>=', $date)->orderBY('patient_followups.followup_date', 'ASC');
            if ($pagination == true) {
                $count = $followups->count();
                $followups = $followups->paginate($per_page);
            } else {
                $count = $followups->count();
                $followups = $followups->get();
            }


        } else {
            $date = (string)date("Y-m-d");
            $followups = PatientFollowUp::select('patient_follow_ups.*')
                ->join('discharge_patients', 'patient_follow_ups.patient_id', '=', 'discharge_patients.id')
                ->join('user_profiles', 'user_profiles.user_id', '=', 'discharge_patients.patient_id')->join('role_user', 'role_user.user_id', 'discharge_patients.patient_id')->where('role_user.role_id', Role::getPatientId())->where('patient_follow_ups.followup_date', '>=', $date)->orderBY('patient_follow_ups.followup_date', 'ASC');
            if ($pagination == true) {
                $count = $followups->count();
                $followups = $followups->paginate($per_page);
            } else {
                $count = $followups->count();
                $followups = $followups->get();
            }
        }

        return view('laralum.booking.followups', compact('followups', 'search', 'error', 'count'));
    }

    public function generateBill(Request $request, $id = null) {
        $booking = Booking::find($id);
        $data = [];
        $data['back'] = 'discharge';
        $data['booking'] = $booking;
        try {
            \DB::beginTransaction();

            if ($booking->patient_type == Booking::PATIENT_TYPE_IPD) {
                $pending_amount = $booking->getPendingAmountWithoutBill();
                if ($pending_amount > 0) {
                    return redirect()->back()->with('error', 'You are going to generate bill for IPD patient please clear all dues before proceeding.');   
                }
            }

            $bill = $booking->generateBill();   

		    \DB::commit();
            $data['bill'] = $bill;
            return view('laralum.booking.print-generated-bill', $data);

        } catch (\Exception $e) {
            Log::error("Failed to send account activation mail, possible causes: " . $e->getMessage());
        }

        return redirect()->back()->with('error', 'Something went wrong!!!');       
    }

    public function printBill(Request $request, $id = null)
    {
        //return $id;
        //return "nvshgdhsdsjaghdjhsgdj";
        $data = [];
        $data['back'] = 'discharge';
        $booking = Booking::find($id);
        $discharge_patient = DischargePatient::where([
            'booking_id' => $booking->id,
            'status' => DischargePatient::STATUS_PENDING
        ])->first();
        if ($discharge_patient != null) {

            $data['discharge_patient'] = $discharge_patient;
            if ($id != null) {
                $user = $booking->user;
            }
            $data['user'] = $user;
            $data['booking'] = $booking;

            if ($request->get('type') == Booking::PRINT_NOC) {
                $booking->discharge();
                $d = new DNS1D();
                $twod = new DNS2D();
                $barcode = null;
                $qrcode = null;

                if ($user != null) {
                    $user->update([
                        'is_discharged' => User::DISCHARGED
                    ]);
                    $data['barcode'] = $d->getBarcodePNG($user->userProfile->kid, "C39+");
                    $data['qrcode'] = $twod->getBarcodePNG($user->userProfile->kid, "QRCODE");
                }

                return view('laralum.booking.print-noc', $data);
            }
            if($request->get('generate_bill') == 1) {
               $bill = $booking->generateBill();
               $data['bill'] = $bill;
               return view('laralum.booking.print-generated-bill', $data);
            }

            return view('laralum.booking.print-bill', $data);
        }

        return redirect()->back()->with('error', 'Something went wrong!!!');
    }

    public function getDietPrices($id)
    {/*
        $user = User::find($id);
        $booking = $user->getbooking();*/
        $booking = Booking::find($id);
        $user = $booking->user;
        $diet = true;
        $diets = DietChart::where('booking_id', $id)->get();
        $error = false;
        if (count($diets) > 0) {
            $error = true;
        }
        $discharge = true;
        // $data['user_info'] = $user_info;
        return view('laralum.booking.get-booking-info', compact('user', 'diet', 'diets', 'error', 'booking', 'discharge'));

    }

    public function getDailyDietDetails($id)
    {
        $daily_diet = DietDailyStatus::find($id);
        $html = "";
        if ($daily_diet != null) {
            $html = "<table>";
            foreach (DietChartItems::getTypeOptions() as $type_id => $type) {
                if ($daily_diet->checkType($type_id)) {
                    $items = DietChartItems::where([
                        'diet_id' => $daily_diet->diet_id,
                        'type_id' => $type_id
                    ])->get();
                    $items_html = "";
                    foreach ($items as $item) {
                        $price = $item->item_price;
                        if ($type_id == DietChartItems::TYPE_BREAKFAST) {
                            if ($daily_diet->is_breakfast == 0) {
                                $price = 0;
                            }
                        } elseif ($type_id == DietChartItems::TYPE_LUNCH) {
                            if ($daily_diet->is_lunch == 0) {
                                $price = 0;
                            }
                        } elseif ($type_id == DietChartItems::TYPE_POST_LUNCH) {
                            if ($daily_diet->is_post_lunch == 0) {
                                $price = 0;
                            }
                        } elseif ($type_id == DietChartItems::TYPE_DINNER) {
                            if ($daily_diet->is_dinner == 0) {
                                $price = 0;
                            }
                        } elseif ($type_id == DietChartItems::TYPE_SPECIAL) {
                            if ($daily_diet->is_special == 0) {
                                $price = 0;
                            }
                        }

                        $items_html .= $item->item->name . "  => " . $price . "<br/>";
                    }

                    $html .= "<tr><th>" . $type . "</th><td>" . $items_html . "</td></tr>";
                }
            }
            $html .= "</table>";
        }
        return $html;
    }

    public function getTreatmentDetails($page,$id)
    {
        //return "here";
        //return $page;
        $booking = Booking::find($id);
        $user = $booking->user;
        $discharge = false;
        if($page === 'discharge'){
            $treatments = $booking->getTreatmentsWithoutBill();
            $discharge = true;
        }else{
            $treatments = $booking->getTreatments();
        }
        return view('laralum.booking.get-booking-info', compact('treatments', 'user', 'booking', 'discharge','page'));
    }

    public function getTreatmentDetailsPrint($page,$id){
        $booking = Booking::find($id);
        $user = $booking->user;
        $discharge = false;
        if($page === 'discharge'){
            $treatments = $booking->getTreatmentsWithoutBill();
            $discharge = true;
        }else{
            $treatments = $booking->getTreatments();
        }
        return view('laralum.booking.print-treatment-details', compact('booking', 'user', 'treatments', 'discharge','page'));
        //return "yo";
    }

    public function checkTreatmentStatus($id){
        //return "yo";
        $booking = Booking::find($id);
        return $booking->getTreatmentsWithoutBill();

    }

    public function getLabDetails($id, $discharge = false)
    {
        $booking = Booking::find($id);
        /*  if (!$booking->isAllowed()) {
              abort(401, "You don't have permissions to access this area");
          }*/
        $user = $booking->user;/*
        $user = User::find($id);*/
        // $lab_tests = $booking->labTests;

        $lab_tests = PatientLabTest::where('booking_id', $booking->id)->doesntHave('bill')->where('created_at', '<=', date('Y-m-d H:i:s'))->get();
      
        return view('laralum.booking.get-booking-info', compact('treatments', 'user', 'booking', 'lab_tests', 'discharge'));
    }

    public function  getallLabDetails($page,$booking_id){
        $booking = Booking::find($booking_id);
        $user = $booking->user;
       
        $discharge = false;
        if($page === 'discharge'){
            $discharge = true;
            $all_lab_tests = PatientLabTest::where('booking_id', $booking->id)->doesntHave('bill')->where('created_at', '<=', date('Y-m-d H:i:s'))->get();      
        }else{
            $all_lab_tests = $booking->labTests;
        }
        return view('laralum.booking.get-booking-info', compact('user', 'booking', 'all_lab_tests', 'discharge', 'page'));
    }

    public function getLabDetailsPrint($page,$booking_id){
        $booking = Booking::find($booking_id);
        $user = $booking->user;
        $discharge = false;
        if($page === 'discharge'){
            $discharge = true;
            $all_lab_tests = PatientLabTest::where('booking_id', $booking->id)->doesntHave('bill')->where('created_at', '<=', date('Y-m-d H:i:s'))->get();      
        }else{
            $all_lab_tests = $booking->labTests;
        }
        return view('laralum.booking.print-lab-details', compact('user', 'booking', 'all_lab_tests', 'discharge', 'page'));
    }

    public function exportTokens(Request $request, $type, $per_page = 10, $page = 1)
    {
        $per_page = $request->get('per_page') ? $request->get('per_page') : 10;
        $pagination = true;
        if ($per_page == "All") {
            $pagination = false;
        }

        $models = PatientToken::where(\DB::raw('date(start_date)'), ">=", date("Y-m-d"))->where(\DB::raw('date(end_date)'), "<=", date("Y-m-d", strtotime("+24 hours")));

        if ($pagination == true) {
            $count = $models->count();
            $models = $models->paginate($per_page);
        } else {
            $count = $models->count();
            $models = $models->get();
        }

        $token_array[] = [
            'Token Number',
            'Name of the Patient',
            'Patient Id',
            'Department',
            'Doctor',
            'Start Date',
            'Expiry Date',
        ];
        foreach ($models as $row) {
            $token_array[] = [
                $row->token_no,
                $row->booking->getProfile('first_name') . ' ' . $row->booking->getProfile('last_name'),
                $row->booking->getProfile('kid'),
                $row->department->title,
                $row->doctor->name,
                $row->start_date,
                $row->end_date,
            ];
        }

        // Generate and return the spreadsheet
        $excel = \App::make('excel');
        $excel = $excel->create('Tokens', function ($excel) use ($token_array) {

            // Set the spreadsheet title, creator, and description
            $excel->setTitle('Patient Tokens');
            $excel->setCreator('Laravel')->setCompany('WJ Gilmore, LLC');
            $excel->setDescription('Bookings file');

            // Build the spreadsheet, passing in the payments array
            $excel->sheet('sheet1', function ($sheet) use ($token_array) {
                $sheet->fromArray($token_array, null, 'A1', false, false);
            });

        });
        if ($type == Settings::EXPORT_CSV) {
            $excel->download('csv');
        } elseif ($type == Settings::EXPORT_EXCEL) {
            $excel->download('xls');
        } else {
            $pdf = PDF::loadView('booking.pdf', array('data' => $bookings_array));
            return $pdf->download();
        }

        return redirect()->back()->with('error', 'Something went Wrong!!!');
    }

    public function exportArchived(Request $request, $type, $per_page = 10, $page = 1)
    {
        $matchThese = [];
        $bookingmatchThese = [];
        $usermatchThese = [];
        $matchTheseAddress = [];

        $search = false;
        $option_ar = [];
        if ($request->s && $request->s != 'null') {
            $search_data = json_decode($request->s, true);

            if (!empty($search_data['kid'])) {
                $option_ar[] = "Registration Id";
                $search = true;
                $matchThese['kid'] = $search_data['kid'];
            }

            if (!empty($search_data['uhid'])) {
                $option_ar[] = "UHID";
                $search = true;
                $usermatchThese['uhid'] = $search_data['uhid'];
            }


            if (!empty($search_data['booking_id'])) {
                $option_ar[] = "Booking Id";
                $search = true;
                $bookingmatchThese['booking_id'] = $search_data['booking_id'];
            }

            $filter_name = '';
            if (!empty($search_data['first_name'])) {
                $option_ar[] = "Name";
                $search = true;
                $filter_name = $search_data['first_name'];
            }

            $search_string = '';
            if (!empty($search_data['city'])) {
                $option_ar[] = "City";
                $search = true;
                $search_string = $search_data['city'];
                $array = explode(',', $search_data['city']);

                $matchTheseAddress['city'] = $array[0];
                if (isset($array[1])) {
                    $matchTheseAddress['state'] = $array[1];
                }

                if (isset($array[2])) {
                    $matchTheseAddress['country'] = $array[2];
                }
            }

            if (!empty($search_data['mobile'])) {
                $option_ar[] = "Mobile";
                $search = true;
                $matchThese['mobile'] = $search_data['mobile'];
            }

            if (!empty($search_data['patient_type'])) {
                $option_ar[] = "Patient Type";
                $search = true;
                $bookingmatchThese['patient_type'] = $search_data['patient_type'];
            }
            $acm_status = "";
            if (!empty($search_data['accommodation_status'])) {
                $option_ar[] = "Accommodation Status";
                $search = true;
                $bookingmatchThese['accommodation_status'] = $search_data['accommodation_status'];
                $acm_status = $search_data['accommodation_status'];
            }
            $booking_status = "";
            if (!empty($search_data['status'])) {
                $option_ar[] = "Status";
                $search = true;
                $bookingmatchThese['status'] = $search_data['status'];
                $booking_status = $search_data['status'];
            }

            if (!empty($search_data['email'])) {
                $option_ar[] = "Email";
                $search = true;
                $usermatchThese['email'] = $search_data['email'];
            }

            if (!empty($search_data['state'])) {
                $option_ar[] = "state";
                $search = true;
                $matchTheseAddress['state'] = $search_data['state'];
            }

            if (!empty($search_data['country'])) {
                $option_ar[] = "Country";
                $search = true;
                $matchTheseAddress['country'] = $search_data['country'];
            }
        }

        $options = implode(", ", $option_ar);

        $error = "Entered " . $options . " is not valid,
        make sure that you are entering valid " . $options . " 
        or search by other options";
        $user = [];
        $per_page = $request->get("per_page") ? $request->get("per_page") : 10;
        $pagination = true;
        if ($per_page == "All") {
            $pagination = false;
        }

        $models_query = Booking::select('bookings.*')->leftjoin('users', 'users.id', '=', 'bookings.user_id')->leftjoin('user_profiles', 'user_profiles.id', '=', 'bookings.profile_id')->leftjoin('user_addresses', 'user_addresses.profile_id', '=', 'user_profiles.id')->orderBY('users.created_at', 'DESC')
            ->whereIn('bookings.status', [Booking::STATUS_DISCHARGED, Booking::STATUS_CANCELLED]);

        $malefemale_query = clone $models_query;
        $models = clone $models_query;


        $males = $models_query->where('user_profiles.gender', UserProfile::GENDER_MALE)->count();
        // print_r($males);exit;
        $females = $malefemale_query->where('user_profiles.gender', UserProfile::GENDER_FEMALE)->count();

        if ($search == true) {
            $models_query = Booking::select('bookings.*')->leftjoin('users', 'users.id', '=', 'bookings.user_id')->leftjoin('user_profiles', 'user_profiles.id', '=', 'bookings.profile_id')->leftjoin('user_addresses', 'user_addresses.profile_id', '=', 'bookings.profile_id')->where(function ($query) use ($matchThese, $bookingmatchThese, $usermatchThese, $matchTheseAddress, $filter_name) {
                foreach ($matchTheseAddress as $key => $match) {
                    $query->where('user_addresses.' . $key, 'like', "%" . $match . "%");
                }
                foreach ($matchThese as $key => $match) {
                    $query->where('user_profiles.' . $key, 'like', "%" . $match . "%");
                }
                foreach ($bookingmatchThese as $key => $match) {
                    $query->where('bookings.' . $key, 'like', "%" . $match . "%");

                }
                foreach ($usermatchThese as $key => $match) {
                    $query->where('users.' . $key, 'like', "%" . $match . "%");
                }

                if ($filter_name != "") {
                    $query->WhereRaw("concat(user_profiles.first_name, ' ', user_profiles.last_name) like '%$filter_name%' ");
                }

            })->orderBY('users.created_at', 'DESC')->whereIn('bookings.status', [Booking::STATUS_DISCHARGED, Booking::STATUS_CANCELLED]);
            if ($search_string != "") {
                $matchTheseAddress['city'] = $search_string;
            }


            if (!empty($search_data['first_name'])) {
                $matchThese['first_name'] = $search_data['first_name'];
            }

            $malefemale_query = clone $models_query;
            $models = clone $models_query;


            $males = $models_query->where('user_profiles.gender', UserProfile::GENDER_MALE)->count();
            // print_r($males);exit;
            $females = $malefemale_query->where('user_profiles.gender', UserProfile::GENDER_FEMALE)->count();
        }

        $models = $models->groupBy('bookings.id');

        $per_page = $request->get("per_page") ? $request->get("per_page") : 10;
        $pagination = true;
        if ($per_page == "All") {
            $pagination = false;
        }
        $count = $models->count();
        if ($pagination == true) {
            $models = $models->paginate($per_page);
        } else {
            $models = $models->get();
        }

        $options = implode(", ", $option_ar);

        $error = "Entered " . $options . " is not valid,
          make sure that you are entering valid " . $options . " 
          or search by other options";
        $archived = true;

        $bookings_array[] = [
            'UHID',
            //'Registration Id',
            'Booking Id',
            'Type',
            'Name of the Person',
            'Email ID',
            'Contact No. ',
            'City, State, Country',
            'Created On',
            'Booking Status',
            'Accommodation Status'
        ];

        foreach ($models as $booking) {
            $status = $booking->status != null ? Booking::getStatusOptions($booking->status) : Booking::getStatusOptions(Booking::STATUS_PENDING);
            $bookings_array[] = [
                $booking->getProfile('uhid'),
                //$booking->getProfile('kid'),
                $booking->booking_id,
                $booking->patient_type != null ? $booking->getPatientType($booking->patient_type) : "OPD",
                $booking->getProfile('first_name') . ' ' . $booking->getProfile('last_name'),
                isset($booking->user->email) ? $booking->user->email : "",
                $booking->getProfile('mobile') ? $booking->getProfile('mobile') : "",
                $booking->getAddress('city') ? $booking->getAddress('city') . ',' . $booking->getAddress('state') . ',' . $booking->getAddress('country') : "",
                $booking->created_at,
                $status,
                $booking->accommodationStatus()
            ];
        }


        // Generate and return the spreadsheet
        $excel = \App::make('excel');
        $excel = $excel->create('Bookings', function ($excel) use ($bookings_array) {

            // Set the spreadsheet title, creator, and description
            $excel->setTitle('Archived Bookings');

            // Build the spreadsheet, passing in the payments array
            $excel->sheet('sheet1', function ($sheet) use ($bookings_array) {
                $sheet->fromArray($bookings_array, null, 'A1', false, false);
            });

        });
        if ($type == Settings::EXPORT_CSV) {
            $excel->download('csv');
        } elseif ($type == Settings::EXPORT_EXCEL) {
            $excel->download('xls');
        } else {
            // $excel->download('pdf');
            $pdf = PDF::loadView('booking.pdf', array('data' => $bookings_array));
            return $pdf->download('archived_patients.pdf');
        }

        return redirect()->back()->with('error', 'Something went Wrong!!!');
    }

    public function export(Request $request, $type, $per_page = 10, $page = 1)
    {
        Laralum::permissionToAccess(['admin.patients.list', 'doctor.patients', 'admin.bookings.list']);

        $matchThese = [];
        $bookingmatchThese = [];
        $usermatchThese = [];
        $matchTheseAddress = [];

        $search = false;
        $option_ar = [];

        if ($request->s && $request->s != 'null') {
            $search_data = json_decode($request->s, true);

            if (!empty($search_data['kid'])) {
                $option_ar[] = "Patient Id";
                $search = true;
                $matchThese['kid'] = $search_data['kid'];
            }

            if (!empty($search_data['uhid'])) {
                $option_ar[] = "UHID";
                $search = true;
                $usermatchThese['uhid'] = $search_data['uhid'];
            }

            if (!empty($search_data['booking_id'])) {
                $option_ar[] = "Booking Id";
                $search = true;
                $bookingmatchThese['booking_id'] = $search_data['booking_id'];
            }

            $filter_name = '';

            if (!empty($search_data['first_name'])) {
                $option_ar[] = "Name";
                $search = true;
                $filter_name =  trim($search_data['first_name']);
            }

            $address_string = '';
            if (!empty($search_data['city'])) {
                $option_ar[] = "City";
                $search = true;
                $address_array = explode(',', $search_data['city']);
                $address_string = $search_data['city'];
                $matchTheseAddress['city'] = $address_array[0];
                if (isset($address_array[1])) {
                    $matchTheseAddress['state'] = $address_array[1];
                }

                if (isset($address_array[2])) {
                    $matchTheseAddress['country'] = $address_array[2];
                }
            }

            if (!empty($search_data['state'])) {
                $option_ar[] = "State";
                $search = true;
                $matchTheseAddress['state'] = $search_data['state'];
            }

            if (!empty($search_data['country'])) {
                $option_ar[] = "Country";
                $search = true;
                $matchTheseAddress['country'] = $search_data['country'];
            }

            if (!empty($search_data['mobile'])) {
                $option_ar[] = "Mobile";
                $search = true;
                $matchThese['mobile'] = $search_data['mobile'];
            }


            if (!empty($search_data['patient_type'])) {
                $option_ar[] = "Patient Type";
                $search = true;
                $bookingmatchThese['patient_type'] = $search_data['patient_type'];
            }
            $acm_status = "";
            if (!empty($search_data['accommodation_status'])) {
                $option_ar[] = "Accommodation Status";
                $search = true;
                $bookingmatchThese['accommodation_status'] = $search_data['accommodation_status'];
                $acm_status = $search_data['accommodation_status'];
            }
            $booking_status = "";
            if (!empty($search_data['status'])) {
                $option_ar[] = "Status";
                $search = true;
                $bookingmatchThese['status'] = $search_data['status'];
                $booking_status = $search_data['status'];
            }

            if (!empty($search_data['email'])) {
                $option_ar[] = "Email";
                $search = true;
                $usermatchThese['email'] = $search_data['email'];
            }
        }

        $options = implode(", ", $option_ar);

        $error = "Entered " . $options . " is not valid,
make sure that you are entering valid " . $options . " 
or search by other options";
        $user = [];
        $per_page = $request->get("per_page") ? $request->get("per_page") : 10;
        $pagination = true;
        if ($per_page == "All") {
            $pagination = false;
        }

        $models_query = Booking::select('bookings.*')->join('users', 'users.id', '=', 'bookings.user_id')->join('user_profiles', 'user_profiles.id', 'bookings.profile_id')->orderBy('bookings.created_at', 'DESC');

        if (\Auth::user()->isDoctor()) {
            $models_query = $models_query->whereIn('status', [Booking::STATUS_COMPLETED]);
        } else {
            $models_query = $models_query->whereIn('status', [Booking::STATUS_COMPLETED, Booking::STATUS_PENDING]);
        }
        $models_query = $models_query->where('bookings.patient_type', Booking::PATIENT_TYPE_OPD);

        $malefemale_query = clone $models_query;
        $models = clone $models_query;


        $males = $models_query->where('user_profiles.gender', UserProfile::GENDER_MALE)->count();
        // print_r($males);exit;
        $females = $malefemale_query->where('user_profiles.gender', UserProfile::GENDER_FEMALE)->count();
        //  print_r($models->count());exit;


        if ($search == true) {
            $models = Booking::select('bookings.*')->join('users', 'users.id', '=', 'bookings.user_id')->join('user_profiles', 'user_profiles.id', 'bookings.profile_id')
                ->leftJoin('user_addresses', 'user_addresses.profile_id', 'bookings.profile_id')
                ->where(function ($query) use ($matchThese, $bookingmatchThese, $usermatchThese, $matchTheseAddress, $filter_name) {
                    foreach ($matchTheseAddress as $key => $match) {
                        $query->where('user_addresses.' . $key, 'like', "%$match%");
                    }
                    foreach ($matchThese as $key => $match) {
                        $query->where('user_profiles.' . $key, 'like', "%$match%");
                    }
                    foreach ($bookingmatchThese as $key => $match) {
                        $query->where('bookings.' . $key, 'like', "%$match%");
                    }
                    foreach ($usermatchThese as $key => $match) {
                        $query->where('users.' . $key, 'like', "%$match%");
                    }
                    if ($filter_name != "") {
                        $query->WhereRaw("concat(user_profiles.first_name, ' ', user_profiles.last_name) like '%$filter_name%' ");
                    }
                })
                ->orderBy('bookings.created_at', 'DESC');
            if ($filter_name != "") {
                $matchThese['first_name'] = $search_data['first_name'];
            }

            if ($address_string != "") {
                $matchTheseAddress['city'] = $address_string;
            }


            if (\Auth::user()->isDoctor()) {
                $models = $models->whereIn('status', [Booking::STATUS_COMPLETED]);

                if ($booking_status != "") {
                    $models = $models->where('status', $booking_status);
                }
            } else {
                $models = $models->whereIn('status', [Booking::STATUS_COMPLETED, Booking::STATUS_PENDING]);

                if ($booking_status != "") {
                    $models = $models->where('status', $booking_status);
                }
            }

            $models = $models->where('user_profiles.patient_type', UserProfile::PATIENT_TYPE_OPD);

            if ($acm_status != "") {
                $models = $models->where('accommodation_status', $acm_status);
            }

            if ($acm_status != "") {
                $models = $models->where('accommodation_status', $acm_status);
            }
            $count = $models->count();
            $models = $models->distinct()->get();
            //print_r($models);
        } else {
            if ($pagination == true) {
                $count = $models->count();
                $models = $models->paginate($per_page);
            } else {
                $count = $models->count();
                $models = $models->distinct()->get();
            }
        }

        if (isset($matchThese['first_name'])) {
            $matchThese['first_name'] = $matchThese['first_name'] . ' ' . $matchThese['last_name'];
        }

        $bookings_array[] = [
            'UHID',
            'Registration Id',
            'Booking Id',
            'Patient Name',
            'Contact No. ',
            'City, State, Country',
            'Booking Status',
        ];

        foreach ($models as $booking) {
            $status = $booking->status != null ? Booking::getStatusOptions($booking->status) : Booking::getStatusOptions(Booking::STATUS_PENDING);
            $bookings_array[] = [
                $booking->getProfile('uhid'),
                $booking->getProfile('kid'),
                $booking->booking_id,
                $booking->getProfile('first_name') . ' ' . $booking->getProfile('last_name'),
                $booking->getProfile('mobile') ? $booking->getProfile('mobile') : "",
                $booking->getAddress('city') ? $booking->getAddress('city') . ',' . $booking->getAddress('state') . ',' . $booking->getAddress('country') : "",
                $status
            ];
        }


        //return $bookings_array;

        // Generate and return the spreadsheet
        $excel = \App::make('excel');
        $excel = $excel->create('Bookings', function ($excel) use ($bookings_array) {

            // Set the spreadsheet title, creator, and description
            $excel->setTitle('Bookings Patients');
            $excel->setCreator('Laravel')->setCompany('WJ Gilmore, LLC');
            $excel->setDescription('Bookings file');

            // Build the spreadsheet, passing in the payments array
            $excel->sheet('sheet1', function ($sheet) use ($bookings_array) {
                $sheet->fromArray($bookings_array, null, 'A1', false, false);
            });

        });
        if ($type == Settings::EXPORT_CSV) {
            $excel->download('csv');
        } elseif ($type == Settings::EXPORT_EXCEL) {
            $excel->download('xls');
        } else {
            $pdf = PDF::loadView('booking.pdf', array('data' => $bookings_array));
            return $pdf->download();
            // $pdf->setPaper('A4', 'landscape');
            // $pdf->getMpdf()->AddPage(...);
            // $excel->download('pdf');
        }

        return redirect()->back()->with('error', 'Something went Wrong!!!');
    }

    public function followupsExport(Request $request, $type, $per_page = 10, $page = 1)
    {
        $per_page = $request->get('per_page') ? $request->get('per_page') : 10;
        $pagination = true;
        if ($per_page == "All") {
            $pagination = false;
        }

        $date = (string)date("Y-m-d");
        $followups = PatientFollowUp::select('patient_follow_ups.*')
            ->join('discharge_patients', 'patient_follow_ups.patient_id', '=', 'discharge_patients.id')
            ->join('user_profiles', 'user_profiles.user_id', '=', 'discharge_patients.patient_id')->join('role_user', 'role_user.user_id', 'discharge_patients.patient_id')->where('role_user.role_id', Role::getPatientId())->where('patient_follow_ups.followup_date', '>=', $date)->orderBY('patient_follow_ups.followup_date', 'ASC');
        if ($pagination == true) {
            $count = $followups->count();
            $followups = $followups->paginate($per_page);
        } else {
            $count = $followups->count();
            $followups = $followups->get();
        }

        $followups_array[] = [
            'Patient Id',
            'Follow Up Date',
            'Name of the Person',
            'Email ID',
            'Contact No. ',
            'City, State, Country '
        ];
        foreach ($followups as $followup) {
            if (isset($followup->patient->patient->userProfile->kid)) {
                $followups_array[] = [
                    $followup->patient->patient->userProfile->kid,
                    $followup->patient->patient->name,
                    $followup->patient->patient->email,
                    $followup->patient->patient->userProfile->mobile,
                    $followup->patient->patient->address->city . ',' . $followup->patient->patient->address->state . ',' . $followup->patient->patient->address->country,
                ];
            }
        }

        // Generate and return the spreadsheet
        $excel = \App::make('excel');
        $excel = $excel->create('Followups', function ($excel) use ($followups_array) {

            // Set the spreadsheet title, creator, and description
            $excel->setTitle('Followups Patients');
            $excel->setCreator('Laravel')->setCompany('WJ Gilmore, LLC');
            $excel->setDescription('Followups file');

            // Build the spreadsheet, passing in the payments array
            $excel->sheet('sheet1', function ($sheet) use ($followups_array) {
                $sheet->fromArray($followups_array, null, 'A1', false, false);
            });

        });
        if ($type == Settings::EXPORT_CSV) {
            $excel->download('csv');
        } elseif ($type == Settings::EXPORT_EXCEL) {
            $excel->download('xls');
        } else {
            // $excel->download('pdf');
            $pdf = PDF::loadView('booking.pdf', array('data' => $followups_array));
            return $pdf->download();
        }

        return redirect()->back()->with('error', 'Something went Wrong!!!');
    }

    public function exportIpd(Request $request, $type, $per_page = 10, $page = 1)
    {
        Laralum::permissionToAccess(['admin.patients.list', 'doctor.patients', 'admin.bookings.list']);
        $matchThese = [];
        $bookingmatchThese = [];
        $usermatchThese = [];
        $matchTheseAddress = [];

        $search = false;
        $option_ar = [];

        if ($request->s && $request->s != 'null') {
            $search_data = json_decode($request->s, true);

            if (!empty($search_data['kid'])) {
                $option_ar[] = "Registration Id";
                $search = true;
                $matchThese['kid'] = $search_data['kid'];
            }

            if (!empty($search_data['uhid'])) {
                $option_ar[] = "UHID";
                $search = true;
                $usermatchThese['uhid'] = $search_data['uhid'];
            }
            if (!empty($search_data['booking_id'])) {
                $option_ar[] = "Booking Id";
                $search = true;
                $bookingmatchThese['booking_id'] = $search_data['booking_id'];
            }

            $filter_name = '';
            if (!empty($search_data['first_name'])) {
                $option_ar[] = "Name";
                $search = true;
                $filter_name = $search_data['first_name'];
            }

            $search_string = '';
            if (!empty($search_data['city'])) {
                $option_ar[] = "City";
                $search = true;
                $search_string = $search_data['city'];
                $array = explode(',', $search_data['city']);

                $matchTheseAddress['city'] = $array[0];
                if (isset($array[1])) {
                    $matchTheseAddress['state'] = $array[1];
                }

                if (isset($array[2])) {
                    $matchTheseAddress['country'] = $array[2];
                }
            }

            if (!empty($search_data['state'])) {
                $option_ar[] = "State";
                $search = true;
                $matchTheseAddress['state'] = $search_data['state'];
            }

            if (!empty($search_data['country'])) {
                $option_ar[] = "Country";
                $search = true;
                $matchTheseAddress['country'] = $search_data['country'];
            }

            //print_r($matchTheseAddress);exit;
            if (!empty($search_data['mobile'])) {
                $option_ar[] = "Mobile";
                $search = true;
                $matchThese['mobile'] = $search_data['mobile'];
            }

            if (!empty($search_data['patient_type'])) {
                $option_ar[] = "Patient Type";
                $search = true;
                $bookingmatchThese['patient_type'] = $search_data['patient_type'];
            }

            $acm_status = "";
            if (!empty($search_data['accommodation_status'])) {
                $option_ar[] = "Accommodation Status";
                $search = true;
                $bookingmatchThese['accommodation_status'] = $search_data['accommodation_status'];
                $acm_status = $request->get('accommodation_status');
            }

            $booking_status = "";
            if (!empty($search_data['status'])) {
                $option_ar[] = "Status";
                $search = true;
                $bookingmatchThese['status'] = $search_data['status'];
                $booking_status = $search_data['status'];
            }

            if (!empty($search_data['email'])) {
                $option_ar[] = "Email";
                $search = true;
                $usermatchThese['email'] = $search_data['email'];
            }
        }

        $options = implode(", ", $option_ar);

        $error = "Entered " . $options . " is not valid,
make sure that you are entering valid " . $options . " 
or search by other options";
        $user = [];
        $per_page = $request->get("per_page") ? $request->get("per_page") : 10;
        $pagination = true;
        if ($per_page == "All") {
            $pagination = false;
        }

        $models_query = Booking::select('bookings.*')->join('users', 'users.id', '=', 'bookings.user_id')->join('user_profiles', 'user_profiles.id', 'bookings.profile_id')->orderBy('bookings.created_at', 'DESC')->whereIn('accommodation_status', [Booking::ACCOMMODATION_STATUS_CONFIRMED])->whereIn('status', [Booking::STATUS_COMPLETED, Booking::STATUS_PENDING]);


        $malefemale_query = clone $models_query;

        $models = clone $malefemale_query;

        $males = $models_query->where('user_profiles.gender', UserProfile::GENDER_MALE)->count();
        // print_r($males);exit;
        $females = $malefemale_query->where('user_profiles.gender', UserProfile::GENDER_FEMALE)->count();
        //  print_r($models->count());exit;
//print_r($males);exit;

        if ($search == true) {
            $models_query = Booking::select('bookings.*')->join('users', 'users.id', '=', 'bookings.user_id')->join('user_profiles', 'user_profiles.id', 'bookings.profile_id')->join('user_addresses', 'user_addresses.profile_id', 'bookings.profile_id')->orderBy('bookings.created_at', 'DESC')->whereIn('accommodation_status', [Booking::ACCOMMODATION_STATUS_CONFIRMED])->whereIn('status', [Booking::STATUS_COMPLETED])->where('user_profiles.patient_type', UserProfile::PATIENT_TYPE_IPD)
                ->where(function ($query) use ($matchThese, $bookingmatchThese, $usermatchThese, $matchTheseAddress, $filter_name) {
                    foreach ($matchTheseAddress as $key => $match) {
                        $query->where('user_addresses.' . $key, 'like', "%$match%");
                    }
                    foreach ($matchThese as $key => $match) {
                        $query->where('user_profiles.' . $key, 'like', "%$match%");
                    }
                    foreach ($bookingmatchThese as $key => $match) {
                        $query->where('bookings.' . $key, 'like', "%$match%");
                    }
                    foreach ($usermatchThese as $key => $match) {
                        $query->where('users.' . $key, 'like', "%$match%");
                    }

                    if ($filter_name != "") {
                        $query->WhereRaw("concat(user_profiles.first_name, ' ', user_profiles.last_name) like '%$filter_name%' ");
                    }
                })
                ->orderBy('bookings.created_at', 'DESC');



            $malefemale_query = clone $models_query;

            $models = clone $malefemale_query;

            $males = $models_query->where('user_profiles.gender', UserProfile::GENDER_MALE)->count();
            // print_r($males);exit;
            $females = $malefemale_query->where('user_profiles.gender', UserProfile::GENDER_FEMALE)->count();

            $count = $models->count();
            $models = $models->distinct()->get();
        } else {
            if ($pagination == true) {
                $count = $models->count();
                $models = $models->paginate($per_page);
            } else {
                $count = $models->count();
                $models = $models->distinct()->get();
            }
        }

        $bookings_array[] = [
            'UHID',
            'Registration Id',
            'Booking Id',
            'Type',
            'Name of the Person',
            'Email ID',
            'Contact No. ',
            'City, State, Country',
            'Created On',
            'Booking Status',
            'Accommodation',
            'Status'
        ];

        foreach ($models as $booking) {
            $status = $booking->status != null ? Booking::getStatusOptions($booking->status) : Booking::getStatusOptions(Booking::STATUS_PENDING);
            $bookings_array[] = [
                $booking->getProfile('uhid'),
                $booking->getProfile('kid'),
                $booking->booking_id,
                $booking->patient_type != null ? $booking->getPatientType($booking->patient_type) : "OPD",
                $booking->getProfile('first_name') . ' ' . $booking->getProfile('last_name'),
                isset($booking->user->email) ? $booking->user->email : "",
                $booking->getProfile('mobile') ? $booking->getProfile('mobile') : "",
                $booking->getAddress('city') ? $booking->getAddress('city') . ',' . $booking->getAddress('state') . ',' . $booking->getAddress('country') : "",
                $booking->created_at,
                $status,
                $booking->accommodationStatus()
            ];
        }


        //return $bookings_array;

        // Generate and return the spreadsheet
        $excel = \App::make('excel');
        $excel = $excel->create('IPD_Bookings', function ($excel) use ($bookings_array) {

            // Set the spreadsheet title, creator, and description
            $excel->setTitle('Bookings Patients');
            $excel->setCreator('Laravel')->setCompany('WJ Gilmore, LLC');
            $excel->setDescription('Bookings file');

            // Build the spreadsheet, passing in the payments array
            $excel->sheet('sheet1', function ($sheet) use ($bookings_array) {
                $sheet->fromArray($bookings_array, null, 'A1', false, false);
            });

        });
        if ($type == Settings::EXPORT_CSV) {
            $excel->download('csv');
        } elseif ($type == Settings::EXPORT_EXCEL) {
            $excel->download('xls');
        } else {
            $pdf = PDF::loadView('booking.pdf', array('data' => $bookings_array));
            return $pdf->download();
            // $pdf->setPaper('A4', 'landscape');
            // $pdf->getMpdf()->AddPage(...);
            // $excel->download('pdf');
        }

        return redirect()->back()->with('error', 'Something went Wrong!!!');
    }

    public function exportFuture(Request $request, $type, $per_page = 10, $page = 1)
    {
        Laralum::permissionToAccess(['admin.future_patients_management']);
        $matchThese = [];
        $bookingmatchThese = [];
        $usermatchThese = [];
        $matchTheseAddress = [];
        $others = [];

        $search = false;
        $option_ar = [];

        if ($request->s && $request->s != 'null') {
            $search_data = json_decode($request->s, true);

            /*if (!empty($search_data['kid'])) {
                $option_ar[] = "Patient Id";
                $search = true;
                $matchThese['kid'] = $search_data['kid'];
            }*/
            if (!empty($search_data['uhid'])) {
                $option_ar[] = "UHID";
                $search = true;
                $usermatchThese['uhid'] = $search_data['uhid'];
            }
            if (!empty($search_data['booking_id'])) {
                $option_ar[] = "Booking Id";
                $search = true;
                $bookingmatchThese['booking_id'] = $search_data['booking_id'];
            }

            if (!empty($search_data['name'])) {
                $option_ar[] = "Name";
                $search = true;

                $array = explode(' ', $search_data['name']);

                $matchThese['first_name'] = $array[0];
                $matchThese['last_name'] = '';

                if (isset($array[1])) {
                    $matchThese['last_name'] = $array[1];
                }
            }

            $filter_name = "";
            if (!empty($search_data['first_name'])) {
                $option_ar[] = "Name";
                $search = true;
                $filter_name = $search_data['first_name'];
            }

            $address_string = '';
            if (!empty($search_data['city'])) {
                $option_ar[] = "City";
                $search = true;
                $address_array = explode(',', $search_data['city']);
                $address_string = $search_data['city'];
                $matchTheseAddress['city'] = $address_array[0];
                if (isset($address_array[1])) {
                    $matchTheseAddress['state'] = $address_array[1];
                }

                if (isset($address_array[2])) {
                    $matchTheseAddress['country'] = $address_array[2];
                }
            }

            if (!empty($search_data['state'])) {
                $option_ar[] = "State";
                $search = true;
                $matchTheseAddress['state'] = $search_data['state'];
            }

            if (!empty($search_data['country'])) {
                $option_ar[] = "Country";
                $search = true;
                $matchTheseAddress['country'] = $search_data['country'];
            }

            if (!empty($search_data['mobile'])) {
                $option_ar[] = "Mobile";
                $search = true;
                $matchThese['mobile'] = $search_data['mobile'];
            }

            if (!empty($search_data['patient_type'])) {
                $option_ar[] = "Patient Type";
                $search = true;
                $bookingmatchThese['patient_type'] = $search_data['patient_type'];
            }

            $acm_status = "";
            if (!empty($search_data['accommodation_status'])) {
                $option_ar[] = "Accommodation Status";
                $search = true;
                $bookingmatchThese['accommodation_status'] = $search_data['accommodation_status'];
                $acm_status = $search_data['accommodation_status'];
            }
            $booking_status = "";

            if (!empty($search_data['status'])) {
                $option_ar[] = "Status";
                $search = true;
                $bookingmatchThese['status'] = $search_data['status'];
                $booking_status = $search_data['status'];
            }
            if (!empty($search_data['email'])) {
                $option_ar[] = "Email";
                $search = true;
                $usermatchThese['email'] = $search_data['email'];
            }

            if (!empty($search_data['check_in_date'])) {
                $search = true;
                $check_in_date = date("Y-m-d", strtotime($search_data['check_in_date']));
            }

            if (!empty($search_data['check_out_date'])) {
                $search = true;
                $check_out_date = date("Y-m-d", strtotime($search_data['check_out_date']));
            }
        }

        $options = implode(", ", $option_ar);

        $error = "Entered " . $options . " is not valid,
make sure that you are entering valid " . $options . " 
or search by other options";
        $user = [];
        $per_page = $request->get("per_page") ? $request->get("per_page") : 10;
        $pagination = true;
        if ($per_page == "All") {
            $pagination = false;
        }

        $models_query = Booking::select('bookings.*')->join('users', 'users.id', '=', 'bookings.user_id')->join('user_profiles', 'user_profiles.id', 'bookings.profile_id')->orderBy('bookings.created_at', 'DESC');

        $models_query = $models_query->whereIn('status', [Booking::STATUS_COMPLETED])->where('user_profiles.patient_type', UserProfile::PATIENT_TYPE_IPD)->where(function ($q) {
            $q->orWhereNull('accommodation_status')->orWhere('accommodation_status', Booking::ACCOMMODATION_STATUS_PENDING);
        });

        // echo '<pre>'; print_r($models_query->get());exit;

        $malefemale_query = clone $models_query;

        $models = clone $malefemale_query;

        $males = $models_query->where('user_profiles.gender', UserProfile::GENDER_MALE)->count();
        // print_r($males);exit;
        $females = $malefemale_query->where('user_profiles.gender', UserProfile::GENDER_FEMALE)->count();
        //  print_r($models->count());exit;

        if ($search == true) {
            $models_query = Booking::select('bookings.*')->join('users', 'users.id', '=', 'bookings.user_id')->join('user_profiles', 'user_profiles.id', 'bookings.profile_id')
                ->join('user_addresses', 'user_addresses.profile_id', 'bookings.profile_id')->where(function ($query) use ($matchThese, $bookingmatchThese, $usermatchThese, $matchTheseAddress, $filter_name) {
                    foreach ($matchTheseAddress as $key => $match) {
                        $query->where('user_addresses.' . $key, 'like', "%$match%");
                    }
                    foreach ($matchThese as $key => $match) {
                        $query->where('user_profiles.' . $key, 'like', "%$match%");
                    }
                    foreach ($bookingmatchThese as $key => $match) {
                        $query->where('bookings.' . $key, 'like', "%$match%");
                    }
                    foreach ($usermatchThese as $key => $match) {
                        $query->where('users.' . $key, 'like', "%$match%");
                    }
                    if ($filter_name != "") {
                        $query->WhereRaw("concat(user_profiles.first_name, ' ', user_profiles.last_name) like '%$filter_name%' ");
                    }
                })
                ->orderBy('bookings.created_at', 'DESC');


            if (\Auth::user()->isDoctor()) {
                $models_query = $models_query->whereIn('status', [Booking::STATUS_COMPLETED]);

                if ($booking_status != "") {
                    $models_query = $models_query->where('status', $booking_status);
                }
            } else {
                $models_query = $models_query->whereIn('status', [Booking::STATUS_COMPLETED]);

                if ($booking_status != "") {
                    $models_query = $models_query->where('status', $booking_status);
                }
            }

            $models_query = $models_query->where('user_profiles.patient_type', UserProfile::PATIENT_TYPE_IPD);

            if ($acm_status != "") {
                $models_query = $models_query->where('accommodation_status', $acm_status);
            }

            $models_query = $models_query->whereIn('status', [Booking::STATUS_COMPLETED])->where('user_profiles.patient_type', UserProfile::PATIENT_TYPE_IPD)->whereNotIn('accommodation_status', [Booking::ACCOMMODATION_STATUS_CONFIRMED]);


            if (isset($check_in_date)) {
                $others['check_in_date'] = date('d-m-Y', strtotime($check_in_date));
                $models_query = $models_query->whereDate('check_in_date', $check_in_date);
            }

            if (isset($check_out_date)) {
                $others['check_out_date'] = date('d-m-Y', strtotime($check_out_date));
                $models_query = $models_query->whereDate('check_out_date', $check_out_date);
            }
            //->where('check_in_date', '>', date("Y-m-d H:i:s"));


            $malefemale_query = clone $models_query;

            $models = clone $malefemale_query;

            $males = $models_query->where('user_profiles.gender', UserProfile::GENDER_MALE)->count();
            // print_r($males);exit;
            $females = $malefemale_query->where('user_profiles.gender', UserProfile::GENDER_FEMALE)->count();

            $matchThese['first_name'] = $request->get('first_name');
            $matchTheseAddress['city'] = $address_string;
        }

        if ($pagination == true) {
            $count = $models->count();
            $models = $models->paginate($per_page);
        } else {
            $count = $models->count();
            $models = $models->get();
        }

        $bookings_array[] = [
            'UHID',
            //'Registration Id',
            'Booking Id',
            'Patient Name',
            'Contact No.',
            'City, State, Country',
            'Check In date',
            'Check Out date',
            'Building/Floor',
            'Status',
            'Accommodation',
        ];

        foreach ($models as $booking) {
            $status = $booking->status != null ? Booking::getStatusOptions($booking->status) : Booking::getStatusOptions(Booking::STATUS_PENDING);
            $bookings_array[] = [
                $booking->getProfile('uhid'),
               // $booking->getProfile('kid'),
                $booking->booking_id,
                $booking->getProfile('first_name') . ' ' . $booking->getProfile('last_name'),
                $booking->getProfile('mobile') ? $booking->getProfile('mobile') : "",
                $booking->getAddress('city') ? $booking->getAddress('city') . ',' . $booking->getAddress('state') . ',' . $booking->getAddress('country') : "",
                date("d-m-Y", strtotime($booking->check_in_date)),
                date("d-m-Y", strtotime($booking->check_out_date)),
                $booking->building_name . '/' . $booking->building_floor_name,
                $status,
                $booking->accommodationStatus()
            ];
        }

        // Generate and return the spreadsheet
        $excel = \App::make('excel');
        $excel = $excel->create('Future_Bookings', function ($excel) use ($bookings_array) {
            // Set the spreadsheet title, creator, and description
            $excel->setTitle('Bookings Patients');
            $excel->setCreator('Laravel')->setCompany('WJ Gilmore, LLC');
            $excel->setDescription('Bookings file');

            // Build the spreadsheet, passing in the payments array
            $excel->sheet('sheet1', function ($sheet) use ($bookings_array) {
                $sheet->fromArray($bookings_array, null, 'A1', false, false);
            });

        });
        if ($type == Settings::EXPORT_CSV) {
            $excel->download('csv');
        } elseif ($type == Settings::EXPORT_EXCEL) {
            $excel->download('xls');
        } else {
            $pdf = PDF::loadView('booking.pdf', array('data' => $bookings_array));
            return $pdf->download();
            // $pdf->setPaper('A4', 'landscape');
            // $pdf->getMpdf()->AddPage(...);
            // $excel->download('pdf');
        }

        return redirect()->back()->with('error', 'Something went Wrong!!!');
    }

    /**
     * get all resource listing
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function treatmentTokens(Request $request)
    {
        $matchThese = [];
        $search = false;
        $option_ar = [];
        if ($request->has('kid') && $request->get('kid') != "") {
            $option_ar[] = "Registration Id";
            $search = true;
            $matchThese['kid'] = $request->get('kid');
        }

        $filter_name = "";

        if ($request->has('first_name') && $request->get('first_name') != "") {
            $option_ar[] = "First Name";
            $search = true;
            $filter_name = $request->get('first_name');

            //  $matchThese['first_name'] = $request->get('filter_first_name');
        }
        $dep_id = '';

        if ($request->has('department_id') && $request->get('department_id') != "") {
            $option_ar[] = "Department";
            $search = true;
            $dep_id = $request->get('department_id');
        }

        $t_id = '';

        if ($request->has('treatment_id') && $request->get('treatment_id') != "") {
            $option_ar[] = "Treatment";
            $search = true;
            $t_id = $request->get('treatment_id');
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
        $options = implode(", ", $option_ar);

        $error = "Entered " . $options . " is not valid,
make sure that you are entering valid " . $options . " 
or search by other options";
        $user = [];
        $per_page = $request->get("per_page") ? $request->get("per_page") : 10;
        $pagination = true;
        if ($per_page == "All") {
            $pagination = false;
        }

        if ($search == true) {
            $tokens = TreatmentToken::select('treatment_tokens.*')
                ->join('user_profiles', 'user_profiles.user_id', '=', 'treatment_tokens.patient_id')
                ->join('users', 'treatment_tokens.patient_id', '=', 'users.id')
                ->where(function ($query) use ($dep_id, $matchThese, $filter_email, $filter_name, $t_id) {
                    foreach ($matchThese as $key => $match) {
                        $query->where('user_profiles.' . $key, 'like', "%$match%");
                    }
                    if ($filter_email != "") {
                        $query->where('users.email', 'like', "%$filter_email%");
                    }
                    if ($filter_name != "") {
                        $query->WhereRaw("concat(user_profiles.first_name, ' ', user_profiles.last_name) like '%$filter_name%' ");
                    }
                    if ($dep_id) {
                        $query->where('treatment_tokens.department_id', $dep_id);
                    }
                    if ($t_id) {
                        $query->whereHas('treatments', function ($q) use ($t_id) {
                            $q->where('treatment_id', $t_id);
                        });
                    }
                })->where('treatment_date', (string)date("Y-m-d"));

            $count = $tokens->count();
            $tokens = $tokens->get();
            $matchThese['department_id'] = $request->get('department_id');
            $matchThese['first_name'] = $request->get('first_name');
            $matchThese['treatment_id'] = $t_id;
        } else {
            $tokens = TreatmentToken::select('treatment_tokens.*')->where('treatment_date', (string)date("Y-m-d"));

            $count = $tokens->count();

            if ($pagination == true) {
                $tokens = $tokens->paginate($per_page);
            } else {
                $tokens = $tokens->get();
            }
        }

        if ($request->ajax()) {
            return [
                'html' => view('laralum/booking/_treatment-tokens', ['tokens' => $tokens, 'count' => $count, 'error' => $error, 'search' => $search, 'search_data' => array_merge($matchThese)])->render()
            ];
        }
        return view('laralum.booking.treatment-tokens', compact('tokens', 'search', 'error', 'count'));
    }

    public function printTreatment($id)
    {
        $token = TreatmentToken::find($id);
        $booking = $token->booking;
        $back_url = url('/admin/booking/treatment-tokens');
        $d = new DNS1D();
        $barcode = $d->getBarcodePNG($token->token_no, "C39+");
        return view('laralum.token.print_treatment_token', compact('token', 'barcode', 'back_url', 'booking'));
    }

    public function allotRooms($id)
    {
        $booking = Booking::find($id);
        if (!$booking->isAllowed()) {
            abort(401, "You don't have permissions to access this area");
        }

        /*if (!$booking->isEditable())
            return redirect("admin/booking/" . $id . "/show")->back()->with('error', 'Patient is not active.');
        */

        $user = $booking->user;
        return view('laralum.booking.allot_rooms', compact('booking', 'user'));
    }

    public function accommodationPrint(Request $request, $id)
    {
        $booking = Booking::find($id);
        if (!$booking->isAllowed()) {
            abort(401, "You don't have permissions to access this area");
        }
        /*if (!$booking->isEditable())
            return redirect("admin/booking/" . $id . "/show")->back()->with('error', 'Patient is not active.');*/
        $user = $booking->user;
        return view('laralum.booking.print_allot_rooms', compact('booking', 'user'));
    }

    public function allotRoomForm(Request $request, $id, $m_id = null, $r_id = null)
    {
        $booking = Booking::find($id);
        $member = Member::find($m_id);
        $user = $booking->user;
        $data['user_id'] = $id;
        // call room wise data
        $data['user'] = $user;
        $data['booking'] = $booking;
        $data['m_id'] = $m_id;
        $room_obj = null;
        $data['member'] = $member;
        $gender = $booking->userProfile->getGenderOptions($booking->userProfile->gender);

        $check_in_date = date("Y-m-d", strtotime($booking->check_in_date));
        $check_out_date = date("Y-m-d", strtotime($booking->check_out_date));
        $booking_type = $booking->booking_type;

        $booking_room = new BookingRoom();
        $booking_room->check_in_date = $check_in_date;
        $booking_room->check_out_date = $check_out_date;
        $booking_room->type = $booking_type;

        $data['building'] = $booking->building_id;
        $data['floor'] = $booking->floor_number;

        if ($member != null) {
            $booked_rooms = BookingRoom::where('member_id', $member->id)->whereNull('status')->get();
            $data['booked_rooms'] = $booked_rooms;
            foreach ($booked_rooms as $booked_room) {
                //$booking_room->id = $booked_room->id;
                $data['building'] = $booked_room->room->building_id;
                $data['floor'] = $booked_room->room->floor_number;
                break;
            }

            $gender = $member->getGenderOptions($member->gender);
        } else {
            $booked_rooms = BookingRoom::where('booking_id', $booking->id)->whereNull('member_id')->whereNull('status')->get();
            foreach ($booked_rooms as $booked_room) {
                //$booking_room->id = $booked_room->id;
                $data['building'] = $booked_room->room->building_id;
                $data['floor'] = $booked_room->room->floor_number;
                break;
            }
            $data['booked_rooms'] = $booked_rooms;
        }

        if ($r_id != null) {
            $booking_room = BookingRoom::find($r_id);
            $data['building'] = $booking_room->building_id;
        }

        $data['booking_room'] = $booking_room;
        $data['gender'] = $gender;

        $adminsertting1 = AdminSetting::where('setting_name', 'Child')->first();
        if ($adminsertting1) {
            $child_price = $adminsertting1->price;
        } else {
            $child_price = 0;
        }

        $adminsertting2 = AdminSetting::where('setting_name', 'Driver')->first();
        if ($adminsertting2) {
            $driver_price = $adminsertting2->price;
        } else {
            $driver_price = 0;
        }

        $data['child_price'] = $child_price;
        $data['driver_price'] = $driver_price;
        $array = array();
        $service_child_count = 0;
        $service_driver_count = 0;
        //dd ($data['booked_rooms']);

        if (count($data['booked_rooms']) > 0) {
            $room_id = $data['booked_rooms'][0]['id'];
            $array = array();
            $nowdate = date('Y-m-d');
            $services = UserExtraService::where('booking_id', '=', $room_id)->where('service_end_date', '>', $nowdate)->get();
            if (!empty($services)) {
                //dd($services);
                $service_child_count = 0;
                $service_driver_count = 0;
                foreach ($services as $service) {
                    if ($service->is_child_driver == 1) {
                        $service_child_count = $service_child_count + 1;
                        $data['child_check_in'][] = $service->service_start_date;
                        $data['child_check_out'][] = $service->service_end_date;
                    } elseif ($service->is_child_driver == 2) {
                        $service_driver_count = $service_driver_count + 1;
                        $data['driver_check_in'][] = $service->service_start_date;
                        $data['driver_check_out'][] = $service->service_end_date;
                    }
                    $array[] = $service->is_child_driver;
                }
                $data['service_child_count'] = $service_child_count;
                $data['service_driver_count'] = $service_driver_count;
            }
            $data['service_child_count'] = $service_child_count;
            $data['service_driver_count'] = $service_driver_count;
            $data['services'] = $array;
        } else {
            $data['service_child_count'] = $service_child_count;
            $data['service_driver_count'] = $service_driver_count;
            $data['services'] = $array;
        }
        //dd($data);
        /*if($data['booked_rooms']->count() ==  0){
            return "empty";
        }else{
            return "not empty";
        }*/
        return view('laralum.booking.accommodation-booking-form', $data);
    }


    public function editRoomForm(Request $request, $id)
    {
        $booked_rooms = BookingRoom::find($id);
        $member = Member::find($booked_rooms->member_id);
        $booking = Booking::find($booked_rooms->booking_id);
        $user = $booking->user;
        $data['user_id'] = $user->id;
        // call room wise data
        $data['user'] = $user;
        $data['booking'] = $booking;
        $data['m_id'] = @$member->id;
        $room_obj = null;
        $data['member'] = $member;
        $gender = $booking->userProfile->getGenderOptions($booking->userProfile->gender);

        $data['building'] = $booked_rooms->room->building_id;
        $data['floor'] = $booked_rooms->room->floor_number;

        if ($member != null) {
            $gender = $member->getGenderOptions($member->gender);
        }
        $data['booking_room'] = $booked_rooms;
        $data['booked_rooms'] = $booked_rooms;
        $data['gender'] = $gender;

        $adminsertting1 = AdminSetting::where('setting_name', 'Child')->first();
        if ($adminsertting1) {
            $child_price = $adminsertting1->price;
        } else {
            $child_price = 0;
        }

        $adminsertting2 = AdminSetting::where('setting_name', 'Driver')->first();
        if ($adminsertting2) {
            $driver_price = $adminsertting2->price;
        } else {
            $driver_price = 0;
        }

        $data['child_price'] = $child_price;
        $data['driver_price'] = $driver_price;
        $array = array();
        $service_child_count = 0;
        $service_driver_count = 0;


        $array = array();
        $driver_stay = array();
        $nowdate = date('Y-m-d');
        $services = UserExtraService::where('booking_id', '=', $booked_rooms->id)->get();
        if (!empty($services)) {
            //dd($services);
            $service_child_count = 0;
            $service_driver_count = 0;
            foreach ($services as $service) {
                if ($service->is_child_driver == 1) {
                    $service_child_count = $service_child_count + 1;
                    $data['child_check_in'][] = $service->service_start_date;
                    $data['child_check_out'][] = $service->service_end_date;
                } elseif ($service->is_child_driver == 2) {
                    $service_driver_count = $service_driver_count + 1;
                    $data['driver_check_in'][] = $service->service_start_date;
                    $data['driver_check_out'][] = $service->service_end_date;
                }
                $array[] = $service->is_child_driver;
                if ($service->is_child_driver == 2) {
                    $driver_stay[] = $service->driver_stay;
                }
            }
            $data['service_child_count'] = $service_child_count;
            $data['service_driver_count'] = $service_driver_count;
        }
        $data['service_child_count'] = $service_child_count;
        $data['service_driver_count'] = $service_driver_count;
        $data['services'] = $array;
        $data['driver_stay'] = $driver_stay;
        //dd($data);
        return view('laralum.booking.accommodation-booking-form', $data);
    }

    public function getEditAccomform(Request $request, $booking_id, $id = null)
    {
        $booking_room = new BookingRoom();
        $booking = Booking::find($booking_id);

        $check_in_date = date("Y-m-d", strtotime($booking->check_in_date));
        $check_out_date = date("Y-m-d", strtotime($booking->check_out_date));
        $booking_type = $booking->booking_type;

        $booking_room->check_in_date = $check_in_date;
        $booking_room->check_out_date = $check_out_date;
        $booking_room->type = $booking_type;
        $data['building'] = $booking->building_id;
        $data['floor'] = $booking->floor_number;

        if ($id != null) {
            $booking_room = BookingRoom::find($id);
            $data['building'] = $booking_room->room->building_id;
            $data['floor'] = $booking_room->room->floor_number;
        }

        /*   if (!$booking->isEditable())
               return redirect("admin/booking/" . $id . "/show")->back()->with('error', 'Patient is not active.');*/


        $member = Member::find($booking_room->member_id);
        $user = $booking->user;
        $data['user_id'] = $id;
        // call room wise data
        $data['user'] = $user;
        $data['booking'] = $booking;
        $data['m_id'] = $booking_room->member_id;
        $room_obj = null;
        $data['member'] = $member;
        $gender = $booking->userProfile->getGenderOptions($booking->userProfile->gender);


        /* if ($member != null) {
             $booked_rooms = BookingRoom::where('member_id', $member->id)->orderBy('created_at', 'DESC')->get();
             $data['booked_rooms'] = $booked_rooms;
             foreach ($booked_rooms as $booked_room) {
                 //$booking_room = $booked_room;
                 $data['building'] = $booked_room->room->building_id;
                 $data['floor'] = $booked_room->room->floor_number;
                 break;
             }

             $gender = $member->getGenderOptions($member->gender);
         } else {
             $booked_rooms = BookingRoom::where('booking_id', $booking->id)->whereNull('member_id')->orderBy('created_at', 'DESC')->get();
             foreach ($booked_rooms as $booked_room) {
                 //$booking_room = $booked_room;
                 $data['building'] = $booked_room->room->building_id;
                 $data['floor'] = $booked_room->room->floor_number;
                 break;
             }
             $data['booked_rooms'] = $booked_rooms;
         }*/


        $data['booking_room'] = $booking_room;
        $data['gender'] = $gender;

        $adminsertting1 = AdminSetting::where('setting_name', 'Child')->first();
        if ($adminsertting1) {
            $child_price = $adminsertting1->price;
        } else {
            $child_price = 0;
        }

        $adminsertting2 = AdminSetting::where('setting_name', 'Driver')->first();
        if ($adminsertting2) {
            $driver_price = $adminsertting2->price;
        } else {
            $driver_price = 0;
        }

        $data['child_price'] = $child_price;
        $data['driver_price'] = $driver_price;
        $array = array();
        $service_child_count = 0;
        $service_driver_count = 0;


        $array = array();
        $nowdate = date('Y-m-d');
        $services = UserExtraService::where('booking_id', '=', $booking_room->id)->where('service_end_date', '>', $nowdate)->get();
        if (!empty($services)) {
            //dd($services);
            $service_child_count = 0;
            $service_driver_count = 0;
            foreach ($services as $service) {
                if ($service->is_child_driver == 1) {
                    $service_child_count = $service_child_count + 1;
                    $data['child_check_in'][] = $service->service_start_date;
                    $data['child_check_out'][] = $service->service_end_date;
                } elseif ($service->is_child_driver == 2) {
                    $service_driver_count = $service_driver_count + 1;
                    $data['driver_check_in'][] = $service->service_start_date;
                    $data['driver_check_out'][] = $service->service_end_date;
                }
                $array[] = $service->is_child_driver;
            }
            $data['service_child_count'] = $service_child_count;
            $data['service_driver_count'] = $service_driver_count;
        }
        $data['service_child_count'] = $service_child_count;
        $data['service_driver_count'] = $service_driver_count;
        $data['services'] = $array;

        return [
            'html' => view('laralum.booking._allot_accom_form_partial', $data)->render()];

    }

    public function deleteBookedRoom(Request $request, $id)
    {
        $booked_room = BookingRoom::find($id);

        if ($booked_room) {
            $booked_room->customDelete();
            return [
                'id' => $id,
                'status' => 'success'
            ];
        }

        return [
            'id' => $id,
            'status' => 'error'
        ];
    }

    public function accommBookingForm($user_id, $room_id, $booking_ids = null, $member_id = null)
    {
        /*$user_id = \Session::get('user_id');*/
        $user_info = [];
        $external_services = [];
        if (is_numeric($user_id)) {
            $user_obj = User::find($user_id);
        }
        $external_services_obj = ExternalService::getServices($room_id);
        if ($external_services_obj != null) {
            foreach ($external_services_obj as $ext_service) {
                $external_services[$ext_service->id] = $ext_service->name;
            }
        }
        $room_data = [];
        $room_obj = \DB::table('rooms')
            ->join('room_types', 'rooms.room_type_id', '=', 'room_types.id')
            ->join('buildings', 'rooms.building_id', '=', 'buildings.id')
            ->where('rooms.id', $room_id)
            ->select('rooms.room_number', 'room_types.name as room_type', 'rooms.room_number', 'buildings.name as building_name')
            ->first();
        $room = Room::find($room_id);
        if (!empty($room_obj)) {
            $room_data['room_id'] = $room->id;
            $room_data['room_number'] = $room_obj->room_number;
            $room_data['room_type'] = $room_obj->room_type;
            $room_data['room_number'] = $room_obj->room_number;
            $room_data['room_price'] = $room->roomType->price;
            $room_data['building'] = $room_obj->building_name;
        }
        // partial booking
        if (!empty($booking_ids)) {
            $booking_id_arr = explode('-', $booking_ids);
            $booked_info = Booking::join('rooms', 'rooms.id', '=', 'bookings.room_id')
                ->join('user_profiles', 'bookings.user_id', '=', 'user_profiles.user_id')
                ->whereIN('bookings.id', $booking_id_arr)
                ->select('bookings.*', 'rooms.*', 'rooms.id as room_id', 'bookings.*', 'user_profiles.first_name', 'user_profiles.last_name')
                ->get();

            $data['booked_info'] = $booked_info;
        } else {
            $data['booked_info'] = [];
        }


        $booking_types = $room->getBookingTypes();
        $data['user_obj'] = $user_obj;
        $data['external_services'] = $external_services;
        $data['room_data'] = $room_data;
        $data['booking_types'] = $booking_types;
        $data['check_in_date'] = "";
        $data['check_out_date'] = "";
        $member = Member::find($member_id);

        $data['member'] = $member;
        $data['member_id'] = $member_id;
        $booking = $user_obj->getBooking();

        $data['total_price'] = 0;
        if ($booking != null) {
            $data['check_in_date'] = date('Y-m-d', strtotime($booking->check_in_date));
            $data['check_out_date'] = date('Y-m-d', strtotime($booking->check_out_date));
            $data['total_price'] = $booking->daysPrice($room_id);
        }
        if ($member != null) {
            $data['total_price'] = $member->daysPrice($room_id);
        }

        $adminsertting1 = AdminSetting::where('setting_name', 'Child')->first();
        if ($adminsertting1) {
            $child_price = $adminsertting1->price;
        } else {
            $child_price = 0;
        }

        $adminsertting2 = AdminSetting::where('setting_name', 'Driver')->first();
        if ($adminsertting2) {
            $driver_price = $adminsertting2->price;
        } else {
            $driver_price = 0;
        }

        $data['child_price'] = $child_price;
        $data['driver_price'] = $driver_price;


        if ($member != null) {
            $booked_rooms = BookingRoom::where('member_id', $member->id)->get();
            $data['booked_rooms'] = $booked_rooms;
            foreach ($booked_rooms as $booked_room) {
                //$booking_room = $booked_room;
                $data['building'] = $booked_room->room->building_id;
                $data['floor'] = $booked_room->room->floor_number;
                break;
            }

            $gender = $member->getGenderOptions($member->gender);
        } else {
            $booked_rooms = BookingRoom::where('booking_id', $booking->id)->whereNull('member_id')->get();
            foreach ($booked_rooms as $booked_room) {
                //$booking_room = $booked_room;
                $data['building'] = $booked_room->room->building_id;
                $data['floor'] = $booked_room->room->floor_number;
                break;
            }
            $data['booked_rooms'] = $booked_rooms;
        }


        // return $data['booked_rooms'];
        $array = array();
        if (count($data['booked_rooms']) > 0) {
            $room_id = $data['booked_rooms'][0]['id'];
            $array = array();
            $nowdate = date('Y-m-d');
            $services = UserExtraService::where('booking_id', '=', $room_id)->where('service_end_date', '>', $nowdate)->get();
            if (!empty($services)) {
                foreach ($services as $service) {
                    if ($service->is_child_driver == 1) {
                        $data['child_check_in'] = $service->service_start_date;
                        $data['child_check_out'] = $service->service_end_date;
                    } elseif ($service->is_child_driver == 2) {
                        $data['driver_check_in'] = $service->service_start_date;
                        $data['driver_check_out'] = $service->service_end_date;
                    }
                    $array[] = $service->is_child_driver;
                }
            }
            $data['services'] = $array;
        } else {
            $data['services'] = $array;
        }

        //dd($data);
        //not here
        return view('laralum.booking.accommodation-booking-form', $data);
    }

    public function accommBookingFormStore(Request $request, $id, $room_id = null)
    {
        //dd($request->all());

        $booking = Booking::find($id);

        $booking_room_id = $request->get('booking_room_id');

        $user = $booking->user;

        $member = Member::find($request->get('member_id'));

        \Validator::extend('greater_than', function ($attribute, $value, $parameters, $validator) {
            $min_field = $parameters[0];
            $data = $validator->getData();
            $min_value = $data[$min_field];
            return $value > $min_value;
        });
        \Validator::replacer('greater_than', function ($message, $attribute, $rule, $params) {
            return str_replace('_', ' ', 'The ' . $attribute . ' must be greater than the ' . $params[0]);
        });


        $booking_room_old = BookingRoom::where('booking_id', $booking->id)->orderBy('id', 'desc')->first();
        $booking_room = new BookingRoom();

        /*$rules = array_merge($booking_room->rules(), [
            'check_out_date' => 'required|date_format:d-m-Y|after:check_in_date|after:yesterday',
            'check_in_date' => 'required|date_format:d-m-Y|after:yesterday'
        ]);*/
        $rules = array_merge($booking_room->rules(), [
            'check_out_date' => 'required|date_format:d-m-Y|after:check_in_date',
            'check_in_date' => 'required|date_format:d-m-Y'
        ]);

        $date = date("Y-m-d", strtotime($request->check_in_date));

        /*if ($date < date("Y-m-d")) {
            return redirect()->back()->with('error', "Checkin date must be greater than or equal to today's date");
        }*/


        $validator = \Validator::make($request->all(), $rules);


        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors());
        }


        // die('here');

        if ($booking != null) {
            $room_model = Room::find($request->room_id);
            $booking_id = $booking->id;
            //new code for Kid
            $profile = $booking->userProfile;
           /* if($profile != null){
                if ($profile->patient_type == UserProfile::PATIENT_TYPE_IPD && $profile->kid == null) {
                        $profile->update([
                            'kid' => User::getId("K-IPD", $profile->getIdNumber())
                        ]);
                    } else if ($profile->patient_type == UserProfile::PATIENT_TYPE_OPD && $profile->kid == null) {
                        $profile->update([
                            'kid' => User::getId("K-OPD", $profile->getIdNumber())
                        ]);
                    }
            }*/

            if($profile != null){
                if ($booking->patient_type == Booking::PATIENT_TYPE_IPD && $booking->booking_kid == null) {
                        $kid = User::getId("K-IPD", $booking->getKIdNumber());
                        $booking->update([
                            'booking_kid' => $kid
                        ]);
                        $profile->update([
                            'kid' => $kid
                        ]);
                    } else if ($booking->patient_type == UserProfile::PATIENT_TYPE_OPD && $booking->booking_kid == null) {
                        $kid = User::getId("K-OPD", $booking->getKIdNumber());
                        $booking->update([
                            'booking_kid' => $kid
                        ]);
                    }    
            }


            $member_id = null;
            if ($member == null) {
                $now = date("Y-m-d");
                $is_booked = BookingRoom::where('booking_id', $booking_id)->whereNull('member_id')->orderBy('created_at', 'DESC')->first();

                if ($is_booked) {
                    $checkindate = $is_booked->check_in_date;
                    $checkoutdate = $is_booked->check_out_date;
                    if ($now > $checkindate) {
                        $bookinggg = Booking::where('id', $booking_id)->first();
                        $bookinggg->update([
                            'check_in_date' => date("Y-m-d", strtotime($request->get("check_in_date"))),
                            'check_out_date' => date("Y-m-d", strtotime($request->get("check_out_date"))),
                            'room_id' => $request->get('room_id'),
                            'bed_number' => $request->get('bed_no') ? $request->get('bed_no') : 1,
                            'booking_type' => $request->get('type'),
                            'building_id' => $request->get('building_id'),
                            'floor_number' => $request->get('floor_number'),
                        ]);
                        $re_check_in = date("Y-m-d", strtotime($request->get("check_in_date")));
                        $old_checkout = date("Y-m-d", strtotime($is_booked->check_out_date));

                        if ($old_checkout > $re_check_in) {
                            $is_booked->update([
                                'status' => 0,
                                'check_out_date' => date("Y-m-d", strtotime($request->get("check_in_date")))
                            ]);
                        }

                        $room = new BookingRoom();
                    } else {
                        $bookinggg = Booking::where('id', $booking_id)->first();
                        $bookinggg->update([
                            'check_in_date' => date("Y-m-d", strtotime($request->get("check_in_date"))),
                            'check_out_date' => date("Y-m-d", strtotime($request->get("check_out_date"))),
                            'room_id' => $request->get('room_id'),
                            'bed_number' => $request->get('bed_no') ? $request->get('bed_no') : 1,
                            'booking_type' => $request->get('type'),
                            'building_id' => $request->get('building_id'),
                            'floor_number' => $request->get('floor_number'),
                        ]);
                        $room = $is_booked;
                    }
                } else {
                    if ($room_id != null) {
                        $room = BookingRoom::find($room_id);
                    } else {
                        $room = new BookingRoom();
                    }
                    $bookinggg = Booking::where('id', $booking_id)->first();
                    $bookinggg->update([
                        'check_in_date' => date("Y-m-d", strtotime($request->get("check_in_date"))),
                        'check_out_date' => date("Y-m-d", strtotime($request->get("check_out_date"))),
                        'room_id' => $request->get('room_id'),
                        'bed_number' => $request->get('bed_no') ? $request->get('bed_no') : 1,
                        'booking_type' => $request->get('type'),
                        'building_id' => $request->get('building_id'),
                        'floor_number' => $request->get('floor_number'),
                    ]);
                }


                $room->room_id = $request->get('room_id');
                $room->bed_number = $request->get('bed_no') ? $request->get('bed_no') : 1;
                $room->check_in_date = date("Y-m-d", strtotime($request->get("check_in_date")));
                $room->check_out_date = date("Y-m-d", strtotime($request->get("check_out_date")));
                $room->member_id = null;
                $room->booking_id = $booking->id;
                $room->type = $request->get('type');

                $price = 0;
                $price = $room_model->room_price;

                if ($room->type == BookingRoom::BOOKING_TYPE_SINGLE_BED) {
                    $price = $room_model->bed_price;
                }

                $room->price = $price;
                $room->save();

                $booking->update([
                    'accommodation_status' => Booking::ACCOMMODATION_STATUS_CONFIRMED
                ]);

                $new_booking_room_id = $room->id;

                //return $room->deleteServices($member_id);
                $room->saveServices($request->get('external_services'), $request->get('start_date'), $request->get('end_date'), $member_id, $booking_room_id);
                //////////////////////////////////      child       //////////////////////////////////
                if ($request->has('is_child')) {
                    $is_child = 1;
                    $child_count = count($request->get('is_child'));
                } else {
                    $is_child = 0;
                    $child_count = 0;
                }

                $now = date("Y-m-d");
                $is_exists = UserExtraService::where('is_child_driver', '1')->where('booking_id', $booking_room_id)->where('member_id', null)->get();
                if ($is_exists->count() > 0) {
                    foreach ($is_exists as $is_exist) {
                        $startdate = $is_exist->service_start_date;
                        $now = date("Y-m-d");
                        if ($now > $startdate) {
                            $is_exist->update([
                                'service_end_date' => date("Y-m-d", strtotime($request->get("check_in_date")))
                            ]);
                        } else {
                            $is_exist->delete();
                        }

                    }
                }

                if ($child_count > 0) {
                    $adminsertting = AdminSetting::where('setting_name', 'Child')->first();
                    if ($adminsertting) {
                        $price = $adminsertting->price;
                    } else {
                        $price = 0;
                    }
                    $children_starts = $request->get('child_start_date');
                    $children_ends = $request->get('child_end_date');
                    foreach ($children_starts as $k => $child_start_date) {
                        if ($child_start_date != '') {
                            $service = new UserExtraService();
                            $service->user_id = $booking->user_id;
                            $service->booking_id = $new_booking_room_id;
                            $service->service_start_date = date("Y-m-d", strtotime($child_start_date));
                            $service->service_end_date = date("Y-m-d", strtotime($children_ends[$k]));
                            $service->price = $price;
                            $service->is_child_driver = 1;
                            //$service->save();
                            if ($service->id == null) {
                                if ($service->service_start_date < $service->service_end_date) {
                                    $service->save();
                                }
                            } else {
                                $service->save();
                            }
                        }
                    }
                    $bookinggg2 = Booking::where('id', $booking_id)->first();
                    $bookinggg2->update([
                        'child_count' => $child_count,
                    ]);
                }


                //////////////////////////////////      driver       //////////////////////////////////
                if ($request->has('is_driver')) {
                    $is_driver = 1;
                    $driver_count = count($request->get('is_driver'));
                } else {
                    $is_driver = 0;
                    $driver_count = 0;
                }
                $is_exists = UserExtraService::where('is_child_driver', '2')->where('booking_id', $booking_room_id)->where('member_id', null)->get();
                if ($is_exists->count() > 0) {
                    $old_room_details = BookingRoom::where('id', '=', $booking_room_id)->first();
                    $booking_room_start_date = $old_room_details->check_in_date;
                    foreach ($is_exists as $is_exist) {
                        $startdate = $is_exist->service_start_date;
                        if ($startdate == null) {
                            $startdate = $booking_room_start_date;
                        }
                        $now = date("Y-m-d");
                        if ($now > $startdate) {
                            $thisstart = $is_exist->service_start_date;
                            if ($thisstart != null && $now > $thisstart) {
                                $is_exist->update([
                                    'service_end_date' => date("Y-m-d", strtotime($request->get("check_in_date")))
                                ]);
                            }

                        } else {
                            $is_exist->delete();
                        }

                    }
                }


                if ($driver_count > 0) {
                    $adminsertting = AdminSetting::where('setting_name', 'Driver')->first();
                    if ($adminsertting) {
                        $price = $adminsertting->price;
                    } else {
                        $price = 0;
                    }
                    $drivers_stay = $request->get('driver_stay');
                    $driver_start_date = $request->get('driver_start_date');
                    $driver_end_date = $request->get('driver_end_date');
                    foreach ($drivers_stay as $k => $driver_stay) {
                        if ($driver_stay[0] == 'outside') {
                            if ($driver_start_date[$k - 1] != '') {
                                $service = new UserExtraService();
                                $service->user_id = $booking->user_id;
                                $service->booking_id = $new_booking_room_id;
                                $service->service_start_date = date("Y-m-d", strtotime($driver_start_date[$k - 1]));
                                $service->service_end_date = date("Y-m-d", strtotime($driver_end_date[$k - 1]));
                                $service->price = $price;
                                $service->is_child_driver = 2;
                                $service->driver_stay = 2;
                                if ($service->id == null) {
                                    if ($service->service_start_date < $service->service_end_date) {
                                        $service->save();
                                    }
                                } else {
                                    $service->save();
                                }
                                //$service->save();
                            }
                        } elseif ($driver_stay[0] == 'inside') {
                            $service = new UserExtraService();
                            $service->user_id = $booking->user_id;
                            $service->booking_id = $new_booking_room_id;
                            $service->price = $price;
                            $service->is_child_driver = 2;
                            $service->driver_stay = 1;
                            /*if($service->id == null){
                                if($service->service_start_date < $service->service_end_date){
                                     $service->save();
                                }
                            }
                            else{
                                $service->save();
                            }*/
                            $service->save();
                        }
                    }
                    $bookinggg2 = Booking::where('id', $booking_id)->first();
                    $bookinggg2->update([
                        'driver_count' => $driver_count,
                    ]);

                }
            } else {
                $booking_room_old = BookingRoom::where('booking_id', $booking->id)->where('member_id', $member->id)->orderBy('id', 'desc')->first();

                $room = BookingRoom::where([
                    'booking_id' => $booking->id,
                    'member_id' => $member->id,
                ])->orderBy('created_at', 'DESC')->first();
                if ($room != null) {
                    $now = date("Y-m-d");
                    $checkindate = $room->check_in_date;
                    $checkoutdate = $room->check_out_date;
                    if ($now > $checkindate) {
                        $memberrr = Member::where('id', $member->id)->first();
                        $memberrr->update([
                            'status' => 2,
                            'room_id' => $request->get('room_id'),
                            'bed_number' => $request->get('bed_no') ? $request->get('bed_no') : 1,
                            'booking_type' => $request->get('type'),
                            'building_id' => $request->get('building_id'),
                            'floor_number' => $request->get('floor_number'),
                            'check_in_date' => date("Y-m-d", strtotime($request->get("check_in_date"))),
                            'check_out_date' => date("Y-m-d", strtotime($request->get("check_out_date")))
                        ]);
                        $room->update([
                            'status' => 0,
                            'check_out_date' => date("Y-m-d", strtotime($request->get("check_in_date")))
                        ]);
                        $room = new BookingRoom();
                    } else {
                        $memberrr = Member::where('id', $member->id)->first();
                        $memberrr->update([
                            'status' => 2,
                            'room_id' => $request->get('room_id'),
                            'bed_number' => $request->get('bed_no') ? $request->get('bed_no') : 1,
                            'booking_type' => $request->get('type'),
                            'building_id' => $request->get('building_id'),
                            'floor_number' => $request->get('floor_number'),
                            'check_in_date' => date("Y-m-d", strtotime($request->get("check_in_date"))),
                            'check_out_date' => date("Y-m-d", strtotime($request->get("check_out_date")))
                        ]);
                    }
                } else {
                    $memberrr = Member::where('id', $member->id)->first();
                    $memberrr->update([
                        'status' => 2,
                        'room_id' => $request->get('room_id'),
                        'bed_number' => $request->get('bed_no') ? $request->get('bed_no') : 1,
                        'booking_type' => $request->get('type'),
                        'building_id' => $request->get('building_id'),
                        'floor_number' => $request->get('floor_number'),
                        'check_in_date' => date("Y-m-d", strtotime($request->get("check_in_date"))),
                        'check_out_date' => date("Y-m-d", strtotime($request->get("check_out_date")))
                    ]);
                    $room = new BookingRoom();
                }

                $member_id = $member->id;
                $room->room_id = $request->get('room_id');
                $room->check_in_date = date("Y-m-d", strtotime($request->get("check_in_date")));
                $room->check_out_date = date("Y-m-d", strtotime($request->get("check_out_date")));
                $room->member_id = $member->id;
                $room->booking_id = $booking->id;
                $room->bed_number = $request->get('bed_no');
                $room->type = $request->get('type');
                $price = $room_model->room_price;

                if ($room->type == BookingRoom::BOOKING_TYPE_SINGLE_BED) {
                    $price = $room_model->bed_price;
                }

                $room->price = $price;
                $room->save();

                $memberrr = Member::where('id', $member->id)->first();
                $memberrr->update([
                    'status' => 2,
                    'room_id' => $request->get('room_id'),
                    'bed_number' => $request->get('bed_no')
                ]);

                $booking->update([
                    'accommodation_status' => Booking::ACCOMMODATION_STATUS_CONFIRMED
                ]);

                $new_booking_room_id = $room->id;

                // $room->deleteServices($member_id);
                $room->saveServices($request->get('external_services'), $request->get('start_date'), $request->get('end_date'), $member_id, $booking_room_id);


                //////////////////////////////////      child       //////////////////////////////////
                if ($request->has('is_child')) {
                    $is_child = 1;
                    $child_count = count($request->get('is_child'));
                } else {
                    $is_child = 0;
                    $child_count = 0;
                }

                $now = date("Y-m-d");
                $is_exists = UserExtraService::where('is_child_driver', '1')->where('booking_id', $booking_room_id)->where('member_id', $member_id)->get();
                if ($is_exists->count() > 0) {
                    foreach ($is_exists as $is_exist) {
                        $startdate = $is_exist->service_start_date;
                        $now = date("Y-m-d");
                        if ($now > $startdate) {
                            $is_exist->update([
                                'service_end_date' => date("Y-m-d", strtotime($request->get("check_in_date")))
                            ]);
                        } else {
                            $is_exist->delete();
                        }

                    }
                }

                if ($child_count > 0) {
                    $adminsertting = AdminSetting::where('setting_name', 'Child')->first();
                    if ($adminsertting) {
                        $price = $adminsertting->price;
                    } else {
                        $price = 0;
                    }
                    $children_starts = $request->get('child_start_date');
                    $children_ends = $request->get('child_end_date');
                    foreach ($children_starts as $k => $child_start_date) {
                        if ($child_start_date != '') {
                            $service = new UserExtraService();
                            $service->user_id = $booking->user_id;
                            $service->member_id = $member_id;
                            $service->booking_id = $new_booking_room_id;
                            $service->service_start_date = date("Y-m-d", strtotime($child_start_date));
                            $service->service_end_date = date("Y-m-d", strtotime($children_ends[$k]));
                            $service->price = $price;
                            $service->is_child_driver = 1;
                            if ($service->id == null) {
                                if ($service->service_start_date < $service->service_end_date) {
                                    $service->save();
                                }
                            } else {
                                $service->save();
                            }
                            // $service->save();
                        }
                    }
                    $membersss = Member::where('id', $member_id)->first();
                    $membersss->update([
                        'child_count' => $child_count,
                    ]);
                }


                //////////////////////////////////      driver       //////////////////////////////////
                if ($request->has('is_driver')) {
                    $is_driver = 1;
                    $driver_count = count($request->get('is_driver'));
                } else {
                    $is_driver = 0;
                    $driver_count = 0;
                }
                $is_exists = UserExtraService::where('is_child_driver', '2')->where('booking_id', $booking_room_id)->where('member_id', $member_id)->get();
                if ($is_exists->count() > 0) {
                    $old_room_details = BookingRoom::where('id', '=', $booking_room_id)->first();
                    $booking_room_start_date = $old_room_details->check_in_date;
                    foreach ($is_exists as $is_exist) {
                        $startdate = $is_exist->service_start_date;
                        if ($startdate == null) {
                            $startdate = $booking_room_start_date;
                        }
                        $now = date("Y-m-d");
                        if ($now > $startdate) {
                            $thisstart = $is_exist->service_start_date;
                            if ($thisstart != null && $now > $thisstart) {
                                $is_exist->update([
                                    'service_end_date' => date("Y-m-d", strtotime($request->get("check_in_date")))
                                ]);
                            }

                        } else {
                            $is_exist->delete();
                        }

                    }
                }


                if ($driver_count > 0) {
                    $adminsertting = AdminSetting::where('setting_name', 'Driver')->first();
                    if ($adminsertting) {
                        $price = $adminsertting->price;
                    } else {
                        $price = 0;
                    }

                    $drivers_stay = $request->get('driver_stay');
                    $driver_start_date = $request->get('driver_start_date');
                    $driver_end_date = $request->get('driver_end_date');
                    /*foreach ($drivers_starts as $k => $driver_start_date) {
                        if ($driver_start_date != '') {
                            $service = new UserExtraService();
                            $service->user_id = $booking->user_id;
                            $service->member_id = $member_id;
                            $service->booking_id = $new_booking_room_id;
                            $service->service_start_date = date("Y-m-d", strtotime($driver_start_date));
                            $service->service_end_date = date("Y-m-d", strtotime($drivers_ends[$k]));
                            $service->price = $price;
                            $service->is_child_driver = 2;
                            $service->save();
                        }
                    }*/
                    foreach ($drivers_stay as $k => $driver_stay) {
                        if ($driver_stay[0] == 'outside') {
                            if ($driver_start_date[$k - 1] != '') {
                                $service = new UserExtraService();
                                $service->user_id = $booking->user_id;
                                $service->member_id = $member_id;
                                $service->booking_id = $new_booking_room_id;
                                $service->service_start_date = date("Y-m-d", strtotime($driver_start_date[$k - 1]));
                                $service->service_end_date = date("Y-m-d", strtotime($driver_end_date[$k - 1]));
                                $service->price = $price;
                                $service->is_child_driver = 2;
                                $service->driver_stay = 2;
                                if ($service->id == null) {
                                    if ($service->service_start_date < $service->service_end_date) {
                                        $service->save();
                                    }
                                } else {
                                    $service->save();
                                }
                            }
                        } elseif ($driver_stay[0] == 'inside') {
                            $service = new UserExtraService();
                            $service->user_id = $booking->user_id;
                            $service->member_id = $member_id;
                            $service->booking_id = $new_booking_room_id;
                            $service->price = $price;
                            $service->is_child_driver = 2;
                            $service->driver_stay = 1;
                            /*if($service->id == null){
                                if($service->service_start_date < $service->service_end_date){
                                     $service->save();
                                }
                            }
                            else{
                                $service->save();
                            }*/
                            $service->save();
                        }
                    }
                    $membersss = Member::where('id', $member_id)->first();
                    $membersss->update([
                        'driver_count' => $driver_count,
                    ]);
                }
            }
        }

        return redirect(route('Laralum::ipd.booking.show', ['booking_id' => $booking->id]))->with('success', 'Booking has been completed successfully.');
    }

    public function bookedroomupdate(Request $request)
    {
        ///return $request->all();
        $id = $request->booking_id;
        $booking_room = BookingRoom::find($id);

        if ($booking_room) {
            if ($request->booking_action == 'checkout') {
                $booking_room->update([
                    'check_out_date' => date("Y-m-d"),
                    'status' => 0
                ]);

                $services = UserExtraService::where('booking_id', $id)->get();

                foreach ($services as $service) {
                    $service->update([
                        'service_end_date' => date("Y-m-d")
                    ]);
                }

                return redirect()->back()->with('success', 'Accomodation have been checkout successfully.');
            } elseif ($request->booking_action == 'delete') {

                //dd($booking_room);
                $member_id = $booking_room->member_id;
                if ($member_id != null) {
                    $member = Member::where('id', $member_id)->first();
                    if ($member) {
                        $member->update([
                            'status' => 1
                        ]);
                    }
                }

                //die('fdfsd');
                $services = UserExtraService::where('booking_id', $id)->get();

                foreach ($services as $service) {
                    $service->delete();
                }

                $booking_room->delete();

                return redirect()->back()->with('success', 'Accomodation have been deleted successfully.');
            }
        }

        return redirect()->back()->with('error', 'Something went wrong.');

    }

    public function account(Request $request, $id)
    {
        $data = [];
        $matchThese = [];
        $search = false;
        $patient = [];
        $user = [];

        $option_ar = [];
        $kid = "";
        if ($request->has('filter_patient_id') && $request->get('filter_patient_id') != "") {
            $option_ar[] = "Patient Id";
            $search = true;
            $kid = $request->get('filter_patient_id');
            $matchThese['kid'] = $request->get('filter_patient_id');
        }
        $filter_name = "";
        if ($request->has('filter_name') && $request->get('filter_name') != "") {
            $option_ar[] = "Name";
            $search = true;
            $matchThese['first_name'] = $request->get('filter_name');
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

        if ($id != null) {
            $booking = Booking::find($id);

            /* if (!$booking->isAllowed()) {
                 abort(401, "You don't have permissions to access this area");
             }*/

            if (!$booking->bookingValidity()) {
                return redirect('admin/booking/' . $booking->id . '/show')->with('error', "Can not check accounts, as booking is not available on current date");
            }
            $user = $booking->user;
        } else {
            $booking = new Booking();
        }

        if ($search == true) {
            $booking = Booking::select('bookings.*')->where('status', Booking::STATUS_COMPLETED)->join('users', 'users.id', '=', 'bookings.user_id')->join('user_profiles', 'user_profiles.booking_id', '=', 'bookings.id')->where('users.email', 'like', "%" . $filter_email . "%")->where('users.name', 'like', "%" . $filter_name . "%")->where('user_profiles.mobile', 'like', "%" . $filter_mobile . "%");
            if ($kid != null) {
                $booking = $booking->where('user_profiles.kid', $kid);
            }
            $booking = $booking->first();
            $user = new User();
            if ($booking != null) {
                /* if (!$booking->isAllowed()) {
                     abort(401, "You don't have permissions to access this area");
                 }*/
                if (!$booking->bookingValidity()) {
                    return redirect('admin/booking/' . $booking->id . '/show')->with('error', "Can not check accounts, as booking is not available on current date");
                }
                $user = $booking->user;
            } else {
                $booking = new Booking();
            }
        }

        return view('laralum.booking.account', compact('booking', 'user', 'search'));

    }

    public function accountPrint(Request $request, $id)
    {
        $booking = Booking::find($id);
        $user = $booking->user;
        $user_id = $user->id;
        $user_profile = UserProfile::where([
            'user_id' => $user_id,
        ])->first();
        $discharge_patient = DischargePatient::where([
            'booking_id' => $booking->id,
            'status' => DischargePatient::STATUS_PENDING
        ])->first();

        if ($discharge_patient == null) {
            $discharge_patient = new DischargePatient();
        }

        $data['discharge_patient'] = $discharge_patient;
        $data['booking'] = $booking;
        $data['user'] = $user;
        $data['user_profile'] = $user_profile;
        $data['back'] = 'account';

        //dd($data);
        return view('laralum.booking.print-bill', $data);

        // return view('laralum.booking.print-account-details', compact('booking', 'user'));
    }

    public function destroy(Request $request, $id)
    {
        $booking = Booking::find($id);

        if ($booking != null) {
            /*if (!$booking->isDeleteAble()) {
                if ($booking->status != Booking::STATUS_DISCHARGED) {
                    $booking->discharge(Booking::STATUS_CANCELLED);
                    $booking->update([
                        'status' => Booking::STATUS_CANCELLED,
                        'cancel_reason' => $request->get('cancel_reason')
                    ]);
                }

                return redirect('admin/bookings')->with('success', 'Booking '.$booking->userProfile->kid.' cancelled successfully !!!');
            }

            $status = $booking->status;

            if ($booking->status == Booking::STATUS_DISCHARGED) {
                $booking->customDelete();
            } else {
                $booking->discharge();
                $booking->customDelete();
            }
            if ($status == Booking::STATUS_DISCHARGED) {
                return redirect('admin/archived-patients')->with('status', 'Succesfully Deleted');
            }
            return redirect('admin/bookings')->with('status', 'Succesfully Deleted');
        }*/

            if ($booking->status != Booking::STATUS_DISCHARGED) {
                $booking->discharge(Booking::STATUS_CANCELLED);
                $booking->update([
                    'status' => Booking::STATUS_CANCELLED,
                    'cancel_reason' => $request->get('cancel_reason')
                ]);
                return redirect('admin/bookings')->with('success', 'Booking ' . $booking->userProfile->kid . ' cancelled successfully !!!');
            }
        }

        return redirect('admin/bookings')->with('status', 'Something Went Wrong!!!');

    }

    public function confirmDelete($id)
    {
        $booking = Booking::find($id);
        if ($booking != null) {
            if (!$booking->isDeleteAble()) {
                return view('laralum/booking/cancel', compact('booking'));
            }

        }
        return view('laralum/booking/cancel', compact('booking'));
        /*
        return view('laralum/security/confirm');*/
    }


    public function ajaxUpdate(Request $request)
    {
        Laralum::permissionToAccess(['admin.patients.list', 'doctor.patients', 'admin.bookings.list']);

        $matchThese = [];
        $bookingmatchThese = [];
        $usermatchThese = [];
        $matchTheseAddress = [];

        $search = false;
        $option_ar = [];

        if (!empty($request->get('kid'))) {
            $option_ar[] = "Patient Id";
            $search = true;
            $matchThese['kid'] = $request->get('kid');
        }

        if (!empty($request->get('uhid'))) {
            $option_ar[] = "UHID";
            $search = true;
            $usermatchThese['uhid'] = $request->get('uhid');
        }

        if (!empty($request->get('booking_id'))) {
            $option_ar[] = "Booking Id";
            $search = true;
            $bookingmatchThese['booking_id'] = $request->get('booking_id');
        }

        $filter_name = '';

        if (!empty($request->get('first_name'))) {
            $option_ar[] = "Name";
            $search = true;
            $filter_name = $request->get('first_name');
        }

        $address_string = '';
        if ($request->has('city') && $request->get('city') != "") {
            $option_ar[] = "City";
            $search = true;
            $address_array = explode(',', $request->city);
            $address_string = $request->city;
            $matchTheseAddress['city'] = $address_array[0];
            if (isset($address_array[1])) {
                $matchTheseAddress['state'] = $address_array[1];
            }

            if (isset($address_array[2])) {
                $matchTheseAddress['country'] = $address_array[2];
            }
        }

        if (!empty($request->get('state'))) {
            $option_ar[] = "State";
            $search = true;
            $matchTheseAddress['state'] = $request->get('state');
        }

        if (!empty($request->get('country'))) {
            $option_ar[] = "Country";
            $search = true;
            $matchTheseAddress['country'] = $request->get('country');
        }

        if (!empty($request->get('mobile'))) {
            $option_ar[] = "Mobile";
            $search = true;
            $matchThese['mobile'] = $request->get('mobile');
        }

        if (!empty($request->get('patient_type'))) {
            $option_ar[] = "Patient Type";
            $search = true;
            $bookingmatchThese['patient_type'] = $request->get('patient_type');
        }
        $acm_status = "";
        if (!empty($request->get('accommodation_status'))) {
            $option_ar[] = "Accommodation Status";
            $search = true;
            $bookingmatchThese['accommodation_status'] = $request->get('accommodation_status');
            $acm_status = $request->get('accommodation_status');
        }
        $booking_status = "";
        if (!empty($request->get('status'))) {
            $option_ar[] = "Status";
            $search = true;
            $bookingmatchThese['status'] = $request->get('status');
            $booking_status = $request->get('status');
        }

        if (!empty($request->get('email'))) {
            $option_ar[] = "Email";
            $search = true;
            $usermatchThese['email'] = $request->get('email');
        }

        $options = implode(", ", $option_ar);

        $error = "Entered " . $options . " is not valid,
make sure that you are entering valid " . $options . " 
or search by other options";
        $user = [];
        $per_page = $request->get("per_page") ? $request->get("per_page") : 10;
        $pagination = true;
        if ($per_page == "All") {
            $pagination = false;
        }

        $models_query = Booking::select('bookings.*')->join('users', 'users.id', '=', 'bookings.user_id')->join('user_profiles', 'user_profiles.id', 'bookings.profile_id')->orderBy('bookings.created_at', 'DESC');

        if (\Auth::user()->isDoctor()) {
            $models_query = $models_query->whereIn('status', [Booking::STATUS_COMPLETED]);
        } else {
            $models_query = $models_query->whereIn('status', [Booking::STATUS_COMPLETED, Booking::STATUS_PENDING]);
        }
        $models_query = $models_query->where('bookings.patient_type', Booking::PATIENT_TYPE_OPD);

        $malefemale_query = clone $models_query;
        $models = clone $models_query;


        $males = $models_query->where('user_profiles.gender', UserProfile::GENDER_MALE)->count();
        // print_r($males);exit;
        $females = $malefemale_query->where('user_profiles.gender', UserProfile::GENDER_FEMALE)->count();
        //  print_r($models->count());exit;


        if ($search == true) {
            $models_query = Booking::select('bookings.*')->join('users', 'users.id', '=', 'bookings.user_id')->join('user_profiles', 'user_profiles.id', 'bookings.profile_id')
                ->leftJoin('user_addresses', 'user_addresses.profile_id', 'bookings.profile_id')
                ->where('user_profiles.patient_type', UserProfile::PATIENT_TYPE_OPD)
                ->where('bookings.status', Booking::STATUS_COMPLETED)
                ->where(function ($query) use ($matchThese, $bookingmatchThese, $usermatchThese, $matchTheseAddress, $filter_name) {
                    foreach ($matchTheseAddress as $key => $match) {
                        $query->where('user_addresses.' . $key, 'like', "%$match%");
                    }
                    foreach ($matchThese as $key => $match) {
                        $query->where('user_profiles.' . $key, 'like', "%$match%");
                    }
                    foreach ($bookingmatchThese as $key => $match) {
                        $query->where('bookings.' . $key, 'like', "%$match%");
                    }
                    foreach ($usermatchThese as $key => $match) {
                        $query->where('users.' . $key, 'like', "%$match%");
                    }
                    if ($filter_name != "") {
                        $query->WhereRaw("concat(user_profiles.first_name, ' ', user_profiles.last_name) like '%$filter_name%' ");
                    }

                })
                ->orderBy('bookings.created_at', 'DESC');

            $count = $models_query->count();
            //print_r($models);


            $malefemale_query = clone $models_query;
            $models = clone $models_query;

            $males = $models_query->where('user_profiles.gender', UserProfile::GENDER_MALE)->count();
            // print_r($males);exit;
            $females = $malefemale_query->where('user_profiles.gender', UserProfile::GENDER_FEMALE)->count();


            $models = $models->distinct()->get();

            if ($filter_name != "") {
                $matchThese['first_name'] = $request->get('first_name');
            }

            if ($address_string != "") {
                $matchTheseAddress['city'] = $address_string;
            }

        } else {
            if ($pagination == true) {
                $count = $models->count();
                $models = $models->paginate($per_page);
            } else {
                $count = $models->count();
                $models = $models->distinct()->get();
            }
        }

        if (isset($matchThese['first_name'])) {
            $matchThese['first_name'] = $matchThese['first_name'] . ' ' . $matchThese['last_name'];
        }

        /*echo '<pre>'; print_r($matchThese['role_id']);exit;*/
        # Return the view

        return [
            'html' => view('laralum/booking/_index', ['models' => $models, 'count' => $count, 'error' => $error, 'males' => $males, 'females' => $females, 'search' => $search, 'search_data' => array_merge($matchThese, $bookingmatchThese, $usermatchThese, $matchTheseAddress)])->render()
        ];

    }


    //Opd tokens
    public function opdTokenList(Request $request)
    {
        Laralum::permissionToAccess('admin.bookings.opd.tokens.list');
        $models = OpdTokens::orderBy('opd_tokens.created_at', 'DESC');
        $per_page = $request->get("per_page") ? $request->get("per_page") : 10;
        $pagination = true;
        if ($per_page == "All") {
            $pagination = false;
        }
        $count = $models->count();
        if ($pagination == true) {
            $models = $models->paginate($per_page);
        } else {
            $models = $models->get();
        }

        $data['models'] = $models;
        $data['count'] = $count;
        return view('laralum.booking.generate-opd-token-list', $data);
    }

    public function generateOpdToken(Request $request, $id = null)
    {
        Laralum::permissionToAccess('admin.bookings.opd.tokens.list');
        $data = [];
        $matchThese = [];
        $search = false;
        $patient = [];
        $user = [];

        $option_ar = [];
        $kid = "";
        if ($request->has('filter_patient_id') && $request->get('filter_patient_id') != "") {
            $option_ar[] = "Patient Id";
            $search = true;
            $kid = $request->get('filter_patient_id');
            $matchThese['kid'] = $request->get('filter_patient_id');
        }

        $uhid = "";

        if ($request->has('uhid') && $request->get('uhid') != "") {
            $option_ar[] = "UHID";
            $search = true;
            $uhid = $request->get('uhid');
        }


        $filter_name = "";
        if ($request->has('filter_name') && $request->get('filter_name') != "") {
            $option_ar[] = "Name";
            $search = true;

            $filter_name = $request->get('filter_name');
        }

        /*if ($request->has('filter_first_name') && $request->get('filter_first_name') != "") {
            $option_ar[] = "First Name";
            $search = true;
            $matchThese['first_name'] = $request->get('filter_first_name');
        }*/

        /*if ($request->has('filter_last_name') && $request->get('filter_last_name') != "") {
            $option_ar[] = "Last Name";
            $search = true;
            $matchThese['last_name'] = $request->get('filter_last_name');
        }*/
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

        if ($id != null) {
            $booking = Booking::find($id);

            if (!$booking->isAllowed()) {
                abort(401, "You don't have permissions to access this area");
            }

            if (!$booking->isEditable())
                return redirect("admin/booking/" . $id . "/show")->back()->with('error', 'Patient is not active.');

            if (!$booking->bookingValidity()) {
                return redirect('admin/booking/' . $booking->id . '/show')->with('error', "Can not generate token, as booking is not available on current date");
            }
            $user = $booking->user;
        } else {
            $booking = new Booking();
        }

        if ($search == true) {
            $booking = Booking::select('bookings.*')->where('bookings.patient_type', Booking::PATIENT_TYPE_OPD)
                ->where('status', Booking::STATUS_COMPLETED)->join('users', 'users.id', '=', 'bookings.user_id')
                ->join('user_profiles', 'user_profiles.id', 'bookings.profile_id')
                ->whereNotNull('bookings.booking_id')
                /*->where('users.email', 'like', "%" . $filter_email . "%")->where('users.name', 'like', "%" . $filter_name . "%")
                ->where('user_profiles.mobile', 'like', "%" . $filter_mobile . "%")*/

                ->where(function ($query) use ($matchThese, $filter_email, $filter_name, $uhid, $kid) {
                    foreach ($matchThese as $key => $match) {
                        $query->where('user_profiles.' . $key, 'like', "%$match%");
                    }
                    if ($filter_email != "") {
                        $query->where('users.email', 'like', "%$filter_email%");
                    }
                    if ($filter_name != "") {
                        $query->WhereRaw("concat(user_profiles.first_name, ' ', user_profiles.last_name) like '%$filter_name%' ");
                    }

                    if ($uhid != "") {
                        $query->where('users.uhid', 'like', "%$uhid%");
                    }
                    if ($kid != "") {
                        $query->where('user_profiles.kid', 'like', "%$kid%");
                    }
                });

            $booking = $booking->first();

            $user = new User();
            if ($booking != null) {
                if (!$booking->bookingValidity()) {
                    return redirect('admin/booking/' . $booking->id . '/show')->with('error', "Can not generate token, as booking is not available on current date");
                }
                $user = $booking->user;
            } else {
                $booking = new Booking();
            }
            if ($request->get('filter_name')) {
                $matchThese['first_name'] = $request->get('filter_name');
            }

            if ($request->get('uhid')) {
                $matchThese['uhid'] = $request->get('uhid');
            }

        }

        if (!$booking->isEditable())
            return redirect()->back()->with('error', 'Patient is not active.');

        $date = (string)date('Y-m-d');
        $token = new OpdTokens();

        if ($user == null) {
            $user = new User();
        }
        $countries = Laralum::countries();
        $states = State::where('country_id', 101)->pluck('name')->toArray();

        $data['patient'] = $user;
        $data['booking'] = $booking;
        $data['user'] = $data['patient'];
        $data['search'] = $search;
        $data['error'] = $error;
        $data['countries'] = $countries;
        $data['states'] = $states;

        return view('laralum.booking.generate-opd-token', $data);
    }

    public function opdTokenAjaxUpdate(Request $request)
    {
        Laralum::permissionToAccess('admin.bookings.opd.tokens.list');
        $matchThese = [];
        $profileMatchThese = [];
        $matchTheseAddress = [];
        $doctorMatchThese = [];

        $search = false;
        $option_ar = [];
        if (!empty($request->get('city'))) {
            $option_ar[] = "City";
            $search = true;
            $matchTheseAddress['city'] = $request->get('city');
        }

        if (!empty($request->get('kid'))) {
            $option_ar[] = "Registration Id";
            $search = true;
            $profileMatchThese['kid'] = $request->get('kid');
        }

        if (!empty($request->get('department_id'))) {
            $option_ar[] = "Department";
            $search = true;
            $matchThese['department_id'] = $request->get('department_id');
        }

        $filter_name = "";
        if (!empty($request->get('first_name'))) {
            $option_ar[] = "Name";
            $search = true;
            $filter_name = $request->get('first_name');
        }

        $doc_name = "";


        if (!empty($request->get('doctor_name'))) {
            $option_ar[] = "Doctor Name";
            $search = true;
            $doctorMatchThese['doctor_name'] = $request->get('doctor_name');
            $doc_name = $request->get('doctor_name');
        }

        $options = implode(", ", $option_ar);

        $error = "Entered " . $options . " is not valid,
make sure that you are entering valid " . $options . " 
or search by other options";
        $user = [];
        $per_page = $request->get("per_page") ? $request->get("per_page") : 10;
        $pagination = true;
        if ($per_page == "All") {
            $pagination = false;
        }

        $models = OpdTokens::select('opd_tokens.*')->orderBy('opd_tokens.created_at', 'DESC');
        $count = 0;
        if ($search == true) {
            $models = OpdTokens::select('opd_tokens.*')->leftJoin('bookings', 'bookings.id', '=', 'opd_tokens.booking_id')->leftJoin('user_profiles', 'user_profiles.id', '=', 'bookings.profile_id')->join('users', 'users.id', '=', 'opd_tokens.doctor_id');
            if (!empty($matchThese) || !empty($profileMatchThese) || !empty($doc_name) || !empty($filter_name)) {

                $models = $models->where(function ($query) use ($matchThese, $profileMatchThese, $doc_name, $filter_name) {
                    foreach ($matchThese as $key => $match) {
                        $query->where('opd_tokens.' . $key, 'like', "%$match%");
                    }
                    foreach ($profileMatchThese as $key => $match) {
                        $query->where('user_profiles.' . $key, 'like', "%$match%");
                    }
                    if ($filter_name != "") {
                        $query->whereRaw("concat(opd_tokens.first_name, ' ', opd_tokens.last_name) like '%$filter_name%' ");
                    }

                    if ($doc_name != "") {
                        $query->where('users.name', 'LIKE', '%' . $doc_name . '%');
                    }
                });
            }

            $models = $models->orderBy('opd_tokens.created_at', 'DESC');

            $models = $models->get();
            $count = $models->count();

            if (!empty($filter_name)) {
                $matchThese['first_name'] = $filter_name;
            }
        } else {
            if ($pagination == true) {
                $count = $models->count();
                $models = $models->paginate($per_page);
            } else {
                $count = $models->count();
                $models = $models->get();
            }
        }

        /*echo '<pre>'; print_r($matchThese['role_id']);exit;*/
        # Return the view
        return [
            'html' => view('laralum/booking/_opdtokens', ['models' => $models, 'count' => $count, 'error' => $error, 'search' => $search, 'search_data' => array_merge($matchThese, $profileMatchThese, $doctorMatchThese)])->render()
        ];
    }

    public function printOpdToken(Request $request, $id = null)
    {

        //dd($request->all());
        Laralum::permissionToAccess('admin.bookings.opd.tokens.list');
        if ($id == null)
            $id = $request->get('booking_id');

        $booking = Booking::find($id);
        $profession_req = $request->get('profession');
        if($profession_req == 'other'){
            $profession = Profession::create([
                            'name' => $request->get('profession_name'),
                            'slug' => str_slug($request->get('profession_name')),
                            'is_private' => Profession::IS_PRIVATE
                        ]);
            $profession_id = $profession->id;
        }
        else{
            $profession_id = $request->get('profession');
        }
        $token = new OpdTokens();
        $token->department_id = $request->get('department_id');
        $token->doctor_id = $request->get('doctor_id');
        $token->complaints = $request->get('complaints');
        $token->date = date('Y-m-d');
        $token->reference_number = $token->getNumber();
        $token->charges = AdminSetting::getSettingPrice('consultation_charges');
        if ($booking != null) {
            $token->booking_id = $id;
            $token->patient_id = $booking->user_id;
        }
        $token->first_name = $request->get('first_name');
        $token->last_name = $request->get('last_name');
        $token->mobile = $request->get('mobile');
        $token->gender = $request->get('gender');
        $token->profession = $profession_id;
        $token->dob = $request->get('dob');
        $token->address = $request->get('address');
        $token->city = $request->get('city');
        $token->state = $request->get('state');
        $token->country = $request->get('country') ? $request->get('country') : 'IN';
        $token->save();
        $back_url = "";
        //dd($array);
        return view('laralum.booking.print-opd-token', compact('token', 'booking', 'back_url'));
    }

    public function opdTokensConvert($id)
    {
        $token = OpdTokens::find($id);
        $random = str_random(6);
        $user = User::where('mobile_number', $token->mobile)->first();
        if (empty($user)) {
            $user = new User();
            $user = User::create([
                'mobile_number' => $token->mobile,
                'password' => Hash::make($random),
                'country_code' => 'IN',
                'uhid' => $user->getUhid()
            ]);
            $user->save();
            $user->saveRole(Role::ROLE_PATIENT);
            $user->sendPassword($random);
        } else {
            $c_booking = $user->current_booking;
            if ($c_booking) {
                return redirect()->back()->with('error', 'Booking already exists for the user with same mobile number (' . $user->mobile_number . '). UHID=' . $user->uhid . ' Registration ID=' . $user->current_booking->getProfile('kid') . ' Booking ID=' . $user->current_booking->booking_id);
            }
        }

        $userProfile = UserProfile::where('user_id', $user->id)->first();
        if ($userProfile == null) {
            $userProfile = new UserProfile();
            $userProfile->user_id = $user->id;
        }

        $userAddress = UserAddress::where('user_id', $user->id)->first();
        if ($userAddress == null) {
            $userAddress = new UserAddress();
            $userAddress->user_id = $user->id;
        }

        $userProfile->first_name = $token->first_name;
        $userProfile->patient_type = UserProfile::PATIENT_TYPE_OPD;
        $userProfile->last_name = $token->last_name;
        $userProfile->mobile = $token->mobile;
        $userProfile->gender = $token->gender;
        $userProfile->profession_id = $token->profession;
        $userProfile->dob = $token->dob;
        $userProfile->save();
        $userProfile->kid = User::getId("K-OPD", $userProfile->getIdNumber());
        $userProfile->save();
        $booking = $user->getBooking(Booking::STATUS_PENDING);
        if ($booking == null) {
            $booking = new Booking();
        }

        $booking->user_id = $user->id;
        $booking->patient_type = UserProfile::PATIENT_TYPE_OPD;
        $booking->status = Booking::STATUS_COMPLETED;
        $booking->profile_id = $userProfile->id;
        $booking->booking_id = $booking->getIdNumber();
        $booking->save();

        $userAddress->address1 = $token->address;
        $userAddress->city = $token->city;
        $userAddress->state = $token->state;
        $userAddress->country = $token->country ? $token->country : 'IN';
        $userAddress->profile_id = $userProfile->id;
        $userAddress->save();

        $healthIssues = HealthIssue::where([
            'user_id' => $user->id,
            'status' => Booking::STATUS_PENDING,
            'booking_id' => $booking->id
        ])->first();
        if ($healthIssues == null) {
            $healthIssues = new HealthIssue();
        }
        $healthIssues->user_id = $user->id;
        $healthIssues->booking_id = $booking->id;
        $healthIssues->status = HealthIssue::STATUS_COMPLETED;
        $healthIssues->health_issues = $token->complaints;
        $healthIssues->save();

        $payment_detail = PaymentDetail::where([
            'user_id' => $user->id,
            'booking_id' => $booking->id,
        ])->first();

        if ($payment_detail == null) {
            PaymentDetail::create([
                'user_id' => $user->id,
                'booking_id' => $booking->id,
                'type' => PaymentDetail::PAYMENT_METHOD_WALLET,
            ]);
        }

        if ($token->charges > 0) {
            $wallet = Wallet::create([
                'user_id' => $booking->user_id,
                'amount' => $token->charges,
                'type' => Wallet::TYPE_PAID,
                'created_by' => \Auth::user()->id,
                'status' => Wallet::STATUS_PAID,
                'payment_method' => PaymentDetail::PAYMENT_METHOD_WALLET,
                'booking_id' => $booking->id,
                'description' => '',
            ]);
        }
        $token->booking_id = $booking->id;
        $token->patient_id = $booking->user_id;
        $token->save();
        return redirect()->back()->with('success', 'Successfully converted to OPD Patient');
    }

    /**
     * print individual opd token
     * @param null $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function printOpdPatientToken($id = null)
    {
        Laralum::permissionToAccess('admin.bookings.tokens.list');
        $token = OpdTokens::find($id);
        $booking = $token->booking;
        $back_url = url('admin/opd-token-list');
        return view('laralum.booking.print-opd-token', compact('token', 'back_url', 'booking'));
    }

     /**
     * print individual opd token bill
     * @param null $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function printOpdPatientTokenBill(Request $request, $id = null)
    {
        Laralum::permissionToAccess('admin.bookings.tokens.list');
        $tokenBill = Bill::where('opd_token_id', $id)->first();
        $token = OpdTokens::find($id);
        $booking = $token->booking;
        $data['booking'] = $booking;
        $back_url = 'opd-token-list';
        if ($request->get('back_url')){
            $back_url = url($request->get('back_url'));
        }
        $data['back'] = $back_url;

        if ($tokenBill === null) {
            $tokenBill = Bill::generateOpdTokenBill($token);           
        }
        $data['bill'] = $tokenBill;

        return view('laralum.booking.print-generated-bill', $data);
    }

    /**
     * delete opd token
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deleteOpdToken($id)
    {

        $token = OpdTokens::find($id);

        if ($token) {
            $token->delete();
        }

        return redirect()->to('admin/opd-token-list')->with('success', 'Token has been deleted successfully!');
    }

    public function printPatientDetails(Request $request, $id)
    {
        $booking = Booking::find($id);
        $patient_details = PatientDetails::where("booking_id", $id)->orderBy("created_at", "DESC")->first();

        if ($patient_details == null) {
            $patient_details = new PatientDetails();
        }

        $back_url = url('admin/booking/' . $id . '/show');
        $discharge = false;


        $vitalData = VitalData::where('booking_id', $booking->id)->orderBy('created_at', "DESC")->first();

        if ($vitalData == null) {
            $vitalData = new VitalData();
        }

        $physical = PhysicalExamination::where('booking_id', $id)->where('status', PhysicalExamination::STATUS_PENDING)->orderBy('created_at', 'DESC')->first();

        if ($physical == null)
            $physical = new PhysicalExamination();

        $respiratory = RespiratoryExamination::where('booking_id', $id)->where('status', RespiratoryExamination::STATUS_PENDING)->orderBy('created_at', 'DESC')->first();

        if ($respiratory == null)
            $respiratory = new RespiratoryExamination();

        $cardio = CardiovascularExamination::where('booking_id', $id)->where('status', CardiovascularExamination::STATUS_PENDING)->orderBy('created_at', 'DESC')->first();

        if ($cardio == null)
            $cardio = new CardiovascularExamination();

        $genitorinary = GenitourinaryExamination::where('booking_id', $id)->where('status', GenitourinaryExamination::STATUS_PENDING)->orderBy('created_at', 'DESC')->first();

        if ($genitorinary == null)
            $genitorinary = new GenitourinaryExamination();

        $gastro = GastrointestinalExamination::where('booking_id', $id)->where('status', GastrointestinalExamination::STATUS_PENDING)->orderBy('created_at', 'DESC')->first();

        if ($gastro == null)
            $gastro = new GastrointestinalExamination();

        $neuro = NeurologicalExamination::where('booking_id', $id)->where('status', NeurologicalExamination::STATUS_PENDING)->orderBy('created_at', 'DESC')->first();
        if ($neuro == null)
            $neuro = new NeurologicalExamination();

        $systemic = PhysiotherapySystemicExamination::where('booking_id', $id)->orderBy('created_at', 'DESC')->first();
        if ($systemic == null)
            $systemic = new PhysiotherapySystemicExamination();

        $sensory = PhysiotherapySensoryExamination::where('booking_id', $id)->orderBy('created_at', 'DESC')->first();
        if ($sensory == null)
            $sensory = new PhysiotherapySensoryExamination();

        $motor = PhysiotherapyMotorExamination::where('booking_id', $id)->orderBy('created_at', 'DESC')->first();
        if ($motor == null)
            $motor = new PhysiotherapyMotorExamination();

        $pain = PhysiotherapyPainExamination::where('booking_id', $id)->orderBy('created_at', 'DESC')->first();
        if ($pain == null)
            $pain = new PhysiotherapyPainExamination();

        $pain_assesment = PhysiotherapyPainAssesment::where('booking_id', $id)->orderBy('created_at', 'DESC')->first();
        if ($pain_assesment == null)
            $pain_assesment = new PhysiotherapyPainAssesment();


        $aturpariksha = AyurvedAturExamination::where('booking_id', $id)->where('status', AyurvedAturExamination::STATUS_PENDING)->first();
        if ($aturpariksha == null)
            $aturpariksha = new AyurvedAturExamination();
//        $aturpariksha->praman = $aturpariksha->praman != null ? $aturpariksha->praman : $vital_data_height;

        $ashtvidh = AyurvedaAshtvidhExamination::where('booking_id', $id)->where('status', AyurvedAturExamination::STATUS_PENDING)->first();
        if ($ashtvidh == null)
            $ashtvidh = new AyurvedaAshtvidhExamination();

        $doshpariksha = AyurvedDoshExamination::where('booking_id', $id)->where('status', AyurvedAturExamination::STATUS_PENDING)->first();
        if ($doshpariksha == null)
            $doshpariksha = new AyurvedDoshExamination();

        $dhatupariksha = AyurvedDhatuExamination::where('booking_id', $id)->where('status', AyurvedAturExamination::STATUS_PENDING)->first();
        if ($dhatupariksha == null)
            $dhatupariksha = new AyurvedDhatuExamination();


        $data['patient_details'] = $patient_details;
        $data['booking'] = $booking;
        $data['discharge'] = $discharge;
        $data['back_url'] = $back_url;
        $data['patient'] = $booking->user;

        $data['vitalData'] = $vitalData;
        $data['physical'] = $physical;
        $data['respiratory'] = $respiratory;
        $data['cardio'] = $cardio;
        $data['genitorinary'] = $genitorinary;
        $data['gastro'] = $gastro;
        $data['neuro'] = $neuro;
        $data['systemic'] = $systemic;
        $data['sensory'] = $sensory;
        $data['motor'] = $motor;
        $data['pain'] = $pain;
        $data['aturpariksha'] = $aturpariksha;
        $data['pain_assesment'] = $pain_assesment;
        $data['ashtvidh'] = $ashtvidh;
        $data['doshpariksha'] = $doshpariksha;
        $data['dhatupariksha'] = $dhatupariksha;

        $back_url = route('Laralum::booking.show', ['booking_id' => $booking->id]);


        //return $booking->status;

        if ($booking->status == Booking::STATUS_COMPLETED) {
            if ($booking->patient_type == \App\Booking::PATIENT_TYPE_IPD) {
                if ($booking->accommodation_status == \App\Booking::ACCOMMODATION_STATUS_CONFIRMED)
                    $back_url = route('Laralum::ipd.booking.show', $booking->id);
                else
                    $back_url = route('Laralum::future.booking.show', $booking->id);
            } elseif ($booking->patient_type == \App\Booking::PATIENT_TYPE_OPD) {
                $back_url = route('Laralum::opd.booking.show', $booking->id);
            }
        }

        if($booking->status == Booking::STATUS_DISCHARGED){
            $back_url = route('Laralum::ipd.booking.show', $booking->id);
        }   

        $data['back_url'] = $back_url;

        return view('laralum.booking.print-booking-details', $data);
    }

    public function pendingPatients(Request $request)
    {
        Laralum::permissionToAccess(['admin.future_patients_management']);
        $matchThese = [];
        $search = false;
        $option_ar = [];
        $matchTheseAddress = [];

        if ($request->has('kid') && $request->get('kid') != "") {
            $option_ar[] = "Patient Id";
            $search = true;
            $search = true;
            $matchThese['kid'] = $request->get('kid');
        }
        $uhid = '';
        if ($request->has('uhid') && $request->get('uhid') != "") {
            $option_ar[] = "UHID";
            $search = true;
            $search = true;
            $uhid = $request->get('uhid');
        }
        $filter_name = "";
        if ($request->has('first_name') && $request->get('first_name') != "") {
            $option_ar[] = "First Name";
            $search = true;
            //$matchThese['first_name'] = $request->get('first_name');
            $filter_name = $request->get('first_name');
        }


        if ($request->has('filter_last_name') && $request->get('filter_last_name') != "") {
            $option_ar[] = "Last Name";
            $search = true;

            $matchThese['last_name'] = $request->get('filter_last_name');
        }
        $filter_mobile = "";
        if ($request->has('mobile') && $request->get('mobile') != "") {
            $option_ar[] = "Mobile";
            $search = true;
            $filter_mobile = $request->get('mobile');
            $matchThese['mobile'] = $request->get('mobile');
        }

        $filter_type = "";
        if ($request->has('filter_patient_type') && $request->get('filter_patient_type') != "") {
            $option_ar[] = "Patient Type";
            $search = true;
            $filter_type = $request->get('filter_patient_type');
            $matchThese['patient_type'] = $request->get('filter_patient_type');
        }

        $address_string = '';
        if ($request->has('city') && $request->get('city') != "") {
            $option_ar[] = "City";
            $search = true;
            $address_array = explode(',', $request->city);
            $address_string = $request->city;
            $matchTheseAddress['city'] = $address_array[0];
            if (isset($address_array[1])) {
                $matchTheseAddress['state'] = $address_array[1];
            }

            if (isset($address_array[2])) {
                $matchTheseAddress['country'] = $address_array[2];
            }

        }

        $filter_accommodation_staus = "";
        if ($request->has('filter_accommodation_staus') && $request->get('filter_accommodation_staus') != "") {
            $option_ar[] = "Accommodation Status";
            $search = true;
            $filter_accommodation_staus = $request->get('filter_accommodation_staus');
        }

        /* $filter_name = "";
         if ($request->has('filter_name') && $request->get('filter_name') != "") {
             $option_ar[] = "Name";
             $search = true;
             $filter_name = $request->get('filter_name');
         }*/

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
        $per_page = $request->get("per_page") ? $request->get("per_page") : 10;
        $pagination = true;
        if ($per_page == "All") {
            $pagination = false;
        }
        if (\Auth::user()->isDoctor()) {
            $models_query = Booking::select('bookings.*')->where('bookings.check_in_date', '<=', date('Y-m-d h:i:s'))->join('users', 'users.id', '=', 'bookings.user_id')->leftjoin('user_profiles', 'user_profiles.id', 'bookings.profile_id')->orderBy('bookings.created_at', 'DESC');
        } else {
            $models_query = Booking::select('bookings.*')->join('users', 'users.id', '=', 'bookings.user_id')->leftjoin('user_profiles', 'user_profiles.id', 'bookings.profile_id')->orderBy('bookings.created_at', 'DESC');
        }

        $models_query = $models_query->whereIn('status', [Booking::STATUS_PENDING]);

        // echo '<pre>'; print_r($models_query->get());exit;

        $malefemale_query = clone $models_query;

        $models = clone $malefemale_query;

        $males = $models_query->where('user_profiles.gender', UserProfile::GENDER_MALE)->count();
        // print_r($males);exit;
        $females = $malefemale_query->where('user_profiles.gender', UserProfile::GENDER_FEMALE)->count();
        //  print_r($models->count());exit;
//print_r($matchThese);
        if ($search == true) {
            $models_query = Booking::select('bookings.*')->join('users', 'users.id', '=', 'bookings.user_id')->leftjoin('user_profiles', 'user_profiles.id', 'bookings.profile_id')->leftJoin('user_addresses', 'user_addresses.profile_id', '=', 'bookings.profile_id')->where(function ($query) use ($matchThese, $filter_email, $filter_name, $matchTheseAddress, $uhid) {
                foreach ($matchThese as $key => $match) {
                    $query->where('user_profiles.' . $key, 'like', "%$match%");
                }
                foreach ($matchTheseAddress as $key => $match) {
                    $query->where('user_addresses.' . $key, 'like', "%$match%");
                }
                if ($filter_email != "") {
                    $query->where('users.email', 'like', "%$filter_email%");
                }
                if ($uhid != "") {
                    $query->where('users.uhid', $uhid);
                }
                if ($filter_name != "") {
                    $query->where(function ($q) use ($filter_name) {
                        $q->WhereRaw("concat(user_profiles.first_name, ' ', user_profiles.last_name) like '%$filter_name%' ")->orWhere("users.name", 'like', "%$filter_name%");
                    });
                }
            })/*->where('users.email', 'like', "%" . $filter_email . "%")->where('users.name', 'like', "%" . $filter_name . "%")->where('user_profiles.mobile', 'like', "%" . $filter_mobile . "%")*/
            ->orderBy('bookings.created_at', 'DESC');

            $models_query = $models_query->whereIn('status', [Booking::STATUS_PENDING]);

            $models_query = $models_query->whereIn('status', [Booking::STATUS_PENDING]);

            // echo '<pre>'; print_r($models_query->get());exit;

            $malefemale_query = clone $models_query;

            $models = clone $malefemale_query;

            $males = $models_query->where('user_profiles.gender', UserProfile::GENDER_MALE)->count();
            // print_r($males);exit;
            $females = $malefemale_query->where('user_profiles.gender', UserProfile::GENDER_FEMALE)->count();

            //  echo '<pre>'; print_r($models->get()->toArray());exit;

            //->where('check_in_date', '>', date("Y-m-d H:i:s"));
            if(!empty($filter_name)) {
                $matchThese['first_name'] = $filter_name;
            }
            if(!empty($request->get('uhid'))) {
                $matchThese['uhid'] = $request->get('uhid');
            }
            if(!empty($address_string)) {
                $matchTheseAddress['city'] = $address_string;
            }
        }

        if ($pagination == true) {
            $count = $models->count();
            $models = $models->paginate($per_page);
        } else {
            $count = $models->count();
            $models = $models->get();
        }

        Notification::updateNotification(User::class);

        $pending = true;

        if ($request->ajax()) {

            return [
                'html' => view('laralum/booking/_index', ['models' => $models, 'count' => $count, 'error' => $error, 'search' => $search, 'search_data' => array_merge($matchThese, $matchTheseAddress), 'pending' => true, 'males' => $males, 'females' => $females])->render()
            ];
        }

        return view('laralum.booking.index', compact('models', 'search', 'error', 'count', 'males', 'females', 'pending'));
    }

    public function futurePatients(Request $request)
    {
        Laralum::permissionToAccess(['admin.future_patients_management']);
        $matchThese = [];
        $bookingmatchThese = [];
        $usermatchThese = [];
        $matchTheseAddress = [];
        $others = [];

        $search = false;
        $option_ar = [];

        if (!empty($request->get('kid'))) {
            $option_ar[] = "Patient Id";
            $search = true;
            $matchThese['kid'] = $request->get('kid');
        }
        if (!empty($request->get('uhid'))) {
            $option_ar[] = "UHID";
            $search = true;
            $usermatchThese['uhid'] = $request->get('uhid');
        }

        if (!empty($request->get('booking_id'))) {
            $option_ar[] = "Booking Id";
            $search = true;
            $bookingmatchThese['booking_id'] = $request->get('booking_id');
        }


        if (!empty($request->get('name'))) {
            $option_ar[] = "Name";
            $search = true;

            $array = explode(' ', $request->get('first_name'));

            $matchThese['first_name'] = $array[0];
            $matchThese['last_name'] = '';

            if (isset($array[1])) {
                $matchThese['last_name'] = $array[1];
            }
        }

        $filter_name = "";

        if (!empty($request->get('first_name'))) {
            $option_ar[] = "Name";
            $search = true;
            $filter_name = $request->first_name;
        }

        $address_string = '';
        if ($request->has('city') && $request->get('city') != "") {
            $option_ar[] = "City";
            $search = true;
            $address_array = explode(',', $request->city);
            $address_string = $request->city;
            $matchTheseAddress['city'] = $address_array[0];
            if (isset($address_array[1])) {
                $matchTheseAddress['state'] = $address_array[1];
            }

            if (isset($address_array[2])) {
                $matchTheseAddress['country'] = $address_array[2];
            }
        }

        if (!empty($request->get('state'))) {
            $option_ar[] = "State";
            $search = true;
            $matchTheseAddress['state'] = $request->get('state');
        }

        if (!empty($request->get('country'))) {
            $option_ar[] = "Country";
            $search = true;
            $matchTheseAddress['country'] = $request->get('country');
        }

        if (!empty($request->get('mobile'))) {
            $option_ar[] = "Mobile";
            $search = true;
            $matchThese['mobile'] = $request->get('mobile');
        }

        if (!empty($request->get('patient_type'))) {
            $option_ar[] = "Patient Type";
            $search = true;
            $bookingmatchThese['patient_type'] = $request->get('patient_type');
        }

        $acm_status = "";
        if (!empty($request->get('accommodation_status'))) {
            $option_ar[] = "Accommodation Status";
            $search = true;
            $bookingmatchThese['accommodation_status'] = $request->get('accommodation_status');
            $acm_status = $request->get('accommodation_status');
        }
        $booking_status = "";

        if (!empty($request->get('status'))) {
            $option_ar[] = "Status";
            $search = true;
            $bookingmatchThese['status'] = $request->get('status');
            $booking_status = $request->get('status');
        }

        if (!empty($request->get('email'))) {
            $option_ar[] = "Email";
            $search = true;
            $usermatchThese['email'] = $request->get('email');
        }

        if (!empty($request->get('check_in_date'))) {
            $search = true;
            $check_in_date = date("Y-m-d", strtotime($request->get('check_in_date')));
        }

        if (!empty($request->get('check_out_date'))) {
            $search = true;
            $check_out_date = date("Y-m-d", strtotime($request->get('check_out_date')));
        }

        $options = implode(", ", $option_ar);

        $error = "Entered " . $options . " is not valid,
make sure that you are entering valid " . $options . " 
or search by other options";
        $user = [];
        $per_page = $request->get("per_page") ? $request->get("per_page") : 10;
        $pagination = true;
        if ($per_page == "All") {
            $pagination = false;
        }

        $models_query = Booking::select('bookings.*')->join('users', 'users.id', '=', 'bookings.user_id')->join('user_profiles', 'user_profiles.id', 'bookings.profile_id')->orderBy('bookings.created_at', 'DESC');

        $models_query = $models_query->whereIn('status', [Booking::STATUS_COMPLETED])->where('user_profiles.patient_type', UserProfile::PATIENT_TYPE_IPD)->where(function ($q) {
            $q->orWhereNull('accommodation_status')->orWhere('accommodation_status', Booking::ACCOMMODATION_STATUS_PENDING);
        });

        // echo '<pre>'; print_r($models_query->get());exit;

        $malefemale_query = clone $models_query;

        $models = clone $malefemale_query;

        $males = $models_query->where('user_profiles.gender', UserProfile::GENDER_MALE)->count();
        // print_r($males);exit;
        $females = $malefemale_query->where('user_profiles.gender', UserProfile::GENDER_FEMALE)->count();
        //  print_r($models->count());exit;

        if ($search == true) {
            $models_query = Booking::select('bookings.*')->join('users', 'users.id', '=', 'bookings.user_id')->join('user_profiles', 'user_profiles.id', 'bookings.profile_id')
                ->join('user_addresses', 'user_addresses.profile_id', 'bookings.profile_id')->where(function ($query) use ($matchThese, $bookingmatchThese, $usermatchThese, $matchTheseAddress, $filter_name) {
                    foreach ($matchTheseAddress as $key => $match) {
                        $query->where('user_addresses.' . $key, 'like', "%$match%");
                    }
                    foreach ($matchThese as $key => $match) {
                        $query->where('user_profiles.' . $key, 'like', "%$match%");
                    }
                    foreach ($bookingmatchThese as $key => $match) {
                        $query->where('bookings.' . $key, 'like', "%$match%");
                    }
                    foreach ($usermatchThese as $key => $match) {
                        $query->where('users.' . $key, 'like', "%$match%");
                    }
                    if ($filter_name != "") {
                        $query->WhereRaw("concat(user_profiles.first_name, ' ', user_profiles.last_name) like '%$filter_name%' ");
                    }
                })
                ->orderBy('bookings.created_at', 'DESC');


            if (\Auth::user()->isDoctor()) {
                $models_query = $models_query->whereIn('status', [Booking::STATUS_COMPLETED]);

                if ($booking_status != "") {
                    $models_query = $models_query->where('status', $booking_status);
                }
            } else {
                $models_query = $models_query->whereIn('status', [Booking::STATUS_COMPLETED]);

                if ($booking_status != "") {
                    $models_query = $models_query->where('status', $booking_status);
                }
            }

            $models_query = $models_query->where('user_profiles.patient_type', UserProfile::PATIENT_TYPE_IPD);

            if ($acm_status != "") {
                $models_query = $models_query->where('accommodation_status', $acm_status);
            }

            $models_query = $models_query->whereIn('status', [Booking::STATUS_COMPLETED])->where('user_profiles.patient_type', UserProfile::PATIENT_TYPE_IPD)->whereNotIn('accommodation_status', [Booking::ACCOMMODATION_STATUS_CONFIRMED]);


            if (isset($check_in_date)) {
                $others['check_in_date'] = date('d-m-Y', strtotime($check_in_date));
                $models_query = $models_query->whereDate('check_in_date', $check_in_date);
            }

            if (isset($check_out_date)) {
                $others['check_out_date'] = date('d-m-Y', strtotime($check_out_date));
                $models_query = $models_query->whereDate('check_out_date', $check_out_date);
            }
            //->where('check_in_date', '>', date("Y-m-d H:i:s"));


            $malefemale_query = clone $models_query;

            $models = clone $malefemale_query;

            $males = $models_query->where('user_profiles.gender', UserProfile::GENDER_MALE)->count();
            // print_r($males);exit;
            $females = $malefemale_query->where('user_profiles.gender', UserProfile::GENDER_FEMALE)->count();

            $matchThese['first_name'] = $request->get('first_name');
            $matchTheseAddress['city'] = $address_string;
        }

        if ($pagination == true) {
            $count = $models->count();
            $models = $models->paginate($per_page);
        } else {
            $count = $models->count();
            $models = $models->get();
        }
        Notification::updateNotification(User::class);

        $future = true;

        if ($request->ajax()) {
            return [
                'html' => view('laralum/booking/_index', ['models' => $models, 'count' => $count, 'error' => $error, 'search' => $search, 'search_data' => array_merge($matchThese, $matchTheseAddress, $others, $bookingmatchThese, $usermatchThese), 'future' => true, 'males' => $males, 'females' => $females])->render()
            ];
        }
        return view('laralum.booking.index', compact('models', 'search', 'error', 'count', 'males', 'females', 'future'));
    }

    public function setUhid()
    {
        $profiles = UserProfile::select('user_profiles.*')->get();

        $uhid = 1;

        foreach ($profiles as $profile) {
            $profile->update([
                'uhid' => User::getGenralId('', $uhid)
            ]);
            $uhid++;
        }
        return 'done';
    }

    public function exportPending(Request $request, $type, $per_page = 10, $page = 1)
    {
        Laralum::permissionToAccess(['admin.future_patients_management']);
        $matchThese = [];
        $search = false;
        $option_ar = [];
        $matchTheseAddress = [];

        if ($request->s && $request->s != 'null') {
            $search_data = json_decode($request->s, true);

            if (!empty($search_data['kid'])) {
                $option_ar[] = "Registration Id";
                $search = true;
                $search = true;
                $matchThese['kid'] = $search_data['kid'];
            }
            $uhid = '';
            if (!empty($search_data['uhid'])) {
                $option_ar[] = "UHID";
                $search = true;
                $search = true;
                $uhid = $search_data['uhid'];
            }
            $filter_name = "";
            if (!empty($search_data['first_name'])) {
                $option_ar[] = "First Name";
                $search = true;
                //$matchThese['first_name'] = $request->get('first_name');
                $filter_name = $search_data['first_name'];
            }

            if (!empty($search_data['filter_last_name'])) {
                $option_ar[] = "Last Name";
                $search = true;

                $matchThese['last_name'] = $search_data['filter_last_name'];
            }
            $filter_mobile = "";
            if (!empty($search_data['mobile'])) {
                $option_ar[] = "Mobile";
                $search = true;
                $filter_mobile = $search_data['mobile'];
                $matchThese['mobile'] = $search_data['mobile'];
            }

            $filter_type = "";
            if (!empty($search_data['filter_patient_type'])) {
                $option_ar[] = "Patient Type";
                $search = true;
                $filter_type = $search_data['filter_patient_type'];
                $matchThese['patient_type'] = $search_data['filter_patient_type'];
            }

            $address_string = '';
            if (!empty($search_data['city'])) {
                $option_ar[] = "City";
                $search = true;
                $address_array = explode(',', $search_data['city']);
                $address_string = $search_data['city'];
                $matchTheseAddress['city'] = $address_array[0];
                if (isset($address_array[1])) {
                    $matchTheseAddress['state'] = $address_array[1];
                }

                if (isset($address_array[2])) {
                    $matchTheseAddress['country'] = $address_array[2];
                }

            }

            $filter_accommodation_staus = "";
            if (!empty($search_data['filter_accommodation_staus'])) {
                $option_ar[] = "Accommodation Status";
                $search = true;
                $filter_accommodation_staus = $search_data['filter_accommodation_staus'];
            }

            /* $filter_name = "";
             if ($request->has('filter_name') && $request->get('filter_name') != "") {
                 $option_ar[] = "Name";
                 $search = true;
                 $filter_name = $request->get('filter_name');
             }*/

            $filter_email = "";
            if (!empty($search_data['filter_email'])) {
                $option_ar[] = "Email";
                $search = true;
                $filter_email = $search_data['filter_email'];
            }
        }
        $options = implode(", ", $option_ar);

        $error = "Entered " . $options . " is not valid,
make sure that you are entering valid " . $options . " 
or search by other options";
        $user = [];
        $per_page = $request->get("per_page") ? $request->get("per_page") : 10;
        $pagination = true;
        if ($per_page == "All") {
            $pagination = false;
        }
        if (\Auth::user()->isDoctor()) {
            $models_query = Booking::select('bookings.*')->where('bookings.check_in_date', '<=', date('Y-m-d h:i:s'))->join('users', 'users.id', '=', 'bookings.user_id')->leftjoin('user_profiles', 'user_profiles.id', 'bookings.profile_id')->orderBy('bookings.created_at', 'DESC');
        } else {
            $models_query = Booking::select('bookings.*')->join('users', 'users.id', '=', 'bookings.user_id')->leftjoin('user_profiles', 'user_profiles.id', 'bookings.profile_id')->orderBy('bookings.created_at', 'DESC');
        }

        $models_query = $models_query->whereIn('status', [Booking::STATUS_PENDING]);

        // echo '<pre>'; print_r($models_query->get());exit;

        $malefemale_query = clone $models_query;

        $models = clone $malefemale_query;

        $males = $models_query->where('user_profiles.gender', UserProfile::GENDER_MALE)->count();
        // print_r($males);exit;
        $females = $malefemale_query->where('user_profiles.gender', UserProfile::GENDER_FEMALE)->count();
        //  print_r($models->count());exit;
//print_r($matchThese);
        if ($search == true) {
            $models_query = Booking::select('bookings.*')->join('users', 'users.id', '=', 'bookings.user_id')->leftjoin('user_profiles', 'user_profiles.id', 'bookings.profile_id')->leftJoin('user_addresses', 'user_addresses.profile_id', '=', 'bookings.profile_id')->where(function ($query) use ($matchThese, $filter_email, $filter_name, $matchTheseAddress, $uhid) {
                foreach ($matchThese as $key => $match) {
                    $query->where('user_profiles.' . $key, 'like', "%$match%");
                }
                foreach ($matchTheseAddress as $key => $match) {
                    $query->where('user_addresses.' . $key, 'like', "%$match%");
                }
                if ($filter_email != "") {
                    $query->where('users.email', 'like', "%$filter_email%");
                }
                if ($uhid != "") {
                    $query->where('users.uhid', $uhid);
                }
                if ($filter_name != "") {
                    $query->where(function ($q) use ($filter_name) {
                        $q->WhereRaw("concat(user_profiles.first_name, ' ', user_profiles.last_name) like '%$filter_name%' ")->orWhere("users.name", 'like', "%$filter_name%");
                    });
                }
            })/*->where('users.email', 'like', "%" . $filter_email . "%")->where('users.name', 'like', "%" . $filter_name . "%")->where('user_profiles.mobile', 'like', "%" . $filter_mobile . "%")*/
            ->orderBy('bookings.created_at', 'DESC');

            $models_query = $models_query->whereIn('status', [Booking::STATUS_PENDING]);

            $models_query = $models_query->whereIn('status', [Booking::STATUS_PENDING]);

            // echo '<pre>'; print_r($models_query->get());exit;

            $malefemale_query = clone $models_query;

            $models = clone $malefemale_query;

            $males = $models_query->where('user_profiles.gender', UserProfile::GENDER_MALE)->count();
            // print_r($males);exit;
            $females = $malefemale_query->where('user_profiles.gender', UserProfile::GENDER_FEMALE)->count();

            //  echo '<pre>'; print_r($models->get()->toArray());exit;

            //->where('check_in_date', '>', date("Y-m-d H:i:s"));
            if(!empty($filter_name)) {
                $matchThese['first_name'] = $filter_name;
            }
            if(!empty($search_data['uhid'])) {
                $matchThese['uhid'] = $search_data['uhid'];
            }
            if(!empty($address_string)) {
                $matchTheseAddress['city'] = $address_string;
            }
        }

        if ($pagination == true) {
            $count = $models->count();
            $models = $models->paginate($per_page);
        } else {
            $count = $models->count();
            $models = $models->get();
        }

        $bookings_array[] = [
            'UHID',
            'Patient Name',
            'Contact No. ',
            'City, State, Country',
            'Booking Status',
        ];

        foreach ($models as $booking) {
            $status = $booking->status != null ? Booking::getStatusOptions($booking->status) : Booking::getStatusOptions(Booking::STATUS_PENDING);
            $bookings_array[] = [
                (string)$booking->getProfile('uhid'),
                $booking->getProfile('first_name') . ' ' . $booking->getProfile('last_name'),
                $booking->getProfile('mobile') ? $booking->getProfile('mobile') : "",
                $booking->getAddress('city') ? $booking->getAddress('city') . ',' . $booking->getAddress('state') . ',' . $booking->getAddress('country') : "",
                $status
            ];
        }


        //return $bookings_array;

        // Generate and return the spreadsheet
        $excel = \App::make('excel');
        $excel = $excel->create('Bookings', function ($excel) use ($bookings_array) {

            // Set the spreadsheet title, creator, and description
            $excel->setTitle('Pending Bookings');

            // Build the spreadsheet, passing in the payments array
            $excel->sheet('sheet1', function ($sheet) use ($bookings_array) {
                $sheet->fromArray($bookings_array, null, 'A1', false, false);
            });

        });
        if ($type == Settings::EXPORT_CSV) {
            $excel->download('csv');
        } elseif ($type == Settings::EXPORT_EXCEL) {
            $excel->download('xls');
        } else {
            $pdf = PDF::loadView('booking.pdf', array('data' => $bookings_array));
            return $pdf->download('pending_bookings.pdf');
            // $pdf->setPaper('A4', 'landscape');
            // $pdf->getMpdf()->AddPage(...);
            // $excel->download('pdf');
        }

        return redirect()->back()->with('error', 'Something went Wrong!!!');
    }

    public function deleteMember(Request $request)
    {
        Laralum::permissionToAccess(['admin.patients.list', 'doctor.patients', 'admin.bookings.list']);
        try {
            $id = $request->get('id');
            $member = Member::find($id);
            $user_id = $request->get('user_id');

            $member->customDelete();
            return ['success' => true, 'id' => $id];
        } catch (\Exception $e) {
            return ['success' => false];
        }

    }

public function updateallBooking() {

$bookings = Booking::whereNull('booking_kid')->where('status', 2)->where('patient_type', '=', UserProfile::PATIENT_TYPE_OPD)->orderBy('id', 'asc')->get();
foreach($bookings as $booking) {
	     $booking->update([
                 'booking_kid' => User::getId("K-OPD", $booking->getKIdNumber())
             ]);
           }
}


 public function saveMisc(Request $request)
    {
        Laralum::permissionToAccess(['admin.patients.list', 'doctor.patients', 'admin.bookings.list']);
       // try {
            $id = $request->get('booking_id');
            $misc = Misc::where('booking_id',$id)->first();
	$discharge = false;
		$booking = Booking::find($id);
$discount = false;
		$user = $booking->user;
	   if ($misc) {
		$misc->update([ 
			'price' => $request->price
			]);
		}else{
		$misc = new Misc();
		$misc->booking_id = $id;
		$misc->price = $request->price;
		$misc->created_by = \Auth::user()->id;
		$misc->save();
		}
return ['success' => true];

  // } catch (\Exception $e) {
      //     return ['success' => false];
     //   }

    }

}

