<?php

namespace App\Http\Controllers;

use App\AyurvedaAshtvidhExamination;
use App\AyurvedAturExamination;
use App\AyurvedDhatuExamination;
use App\AyurvedDoshExamination;
use App\Booking;
use App\CardiovascularExamination;
use App\DischargePatient;
use App\GastrointestinalExamination;
use App\GenitourinaryExamination;
use App\NeurologicalExamination;
use App\PatientDetails;
use App\PhysicalExamination;
use App\PhysiotherapyMotorExamination;
use App\PhysiotherapyPainAssesment;
use App\PhysiotherapyPainExamination;
use App\PhysiotherapySensoryExamination;
use App\PhysiotherapySystemicExamination;
use App\RespiratoryExamination;
use App\Settings;
use App\UserBooking;
use App\UserProfile;
use App\User;
use App\VitalData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UserProfileController extends Controller
{

    public function bookings(Request $request)
    {
        $user = \Auth::user();
        $per_page = $request->get('per-page') ? $request->get('per-page') : 10;
        $models = Booking::where('user_id', $user->id)->paginate($per_page);
        $count = Booking::where('user_id', $user->id)->count();
        return view('users.bookings', compact('user', 'models', 'count'));
    }

    //update or create new user profile
    public function updateProfile(Request $request, $id = null) {
        $result = [
            'status' => 'NOK'
        ];
        if($id == null) {
            $id = \Auth::user()->id;
        }
        $user = User::findOrFail($id);
        $user_profile = $user->userProfile;

        if($user_profile == null)
            $user_profile = new UserProfile();

        //Validating requested data
        $rules = [
            'email' => 'required'
        ];
        $validator = \Validator::make($request->all(), $rules);

        if($validator->fails()) {
            return $validator->errors();
        }

        if ($user->setData($request)) {
            $user->save();
            if ($user_profile->setData($request, $id)) {
                $user_profile->save();
                $result['status'] = 'OK';
                $user = User::with('userProfile')->where('id', $id)->first();
                $result['data'] = $user->toArray();
                $result['data']['user_profile']['profile_picture'] = $user->userProfile->profile_picture != null ? Settings::getImageUrl($user->userProfile->profile_picture) : "";
            }
        }
        return $result;
    }

    public function changePassword($id = null)
    {
        if($id == null) {
            $id = \Auth::user()->id;
        }

        $user = User::find($id);
        $data['title'] = 'Change Password';/*.$user->name.'('.$user->getRoleName().')';*/
        $data['user'] = $user;

        return view('users.change-password',$data);
    }

    public function postChangePassword(Request $request)
    {
        $rules = [
            'password' => 'required|min:6|confirmed',
            'user_id' => 'required',
            'old_password' => 'required',
        ];

        $validator = \Validator::make($request->all(), $rules);

        if($validator->fails()) {
            $errors = $validator->errors();
            return redirect()->back()->withErrors($validator->errors());
        }

        $id = $request->get('user_id');

        $user = User::find($id);

        if(Hash::check($request->get('old_password'), $user->password)){

            $user->update(
                [
                    'password' => bcrypt($request->get('password'))
                ]
            );
        }else{
            $errors['old_password'] = 'You have entered wrong password';
            return redirect()->back()->withErrors($errors);
        }

        return redirect()->back()->with('success', 'Successfully Changed Your password');
    }

    /**
     * Check user email already exists or not
     *
     * @param post data
     * @return User
     */

    public function checkUserMail(Request $request){

        $user = User::where('email', $request->get('email'))->first();

        if(count($user) > 0){
            if(Auth::check() && Auth::id() == $user->id) {   //  verify user account email after login and update his email address
                exit('true');
            }else {
                exit('false');
            }
        }else{
            exit('true');
        }
    }

    public function bookingDetail($id)
    {
        $user = \Auth::user();

        $booking = Booking::where([
            'id' => $id,
            'user_id' => $user->id,
            'status' => Booking::STATUS_COMPLETED
        ])->first();

        if ($booking == null) {
            return redirect('bookings/'.$id.'/personal-details');
        }

        $healthIssues = $booking->healthIssues;
        
        return view("booking.view", compact('booking', 'user', 'healthIssues'));
    }

    public function printBooking(Request $request, $id)
    {
        $user = \Auth::user();
        $booking = Booking::where([
            'id' => $id,
            'user_id' => $user->id,
        ])->first();

        $patient_details = PatientDetails::where("booking_id", $id)->orderBy("created_at", "DESC")->first();

        if ($patient_details == null) {
            $patient_details = new PatientDetails();
        }

        $back_url = url('booking/'.$id.'/booking-detail');
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
        $data['back_url'] = $back_url;

        return view('booking.print-booking-details', $data);
    }


    public function account(Request $request, $id)
    {
        $user = \Auth::user();
        $booking = Booking::where([
            'id' => $id,
            'user_id' => $user->id,
        ])->first();
        $data['booking'] = $booking;
        $data['user'] = $user;
        return view('booking.account', $data);
    }

    public function printAccount($id)
    {
        $user = \Auth::user();
        $booking = Booking::where([
            'id' => $id,
            'user_id' => $user->id,
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
        $data['back_url'] = url('bookings/'.$id.'/account');

        return view('booking.print-account-details', $data);
    }
}

