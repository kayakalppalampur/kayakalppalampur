<?php

namespace App\Http\Controllers;

use App\Department;
use App\EmailTemplate;
use App\Issue;
use App\Notification;
use App\Profession;
use App\Room;
use App\Room_Type;
use App\User;
use App\UserAddress;
use App\UserProfile;
use App\Booking;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class HomeController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = \Auth::user();
        return view('home', compact('user'));
    }
    /**
     * patient query form
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function patientQuery()
    {
        return view('patient_query');
    }

    public function patientQueryStore(Request $request)
    {

        $rules = Issue::getRules(true);

        $validator = \Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput()->with(['error' => 'Please check the errors below and try again.']);
        }

        try {
            $issue = new Issue();

            if ($issue->setData($request)) {
                $issue->type = Issue::TYPE_QUERY;
                $issue->save();
                Notification::saveNotification($issue, "New Online Query");
                $title = $issue->title;
                $message_string = $issue->description;
                $data = [];
                EmailTemplate::sendEmail(EmailTemplate::EVENT_QUERY_SUBMITTED, $data, $issue->email_id);


                $email = env("ADMIN_EMAIL", 'antarrahi@gmail.com');
                $from = $issue->email_id != null ? $issue->email_id : env("USER_EMAIL");
                
                \Mail::send('email.reply', ['title' => $title, 'message_string' => $message_string], function ($message) use($email, $issue) {
                    $message->from($issue->email_id, $issue->name);
                    $message->subject("Query: ".$issue->title);
                    $message->to($email);
                });
                
                return redirect()->back()->with('success', 'Query added successfully.');
            }else{
                return redirect()->back()->with('error', 'Something went wrong. Please try again later.');
            }

        } catch (\Exception $e) {

            \Log::error("Failed to add the issue, possible causes: ".$e->getMessage());
            //print_r($e->getMessage());exit;
            return redirect()->back()->with('error', 'Something went wrong. Please try again later.');
        }

    }

    /**
     * view for booking process
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function guestBooking()
    {
        return redirect('guest/booking/signup');
        return view('guest_booking');
    }

    public function guestBookingChart(Request $request)
    {
        if(!empty($request->all())){
            $select_date = $request->select_date;
            $select_date_formatted = date($select_date);
            $default_date = $select_date;
        }else{
            $select_date_formatted = time();
            $default_date = date('Y-m-d');
        }
        $room_id_arr = [];
        $rooms_share_avail = [];
        $rooms_share_gender = [];
        $vacant_room_arr = [];
        $total_partial_vacant = [];
        $single_occupancy_booked=[];
        $single_bed_booked = [];
        $doublebed_shared_booked = [];
        $room_number_iterate = [];
        $room_booked_arr = [];
        $room_arr = [];
        /*DB::enableQueryLog();
        dd(DB::getQueryLog());*/
        $booked_rooms = DB::table('rooms')
            ->join('bookings', 'rooms.id', '=', 'bookings.room_id')
            ->join('user_profiles', 'bookings.user_id', '=', 'user_profiles.user_id')
            ->join('room_types', 'room_types.id', '=', 'rooms.room_type_id')
            ->where('bookings.check_in_date','<=',$select_date_formatted)
            ->where('bookings.check_out_date','>=', $select_date_formatted)
            ->where('rooms.status',1)
            ->select('rooms.*','rooms.id as room_id', 'bookings.*','bookings.id as booking_id','user_profiles.gender','user_profiles.user_id as user_id','room_types.id as room_type_id')
            ->get();
        foreach ($booked_rooms as $bk_room){
            // create booking array based on room id
            $room_booked_arr[$bk_room->room_id][] = ['booking_type' => $bk_room->booking_type,'booking_gender' => $bk_room->gender,'booking_id'=>$bk_room->booking_id];
            $room_id_arr[$bk_room->id] = $bk_room->id;
            //$two_user_booked_signle_room[$bk_room->room_type_id][$bk_room->room_number][] = user_uid;
            if(in_array($bk_room->room_number, $room_number_iterate)){
                unset($rooms_share_avail[$bk_room->room_type_id][$bk_room->room_number]);
            }else {
                // 1 => SingleBed, 2 => SingleOccupancy, 3=>SingleOccupancyWithExtraBed, 4- DoubleBedWithSharing
                if ($bk_room->booking_type != 2 && $bk_room->booking_type != 3) {
                    if ($bk_room->gender == 1) {
                        $gender = 'female';
                    } else {
                        $gender = 'male';
                    }
                    $rooms_share_avail[$bk_room->room_type_id][$bk_room->room_number][] = $gender;
                }
            }
            $room_number_iterate[] = $bk_room->room_number;
        }
        //echo '<pre>'; print_r($rooms_share_avail); echo '</pre>'; die;
        /*$array = array("Kyle","Ben","Sue","Phil","Ben","Mary","Sue","Ben");
        $counts = array_count_values($array);
        echo $counts['Ben'];*/
        // get gender based booking number
        foreach ($rooms_share_avail as $room_type=>$room_share_avail) {
            foreach ($room_share_avail as $room_number=>$gender) {
                $counts = array_count_values($gender);
                if (isset($counts['female'])) {
                    $rooms_share_gender[$room_type]['female_count'] = $counts['female'];
                } else {
                    $rooms_share_gender[$room_type]['female_count'] = 0;
                }
                if (isset($counts['male'])) {
                    $rooms_share_gender[$room_type]['male_count'] = $counts['male'];
                } else {
                    $rooms_share_gender[$room_type]['male_count'] = 0;
                }
            }
        }
        //$rooms = Room::whereNotIn('id',$room_id_arr)->get();
        $vacant_rooms = DB::table('rooms')
            ->where('rooms.status',1)
            ->whereNotIn('rooms.id',$room_id_arr)
            ->Join('room_types', 'rooms.room_type_id', '=', 'room_types.id')
            ->select('rooms.*', 'room_types.id as room_type_id')
            ->get();
        foreach ($vacant_rooms as $vacant_room) {
            $vacant_room_arr[$vacant_room->room_type_id][] = $vacant_room->room_number;
        }
        //echo '<pre>'; print_r($vacant_rooms); echo '</pre>'; die;
        $room_types = Room_Type::all();
        foreach ($room_types as $room_type){
            $total_vacant_rooms = 0;
            $total_partial_rooms = 0;
            $female_count = 0;
            $male_count = 0;
            $any_gender = 0;
            $single_occupancy_rooms = 0;
            // Get total rooms
            if(isset($vacant_room_arr[$room_type->id])) {
                $total_vacant_rooms = count($vacant_room_arr[$room_type->id]);
                // double vacant room as two bed is available for booking in each room
                $total_vacant_rooms = 2*$total_vacant_rooms;
            }
            // get total sharing booking
            if(isset($rooms_share_gender[$room_type->id])) {
                $female_count = $rooms_share_gender[$room_type->id]['female_count'];
                $male_count = $rooms_share_gender[$room_type->id]['male_count'];
                $total_partial_rooms = $female_count + $male_count;
            }
            // get total single occupancy booking
            if(isset($single_occupancy_booked[$room_type->id])){
                $single_occupancy_rooms = count($single_occupancy_booked[$room_type->id]);
            }
            // double the single occupancy rooms as basically two bed are booked as single occupancy;
            $rooms = Room::where('room_type_id',$room_type->id)
                ->where('status',1);
            $total = $rooms->count();
            // Double th room total because each room has two beds
            $total = 2*$total;
            $total_vacant_rooms = $total_vacant_rooms + $total_partial_rooms;
            $total_partial_vacant[$room_type->id] = ['room_type'=>$room_type->name,'total' => $total, 'total_vacant_rooms' => $total_vacant_rooms, 'female'=>$female_count, 'male' => $male_count];
            $room_types_arr[$room_type->id]=$room_type->name;
        }
        //Room::where('status',1)
        $rooms_obj = DB::table('rooms')
            ->leftjoin('room_types', 'room_types.id', '=', 'rooms.room_type_id')
            ->leftjoin('buildings', 'buildings.id', '=', 'rooms.building_id')
            ->where('rooms.status',1)
            ->select('rooms.id as room_id','rooms.room_number','rooms.room_type_id','rooms.building_id','rooms.floor_number',
                'room_types.id as room_type_id','room_types.name as room_type_name','room_types.short_name as room_type_short_name',
                'buildings.name as building_name')
            ->get();
        foreach($rooms_obj as $room){
            if(isset($room_booked_arr[$room->room_id])){
                $booking_status = $room_booked_arr[$room->room_id];
            }
            else {
                $booking_status = [];
            }
            $building_arr[$room->building_id] = ['building_name' => $room->building_name,
                                                'room_type_name' => $room->room_type_name];
            $room_data[$room->building_id][] = [
                                            'room_id'=>$room->room_id,
                                            'room_number'=>$room->room_number,
                                            'booking_status' => $booking_status,
                                            'room_type_short_name' => $room->room_type_short_name,
                                            'floor_number' => $room->floor_number
                                            ];

        }
        foreach($building_arr as $building_id=>$building){
            $room_arr[$building_id]=['building_data'=>$building,'room_data'=>$room_data[$building_id]];
        }
        //echo '<pre>'; print_r($room_arr); echo '</pre>'; die;
       /* if(!$booked_rooms->isEmpty()){
            echo 'dddd';
        }*/
        /*foreach ($total_partial_vacant as $room_type_id=>$vacant) {
            echo '<br><b>'.$room_types_arr[$room_type_id].' </b><br>total- '.$vacant['total'].' <br>total Any gender- '.$vacant['total_vacant_rooms'].' <br>female- '.$vacant['female'].' <br>male- '.$vacant['male'];
        }
       die;*/
        //echo '<pre>'; print_r($vacant_room_arr); echo '</pre>'; die;
        $data['total_partial_vacant'] = $total_partial_vacant;
        $data['room_arr'] = $room_arr;
        $data['default_date'] = $default_date;
        return view('booking.booking_chart_room_wise',$data);
    }
    public function getBookingInfo($booking_ids){
        $booking_id_arr = explode('-',$booking_ids);
        $booked_info = Booking::join('rooms', 'rooms.id', '=', 'bookings.room_id')
            ->join('user_profiles', 'bookings.user_id', '=', 'user_profiles.user_id')
            ->whereIN('bookings.id',$booking_id_arr)
            ->select('rooms.*','rooms.id as room_id', 'bookings.*','user_profiles.first_name','user_profiles.last_name')
            ->get();

        //echo '<pre>'; print_r($booking_ids); die;
        $data['booked_info'] = $booked_info;
        return view('booking.get-booking-info',$data);
    }
    public function accommBookingForm(){
        $data['booked_info'] = [];
        return view('booking.accommodation-booking-form',$data);
    }
    public function accommBookingFormStore(){
        $data['booked_info'] = [];
        return redirect(route('Laralum::guest.bookingRm'))->with('success', 'Booking has been completed successfully.');
       // return view('booking.booking_chart_room_wise',$data);
    }
    /**
     * view for booking process
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function guestBookingChartmw(Request $request)
    {
        if(!empty($request->all())){
            $month_year = $request->select_month_year;
            $month_year_arr = explode('-',$month_year);
            $month = $month_year_arr[0];
            $year = $month_year_arr[1];
        }else{
            $month = date('m');
            $year = date('Y');
        }
        $room_status_arr = [];
        $month_days_count = cal_days_in_month(CAL_GREGORIAN, $month, $year);
        $rooms_status_arr['month_data'] = ['month_date_count' => $month_days_count,'year'=>$year,'month'=>$month];
        $rooms = DB::table('rooms')
            ->join('room_types', 'room_types.id', '=', 'rooms.room_type_id')
            ->join('buildings', 'buildings.id', '=', 'rooms.building_id')
            ->where('rooms.status',1)
            ->select('rooms.*','room_types.short_name','buildings.name as building_name')
            ->get();
        if(!$rooms->isempty()){
            foreach($rooms as $room){
                for($month_day=1;$month_day<=$month_days_count;$month_day++) {
                    $room_id = $room->id;
                    $room_status_arr['building'] = $room->building_name;
                    $room_status_arr['room_type'] = $room->short_name;
                    $room_status_arr['room_number'] = $room->room_number;
                    $room_status_arr['days_bookings'][$month_day] = $this->getRoomMonthStatus($room_id,$month_day,$month,$year);
                }
                $rooms_status_arr['rooms_data']['roomm-'.$room->id] = $room_status_arr;
            }
        }
        //echo '<pre>';print_r($rooms_status_arr); die;
        $data['rooms_status_arr'] = $rooms_status_arr;
        return view('booking.booking_chart_month_wise',$data);
    }

    public function postBooking(Request $request)
    {
        $error_messages = User::getErrorMessages(true);
        $rules = User::getRules(true);

        $validator = Validator::make($request->all(), $rules, $error_messages);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput()->with(['status' => 'danger', 'message' => 'Please check the errors below.']);
        }

        try {
            /* add user data */
            $user = new User();
            $data = $request->get('user');

            if ($user->setData($data)) {
                # Setup a random activation key
                $activation_key = str_random(25);
                $user->name = $request->get('user')['first_name'].' '.$request->get('user')['last_name'];
                $user->password = bcrypt($data['password']);
                $user->activation_key = $activation_key;
                $user->save();

                /* add user profile */
                $userProfile = new UserProfile();
                $userProfileData = $request->get('userProfile');
                $userProfileData['file'] = $request->file('profile_picture');
                if ($userProfile->setData($userProfileData, $user->id)) {
                    $userProfile->save();
                }

                /* add user address */
                $userAddress = new UserAddress();
                $userAddressData = $request->get('userAddress');

                if ($userAddress->setData($userAddressData, $user->id)) {
                    $userAddress->save();
                }

                try{

                    $user->SendActivationEmail(); // send activation mail to user

                }catch(\Exception $e){
                    Log::error("Failed to send account activation mail, possible causes: ".$e->getMessage());
                }

                /* redirect to admin if request from admin panel */
                if($request->has('admin')){
                    return redirect()->to('/admin/bookings')->with('success', 'Booking has been completed successfully.');
                }else{
                    return redirect()->route('thank-you')->with(['status' => 'success', 'message' => 'Your booking has been completed successfully. Please check your email to activate your account']);
                }

            }

            return redirect()->back()->with(['status' => 'danger', 'message' => 'Something went wrong .']);

        } catch (\Exception $e) {
            Log::error("Failed to add the user data during booking process. Possible causes: " . $e->getMessage());
            return redirect()->back()->with(['status' => 'danger', 'message' => 'Failed to process your request. Please try again later.']);
        }
    }
    /*
     * Functions to call in actions
     */
    private function getRoomMonthStatus($room_id,$month_day,$month,$year){
        $booked_room_arr = [];
        /*DB::enableQueryLog();
        dd(DB::getQueryLog());*/
        //$month_days_timestamp = strtotime($month_day.'-' . $month . '-' . $year);
        $month_days_date = date($year.'-' . $month . '-' . $month_day);
        $booking_arr = Booking::where('room_id', '=', $room_id)
            ->whereDate('check_in_date', '<=', $month_days_date)
            ->whereDate('check_out_date', '>=', $month_days_date)
            ->get();
        if(!$booking_arr->isempty()){
            foreach ($booking_arr as $booking) {
                $user_profile_obj = UserProfile::find($booking->user_id,['gender']);
                $booked_room_arr[] = ['user_gender'=>$user_profile_obj->gender,'booking_id'=>$booking->id,'booking_type' => $booking->booking_type, 'user_id' => $booking->user_id];
            }
        }
        //
        //echo '<pre>sssss';print_r($booked_room_arr); die;
        return $booked_room_arr;
    }

	public function testemail(){


\Mail::send('email.reply', ['title' => 'test email', 'message_string' => 'test email body'], function ($message) {
 $message->from(env("USER_EMAIL"), 'Kayakalp');
                    $message->subject("kayaklp testing email: ");
                    $message->to('swati_8491@yahoo.co.in');
                });
                }

}
