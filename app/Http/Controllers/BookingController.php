<?php

namespace App\Http\Controllers;

use App\AdminSetting;
use App\Country;
use App\EmailTemplate;
use App\ExternalService;
use App\HealthIssue;
use App\Http\Controllers\Laralum\Laralum;
use App\Member;
use App\Notification;
use App\PaymentDetail;
use App\Profession;
use App\Role;
use App\Settings;
use App\Transaction;
use App\UserAddress;
use App\UserProfile;
use App\Room;
use App\Room_Type;
use App\Booking;
use App\User;
use App\Wallet;
use App\State;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\Translation\Dumper\IniFileDumper;
use Validator;
use Session;
use Log;
use DateTime;

class BookingController extends Controller
{
    /**
     * view for booking process
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function guestBookingSignup()
    {
        $user = new User();
        \Session::forget('user_id');
        \Session::forget('profile_id');
        \Session::forget('txn_id');
        \Session::forget('booking_id');
        \Session::forget('health_issues');
        \Session::forget('aggreement_accepted');
        \Session::forget('payment_method');
        \Session::forget('booking_id');

        if (\Session::get('user_id') != null) {
            $user = User::find(\Session::get('user_id'));
        }

        return view('booking.guest-booking-signup', compact('user'));
    }

    public function guestBookingSignupStore(Request $request)
    {
        $error_messages = User::getErrorMessages(true);

        $check_pas = false;
        $user = [];

        if (\Session::get('user_id') != null) {
            $user = User::find(\Session::get('user_id'));
            if ($user != null) {
                $check_pas = true;
            }
        }

        if ($request->get('user_id')) {
            $user = User::find($request->get('user_id'));
        }


        if ($user != null) {
            $check_pas = true;
            Session::put('user_id', $user->id);
            if ($user->userProfile != null) {
                Session::put('profile_id', $user->userProfile->id);
                if ($user->userProfile->health_issues != null) {
                    Session::put('health_issues', true);
                }
            }
        } else {
            $user = new User();
        }

        $rules = $user->getRules(true);

        $validator = \Validator::make($request->all(), $rules, $error_messages);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        if ($check_pas == true) {
            $credentials = ['email' => $user->email, 'password' => $request->get('user')['password']];

            if (!\Auth::validate($credentials)) {
                return redirect()->back()->withInput()->with(['status' => 'danger', 'message' => 'Invalid password, please recover your password or try using another credentials.']);
            }
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

                if($user->uhid == null) {
                    $user->uhid = $user->getUhid();
                }
                //  $user->active=1;
                $user->save();

                Session::put('user_id', $user->id);
                Session::put('password', $data['password']);
                $user->saveRole(Role::ROLE_PATIENT);
                $user->sendActivationEmail();

                if ($user->activation_key == null) {
                    try {
                        $user->sendActivationEmail(); // send activation mail to user
                    } catch (\Exception $e) {
                        Log::error("Failed to send account activation mail, possible causes: " . $e->getMessage());
                    }
                }

                $user->save();

                /*
                $booking = Booking::where([
                    'user_id' => $user->id,
                    'status' => Booking::STATUS_COMPLETED
                ])->first();

                if ($booking != null) {
                    $credentials = ['email' => $user->email, 'password' => $request->get('user')['password']];

                    if (\Auth::attempt($credentials)) {
                        return redirect('home');
                    }
                }*/

                return redirect()->route('guest.booking.personalDetails')->with(['status' => 'success', 'message' => 'Signup is completed successfully, Please fill personal details now.']);
            }

            return redirect()->back()->with(['status' => 'danger', 'message' => 'Something went wrong .']);

        } catch (\Exception $e) {
            \Log::error("Failed to add the user data during booking process. Possible causes: " . $e->getMessage());
            return redirect()->back()->with(['status' => 'danger', 'message' => 'Failed to process your request. Please try again later.']);
        }
    }

    public function guestBookingPersonalDetails(Request $request, $id = null)
    {
        if ($id === null) {
            if (Session::has('user_id')) {
                $id = Session::get('user_id');
                $user = User::find($id);
                $booking = $user->getBooking(Booking::STATUS_PENDING);
                $profile = new UserProfile();
                $address = new UserAddress();
                if ($booking->id != null) {
                    $profile = $booking->userProfile;
                    if ($profile == null)
                        $profile = new UserProfile();
                    $address = $profile->address;
                    if ($address == null)
                        $address = new UserAddress();
                }
            } elseif ($request->skip == 1) {
                $user = new User();
                $profile = new UserProfile();
                $address = new UserAddress();
                $no_flags = Laralum::noFlags();
                $countries = Country::pluck('name', 'sortname')->toArray();
                $states = State::where('country_id', 101)->pluck('name')->toArray();
                $booking = new Booking();

                $data['user'] = $user;
                $data['profile'] = $profile;
                $data['address'] = $address;
                $data['no_flags'] = $no_flags;
                $data['countries'] = $countries;
                $data['booking'] = $booking;

                return view('booking.guest-booking-personal-details', compact('user', 'profile', 'address', 'booking', 'states'));
            } else {
                return redirect()->route('guest.booking.signup');
            }
        } else {
            $booking = Booking::find($id);
            $user = $booking->user;
            $profile = $booking->userProfile;
            $address = $profile->address;
        }

        $countries = Laralum::countries();
        $no_flags = Laralum::noFlags();
        $states = State::where('country_id', 101)->pluck('name')->toArray();

        /*if (\Auth::check())
            return view('laralum.booking.personal-details', compact('user', 'profile', 'address', 'countries', 'flags', 'booking'));*/

        return view('booking.guest-booking-personal-details', compact('user', 'profile', 'address', 'booking', 'states'));
    }

    public function guestBookingPersonalDetailsStore(Request $request, $id = null)
    {
        $error_messages = UserProfile::getErrorMessages(true);
        $user_id = Session::get('user_id');

        if ($user_id == null) {
            $rules = [
                /*'userProfile.mobile' => 'required|unique:users,mobile_number',*/
                'userProfile.mobile' => 'required'
            ];
            $error_messages = [];

            $validator = \Validator::make($request->all(), $rules, $error_messages);

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput()->with(['status' => 'danger', 'message' => 'Please check the errors below.']);
            }

            $random = str_random(6);
            $r_pr = $request->userProfile;

            $user = User::where('mobile_number', $r_pr['mobile'])->first();

            if ($user == null) {
                $user = new User();

                $user = User::create([
                    'mobile_number' => $r_pr['mobile'],
                    'password' => Hash::make($random),
                    'country_code' => 'IN',
                    'uhid' => $user->getUhid()
                ]);

                \Session::put('user_id', $user->id);
                $user->saveRole(Role::ROLE_PATIENT);
                $user->sendPassword($random);
            }

            $userProfile = new UserProfile();
            $userProfile->user_id = $user->id;

            $userAddress = new UserAddress();
            $userAddress->user_id = $user->id;
            $booking = $user->getBooking(Booking::STATUS_PENDING);
        } else {
            $user = User::find($user_id);
            $booking = $user->getBooking(Booking::STATUS_PENDING);
            $userProfile = new UserProfile();
            $userAddress = new UserAddress();
        }

        if ($booking->id != null) {
            $userProfile = $booking->userProfile;
            if ($userProfile == null)
                $userProfile = new UserProfile();
            $userAddress = $userProfile->address;
            if ($userAddress == null)
                $userAddress = new UserAddress();
        }

        if ($id != null) {
            $booking = Booking::find($id);
            $user = $booking->user;
            $userProfile = $booking->userProfile;
            if ($userProfile == null)
                $userProfile = new UserProfile();
            $userAddress = $userProfile->address;
            if ($userAddress == null)
                $userAddress = new UserAddress();
        }

        $userProfileData = $request->get('userProfile');
        $userProfileData['file'] = $request->file('profile_picture');

        $rules = $userProfile->getRules(true);
        $address_rules = $userAddress->getRules();
        $rule_array = array_merge($address_rules, $rules);
        $validator = \Validator::make($request->all(), $rule_array, $error_messages);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput()->with(['status' => 'danger', 'message' => 'Please check the errors below.']);
        }

        if (!empty($request->file('profile_picture'))) {
            if ($request->file('profile_picture')->getSize() > 2097152 || $request->file('profile_picture')->getSize() == "") {
                return redirect()->back()->withErrors($validator)->withInput()->with(['status' => 'danger', 'message' => 'One or more files exceeds the max size limit of 2MB.']);
            }
        }

        if (!$userProfile->checkDocuments($request)) {
            return redirect()->back()->withErrors($validator)->withInput()->with(['status' => 'danger', 'message' => 'One or more files exceeds the max size limit of 2MB.']);
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
                }
            }

            $userProfile->save();
            $booking->user_id = $user->id;
            $booking->patient_type = $userProfile->patient_type;
            $booking->status = Booking::STATUS_PENDING;
            $booking->profile_id = $userProfile->id;
            $booking->save();
            $userProfile->saveDocuments($request);
            /* add user address */

            $userAddressData = $request->get('userAddress');

            if ($userAddress->setData($userAddressData, $user->id)) {
                $userAddress->profile_id = $userProfile->id;

                $userAddress->save();
                $userAddress->update([
                    'profile_id' => $userProfile->id,
                ]);
            }
        }

        $user_id = Session::get('user_id');
        if ($user_id == null) {
            Session::put('user_id', $user->id);
        }
        /* if (\Auth::check())
             return redirect()->route('Laralum::user.booking.health_issues');*/

        return redirect()->route('guest.booking.health_issues');
        /* } catch (\Exception $e) {

             Log::error("Failed to add the personal details during booking process. Possible causes: " . $e->getMessage());
             return redirect()->back()->with(['status' => 'danger', 'message' => 'Something went wrong .']);
         }*/
        return redirect()->back()->withInput()->with('error', 'Something went wrong!!!');
    }

    public function getStates($id)
    {
        $country_id = Country::where('sortname', $id)->pluck('id')->first();
        return State::where('country_id', $country_id)->pluck('name')->toArray();
        // return $country_id;
    }


    public function guestBookingHealthIssues()
    {
        $user_id = Session::get('user_id');
        $user = User::find($user_id);
        if ($user != null) {
            $booking = $user->getBooking(Booking::STATUS_PENDING);

            if ($booking->id == null && $booking->userProfile == null) {
                return redirect()->route('guest.booking.personalDetails');
            }
            $profile = $booking->userProfile;

            $healthIssues = HealthIssue::where([
                'user_id' => $user->id,
                'status' => Booking::STATUS_PENDING,
                'booking_id' => $booking->id
            ])->first();

            if ($healthIssues == null) {
                $healthIssues = new HealthIssue();
            }

            return view('booking.guest-booking-health-issues', compact('user', 'profile', 'healthIssues'));
        }
        return redirect()->route('guest.booking.signup');
    }

    public function guestBookingHealthIssuesStore(Request $request)
    {
        $user_id = Session::get('user_id');
        $user = User::find($user_id);
        $booking = $user->getBooking(Booking::STATUS_PENDING);


        if (!empty($request->all())) {
            if (isset($request->health_issues) && !empty($request->health_issues)) {
                try {
                    $profile = $booking->userProfile;
                    $profile->update(['health_issues' => $request->health_issues]);

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

                    if ($user->checkAccommodation()) {
                        return redirect()->route('guest.booking.accommodation');
                    } else {
                        return redirect()->route('guest.booking.aggreement');
                    }
                } catch (\Exception $e) {
                    echo '<pre>';
                    print_r($e->getMessage());
                    exit;
                    Log::error("Failed to add the health issues during booking process. Possible causes: " . $e->getMessage());
                    return redirect()->back()->with(['status' => 'danger', 'message' => 'Something went wrong .']);
                }
            }
        }

        return redirect()->back()->with('error', 'Please fill the form');
    }

    public function guestBookingAccommodation(Request $request)
    {
        $user_id = Session::get('user_id');
        $user = User::find($user_id);

        if ($user != null) {

            $booking = $user->getBooking(Booking::STATUS_PENDING);

            if ($booking->id == null && $booking->userProfile == null) {
                return redirect()->route('guest.booking.personalDetails');
            }

            if ($booking->healthIssues == null) {
                return redirect()->route('guest.booking.health_issues');
            }
            $booking->check_in_date = $booking->check_in_date != "0000-00-00" || $booking->check_in_date != ""  ? date("d-m-Y") : "";
            $booking->check_out_date = $booking->check_out_date != "0000-00-00" || $booking->check_in_date != "" ? date("d-m-Y", strtotime("+1 day")) : "";
            $booking->external_services = explode(',', $booking->external_services);
            if ($booking->booking_type == null) {
                $booking->booking_type = "";
            }
            $members = $booking->members;

            return view('booking.guest-booking-accommodation', compact('user', 'profile', 'user_id', 'booking', 'members'));
        }

        return redirect()->route('guest.booking.signup');
    }

    public function guestBookingAccommodationRequest(Request $request)
    {
        $user_id = Session::get('user_id');
        $user = User::find($user_id);
        $model = $user->getBooking(Booking::STATUS_PENDING);

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
            'check_out_date' => 'required|after:today|greater_than:check_in_date',
            'check_in_date' => 'required|after:today'
        ]);

        $validator = \Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors());
        }
        $model->setData($request);
        $check = $model->checkBooking();

        if ($check == true) {
            $model->save();
            $model->saveMembers($request);
            return redirect(route('guest.booking.aggreement', ['user_id' => $request->get('user_id')]))->with('success', 'Booking has been completed successfully.');
        }

        return redirect()->back()->with('error', "No Room Available for these dates.");
    }


    public function guestBookingChart($request)
    {
        $accordian_status_rw = 0;
        if (!empty($request->all())) {
            if (isset($request->select_date) && !empty($request->select_date)) {
                $select_date = $request->select_date;
                $select_date_formatted = date($select_date);
                $select_date_formatted = date('Y-m-d 00:00:00', strtotime($select_date));//time();
                $default_date = $select_date;
                $accordian_status_rw = 1;
            } else {
                $select_date_formatted = date('Y-m-d 00:00:00');//time();
                $default_date = date('Y-m-d');
            }
        } else {
            $select_date_formatted = date('Y-m-d 00:00:00');//time();
            $default_date = date('Y-m-d');
        }
        $room_id_arr = [];
        $rooms_share_avail = [];
        $rooms_share_gender = [];
        $vacant_room_arr = [];
        $total_partial_vacant = [];
        $single_occupancy_booked = [];
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
            ->whereDate('bookings.check_in_date', '<=', $select_date_formatted)
            ->whereDate('bookings.check_out_date', '>=', $select_date_formatted)
            ->whereNotIn('bookings.status', [Booking::STATUS_CANCELLED, Booking::STATUS_DISCHARGED])
            ->where('rooms.status', 1)
            ->select('rooms.*', 'rooms.id as room_id', 'bookings.*', 'bookings.id as booking_id', 'user_profiles.gender', 'user_profiles.user_id as user_id', 'room_types.id as room_type_id')
            ->get();

        foreach ($booked_rooms as $bk_room) {
            // create booking array based on room id
            $room_booked_arr[$bk_room->room_id][] = ['booking_type' => $bk_room->booking_type, 'booking_gender' => $bk_room->gender, 'booking_id' => $bk_room->booking_id];
            $room_id_arr[$bk_room->id] = $bk_room->id;
            //$two_user_booked_signle_room[$bk_room->room_type_id][$bk_room->room_number][] = user_uid;
            if (in_array($bk_room->room_number, $room_number_iterate)) {
                unset($rooms_share_avail[$bk_room->room_type_id][$bk_room->room_number]);
            } else {
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
        foreach ($rooms_share_avail as $room_type => $room_share_avail) {
            foreach ($room_share_avail as $room_number => $gender) {
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
            ->where('rooms.status', 1)
            ->whereNotIn('rooms.id', $room_id_arr)
            ->Join('room_types', 'rooms.room_type_id', '=', 'room_types.id')
            ->select('rooms.*', 'room_types.id as room_type_id')
            ->get();
        foreach ($vacant_rooms as $vacant_room) {
            $vacant_room_arr[$vacant_room->room_type_id][] = $vacant_room->room_number;
        }
        //echo '<pre>'; print_r($vacant_rooms); echo '</pre>'; die;
        $room_types = Room_Type::all();
        foreach ($room_types as $room_type) {
            $total_vacant_rooms = 0;
            $total_partial_rooms = 0;
            $female_count = 0;
            $male_count = 0;
            $any_gender = 0;
            $single_occupancy_rooms = 0;
            // Get total rooms
            if (isset($vacant_room_arr[$room_type->id])) {
                $total_vacant_rooms = count($vacant_room_arr[$room_type->id]);
                // double vacant room as two bed is available for booking in each room
                $total_vacant_rooms = 2 * $total_vacant_rooms;
            }
            // get total sharing booking
            if (isset($rooms_share_gender[$room_type->id])) {
                $female_count = $rooms_share_gender[$room_type->id]['female_count'];
                $male_count = $rooms_share_gender[$room_type->id]['male_count'];
                $total_partial_rooms = $female_count + $male_count;
            }
            // get total single occupancy booking
            if (isset($single_occupancy_booked[$room_type->id])) {
                $single_occupancy_rooms = count($single_occupancy_booked[$room_type->id]);
            }
            // double the single occupancy rooms as basically two bed are booked as single occupancy;
            $rooms = Room::where('room_type_id', $room_type->id)
                ->where('status', 1);
            $total = $rooms->count();
            // Double th room total because each room has two beds
            $total = 2 * $total;
            $total_vacant_rooms = $total_vacant_rooms + $total_partial_rooms;
            $total_partial_vacant[$room_type->id] = ['room_type' => $room_type->name, 'total' => $total, 'total_vacant_rooms' => $total_vacant_rooms, 'female' => $female_count, 'male' => $male_count];
            $room_types_arr[$room_type->id] = $room_type->name;
        }
        //Room::where('status',1)
        $rooms_obj = DB::table('rooms')
            ->leftjoin('room_types', 'room_types.id', '=', 'rooms.room_type_id')
            ->leftjoin('buildings', 'buildings.id', '=', 'rooms.building_id')
            ->where('rooms.status', 1)
            ->select('rooms.id as room_id', 'rooms.room_number', 'rooms.room_type_id', 'rooms.building_id', 'rooms.floor_number',
                'room_types.id as room_type_id', 'room_types.name as room_type_name', 'room_types.short_name as room_type_short_name',
                'buildings.name as building_name')
            ->get();
        $building_arr = [];
        foreach ($rooms_obj as $room) {
            if (isset($room_booked_arr[$room->room_id])) {
                $booking_status = $room_booked_arr[$room->room_id];
            } else {
                $booking_status = [];
            }
            $building_arr[$room->building_id] = ['building_name' => $room->building_name,
                'room_type_name' => $room->room_type_name];
            $room_data[$room->building_id][] = [
                'room_id' => $room->room_id,
                'room_number' => $room->room_number,
                'booking_status' => $booking_status,
                'room_type_short_name' => $room->room_type_short_name,
                'floor_number' => $room->floor_number
            ];

        }
        foreach ($building_arr as $building_id => $building) {
            $room_arr[$building_id] = ['building_data' => $building, 'room_data' => $room_data[$building_id]];
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
        $data['accordian_status_rw'] = $accordian_status_rw;
        return $data;
    }

    public function getBookingInfo($booking_ids, $room_id)
    {
        $booking_id_arr = explode('-', $booking_ids);
        $booked_info = Booking::join('rooms', 'rooms.id', '=', 'bookings.room_id')
            ->join('user_profiles', 'bookings.user_id', '=', 'user_profiles.user_id')
            ->whereIN('bookings.id', $booking_id_arr)
            ->whereNotIn('bookings.status', [Booking::STATUS_CANCELLED, Booking::STATUS_DISCHARGED])
            ->select('rooms.*', 'rooms.id as room_id', 'bookings.*', 'user_profiles.first_name', 'user_profiles.last_name')
            ->get();

        $data['booked_info'] = $booked_info;

        if ($booked_info->count() == 1) {
            foreach ($booked_info as $booked) {
                if ($booked->user_id == \Session::get('user_id')) {
                    return $this->accommBookingForm($room_id, $booking_ids);
                }
            }
        }
        $data['user_id'] = \Session::get('user_id');
        // $data['user_info'] = $user_info;
        return view('booking.get-booking-info', $data);
    }

    public function accommBookingForm($room_id, $booking_ids = null)
    {
        $user_id = \Session::get('user_id');
        $user_info = [];
        $external_services = [];
        if (is_numeric($user_id)) {
            $user_obj = User::find($user_id);
        }
        $external_services_obj = ExternalService::where('status', 1)->get();
        if (!$external_services_obj->isEmpty()) {
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
        return view('booking.accommodation-booking-form', $data);
    }

    /**
     * view for booking process
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function guestBookingChartmw(Request $request)
    {
        $accordian_status_mw = 0;
        if (!empty($request->all())) {
            if (isset($request->select_month_year) && !empty($request->select_month_year)) {
                $month_year = $request->select_month_year;
                $month_year_arr = explode('-', $month_year);
                $month = $month_year_arr[0];
                $year = $month_year_arr[1];
                $accordian_status_mw = 1;
            } else {
                $month = date('m');
                $year = date('Y');
            }
        } else {
            $month = date('m');
            $year = date('Y');
        }
        $room_status_arr = [];
        $month_days_count = cal_days_in_month(CAL_GREGORIAN, $month, $year);
        $rooms_status_arr['month_data'] = ['month_date_count' => $month_days_count, 'year' => $year, 'month' => $month];
        $rooms = DB::table('rooms')
            ->join('room_types', 'room_types.id', '=', 'rooms.room_type_id')
            ->join('buildings', 'buildings.id', '=', 'rooms.building_id')
            ->where('rooms.status', 1)
            ->select('rooms.*', 'room_types.short_name', 'buildings.name as building_name')
            ->get();
        if (!$rooms->isempty()) {
            foreach ($rooms as $room) {
                for ($month_day = 1; $month_day <= $month_days_count; $month_day++) {
                    $month_day = sprintf("%02d", $month_day);

                    $room_id = $room->id;
                    $room_status_arr['building'] = $room->building_name;
                    $room_status_arr['room_type'] = $room->short_name;
                    $room_status_arr['room_id'] = $room->id;
                    $room_status_arr['room_number'] = $room->room_number;
                    $room_status_arr['days_bookings'][$month_day] = $this->getRoomMonthStatus($room_id, $month_day, $month, $year);
                }
                $rooms_status_arr['rooms_data']['roomm-' . $room->id] = $room_status_arr;
            }
        }
        //echo '<pre>';print_r($rooms_status_arr); die;
        //$data['rooms_status_arr'] = $rooms_status_arr;
        $month_wise_arr = ['accordian_status_mw' => $accordian_status_mw, 'rooms_status_arr' => $rooms_status_arr];
        return $month_wise_arr;
        //return view('booking.booking_chart_month_wise',$data);
    }

    public function accommBookingFormStore(Request $request)
    {
        $booking = Booking::where([
            'user_id' => $request->get('user_id'),
            'status' => Booking::STATUS_PENDING
        ])->first();
        if ($booking == null) {
            $booking = new Booking();
        }
        $booking->user_id = $request->get('user_id');
        $booking->room_id = $request->get('room_id');
        $booking->booking_type = $request->get('booking_type');
        $booking->check_in_date = $request->get('check_in_date');
        $booking->check_out_date = $request->get('check_out_date');


        $booking->status = Booking::STATUS_PENDING;

        $booking->save();
        $booking->deleteServices();
        $booking->saveServices($request->get('external_services'));

        /*$txn_id = $booking->saveTransaction($request->get('total_price'));*/
        \Session::put('booking_id', $booking->id);
        return redirect(route('guest.booking.aggreement', ['user_id' => $request->get('user_id')]))->with('success', 'Booking has been completed successfully.');
        // return view('booking.booking_chart_room_wise',$data);
    }

    public function guestBookingAggreement()
    {
        $user_id = Session::get('user_id');
        $user = User::find($user_id);
        if ($user) {
            $booking = $user->getBooking(Booking::STATUS_PENDING);

            if ($booking->id == null && $booking->userProfile == null) {
                return redirect()->route('guest.booking.personalDetails');
            }

            if ($booking->healthIssues == null) {
                return redirect()->route('guest.booking.health_issues');
            }

            if ($booking->healthIssues == null) {
                return redirect()->route('guest.booking.health_issues');
            }
            if ($user->checkAccommodation()) {
                if ($booking->building_id == null) {
                    return redirect()->route('guest.booking.accommodation');
                }
            }

            return view('booking.guest-booking-aggreement', compact('user'));
        }

        return redirect()->route('guest.booking.signup');
    }

    public function guestBookingAggreementStore()
    {
        \Session::put('aggreement_accepted', true);
        return redirect()->route('guest.booking.payment');
    }

    public function guestBookingPayment()
    {
        $user_id = Session::get('user_id');
        $user = User::find($user_id);
        if ($user) {
            $booking = $user->getBooking(Booking::STATUS_PENDING);

            if ($booking->id == null && $booking->userProfile == null) {
                return redirect()->route('guest.booking.personalDetails');
            }

            if ($booking->healthIssues == null) {
                return redirect()->route('guest.booking.health_issues');
            }

            if ($booking->healthIssues == null) {
                return redirect()->route('guest.booking.health_issues');
            }
            if ($user->checkAccommodation()) {
                if ($booking->building_id == null) {
                    return redirect()->route('guest.booking.accommodation');
                }
            }

            if (\Session::has('aggreement_accepted')) {
                return view('booking.guest-booking-payment', compact('user'));
            } else {
                return redirect()->route('guest.booking.aggreement');
            }
        }
        return redirect()->route('guest.booking.signup');
    }

    public function guestBookingPaymentStore(Request $request)
    {
        \Session::put('payment_method', $request->get('payment_method'));
        $user_id = Session::get('user_id');
        $user = User::find($user_id);
        if ($user) {
            $booking = $user->getBooking(Booking::STATUS_PENDING);

            if ($booking->id == null && $booking->userProfile == null) {
                return redirect()->route('guest.booking.personalDetails');
            }

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
                $wallet = Wallet::create([
                    'user_id' => $user_id,
                    'amount' => AdminSetting::getSettingPrice('advance_payment'),
                    'type' => Wallet::TYPE_PAID,
                    'created_by' => $user_id,
                    'status' => Wallet::STATUS_PAID,
                    'payment_method' => $request->get('payment_method'),
                    'booking_id' => $booking->id,
                    'description' => $request->get('description'),
                ]);
            }

            return redirect()->route('guest.booking.confirm');
        }
        return redirect()->route('guest.booking.signup');
    }

    public function guestBookingConfirm()
    {
        $user_id = Session::get('user_id');
        $user = User::find($user_id);
        if ($user) {
            $booking = $user->getBooking(Booking::STATUS_PENDING);

            if ($booking->id == null && $booking->userProfile == null) {
                return redirect()->route('guest.booking.personalDetails');
            }

            if ($booking->booking_id == null) {
                $booking->booking_id = $booking->getIdNumber();
            }

            $profile = $booking->userProfile;

            if ($booking->healthIssues == null) {
                return redirect()->route('guest.booking.health_issues');
            }

            if ($booking->healthIssues == null) {
                return redirect()->route('guest.booking.health_issues');
            }
            $healthIssues = $booking->healthIssues;

           /* if ($profile->patient_type == UserProfile::PATIENT_TYPE_IPD && $profile->kid == null) {
                $profile->update([
                    'kid' => User::getId("K-IPD", $profile->getIdNumber())
                ]);
            } else if ($profile->kid == null) {
                $profile->update([
                    'kid' => User::getId("K-OPD", $profile->getIdNumber())
                ]);
            }*/

            if ($profile->patient_type == UserProfile::PATIENT_TYPE_OPD && $profile->kid == null) {
                $profile->update([
                    'kid' => User::getId("K-OPD", $profile->getIdNumber())
                ]);
            }


            if ($user->checkAccommodation()) {
                if ($booking->building_id == null) {
                    return redirect()->route('guest.booking.accommodation');
                }
            }

            if (!\Session::has('aggreement_accepted')) {
                return redirect()->route('guest.booking.aggreement');
            }

            if ($user->checkPaymentMethod($booking->id)) {
                return view('booking.guest-booking-confirm', compact('user', 'healthIssues', 'booking', 'profile'));
            }

            return redirect()->route('guest.booking.payment');
        }


        return redirect()->route('guest.booking.signup');
    }

    public function guestBookingConfirmStore(Request $request)
    {
        $user_id = Session::get('user_id');
        $password = Session::get('password');
        $user = User::find($user_id);

        $booking = $user->getBooking(Booking::STATUS_PENDING);

        if ($booking->id == null) {
            $booking = $user->getBooking(Booking::STATUS_COMPLETED);
        }

        if ($booking->booking_id == "") {
            $booking->update([
                'booking_id' => $booking->getIdNumber(),
            ]);
        }
        $profile = $booking->userProfile;

        if ($profile->kid == null) {
            /*if ($profile->patient_type == UserProfile::PATIENT_TYPE_IPD && $profile->kid == null) {
                $profile->update([
                    'kid' => User::getId("K-IPD", $profile->getIdNumber())
                ]);
            } else if ($profile->kid == null) {
                $profile->update([
                    'kid' => User::getId("K-OPD", $profile->getIdNumber())
                ]);
            }*/

            if ($profile->patient_type == UserProfile::PATIENT_TYPE_OPD && $profile->kid == null) {
                $profile->update([
                    'kid' => User::getId("K-OPD", $profile->getIdNumber())
                ]);
            }

        }
        $booking->update([
            'status' => Booking::STATUS_COMPLETED
        ]);


        $healthIssues = $booking->healthIssues;
        $healthIssues->status = HealthIssue::STATUS_COMPLETED;
        $healthIssues->booking_id = $booking->id;
        $healthIssues->save();
        Notification::saveNotification($user, "New Booking", $user->id);
        /* $user->sendActivationEmail();*/

        /*if (!empty($user->email)) {
            $data = $booking->setMailData();
            EmailTemplate::sendEmail(EmailTemplate::EVENT_BOOKING, $data, $user->email);
        }*/
//
//        if (\Auth::attempt(['email' => $user->email, 'password' =>$password ])) {
//            return redirect()->intended('/home')->with(['status' => 'success', 'message' => 'Your booking has been completed successfully. Please check your email to activate your account']);;
//        }

        return redirect()->route('thank-you')->with(['status' => 'success', 'message' => 'Your booking has been completed successfully. Please check your email to activate your account']);
    }

    public function guestBookingConfirmStoreOld(Request $request)
    {
        $transaction = Transaction::where('user_id', \Session::get('user_id'))->first();

        if ($transaction == null) {
            $transaction = new Transaction();
        }
        $patient_id = "K-" . \Session::get('user_id') . date("mdY");
        if ($transaction != null) {
            $transaction = $transaction->fill([
                'user_id' => \Session::get('user_id'),
                'txn_id' => 'PATIENT-' . $patient_id,
                'payment_method' => \Session::get('payment_method'),
                'amount' => $request->get('total_amount'),
                'status' => Transaction::STATUS_COMPLETED,
                'discount_amount' => $request->get("discount_amount"),
                'discount_id' => $request->get("discount_id"),
                'payable_amount' => $request->get("payable_amount")
            ]);
            $transaction->save();

            $wallet = Wallet::create([
                'user_id' => $transaction->user_id,
                'amount' => $request->get('payable_amount'),
                'type' => Wallet::TYPE_PAID,
                'status' => Wallet::STATUS_PAID,
                'model_id' => $transaction->id,
                'model_type' => get_class($transaction),
                'txn_id' => 'Transaction-' . $patient_id,
                'created_by' => $transaction->user_id,
            ]);
            $transaction->user->userProfile->update([
                'kid' => $patient_id
            ]);
            $transaction->update([
                'status' => Transaction::STATUS_COMPLETED
            ]);

            if (Session::has('booking_id')) {
                $transaction->update([
                    'booking_id' => Session::get('booking_id')
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
            Notification::saveNotification($transaction->user, "New Booking", $transaction->user_id);
            $transaction->user->SendActivationEmail();
            $transaction->user->update([
                'is_discharged' => User::ADMIT
            ]);
            $transaction->sendBookingEmail();
        }

        return redirect()->route('thank-you')->with(['status' => 'success', 'message' => 'Your booking has been completed successfully. Please check your email to activate your account']);

    }

    public function postBooking(Request $request)
    {
        $error_messages = User::getErrorMessages(true);
        $rules = User::getRules(true);

        $validator = \Validator::make($request->all(), $rules, $error_messages);

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
                $user->password = bcrypt($data['password']);
                $user->activation_key = $activation_key;
                $user->country_code = "IN";
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

                try {

                    $user->SendActivationEmail(); // send activation mail to user

                } catch (\Exception $e) {
                    Log::error("Failed to send account activation mail, possible causes: " . $e->getMessage());
                }

                /* redirect to admin if request from admin panel */
                if ($request->has('admin')) {
                    return redirect()->to('/admin/bookings')->with('success', 'Booking has been completed successfully.');
                } else {
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
    private function getRoomMonthStatus($room_id, $month_day, $month, $year)
    {
        $booked_room_arr = [];
        /*DB::enableQueryLog();
        dd(DB::getQueryLog());*/
        //$month_days_timestamp = strtotime($month_day.'-' . $month . '-' . $year);
        $month_days_date = date($year . '-' . $month . '-' . $month_day . ' ' . '00:00:00');
        $booking_arr = Booking::where('room_id', '=', $room_id)
            ->whereDate('check_in_date', '<=', $month_days_date)
            ->whereDate('check_out_date', '>=', $month_days_date)
            ->whereNotIn('status', [Booking::STATUS_CANCELLED, Booking::STATUS_DISCHARGED])
            ->get();
        if (!$booking_arr->isempty()) {
            foreach ($booking_arr as $booking) {
                $user_profile_obj = UserProfile::where('user_id', $booking->user_id)->first();
                $booked_room_arr[] = ['user_gender' => $user_profile_obj->gender, 'booking_id' => $booking->id, 'booking_type' => $booking->booking_type, 'user_id' => $booking->user_id];
            }
        }
        //
        //echo '<pre>sssss';print_r($booked_room_arr); die;
        return $booked_room_arr;
    }

    public function getRoomStatus(Request $request)
    {
        $date_in = $request->get('date_in');
        $date_out = $request->get('date_out');

        $date_in_stamp = strtotime($date_in); // or your date as well
        $date_out_stamp = strtotime($date_out);
        $datediff = $date_out_stamp - $date_in_stamp;
        $datediff = floor($datediff / (60 * 60 * 24));

        $room = Room::find($request->get('room_id'));
        $b = false;
        $nok = false;
        $result['status'] = 'NOK';
        for ($i = 0; $i < $datediff; $i++) {
            $date = date('Y-m-d H:i:s', strtotime($date_in . ' +' . $i . ' days'));
            $booking_arr = Booking::where('room_id', $request->get('room_id'))
                ->where('user_id', '!=', $request->get('user_id'))
                ->whereDate('check_in_date', '<=', $date)
                ->whereDate('check_out_date', '>=', $date)
                ->where('user_id', '!=', $request->get('user_id'))
                ->whereNotIn('status', [Booking::STATUS_CANCELLED, Booking::STATUS_DISCHARGED])
                ->get();

            if (!$booking_arr->isempty()) {
                foreach ($booking_arr as $booking) {
                    $b = true;
                    if ($room->bed_type == Room::BED_TYPE_SINGLE) {
                        if ($booking->booking_type == Booking::BOOKING_TYPE_SINGLE_OCCUPANCY || $booking->booking_type == Booking::BOOKING_TYPE_SINGLE_OCCUPANCY_EB) {
                            $nok = true;
                        }
                    } else {
                        if ($booking->booking_type == Booking::BOOKING_TYPE_DOUBLE_BED || $booking->booking_type == Booking::BOOKING_TYPE_DOUBLE_BED_EB) {
                            $nok = true;
                        }
                    }
                }
            }

            if ($b == true && $nok == true) {
                return ['status' => 'NOK'];
            } elseif ($b == true && $nok == false) {
                $result['status'] = 'OK';
                if ($room->bed_type == Room::BED_TYPE_SINGLE) {
                    $result['data'] = Booking::getSingleBedTypesCheckboxes(true);
                } else {
                    $result['data'] = Booking::getDoubleBedTypesCheckboxes(true);
                }
                return $result;
            } else {
                $result['status'] = 'OK';
                if ($room->bed_type == Room::BED_TYPE_SINGLE) {
                    $result['data'] = Booking::getSingleBedTypesCheckboxes();
                } else {
                    $result['data'] = Booking::getDoubleBedTypesCheckboxes();
                }
                return $result;
            }
        }
        return $result;

    }

    public function checkEmail(Request $request)
    {
        $result = [
            'success' => true
        ];
        $email = $request->get('email');
        if ($email) {
            $user = User::where([
                'email' => trim($email),
            ])->first();

            if ($user != null) {
                $result['success'] = false;
                $result['user_id'] = $user->id;
            }
        }

        return $result;
    }

    public function deleteMember(Request $request)
    {
        $id = $request->get('id');
        $member = Member::find($id);
        $user_id = $request->get('user_id');

        if ($member->user_id == $user_id) {
            $member->delete();
            return ['success' => true, 'id' => $id];
        }
        return ['success' => false];
    }

    public function personalDetails($id)
    {
        $user = \Auth::user();
        $booking = Booking::where([
            'id' => $id,
            'user_id' => $user->id,
        ])->first();

        $user = $booking->user;
        $profile = $booking->userProfile;
        $address = $profile->address;
        $countries = Laralum::countries();
        $no_flags = Laralum::noFlags();
        $user_country = $address->country;
        $country = Country::where('sortname', $user_country)->first();
        $country_id = $country->id;
        $states = State::where('country_id', $country_id)->pluck('name')->toArray();
        return view('booking.personal-details', compact('user', 'profile', 'address', 'no_flags', 'countries', 'booking', 'states'));
    }


    public function personalDetailsStore(Request $request, $id)
    {

        //return $request->all();
        // try {
        $booking = Booking::find($id);

        if (!$booking->isEditable())
            return redirect("admin/booking/" . $id . "/show")->with('error', 'You do not have edit access.');

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


    public function healthIssues($id = null)
    {
        $booking = Booking::find($id);

        if (!$booking->isAllowed()) {
            abort(401, "You don't have permissions to access this area");
        }

        /*if (!$booking->isEditable())
            return redirect("admin/booking/" . $id . "/show")->with('error', 'Patient is not active.');*/

        $user = $booking->user;
        $profile = $booking->userProfile;
        return view('booking.health_issues', compact('user', 'profile', 'booking'));
    }


    public function healthIssuesStore(Request $request, $id)
    {
        $booking = Booking::find($id);

        if (!$booking->isEditable())
            return redirect("admin/booking/" . $id . "/show")->with('error', 'You do not have edit access.');

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

        /*if (!$booking->isEditable())
            return redirect("admin/booking/" . $id . "/show")->back()->with('error', 'Patient is not active.');*/

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

        return view('booking.accommodation', $data);
    }

    public function accommodationRequest(Request $request, $id)
    {
        //return $request->all();

        $model = Booking::find($id);

        if (!$model->isAllowed()) {
            abort(401, "You don't have permissions to access this area");
        }

        if (!$model->isEditable())
            return redirect("admin/booking/" . $id . "/show")->with('error', 'You do not have edit access.');

        $user = $model->user;

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

        /*if (!$booking->isEditable())
            return redirect("admin/booking/" . $id . "/show")->back()->with('error', 'Patient is not active.');*/

        $user = $booking->user;
        $profile = $user->userProfile;
        return view('booking.payment', compact('user', 'booking'));
    }



    public function paymentStore(Request $request, $id)
    {
         $booking = Booking::find($id);
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
            else{
                $payment_detail->update([
                    'type' => $request->get('payment_method')
                ]);
            }


            if ($request->get('amount') > 0) {
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
            }
            return redirect("booking/" . $id . "/confirm");
        }
        return redirect()->back()->withInput()->with('error', "Something went wrong!!!");
    }

    public function confirm($id)
    {
        $booking = Booking::find($id);

        if (!$booking->isAllowed()) {
            abort(401, "You don't have permissions to access this area");
        }

        /*if (!$booking->isEditable())
            return redirect("admin/booking/" . $id . "/show")->back()->with('error', 'Patient is not active.');*/

        $user = $booking->user;
        $profile = $user->userProfile;
        $healthIssues = HealthIssue::where([
            'user_id' => $user->id,
            'status' => HealthIssue::STATUS_PENDING
        ])->first();

        if ($user->checkAccommodation($id)) {
            return view('booking.confirm', compact('user', 'booking', 'healthIssues'));
        }

        return view('laralum.booking.confirm', compact('user', 'booking', 'healthIssues'));
    }

    public function confirmStore(Request $request, $id)
    {
        //return $request->all();
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
            /*if ($profile->patient_type == UserProfile::PATIENT_TYPE_IPD && $profile->kid == null) {
                $profile->update([
                    'kid' => User::getId("K-IPD", $profile->getIdNumber())
                ]);
            } else if ($profile->kid == null) {
                $profile->update([
                    'kid' => User::getId("K-OPD", $profile->getIdNumber())
                ]);
            }*/

            if ($profile->patient_type == UserProfile::PATIENT_TYPE_OPD && $profile->kid == null) {
                $profile->update([
                    'kid' => User::getId("K-OPD", $profile->getIdNumber())
                ]);
            }


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
            /*if (!empty($booking->user->email)) {
                EmailTemplate::sendEmail(EmailTemplate::EVENT_BOOKING, $data, $user->email);
            }
            EmailTemplate::sendEmail(EmailTemplate::EVENT_BOOKING, $data, \Auth::user()->email);*/

        } else {
            $booking->update([
                'status' => Booking::STATUS_COMPLETED
            ]);
        }

        return redirect()->back()->with('success', 'Successfully Saved Details.');
    }
}
