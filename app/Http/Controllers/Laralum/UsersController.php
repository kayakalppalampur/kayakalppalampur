<?php

namespace App\Http\Controllers\Laralum;

use App\Booking;
use App\Department;
use App\DepartmentUser;
use App\DischargePatient;
use App\Role;
use App\Settings;
use App\Staff;
use App\Transaction;
use App\User;
use App\UserProfile;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Role_User;
use Illuminate\Routing\Route;
use Laralum;
use Auth;
use Gate;
use PDF;


class UsersController extends Controller
{

    public function index(Request $request)
    {
        Laralum::permissionToAccess('admin.users.list');

        # Get all users
        //$users = Laralum::users();
        $matchThese = [];
        $search = false;
        $option_ar = [];

        if ($request->has('filter_patient_id') && $request->get('filter_patient_id') != "") {
            $search = true;
            $option_ar[] = "Patient Id";
            $matchThese['kid'] = trim($request->get('filter_patient_id'));
        }/*
        if ($request->has('filter_first_name') && $request->get('filter_first_name') != ""){
            $search = true;
            $option_ar[] = "First Name";
            $matchThese['first_name'] = $request->get('filter_first_name');
        }*/
        $filter_name = "";
        if ($request->has('filter_name') && $request->get('filter_name') != "") {
            $search = true;
            $option_ar[] = "Name";
            $filter_name = trim($request->get('filter_name'));
        }/*
        if ($request->has('filter_last_name') && $request->get('filter_last_name') != ""){
            $search = true;
            $option_ar[] = "Last Name";
            $matchThese['last_name'] = $request->get('filter_last_name');
        }*/
        if ($request->has('filter_mobile') && $request->get('filter_mobile') != "") {
            $search = true;
            $option_ar[] = "Mobile";
            $matchThese['mobile'] = trim($request->get('filter_mobile'));
        }

        $filter_email = "";

        if ($request->has('filter_email')) {
            $search = true;
            $option_ar[] = "Email";
            $filter_email = trim($request->get('filter_email'));
        }
        if ($search == true) {
            $users = User::select('users.*')
                ->leftjoin('role_user', 'role_user.user_id', '=', 'users.id')
                ->where(function ($query) {
                    $query->whereNotIn('role_user.role_id', [Role::getPatientId(), Role::getDoctorId()])->orWhereNull('role_user.id');
                })
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
                })->orderBY('users.created_at', 'DESC');

            $per_page = $request->get("per_page") ? $request->get("per_page") : 10;
            $pagination = true;
            if ($per_page == "All") {
                $pagination = false;
            }
            $count = $users->count();
            if ($pagination == true) {
                $users = $users->paginate($per_page);
            } else {
                $users = $users->get();
            }
        } else {
            $users = User::select('users.*')->leftjoin('role_user', 'role_user.user_id', '=', 'users.id')
                ->where(function ($query) {
                    $query->whereNotIn('role_user.role_id', [Role::getPatientId(), Role::getDoctorId()])->orWhereNull('role_user.id');
                });

            $per_page = $request->get("per_page") ? $request->get("per_page") : 10;
            $pagination = true;
            if ($per_page == "All") {
                $pagination = false;
            }
            $count = $users->count();
            if ($pagination == true) {
                $users = $users->paginate($per_page);
            } else {
                $users = $users->get();
            }
        }
        $options = implode(", ", $option_ar);

        $error = "Entered " . $options . " is not valid,
make sure that you are entering valid " . $options . " 
or search by other options";


        # Get the active users
        $active_users = Laralum::users('active', true);

        # Get Banned Users
        $banned_users = Laralum::users('banned', true);

        # Get all roles
        $roles = Laralum::roles();

        # Return the view
        return view('laralum/users/index', [
            'users' => $users,
            'title' => 'Users',
            'roles' => $roles,
            'active_users' => $active_users,
            'banned_users' => $banned_users,
            'count' => $count,
            'error' => $error,
            'user_type' => User::USER_TYPE_ALL
        ]);
    }

    public function printUsers(Request $request, $type)
    {
        if ($type == User::USER_TYPE_PATIENTS) {
            return redirect('admin/patient-list/print');
        }

        if ($type == User::USER_TYPE_ARCHIVED_PATIENTS) {
            return redirect('admin/archived-patient-list/print');
        }

        if ($type == User::USER_TYPE_DOCTORS) {
            return redirect('admin/doctors/print');
        }

        if ($type == User::USER_TYPE_PATIENTS) {
            return redirect('admin/patient-list/print');
        }

        Laralum::permissionToAccess('admin.users.list');
        $user_type = $request->get("user_type");
        $per_page = $request->get("per_page") ? $request->get("per_page") : 10;
        $pagination = true;
        if ($per_page == "All") {
            $pagination = false;
        }
        $matchThese = [];
        $userMatch = [];
        $userMatchN = [];
        $search = false;
        $option_ar = [];

        if ($request->s && $request->s != 'null') {
            $search_data = json_decode($request->s, true);
            if (!empty($search_data['name'])) {
                $option_ar[] = "Name";
                $search = true;
                $userMatch['name'] = trim($search_data['name']);
            }

            if (!empty($search_data['email'])) {
                $option_ar[] = "Email";
                $search = true;
                $userMatch['email'] = trim($search_data['email']);
            }

            $role_id = "";
            if (!empty($search_data['role_id'])) {
                $option_ar[] = "Role";
                $search = true;
                $userMatchN['role_id'] = trim($search_data['role_id']);
                $role_id = $search_data['role_id'];
            }

            $dept_id = "";
            if (!empty($search_data['department'])) {
                $option_ar[] = "Department";
                $search = true;
                $userMatchN['department'] = trim($search_data['department']);
                $dept_id = $search_data['department'];
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

        $users = User::select('users.*')->join('role_user', 'role_user.user_id', '=', 'users.id')->whereNotIn('role_user.role_id', [Role::getPatientId(), Role::getDoctorId()]);

        if ($user_type == User::USER_TYPE_DOCTORS) {
            $users = User::select('users.*')->join('role_user', 'role_user.user_id', '=', 'users.id')->where('role_user.role_id', Role::getDoctorId());
        }


        if ($search == true) {
            $users = User::select('users.*')
                ->leftjoin('role_user', 'role_user.user_id', '=', 'users.id');
            if (!empty($matchThese) || !empty($userMatch)) {
                $users = $users->where(function ($query) use ($matchThese, $userMatch) {
                    foreach ($matchThese as $key => $match) {
                        $query->where('user_profiles.' . $key, 'like', "%$match%");
                    }
                    foreach ($userMatch as $key => $match) {
                        $query->where('users.' . $key, 'like', "%$match%");
                    }
                });
            }

            /*$users = $users->orderBY('users.created_at', 'DESC');*/

            if ($role_id != "") {
                $users = $users->where('role_user.role_id', $role_id);
            } else {
                if ($user_type == User::USER_TYPE_DOCTORS) {
                    $users = $users->where('role_user.role_id', Role::getDoctorId());
                } else {
                    $users = $users->where(function ($query) {
                        $query->whereNotIn('role_user.role_id', [Role::getDoctorId(), Role::getPatientId()])->orWhereNull('role_user.id');
                    });
                }
            }

            if ($dept_id != "") {
                $users = $users->leftJoin('department_users', 'department_users.user_id', '=', 'users.id')->where('department_users.department_id', $dept_id);
            }
            $users_count = clone $users;
            $users = $users->get();
            $count = $users_count->count();
        } else {
            $users_get = clone $users;
            $count = $users->count();

            if ($pagination == true) {
                $users = $users_get->paginate($per_page);
            } else {
                $users = $users_get->get();
            }
        }

        # Return the view
        return view('laralum/users/print_users', [
            'users' => $users,
            'user_type' => $type,
            'print' => true,
        ]);
    }


    public function patients(Request $request)
    {
        /*Laralum::permissionToAccess('admin.users.list');*/

        $matchThese = [];
        $search = false;
        $option_ar = [];

        if ($request->has('filter_patient_id') && $request->get('filter_patient_id') != "") {
            $option_ar[] = "Patient Id";
            $matchThese['kid'] = trim($request->get('filter_patient_id'));
        }
        if ($request->has('filter_first_name') && $request->get('filter_first_name') != "") {
            $option_ar[] = "First Name";
            $matchThese['first_name'] = trim($request->get('filter_first_name'));
        }
        $filter_name = "";
        if ($request->has('filter_name') && $request->get('filter_name') != "") {
            $option_ar[] = "Name";
            $filter_name = trim($request->get('filter_name'));
        }
        if ($request->has('filter_last_name') && $request->get('filter_last_name') != "") {
            $option_ar[] = "Last Name";
            $matchThese['last_name'] = trim($request->get('filter_last_name'));
        }
        if ($request->has('filter_mobile') && $request->get('filter_mobile') != "") {
            $option_ar[] = "Mobile";
            $matchThese['mobile'] = trim($request->get('filter_mobile'));
        }

        $filter_email = "";

        if ($request->has('filter_email')) {
            $option_ar[] = "Email";
            $filter_email = trim($request->get('filter_email'));
        }


        $users = User::select('users.*')
            ->join('role_user', 'role_user.user_id', '=', 'users.id')
            ->leftJoin('user_profiles', 'user_profiles.user_id', '=', 'users.id')
            ->where('users.is_discharged', '!=', User::DISCHARGED)
            ->where('role_user.role_id', Role::getPatientId())
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
            })->orderBY('users.created_at', 'DESC');

        $per_page = $request->get("per_page") ? $request->get("per_page") : 10;
        $pagination = true;
        if ($per_page == "All") {
            $pagination = false;
        }
        $count = $users->count();
        if ($pagination == true) {
            $users = $users->paginate($per_page);
        } else {
            $users = $users->get();
        }
        $options = implode(", ", $option_ar);

        $error = "Entered " . $options . " is not valid,
make sure that you are entering valid " . $options . " 
or search by other options";
        # Get all users
        //$users = Laralum::users();
        # Get the active users
        $active_users = Laralum::users('active', true);

        # Get Banned Users
        $banned_users = Laralum::users('banned', true);

        # Get all roles
        $roles = Laralum::roles();

        # Return the view
        return view('laralum/users/index', [
            'users' => $users,
            'roles' => $roles,
            'active_users' => $active_users,
            'banned_users' => $banned_users,
            'title' => 'Patients',
            'error' => $error,
            'patient' => true,
            'error' => $error,
            'count' => $count,
            'user_type' => User::USER_TYPE_PATIENTS
        ]);
    }

    public function printPatients()
    {
        $users = User::select('users.*')
            ->join('role_user', 'role_user.user_id', '=', 'users.id')
            ->leftJoin('user_profiles', 'user_profiles.user_id', '=', 'users.id')
            ->where('users.is_discharged', '!=', User::DISCHARGED)
            ->where('role_user.role_id', Role::getPatientId())
            ->orderBY('users.created_at', 'DESC');

        $users = $users->get();

        # Return the view
        return view('laralum/users/print_users', [
            'users' => $users,
            'user_type' => User::USER_TYPE_PATIENTS,
            'print' => true,
            'back_url' => url("admin/patient-list")
        ]);
    }

    public function archivedPatients(Request $request, Route $route)
    {
        /*Laralum::permissionToAccess('admin.users.list');*/
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


        if (!empty($request->get('state'))) {
            $option_ar[] = "state";
            $search = true;
            $matchTheseAddress['state'] = $request->get('state');
        }

        if (!empty($request->get('country'))) {
            $option_ar[] = "Country";
            $search = true;
            $matchTheseAddress['country'] = $request->get('country');
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
            if ($request->get('first_name')) {
                $matchThese['first_name'] = $request->get('first_name');
            }

            $malefemale_query = clone $models_query;
            $models = clone $models_query;


            $males = $models_query->where('user_profiles.gender', UserProfile::GENDER_MALE)->count();
            // print_r($males);exit;
            $females = $malefemale_query->where('user_profiles.gender', UserProfile::GENDER_FEMALE)->count();
        }

       // return $models_query->get();

        $models = $models->groupBy('bookings.id');

        //return $models = $models->get();

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
       // return $models;
        if ($request->ajax()) {
            return [
                'html' => view('laralum/booking/_index', ['models' => $models,
                    'males' => $males, 'females' => $females,
                    'count' => $count,
                    'error' => $error,
                    'search' => $search,
                    'archived' => $archived,
                    'search_data' => array_merge($matchThese, $matchTheseAddress, $usermatchThese, $bookingmatchThese)
                ])->render()
            ];
        }


        return view('laralum.booking.index', compact('models', 'males', 'females', 'count', 'error', 'search', 'archived'));


        # Get all users
        //$users = Laralum::users();
        # Get the active users
        $active_users = Laralum::users('active', true);

        # Get Banned Users
        $banned_users = Laralum::users('banned', true);

        # Get all roles
        $roles = Laralum::roles();
        if ($request->ajax()) {
            return [
                'html' => view('laralum/users/_index', ['users' => $users,
                    'roles' => $roles,
                    'active_users' => $active_users,
                    'banned_users' => $banned_users,
                    'title' => 'Patients',
                    'error' => $error,
                    'patient' => true,
                    'males' => $males,
                    'females' => $females,
                    'user_type' => User::USER_TYPE_ARCHIVED_PATIENTS,
                    'count' => $count, 'error' => $error, 'search' => $search, 'search_data' => array_merge($matchThese, $profileMatchThese, $doctorMatchThese)])->render()
            ];
        }

        # Return the view
        return view('laralum/users/index', [
            'users' => $users,
            'roles' => $roles,
            'active_users' => $active_users,
            'banned_users' => $banned_users,
            'title' => 'Patients',
            'error' => $error,
            'patient' => true,
            'user_type' => User::USER_TYPE_ARCHIVED_PATIENTS,
            'count' => $count,
            'males' => $males,
            'females' => $females
        ]);
    }


    public function archivedPatientsWithAccomodations(Request $request, Route $route)
    {

        $matchThese = [];
        $bookingmatchThese = [];
        $usermatchThese = [];
        $search = false;
        $option_ar = [];
        if (!empty($request->get('kid'))) {
            $option_ar[] = "Patient Id";
            $search = true;
            $matchThese['kid'] = $request->get('kid');
        }
        if (!empty($request->get('uhid'))) {
            $option_ar[] = "UH Id";
            $search = true;
            $matchThese['uhid'] = $request->get('uhid');
        }

        if (!empty($request->get('first_name'))) {
            $option_ar[] = "Name";
            $search = true;
            $matchThese['first_name'] = $request->get('first_name');
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
        if ($request->has('email')) {
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
        $models = Booking::select('bookings.*')->leftjoin('users', 'users.id', '=', 'bookings.user_id')->leftjoin('user_profiles', 'user_profiles.id', '=', 'bookings.profile_id')->where('user_profiles.patient_type', '=', UserProfile::PATIENT_TYPE_IPD)->orderBY('users.created_at', 'DESC');
        // ->whereIn('bookings.status', [Booking::STATUS_DISCHARGED, Booking::STATUS_CANCELLED]);
        if ($search == true) {
            $models = Booking::select('bookings.*')->leftjoin('users', 'users.id', '=', 'bookings.user_id')->leftjoin('user_profiles', 'user_profiles.id', '=', 'bookings.profile_id')->where('user_profiles.patient_type', '=', UserProfile::PATIENT_TYPE_IPD)->where(function ($query) use ($matchThese, $bookingmatchThese, $usermatchThese) {
                foreach ($matchThese as $key => $match) {

                    $query->where('user_profiles.' . $key, 'like', "%" . $match . "%");

                }
                foreach ($bookingmatchThese as $key => $match) {
                    $query->where('bookings.' . $key, 'like', "%" . $match . "%");

                }
                foreach ($usermatchThese as $key => $match) {
                    $query->where('users.' . $key, 'like', "%" . $match . "%");

                }

            })->orderBY('users.created_at', 'DESC');
        }


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
        if ($request->ajax()) {
            return [
                'html' => view('laralum/booking/_patient_with_acc', ['models' => $models,
                    'count' => $count,
                    'error' => $error,
                    'search' => $search,
                    'archived' => $archived,
                    'search_data' => array_merge($matchThese)
                ])->render()
            ];
        }

        return view('laralum.booking.patient_with_acc', compact('models', 'count', 'error', 'search', 'archived'));


        # Get all users
        //$users = Laralum::users();
        # Get the active users
        $active_users = Laralum::users('active', true);

        # Get Banned Users
        $banned_users = Laralum::users('banned', true);

        # Get all roles
        $roles = Laralum::roles();
        if ($request->ajax()) {
            return [
                'html' => view('laralum/users/_index', ['users' => $users,
                    'roles' => $roles,
                    'active_users' => $active_users,
                    'banned_users' => $banned_users,
                    'title' => 'Patients',
                    'error' => $error,
                    'patient' => true,
                    'user_type' => User::USER_TYPE_ARCHIVED_PATIENTS,
                    'count' => $count, 'error' => $error, 'search' => $search, 'search_data' => array_merge($matchThese, $profileMatchThese, $doctorMatchThese)])->render()
            ];
        }

        # Return the view
        return view('laralum/users/index', [
            'users' => $users,
            'roles' => $roles,
            'active_users' => $active_users,
            'banned_users' => $banned_users,
            'title' => 'Patients',
            'error' => $error,
            'patient' => true,
            'user_type' => User::USER_TYPE_ARCHIVED_PATIENTS,
            'count' => $count
        ]);
    }


    public function printArchivedPatients(Request $request)
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

            if (!empty($search_string)) {
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

        # Return the view
        return view('laralum/booking/print-booking', [
            'models' => $models,
            'males' => $males,
            'females' => $females,
            'user_type' => User::USER_TYPE_ARCHIVED_PATIENTS,
            'print' => true,
            'back_url' => url("admin/archived-patient-list")
        ]);
    }

    public function archived_summary(Request $request, $id)
    {
        $data = $this->_summarydata($id);
        //dd($data['lab_tests']);
        //return $data['pain_assesment']->getValue('type_of_pain');
        return view('laralum.token.summary_details', $data);
    }

    public function archivedPatient(Request $request, $view = "view")
    {
        $matchThese = [];
        $search = false;
        $option_ar = [];
        if ($request->has('filter_patient_id') && $request->get('filter_patient_id') != "") {
            $option_ar[] = "Patient Id";
            $matchThese['kid'] = trim($request->get('filter_patient_id'));
        }

        $filter_name = "";
        if ($request->has('filter_name') && $request->get('filter_name') != "") {
            $option_ar[] = "Name";
            $filter_name = trim($request->get('filter_name'));
        }
        $filter_first_name = "";
        if ($request->has('filter_first_name') && $request->get('filter_first_name') != "") {
            $option_ar[] = "First Name";
            $matchThese['first_name'] = trim($request->get('filter_first_name'));
            $filter_first_name = trim($request->get('filter_first_name'));
        }
        $filter_last_name = "";
        if ($request->has('filter_last_name') && $request->get('filter_last_name') != "") {
            $option_ar[] = "Last Name";
            $matchThese['last_name'] = trim($request->get('filter_last_name'));
            $filter_last_name = trim($request->get('filter_last_name'));
        }
        if ($request->has('filter_mobile') && $request->get('filter_mobile') != "") {
            $option_ar[] = "Mobile";
            $matchThese['mobile'] = trim($request->get('filter_mobile'));
        }

        $filter_email = "";

        if ($request->has('filter_email')) {
            $option_ar[] = "Email";
            $filter_email = trim($request->get('filter_email'));
        }

        $booking = Booking::select('bookings.*')->join('users', 'users.id', '=', 'bookings.user_id')->join('user_profiles', 'user_profiles.user_id', '=', 'bookings.user_id')->where('bookings.status', Booking::STATUS_DISCHARGED)->where(function ($query) use ($matchThese, $filter_email, $filter_first_name, $filter_last_name, $filter_name) {
            foreach ($matchThese as $key => $match) {
                $query->where('user_profiles.' . $key, 'like', "%$match%");
            }
            if ($filter_email != "") {
                $query->where('users.email', 'like', "%$filter_email%");
            }

            if ($filter_first_name != "") {
                $query->where('user_profiles.first_name', 'like', "%$filter_first_name%");
            }

            if ($filter_last_name != "") {
                $query->where('user_profiles.last_name', 'like', "%$filter_last_name%");
            }/*

            if($filter_name != "") {
                $query->where('users.name', 'like', "%$filter_name%");
            }*/
        })->orderBY('users.created_at', 'DESC')->first();
        if ($booking != null)
            $user = $booking->user;
        $options = implode(", ", $option_ar);

        $error = "Entered " . $options . " is not valid,
make sure that you are entering valid " . $options . " 
or search by other options";
        return view('laralum.booking.' . $view, compact('booking', 'user', 'error', 'search'));

    }

    public function doctors(Request $request)
    {
        Laralum::permissionToAccess('admin.users.list');

        $matchThese = [];
        $option_ar = [];
        if ($request->has('filter_patient_id') && $request->get('filter_patient_id') != "") {
            $option_ar[] = 'Patient Id';
            $matchThese['user_id'] = $request->get('filter_patient_id');
        }

        $filter_name = "";
        if ($request->has('filter_name') && $request->get('filter_name') != "") {
            $option_ar[] = "Name";
            $filter_name = trim($request->get('filter_name'));
        }
        $filter_last_name = "";
        if ($request->has('filter_last_name') && $request->get('filter_last_name') != "") {
            $option_ar[] = "Last Name";
            $matchThese['last_name'] = trim($request->get('filter_last_name'));
            $filter_last_name = trim($request->get('filter_last_name'));
        }
        if ($request->has('filter_mobile') && $request->get('filter_mobile') != "") {
            $option_ar[] = 'Mobile No';
            $matchThese['mobile'] = trim($request->get('filter_mobile'));
        }

        $filter_email = "";

        if ($request->has('filter_email')) {
            $option_ar[] = 'Email';
            $filter_email = trim($request->get('filter_email'));
        }

        $users = User::select('users.*')
            ->join('role_user', 'role_user.user_id', '=', 'users.id')
            ->leftJoin('user_profiles', 'user_profiles.user_id', '=', 'users.id')
            ->where('role_user.role_id', Role::getDoctorId())
            /* ->where(function ($query) use ($matchThese, $filter_email, $filter_name, $filter_last_name) {
                 foreach ($matchThese as $key => $match) {
                     //$query->where('user_profiles.'.$key,'like',"%$match%");
                 }
                 if ($filter_email != "") {
                     $query->where('users.email', 'like', "%$filter_email%");
                 }
                 if ($filter_name != "") {
                     $query->where('users.name', 'like', "%$filter_name%");
                 }
             })*/
            ->orderBY('users.created_at', 'DESC');
        //dd('kayakalp');

        $count = $users->count();
        $options = implode(", ", $option_ar);
        $error = "Entered " . $options . " is not valid,
make sure that you are entering valid " . $options . " 
or search by other options";
        $per_page = $request->get("per_page") ? $request->get("per_page") : 10;
        $pagination = true;
        if ($per_page == "All") {
            $pagination = false;
        }

        if ($pagination == true) {
            $users = $users->paginate($per_page);
        } else {
            $users = $users->get();
        }
        # Get the active users
        $active_users = Laralum::users('active', true);

        # Get Banned Users
        $banned_users = Laralum::users('banned', true);

        # Get all roles
        $roles = Laralum::roles();

        # Return the view
        return view('laralum/users/index', [
            'users' => $users,
            'roles' => $roles,
            'active_users' => $active_users,
            'banned_users' => $banned_users,
            'title' => 'Doctors',
            'error' => $error,
            'count' => $count,
            'user_type' => User::USER_TYPE_DOCTORS
        ]);
    }

    public function printDoctors(Request $request)
    {
        Laralum::permissionToAccess('admin.users.list');

        $user_type = User::USER_TYPE_DOCTORS;
        $per_page = $request->get("per_page") ? $request->get("per_page") : 10;
        $pagination = true;
        if ($per_page == "All") {
            $pagination = false;
        }
        $matchThese = [];
        $userMatch = [];
        $userMatchN = [];
        $search = false;
        $option_ar = [];
        $role_id = "";
        $dept_id = "";

        if ($request->s && $request->s != 'null') {
            $search_data = json_decode($request->s, true);
            if (!empty($search_data['name'])) {
                $option_ar[] = "Name";
                $search = true;
                $userMatch['name'] = trim($search_data['name']);
            }

            if (!empty($search_data['email'])) {
                $option_ar[] = "Email";
                $search = true;
                $userMatch['email'] = trim($search_data['email']);
            }

            if (!empty($search_data['role_id'])) {
                $option_ar[] = "Role";
                $search = true;
                $userMatchN['role_id'] = trim($search_data['role_id']);
                $role_id = $search_data['role_id'];
            }

            if (!empty($search_data['department'])) {
                $option_ar[] = "Department";
                $search = true;
                $userMatchN['department'] = trim($search_data['department']);
                $dept_id = $search_data['department'];
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

        $users = User::select('users.*')->join('role_user', 'role_user.user_id', '=', 'users.id')->whereNotIn('role_user.role_id', [Role::getPatientId(), Role::getDoctorId()]);

        if ($user_type == User::USER_TYPE_DOCTORS) {
            $users = User::select('users.*')->join('role_user', 'role_user.user_id', '=', 'users.id')->where('role_user.role_id', Role::getDoctorId());
        }

        $users = $users->orderBY('users.created_at', 'DESC');

        if ($search == true) {
            $users = User::select('users.*')
                ->leftjoin('role_user', 'role_user.user_id', '=', 'users.id');
            if (!empty($matchThese) || !empty($userMatch)) {
                $users = $users->where(function ($query) use ($matchThese, $userMatch) {
                    foreach ($matchThese as $key => $match) {
                        $query->where('user_profiles.' . $key, 'like', "%$match%");
                    }
                    foreach ($userMatch as $key => $match) {
                        $query->where('users.' . $key, 'like', "%$match%");
                    }
                });
            }

            $users = $users->orderBY('users.created_at', 'DESC');

            if ($role_id != "") {
                $users = $users->where('role_user.role_id', $role_id);
            } else {
                if ($user_type == User::USER_TYPE_DOCTORS) {
                    $users = $users->where('role_user.role_id', Role::getDoctorId());
                } else {
                    $users = $users->where(function ($query) {
                        $query->whereNotIn('role_user.role_id', [Role::getDoctorId(), Role::getPatientId()])->orWhereNull('role_user.id');
                    });
                }
            }

            if ($dept_id != "") {
                $users = $users->leftJoin('department_users', 'department_users.user_id', '=', 'users.id')->where('department_users.department_id', $dept_id);
            }
            $users_count = clone $users;
            $users = $users->get();
            $count = $users_count->count();
        } else {
            $users_get = clone $users;
            $count = $users->count();

            if ($pagination == true) {
                $users = $users_get->paginate($per_page);
            } else {
                $users = $users_get->get();
            }
        }


        # Return the view
        return view('laralum/users/print_users', [
            'users' => $users,
            'user_type' => User::USER_TYPE_DOCTORS,
            'print' => true,
            'back_url' => url("admin/doctors")
        ]);
    }

    public function show($id)
    {
        Laralum::permissionToAccess('admin.users.list');

        # Find the user
        $user = Laralum::user('id', $id);

        # Return the view
        return view('laralum/users/show', ['user' => $user]);
    }

    public function create($user_type = User::USER_TYPE_ALL)
    {
        # Check permissions
        Laralum::permissionToAccess('admin.users.list');

        # Get all roles
        $roles = Laralum::roles();
        $departments = Department::all();

        # Get all the data
        $data_index = 'users';
        require('Data/Create/Get.php');

        $role = "";
        if ($user_type == User::USER_TYPE_DOCTORS) {
            $role = Role::getDoctorId();
        }
        # Return the view
        return view('laralum/users/create', [
            'roles' => $roles,
            'fields' => $fields,
            'confirmed' => $confirmed,
            'encrypted' => $encrypted,
            'hashed' => $hashed,
            'masked' => $masked,
            'table' => $table,
            'code' => $code,
            'wysiwyg' => $wysiwyg,
            'relations' => $relations,
            'departments' => $departments,
            'user_type' => $user_type,
            'role' => $role
        ]);
    }

    public function store(Request $request)
    {
        # Check permissions
        Laralum::permissionToAccess('admin.users.list');


        # create the user
        $row = Laralum::newUser();

        if ($request->user_type == User::USER_TYPE_DOCTORS) {
            if ($request->get('department_id') == null) {
                return redirect()->back()->with('error', 'Please select department');
            }
        }

        # Save the data
        $data_index = 'users';
        require('Data/Create/Save.php');

        # Setup a random activation key
        $row->activation_key = str_random(25);

        # Get the register IP
        $row->register_ip = $request->ip();

        # Activate the user if set
        if ($request->exists('active')) {
            $row->active = true;
        }

        # Save the user
        $row->save();

        if ($request->get('department_id') != null) {
            $row->saveDepartment($request->get('department_id'));
        }

        # Send welcome email if set
        if ($request->input('mail')) {
            # Send Welcome email
            $row->sendWelcomeEmail($row);
        }

        # Send activation email if set
        if ($request->exists('send_activation')) {
            $row->sendActivationEmail($row);
        }

        $this->saveRole($row->id, $request);

       // $this->setRoles($row->id, $request);
        $this->saveStaff($row->id, $request);
        if ($row->isDoctor()) {
            return redirect()->route('Laralum::doctors')->with('success', trans('laralum.msg_doctor_created'));
        }

        # Return the admin to the users page with a success message
        return redirect()->route('Laralum::users')->with('success', trans('laralum.msg_user_created'));
    }

    public function saveRole($id, Request $request)
    {
        Laralum::permissionToAccess('admin.users.list');

        # Find the user
        $user = Laralum::user('id', $id);

        # Check if admin access
        /*Laralum::mustNotBeAdmin($user);*/

        # Get all roles
        $roles = Laralum::roles();
        $role_id = $request->role_id;

        # Change user's roles
        foreach ($roles as $role) {

            $modify = true;

            # Check for su
            if ($role->su) {
                $modify = false;
            }

            # Check if it's assignable
            if (!$role->assignable and !Laralum::loggedInUser()->su) {
                $modify = false;
            }

            if ($modify) {
                if ($role_id == $role->id) {
                    # The admin selected that role

                    # Check if the user was already in that role
                    if ($this->checkRole($user->id, $role->id)) {
                        # The user is already in that role, so no change is made
                    } else {
                        # Add the user to the selected role
                        $this->addRel($user->id, $role->id);
                    }
                } else {
                    # The admin did not select that role

                    # Check if the user was in that role
                    if ($this->checkRole($user->id, $role->id)) {
                        # The user is in that role, so as the admin did not select it, we need to delete the relationship
                        $this->deleteRel($user->id, $role->id);
                    } else {
                        # The user is not in that role and the admin did not select it
                    }
                }
            }
        }

        # Return Redirect
        return redirect()->route('Laralum::users')->with('success', trans('laralum.msg_user_roles_edited'));
    }

    public function setRoles($id, Request $request)
    {
        Laralum::permissionToAccess('admin.users.list');


        # Find the user
        $user = Laralum::user('id', $id);

        # Check if admin access
        /*Laralum::mustNotBeAdmin($user);*/

        # Get all roles
        $roles = Laralum::roles();

        # Change user's roles
        foreach ($roles as $role) {

            $modify = true;

            # Check for su
            if ($role->su) {
                $modify = false;
            }

            # Check if it's assignable
            if (!$role->assignable and !Laralum::loggedInUser()->su) {
                $modify = false;
            }

            if ($modify) {
                if ($request->input($role->id)) {
                    # The admin selected that role

                    # Check if the user was already in that role
                    if ($this->checkRole($user->id, $role->id)) {
                        # The user is already in that role, so no change is made
                    } else {
                        # Add the user to the selected role
                        $this->addRel($user->id, $role->id);
                    }
                } else {
                    # The admin did not select that role

                    # Check if the user was in that role
                    if ($this->checkRole($user->id, $role->id)) {
                        # The user is in that role, so as the admin did not select it, we need to delete the relationship
                        $this->deleteRel($user->id, $role->id);
                    } else {
                        # The user is not in that role and the admin did not select it
                    }
                }
            }
        }

        # Return Redirect
        return redirect()->route('Laralum::users')->with('success', trans('laralum.msg_user_roles_edited'));
    }

    public function checkRole($user_id, $role_id)
    {
        Laralum::permissionToAccess('admin.users.list');

        # This function returns true if the specified user is found in the specified role and false if not

        if (Role_User::whereUser_idAndRole_id($user_id, $role_id)->first()) {
            return true;
        } else {
            return false;
        }

    }

    public function addRel($user_id, $role_id)
    {
        Laralum::permissionToAccess('admin.users.list');

        //$rel = Role_User::whereUser_idAndRole_id($user_id, $role_id)->first();
	$rel = Role_User::whereUser_id($user_id)->first();
        if (!$rel) {
            $rel = new Role_User;
            $rel->user_id = $user_id;
            $rel->role_id = $role_id;
            $rel->save();
        }else{
 	    $rel->role_id = $role_id;
            $rel->save();
         }
    }

    public function deleteRel($user_id, $role_id)
    {
        Laralum::permissionToAccess('admin.users.list');

        $rel = Role_User::whereUser_idAndRole_id($user_id, $role_id)->first();
        if ($rel) {
            $rel->delete();
        }
    }

    public function saveStaff($id, Request $request)
    {
        Laralum::permissionToAccess('admin.users.list');

        $roles = Laralum::roles();

        # Change user's roles
        foreach ($roles as $role) {
            $role_id = $request->input($role->id);
        }

        $user = User::find($id);
        $staff = Staff::where('user_id', $user->id)->first();
        if ($staff == null) {
            $staff = new Staff();
        }
        $staff->name = $user->name;
        $staff->department = $user->getStaffDepartment($role_id);
        $staff->status = Staff::STATUS_ACTIVE;
        $staff->save();
    }

    public function edit($id)
    {
        Laralum::permissionToAccess('admin.users.list');

        # Check permissions
        //Laralum::permissionToAccess('laralum.users.edit');

        # Find the user
        $row = User::find($id);

        # Check if admin access
        //     Laralum::mustNotBeAdmin($row);

        # Get all the data
        $data_index = 'users';
        require('Data/Edit/Get.php');

        # Return the view
        return view('laralum/users/edit', [
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
        Laralum::permissionToAccess('admin.users.list');

        # Check permissions
        // Laralum::permissionToAccess('laralum.users.edit');

        # Find the user
        $row = Laralum::user('id', $id);

        # Check if admin access
        Laralum::mustNotBeAdmin($row);

        # Save the data
        $data_index = 'users';
        require('Data/Edit/Save.php');
        $this->saveStaff($id, $request);

        if ($row->isDoctor()) {
            return redirect()->route('Laralum::doctors')->with('success', trans('laralum.msg_doctor_created'));
        }
        # Return the admin to the users page with a success message
        return redirect()->route('Laralum::users')->with('success', trans('laralum.msg_user_edited'));
    }

    public function editRoles($id)
    {
        Laralum::permissionToAccess('admin.users.list');

        # Check permissions
        //  Laralum::permissionToAccess('laralum.users.roles');

        # Find the user
        $user = Laralum::user('id', $id);

        # Check if admin access
        Laralum::mustNotBeAdmin($user);

        # Get all roles
        $roles = Laralum::roles();

        # Return the view
        return view('laralum/users/roles', ['user' => $user, 'roles' => $roles]);
    }

    public function destroy($id)
    {
        Laralum::permissionToAccess('admin.users.list');

        # Check permissions
        //  Laralum::permissionToAccess('laralum.users.delete');

        # Find The User
        $user = Laralum::user('id', $id);

        # Check if admin access
        Laralum::mustNotBeAdmin($user);

        # Check if it's su
        if ($user->su) {
            abort(403, trans('laralum.error_security_reasons'));
        }

        # Check before deleting
        if ($id == Laralum::loggedInUser()->id) {
            abort(403, trans('laralum.error_user_delete_yourself'));
        } else {
            # Delete Relationships
            $rels = Role_User::where('user_id', $user->id)->get();
            foreach ($rels as $rel) {
                $rel->delete();
            }
            DepartmentUser::where('user_id', $id)->delete();
            # Delete User
            $user->delete();

            # Return the admin with a success message
            return redirect()->route('Laralum::users')->with('success', trans('laralum.msg_user_deleted'));
        }
    }

    public function editSettings()
    {
        Laralum::permissionToAccess('admin.users.list');

        # Check permissions
        //   Laralum::permissionToAccess('laralum.users.settings');

        # Get the user settings
        $row = Laralum::userSettings();

        # Update the settings
        $data_index = 'users_settings';
        require('Data/Edit/Get.php');

        return view('laralum/users/settings', [
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

    public function updateSettings(Request $request)
    {
        Laralum::permissionToAccess('admin.users.list');

        # Check permissions
        //  Laralum::permissionToAccess('laralum.users.settings');

        # Get the user settings
        $row = Laralum::userSettings();

        # Update the settings
        $data_index = 'users_settings';
        require('Data/Edit/Save.php');

        # Return a redirect
        return redirect()->route('Laralum::users')->with('success', trans('laralum.msg_user_update_settings'));
    }


    public function editDepartment($id)
    {
        Laralum::permissionToAccess('admin.users.list');

        # Check permissions
        //Laralum::permissionToAccess('laralum.users.departments');

        # Find the user
        $user = Laralum::user('id', $id);

        # Check if admin access
        Laralum::mustNotBeAdmin($user);

        # Get all departments
        $departments = Laralum::departments();

        # Return the view
        return view('laralum/users/departments', ['user' => $user, 'departments' => $departments]);
    }

    public function setDepartment($id, Request $request)
    {
        Laralum::permissionToAccess('admin.users.list');

        # Check permissions
        // Laralum::permissionToAccess('laralum.users.roles');

        # Find the user
        $user = Laralum::user('id', $id);

        # Check if admin access
        Laralum::mustNotBeAdmin($user);

        # Get all departments
        $departments = Laralum::departments();

        # Change user's roles
        foreach ($departments as $department) {

            if ($request->get('department_id') == $department->id) {
                # The admin selected that role

                # Check if the user was already in that role
                if ($this->checkDepartment($user->id, $department->id)) {
                    # The user is already in that role, so no change is made
                } else {
                    # Add the user to the selected role
                    $this->addDepartment($user->id, $department->id);
                }
            } else {
                # The admin did not select that role

                # Check if the user was in that role
                if ($this->checkDepartment($user->id, $department->id)) {
                    # The user is in that role, so as the admin did not select it, we need to delete the relationship
                    $this->deleteDepartment($user->id, $department->id);
                } else {
                    # The user is not in that role and the admin did not select it
                }
            }
        }
        if ($user->isDoctor()) {
            return redirect()->route('Laralum::doctors')->with('success', trans('laralum.msg_user_department_edited'));
        }
        # Return Redirect
        return redirect()->route('Laralum::users')->with('success', trans('laralum.msg_user_department_edited'));
    }

    public function checkDepartment($user_id, $department_id)
    {
        Laralum::permissionToAccess('admin.users.list');

        # This function returns true if the specified user is found in the specified role and false if not

        if (DepartmentUser::whereUser_idAndDepartment_id($user_id, $department_id)->first()) {
            return true;
        } else {
            return false;
        }

    }

    public function addDepartment($user_id, $department_id)
    {
        Laralum::permissionToAccess('admin.users.list');

        $rel = DepartmentUser::whereUser_idAndDepartment_id($user_id, $department_id)->first();
        if (!$rel) {
            $rel = new DepartmentUser;
            $rel->user_id = $user_id;
            $rel->department_id = $department_id;
            $rel->save();
        }
    }

    public function deleteDepartment($user_id, $department_id)
    {
        Laralum::permissionToAccess('admin.users.list');

        $rel = DepartmentUser::whereUser_idAndDepartment_id($user_id, $department_id)->first();
        if ($rel) {
            $rel->delete();
        }
    }

    public function export(Request $request, $type, $user_type)
    {
        Laralum::permissionToAccess('admin.users.list');
        $users = [];
        /*if ($user_type == User::USER_TYPE_ALL) {

            $users = User::select('users.*')->leftJoin('role_user', 'role_user.user_id', '=', 'users.id')->whereNotIn('role_user.role_id', [Role::getPatientId(), Role::getDoctorId()]);
        } elseif ($user_type == User::USER_TYPE_DOCTORS) {
            $users = User::select('users.*')
                ->leftJoin('role_user', 'role_user.user_id', '=', 'users.id')
                ->leftJoin('user_profiles', 'user_profiles.user_id', '=', 'users.id')
                ->where('role_user.role_id', Role::getDoctorId())
                ->orderBY('users.created_at', 'DESC');
        } elseif ($user_type == User::USER_TYPE_PATIENTS) {

            $users = User::select('users.*')
                ->leftJoin('role_user', 'role_user.user_id', '=', 'users.id')
                ->leftJoin('user_profiles', 'user_profiles.user_id', '=', 'users.id')
                ->where('users.is_discharged', '!=', User::DISCHARGED)
                ->where('role_user.role_id', Role::getPatientId())
                ->orderBY('users.created_at', 'DESC');
        } else {
            $users = User::select('users.*')
                ->leftJoin('role_user', 'role_user.user_id', '=', 'users.id')
                ->leftJoin('user_profiles', 'user_profiles.user_id', '=', 'users.id')
                ->where('users.is_discharged', '=', User::DISCHARGED)
                ->where('role_user.role_id', Role::getPatientId())
                ->orderBY('users.created_at', 'DESC');
        }

        $per_page = $request->get("per_page") ? $request->get("per_page") : 10;
        $pagination = true;
        if ($per_page == "All") {
            $pagination = false;
        }
        $count = $users->count();
        if ($pagination == true) {
            $users = $users->paginate($per_page);
        } else {
            $users = $users->get();
        }*/


        Laralum::permissionToAccess('admin.users.list');

        $per_page = $request->get("per_page") ? $request->get("per_page") : 10;
        $pagination = true;
        if ($per_page == "All") {
            $pagination = false;
        }
        $matchThese = [];
        $userMatch = [];
        $userMatchN = [];
        $search = false;
        $option_ar = [];
        $role_id = "";
        $dept_id = "";

        if ($request->s && $request->s != 'null') {
            $search_data = json_decode($request->s, true);
            if (!empty($search_data['name'])) {
                $option_ar[] = "Name";
                $search = true;
                $userMatch['name'] = trim($search_data['name']);
            }

            if (!empty($search_data['email'])) {
                $option_ar[] = "Email";
                $search = true;
                $userMatch['email'] = trim($search_data['email']);
            }


            if (!empty($search_data['role_id'])) {
                $option_ar[] = "Role";
                $search = true;
                $userMatchN['role_id'] = trim($search_data['role_id']);
                $role_id = $search_data['role_id'];
            }


            if (!empty($search_data['department'])) {
                $option_ar[] = "Department";
                $search = true;
                $userMatchN['department'] = trim($search_data['department']);
                $dept_id = $search_data['department'];
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

        $users = User::select('users.*')->join('role_user', 'role_user.user_id', '=', 'users.id')->whereNotIn('role_user.role_id', [Role::getPatientId(), Role::getDoctorId()]);

        if ($user_type == User::USER_TYPE_DOCTORS) {
            $users = User::select('users.*')->join('role_user', 'role_user.user_id', '=', 'users.id')->where('role_user.role_id', Role::getDoctorId());
        }

        /*$users = $users->orderBY('users.created_at', 'DESC');*/

        if ($search == true) {
            $users = User::select('users.*')
                ->leftjoin('role_user', 'role_user.user_id', '=', 'users.id');
            if (!empty($matchThese) || !empty($userMatch)) {
                $users = $users->where(function ($query) use ($matchThese, $userMatch) {
                    foreach ($matchThese as $key => $match) {
                        $query->where('user_profiles.' . $key, 'like', "%$match%");
                    }
                    foreach ($userMatch as $key => $match) {
                        $query->where('users.' . $key, 'like', "%$match%");
                    }
                });
            }

            /*$users = $users->orderBY('users.created_at', 'DESC');*/

            if ($role_id != "") {
                $users = $users->where('role_user.role_id', $role_id);
            } else {
                if ($user_type == User::USER_TYPE_DOCTORS) {
                    $users = $users->where('role_user.role_id', Role::getDoctorId());
                } else {
                    $users = $users->where(function ($query) {
                        $query->whereNotIn('role_user.role_id', [Role::getDoctorId(), Role::getPatientId()])->orWhereNull('role_user.id');
                    });
                }
            }

            if ($dept_id != "") {
                $users = $users->leftJoin('department_users', 'department_users.user_id', '=', 'users.id')->where('department_users.department_id', $dept_id);
            }
            $users_count = clone $users;
            $users = $users->get();
            $count = $users_count->count();
        } else {
            $users_get = clone $users;
            $count = $users->count();

            if ($pagination == true) {
                $users = $users_get->paginate($per_page);
            } else {
                $users = $users_get->get();
            }
        }

        $users_array = [];

        if ($user_type == User::USER_TYPE_ALL) {
            $users_array[] = [
                'Name', 'Email', 'Role'
            ];

            foreach ($users as $user) {
                $role = isset($user->userRole->role->name) ? $user->userRole->role->name : "";
                $users_array[] = [
                    $user->name,
                    $user->email,
                    $role,
                ];
            }
        } elseif ($user_type == User::USER_TYPE_DOCTORS) {
            $users_array[] = [
                'Name', 'Email', 'Department'
            ];
            foreach ($users as $user) {
                $dep = isset($user->department->department->title) ? $user->department->department->title : "";
                $users_array[] = [
                    $user->name,
                    $user->email,
                    $dep
                ];
            }
        } else {
            $users_array[] = [
                'Name', 'Patient Id ', 'Email'
            ];
            foreach ($users as $user) {
                $users_array[] = [
                    $user->name,
                    $user->userProfile->kid,
                    $user->email
                ];
            }
        }

        // Generate and return the spreadsheet
        $excel = \App::make('excel');
        $excel = $excel->create('users', function ($excel) use ($users_array) {

            // Set the spreadsheet title, creator, and description
            $excel->setTitle('User List');

            // Build the spreadsheet, passing in the payments array
            $excel->sheet('sheet1', function ($sheet) use ($users_array) {
                $sheet->fromArray($users_array, null, 'A1', false, false);
            });


        });
        if ($type == Settings::EXPORT_CSV) {
            $excel->download('csv');
        } elseif ($type == Settings::EXPORT_EXCEL) {
            $excel->download('xls');
        } else {
            $pdf = PDF::loadView('booking.pdf', array('data' => $users_array));
            //$pdf = \PDF::loadView('booking.pdf',array('data'=>$users_array));
            return $pdf->download('users_list.pdf');
//            $view = \View::make('laralum.settings.pdf',
//                compact('users_array'))->render();
//
//            $pdf = \App::make('dompdf.wrapper');
//            $pdf->loadHTML($view);
//            $pdf_name = date('YmdHis'). '.pdf';
//            $pdf->save(public_path('pdf/' . $pdf_name));
//
//            //return Redirect::to('/pdf/'.$pdf_name);
            // try{
            // $excel->download('pdf');

            // }catch(\Exception $e){
            //     echo "<pre>";print_r($e);die;
            // }

        }

        return redirect()->back()->with('error', 'Something went Wrong!!!');
    }

    public function ajaxUpdate(Request $request)
    {
        Laralum::permissionToAccess('admin.users.list');
        $user_type = $request->get("user_type");
        $per_page = $request->get("per_page") ? $request->get("per_page") : 10;
        $pagination = true;
        if ($per_page == "All") {
            $pagination = false;
        }
        $matchThese = [];
        $userMatch = [];
        $userMatchN = [];
        $search = false;
        $option_ar = [];

        if (!empty($request->has('name'))) {
            $option_ar[] = "Name";
            $search = true;
            $userMatch['name'] = trim($request->get('name'));
        }

        if (!empty($request->has('email'))) {
            $option_ar[] = "Email";
            $search = true;
            $userMatch['email'] = trim($request->get('email'));
        }

        $role_id = "";
        if (!empty($request->has('role_id'))) {
            $option_ar[] = "Role";
            $search = true;
            $userMatchN['role_id'] = trim($request->get('role_id'));
            $role_id = $request->get('role_id');
        }

        $dept_id = "";
        if (!empty($request->has('department'))) {
            $option_ar[] = "Department";
            $search = true;
            $userMatchN['department'] = trim($request->get('department'));
            $dept_id = $request->get('department');
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

        $users = User::select('users.*')->join('role_user', 'role_user.user_id', '=', 'users.id')->whereNotIn('role_user.role_id', [Role::getPatientId(), Role::getDoctorId()]);

        if ($user_type == User::USER_TYPE_DOCTORS) {
            $users = User::select('users.*')->join('role_user', 'role_user.user_id', '=', 'users.id')->where('role_user.role_id', Role::getDoctorId());
        }


        if ($search == true) {
            $users = User::select('users.*')
                ->leftjoin('role_user', 'role_user.user_id', '=', 'users.id');
            if (!empty($matchThese) || !empty($userMatch)) {
                $users = $users->where(function ($query) use ($matchThese, $userMatch) {
                    foreach ($matchThese as $key => $match) {
                        $query->where('user_profiles.' . $key, 'like', "%$match%");
                    }
                    foreach ($userMatch as $key => $match) {
                        $query->where('users.' . $key, 'like', "%$match%");
                    }
                });
            }

            $users = $users->orderBY('users.created_at', 'DESC');

            if ($role_id != "") {
                $users = $users->where('role_user.role_id', $role_id);
            } else {
                if ($user_type == User::USER_TYPE_DOCTORS) {
                    $users = $users->where('role_user.role_id', Role::getDoctorId());
                } else {
                    $users = $users->where(function ($query) {
                        $query->whereNotIn('role_user.role_id', [Role::getDoctorId(), Role::getPatientId()])->orWhereNull('role_user.id');
                    });
                }
            }

            if ($dept_id != "") {
                $users = $users->leftJoin('department_users', 'department_users.user_id', '=', 'users.id')->where('department_users.department_id', $dept_id);
            }
            $users_count = clone $users;
            $users = $users->get();
            $count = $users_count->count();
        } else {
            $users_get = clone $users;
            $count = $users->count();

            if ($pagination == true) {
                $users = $users_get->paginate($per_page);
            } else {
                $users = $users_get->get();
            }
        }
        /*echo '<pre>'; print_r($matchThese['role_id']);exit;*/
        # Return the view
        return [
            'html' => view('laralum/users/_index', ['users' => $users, 'count' => $count, 'error' => $error, 'search' => $search, 'user_type' => $user_type, 'search_data' => array_merge($matchThese, $userMatch, $userMatchN)])->render()
        ];

    }

    public function ajaxUpdateArchived(Request $request)
    {
        Laralum::permissionToAccess(['admin.patients.list', 'doctor.patients', 'admin.bookings.list']);

        $matchThese = [];
        $bookingmatchThese = [];
        $usermatchThese = [];
        $search = false;
        $option_ar = [];
        if (!empty($request->get('patient_id'))) {
            $option_ar[] = "Patient Id";
            $search = true;
            $matchThese['kid'] = $request->get('patient_id');
        }
        if (!empty($request->get('name'))) {
            $option_ar[] = "Name";
            $search = true;
            $matchThese['first_name'] = $request->get('name');
        }

        if (!empty($request->get('first_name'))) {
            $option_ar[] = "Name";
            $search = true;
            $matchThese['first_name'] = $request->get('first_name');
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
        if ($request->has('email')) {
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

        $models = Booking::select('bookings.*')->join('users', 'users.id', '=', 'bookings.user_id')->join('user_profiles', 'user_profiles.id', 'bookings.profile_id')->orderBy('bookings.created_at', 'DESC');

        $models = $models->whereIn('status', [Booking::STATUS_DISCHARGED]);
        if ($search == true) {
            $models = Booking::select('bookings.*')->join('users', 'users.id', '=', 'bookings.user_id')->join('user_profiles', 'user_profiles.id', 'bookings.profile_id')->where(function ($query) use ($matchThese, $bookingmatchThese, $usermatchThese) {
                foreach ($matchThese as $key => $match) {
                    $query->where('user_profiles.' . $key, 'like', "%$match%");
                }
                foreach ($bookingmatchThese as $key => $match) {
                    $query->where('bookings.' . $key, 'like', "%$match%");
                }
                foreach ($usermatchThese as $key => $match) {
                    $query->where('users.' . $key, 'like', "%$match%");
                }
            })
                ->orderBy('bookings.created_at', 'DESC');
            $models = $models->whereIn('status', [Booking::STATUS_DISCHARGED]);

            if ($acm_status != "") {
                $models = $models->where('accommodation_status', $acm_status);
            }
            $count = $models->count();
            $models = $models->get();
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
            'html' => view('laralum/booking/_index', ['models' => $models, 'count' => $count, 'error' => $error, 'search' => $search, 'search_data' => array_merge($matchThese, $bookingmatchThese, $usermatchThese)])->render()
        ];

    }
}
