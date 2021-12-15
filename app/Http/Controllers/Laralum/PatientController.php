<?php

namespace App\Http\Controllers\Laralum;

use App\Booking;
use App\Http\Controllers\Controller;
use App\PatientTreatment;
use App\Role;
use App\Settings;
use App\TreatmentToken;
use App\User;
use App\UserProfile;
use App\Wallet;
use Illuminate\Http\Request;

class PatientController extends Controller
{
    //
    /**
     * patient listing
     * @return View
     */
    public function index(Request $request)
    {
        Laralum::permissionToAccess('admin.patients.list');
        $matchThese =   [];
        $search = false;
        $option_ar = [];
        $per_page = $request->get("per_page") ? $request->get("per_page") : 10;
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
            
            
            $patients = UserProfile::select('user_profiles.*')
                ->join('users', 'user_profiles.user_id', '=', 'users.id')
                ->join('role_user', 'role_user.user_id', '=', 'user_profiles.user_id')
                ->where('role_user.role_id', Role::getPatientId())
                ->whereNotNull('user_profiles.kid')
                ->where(function($query) use ($matchThese,$filter_email) {
                    foreach($matchThese as $key=>$match){
                        $query->where('user_profiles.'.$key,'like',"%$match%");
                    }
                    if($filter_email != "") {
                        $query->where('users.email', 'like', "%$filter_email%");
                    }
                })->where('role_user.role_id', Role::getPatientId())->orderBY('users.created_at', 'DESC')
                ->paginate($per_page);

        }else{
            $patients = User::select('users.*')->join('role_user', 'role_user.user_id', '=', 'users.id')->join('user_profiles', 'user_profiles.user_id', '=', 'users.id')->where('role_user.role_id', Role::getPatientId())->whereNotNull('user_profiles.kid')->orderBy('created_at', 'DESC')->paginate($per_page);

        }

        return view('laralum.patient.index',compact('patients', 'search', 'error'));
    }

    /**
     * patient details with replies
     * @return View
     */
    public function view($id)
    {
        /*Laralum::permissionToAccess('patients');*/
        $user = User::find($id);

        return view('laralum.patient.view',compact('user'));
    }

    public function accommodationDetails($id)
    {
        $bookings = Booking::where('user_id', $id)->whereNotIn('status', [Booking::STATUS_CANCELLED, Booking::STATUS_PENDING])->get();
        $user = User::find($id);

        return view('laralum.patient.ajax-info',compact('user', 'bookings'));
    }

    public function accountDetails($id)
    {
        $wallets = Wallet::where('user_id', $id)->get();
        $user = User::find($id);
        return view('laralum.patient.ajax-info',compact('user', 'wallets'));
    }


    public function getTreatmentDetails($id)
    {
        $user = User::find($id);
        $treatments = $user->getTreatments();
        return view('laralum.booking.get-booking-info',compact('treatments', 'user'));
    }

    public function getAllTreatments()
    {
        $treatments = TreatmentToken::where('treatment_date', (string) date("Y-m-d"))->get();
        return view('laralum.booking.generate-treatment-token',compact('treatments'));
    }


    public function export(Request $request, $type)
    {
        $users = User::select('users.*')
            ->join('role_user', 'role_user.user_id', '=', 'users.id')
            ->leftJoin('user_profiles', 'user_profiles.user_id', '=', 'users.id')
            ->where('users.is_discharged', '=', User::DISCHARGED)
            ->where('role_user.role_id', Role::getPatientId())
            ->orderBY('users.created_at', 'DESC');
        $per_page = $request->get("per_page") ? $request->get("per_page") : 10;
        $pagination = true;
        if ($per_page == "All") {
            $pagination = false;
        }
        $count = $users->count();
        if($pagination == true) {
            $users = $users->paginate($per_page);
        }else{
            $users = $users->get();
        }
        $users_array[] = [
            'Name','Patient Id ','Email'
        ];
        foreach ($users as $user) {
            $users_array[] = [
                $user->name,
                $user->userProfile->kid,
                $user->email
            ];
        }

        // Generate and return the spreadsheet
        $excel = \App::make('excel');
        $excel = $excel->create('users', function($excel) use ($users_array) {

            // Set the spreadsheet title, creator, and description
            $excel->setTitle('Archived Patientss');
            $excel->setCreator('Laravel')->setCompany('WJ Gilmore, LLC');
            $excel->setDescription('Archived Patientss file');

            // Build the spreadsheet, passing in the payments array
            $excel->sheet('sheet1', function($sheet) use ($users_array) {
                $sheet->fromArray($users_array, null, 'A1', false, false);
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
}
