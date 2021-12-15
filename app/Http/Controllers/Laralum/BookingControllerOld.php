<?php

namespace App\Http\Controllers\Laralum;;

use App\Booking;
use App\BookingRoom;
use App\DietChart;
use App\DietChartItems;
use App\DietDailyStatus;
use App\DischargePatient;
use App\ExternalService;
use App\Feedback;
use App\FeedbackQuestion;
use App\HealthIssue;
use App\Member;
use App\Notification;
use App\OrderItem;
use App\PatientFollowUp;
use App\PatientToken;
use App\PatientTreatment;
use App\Profession;
use App\Role;
use App\Room;
use App\Settings;
use App\Transaction;
use App\TreatmentToken;
use App\User;
use App\UserProfile;
use App\Wallet;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Laralum;
use Milon\Barcode\DNS1D;
use Milon\Barcode\DNS2D;
use SebastianBergmann\Comparator\Book;

class BookingControllerold extends Controller
{
    /**
     * get all resource listing
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */

    public function index(Request $request)
    {
        $matchThese =   [];
        $search = false;
        $option_ar = [];
        if ($request->has('filter_patient_id') && $request->get('filter_patient_id') != ""){
            $option_ar[] = "Patient Id";
            $search = true;
            $matchThese['kid'] = $request->get('filter_patient_id');
        }
        if ($request->has('filter_first_name') && $request->get('filter_first_name') != ""){
            $option_ar[] = "First Name";
            $search = true;
            $matchThese['first_name'] = $request->get('filter_first_name');
        }

        if ($request->has('filter_last_name') && $request->get('filter_last_name') != ""){
            $option_ar[] = "Last Name";
            $search = true;
            $matchThese['last_name'] = $request->get('filter_last_name');
        }
        $filter_mobile  = "";
        if ($request->has('filter_mobile') && $request->get('filter_mobile') != ""){
            $option_ar[] = "Mobile";
            $search = true;
            $filter_mobile = $request->get('filter_mobile');
            $matchThese['mobile'] = $request->get('filter_mobile');
        }

        $filter_type = "";
        if ($request->has('filter_patient_type') && $request->get('filter_patient_type') != ""){
            $option_ar[] = "Patient Type";
            $search = true;
            $filter_type = $request->get('filter_patient_type');
        }
        $filter_name = "";
        if ($request->has('filter_name') && $request->get('filter_name') != ""){
            $option_ar[] = "Name";
            $search = true;
            $filter_name = $request->get('filter_name');
        }

        $filter_email = "";

        if ($request->has('filter_email')){
            $option_ar[] = "Email";
            $search = true;
            $filter_email = $request->get('filter_email');
        }
        $options = implode(", ", $option_ar);

        $error = "Entered ".$options." is not valid,
make sure that you are entering valid ".$options." 
or search by other options";
        $user = [];
        $per_page = $request->get("per_page") ? $request->get("per_page") : 10;
        $pagination = true;
        if ($per_page == "All") {
            $pagination = false;
        }

        $models = Booking::select('bookings.*')->where('status', Booking::STATUS_COMPLETED)->join('users', 'users.id', '=', 'bookings.user_id')->join('user_profiles', 'user_profiles.user_id', 'bookings.user_id')->orderBy('bookings.created_at', 'DESC');

        if ($search == true) {
            $models = Booking::select('bookings.*')->where('status', Booking::STATUS_COMPLETED)->join('users', 'users.id', '=', 'bookings.user_id')->join('user_profiles', 'user_profiles.user_id', 'bookings.user_id')->where('users.email','like', "%".$filter_email."%")->where('users.name','like', "%".$filter_name."%")->where('user_profiles.mobile','like', "%".$filter_mobile."%")->orderBy('bookings.created_at', 'DESC');
            if ($filter_type != "") {
                $models = $models->where('bookings.patient_type', $filter_type);
            }
        }

        if ($pagination == true) {
            $count = $models->count();
            $models = $models->paginate($per_page);
        }else{
            $count = $models->count();
            $models = $models->get();
        }

        Notification::updateNotification(User::class);
        return view('laralum.booking.index',compact('models', 'search', 'error', 'count'));

    }

    public function indexOld(Request $request)
    {
        $matchThese =   [];
        $search = false;
        $option_ar = [];
        if ($request->has('filter_patient_id') && $request->get('filter_patient_id') != ""){
            $option_ar[] = "Patient Id";
            $search = true;
            $matchThese['kid'] = $request->get('filter_patient_id');
        }
        if ($request->has('filter_first_name') && $request->get('filter_first_name') != ""){
            $option_ar[] = "First Name";
            $search = true;
            $matchThese['first_name'] = $request->get('filter_first_name');
        }

        if ($request->has('filter_last_name') && $request->get('filter_last_name') != ""){
            $option_ar[] = "Last Name";
            $search = true;
            $matchThese['last_name'] = $request->get('filter_last_name');
        }
        if ($request->has('filter_mobile') && $request->get('filter_mobile') != ""){
            $option_ar[] = "Mobile";
            $search = true;
            $matchThese['mobile'] = $request->get('filter_mobile');
        }

        $filter_email = "";

        if ($request->has('filter_email')){
            $option_ar[] = "Email";
            $search = true;
            $filter_email = $request->get('filter_email');
        }
        $options = implode(", ", $option_ar);

        $error = "Entered ".$options." is not valid,
make sure that you are entering valid ".$options." 
or search by other options";
        $user = [];
        $per_page = $request->get("per_page") ? $request->get("per_page") : 10;
        $pagination = true;
        if ($per_page == "All") {
            $pagination = false;
        }

        if ($search == true) {
            $bookings = User::select('users.*')->with(['userProfile', 'address'])
                ->join('user_addresses', 'user_addresses.user_id', '=', 'users.id')
                            ->join('user_profiles', 'user_profiles.user_id', '=', 'users.id')
                            ->join('transactions', 'transactions.user_id', '=', 'users.id')
                            ->where(function($query) use ($matchThese,$filter_email) {
                                foreach($matchThese as $key=>$match){
                                    $query->where('user_profiles.'.$key,'like',"%$match%");
                                }
                                if($filter_email != "") {
                                    $query->where('users.email', 'like', "%$filter_email%");
                                }
                            })->join('role_user','role_user.user_id', 'users.id')->where('role_user.role_id', Role::getPatientId())->orderBY('users.created_at', 'DESc')->whereNotIn('transactions.status', [Transaction::STATUS_DISCHARGED, Transaction::STATUS_CANCELLED])/*->where('users.active', 1)*/;
                              if ($pagination == true) {
                                  $booking_count = $bookings->count();
                                  $bookings = $bookings->paginate($per_page);
                              }else{
                                  $booking_count = $bookings->count();
                                  $bookings = $bookings->get();
                              }

        }else{
            $bookings   =  User::select('users.*')->with(['userProfile', 'address'])
                ->join('transactions', 'transactions.user_id', '=', 'users.id')
                ->join('user_addresses', 'user_addresses.user_id', '=', 'users.id')
                ->join('user_profiles', 'user_profiles.user_id', '=', 'users.id')->join('role_user','role_user.user_id', 'users.id')->where('role_user.role_id', Role::getPatientId())->whereNotIn('transactions.status', [Transaction::STATUS_DISCHARGED, Transaction::STATUS_CANCELLED])/*->where('active', 1)*/->orderBY('users.created_at', 'DESC');
            if ($pagination == true) {
                $booking_count = $bookings->count();
                $bookings = $bookings->paginate($per_page);
            }else{
                $booking_count = $bookings->count();
                $bookings = $bookings->get();
            }
        }

        Notification::updateNotification(User::class);

        return view('laralum.booking.index',compact('bookings', 'search', 'error', 'booking_count'));
    }

    /**
     * show resource in details
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show($id)
    {
        $booking = Booking::find($id);
        $user = $booking->user;
        return view('laralum.booking.view',compact('booking', 'user'));
    }

    public function create($id = null) {
        $user = new User();

        if ($id != null) {
            $user = User::find($id);
        }

        return view('laralum.booking.signup',compact('admin','user'));
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
                try{

                    $user->SendActivationEmail(); // send activation mail to user

                }catch(\Exception $e){
                    Log::error("Failed to send account activation mail, possible causes: ".$e->getMessage());
                }

                return redirect()->route('Laralum::booking.personalDetails', ['user_id' => $user->id])->with(['status' => 'success', 'message' => 'Signup is completed successfully, please fill personal details now.']);
            }

            return redirect()->back()->with(['status' => 'danger', 'message' => 'Something went wrong .']);

        } catch (\Exception $e) {

            print_r($e->getMessage());exit;
            Log::error("Failed to add the user data during booking process. Possible causes: " . $e->getMessage());
            return redirect()->back()->with(['status' => 'danger', 'message' => 'Failed to process your request. Please try again later.']);
        }
    }

    public function personalDetails($id = null)
    {
        if($id != null){
            $user = User::find($id);
            if ($user != null) {
                $profile = $user->getUserProfile();
                $address = $user->getAddress();
                $countries = Laralum::countries();
                $no_flags = Laralum::noFlags();
                $booking = $user->getBooking();
                return view('laralum.booking.personal-details', compact('user', 'profile', 'address', 'no_flags','countries', 'booking'));
            }
        }
        return redirect()->route('Laralum::booking.create');
    }

    public function personalDetailsStore(Request $request, $user_id)
    {
       // try {
        
            $user = User::find($user_id);
            /* add user profile */
            $userProfile = $user->getUserProfile();

            $userProfileData = $request->get('userProfile');
            $userProfileData['file'] = $request->file('profile_picture');

            if ($userProfile->patient_type == UserProfile::PATIENT_TYPE_IPD && $userProfileData['patient_type'] == UserProfile::PATIENT_TYPE_OPD) {
                $booking = Booking::where('user_id', $userProfile->user_id)->where('status', Booking::STATUS_PENDING)->first();
                $booking->delete();
                $transaction = Transaction::where('user_id', $userProfile->user_id)->where('status', Transaction::STATUS_COMPLETED)->first();
                if ($transaction->booking != null) {
                    $transaction->booking->update([
                        'status' => Booking::STATUS_CANCELLED
                    ]);
                    $amount_paid = $transaction->getPaidAmount();
                    $payable_amount = $transaction->amount - $transaction->booking->daysPrice() - $transaction->discount_amount;
                    $total_payable = $amount_paid - $payable_amount;
                    if ($total_payable > 0) {
                        Wallet::create([
                            'user_id' => $transaction->user_id,
                            'amount' => $total_payable,
                            'type' => Wallet::TYPE_REFUND,
                            'created_by' => \Auth::user()->id,
                            'model_id' => $transaction->id,
                            'model_type' => get_class($transaction),
                        ]);
                    }
                }
            }

            if ($userProfile->setData($userProfileData, $user_id)) {
                if (isset($request->get('userProfile')['profession_name'])) {
                    if ($request->get('userProfile')['profession_name']) {
                        $profession = Profession::create([
                            'name' => $request->get('userProfile')['profession_name'],
                            'slug' => str_slug($request->get('userProfile')['profession_name']),
                            'is_private' => Profession::IS_PRIVATE
                        ]);
                        $userProfile->profession_id = $profession->id;
                        $userProfile->save();
                    }
                }
                $userProfile->save();
                $userProfile->saveDocuments($request);
                \Session::put('profile_id', $userProfile->id);
            }

            /* add user address */
            $userAddress = $user->getAddress();
            $userAddressData = $request->get('userAddress');

            if ($userAddress->setData($userAddressData, $user_id)) {
                $userAddress->save();
            }
            if (\Auth::user()->isPatient())
                return redirect()->route('Laralum::user.booking.health_issues', ['user_id' => $user_id]);

            return redirect()->route('Laralum::booking.health_issues', ['user_id' => $user_id]);
        /*} catch (\Exception $e) {

            Log::error("Failed to add the personal details during booking process. Possible causes: " . $e->getMessage());
            return redirect()->back()->withInput()->with(['status' => 'danger', 'message' => 'Something went wrong .']);
        }*/

        return view('laralum.booking.personal-details');
    }

    public function healthIssues($id = null)
    {
        if ($id != null) {
            $user = User::find($id);
            $profile = $user->getUserProfile();
            if ($user != null) {
                $booking = $user->getBooking();
                return view('laralum.booking.health_issues', compact('user', 'profile', 'booking'));
            }
        }

        return redirect()->route('Laralum::booking.personalDetails', ['user_id' => $id]);
    }

    public function healthIssuesStore(Request $request, $user_id)
    {
        $user = User::find($user_id);
        if(!empty($request->all())){
            if(isset($request->health_issues) && !empty($request->health_issues)) {
                try {

                    $profile = $user->getUserProfile();
                    $profile->update(['health_issues' => $request->health_issues]);
                } catch (\Exception $e) {
                    Log::error("Failed to add the health issues during booking process. Possible causes: " . $e->getMessage());
                    return redirect()->back()->with(['status' => 'danger', 'message' => 'Something went wrong .']);
                }
                if ($user->checkAccommodation()) {
                    if (\Auth::user()->isPatient())
                        return redirect()->route('Laralum::user.booking.accommodation', ['user_id' => $user_id]);
                    return redirect()->route('Laralum::booking.accommodation', ['user_id' => $user_id]);
                } else {
                    if (\Auth::user()->isPatient())
                        return redirect()->route('Laralum::user.booking.aggreement', ['user_id' => $user_id]);
                    return redirect()->route('Laralum::booking.aggreement', ['user_id' => $user_id]);
                }
            }
        }

        return redirect()->back()->with('error', 'Please input health issues');
    }

    public function userAccommodation($id)
    {
        $user = User::find($id);
        $booking = Booking::where([
            'user_id' => $id
        ])->whereIn('status', [Booking::STATUS_COMPLETED, Booking::STATUS_PENDING])->first();

        if($booking == null) {
            $booking = new Booking();
        }

        $data['booking'] = $booking;
        $data['user'] = $user;
        return view('laralum.booking.user-accommodation', $data);
    }

    public function userAccommodationStore(Request $request, $id)
    {
        $user = User::find($id);
        $model = Booking::where([
            'user_id' => $id
        ])->whereIn('status', [Booking::STATUS_COMPLETED, Booking::STATUS_PENDING])->first();

        if($model == null) {
            $model = new Booking();
        }

        $validator = \Validator::make($request->all(), $model->rules());

        if($validator->fails()){
            return redirect()->back()->withInput()->withErrors($validator->errors());
        }

        $model->setData($request);
        $check = $model->checkBooking();
        if ($check == true) {
            $model->save();
            if (\Auth::user()->isPatient())
                return redirect(route('Laralum::user.booking.confirm', ['user_id' => $id]))->with('success', 'Booking has been completed successfully.');

            return redirect(route('Laralum::booking.confirm', ['user_id' => $id]))->with('success', 'Booking has been completed successfully.');
        }

        return redirect()->back()->withInput()->with('error', "No Room Available for these dates");
    }

    public function accommodation(Request $request, $id)
    {
        if ($id != null) {
            $user = User::find($id);
            $profile = $user->userProfile;
            if ($user != null) {
                if ($profile != null) {
                    if ($profile->health_issues != null) {
                        // call month/period wise data

                        $data['user_id'] = $id;
                        // call room wise data
                        $data['user'] = $user;
                        $booking = new Booking();
                        if ($user->getBooking()){
                            $booking = $user->getBooking();
                        }
                        $previousBooking = null;
                        if ($user->previousBooking()){
                            $previousBooking = $user->previousBooking();
                        }
                        if ($booking->check_in_date == "" || $booking->check_in_date < date('Y-m-d H:i:s'))
                            $booking->check_in_date = date('Y-m-d 00:00:00');

                        $default_date = date('Y-m-d', strtotime($booking->check_in_date));

                        $default_month_year = date("m-Y", strtotime($booking->check_in_date));
                        $month_wise_arr = Booking::guestBookingChartmw($request, $default_month_year);
                        $data['rooms_status_arr'] = $month_wise_arr['rooms_status_arr'];
                        $data['accordian_status_mw'] = $month_wise_arr['accordian_status_mw'];
                        $room_wise_arr = Booking::guestBookingChart($request, $default_date);
                        
                        $data = array_merge($data, $room_wise_arr);
                        $members = [];
                        $data['booking'] = $booking;
                        if ($booking->members->count() > 0) {
                            $members = $booking->members;
                        }
                        $data['members'] = $members;
                        $data['previousBooking'] = $previousBooking;
                        $data['default_date'] = $default_date;
                        $filter_date = false;
                        if($request->get('filter_date')) {
                            $filter_date = true;
                        }

                        $filter_month = false;
                        if($request->get('filter_month')) {
                            $filter_month = true;
                        }
                        $data['filter_date'] = $filter_date;
                        $data['filter_month'] = $filter_month;
                        //echo '<pre>'; print_r($data); die;
                        return view('laralum.booking.accommodation', $data);
                    } else {
                        return redirect()->route('Laralum::booking.health_issues', ['user_id' => $id]);
                    }
                } else {
                    return redirect()->route('Laralum::booking.personalDetails', ['user_id' => $id]);
                }
            }
        }
        return redirect()->route('guest.booking.signup');
    }

    public function accommodationRequest(Request $request, $id)
    {
        $model = Booking::where('user_id', $id)->where([
            'status' => Booking::STATUS_PENDING
        ])->first();

        if ($model == null) {
            $model = new Booking();
        }

        $validator = \Validator::make($request->all(), $model->rules());

        if($validator->fails()){
            return redirect()->back()->withInput()->withErrors($validator->errors());
        }

        $model->setData($request);
        $check = $model->checkBooking();
        if ($check == true) {
            $model->save();
            $model->saveMembers($request);
            \Session::put('booking_id', $model->id);
            return redirect(route('Laralum::booking.payment', ['user_id' => $id]))->with('success', 'Booking has been completed successfully.');
        }

        return redirect()->back()->withInput()->with('error', "No Room Available for these dates");
    }

    public function getBookingInfo($booking_ids,$room_id, $user_id)
    {
        $room = Room::find($room_id);
        $members = Member::select('members.*')->where('room_id', $room_id)->whereNotIn('status', [Booking::STATUS_DISCHARGED, Booking::STATUS_CANCELLED])->get();
        $data = [];
        foreach ($members as $member) {
            $data[] = [
                'name' => $member->name,
                'age' => $member->age,
                'gender' => $member->getGenderOptions($member->gender),
                'check_in_date' => $member->check_in_date,
                'check_out_date' => $member->check_out_date,
                'booked_by' => $member->booking->user->name."(". $member->booking->user->userProfile->kid.")",
                'room_number' => $room->room_number,
                'floor_number' => $room->getFloorNumber($room->floor_number),
                'booking_type' => Booking::getBookingType(Booking::BOOKING_TYPE_SINGLE_BED),
                'days_price' => $member->daysPrice(null, false),
                'services' => $member->getServices()
                ];
        }

        $bookings = Booking::where('room_id', $room_id)->whereNotIn('status', [Booking::STATUS_DISCHARGED, Booking::STATUS_CANCELLED])->get();
        
        foreach ($bookings as $booking) {
            $data[] = [
                'name' => $booking->user->name,
                'age' => $booking->user->userProfile->getAge(),
                'gender' => $booking->user->userProfile->getGenderOptions($booking->user->userProfile->gender),
                'check_in_date' => $booking->check_in_date,
                'check_out_date' => $booking->check_out_date,
                'booked_by' => $booking->user->name."(". $booking->user->userProfile->kid.")",
                'room_number' => $room->room_number,
                'floor_number' => $room->getFloorNumber($room->floor_number),
                'booking_type' => Booking::getBookingType($booking->booking_type),
                'days_price' => $booking->daysPrice(null, false),
                'services' => $booking->getServices()
            ];
        }
        /*echo '<pre>'; print_r($data);exit;*/
       /* foreach ($data as $d) {
            echo '<pre>'; print_r($d['name']);
        }exit;*/
        $booking_id_arr = explode('-',$booking_ids);
        $booked_info = Booking::join('rooms', 'rooms.id', '=', 'bookings.room_id')
            ->join('user_profiles', 'bookings.user_id', '=', 'user_profiles.user_id')
            ->whereIN('bookings.id',$booking_id_arr)
            ->select('rooms.*','rooms.id as room_id', 'bookings.*','user_profiles.first_name','user_profiles.last_name')
            ->get();

        $data['booked_info'] = $booked_info;
        $data['data'] = array_filter($data);
       /* if($booked_info->count() == 1) {
            foreach ($booked_info as $booked) {
                if($booked->user_id == $user_id) {
                    return $this->accommBookingForm($user_id, $room_id, $booking_ids);
                }
            }
        }*/
        // $data['user_info'] = $user_info;
        return view('laralum.booking.get-booking-info',$data);
    }

    public function accommBookingForm($user_id, $room_id,$booking_ids = null, $member_id = null){
        /*$user_id = \Session::get('user_id');*/
        $user_info = [];
        $external_services = [];
        if(is_numeric($user_id)){
            $user_obj = User::find($user_id);
        }
        $external_services_obj = ExternalService::getServices($room_id);
        if($external_services_obj != null){
            foreach ($external_services_obj as $ext_service){
                $external_services[$ext_service->id] = $ext_service->name;
            }
        }
        $room_data = [];
        $room_obj = \DB::table('rooms')
            ->join('room_types', 'rooms.room_type_id', '=', 'room_types.id')
            ->join('buildings', 'rooms.building_id', '=', 'buildings.id')
            ->where('rooms.id',$room_id)
            ->select('rooms.room_number','room_types.name as room_type','rooms.room_number','buildings.name as building_name')
            ->first();
        $room = Room::find($room_id);
        if(!empty($room_obj)){
            $room_data['room_id'] = $room->id;
            $room_data['room_number'] = $room_obj->room_number;
            $room_data['room_type'] = $room_obj->room_type;
            $room_data['room_number'] = $room_obj->room_number;
            $room_data['room_price'] = $room->roomType->price;
            $room_data['building'] = $room_obj->building_name;
        }
        // partial booking
        if(!empty($booking_ids)) {
            $booking_id_arr = explode('-', $booking_ids);
            $booked_info = Booking::join('rooms', 'rooms.id', '=', 'bookings.room_id')
                ->join('user_profiles', 'bookings.user_id', '=', 'user_profiles.user_id')
                ->whereIN('bookings.id', $booking_id_arr)
                ->select('bookings.*', 'rooms.*', 'rooms.id as room_id', 'bookings.*', 'user_profiles.first_name', 'user_profiles.last_name')
                ->get();

            $data['booked_info'] = $booked_info;
        }
        else {
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

        return view('laralum.booking.accommodation-booking-form',$data);
    }
    public function accommBookingFormStore(Request $request, $id)
    {
        $data['booked_info'] = [];
        $user = User::find($id);
        $member = Member::find($request->get('member_id'));

        $booking = Booking::where('user_id', $id)->where('status', Booking::STATUS_COMPLETED)->orderBy('created_at', 'DESC')->first();
        if ($booking != null) {
            $member_id = null;
            if ($member == null) {
                $booking->room_id = $request->get('room_id');
                $booking->booking_type = $request->get('booking_type');
                $booking->check_in_date = $request->get('check_in_date');
                $booking->check_out_date = $request->get('check_out_date');
                $booking->save();

            } else {
                $member_id = $member->id;
                $member->room_id = $request->get('room_id');
                $member->check_in_date = $request->get('check_in_date');
                $member->check_out_date = $request->get('check_out_date');
                $member->type = $request->get('booking_type');
                $member->save();
            }
            $booking->deleteServices($member_id);
            $booking->saveServices($request->get('external_services'), $member_id);
        }

        return redirect(route('Laralum::booking.allot.rooms', ['user_id' => $booking->id]))->with('success', 'Booking has been completed successfully.');
        // return view('booking.booking_chart_room_wise',$data);
    }


    public function payment($id)
    {
        if ($id != null) {
            $user = User::find($id);
            $profile = $user->userProfile;
            $booking = Booking::where('user_id', $user->id)->whereIn('status', [Booking::STATUS_COMPLETED, Booking::STATUS_PENDING])->first();
            if ($user != null) {
                if ($profile != null) {
                    if ($profile->health_issues != null) {
                        if ($user->checkAccommodation($id)) {
                            if ($booking != null) {
                                return view('laralum.booking.payment', compact('user', 'booking'));
                            }
                            return redirect()->route('Laralum::booking.accommodation', ['user_id' => $id]);
                        }
                        return view('laralum.booking.payment', compact('user'));
                    }
                    return redirect()->route('Laralum::booking.health_issues', ['user_id' => $id]);
                }
                return redirect()->route('Laralum::booking.personalDetails', ['user_id' => $id]);
            }
        }
        return redirect()->route('Laralum::booking.create');
    }

    public function paymentStore(Request $request, $id)
    {
        \Session::put('payment_method', $request->get('payment_method'));
        if(\Auth::user()->isPatient())
            return redirect()->route('Laralum::user.booking.confirm', ['user_id'=> $id]);
        return redirect()->route('Laralum::booking.confirm', ['user_id'=> $id]);
    }

    public function confirm($id)
    {
        if ($id != null) {
            $user = User::find($id);

            $profile = $user->userProfile;
            $booking = Booking::where('user_id', $user->id)->whereIn('status', [Booking::STATUS_COMPLETED, Booking::STATUS_PENDING])->first();
            if ($user != null) {
                if ($profile != null) {
                    if ($profile->health_issues != null) {
                        if ($user->checkAccommodation($id)) {
                            if ($booking != null) {
                                if ($booking == null) {
                                    $booking = new Booking();
                                }

                                $healthIssues = HealthIssue::where([
                                    'user_id' => $user->id,
                                    'status' => HealthIssue::STATUS_PENDING
                                ])->first();

                                if ($healthIssues == null) {
                                    $healthIssues = new HealthIssue();
                                }
                                if (\Session::has("payment_method")) {
                                    return view('laralum.booking.confirm', compact('user', 'booking', 'healthIssues'));
                                }
                                if (\Auth::user()->isPatient())
                                    return redirect()->route('Laralum::user.booking.payment', ['user_id' =>$id]);
                                return redirect()->route('Laralum::booking.payment', ['user_id' =>$id]);
                            }
                            if (\Auth::user()->isPatient())
                                return redirect()->route('Laralum::user.booking.accommodation', ['user_id' => $id]);
                            return redirect()->route('Laralum::booking.accommodation', ['user_id' => $id]);
                        }
                        if (\Session::has("payment_method") || $user->getTransaction() != null)
                            return view('laralum.booking.confirm', compact('user', 'booking', 'healthIssues'));
                        if (\Auth::user()->isPatient())
                            return redirect()->route('Laralum::user.booking.payment', ['user_id' => $id]);
                        return redirect()->route('Laralum::booking.payment', ['user_id' =>$id]);
                    }
                    if (\Auth::user()->isPatient())
                        return redirect()->route('Laralum::user.booking.health_issues', ['user_id' => $id]);
                    return redirect()->route('Laralum::booking.health_issues', ['user_id' => $id]);
                }
                if (\Auth::user()->isPatient())
                    return redirect()->route('Laralum::user.booking.personalDetails', ['user_id' => $id]);
                return redirect()->route('Laralum::booking.personalDetails', ['user_id' => $id]);
            }
        }
        return redirect()->route('Laralum::booking.create');
    }

    public function confirmStore(Request $request, $id)
    {
        $user = User::find($id);
        $booking = Booking::where([
            'user_id' => $user->id,
            'status' => Booking::STATUS_PENDING
        ])->first();

        if ($booking == null) {
            $booking = Booking::create([
                'user_id' => $user->id,
                'status' => Booking::STATUS_COMPLETED,
                'patient_type' => Booking::PATIENT_TYPE_OPD,

            ]);
        }

        $booking->update([
            'booking_id' => User::getId("B", $booking->id),
            'status' => Booking::STATUS_COMPLETED
        ]);
        $healthIssues = HealthIssue::where([
            'user_id' => $user->id,
            'status' => HealthIssue::STATUS_PENDING
        ])->first();

        if ($healthIssues == null) {
            $healthIssues = new HealthIssue();
        }

        $healthIssues->user_id = $user->id;
        $healthIssues->status = HealthIssue::STATUS_COMPLETED;
        $healthIssues->booking_id = $booking->id;
        $healthIssues->save();

        $user->userProfile->update([
            'kid' => User::getId("K", $user->id)
        ]);

        Notification::saveNotification($user,"New Booking", $user->id);
        $user->sendActivationEmail();
        $data['name'] = $user->name;
        $data['email'] = $user->email;
        $data['booking_id'] = $booking->booking_id;
        $data['patient_id'] = $booking->patient_id;
        $data['registration_id'] = $user->registration_id;
        $data['patient_id'] = $user->userProfile->kid;
        $data['dates'] = $booking->check_in_date." - ".$booking->check_out_date;
        //EmailTemplate::sendEmail(EmailTemplate::EVENT_BOOKING, $data, $user->email);
        if (\Auth::user()->isPatient())
            return redirect()->route('Laralum::user.booking-detail')->with(['status' => 'success', 'message' => 'Booking has been completed successfully.']);

        return redirect()->route('Laralum::bookings.print_kid',['booking_id' => $id])->with(['status' => 'success', 'message' => 'Booking has been completed successfully.']);
    }


    public function confirmStoreOld(Request $request, $id)
    {
        $user = User::find($id);
        $transaction = Transaction::where([
            'user_id' => $user->id,
            'status' => Transaction::STATUS_COMPLETED
            ])->orderBy('created_at', 'DESC')->first();
        $am = false;
        $booking_id = $user->getBookingId();

        $patient_id = $user->userProfile->kid;
        if ($patient_id == null || $patient_id == 0) {
            $patient_id = "K-".$id.date("m").date("d").date("Y");
        }
        if ($transaction != null) {
            if ($transaction->booking != null) {
                if ($transaction->booking->status == Booking::STATUS_CANCELLED) {
                    $transaction->update([
                        'status' => Transaction::STATUS_CANCELLED
                    ]);
                    $transaction = new Transaction();
                }
            }
        }else{
            $transaction = new Transaction();
        }
        if ($transaction->id == null) {
            $am = true;
            $transaction = new Transaction();
            $transaction->user_id = $id;
            $transaction->txn_id = 'PATIENT-'.$patient_id;
            $transaction->payment_method = Transaction::PAYMENT_METHOD_WALLET;
            $transaction->amount = $request->get("total_amount");
            $transaction->status = Transaction::STATUS_COMPLETED;
            $transaction->discount_amount = $request->get("discount_amount");
            $transaction->discount_id = $request->get("discount_id");
            $transaction->payable_amount = $request->get("payable_amount");
            $transaction->booking_id = $booking_id;
            $transaction->save();
        }

        if ($am == true) {
            $type = Wallet::TYPE_REFUND;
            $amount = $request->get('user_amount');
            if ($request->get('user_amount') >= 0) {
                $type = Wallet::TYPE_PAID;
            }
            $amount = str_replace("-","",$amount);
            $wallet = new Wallet();
            if ($type == Wallet::TYPE_REFUND) {
                $wallet = Wallet::where('user_id', $transaction->user_id)->where('type', Wallet::TYPE_REFUND)->where('status', Wallet::STATUS_PENDING)->first();
                if ($wallet == null)
                {
                    $wallet = new Wallet();
                }
            }
            $wallet->user_id = $transaction->user_id;
            $wallet->amount = $amount;
            $wallet->type = $type;
            if ($type == Wallet::TYPE_PAID) {
                $wallet->status = Wallet::STATUS_PAID;
            }
            $wallet->model_id = $transaction->id;
            $wallet->model_type = get_class($transaction);
            $wallet->created_by = \Auth::user()->id;
            $wallet->txn_id = 'Transaction-'.$user->userProfile->kid;
            $wallet->save();
        }

        $user->userProfile->update([
            'kid' => $patient_id
        ]);
        $transaction->update([
            'status' => Transaction::STATUS_COMPLETED
        ]);

        if ($user->getBookingId()) {
            $transaction->update([
                'booking_id' => $user->getBookingId()
            ]);
            $transaction->booking->update([
                'status' => Booking::STATUS_COMPLETED
            ]);
        }
        $transaction->saveItems();
        foreach ($transaction->items as $item) {
            $item->update([
                'status' => Booking::STATUS_COMPLETED
            ]);
        }
        $transaction->user->SendActivationEmail();
        $transaction->user->update([
            'is_discharged' => User::ADMIT
        ]);
        $transaction->sendBookingEmail();

        if (\Auth::user()->isPatient())
            return redirect()->route('Laralum::user.booking-detail')->with(['status' => 'success', 'message' => 'Booking has been completed successfully.']);

        return redirect()->route('Laralum::bookings.print_kid',['booking_id' => $id])->with(['status' => 'success', 'message' => 'Booking has been completed successfully.']);
    }

    /**
     * delete booking
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id){

        # Check permissions
        Laralum::permissionToAccess('bookings.delete');

        # Select booking
        $booking = User::findOrFail($id);
        # Delete booking
        $booking->customDelete();

        # Redirect the admin
        return redirect()->route('Laralum::bookings')->with('success', 'Booking has been deleted successfully.');

    }

    /*public function searchPatient(Request $request){

        return $request->all();

    }*/

    public function generatePatientCard(Request $request, $id = null)
    {
        $matchThese =   [];
        $search = false;
        $option_ar = [];
        if ($request->has('filter_patient_id') && $request->get('filter_patient_id') != ""){
            $option_ar[] = "Patient Id";
            $search = true;
            $matchThese['kid'] = $request->get('filter_patient_id');
        }
        if ($request->has('filter_first_name') && $request->get('filter_first_name') != ""){
            $option_ar[] = "First Name";
            $search = true;
            $matchThese['first_name'] = $request->get('filter_first_name');
        }

        if ($request->has('filter_last_name') && $request->get('filter_last_name') != ""){
            $option_ar[] = "Last Name";
            $search = true;
            $matchThese['last_name'] = $request->get('filter_last_name');
        }
        if ($request->has('filter_mobile') && $request->get('filter_mobile') != ""){
            $option_ar[] = "Mobile";
            $search = true;
            $matchThese['mobile'] = $request->get('filter_mobile');
        }

        $filter_email = "";

        if ($request->has('filter_email')){
            $option_ar[] = "Email";
            $search = true;
            $filter_email = $request->get('filter_email');
        }
        $options = implode(", ", $option_ar);

        $error = "Entered ".$options." is not valid,
make sure that you are entering valid ".$options." 
or search by other options";
        $user = [];

        if ($search == true) {
            $user = User::select('users.*')
                ->join('user_profiles', 'users.id', '=', 'user_profiles.user_id')
                ->where(function ($query) use ($matchThese, $filter_email) {
                    foreach ($matchThese as $key => $match) {
                        $query->where('user_profiles.' . $key, 'like', "%$match%");
                    }
                    if ($filter_email != "") {
                        $query->where('users.email', 'like', "%$filter_email%");
                    }
                })->join('role_user', 'role_user.user_id', 'users.id')->where('role_user.role_id', Role::getPatientId())->first();
        }

        if ($id != null)
            $user = User::find($id);

        $d = new DNS1D();
        $twod = new DNS2D();
        $barcode = null;
        $qrcode = null;

        if ($user != null) {
            $barcode = $d->getBarcodePNG($user->userProfile->kid, "C39+");
            $qrcode = $twod->getBarcodePNG($user->userProfile->kid, "QRCODE");
        }else{
            $user = new User();
        }
        if ($id != null)
            return view('laralum.booking.patient-card', compact('barcode', 'user','qrcode'));

        return view('laralum.booking.generate-patient-card', compact('barcode', 'user','qrcode', 'search', 'error'));
    }

    public function printPatientCard(Request $request, $id)
    {
        $user = User::find($id);
        $d = new DNS1D();
        $twod = new DNS2D();
        $barcode =  $d->getBarcodePNG($user->userProfile->kid, "C39+");
        $qrcode =  $twod->getBarcodePNG($user->userProfile->kid, "QRCODE");
        $back_url = "";
        if ($request->get("backurl")) {
            $back_url = $request->get("backurl");
        }
        return view('laralum.booking.print-patient-card', compact('barcode', 'user','qrcode', 'back_url'));
    }

    public function generateToken(Request $request, $id = null)
    {
        $data = [];
        $matchThese =   [];
        $search = false;
        $patient = [];
        $user = [];

        $option_ar = [];
        $kid = "";
        if ($request->has('filter_patient_id') && $request->get('filter_patient_id') != ""){
            $option_ar[] = "Patient Id";
            $search = true;
            $kid = $request->get('filter_patient_id');
            $matchThese['kid'] = $request->get('filter_patient_id');
        }
        $filter_name = "";
        if ($request->has('filter_name') && $request->get('filter_name') != ""){
            $option_ar[] = "Name";
            $search = true;
            $matchThese['first_name'] = $request->get('filter_name');
            $filter_name = $request->get('filter_name');
        }

        if ($request->has('filter_first_name') && $request->get('filter_first_name') != ""){
            $option_ar[] = "First Name";
            $search = true;
            $matchThese['first_name'] = $request->get('filter_first_name');
        }

        if ($request->has('filter_last_name') && $request->get('filter_last_name') != ""){
            $option_ar[] = "Last Name";
            $search = true;
            $matchThese['last_name'] = $request->get('filter_last_name');
        }
        $filter_mobile = "";
        if ($request->has('filter_mobile') && $request->get('filter_mobile') != ""){
            $option_ar[] = "Mobile";
            $search = true;
            $filter_mobile = $request->get('filter_mobile');
            $matchThese['mobile'] = $request->get('filter_mobile');
        }

        $filter_email = "";

        if ($request->has('filter_email')){
            $option_ar[] = "Email";
            $search = true;
            $filter_email = $request->get('filter_email');
        }
        $options = implode(", ", $option_ar);

        $error = "Entered ".$options." is not valid,
make sure that you are entering valid ".$options." 
or search by other options";

        if($id != null) {
            $booking = Booking::find($id);
            $user = $booking->user;
        }else{
            $booking = new Booking();
        }
        
        if ($search == true) {
            $booking = Booking::select('bookings.*')->where('status', Booking::STATUS_COMPLETED)->join('users', 'users.id', '=', 'bookings.user_id')->join('user_profiles', 'user_profiles.user_id', 'bookings.user_id')->where('users.email','like', "%".$filter_email."%")->where('users.name','like', "%".$filter_name."%")->where('user_profiles.mobile','like', "%".$filter_mobile."%");
            if ($kid != null) {
                $booking = $booking->where('user_profiles.kid',$kid);
            }
            $booking = $booking->first();
            $user = new User();
            if ($booking != null)
                $user = $booking->user;
        }

        $date = (string) date('Y-m-d');
        $token = PatientToken::where(\DB::raw('date(`start_date`)'), $date)->orderBy('created_at', 'DESC')->first();
        $data['token_no'] = 1;

        
        if ($user == null){
            $user = new User();
        }

        if ($token != null) {
            if ($token->Status == PatientToken::STATUS_PENDING && $token->patient_id == $user->id) {
                $data['token_no'] = $token->token_no;
            }else{
                $data['token_no'] = $token->token_no + 1;
            }
        }
        $data['patient'] = $user;
        $data['booking'] = $booking;
        $data['user'] = $data['patient'];
        $data['search'] = $search;
        $data['error'] = $error;

        return view('laralum.booking.generate-token', $data);
    }

    public function printToken(Request $request, $id = null)
    {
        if ($id == null)
            $id = $request->get('booking_id');

        $booking = Booking::find($id);
        $token = PatientToken::where([
            'booking_id' => $id,
            'doctor_id' => $request->get('doctor_id'),
            'department_id' => $request->get('department_id'),
            'status' => PatientToken::STATUS_PENDING
        ])->where(\DB::raw('date(`start_date`)'), date('Y-m-d'))->first();

        if ($token == null) {
            $token = new PatientToken();
            $token->setData($request);
            $token->booking_id = $id;
            $token->patient_id = $booking->user_id;
            $token->save();
        }

        return view('laralum.booking.print-token', compact('token'));
    }

    public function dischargeBillings(Request $request, $id = null)
    {
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
        $transaction = [];

        if ($id != null) {
            $discharge_patient = DischargePatient::where([
                'patient_id' => $id,
                'status' => DischargePatient::STATUS_PENDING
            ])->first();
            if ($discharge_patient != null) {
                $user = User::find($id);
                $transaction = $user->getTransaction();
            }else{
                return redirect()->back()->with('error', 'Please make sure doctor have marked this patient as discharged');
            }
        } else {
            $matchThese =   [];
            if ($request->has('filter_patient_id') && $request->get('filter_patient_id') != ""){
                $option_ar[] = "Patient Id";
                $search = true;
                $matchThese['kid'] = $request->get('filter_patient_id');
            }
            $filter_name = "";
            if ($request->has('filter_name') && $request->get('filter_name') != ""){
                $option_ar[] = "Name";
                $search = true;
                $filter_name = $request->get('filter_name');
            }

            if ($request->has('filter_first_name') && $request->get('filter_first_name') != ""){
                $option_ar[] = "First Name";
                $search = true;
                $matchThese['first_name'] = $request->get('filter_first_name');
            }

            if ($request->has('filter_last_name') && $request->get('filter_last_name') != ""){
                $option_ar[] = "Last Name";
                $search = true;
                $matchThese['last_name'] = $request->get('filter_last_name');
            }
            if ($request->has('filter_mobile') && $request->get('filter_mobile') != ""){
                $option_ar[] = "Mobile";
                $search = true;
                $matchThese['mobile'] = $request->get('filter_mobile');
            }

            $filter_email = "";

            if ($request->has('filter_email')){
                $option_ar[] = "Email";
                $search = true;
                $filter_email = $request->get('filter_email');
            }
            if ($search == true) {
                $transaction = Transaction::select('transactions.*')
                    ->join('users', 'users.id', '=', 'transactions.user_id')
                    ->join('user_profiles', 'transactions.user_id', '=', 'user_profiles.user_id')
                    ->join('discharge_patients', 'transactions.user_id', '=', 'discharge_patients.patient_id')
                    ->where('transactions.status', Transaction::STATUS_COMPLETED)
                    ->where('discharge_patients.status', '!=', DischargePatient::STATUS_DISCHARGED)
                    ->where('users.is_discharged', User::ADMIT)
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
                    })
                    ->join('role_user', 'role_user.user_id', 'transactions.user_id')
                    ->where('role_user.role_id', Role::getPatientId())->orderBy('transactions.created_at', "DESC")                      ->first();
            }

        }

        $options = implode(", ", $option_ar);

        $error = "Entered ".$options." is not valid,
make sure that you are entering valid ".$options." 
or search by other options";

        if ($transaction != null) {
            $amount = $transaction->getAmount();
        }
        if ($transaction == null) {
            $transaction = new Transaction();
        }
        $data['transaction'] = $transaction;
        $data['amount'] = $amount;
        $data['error'] = $error;
        $data['search'] = $search;

        return view('laralum.booking.discharge_patient', $data);
    }

    public function getAccommodationDetails($id)
    {
        $transaction = Transaction::find($id);
        $booked_info = Booking::join('rooms', 'rooms.id', '=', 'bookings.room_id')
            ->join('user_profiles', 'bookings.user_id', '=', 'user_profiles.user_id')
            ->where('bookings.id',$transaction->booking_id)
            ->select('rooms.*','rooms.id as room_id', 'bookings.*','user_profiles.first_name','user_profiles.last_name')
            ->get();

        $data['booked_info'] = $booked_info;
        // $data['user_info'] = $user_info;
        return view('laralum.booking.get-booking-info',$data);
    }

    public function getServicesDetails($id)
    {
        $transaction = Transaction::find($id);
        $user = $transaction->user;
        $services = $transaction->booking->services;
        // $data['user_info'] = $user_info;
        return view('laralum.booking.get-booking-info',compact('user', 'services' ));
    }

    public function getPaidDetails($id)
    {
        $transaction = Transaction::find($id);
        $user = $transaction->user;
        $items = Wallet::where('user_id', $transaction->user_id)->where('status', Wallet::STATUS_PAID)->where('type', Wallet::TYPE_PAID)->get();
        return view('laralum.booking.get-booking-info',compact('user', 'items' ));
    }

    public function getFeedbackForm($id)
    {
        $user = User::find($id);
        $feedback = Feedback::where('user_id', $id)->orderBy("created_at", "DESC")->first();
        if ($feedback == null) {
            $feedback = new Feedback();
        }
        // $data['user_info'] = $user_info;
        return view('laralum.booking.feedback-form',compact('user', 'feedback' ));
    }

    public function submitFeedbackForm(Request $request,$id)
    {
        $user = User::find($id);
        if ($request->get('feedback') != null) {
            /*$feedback = Feedback::where('user_id', $id)->first();
            if ($feedback == null) {*/
             $feedback = new Feedback();
            //}
            $question_ans = [];
            foreach (FeedbackQuestion::all() as $question) {
                $question_ans[$question->id] = $request->get('rate_'.$question->id);;
            }
            $feedback->question_id =  implode(",", array_keys($question_ans));
            $feedback->rate =  implode(",", array_values($question_ans));
            $feedback->feedback =  $request->get('feedback');
            $feedback->user_id =  $id;
            $feedback->save();
        }
        return 'success';
        // $data['user_info'] = $user_info;
    }

    public function getNoc($id)
    {
        $user = User::find($id);
        $noc = true;
        $feedback = Feedback::where('user_id', $id)->first();
        $error = false;
        if ($feedback == null) {
            $error = true;
        }
        // $data['user_info'] = $user_info;
        return view('laralum.booking.get-booking-info',compact('user', 'noc', 'error' ));
    }

    public function followups(Request $request)
    {
        $matchThese =   [];
        $search = false;
        $option_ar = [];
        if ($request->has('filter_patient_id') && $request->get('filter_patient_id') != ""){
            $option_ar[] = "Patient Id";
            $search = true;
            $matchThese['kid'] = $request->get('filter_patient_id');
        }
        if ($request->has('filter_first_name') && $request->get('filter_first_name') != ""){
            $option_ar[] = "First Name";
            $search = true;
            $matchThese['first_name'] = $request->get('filter_first_name');
        }

        if ($request->has('filter_last_name') && $request->get('filter_last_name') != ""){
            $option_ar[] = "Last Name";
            $search = true;
            $matchThese['last_name'] = $request->get('filter_last_name');
        }
        if ($request->has('filter_mobile') && $request->get('filter_mobile') != ""){
            $option_ar[] = "Mobile";
            $search = true;
            $matchThese['mobile'] = $request->get('filter_mobile');
        }

        $filter_email = "";

        if ($request->has('filter_email')){
            $option_ar[] = "Email";
            $search = true;
            $filter_email = $request->get('filter_email');
        }
        $options = implode(", ", $option_ar);

        $error = "Entered ".$options." is not valid,
make sure that you are entering valid ".$options." 
or search by other options";
        $user = [];
        $per_page = $request->get("per_page") ? $request->get("per_page") : 10;
        $pagination = true;
        if ($per_page == "All") {
            $pagination = false;
        }
        $count = 0;
        if ($search == true) {
            $date = (string) date("Y-m-d");
            $followups = PatientFollowUp::select('patient_followups.*')
                ->join('discharge_patients', 'patient_followups.patient_id', '=', 'discharge_patients.id')
                ->join('user_profiles', 'user_profiles.user_id', '=', 'discharge_patients.patient_id')->join('role_user','role_user.user_id', 'discharge_patients.patient_id')->where('role_user.role_id', Role::getPatientId())->where(function($query) use ($matchThese,$filter_email) {
                    foreach($matchThese as $key=>$match){
                        $query->where('user_profiles.'.$key,'like',"%$match%");
                    }
                    if($filter_email != "") {
                        $query->where('users.email', 'like', "%$filter_email%");
                    }
                })->join('role_user','role_user.user_id', 'users.id')->where('role_user.role_id', Role::getPatientId())->where('patient_followups.followup_date', '>=', $date)->orderBY('patient_followups.followup_date', 'ASC');
            if ($pagination == true) {
                $count = $followups->count();
                $followups = $followups->paginate($per_page);
            }else{
                $count = $followups->count();
                $followups = $followups->get();
            }


        }else{
            $date = (string) date("Y-m-d");
            $followups = PatientFollowUp::select('patient_follow_ups.*')
                ->join('discharge_patients', 'patient_follow_ups.patient_id', '=', 'discharge_patients.id')
                ->join('user_profiles', 'user_profiles.user_id', '=', 'discharge_patients.patient_id')->join('role_user','role_user.user_id', 'discharge_patients.patient_id')->where('role_user.role_id', Role::getPatientId())->where('patient_follow_ups.followup_date', '>=', $date)->orderBY('patient_follow_ups.followup_date', 'ASC');
                 if ($pagination == true) {
                     $count = $followups->count();
                     $followups = $followups->paginate($per_page);
                 }else{
                     $count =  $followups->count();
                     $followups = $followups->get();
                 }
        }

        return view('laralum.booking.followups',compact('followups', 'search', 'error', 'count'));
    }

    public function printBill(Request $request, $id = null)
    {
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
        $transaction = [];

        $discharge_patient = DischargePatient::where([
            'patient_id' => $id,
            'status' => DischargePatient::STATUS_PENDING
        ])->first();
        if ($discharge_patient != null) {
            if ($id != null) {
                $user = User::find($id);
                $transaction = $user->getTransaction();
            }

            if ($transaction != null) {
                $amount = $transaction->getAmount();
                $data['transaction'] = $transaction;
                $data['amount'] = $amount;
                $data['user'] = $user;
                if ($request->get('type') == Booking::PRINT_NOC) {
                    $transaction->update([
                        'status' => Transaction::STATUS_DISCHARGED
                    ]);
                    if ($transaction->booking != null) {
                        $transaction->booking->update([
                            'status' => Booking::STATUS_DISCHARGED
                        ]);
                    }
                    $user->saveDietAmount();
                    $user->saveTreatmentsAmount();

                    $wallets = Wallet::where('user_id', $id)->get();
                    foreach ($wallets as $wallet) {
                        $wallet->update([
                            'status' => Wallet::STATUS_CLOSE
                        ]);
                    }
                    $discharge_patient->update([
                        'status' => DischargePatient::STATUS_DISCHARGED
                    ]);

                    $discharge_patient->update([
                        'status' => DischargePatient::STATUS_DISCHARGED
                    ]);

                    $treatment_tokens = $user->getTreatments();

                    if ($treatment_tokens->count() > 0) {
                        foreach ($treatment_tokens as $treatment_token) {
                            $treatment_token->update([
                                'status' => TreatmentToken::STATUS_DISCHARGED
                            ]);
                            $treatments = $treatment_token->treatments;
                            if ($treatments->count() > 0) {
                                foreach ($treatments as $treatment) {
                                    $treatment->update([
                                        'status' => PatientTreatment::STATUS_DISCHARGED
                                    ]);
                                }
                            }

                        }
                    }

                    $diets = $user->getDiets();

                    if ($diets->count() > 0) {
                        foreach ($diets as $diet) {
                            $diet->update([
                                'status' => DietChart::STATUS_DISCHARGED
                            ]);
                        }
                    }

                    $user->updatePatientVitalData();

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
                return view('laralum.booking.print-bill', $data);
            }
        }

        return redirect()->back()->with('error', 'Something went wrong!!!');
    }

    public function getDietPrices($id)
    {
        $user = User::find($id);
        $diet = true;
        $diets = DietChart::where('patient_id', $id)->where('status', DietChart::STATUS_PENDING)->get();
        $error = false;
        if (count($diets) > 0) {
            $error = true;
        }
        // $data['user_info'] = $user_info;
        return view('laralum.booking.get-booking-info',compact('user', 'diets', 'error' ));

    }

    public function getDailyDietDetails($id)
    {
        $daily_diet = DietDailyStatus::find($id);
        $html = "";
        if ($daily_diet != null) {
            $html = "<table>";
            foreach (DietChartItems::getTypeOptions() as $id => $type) {
                if ($daily_diet->checkType($id)) {
                    $items = DietChartItems::where([
                        'diet_id' => $daily_diet->diet_id,
                        'type_id' => $id
                    ])->get();
                    $items_html = "";
                    foreach ($items as $item) {
                        $items_html .= $item->item->name . "  => " . $item->item->price . "<br/>";
                    }

                    $html .= "<tr><th>" . $type . "</th><td>" . $items_html . "</td></tr>";
                }
            }
            $html .= "</table>";
        }
        return $html;
    }



    public function export(Request $request, $type, $per_page = 10, $page = 1)
    {
        $per_page = $request->get('per_page') ? $request->get('per_page') : 10;
        $pagination = true;
        if ($per_page == "All") {
            $pagination = false;
        }

        $bookings   =  User::select('users.*')->with(['userProfile', 'address'])
            ->join('transactions', 'transactions.user_id', '=', 'users.id')
            ->join('user_addresses', 'user_addresses.user_id', '=', 'users.id')
            ->join('user_profiles', 'user_profiles.user_id', '=', 'users.id')->join('role_user','role_user.user_id', 'users.id')->where('role_user.role_id', Role::getPatientId())->whereNotIn('transactions.status', [Transaction::STATUS_DISCHARGED, Transaction::STATUS_CANCELLED])/*->where('active', 1)*/->orderBY('users.created_at', 'DESC');

        if ($pagination == true) {
            $booking_count = $bookings->count();
            $bookings = $bookings->paginate($per_page);
        }else{
            $booking_count = $bookings->count();
            $bookings = $bookings->get();
        }

        $bookings_array[] = [
            'Patient Id',
            'Name of the Person',
            'Email ID',
            'Contact No. ',
            'City, State, Country '
        ];
        foreach ($bookings as $booking) {
            $bookings_array[] = [
                $booking->userProfile->kid,
                $booking->name,
                $booking->email,
                $booking->userProfile->mobile,
                $booking->address->city .','. $booking->address->state .','. $booking->address->country,
            ];
        }

        // Generate and return the spreadsheet
        $excel = \App::make('excel');
        $excel = $excel->create('Bookings', function($excel) use ($bookings_array) {

            // Set the spreadsheet title, creator, and description
            $excel->setTitle('Bookings Patients');
            $excel->setCreator('Laravel')->setCompany('WJ Gilmore, LLC');
            $excel->setDescription('Bookings file');

            // Build the spreadsheet, passing in the payments array
            $excel->sheet('sheet1', function($sheet) use ($bookings_array) {
                $sheet->fromArray($bookings_array, null, 'A1', false, false);
            });

        });
        if ($type == Settings::EXPORT_CSV) {
            $excel->download('csv');
        }elseif($type == Settings::EXPORT_EXCEL) {
            $excel->download('xls');
        }else{
            $excel->download('pdf');
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

        $date = (string) date("Y-m-d");
        $followups = PatientFollowUp::select('patient_follow_ups.*')
            ->join('discharge_patients', 'patient_follow_ups.patient_id', '=', 'discharge_patients.id')
            ->join('user_profiles', 'user_profiles.user_id', '=', 'discharge_patients.patient_id')->join('role_user','role_user.user_id', 'discharge_patients.patient_id')->where('role_user.role_id', Role::getPatientId())->where('patient_follow_ups.followup_date', '>=', $date)->orderBY('patient_follow_ups.followup_date', 'ASC');
        if ($pagination == true) {
            $count = $followups->count();
            $followups = $followups->paginate($per_page);
        }else{
            $count =  $followups->count();
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
            if(isset($followup->patient->patient->userProfile->kid)) {
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
        $excel = $excel->create('Followups', function($excel) use ($followups_array) {

            // Set the spreadsheet title, creator, and description
            $excel->setTitle('Followups Patients');
            $excel->setCreator('Laravel')->setCompany('WJ Gilmore, LLC');
            $excel->setDescription('Followups file');

            // Build the spreadsheet, passing in the payments array
            $excel->sheet('sheet1', function($sheet) use ($followups_array) {
                $sheet->fromArray($followups_array, null, 'A1', false, false);
            });

        });
        if ($type == Settings::EXPORT_CSV) {
            $excel->download('csv');
        }elseif($type == Settings::EXPORT_EXCEL) {
            $excel->download('xls');
        }else{
            $excel->download('pdf');
        }

        return redirect()->back()->with('error', 'Something went Wrong!!!');
    }

    /**
     * get all resource listing
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function treatmentTokens(Request $request)
    {
        $matchThese =   [];
        $search = false;
        $option_ar = [];
        if ($request->has('filter_patient_id') && $request->get('filter_patient_id') != ""){
            $option_ar[] = "Patient Id";
            $search = true;
            $matchThese['kid'] = $request->get('filter_patient_id');
        }
        if ($request->has('filter_first_name') && $request->get('filter_first_name') != ""){
            $option_ar[] = "First Name";
            $search = true;
            $matchThese['first_name'] = $request->get('filter_first_name');
        }

        if ($request->has('filter_last_name') && $request->get('filter_last_name') != ""){
            $option_ar[] = "Last Name";
            $search = true;
            $matchThese['last_name'] = $request->get('filter_last_name');
        }
        if ($request->has('filter_mobile') && $request->get('filter_mobile') != ""){
            $option_ar[] = "Mobile";
            $search = true;
            $matchThese['mobile'] = $request->get('filter_mobile');
        }

        $filter_email = "";

        if ($request->has('filter_email')){
            $option_ar[] = "Email";
            $search = true;
            $filter_email = $request->get('filter_email');
        }
        $options = implode(", ", $option_ar);

        $error = "Entered ".$options." is not valid,
make sure that you are entering valid ".$options." 
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
                ->where(function($query) use ($matchThese,$filter_email) {
                    foreach($matchThese as $key=>$match){
                        $query->where('user_profiles.'.$key,'like',"%$match%");
                    }
                    if($filter_email != "") {
                        $query->where('users.email', 'like', "%$filter_email%");
                    }
                })->where('treatment_date', (string) date("Y-m-d"));

            $count = $tokens->count();

            if ($pagination == true) {
                $tokens = $tokens->paginate($per_page);
            }else{
                $tokens = $tokens->get();
            }

        }else{
            $tokens = TreatmentToken::select('treatment_tokens.*')->where('treatment_date', (string) date("Y-m-d"));

            $count = $tokens->count();

            if ($pagination == true) {
                $tokens = $tokens->paginate($per_page);
            }else{
                $tokens = $tokens->get();
            }
        }

        return view('laralum.booking.treatment-tokens',compact('tokens', 'search', 'error', 'count'));
    }

    public function printTreatment($id)
    {
        $token = TreatmentToken::find($id);
        $back_url = url('/admin/booking/treatment-tokens');
        $d = new DNS1D();
        $barcode = $d->getBarcodePNG($token->token_no, "C39+");
        return view('laralum.token.print_treatment_token', compact('token', 'barcode', 'back_url'));
    }

    public function allotRooms($id)
    {
        $booking = Booking::find($id);
        $user = $booking->user;
        return view('laralum.booking.allot_rooms', compact('booking', 'user'));
    }

    public function allotRoomForm(Request $request, $id, $m_id = null)
    {
        $booking = Booking::find($id);
        $member = Member::find($m_id);
        $user = $booking->user;

        $data['user_id'] = $id;
        // call room wise data
        $data['user'] = $user;
        $booking = new Booking();
        if ($user->getBooking()){
            $booking = $user->getBooking();
        }
        $previousBooking = null;
        if ($user->previousBooking()){
            $previousBooking = $user->previousBooking();
        }
        if ($booking->check_in_date == "" || $booking->check_in_date < date('Y-m-d H:i:s'))
            $booking->check_in_date = date('Y-m-d 00:00:00');

        $default_date = date('Y-m-d', strtotime($booking->check_in_date));

        $default_month_year = date("m-Y", strtotime($booking->check_in_date));
        $month_wise_arr = Booking::guestBookingChartmw($request, $default_month_year);
        $data['rooms_status_arr'] = $month_wise_arr['rooms_status_arr'];
        $data['accordian_status_mw'] = $month_wise_arr['accordian_status_mw'];
        $room_wise_arr = Booking::guestBookingChart($request, $default_date);

        $data = array_merge($data, $room_wise_arr);

        $data['booking'] = $booking;
        $data['previousBooking'] = $previousBooking;
        $data['default_date'] = $default_date;
        $filter_date = false;
        if($request->get('filter_date')) {
            $filter_date = true;
        }

        $filter_month = false;
        if($request->get('filter_month')) {
            $filter_month = true;
        }
        $data['filter_date'] = $filter_date;
        $data['filter_month'] = $filter_month;
        $data['m_id'] = $m_id;
        return view('laralum.booking.allot_room_form', $data);
    }
}

