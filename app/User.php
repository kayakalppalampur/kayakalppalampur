<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laralum;
use Mail;
use File;
use App\Notifications\WelcomeMessage;
use App\Notifications\AccountActivation;
use App\Role_User;
use App\Role;

class User extends Authenticatable
{
    use Notifiable;

    const DISCHARGED = 1;
    const ADMIT = 0;

    const USER_TYPE_ALL = 0;
    const USER_TYPE_PATIENTS = 1;
    const USER_TYPE_DOCTORS = 2;
    const USER_TYPE_ARCHIVED_PATIENTS = 3;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'active',
        'activation_key',
        'register_ip',
        'country_code',
        'is_discharged',
        'registration_id',
        'mobile_number',
        'uhid'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'activation_key',
    ];

    public static function getErrorMessages($all = false)
    {
        $messages = [
            /*'user.first_name.required'              =>  'User first name is required.',
            'user.last_name.required'               =>  'User last name is required.',*/
            'user.username.required' => 'Username is required.',
            'user.password.required' => 'Password is required.',
            'user.password.confirmed' => 'Passwords does not match.',
            'user.password.min' => 'Passwords must be atleast 6 characters.',
            'user.email.required' => 'Email is required.',
            'user.email.unique' => 'The email you have entered is already been used.',
        ];

        /*if($all == true) {
            $profile_messages = UserProfile::getErrorMessages();
            $address_messages = UserAddress::getErrorMessages();
            $messages = array_merge($messages, $profile_messages, $address_messages);
        }*/

        return $messages;
    }

    public static function getDropDownList($id = null)
    {

        $users = User::all();
        $uid = [];
        foreach ($users as $user) {
            if ($user->departmentHead == null) {
                $uid[] = [
                    'value' => $user->id,
                    'show' => $user->name,
                ];
            }

            if ($id != null) {
                $department = Department::find($id);
                /*if ($user->id == $department->incharge_id) {
                    $uid[] = [
                        'value' => $user->id,
                        'show' => $user->name,
                    ];
                }*/
            }
        }

        return $uid;
    }

    public static function getId($pre, $id)
    {
if($id <=0 ){
$id = 1;
}
        if ($id < 999)
            return $pre . sprintf("%04s", $id);
        else
            return $pre . $id;
    }

    public static function getGenralId($pre, $id)
    {
        return str_pad($id, 7, '0', STR_PAD_LEFT);
    }

    public function address()
    {
        return $this->hasOne('App\UserAddress', 'user_id');
    }

    public function current_booking()
    {
        return $this->hasOne(Booking::class, 'user_id')->whereIn('status', [Booking::STATUS_COMPLETED , Booking::STATUS_PENDING]);
    }

    public function completed_booking()
    {
        return $this->hasOne(Booking::class, 'user_id')->where('status',Booking::STATUS_COMPLETED);
    }

    public function booking()
    {
        return $this->hasOne('App\Booking', 'user_id');
    }

    public function transaction()
    {
        return $this->hasOne('App\Transaction', 'user_id');
    }

    public function department()
    {
        return $this->hasOne('App\DepartmentUser', 'user_id');
    }

    public function departmentHead()
    {
        return $this->hasOne('App\Department', 'incharge_id');
    }

    public function userServices()
    {
        return $this->hasMany('App\UserExtraService', 'user_id');
    }

    public function paymentDetail()
    {
        return $this->hasMany('App\PaymentDetail', 'user_id');
    }

    /**
     * Mutator to capitalize the name
     *
     * @param mixed $value
     */
    public function setNameAttribute($value)
    {
        $this->attributes['name'] = ucwords($value);
    }

    /**
     * Returns all the roles from the user
     *
     */
    public function userRole()
    {
        return $this->hasOne('App\Role_User', 'user_id');
    }

    /**
     * Returns all the roles from the user
     *
     */
    public function roles()
    {
        return $this->belongsToMany('App\Role');
    }

    /**
     * Returns true if the user has access to laralum
     *
     */
    public function isAdmin()
    {
        return $this->hasPermission('access');
    }
    public function isSuperAdmin()
    {
        return $this->hasPermission('access');
    }
    public function isUser()
    {
        if (!$this->isAdmin() && !$this->isAccount() && !$this->isAyurvedic() && !$this->isDoctor() && !$this->isReception() && !$this->isInventory() && !$this->isKitchen() && !$this->isSuperAdmin() && !$this->isLabAttendant()) {
            return true;
        }
        return false;
    }
    public function Admin()
    {
        $role = Role::where('name', 'Admin')->first();
        $role_id = $role->id;
        $data = Role_User::where('role_id',$role_id)->first();
        return $data->user_id;
    }

    /**
     * Returns true if the user has the permission slug
     *
     * @param string $slug
     */
    public function hasPermission($slug)
    {
        foreach ($this->roles as $role) {
            foreach ($role->permissions as $perm) {
                if ($perm->slug == $slug) {
                    if ($slug != 'access') {
                        if ($this->isAdmin()) {
                            return true;
                        }
                    }
                    return true;
                }
            }
        }
        return false;
    }

    public function isAyurvedic()
    {
        if (isset($this->department->department_id)) {
            if ($this->department->department_id == Department::getAyurvedId())
                return true;
        }
        return false;
    }

    public function isPatient()
    {
        if ($this->userRole->role_id == Role::ROLE_PATIENT)
            return true;
        return false;
    }

    /**
     * Returns true if the user has the role
     *
     * @param string $name
     */
    public function hasRole($name)
    {
        foreach ($this->roles as $role) {
            if ($role->name == $name) {
                return true;
            }
        }

        return false;
    }

    public function hasDepartment($id)
    {
        if (isset($this->department->department_id)) {
            if ($this->department->department_id == $id) {
                return true;
            }
        }
        return false;
    }

    /**
     * Returns all the blogs owned by the user
     *
     */
    public function blogs()
    {
        return $this->hasMany('App\Blog');
    }

    /**
     * Returns true if the user has blog access
     *
     * @param number $id
     */
    public function has_blog($id)
    {
        foreach ($this->roles as $role) {
            foreach (Laralum::blog('id', $id)->roles as $b_role) {
                if ($role->id == $b_role->id) {
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * Returns true if the user owns the blog
     *
     * @param number $id
     */
    public function owns_blog($id)
    {
        if ($this->id == Laralum::blog('id', $id)->user_id) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Returns all the posts from the user
     *
     */
    public function posts()
    {
        return $this->hasMany('App\Post');
    }

    /**
     * Returns true if the users owns the post
     *
     * @param number $id
     */
    public function owns_post($id)
    {
        if ($this->id == Laralum::post('id', $id)->author->id) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Returns the user avatar from Gavatar
     *
     * @param number $size
     */
    public function avatar($size = null)
    {
        $file = Laralum::avatarsLocation() . '/' . md5($this->email);
        $file_url = asset($file);
        if (File::exists($file)) {
            return $file_url;
        } else {
            return Laralum::defaultAvatar();
        }
    }

    /**
     * Returns all the documents from the user
     *
     */
    public function documents()
    {
        return $this->hasMany('App\UserDocument', 'user_id');
    }

    /**
     * Returns all the social accounts from the user
     *
     */
    public function socials()
    {
        return $this->hasMany('App\Social');
    }

    /**
     * Returns true if the user has the social account
     *
     * @param string $provider
     */
    public function hasSocial($provider)
    {
        foreach ($this->socials as $social) {
            if ($social->provider == $provider) {
                return true;
            }
        }
        return false;
    }

    /**
     * Sends the welcome email notification to the user
     *
     */
    public function sendWelcomeEmail()
    {
        try {
            return $this->notify(new WelcomeMessage($this));
        } catch (\Exception $e) {
            \Log::info('Erro mail' . $e->getMessage());
        }
    }



    /**
     * Sends the activation email notification to the user
     *
     */
    public function sendActivationEmail()
    {
        try {
            return $this->notify(new AccountActivation($this));
        } catch (\Exception $e) {
            \Log::info('Erro mail' . $e->getMessage());

        }
    }

    public function userProfile()
    {
        return $this->hasOne('App\UserProfile', 'user_id');
    }

    public function setData($data)
    {
        $data = !is_object($data) ? $data : $data->all();

        $this->name = isset($data['username']) != null ? $data['username'] : "";
        $this->email = isset($data['email']) != null ? $data['email'] : "";
        // $this->first_name = isset($data['first_name']) ? $data['first_name'] : $data->get('first_name');
        //$this->last_name = isset($data['last_name']) ? $data['last_name'] : $data->get('last_name');
        $this->su = 0;
        $this->country_code = isset($data['country_code']) ? $data['country_code'] : '';
        return $this;
    }




    public function rules($all = false)
    {
        $rules = [
            /*'user.first_name'               =>  'required',
            'user.last_name'                =>  'required',*/
            'user.username' => 'required',
            'user.password' => 'required|confirmed|min:6',
            'user.email' => 'required|unique:users,email,' . $this->id,
        ];

        /*if($all == true) {
            $profile_rules = UserProfile::getRules();
            $address_rules = UserAddress::getRules();
            $rules = array_merge($rules, $profile_rules, $address_rules);
        }*/

        return $rules;
    }

    public function getRules($all = false)
    {
        $rules = [
            /*'user.first_name'               =>  'required',
            'user.last_name'                =>  'required',*/
            'user.username' => 'required',
            'user.password' => 'required|confirmed|min:6',
            'user.email' => 'required|unique:users,email,' . $this->id,
        ];

        /*if($all == true) {
            $profile_rules = UserProfile::getRules();
            $address_rules = UserAddress::getRules();
            $rules = array_merge($rules, $profile_rules, $address_rules);
        }*/

        return $rules;
    }

    public function attendance($date)
    {
        $attendance = Attendance::where('user_id', $this->id)->where('date_in', $date)->first();

        if ($attendance != null) {
            $label = Attendance::getStatusLabelOptions($attendance->status);

            if ($attendance->status == Attendance::STATUS_LEAVE) {
                $label .= ' <i class="fa fa-question" style="cursor:pointer;" id="comment_' . $this->id . '"title="' . $attendance->comment . '"></i>';
            }
            $time_in = date('H:i', strtotime($attendance->time_in));
            $time_out = date('H:i', strtotime($attendance->time_out));
            $label .= '<input type="hidden" id="time_in_val_' . $this->id . '" value=' . $time_in . '>';
            $label .= '<input type="hidden" id="time_out_val_' . $this->id . '" value=' . $time_out . '>';
            $label .= '<input type="hidden" id="selected_state_' . $this->id . '" value=' . $attendance->status . '>';

            if (Laralum::loggedInUser()->hasPermission('attendance.edit') && $date <= date('Y-m-d')) {
                $label .= ' <i id="edit_' . $this->id . '" class="fa fa-edit hover"></i>';
            }

            return $label;
        } elseif ($date > date('Y-m-d')) {
            return "Not Set";
        }

        return false;
    }

    public function saveDepartment($id)
    {
        $department = DepartmentUser::where('user_id', $this->id)->where('department_id', $id)->first();
        if ($department == null) {
            DepartmentUser::create([
                'user_id' => $this->id,
                'department_id' => $id
            ]);
        }
    }

    public function getDepartmentColor()
    {
        $department = DepartmentUser::where('user_id', $this->id)->first();

        if ($department != null) {
            $department_color = isset($department->department->color) ? $department->department->color : "";

            if ($department_color != "") {
                return $department_color;
            }
        }

        return false;
    }

    public function getUserProfile()
    {
        $profile = $this->userProfile;

        if ($profile == null) {
            $profile = new UserProfile();
        }

        return $profile;
    }

    public function getAddress()
    {
        $address = $this->address;

        if ($address == null) {
            $address = new UserAddress();
        }

        return $address;
    }

    public function saveRole($roleid)
    {
        $role_user = Role_User::where('user_id', $this->id)->first();

        if ($role_user == null) {
            $role_user = new Role_User();

        }
        $role_user->user_id = $this->id;
        $role_user->role_id = $roleid;
        $role_user->save();
    }

    public function getName()
    {
        if (isset($this->userProfile->first_name)) {
            return $this->userProfile->first_name . ' ' . $this->userProfile->last_name;
        }

        return $this->name;
    }

    public function checkPersonalDetailsTab()
    {
        if ($this->id != null)
            return true;
        return false;
    }

    public function checkHealthIssuesTab()
    {
        if ($this->id != null) {
            if ($this->userProfile != null) {
                if ($this->userProfile->id != null) {
                    return true;
                }
            }
        }
        return false;
    }

    public function checkAccomodationTab()
    {
        if ($this->id != null) {
            if ($this->userProfile != null) {
                if ($this->userProfile->id != null) {
                    if ($this->userProfile->health_issues != null) {
                        return true;
                    }
                }
            }
        }
        return false;
    }

    public function checkPaymentTab()
    {
        if ($this->id != null) {
            if ($this->userProfile != null) {
                if ($this->userProfile->id != null) {
                    if ($this->userProfile->health_issues != null) {
                        if ($this->checkAccommodation()) {
                            if ($this->booking != null) {
                                return true;
                            }
                        }
                        return true;
                    }
                }
            }
        }
        return false;
    }

    public function checkAccommodation($id = null)
    {
        if ($id != null) {
            $booking = Booking::find($id);
        } else {
            $booking = Booking::where([
                'user_id' => $this->id,
                'status' => Booking::STATUS_PENDING
            ])->first();
        }

        if ($booking != null) {
            if ($booking->patient_type == \App\UserProfile::PATIENT_TYPE_OPD) {
                return false;
            }
        } else {

            return true;
        }

        if ($id != null) {
            $id = \Session::get('user_id');
        }

        if ($id != null) {
            $user = User::find($id);
            $profile = $user->userProfile;

            if ($profile != null) {
                if ($profile->patient_type == UserProfile::PATIENT_TYPE_OPD) {
                    return false;
                }
            }
        }

        return true;
    }

    public function checkPaymentMethod($id)
    {
        $detail = PaymentDetail::where([
            'user_id' => $this->id,
            'booking_id' => $id
        ])->first();

        if ($detail != null)
            return true;

        return false;
    }

    public function checkConfirmTab()
    {
        if ($this->id != null) {
            if ($this->userProfile != null) {
                if ($this->userProfile->id != null) {
                    if ($this->userProfile->health_issues != null) {
                        $p = PaymentDetail::where([
                            'user_id' => $this->id,
                            'booking_id' => $this->getBooking(Booking::STATUS_PENDING)->id
                        ])->first();
                        if ($p != null) {
                            return true;
                        }/*
                        if ($this->getBooking() != null) {
                            if ($this->getBooking()->status == Booking::STATUS_COMPLETED) {
                                return true;
                            }
                        }*/
                        /*    if ($this->checkAccommodation()) {
                                if ($this->getBookingId()) {
                                    if ($this->transaction != null) {
                                        return true;
                                    }
                                }
                            }
                            if ($this->transaction != null) {
                                return true;
                            }*/
                    }
                }
            }
        }
        return false;
    }

    public function getBooking($status = Booking::STATUS_COMPLETED)
    {
         $booking = Booking::where('user_id', $this->id)->where(['status' => $status])->orderBy('created_at', 'DESC')->first();

        if ($booking != null) {
            return $booking;
        } else {
            return new Booking();
        }

        return $booking;
    }

    public function checkKidTab()
    {
        if ($this->id != null) {
            if ($this->userProfile != null) {
                if ($this->userProfile->id != null) {
                    if ($this->userProfile->health_issues != null) {
                        if ($this->checkAccommodation()) {
                            if ($this->booking != null) {
                                if ($this->transaction != null) {
                                    if ($this->transaction->status == Transaction::STATUS_COMPLETED) {
                                        return true;
                                    }
                                }
                            }
                        }
                        if ($this->transaction != null) {
                            if ($this->transaction->status == Transaction::STATUS_COMPLETED) {
                                return true;
                            }
                        }
                    }
                }
            }
        }
        return false;
    }

    public function getBookingId()
    {
        $booking = Booking::where('user_id', $this->id)->whereNotIn('status', [Booking::STATUS_CANCELLED, Booking::STATUS_DISCHARGED])->orderBy('created_at', 'DESC')->first();

        if ($booking != null) {
            return $booking->id;
        }
        return false;
    }

    public function getBookingPrice()
    {
        $booking = Booking::where('user_id', $this->id)->whereNotIn('status', [Booking::STATUS_CANCELLED, Booking::STATUS_DISCHARGED])->orderBy('created_at', 'DESC')->first();
        return $booking->daysPrice();
    }

    public function getServices()
    {
        $booking = Booking::where('user_id', $this->id)->whereNotIn('status', [Booking::STATUS_CANCELLED, Booking::STATUS_DISCHARGED])->orderBy('created_at', 'DESC')->first();
        return $booking->services;
    }

    public function getDiscount()
    {
        $transaction = Transaction::where('user_id', $this->id)->whereNotIn('status', [Transaction::STATUS_CANCELLED, Transaction::STATUS_DISCHARGED])->orderBy('created_at', 'DESC')->first();
        return $transaction->discount_amount;
    }

    public function getTotalAmountPaid()
    {
        $transaction = Transaction::where('user_id', $this->id)->whereNotIn('status', [Transaction::STATUS_CANCELLED, Transaction::STATUS_DISCHARGED])->orderBy('created_at', 'DESC')->first();
        return $transaction->payable_amount;
    }

    public function getDiscountId()
    {
        $transaction = Transaction::where('user_id', $this->id)->whereNotIn('status', [Transaction::STATUS_CANCELLED, Transaction::STATUS_DISCHARGED])->orderBy('created_at', 'DESC')->first();
        return $transaction->discount_id;
    }

    public function previousBooking()
    {
        $transaction = Transaction::where('user_id', $this->id)->whereNotIn('status', [Transaction::STATUS_CANCELLED, Transaction::STATUS_DISCHARGED])->orderBy('created_at', 'DESC')->first();
        if ($transaction != null) {
            if ($transaction->booking != null) {
                if ($transaction->booking->status == Booking::STATUS_CANCELLED) {
                    return $transaction->booking;
                }
            }
        }
        return false;
    }

    public function pendingBooking()
    {
        $booking = Booking::where('user_id', $this->id)->where('status', Booking::STATUS_PENDING)->orderBy('created_at', 'DESC')->first();
        if ($booking != null)
            return $booking;
        return false;
    }

    public function getPaidAmount()
    {
        $transaction = $this->getTransaction();
        if ($transaction) {
            return $transaction->payable_amount;
        }
        return 0;
    }

    public function getTransaction()
    {

        $transaction = Transaction::where('user_id', $this->id)->whereNotIn('status', [Transaction::STATUS_CANCELLED, Transaction::STATUS_DISCHARGED])->orderBy('created_at', 'DESC')->first();

        if ($transaction != null)
            return $transaction;
        return false;
    }

    public function getBalance()
    {
        $balance = 0;
        $wallets = Wallet::where('user_id', $this->id)->get();
        foreach ($wallets as $wallet) {
            if ($wallet->type == Wallet::TYPE_PAID) {
                $balance = $balance + $wallet->amount;
            } elseif ($wallet->status == Wallet::STATUS_PENDING) {
                $balance = $balance - $wallet->amount;
            }
        }

        return $balance;
    }

    public function isEditable()
    {
        $booking = $this->getBooking();
        if ($booking) {
            if ($booking->check_out_date <= date('Y-m-d H:i:s')) {
                return false;
            }
        }
        return true;
    }

    public function getTotalAmount($type = Wallet::TYPE_PAID, $closed = true)
    {
        $price = 0;
        $transactions = Wallet::where('user_id', $this->id)->where('type', $type);

        if ($closed == true) {
            $transactions = $transactions->where('status', '!=', Wallet::STATUS_CLOSE);
        }
        $transactions = $transactions->get();

        if ($transactions != null) {
            foreach ($transactions as $transaction) {
                if ($type == Wallet::TYPE_REFUND) {
                    if ($transaction->status == Wallet::STATUS_PENDING)
                        $price = $price + $transaction->amount;
                } else {
                    $price = $price + $transaction->amount;
                }
            }
        }

        if ($type == Wallet::TYPE_PAID && $closed == false) {
            $refunded_price = Wallet::where('user_id', $this->id)->where('type', Wallet::TYPE_REFUND)->where('status', '!=', Wallet::STATUS_PENDING)->first();
            if ($refunded_price != null) {
                $price = $price - $refunded_price->amount;
            }
        }

        return $price;
    }

    public function getRefundStatus()
    {
        $wallet = Wallet::where('user_id', $this->id)->where('type', Wallet::TYPE_REFUND)->first();
        if ($wallet != null)
            return $wallet->getStatusOptions($wallet->status);
        return "NA";
    }

    public function customDelete()
    {
        $userProfile = $this->userProfile;
        if ($userProfile != null) {
            $userProfile->delete();
        }

        $userAddress = $this->address;

        if ($userAddress != null) {
            $userAddress->delete();
        }

        $attendances = $this->attendances();
        if ($attendances->count() > 0) {
            foreach ($attendances as $attendance) {
                $attendance->delete();
            }
        }

        $issues = $this->issues();
        if ($issues->count() > 0) {
            foreach ($issues as $issue) {
                $issue->customeDelete();
            }
        }

        $roles = $this->myRoles();
        if ($roles->count() > 0) {
            foreach ($roles as $role) {
                $role->delete();
            }
        }

        /* Doctor Delete*/


        /* Patient Delete*/
        $this->bookingDelete();
        $this->tokensDelete();
        $this->examinationsDelete();

        $wallets = $this->wallet;
        if ($wallets->count() > 0) {
            foreach ($wallets as $wallet) {
                $wallet->delete();
            }
        }

        $this->delete();
        return true;
    }

    public function attendances()
    {
        return $this->hasMany('App\Attendance', 'user_id');
    }

    public function issues()
    {
        return $this->hasMany('App\Issue', 'created_by');
    }

    public function myRoles()
    {
        return $this->hasMany('App\Role_User', 'user_id');
    }

    public function bookingDelete()
    {

        $services = $this->userServices;
        if ($services->count() > 0) {
            foreach ($services as $service) {
                $service->delete();
            }
        }

        $bookings = Booking::where("user_id", $this->id)->whereIn('status', [Booking::STATUS_PENDING, Booking::STATUS_COMPLETED])->get();
        foreach ($bookings as $booking) {
            $booking->update([
                'status' => Booking::STATUS_CANCELLED
            ]);
        }

        $transactions = Transaction::where("user_id", $this->id)->whereIn('status', [Transaction::STATUS_PENDING, Transaction::STATUS_COMPLETED])->get();
        foreach ($transactions as $transaction) {
            $transaction->update([
                'status' => Transaction::STATUS_CANCELLED
            ]);
        }
        /*
                $patient_followups = PatientFollowUp::where('patient_id', $this->id)->get();
                foreach ($patient_followups as $patient_followup) {
                    $patient_followup->update([
                        'status' => PatientFollowUp::STATUS_CANCELLD
                    ]);
                }*/


        $patient_followups = $this->patientFollowups;
        if ($patient_followups->count() > 0) {
            foreach ($patient_followups as $patient_followup) {
                $patient_followup->delete();
            }
        }
    }

    public function tokensDelete()
    {

        $tokens = PatientToken::where('patient_id', $this->id)->get();
        foreach ($tokens as $token) {
            $token->update([
                'status' => PatientToken::STATUS_CANCELLED
            ]);
            $discharge_patient = DischargePatient::where('token_id', $token->id)->first();
            if ($discharge_patient != null) {
                $discharge_patient->update([
                    'status' => DischargePatient::STATUS_CANCELLED
                ]);
            }
        }

        $diet_charts = DietChart::where("patient_id", $this->id)->get();
        foreach ($diet_charts as $diet_chart) {
            $diet_chart->update([
                'status' => DietChart::STATUS_CANCELLED
            ]);
        }
        $discharge_patients = $this->dischargePatients;
        if ($discharge_patients->count() > 0) {
            foreach ($discharge_patients as $discharge_patient) {
                $discharge_patient->delete();
            }
        }

        $feedbacks = $this->feedbacks;
        if ($feedbacks->count() > 0) {
            foreach ($feedbacks as $feedback) {
                $feedback->delete();
            }
        }

        $patient_details = $this->patientDetails;
        if ($patient_details->count() > 0) {
            foreach ($patient_details as $patient_detail) {
                $patient_detail->delete();
            }
        }

        $patient_lab_tests = $this->patientLabTests;
        if ($patient_lab_tests->count() > 0) {
            foreach ($patient_lab_tests as $patient_lab_test) {
                $patient_lab_test->delete();
            }
        }

        $patient_treatment_tokens = $this->patientTreatmentTokens;
        if ($patient_treatment_tokens->count() > 0) {
            foreach ($patient_treatment_tokens as $patient_treatment_token) {
                $treatments = $patient_treatment_token->treatments;
                if ($treatments->count() > 0) {
                    foreach ($treatments as $treatment) {
                        $treatment->delete();
                    }
                }
                $patient_treatment_token->delete();
            }
        }
    }

    public function examinationsDelete()
    {
        $ayurved_ashtvidh_examinations = $this->ayurvedExaminations;
        if ($ayurved_ashtvidh_examinations->count() > 0) {
            foreach ($ayurved_ashtvidh_examinations as $ayurved_ashtvidh_examination) {
                $ayurved_ashtvidh_examination->delete();
            }
        }


        $ayurved_atur_examinations = $this->ayurvedAturExaminations;
        if ($ayurved_atur_examinations->count() > 0) {
            foreach ($ayurved_atur_examinations as $ayurved_atur_examination) {
                $ayurved_atur_examination->delete();
            }
        }

        $ayurved_dosh_examinations = $this->ayurvedDoshExaminations;
        if ($ayurved_dosh_examinations->count() > 0) {
            foreach ($ayurved_dosh_examinations as $ayurved_dosh_examination) {
                $ayurved_dosh_examination->delete();
            }
        }


        $gastro_examinations = $this->gastroExaminations;
        if ($gastro_examinations->count() > 0) {
            foreach ($gastro_examinations as $gastro_examination) {
                $gastro_examination->delete();
            }
        }

        $genitournary_examinations = $this->genitournaryExaminations;
        if ($genitournary_examinations->count() > 0) {
            foreach ($genitournary_examinations as $genitournary_examination) {
                $genitournary_examination->delete();
            }
        }

        $neuro_examinations = $this->neuroExaminations;
        if ($neuro_examinations->count() > 0) {
            foreach ($neuro_examinations as $neuro_examination) {
                $neuro_examination->delete();
            }
        }

        $physical_examinations = $this->physicalExaminations;
        if ($physical_examinations->count() > 0) {
            foreach ($physical_examinations as $physical_examination) {
                $physical_examination->delete();
            }
        }

        $respiratory_examinations = $this->respiratoryExaminations;
        if ($respiratory_examinations->count() > 0) {
            foreach ($respiratory_examinations as $respiratory_examination) {
                $respiratory_examination->delete();
            }
        }

        $vital_datas = $this->vitalData;
        if ($vital_datas->count() > 0) {
            foreach ($vital_datas as $vital_data) {
                $vital_data->delete();
            }
        }
    }

    public function wallet()
    {
        return $this->hasMany('App\Wallet', 'user_id');
    }

    public function patientTreatmentTokens()
    {
        return $this->hasMany('App\TreatmentToken', 'patient_id');
    }

    public function patientTokens()
    {
        return $this->hasMany('App\PatientToken', 'patient_id');
    }

    public function patientLabTests()
    {
        return $this->hasMany('App\PatientLabTest', 'patient_id');
    }

    public function patientFollowups()
    {
        return $this->hasMany('App\PatientFollowUp', 'patient_id');
    }

    public function patientDetails()
    {
        return $this->hasMany('App\PatientDetails', 'patient_id');
    }

    public function vitalData()
    {
        return $this->hasMany('App\VitalData', 'patient_id');
    }

    public function physicalExaminations()
    {
        return $this->hasMany('App\PhysicalExamination', 'patient_id');
    }

    public function respiratoryExaminations()
    {
        return $this->hasMany('App\RespiratoryExamination', 'patient_id');
    }

    public function neuroExaminations()
    {
        return $this->hasMany('App\NeurologicalExamination', 'patient_id');
    }

    public function genitournaryExaminations()
    {
        return $this->hasMany('App\GenitourinaryExamination', 'patient_id');
    }

    public function gastroExaminations()
    {
        return $this->hasMany('App\GastrointestinalExamination', 'patient_id');
    }

    public function feedbacks()
    {
        return $this->hasMany('App\Feedback', 'user_id');
    }

    public function dischargePatients()
    {
        return $this->hasMany('App\DischargePatient', 'patient_id');
    }

    public function ayurvedAturExaminations()
    {
        return $this->hasMany('App\AyurvedAturExamination', 'patient_id');
    }

    public function ayurvedDoshExaminations()
    {
        return $this->hasMany('App\AyurvedDoshExamination', 'patient_id');
    }

    public function ayurvedExaminations()
    {
        return $this->hasMany('App\AyurvedaAshtvidhExamination', 'patient_id');
    }

    public function getDietAmount()
    {
        $diets = $this->getDiets();
        $price = 0;
        foreach ($diets as $diet) {
            $diet_daily_items = DietDailyStatus::where('diet_id', $diet->id)->get();
            if ($diet_daily_items != null) {
                foreach ($diet_daily_items as $item) {
                    $price = $price + $item->getTotalAmount();
                }
            }
        }
        return $price;
    }

    public function getDiets()
    {
        $diets = DietChart::where('patient_id', $this->id)->where('status', DietChart::STATUS_PENDING)->get();
        return $diets;
    }

    public function getTreatmentsAmount()
    {
        $treatment_tokens = $this->getTreatments();
        $price = 0;
        if ($treatment_tokens->count() > 0) {
            foreach ($treatment_tokens as $treatment_token) {
                $treatments = $treatment_token->treatments;
                if ($treatments->count() > 0) {
                    foreach ($treatments as $treatment) {
                        if ($treatment->status == PatientTreatment::STATUS_COMPLETED) {
                            $price = $price + $treatment->treatment->price;
                        }
                    }
                }
            }
        }
        return $price;
    }

    public function getTreatments()
    {
        $treatments = TreatmentToken::where('patient_id', $this->id)->whereIn('status', [TreatmentToken::STATUS_COMPLETED])->get();

        return $treatments;
    }

    public function saveDietAmount()
    {
        $diets = $this->getDiets();
        if ($diets->count() > 0) {
            foreach ($diets as $diet) {
                $diet_daily_items = DietDailyStatus::where('diet_id', $diet->id)->get();
                if ($diet_daily_items != null) {
                    foreach ($diet_daily_items as $item) {
                        $price = $item->is_breakfast + $item->is_dinner + $item->is_lunch + $item->is_post_lunch + $item->special;
                        Wallet::create([
                            'user_id' => $this->id,
                            'amount' => $price,
                            'type' => Wallet::TYPE_PAID,
                            'model_id' => $item->id,
                            'model_type' => get_class($item),
                            'txn_id' => 'Transaction--',
                            'created_by' => \Auth::user()->id,
                            'status' => Wallet::STATUS_PAID
                        ]);
                    }
                }
            }
        }
    }

    public function saveTreatmentsAmount()
    {
        $treatment_tokens = $this->getTreatments();
        if ($treatment_tokens->count() > 0) {
            foreach ($treatment_tokens as $treatment_token) {
                $price = 0;
                $treatments = $treatment_token->treatments;

                if ($treatments->count() > 0) {
                    foreach ($treatments as $treatment) {
                        if ($treatment->status == PatientTreatment::STATUS_COMPLETED)
                            $price = $price + $treatment->treatment->price;
                    }
                }

                Wallet::create([
                    'user_id' => $this->id,
                    'amount' => $price,
                    'type' => Wallet::TYPE_PAID,
                    'model_id' => $treatment_token->id,
                    'model_type' => get_class($treatment_token),
                    'txn_id' => 'Transaction--',
                    'created_by' => \Auth::user()->id,
                    'status' => Wallet::STATUS_PAID
                ]);
            }
        }
    }

    public function updatePatientVitalData()
    {
        $patient_details = PatientDetails::where('patient_id', $this->id)->where('status', PatientDetails::TYPE_ADMISSION)->get();
        foreach ($patient_details as $patient_detail) {
            $patient_detail->update([
                'status' => PatientDetails::STATUS_DISCHARGE
            ]);
        }

        $vital_datas = VitalData::where('patient_id', $this->id)->where('status', VitalData::STATUS_PENDING)->get();
        foreach ($vital_datas as $vital_data) {
            $vital_data->update([
                'status' => VitalData::STATUS_DISCHARGE
            ]);
        }

        $ayurved_ashtvidh_datas = AyurvedaAshtvidhExamination::where('patient_id', $this->id)->where('status', AyurvedaAshtvidhExamination::STATUS_PENDING)->get();
        foreach ($ayurved_ashtvidh_datas as $ayurved_ashtvidh_data) {
            $ayurved_ashtvidh_data->update([
                'status' => AyurvedaAshtvidhExamination::STATUS_DISCHARGED
            ]);
        }

        $ayurved_atur_datas = AyurvedAturExamination::where('patient_id', $this->id)->where('status', AyurvedAturExamination::STATUS_PENDING)->get();
        foreach ($ayurved_atur_datas as $ayurved_atur_data) {
            $ayurved_atur_data->update([
                'status' => AyurvedAturExamination::STATUS_DISCHARGED
            ]);
        }

        $ayurved_dosh_datas = AyurvedDoshExamination::where('patient_id', $this->id)->where('status', AyurvedDoshExamination::STATUS_PENDING)->get();
        foreach ($ayurved_dosh_datas as $ayurved_dosh_data) {
            $ayurved_dosh_data->update([
                'status' => AyurvedDoshExamination::STATUS_DISCHARGED
            ]);
        }
    }

    public function getDiagnosis()
    {
        $vital_data = VitalData::where('patient_id', $this->id)->orderBy('created_at', 'DESC')->first();
        if ($vital_data != null) {
            return 'Present Complaints: ' . $vital_data->present_complaints . ' Present Illness:' . $vital_data->present_illness;
        }
        return "";
    }

    public function getDocument($id, $attr = 'id')
    {
        $document = UserDocument::where('document_type_id', $id)->where('user_id', $this->id)->first();
        if ($document != null) {
            return $document->$attr;
        }

        return false;
    }

    public function getLastTokenNo()
    {
        $date = (string)date('Y-m-d');
        $patient_token = PatientToken::where(\DB::raw('Date(start_date)'), $date)->where('doctor_id', $this->id)->orderBy('created_at', 'DESC')->first();
        if ($patient_token != null)
            return $patient_token->token_no;
        return 0;
    }

    public function getStaffDepartment($role_id = null)
    {
        if ($this->isDoctor()) {
            return StaffDepartment::getDeptId('Doctor');
        } elseif ($this->isReception()) {
            return StaffDepartment::getDeptId('Reception');
        } elseif ($this->isInventory()) {
            return StaffDepartment::getDeptId('Inventory');
        } elseif ($this->isAccount()) {
            return StaffDepartment::getDeptId('Account');
        } elseif ($this->isKitchen()) {
            return StaffDepartment::getDeptId('Kitchen');
        } else {
            if ($role_id != null) {
                $role = Role::find($role_id);
                if ($role != null) {
                    return StaffDepartment::getDeptId($role->name);
                }
            }
        }
        if (isset($this->userRole->role->name)) {
            return StaffDepartment::getDeptId($this->userRole->role->name);
        }
        return StaffDepartment::getDeptId('Other');

    }

    public function isDoctor()
    {
        if (isset($this->userRole->role->id)) {
            return $this->userRole->role->id == Role::getDoctorId();
        };
        return $this->hasPermission('doctor_dashboard');
    }

    public function isReception()
    {
        return $this->hasRole('Reception');
        //return $this->hasPermission('reception_dashboard');
    }

    public function isInventory()
    {
        return $this->hasPermission('inventory_dashboard');
        return false;
    }

    public function isAccount()
    {
        return $this->hasPermission('account_dashboard');
        return false;
    }

    public function isKitchen()
    {
        return $this->hasPermission('kitchen_dashboard');
        return false;
    }

    public function isLabAttendant()
    {
        return $this->hasPermission('lab_dashboard');
        return false;
    }

    public static function getRoutesArray($doctor = false)
    {
        if ($doctor == true) {
            return [
                'Laralum::doctors',
                'Laralum::doctors.print',
                'Laralum::doctors.export',
                'Laralum::doctors_create',
                'Laralum::doctors_edit',
                'Laralum::users_roles',
                'Laralum::doctors_delete'
            ];
        }
        return [
            'Laralum::users',
            'Laralum::print',
            'Laralum::users.export',
            'Laralum::users_create',
            'Laralum::users_edit',
            'Laralum::users_roles',
            'Laralum::users_delete'
        ];
    }

    public static function getDoctors()
    {
        $users = User::select('users.*')->join('role_user', 'role_user.user_id', '=', 'users.id')->where('role_user.role_id', Role::getDoctorId())->get();

        return $users;
    }

    public function sendPassword($password)
    {
        $apiKey = urlencode(env('TEXT_LOCAL_API_KEY'));

        // Message details
        $numbers = array($this->mobile_number);
        $sender = urlencode('TXTLCL');
        $message = rawurlencode('A booking has been created with Kayakalp. Your password is '.$password.' click hrere '.route('login'));

        $numbers = implode(',', $numbers);

        // Prepare data for POST request
        $data = array('apikey' => $apiKey, 'numbers' => $numbers, "sender" => $sender, "message" => $message);

        // Send the POST request with cURL
        $ch = curl_init('https://api.textlocal.in/send/');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);

        //print_r($response);exit;

        // Process your response here
       // echo $response;

    }

    public function getUhid()
    {
        $user = User::whereHas('myRoles', function($q) { $q->where('role_id', Role::ROLE_PATIENT); })->orderBy('id', 'desc')->first();
      
        if ($user) {
            $id = (int) $user->uhid + 1;
            return str_pad($id, 7, '0', STR_PAD_LEFT);
        }

        $id = 1;
        return str_pad($id, 7, '0', STR_PAD_LEFT);
    }
}
