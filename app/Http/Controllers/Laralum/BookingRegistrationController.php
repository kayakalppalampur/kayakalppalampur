<?php

namespace App\Http\Controllers\Laralum;

use App\Booking;
use App\BookingRoom;
use App\DietChart;
use App\DietChartItems;
use App\DietDailyStatus;
use App\DischargePatient;
use App\EmailTemplate;
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
use App\PaymentDetail;
use App\Profession;
use App\Role;
use App\Room;
use App\Settings;
use App\Transaction;
use App\TreatmentToken;
use App\User;
use App\UserAddress;
use App\UserProfile;
use App\Wallet;
use App\Country;
use App\State;
use function GuzzleHttp\Psr7\uri_for;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Input;
use Laralum;
use Milon\Barcode\DNS1D;
use Milon\Barcode\DNS2D;
use SebastianBergmann\Comparator\Book;

class BookingRegistrationController extends Controller
{
    /*STEP 1*/

    public function __construct()
    {
        Input::merge(array_map(function($v){
            return is_string($v)?trim($v):$v;
        }, Input::all()));
    }

    function trim_value($value) {
        $value = !is_array($value) ? trim($value) : '';
    }

    public function create(Request $request)
    {
        $user = new User();

        if ($request->id != null) {
            $user = User::find($request->id);
        }
        $booking = new Booking();
        $data['admin'] = true;
        $data['user'] = $user;
        $data['booking'] = $booking;

        if ($request->reregister) {
            $user_profile = UserProfile::find($request->reregister);
            $user = User::find($user_profile->user_id);
            $data['user'] = $user;
            $data['reregister'] = $request->reregister;
        }

        return view('laralum.booking_registration.signup', $data);
    }

    public function signupStore(Request $request)
    {
        $error_messages = User::getErrorMessages(true);

        if ($request->get('user_id') != "") {
            $user = User::find($request->get('user_id'));
            $booking = $user->current_booking;

            if ($booking) {
                return redirect()->back()->withInput()->with(['status' => 'danger', 'message' => 'Booking for this user already exists. <a href="' . url('admin/booking/personal_details/' . $booking->id) . '">Click here</a>']);
            }

        } else {
            $user = new User();
            $rules = $user->getRules(true);
            $validator = \Validator::make($request->all(), $rules, $error_messages);

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput()->with(['status' => 'danger', 'message' => 'Please check the errors below.']);
            }
            $user = [];
        }


        try {
            /* add user data */
            if ($user == null) {
	\DB::beginTransaction();
                $user = new User();
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
                    $user->save();
                    $user->saveRole(Role::ROLE_PATIENT);
                    $booking = new Booking();
                    $booking->user_id = $user->id;
                    $booking->status = Booking::STATUS_PENDING;
                    $booking->save();
                    try {
                        $user->SendActivationEmail(); // send activation mail to user
                    } catch (\Exception $e) {
                        Log::error("Failed to send account activation mail, possible causes: " . $e->getMessage());
                    }
                    $user->save();
		      \DB::commit();
                    return redirect()->route('Laralum::booking.registration.personalDetails', ['user_id' => $user->id])->with(['status' => 'success', 'message' => 'Signup is completed successfully, please fill personal details now.']);
                }
            } else {
                if ($request->reregister) {
                    return redirect()->route('Laralum::booking.registration.personalDetails', ['user_id' => $user->id, 'reregister' => $request->reregister])->with(['status' => 'success', 'message' => 'Signup is completed successfully, please fill personal details now.']);
                }
                return redirect()->route('Laralum::booking.registration.personalDetails', ['user_id' => $user->id])->with(['status' => 'success', 'message' => 'Signup is completed successfully, please fill personal details now.']);
            }

            return redirect()->back()->with(['status' => 'danger', 'message' => 'Something went wrong .']);

        } catch (\Exception $e) {
            Log::error("Failed to add the user data during booking process. Possible causes: " . $e->getMessage());
            return redirect()->back()->with(['status' => 'danger', 'message' => 'Failed to process your request. Please try again later.']);
        }
    }

    public function personalDetails(Request $request, $id = null)
    {
        //return "here";
        if ($id != null) {

            //return $id;
            $user = User::find($id);
            $completed_booking = $user->completed_booking;

            if ($completed_booking) {
                $errors = 'Booking already exists for this user.';
                $error = collect($errors);
                return redirect()->route('Laralum::booking.registration.create')->withErrors($error);
            }

            $booking = $user->current_booking;

            if ($booking) {
                $profile = $booking->userProfile;
            }

            if (empty($profile)) {
                $profile = new UserProfile();
            }

            $address = $profile->address;

            if (empty($address)) {
                $address = new UserAddress();
            }

            if (empty($booking)) {
                $booking = new Booking();
            }

            /*echo '<pre>';
            print_r($profile);
            print_r($address);
            exit;*/
            if ($user != null) {
                if ($request->reregister) {
                    $data['reregister'] = $request->reregister;
                    $profile = UserProfile::find($request->reregister);
                    $address = UserAddress::where('profile_id', $request->reregister)->first();
                    $user_country = $address->country;
                    $country = Country::where('sortname', $user_country)->first();
                    //dd($country);
                   // return $country->id;
                    $country_id = $country->id;
                    $states = State::where('country_id', $country_id)->pluck('name')->toArray();
                }
                else{
                    $states = State::where('country_id', 101)->pluck('name')->toArray();
                }

                $countries = Country::pluck('name', 'sortname')->toArray();
                //$states = State::where('country_id', 101)->pluck('name')->toArray();
                // dd($countries);
                $no_flags = Laralum::noFlags();

                $data['user'] = $user;
                $data['profile'] = $profile;
                $data['address'] = $address;
                $data['no_flags'] = $no_flags;
                $data['countries'] = $countries;
                $data['booking'] = $booking;
                $data['states'] = $states;
                return view('laralum.booking_registration.personal-details', $data);
            }
        }


        //return "hjdhsagdjghdsa";

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
        $data['states'] = $states;

       // dd($data);

        return view('laralum.booking_registration.personal-details', $data);
    }

    public function getStates($id)
    {
        $country_id = Country::where('sortname', $id)->pluck('id')->first();
        return State::where('country_id', $country_id)->pluck('name')->toArray();
        // return $country_id;
    }

    public function personalDetailsStore(Request $request, $user_id = null, $reregister = null)
    {
        //dd($request->all());

        //try {
        \DB::beginTransaction();
        $r_pr = $request->userProfile;

        if ($user_id != null) {
            $user = User::find($user_id);

            $booking = $user->current_booking;

            if ($booking) {
                $userProfile = $booking->userProfile;
            }

            if (empty($userProfile)) {
                $userProfile = new UserProfile();
            }
            $userAddress = $userProfile->address;

            if (empty($userAddress)) {
                $userAddress = new UserAddress();
            }

            /*if (empty($userProfile)) {
                $userProfile = new UserProfile();
                $userAddress = new UserAddress();
            }
            else{
                $userAddress = $userProfile->address;
                if (empty($userAddress)) {
                    $userAddress = new UserAddress();
                }
                else{
                    UserAddress::where('id', $userAddress->id)->delete();
                    $userAddress = new UserAddress();
                }
                UserProfile::where('id', $userProfile->id)->delete();
                $userProfile = new UserProfile();
            }*/

            if (empty($booking)) {
                $booking = new Booking();
            }

        } else {
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

                $user->saveRole(Role::ROLE_PATIENT);
                $user->sendPassword($random);

                $userProfile = new UserProfile();
                $userProfile->user_id = $user->id;
                $userAddress = new UserAddress();
                $booking = new Booking();
            } else {
                $booking = $user->current_booking;

                if ($booking) {
                    $userProfile = $booking->userProfile;
                }

                if (empty($userProfile)) {
                    $userProfile = new UserProfile();
                }
                $userAddress = $userProfile->address;

                if (empty($userAddress)) {
                    $userAddress = new UserAddress();
                }


                /*if (empty($userProfile)) {
                    $userProfile = new UserProfile();
                    $userAddress = new UserAddress();
                }
                else{
                    $userAddress = $userProfile->address;
                    if (empty($userAddress)) {
                        $userAddress = new UserAddress();
                    }
                    else{
                        UserAddress::where('id', $userAddress->id)->delete();
                        $userAddress = new UserAddress();
                    }
                    UserProfile::where('id', $userProfile->id)->delete();
                    $userProfile = new UserProfile();
                }*/

                if (empty($booking)) {
                    $booking = new Booking();
                }
            }

        }


        if ($reregister != null) {
            $userProfile = UserProfile::find($reregister);
            $userAddress = UserAddress::where('profile_id', $reregister)->first();
        }

        /* echo '<pre>';
         print_r($userProfile);
         print_r($userAddress);
         exit;*/
        /* add user profile */
        $error_messages = UserProfile::getErrorMessages(true);
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

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput()->with(['status' => 'danger', 'message' => 'Please check the errors below.']);
        }

        $userProfileData = $request->get('userProfile');
        $userProfileData['file'] = $request->file('profile_picture');
        $user_id = $user->id;

        if ($userProfile->setData($userProfileData, $user_id)) {
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
            // newaddedpoint for making KID unique
            $userProfile->update([
                'kid' => null,
            ]);
            $userProfile->saveDocuments($request);

            $booking->user_id = $user->id;
            $booking->patient_type = $userProfile->patient_type;
            $booking->status = Booking::STATUS_PENDING;
            $booking->profile_id = $userProfile->id;
            $booking->save();
        }

        /* add user address */

        $userAddressData = $request->get('userAddress');

        if ($userAddress->setData($userAddressData, $user_id)) {
            $userAddress->save();
            $userAddress->update([
                'profile_id' => $userProfile->id,
            ]);
        }
        \DB::commit();
        return redirect()->route('Laralum::booking.registration.health_issues', ['user_id' => $booking->id]);
        /*} catch (\Exception $e) {
            \Log::error("Failed to add the personal details during booking process. Possible causes: " . $e->getMessage());
            return redirect()->back()->withInput()->with(['status' => 'danger', 'message' => $e->getMessage()]);
        }*/

        return view('laralum.booking_registration.personal-details');
    }

    public function healthIssues($id = null, Request $request)
    {
        if ($id != null) {
            $booking = Booking::find($id);

            if ($booking->status == Booking::STATUS_PENDING) {
                $user = $booking->user;
                $profile = $booking->userProfile;
                if ($user != null) {
                    $data['user'] = $user;
                    $data['profile'] = $profile;
                    $data['booking'] = $booking;

                    if ($request->reregister) {
                        $data['reregister'] = $request->reregister;
                    }
                    return view('laralum.booking_registration.health_issues', $data);
                }
            } else {
                return redirect()->route('Laralum::booking.health_issues', ['booking_id' => $id]);
            }
        }

        return redirect()->route('Laralum::booking.registration.personalDetails', ['user_id' => $id]);
    }

    public function healthIssuesStore(Request $request, $user_id)
    {
        $booking = Booking::find($user_id);
        $user = $booking->user;
        $profile = $booking->userProfile;
        if (!empty($request->all())) {
            if (isset($request->health_issues) && !empty($request->health_issues)) {
                try {
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

                } catch (\Exception $e) {
                    Log::error("Failed to add the health issues during booking process. Possible causes: " . $e->getMessage());
                    return redirect()->back()->with(['status' => 'danger', 'message' => 'Something went wrong .']);
                }
                if ($booking->checkAccommodation()) {
                    return redirect()->route('Laralum::booking.registration.accommodation', ['booking_id' => $user_id]);
                } else {
                    return redirect()->route('Laralum::booking.registration.payment', ['booking_id' => $user_id]);
                }
            }
        }

        return redirect()->back()->with('error', 'Please input health issues');
    }

    public function accommodation(Request $request, $id)
    {
        /*echo "accommodation";
        return $request->all();*/
        if ($id != null) {
            $booking = Booking::find($id);
            if ($booking->status == Booking::STATUS_PENDING) {
                $user = $booking->user;
                $profile = $booking->userProfile;
                if ($user != null) {
                    if ($profile != null) {
                        if ($profile->health_issues != null) {
                            $data['user_id'] = $user->id;
                            $data['user'] = $user;
                            $members = [];
                            $booking->booking_type = $booking->booking_type == null ? "" : $booking->booking_type;
                            $booking->check_in_date = $booking->getFormatedDate('check_in_date');
                            $booking->check_out_date = $booking->getFormatedDate('check_out_date');
                            $booking->external_services = explode(',', $booking->external_services);

                            if ($booking->members->count() > 0) {
                                $members = $booking->members;
                            }

                            $data['booking'] = $booking;
                            $data['members'] = $members;


                            //return $members;
                            return view('laralum.booking_registration.accommodation', $data);
                        } else {
                            return redirect()->route('Laralum::booking.registration.health_issues', ['user_id' => $id]);
                        }
                    } else {
                        return redirect()->route('Laralum::booking.registration.personalDetails', ['user_id' => $id]);
                    }
                }
            } else {
                return redirect()->route('Laralum::booking.accommodation', ['booking_id' => $id]);
            }
        }
        return redirect()->route('booking.registration.signup');
    }

    public function accommodationRequest(Request $request, $id)
    {
        /*echo "accommodationRequest to first time save accomo";
        return $request->all();*/
        $booking = Booking::find($id);
        $user = $booking->user;
        $profile = $booking->userProfile;

        \Validator::extend('greater_than', function ($attribute, $value, $parameters, $validator) {
            $min_field = $parameters[0];
            $data = $validator->getData();
            $min_value = $data[$min_field];
            return $value > $min_value;
        });
        \Validator::replacer('greater_than', function ($message, $attribute, $rule, $params) {
            return str_replace('_', ' ', 'The ' . $attribute . ' must be greater than the ' . $params[0]);
        });


        $rules = array_merge($booking->rules(), [
            'check_out_date' => 'required|date_format:d-m-Y|after:check_in_date',
            'check_in_date' => 'required|date_format:d-m-Y|after:yesterday'
        ]);

        $rules = array_merge($booking->rules(), [
            'check_out_date' => 'required|date_format:d-m-Y|after:check_in_date',
            'check_in_date' => 'required|date_format:d-m-Y'
        ]);

        $validator = \Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors());
        }

        $booking->setData($request);
        $check = $booking->checkBooking();
        if ($check == true) {
            $booking->save();
            $booking->saveMembers($request);
            return redirect(route('Laralum::booking.registration.payment', ['user_id' => $id]))->with('success', 'Booking has been requested successfully.');
        }

        return redirect()->back()->with('error', "No Room Available for these dates");
    }

    public function payment($id)
    {
        if ($id != null) {
            $booking = Booking::find($id);
            if ($booking->status == Booking::STATUS_PENDING) {
                $user = $booking->user;
                $profile = $booking->userProfile;

                if ($user != null) {
                    if ($profile != null) {
                        if ($profile->health_issues != null) {
                            if ($booking->checkAccommodation($id)) {
                                if ($booking != null) {
                                    return view('laralum.booking_registration.payment', compact('user', 'booking'));
                                }
                                return redirect()->route('Laralum::booking.registration.accommodation', ['user_id' => $id]);
                            }
                            return view('laralum.booking_registration.payment', compact('user', 'booking'));
                        }
                        return redirect()->route('Laralum::booking.registration.health_issues', ['user_id' => $id]);
                    }
                    return redirect()->route('Laralum::booking.registration.personalDetails', ['user_id' => $id]);
                }
            } else {
                return redirect()->route('Laralum::booking.payment', ['booking_id' => $id]);
            }
        }
        return redirect()->route('Laralum::booking.registration.signup');
    }

    public function paymentStore(Request $request, $id)
    {
        $booking = Booking::find($id);
        $user = $booking->user;
        $profile = $booking->userProfile;

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
                        'status' => Wallet::STATUS_PENDING,
                        'payment_method' => $request->get('payment_method'),
                        'booking_id' => $request->get('booking_id'),
                        'description' => $request->get('description'),
                    ]);
                }else{
                    $wallet->amount = $request->get('amount');
                    $wallet->save();
                }
            }

            return redirect()->route('Laralum::booking.registration.confirm', ['user_id' => $id]);
        }

        return redirect()->back()->withInput()->with('error', "Something went wrong!!!");
    }


    public function confirm($id)
    {
        if ($id != null) {
            $booking = Booking::find($id);
            if ($booking->status == Booking::STATUS_PENDING) {
                $user = $booking->user;
                $profile = $booking->userProfile;
                if ($booking->booking_id == null) {
                    $booking->booking_id = $booking->getIdNumber(); //User::getId("B", $booking->id);
                }
                if ($user != null) {
                    if ($profile != null) {
                        if ($profile->health_issues != null) {
                            /*if ($profile->patient_type == UserProfile::PATIENT_TYPE_IPD && $profile->kid == null) {
                                $profile->update([
                                    'kid' => User::getId("K-IPD", $profile->getIdNumber())
                                ]);
                            } else if ($profile->kid == null) {
                                $profile->update([
                                    'kid' => User::getId("K-OPD", $profile->getIdNumber())
                                ]);
                            }*/


                            /*if ($profile->patient_type == UserProfile::PATIENT_TYPE_IPD) {
                                $profile->update([
                                    'kid' => User::getId("K-IPD", $profile->getIdNumber())
                                ]);
                            } else if ($profile->patient_type == UserProfile::PATIENT_TYPE_OPD) {
                                $profile->update([
                                    'kid' => User::getId("K-OPD", $profile->getIdNumber())
                                ]);
                            }*/

                            if ($booking->checkAccommodation($id)) {
                                if ($booking->building_id == null) {
                                    return redirect()->route('Laralum::booking.registration.accommodation', ['user_id' => $id]);
                                }

                                $healthIssues = HealthIssue::where([
                                    'user_id' => $user->id,
                                    'booking_id' => $booking->id,
                                    'status' => HealthIssue::STATUS_PENDING
                                ])->first();


                                if ($user->checkPaymentMethod($booking->id)) {
                                    //dd($booking->getAddress());
                                    return view('laralum.booking_registration.confirm', compact('user', 'booking', 'healthIssues'));
                                }
                                return redirect()->route('Laralum::booking.registration.payment', ['user_id' => $id]);
                            }
                            if ($user->checkPaymentMethod($booking->id))
                                return view('laralum.booking_registration.confirm', compact('user', 'booking', 'healthIssues'));
                            return redirect()->route('Laralum::booking.registration.payment', ['user_id' => $id]);
                        }
                        return redirect()->route('Laralum::booking.registration.health_issues', ['user_id' => $id]);
                    }
                    return redirect()->route('Laralum::booking.registration.personalDetails', ['user_id' => $id]);
                }
            } else {
                return redirect()->route('Laralum::booking.confirm', ['booking_id' => $id]);
            }
        }
        return redirect()->route('Laralum::booking.registration.create');
    }

    public function confirmStore(Request $request, $id)
    {
        //return "hereonly";
        $booking = Booking::find($id);
        $user = $booking->user;
        $profile = $booking->userProfile;

        /* if ($profile->patient_type == UserProfile::PATIENT_TYPE_IPD) {
             $profile->update([
                 'kid' => User::getId("K-IPD", $profile->getIdNumber())
             ]);
         }else{
             $profile->update([
                 'kid' => User::getId("K-OPD", $profile->getIdNumber())
             ]);
         }*/

         if ($profile->patient_type == UserProfile::PATIENT_TYPE_OPD) {
             $profile->update([
                 'kid' => User::getId("K-OPD", $profile->getIdNumber())
             ]);
         }

        if ($booking->booking_id == "") {
            $booking->update([
                'booking_id' => $booking->getIdNumber(),
            ]);
	    if ($profile->patient_type == UserProfile::PATIENT_TYPE_OPD) {
	     $booking->update([
                 'booking_kid' => User::getId("K-OPD", $booking->getKIdNumber())
             ]);
           }
        }

        $booking->update([
            'status' => Booking::STATUS_COMPLETED
        ]);

        $healthIssues = HealthIssue::where([
            'user_id' => $user->id,
            'status' => HealthIssue::STATUS_PENDING,
            'booking_id' => $booking->id
        ])->first();
        if ($healthIssues != null) {
            $healthIssues->status = HealthIssue::STATUS_COMPLETED;
            $healthIssues->save();
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

        /*try {
            Notification::saveNotification($user, "New Booking", $user->id);

            $data = $booking->setMailData();
            if (!empty($booking->user->email)) {
                EmailTemplate::sendEmail(EmailTemplate::EVENT_BOOKING, $data, $user->email);
            }
            EmailTemplate::sendEmail(EmailTemplate::EVENT_BOOKING, $data, \Auth::user()->email);
        } catch (\Exception $e) {
            \Log::info('error in sending mail', $e->getMessage());
        }*/
        //return $booking->id;

        if ($booking->patient_type == \App\Booking::PATIENT_TYPE_IPD) {
            if ($booking->accommodation_status == \App\Booking::ACCOMMODATION_STATUS_CONFIRMED) {
                $route = 'Laralum::ipd.booking.print_kid';
            } else {
                $route = 'Laralum::future.booking.print_kid';
            }
        } else
            $route = 'Laralum::opd.booking.print_kid';

        return redirect()->route($route, ['booking_id' => $booking->id])->with(['status' => 'success', 'message' => 'Booking has been completed successfully.']);
    }

    /**
     * delete booking
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        # Check permissions
        Laralum::permissionToAccess('bookings.delete');

        # Select booking
        $booking = User::findOrFail($id);
        # Delete booking
        $booking->customDelete();

        # Redirect the admin
        return redirect()->route('Laralum::bookings')->with('success', 'Booking has been deleted successfully.');

    }

}

