<?php

namespace App\Http\Controllers\Laralum;

use Anam\PhantomMagick\Facades\Converter;
use App\ApplyPhysiotherpyExcercise;
use App\ApplyRecommendExcercise;
use App\AyurvedaAshtvidhExamination;
use App\AyurvedAturExamination;
use App\AyurvedDoshExamination;
use App\AyurvedDhatuExamination;
use App\Booking;
use App\CardiovascularExamination;
use App\Department;
use App\DepartmentDischargeBooking;
use App\DietChart;
use App\DietChartItems;
use App\DischargePatient;
use App\GastrointestinalExamination;
use App\GenitourinaryExamination;
use App\LabTest;
use App\NeurologicalExamination;
use App\SkinExamination;
use App\EyeExamination;
use App\PatientDetails;
use App\PatientDiagnosis;
use App\PatientLabTest;
use App\PatientToken;
use App\PatientTreatment;
use App\PhysicalExamination;
use App\PhysiotherapyExercise;
use App\PhysiotherapyMotorExamination;
use App\PhysiotherapyPainAssesment;
use App\PhysiotherapyPainExamination;
use App\PhysiotherapySensoryExamination;
use App\PhysiotherapySystemicExamination;
use App\RespiratoryExamination;
use App\Role;
use App\Settings;
use App\SystemFile;
use App\Transaction;
use App\TreatmentToken;
use App\User;
use App\UserProfile;
use App\VitalData;
use App\Wallet;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Mail;
use Milon\Barcode\DNS1D;
use Monolog\Handler\IFTTTHandler;
use niklasravnsborg\LaravelPdf\Facades\Pdf;
use SebastianBergmann\Comparator\Book;
use App\RomJoint;
use App\RomSubCategory;
use SnappyPDF;
use Response;



class TokenController extends Controller
{
    //

    public function patients(Request $request)
    {
        $tokens = PatientToken::select('patient_tokens.*')->groupBy('patient_id')->where('doctor_id', \Auth::user()->id)->where('department_id', \Auth::user()->department->department_id)->where('status', '!=', PatientToken::STATUS_CANCELLED)->with(PatientToken::getAllRelations())->whereHas('booking', function ($query) {
            $query->where('status', Booking::STATUS_COMPLETED);
        })->orderBy('created_at', 'DESC');

        $per_page = $request->get("per_page") ? $request->get("per_page") : 10;
        $pagination = true;
        if ($per_page == "All") {
            $pagination = false;
        }

        if ($pagination == true) {
            $tokens = $tokens->paginate($per_page);
        } else {
            $tokens = $tokens->get();
        }
        return view('laralum.token.patients', compact('tokens'));
    }

    public function searchPatients(Request $request)
    {
        $matchThese = [];

        if ($request->has('filter_patient_id') && $request->get('filter_patient_id') != "") {
            $matchThese['kid'] = $request->get('filter_patient_id');
        }
        $filter_name = "";
        if ($request->has('filter_name') && $request->get('filter_name') != "") {
            $filter_name = $request->get('filter_name');
        }
        if ($request->has('filter_first_name') && $request->get('filter_first_name') != "") {
            $matchThese['first_name'] = $request->get('filter_first_name');
        }

        if ($request->has('filter_last_name') && $request->get('filter_last_name') != "") {
            $matchThese['last_name'] = $request->get('filter_last_name');
        }
        if ($request->has('filter_mobile') && $request->get('filter_mobile') != "") {
            $matchThese['mobile'] = $request->get('filter_mobile');
        }

        $filter_email = "";

        if ($request->has('filter_email')) {
            $filter_email = $request->get('filter_email');
        }
        $per_page = $request->get("per_page") ? $request->get("per_page") : 10;
        $pagination = true;
        if ($per_page == "All") {
            $pagination = false;
        }

        $tokens = PatientToken::select('patient_tokens.*')->with(PatientToken::getAllRelations())
            ->where('doctor_id', \Auth::user()->id)
            ->join('users', 'patient_tokens.patient_id', '=', 'users.id')
            ->join('user_profiles', 'patient_tokens.patient_id', '=', 'user_profiles.user_id')
            ->where('patient_tokens.doctor_id', \Auth::user()->id)
            ->whereHas('booking', function ($query) {
                $query->where('status', Booking::STATUS_COMPLETED);
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
            })->join('role_user', 'role_user.user_id', 'users.id')->where('role_user.role_id', Role::getPatientId())->orderBY('users.created_at', 'DESc');
        if ($pagination == true) {
            $tokens = $tokens->paginate($per_page);
        } else {
            $tokens = $tokens->get();
        }

        return view('laralum.token.patients', compact('tokens'));
    }

    public function tokens(Request $request)
    {
        $tokens = PatientToken::select('patient_tokens.*');
        if (\Auth::user()->isDoctor()) {
            $dep_id = \Auth::user()->department->department_id;
            $tokens = $tokens->where('department_id', $dep_id)->where('doctor_id', \Auth::user()->id);
        }

        $date = (string)date('Y-m-d');
        $tokens = $tokens->where(\DB::raw('Date(start_date)'), $date)->where('status', '!=', PatientToken::STATUS_CANCELLED)->whereHas('booking', function ($query) {
            $query->where('status', Booking::STATUS_COMPLETED);
        });
        $per_page = $request->get("per_page") ? $request->get("per_page") : 10;
        $pagination = true;
        if ($per_page == "All") {
            $pagination = false;
        }
        $count = $tokens->count();
        if ($pagination == true) {

            $tokens = $tokens->paginate($per_page);
        } else {
            $tokens = $tokens->get();
        }

        return view('laralum.token.tokens', compact('tokens', 'count'));
    }

    public function showPatient($id)
    {

        $user = Auth::user();

        if ($user->isAdmin()) {

            $token = PatientToken::where('booking_id', $id)->where('status', PatientToken::STATUS_PENDING)->orderBy('created_at', 'DESC')->whereHas('booking', function ($query) {
                $query->where('status', Booking::STATUS_COMPLETED);
            })->first();


        } else {

            $token = PatientToken::where('booking_id', $id)->where('doctor_id', \Auth::user()->id)->where("department_id", \Auth::user()->department->department_id)->where('status', PatientToken::STATUS_PENDING)->whereHas('booking', function ($query) {
                $query->where('status', Booking::STATUS_COMPLETED);
            })->orderBy('created_at', 'DESC')->first();


        }
        if ($token == null) {
            $token = new PatientToken();
        }

        $booking = Booking::find($id);
        $patient = $booking->user;

        $patient_detail = PatientDetails::where('booking_id', $id)/*->where(\DB::raw('date(`created_at`)'), date("Y-m-d"))*/
        ->orderBy('created_at', 'DESC')->where('type', PatientDetails::TYPE_ADMISSION)->first();

        if ($patient_detail == null)
            $patient_detail = new PatientDetails();

        return view('laralum.token.patient_details', compact('token', 'patient_detail', 'patient', 'booking'));
    }

    public function getPatientDetails(Request $request, $id)
    {
        $token = PatientToken::where('booking_id', $id)->where("department_id", \Auth::user()->department->department_id)->where('status', PatientToken::STATUS_PENDING)->where('doctor_id', \Auth::user()->id)->whereHas('booking', function ($query) {
            $query->where('status', Booking::STATUS_COMPLETED);
        })->orderBy('created_at', 'DESC')->first();

        if ($token = null) {
            $token = new PatientToken();
        }
        $patient = Booking::find($id);
        /*if ($request->has('patient_id')) {
            $patient_id = $request->get('patient_id');
        }
        */

        if ($request->has('patient_id')) {
            $patient_id = $request->get('patient_id');
            $detail = PatientDetails::where('patient_id', $patient_id)/*->where(\DB::raw('date(`created_at`)'), date("Y-m-d"))*/
            ->orderBy('created_at', 'DESC')->first();
        }
        else{
            $detail = PatientDetails::where('booking_id', $id)/*->where(\DB::raw('date(`created_at`)'), date("Y-m-d"))*/
            ->orderBy('created_at', 'DESC')->where('type', PatientDetails::TYPE_ADMISSION)->first();
        }

        $result = [
            'status' => 'NOK'
        ];

        if ($detail != null) {
            $result['pulse'] = $detail->pulse;
            $result['bp'] = $detail->bp;
            $result['height'] = $detail->height;
            $result['weight'] = $detail->weight;
            $result['blood_group'] = $detail->blood_group;
            $result['bmi'] = $detail->bmi;
            $result['status'] = 'OK';
        }

        return $result;
    }

    public function storePatientDetails(Request $request, $id)
    {

        //return $request->all();
        $booking = Booking::find($id);
        $patient = $booking->user;

        if (!$booking->isEditable())
            return redirect()->back()->with('error', 'Patient is not active.');

        $detail = PatientDetails::where('booking_id', $id)/*->where(\DB::raw('date(`created_at`)'), date("Y-m-d"))*/
        ->orderBy('created_at', 'DESC')->where('type', PatientDetails::TYPE_ADMISSION)->first();

        if ($detail == null) {
            $detail = new PatientDetails();
            $result = [
                'status' => 'NOK'
            ];
        }

        if ($detail->status == PatientDetails::STATUS_PENDING || $detail->id == null) {
           // return $request;
            $detail->setData($request);
            if ($detail->save()) {
                /* if ($detail->pulse != "" && $detail->bp != "" && $detail->height != "" && $detail->weight != "" && $detail->blood_group != "" && $detail->bmi != "") {
                     $detail->update([
                         'status' => PatientDetails::STATUS_COMPLETED
                     ]);
                 }*/

                $token = PatientToken::where('booking_id', $id)->where('doctor_id', \Auth::user()->id)->where("department_id", \Auth::user()->department->department_id)->where('status', PatientToken::STATUS_PENDING)->whereHas('booking', function ($query) {
                    $query->where('status', Booking::STATUS_COMPLETED);
                })->orderBy('created_at', 'DESC')->first();
                if ($token != null) {
                    $token->update([
                        'status' => PatientToken::STATUS_ATTENDED,
                        'doctor_id' => \Auth::user()->id
                    ]);
                }
            }
        }

        $result['id'] = $detail->id;
        $result['pulse'] = $detail->pulse;
        $result['bp'] = $detail->bp;
        $result['height'] = $detail->height;
        $result['weight'] = $detail->weight;
        $result['blood_group'] = $detail->blood_group;
        $result['bmi'] = $detail->bmi;
        $result['details_status'] = $detail->status;
        $result['status'] = 'OK';

        return $result;
    }

    public function vitalData($id)
    {
        if (\Auth::user()->isAdmin()) {
            $token = PatientToken::where('booking_id', $id)->orderBy('created_at', 'DESC')->whereHas('booking', function ($query) {
                $query->where('status', Booking::STATUS_COMPLETED);
            })->first();

        } else {
            $token = PatientToken::where('booking_id', $id)->where("department_id", \Auth::user()->department->department_id)->where('status', PatientToken::STATUS_PENDING)->orderBy('created_at', 'DESC')->whereHas('booking', function ($query) {
                $query->where('status', Booking::STATUS_COMPLETED);
            })->first();
        }
        if ($token == null) {
            $token = new PatientToken();
        }
        $booking = Booking::find($id);
        $patient = $booking->user;

        $vitalData = VitalData::where('booking_id', $id)/*->where('status', VitalData::STATUS_PENDING)*/
        ->orderBy('created_at', "DESC")->first();


        if ($vitalData == null) {
            $vitalData = new VitalData();
            $vitalData->patient_id = $patient->id;
            $vitalData->booking_id = $id;
            $vitalData->setComplaints();
        }

        //return $vitalData;
        $physical = PhysicalExamination::where('booking_id', $id)/*->where('status', PhysicalExamination::STATUS_PENDING)*/
        ->orderBy('created_at', 'DESC')->first();
        if ($physical == null)
            $physical = new PhysicalExamination();

        $respiratory = RespiratoryExamination::where('booking_id', $id)/*->where('status', RespiratoryExamination::STATUS_PENDING)*/
        ->orderBy('created_at', 'DESC')->first();
        if ($respiratory == null)
            $respiratory = new RespiratoryExamination();

        $cardio = CardiovascularExamination::where('booking_id', $id)/*->where('status', CardiovascularExamination::STATUS_PENDING)*/
        ->orderBy('created_at', 'DESC')->first();
        if ($cardio == null)
            $cardio = new CardiovascularExamination();


        $genitorinary = GenitourinaryExamination::where('booking_id', $id)/*->where('status', GenitourinaryExamination::STATUS_PENDING)*/
        ->orderBy('created_at', 'DESC')->first();
        if ($genitorinary == null)
            $genitorinary = new GenitourinaryExamination();


        $gastro = GastrointestinalExamination::where('booking_id', $id)/*->where('status', GastrointestinalExamination::STATUS_PENDING)*/
        ->orderBy('created_at', 'DESC')->first();
        if ($gastro == null)
            $gastro = new GastrointestinalExamination();

        $neuro = NeurologicalExamination::where('booking_id', $id)/*->where('status', NeurologicalExamination::STATUS_PENDING)*/
        ->orderBy('created_at', 'DESC')->first();
        if ($neuro == null)
            $neuro = new NeurologicalExamination();

        $skin = SkinExamination::where('booking_id', $id)
        ->orderBy('created_at', 'DESC')->first();
        if ($skin == null)
            $skin = new SkinExamination();

        $eye = EyeExamination::where('booking_id', $id)
        ->orderBy('created_at', 'DESC')->first();
        if ($eye == null)
            $eye = new EyeExamination();


        $data = [
            'neuro' => $neuro,
            'gastro' => $gastro,
            'genitorinary' => $genitorinary,
            'cardio' => $cardio,
            'respiratory' => $respiratory,
            'physical' => $physical,
            'patient' => $patient,
            'token' => $token,
            'vitalData' => $vitalData,
            'booking' => $booking,
            'skin' => $skin,
            'eye' => $eye
        ];

        return view('laralum.token.vital_data', $data);
        // return view('laralum.token.vital_data', $data);
    }


    public function storeAyurvedVitalData(Request $request, $id)
    {
        // echo '<pre>'; print_r($request->all());exit;
        $booking = Booking::find($id);
        if (!$booking->isEditable())
            return redirect()->back()->with('error', 'Patient is not active.');


        $patient = $booking->user;
        $aturpariksha = AyurvedAturExamination::where('booking_id', $id)->where('status', AyurvedAturExamination::STATUS_PENDING)->first();
        if ($aturpariksha == null)
            $aturpariksha = new AyurvedAturExamination();

        $ashtvidh = AyurvedaAshtvidhExamination::where('booking_id', $id)->where('status', AyurvedAturExamination::STATUS_PENDING)->first();
        if ($ashtvidh == null)
            $ashtvidh = new AyurvedaAshtvidhExamination();

        $doshpariksha = AyurvedDoshExamination::where('booking_id', $id)->where('status', AyurvedAturExamination::STATUS_PENDING)->first();
        if ($doshpariksha == null)
            $doshpariksha = new AyurvedDoshExamination();

        $dhatupariksha = AyurvedDhatuExamination::where('booking_id', $id)->where('status', AyurvedAturExamination::STATUS_PENDING)->first();
        if ($dhatupariksha == null)
            $dhatupariksha = new AyurvedDhatuExamination();

        $aturpariksha->setData($request);
        $aturpariksha->save();
        $ashtvidh->setData($request);
        $ashtvidh->save();
        $doshpariksha->setData($request);
        $doshpariksha->save();
        $dhatupariksha->setData($request);
        $dhatupariksha->save();

        return redirect()->back()->with('success', 'Successfully Saved');
    }

    public function storeVitalData(Request $request, $id)
    {
        $booking = Booking::find($id);
        $patient = $booking->user;
        $token = PatientToken::where('booking_id', $id)->where("department_id", \Auth::user()->department->department_id)->where('status', PatientToken::STATUS_PENDING)->orderBy('created_at', 'DESC')->whereHas('booking', function ($query) {
            $query->where('status', Booking::STATUS_COMPLETED);
        })->first();

        if ($token != null) {
            $vitalData = VitalData::where('token_id', $token->id)->first();
        } else {
            $vitalData = VitalData::where('booking_id', $booking->id)->orderBy('created_at', "DESC")->first();
        }

        if ($vitalData == null) {
            $vitalData = new VitalData();
        }

        if (!$booking->isEditable())
            return redirect()->back()->with('error', 'Patient is not active.');

        if ($request->save_section == 'vital_data') {
            $vitalData->setData($request);
            $vitalData->save();
        }

        $physical = PhysicalExamination::where('booking_id', $id)->where('status', PhysicalExamination::STATUS_PENDING)->orderBy('created_at', 'DESC')->first();
        if ($physical == null)
            $physical = new PhysicalExamination();

        if ($request->save_section == 'general_examination') {
            $physical->setData($request);
            $physical->save();
        }

        $respiratory = RespiratoryExamination::where('booking_id', $id)->where('status', RespiratoryExamination::STATUS_PENDING)->orderBy('created_at', 'DESC')->first();
        if ($respiratory == null)
            $respiratory = new RespiratoryExamination();

        if ($request->save_section == 'respiratory_examination') {
            $respiratory->setData($request);
            $respiratory->save();
        }

        $cardio = CardiovascularExamination::where('booking_id', $id)->where('status', CardiovascularExamination::STATUS_PENDING)->orderBy('created_at', 'DESC')->first();
        if ($cardio == null)
            $cardio = new CardiovascularExamination();

        if ($request->save_section == 'cardio_examination') {
            $cardio->setData($request);
            $cardio->save();
        }

        $genitorinary = GenitourinaryExamination::where('booking_id', $id)->where('status', GenitourinaryExamination::STATUS_PENDING)->orderBy('created_at', 'DESC')->first();
        if ($genitorinary == null)
            $genitorinary = new GenitourinaryExamination();


        if ($request->save_section == 'genitourinary_examination') {
            $genitorinary->setData($request);
            $genitorinary->save();
        }

        $gastro = GastrointestinalExamination::where('booking_id', $id)->where('status', GastrointestinalExamination::STATUS_PENDING)->orderBy('created_at', 'DESC')->first();
        if ($gastro == null)
            $gastro = new GastrointestinalExamination();


        if ($request->save_section == 'gastrointestinal_examination') {
            $gastro->setData($request);
            $gastro->save();
        }

        $neuro = NeurologicalExamination::where('booking_id', $id)->where('status', NeurologicalExamination::STATUS_PENDING)->orderBy('created_at', 'DESC')->first();
        if ($neuro == null)
            $neuro = new NeurologicalExamination();


        if ($request->save_section == 'neurological_examination') {
            $neuro->setData($request);
            $neuro->save();
        }

        $skin = SkinExamination::where('booking_id', $id)->where('status', SkinExamination::STATUS_PENDING)->orderBy('created_at', 'DESC')->first();
        if ($skin == null)
            $skin = new SkinExamination();

        if ($request->save_section == 'skin_examination') {
            $skin->setData($request);
            $skin->save();
        }

        $eye = EyeExamination::where('booking_id', $id)->where('status', EyeExamination::STATUS_PENDING)->orderBy('created_at', 'DESC')->first();
        if ($eye == null)
            $eye = new EyeExamination();

        if ($request->save_section == 'eye_examination') {
            $eye->setData($request);
            $eye->save();
        }


        return redirect()->back()->with('success', 'Successfully Saved');
    }

    public function summary(Request $request, $id)
    {
        $data = $this->_summarydata($id);
        //dd($data['lab_tests']);
        //return $data['pain_assesment']->getValue('type_of_pain');
        return view('laralum.token.summary_details', $data);
    }

    public function printsummary($id)
    {
        $data = $this->_summarydata($id);
        $data['print'] = true;
        return view('laralum.token.print_summary_details', $data);
    }

    public function archived_summary(Request $request, $id)
    {
        $data = $this->_summarydata($id);
        //dd($data['lab_tests']);
        //return $data['pain_assesment']->getValue('type_of_pain');
        return view('laralum.token.archived_summary_details', $data);
    }
    public function print_archived_summary($id)
    {
        $data = $this->_summarydata($id);
        $data['print'] = true;
        $back_url = route('Laralum::archived-summary', $id);
        $data['back_url'] = $back_url;
        return view('laralum.token.print_summary_details', $data);
    }

    public function _summarydata($id)
    {
        $booking = Booking::find($id);
        $patient = $booking->user;
        $attachments = SystemFile::where('model_id', $booking->id)->where('model_type', Booking::class)->where('field_name', 'attachments')->get();

        $treatments = TreatmentToken::where("booking_id", $id)->get();
        $patient_detail = PatientDetails::where('booking_id', $id)->where(\DB::raw('date(`created_at`)'), (string)date("Y-m-d"))->first();
        if ($patient_detail == null) {
            $patient_detail = new PatientDetails();
        }

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


        $skin = SkinExamination::where('booking_id', $id)->where('status', SkinExamination::STATUS_PENDING)->orderBy('created_at', 'DESC')->first();
        if ($skin == null)
            $skin = new SkinExamination();

        $eye = EyeExamination::where('booking_id', $id)->where('status', EyeExamination::STATUS_PENDING)->orderBy('created_at', 'DESC')->first();
        if ($eye == null)
            $eye = new EyeExamination();


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

        $discharge_patient = DischargePatient::where('booking_id', $booking->id)->first();

        $vital_data = PatientDetails::where('booking_id', $booking->id)->where('type', PatientDetails::TYPE_ADMISSION)
            ->orderBY('created_at', 'DESC')->first();

        $discharge_vital = PatientDetails::where('booking_id', $booking->id)->where('type', PatientDetails::TYPE_DISCHARGE)
            ->orderBy('created_at', 'DESC')->first();

        if ($vital_data == null)
            $vital_data = new PatientDetails();
        if ($discharge_vital == null)
            $discharge_vital = new PatientDetails();
        if ($discharge_patient == null)
            $discharge_patient = new DischargePatient();

        $diagnosis = PatientDiagnosis::where([
            'booking_id' => $booking->id,
            'patient_id' => $patient->id,
        ])->first();


        $lab_tests = PatientLabTest::where('booking_id', $booking->id)->get();

        $data = array('booking' => $booking, 'patient' => $patient, 'vitalData' => $vitalData, 'physical' => $physical, 'respiratory' => $respiratory, 'cardio' => $cardio, 'genitorinary' => $genitorinary, 'gastro' => $gastro, 'neuro' => $neuro, 'systemic' => $systemic, 'sensory' => $sensory, 'motor' => $motor, 'pain' => $pain, 'pain_assesment' => $pain_assesment, 'ashtvidh' => $ashtvidh, 'aturpariksha' => $aturpariksha, 'doshpariksha' => $doshpariksha, 'dhatupariksha' => $dhatupariksha, 'patient_detail' => $patient_detail, 'treatments' => $treatments, 'attachments' => $attachments, 'diagnosis' => $diagnosis, 'discharge_patient' => $discharge_patient, 'vital_data' => $vital_data, 'discharge_vital' => $discharge_vital, 'skin'=> $skin, 'eye' => $eye, 'lab_tests' => $lab_tests);

        return $data;
    }

    public function physiotherpyVitalData($id)
    {
        $booking = Booking::find($id);
        $patient = $booking->user;
        $neuro = NeurologicalExamination::where('booking_id', $id)/*->where('status', NeurologicalExamination::STATUS_PENDING)*/
        ->orderBy('created_at', 'DESC')->first();
        if ($neuro == null)
            $neuro = new NeurologicalExamination();
        $sensory = PhysiotherapySensoryExamination::where('booking_id', $id)->orderBy('created_at', 'DESC')->first();
        if ($sensory == null)
            $sensory = new PhysiotherapySensoryExamination();

        $motor = PhysiotherapyMotorExamination::where('booking_id', $id)->orderBy('created_at', 'DESC')->first();
        if ($motor == null)
            $motor = new PhysiotherapyMotorExamination();
        $pain = PhysiotherapyPainExamination::where('booking_id', $id)->orderBy('created_at', 'DESC')->first();
        if ($pain == null)
            $pain = new PhysiotherapyPainExamination();

        $systemic = PhysiotherapySystemicExamination::where('booking_id', $id)->orderBy('created_at', 'DESC')->first();

        if ($systemic == null)
            $systemic = new PhysiotherapySystemicExamination();


        $pain_assesment = PhysiotherapyPainAssesment::where('booking_id', $id)->orderBy('created_at', 'DESC')->first();
        if ($pain_assesment == null)
            $pain_assesment = new PhysiotherapyPainAssesment();

        $skin = SkinExamination::where('booking_id', $id)->where('status', SkinExamination::STATUS_PENDING)->orderBy('created_at', 'DESC')->first();
        if ($skin == null)
            $skin = new SkinExamination();

        $eye = EyeExamination::where('booking_id', $id)->where('status', EyeExamination::STATUS_PENDING)->orderBy('created_at', 'DESC')->first();
        if ($eye == null)
            $eye = new EyeExamination();

        $lab_tests = PatientLabTest::where('status', PatientToken::STATUS_PENDING)->groupBy('booking_id');
        if($lab_tests == null )
            $lab_tests = new PatientLabTest();

        $romjoint = RomJoint::all();

        $data = $this->_summarydata($id);

        $dept_model = \App\Department::where('title', 'like', "%Physiotherapy%")->first();

        return view('laralum.token.physiotherpy_vital_data', compact('dept_model', 'patient', 'motor', 'pain_assesment', 'pain', 'sensory', 'booking', 'neuro', 'systemic', 'romjoint', 'data', 'skin', 'eye', 'lab_tests'));


    }

    public function getJointSubCat(Request $request)
    {
        $subcat = RomSubCategory::where('rom_joint_id', $request->joint_id)->get();
        $html = "<option value=''>Select sub Category</option>";
        foreach ($subcat as $key => $sub) {
            $html .= '<option value=' . $sub->id . ' data-normal-rom=' . $sub->normal_rom . '>' . $sub->sub_category . '</option>';
        }
        return $html;
    }

    public function getHtml(Request $request)
    {
        $html = '<div class="joint_row">
            <div class="input_sm1">
                <label>Joint</label>
                <select name="joint[]" class="select-joint" data-id="' . $request->count . '">
                    <option value="">Select Joint</option>';

        foreach (RomJoint::all() as $joint) {
            $html .= '<option value="' . $joint->id . '">' . $joint->joint_name . '</option>';
        }
        $html .= '</select>
            </div>
            <div class="input_sm1">
                <label>Sub-category</label>
                <select name="subcat[]" class="insert-option insert-option-' . $request->count . '" data-id="' . $request->count . '">
                </select>
            </div>
        </div>

        <div class="joint_row">
            <h4 class="normal-rom normal-rom-' . $request->count . '">Normal ROM:</h4>
            <div class="input_sm1">
                <label>Right Side</label>
                <input type="text" class="form_control add-width" name="right[]">
            </div>
            <div class="input_sm1">
                <label>Left Side</label>
                <input type="text" class="form_control add-width" name="left[]">
            </div>
        </div>';
        return $html;
    }

    public function physiotherpyVitalDataStore(Request $request, $id)
    {
       // dd($request->all());
        $booking = Booking::find($id);
        $patient = $booking->user;
        $neuro = NeurologicalExamination::where('booking_id', $id)/*->where('status', NeurologicalExamination::STATUS_PENDING)*/
        ->orderBy('created_at', 'DESC')->first();
        if ($neuro == null)
            $neuro = new NeurologicalExamination();

        if ($request->save_section == 'neurological_examination') {
            $neuro->setData($request);
            $neuro->save();
        }

        $sensory = PhysiotherapySensoryExamination::where('booking_id', $id)->orderBy('created_at', 'DESC')->first();
        if ($sensory == null)
            $sensory = new PhysiotherapySensoryExamination();

        if ($request->save_section == 'sensory_examination') {
            $sensory->setData($request);
            $sensory->save();
        }


        $motor = PhysiotherapyMotorExamination::where('booking_id', $id)->orderBy('created_at', 'DESC')->first();

        if ($request->save_section == 'motor_examination') {
            if ($motor == null)
                $motor = new PhysiotherapyMotorExamination();

            $motor->setData($request);
            $motor->save();
        }


        $systemic = PhysiotherapySystemicExamination::where('booking_id', $id)->orderBy('created_at', 'DESC')->first();
        if ($systemic == null)
            $systemic = new PhysiotherapySystemicExamination();

        if ($request->save_section == 'systematic_examination') {
            //return $request->body_built;
            $systemic->setData($request);
            $systemic->save();
        }

        $pain = PhysiotherapyPainExamination::where('booking_id', $id)->orderBy('created_at', 'DESC')->first();
        if ($pain == null)
            $pain = new PhysiotherapyPainExamination();

        if ($request->save_section == 'pain_examination') {
            $pain->setData($request);
            $pain->save();
        }

        $pain_assesment = PhysiotherapyPainAssesment::where('booking_id', $id)->orderBy('created_at', 'DESC')->first();
        if ($pain_assesment == null)
            $pain_assesment = new PhysiotherapyPainAssesment();
        if ($request->save_section == 'pain_assessment_examination') {
            $pain_assesment->setData($request);
            $pain_assesment->save();
        }
        return redirect()->back()->with('success', 'Successfully Saved');
    }


    public function diagnosePatient(Request $request, $id)
    {
        $booking = Booking::find($id);
        $patient = $booking->user;
        $model = PatientDiagnosis::where([
            'booking_id' => $booking->id,
            'patient_id' => $patient->id,
            /*'doctor_id' => \Auth::user()->id,*/
            /*'date' => date('Y-m-d')*/
        ])->first();

        if ($model == null) {
            $model = new PatientDiagnosis();
        }

        $diagnosis = PatientDiagnosis::where([
            'booking_id' => $booking->id,
            'patient_id' => $patient->id,
            'doctor_id' => \Auth::user()->id,
        ]);
        $per_page = $request->get("per_page") ? $request->get("per_page") : 10;
        $pagination = true;
        if ($per_page == "All") {
            $pagination = false;
        }
        $count = $diagnosis->count();
        if ($pagination == true) {

            $diagnosis = $diagnosis->paginate($per_page);
        } else {
            $diagnosis = $diagnosis->get();
        }
        return view('laralum.token.diagnosis', compact('patient', 'booking', 'model', 'diagnosis', 'count'));
    }

    public function diagnosePatientStore(Request $request, $id)
    {
        $booking = Booking::find($id);
        if (!$booking->isEditable())
            return redirect()->back()->with('error', 'Patient is not active.');

        $patient = $booking->user;
        $model = PatientDiagnosis::where([
            'booking_id' => $booking->id,
            'patient_id' => $patient->id,
//            'date' => date('Y-m-d'),
          //  'doctor_id' => \Auth::user()->id,
        ])->first();

        if ($model == null) {
            $model = new PatientDiagnosis();
        }

        $validator = \Validator::make($request->all(), $model->rules());

        if ($validator->fails()) {
            return redirect()->back()->with('error', 'Something went wrong');
        }
        if ($model->setData($request)) {
            $model->save();
            return redirect()->back()->with('success', 'Successfully Saved');
        }

        return view('laralum.token.diagnosis', compact('patient', 'booking', 'model'));
    }


    public function deleteDiagnose($id)
    {
        $model = PatientDiagnosis::find($id);
        if (!empty($model)) {
            $model->delete();
            return redirect()->back()->with('success', 'Successfully Deleted');
        }
    }

    public function discharge($id)
    {
        //  Laralum::permissionToAccess('discharge_patients');
        # Get all the data
        $data_index = 'discharge_patients';
        $booking = Booking::find($id);

        $patient = $booking->user;
        $discharge_patient = DischargePatient::where('booking_id', $booking->id)->first();

        $vital_data = PatientDetails::where('booking_id', $booking->id)->where('type', PatientDetails::TYPE_ADMISSION)
            ->orderBY('created_at', 'DESC')->first();

        $discharge_vital = PatientDetails::where('booking_id', $booking->id)->where('type', PatientDetails::TYPE_DISCHARGE)
            ->orderBy('created_at', 'DESC')->first();

        if ($vital_data == null)
            $vital_data = new PatientDetails();
        if ($discharge_vital == null)
            $discharge_vital = new PatientDetails();
        if ($discharge_patient == null)
            $discharge_patient = new DischargePatient();


        $diagnosis = PatientDiagnosis::where([
            'booking_id' => $booking->id,
            'patient_id' => $patient->id,
        ])->first();


        

       // dd($booking->provisional_diagnosis);

        return view('laralum.token.discharge_patient', compact('token', 'vital_data', 'discharge_vital', 'discharge_patient', 'patient', 'summeries', 'avoid_things', 'follow_up_advices', 'booking', 'diagnosis'));
    }

    public function printDischarge($id)
    {
        $booking = Booking::find($id);

        $patient = $booking->user;
        $patient_id = $patient->id;
        $patient_profile = UserProfile::where('user_id',$patient_id)->first();

        $discharge_patient = DischargePatient::where('booking_id', $booking->id)->first();

        $vital_data = PatientDetails::where('booking_id', $booking->id)->where('type', PatientDetails::TYPE_ADMISSION)
            ->orderBY('created_at', 'DESC')->first();

        $discharge_vital = PatientDetails::where('booking_id', $booking->id)->where('type', PatientDetails::TYPE_DISCHARGE)
            ->orderBy('created_at', 'DESC')->first();

        if ($vital_data == null)
            $vital_data = new PatientDetails();
        if ($discharge_vital == null)
            $discharge_vital = new PatientDetails();
        if ($discharge_patient == null)
            $discharge_patient = new DischargePatient();

        $diagnosis = PatientDiagnosis::where([
            'booking_id' => $booking->id,
            'patient_id' => $patient->id,
        ])->first();

        return view('laralum.token.print_discharge_patient', compact('token', 'vital_data', 'discharge_vital', 'discharge_patient', 'patient', 'patient_profile', 'summeries', 'avoid_things', 'follow_up_advices', 'booking', 'diagnosis'));
    }

    public function dischargeStore(Request $request, $id)
    {
        $booking = Booking::find($id);
        /* if (!$booking->isEditable())
             return redirect()->back()->with('error', 'Patient is not active.');*/

        $patient = $booking->user;
        $discharge = DischargePatient::where('booking_id', $booking->id)->first();

        if ($discharge == null) {
            $discharge = new DischargePatient();
        }

        $discharge->setData($request);
        $discharge->booking_id = $booking->id;

        if ($discharge->save()) {
            $followup_date = "";
            if ($request->get("followup_date") != null && $request->get("followup_date") > date("Y-m-d")) {
                $followup_date = date("Y-m-d", strtotime($request->get("followup_date")));
            }
            $discharge->saveFollowup($request->get('followup_days'), $followup_date);
            $discharge->saveVitalData($request);

            $department_discharge = DepartmentDischargeBooking::where('booking_id', $booking->id)->where('department_id', \Auth::user()->department->department_id)->first();

            if ($department_discharge == null) {
                $department_discharge = new DepartmentDischargeBooking();
            }
            $department_discharge->department_id = \Auth::user()->department->department_id;
            $department_discharge->booking_id = $booking->id;
            $department_discharge->summary = $request->get('summary');
            $department_discharge->things_to_avoid = $request->get('things_to_avoid');
            $department_discharge->follow_up_advice = $request->get('follow_up_advice');
            $department_discharge->save();

            $dept_model = \App\Department::where('title', 'like', "%Physiotherapy%")->first();
            return redirect()->back()->with('success', 'Successfully Saved');
        }

        return redirect()->back()->with('error', 'Something went wrong');
    }


    public function assign($id)
    {


        $booking = Booking::find($id);


        $patient = $booking->user;
        $exercises = PhysiotherapyExercise::all();

        $models = ApplyRecommendExcercise::where('booking_id', $booking->id)->where('doctor_id', \Auth::user()->id)->get();
        return view('laralum.physiotherpy_exercises.assign_exercises', compact('booking', 'models', 'exercises'));

    }


    public function recommendByAjax(Request $request)
    {

        $data = [];
        $data['status'] = 'NOK';
        $data['bookingId'] = $request->get('booking_id');
        $data['patient_id'] = $request->get('patient_id');
        $data['exercise_id'] = $request->get('id');
        $model = ApplyRecommendExcercise::where(['physiotherpy_exercise_id' => $data['exercise_id']])->first();
        if ($model == null) {
            $model = new ApplyRecommendExcercise();
        }
        $model->setData($request);
        if ($model->save()) {
            $data['status'] = 'OK';
        }
        return $data;
    }

    public function printExercise($id = null)
    {
        Laralum::permissionToAccess('admin.recommend-exercise.print');
        $exercise = PhysiotherapyExercise::find($id);
        return view('laralum.token.recommend-exercise-print', compact('exercise'));
    }

    public function patientDietChart($id, Request $request)
    {
        $diet = new DietChart();
        $booking = Booking::find($id);
        $patient = $booking->user;

        $diets = DietChart::where("booking_id", $id)/*->where("status", DietChart::STATUS_PENDING)*/
        ->get();
        $data = [];
        /*echo '<pre>'; print_r($diets);exit;*/
        foreach ($diets as $diet_chart) {
            $date = $diet_chart->start_date;
            $date = date("d-m-Y", strtotime($date));
            $diet_item_chart = $diet_chart->getItemArray();
            $data[$date] = $diet_item_chart;
        }
        /*$data = range(1,100);*/
        /*$data = Settings::paginate($data, 10);*/
        $perPage = $request->get("per_page") ? $request->get("per_page") : 10;
        $data_count = count($data);


        if ($perPage == "All") {
            $perPage = count($data);
        }
        $pageStart = \Request::get('page', 1);

        // Start displaying items from this number;
        $offSet = ($pageStart * $perPage) - $perPage;

        // Get only the items you need using array_slice
        $itemsForCurrentPage = array_slice($data, $offSet, $perPage, true);
        $data = new LengthAwarePaginator($itemsForCurrentPage, count($data), $perPage, Paginator::resolveCurrentPage(), array('path' => Paginator::resolveCurrentPath()));/*
        $data = new Paginator($itemsForCurrentPage, count($data),  $request->get('page'));
        $data->setPath(Paginator::resolveCurrentPath());*/

        return view('laralum.token.patient_diet_chart', compact('diet', 'patient', 'token', 'data', 'data_count', 'booking'));

    }

    public function editDietChart($id)
    {
        $diet = DietChart::find($id);

        if (!DietChart::isEditable($id, $diet->start_date))
            return redirect()->back()->with('error', 'Patient is not active.');

        $patient = $diet->patient;
        $booking = $diet->booking;
        return view('laralum.token.patient_diet_chart_edit_form', compact('diet', 'patient', 'booking'));
    }

    public function patientDietForm($id)
    {
        $diet = new DietChart();
        $booking = Booking::find($id);

        if (!$booking->isEditable())
            return redirect()->back()->with('error', 'Patient is not active.');

        $patient = $booking->user;
        return view('laralum.token.patient_diet_chart_form', compact('diet', 'patient', 'token', 'booking'));
    }


    public function patientDietStore(Request $request, $id)
    {
        $diet = new DietChart();
        $booking = Booking::find($id);
        if (!$booking->isEditable())
            return redirect()->back()->with('error', 'Patient is not active.');
        $repeats = $request->get('repeats') ? $request->get('repeats') : 1;
        /*echo '<pre>'; print_r($request->all());exit;*/
        $validator = \Validator::make($request->all(), $diet->getRules());

        if ($validator->fails()) {
            return redirect()->back()->with('error', 'Something went wrong');
        }

        $date = (string)date("Y-m-d", strtotime($request->get("start_date")));

        $prev_diets = DietChart::where("booking_id", $id)->where("start_date", (string)$date)->orderBY("created_at", "DESC")->get();

        foreach ($prev_diets as $prev_diet) {
            $count = Settings::noOfDays($date, $prev_diet->end_date);

            $prev_diet->update([
                'end_date' => $date,
                'repeats' => $count
            ]);
        }


        for ($i = 0; $i < $repeats; $i++) {
            $date = $request->get('start_date');
            $date = date("Y-m-d", strtotime($date . " +" . $i . ' days'));
            $diet = DietChart::where("booking_id", $id)->where("start_date", (string)$date)->orderBY("created_at", "DESC")->first();

            if ($diet == null)
                $diet = new DietChart();

            if ($diet->setData($request)) {
                $diet->start_date = $date;
                $diet->end_date = $date;

                if ($diet->save()) {
                    $diet->saveItems($request);
                }
            }
        }

        return redirect("admin/patient-diet-chart/" . $id)->with('success', 'Successfully Added diet');
    }

    public function allotTreatment($id)
    {
        $token = new TreatmentToken();
        $booking = Booking::find($id);

        if (!$booking->isEditable())
            return redirect('admin/patient/' . $id . '/treatment_history')->with("error", "Patient is not active.");

        $patient = $booking->user;

        $patient_detail = PatientDetails::where('booking_id', $id)->where(\DB::raw('date(`created_at`)'), (string)date("Y-m-d"))->first();
        if ($patient_detail == null) {
            $patient_detail = new PatientDetails();
        }
        return view('laralum.token.patient_treatment_form', compact('patient', 'token', 'booking', 'patient_detail'));
    }

    public function allotTreatmentStore(Request $request, $id)
    {
        //return "heresaving";
        $token = new TreatmentToken();
        $booking = Booking::find($id);

        if (!$booking->isEditable())
            return redirect()->back()->with('error', 'Patient is not active.');

        $rules = TreatmentToken::getRules(true);

        $validator = \Validator::make($request->all(), $rules);
        $check_ids = array_filter($request->get('ids'));

        if ($validator->fails() || !$check_ids) {
            return redirect()->back()->withErrors($validator)->withInput()->with(['error' => 'Please check the details and try again.']);
        }
        $token = TreatmentToken::where("booking_id", $booking->id)->where('department_id', \Auth::user()->department->department_id)->where('status', TreatmentToken::STATUS_PENDING)->where('treatment_date', $request->get('treatment_date'))->first();

        if ($token == null) {
            $token = new TreatmentToken();
        }
        if ($token->setData($request)) {
            $token->save();
            $token->saveTreatments($request);

            /*$patient_detail = PatientDetails::where('booking_id', $id)->where(\DB::raw('date(`created_at`)'), (string)date("Y-m-d"))->first();

            if ($patient_detail == null) {
                $patient_detail = new PatientDetails();
                //if ($patient_detail->checkRequest($request)) {
                $patient_detail->setData($request);
                $patient_detail->save();
                //}
            }*/

            $d = new DNS1D();
            $barcode = $d->getBarcodePNG($token->token_no, "C39+");
            return view('laralum.token.print_treatment_token', compact('token', 'barcode', 'booking'));
        }

        return redirect()->back()->withErrors($validator)->withInput()->with(['error' => 'Please check the details and try again.']);
    }

    public function printTreatment($id)
    {
        $token = TreatmentToken::find($id);
        $booking = $token->booking;
        if (!$booking->isEditable())
            return redirect()->back()->with('error', 'Patient is not active.');
        $d = new DNS1D();
        $barcode = $d->getBarcodePNG($token->token_no, "C39+");

        $back_url = \Auth::user()->isDoctor() ? url('/admin/patient/' . $booking->id . '/treatment_history') : url('/admin/treatment/tokens');

        return view('laralum.token.print_treatment_token', compact('token', 'barcode', 'booking', 'back_url'));
    }

    public function printLabTest($id)
    {
        $lab_test = PatientLabTest::find($id);
        $booking = $lab_test->booking;

        return view('laralum.token.print_lab_test', compact('lab_test', 'barcode', 'booking', 'back_url'));
    }





    public function editTreatment($id)
    {
        $treatment_token = TreatmentToken::find($id);

        if (!$treatment_token->isEditable())
            return redirect()->back()->with('error', 'Patient is not active.');

        $patient = $treatment_token->patient;
        $booking = $treatment_token->booking;
        $patient_detail = PatientDetails::where('booking_id', $booking->id)->where(\DB::raw('date(`created_at`)'), (string)date("Y-m-d", strtotime($treatment_token->created_at)))->first();
        if ($patient_detail == null) {
            $patient_detail = new PatientDetails();
        }
        return view('laralum.token.edit_treatment', compact('treatment_token', 'patient', 'patient_detail', 'booking'));
    }


    public function updateTreatment(Request $request, $id)
    {
        $token = TreatmentToken::find($id);
        if (!$token->isEditable())
            return redirect()->back()->with('error', 'Patient is not active.');
        $rules = TreatmentToken::getRules(true);
        $patient = $token->patient;
        $booking = $token->booking;
        $validator = \Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput()->with(['error' => 'Please check the details and try again.']);
        }
        if ($token->setData($request)) {
            $token->save();
            $token->saveTreatments($request);

            $d = new DNS1D();
            $barcode = $d->getBarcodePNG($token->token_no, "C39+");
            return view('laralum.token.print_treatment_token', compact('token', 'barcode', 'booking'));
        }
        return redirect()->back()->withErrors($validator)->withInput()->with(['error' => 'Please check the details and try again.']);
    }

    public function ayurvedVitalData($id)
    {
        $booking = Booking::find($id);
        $patient = $booking->user;
        $aturpariksha = AyurvedAturExamination::where('booking_id', $id)/*->where('status', AyurvedAturExamination::STATUS_PENDING)*/
        ->first();
        if ($aturpariksha == null)
            $aturpariksha = new AyurvedAturExamination();

        $vital_data_height = "";

        $vitalData = PatientDetails::where('booking_id', $id)->orderBy('created_at', "DESC")->first();
        if ($vitalData) {
            $vital_data_height = $vitalData->height;
        }
        $aturpariksha->praman = $aturpariksha->praman != null ? $aturpariksha->praman : $vital_data_height;

        $ashtvidh = AyurvedaAshtvidhExamination::where('booking_id', $id)/*->where('status', AyurvedAturExamination::STATUS_PENDING)*/
        ->first();
        if ($ashtvidh == null)
            $ashtvidh = new AyurvedaAshtvidhExamination();

        $doshpariksha = AyurvedDoshExamination::where('booking_id', $id)/*->where('status', AyurvedAturExamination::STATUS_PENDING)*/
        ->first();
        if ($doshpariksha == null)
            $doshpariksha = new AyurvedDoshExamination();

        $dhatupariksha = AyurvedDhatuExamination::where('booking_id', $id)/*->where('status', AyurvedAturExamination::STATUS_PENDING)*/
        ->first();
        if ($dhatupariksha == null)
            $dhatupariksha = new AyurvedDhatuExamination();

        $patient_details = PatientDetails::where('booking_id', $id)
        ->orderBy('created_at', 'DESC')->where('type', PatientDetails::TYPE_ADMISSION)->first();

        //dd($aturpariksha);

        return view('laralum.token.ayurved_vital_data', compact('patient', 'token', 'aturpariksha', 'ashtvidh', 'doshpariksha', 'dhatupariksha', 'booking','patient_details'));
    }

    public function treatmentHistory($id)
    {
        $dept = Auth::User()->department()->first();

        // if ($dept == null) {
        $treatments = TreatmentToken::where("booking_id", $id)->orderBy("treatment_date", "ASC")->get();
        /*} else {
            $treatments = TreatmentToken::where([["booking_id", $id], ['department_id', $dept->department_id]])->get();
        }*/
        $booking = Booking::find($id);
        $patient = $booking->user;

        return view("laralum.token.patient_treatment_history", compact("treatments", "patient", "booking"));

    }

    public function savePatientDetails(Request $request, $id)
    {
        $patient_details = PatientDetails::where("booking_id", $id)->orderBy("created_at", "DESC")->first();
        $new_patient_details = new PatientDetails();
        $booking = Booking::find($id);

        if (!$booking->isEditable())
            return redirect()->back()->with('error', 'Patient is not active.');

        if ($request->get("bp") != null && $request->get("pulse") != null && $request->get("weight") == null) {
            $new_patient_details->bp = $request->get("bp");
            $new_patient_details->pulse = $request->get("pulse");
            $new_patient_details->weight = isset($patient_details->weight) ? $patient_details->weight : "";

        } elseif ($request->get("bp") == null && $request->get("pulse") == null && $request->get("weight") != null) {
            $new_patient_details->weight = $request->get("weight");
            $new_patient_details->bp = isset($patient_details->bp) ? $patient_details->bp : "";
            $new_patient_details->pulse = isset($patient_details->pulse) ? $patient_details->pulse : "";
        }

        $new_patient_details->patient_id = $booking->user_id;
        $new_patient_details->booking_id = $id;
        $new_patient_details->created_by = \Auth::user()->id;
        $new_patient_details->type = PatientDetails::TYPE_ADMISSION;
        $new_patient_details->save();
        return "success";
    }

    public function updatePatientTreatment(Request $request, $id)
    {
        $pat_treatment = PatientTreatment::find($id);

        if (!$pat_treatment->treatmentToken->isEditable())
            return redirect()->back()->with('error', 'Patient is not active.');

        if ($pat_treatment != null) {
            if ($request->get("ratings")) {
                $pat_treatment->ratings = $request->get("ratings");
            }
            $pat_treatment->save();
        }
        return ['id' => $id];
    }

    public function updateTreatmentToken(Request $request, $id)
    {
        $treatment_token = TreatmentToken::find($id);

        if (!$treatment_token->isEditable())
            return redirect()->back()->with('error', 'Patient is not active.');

        if ($treatment_token != null) {

            if ($request->get("feedback")) {
                $treatment_token->feedback = $request->get("feedback");
            }
            if ($request->get("doctor_remark")) {
                $treatment_token->doctor_remark = $request->get("doctor_remark");
            }
            if ($request->get("status") !== null) {
                $treatment_token->update([
                    'status' => $request->get("status")
                ]);
            }

            $treatment_token->save();
        }
        return ['id' => $id, 'status' => $treatment_token->status];
    }

    public function deleteTreatmentToken(Request $request, $id)
    {
        $treatment_token = TreatmentToken::find($id);
        if ($treatment_token->treatment_date >= (string)date("Y-m-d") && $treatment_token->status == TreatmentToken::STATUS_PENDING) {
            if ($treatment_token != null) {
                $patient_id = $treatment_token->patient_id;
                $booking_id = $treatment_token->booking_id;
                $treatment_token->deleteToken();

                return redirect()->route('Laralum::patient.treatment_history', ['booking_id' => $booking_id])->with('success', 'Successfully Deleted');
            }
        }
        return redirect()->back()->with('error', 'Something went wrong!!!');
    }

    public function listLabTest($id)
    {
        $lab_tests = PatientLabTest::where('booking_id', $id)/*->where('status', PatientLabTest::STATUS_PENDING)*/
        ->get();
        $booking = Booking::find($id);
        $patient = $booking->user;

        return view('laralum.token.patient_lab_test_list', compact('lab_tests', 'patient', 'booking'));
    }

    public function addLabTest($id)
    {
        $lab_test = new PatientLabTest();
        $booking = Booking::find($id);

        if (!$booking->isEditable())
            return redirect()->back()->with('error', 'Patient is not active.');

        $patient = $booking->user;
        return view('laralum.token.patient_lab_test_form', compact('lab_test', 'patient', 'booking'));
    }

    /*public function storeLabTest(Request $request, $id)
    {
        $lab_test = new PatientLabTest();

        $booking = Booking::find($id);
        if (!$booking->isEditable())
            return redirect()->back()->with('error', 'Patient is not active.');

        $rules = PatientLabTest::getRules(true);
        $test_ids = $request->get('test_id');

        if ($test_ids) {
            $test_ids = is_array($test_ids) ? implode(',', $test_ids) : $test_ids;
        }

        if ($request->get('lab_test_name')) {
            $lab_test_id = PatientLabTest::getTestId($request->get('lab_test_name'));
            $test_ids = $lab_test_id . ',' . $lab_test_id;
        }

        if (!$test_ids) {
            $rules['test_id'] = 'required';
        }

        $validator = \Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput()->with(['error' => 'Please check the details and try again.']);
        }

        if ($lab_test->setData($request)) {

            $lab_test->save();
            return redirect()->route('Laralum::patient_lab_test.index', ['patient_id' => $id])->with('success', 'Successfully Added');
        }
        return redirect()->back()->with('error', 'Something went wrong!!!');
    }*/


    public function storeLabTest(Request $request, $id)
    {

       // return $request->all()
        $booking = Booking::find($id);
        if (!$booking->isEditable())
            return redirect()->back()->with('error', 'Patient is not active.');

        $rules = PatientLabTest::getRules(true);
        $test_ids = $request->get('test_id');

        if ($request->get('lab_test_name')) {
            $lab_test_id = PatientLabTest::getTestId($request->get('lab_test_name'));
            $test_ids[] = $lab_test_id;
        }

        if (!$test_ids) {
            $rules['test_id'] = 'required';
        }

        $validator = \Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput()->with(['error' => 'Please check the details and try again.']);
        }

        foreach($test_ids as $test_id){
            $lab_test = new PatientLabTest();
            if ($lab_test->setData($request,$test_id)) {
                $lab_test->save();
                $save[] = "done";
            }
            else{
                $save[] = "fail";
            }
        }

        if(in_array('fail', $save)){
            return redirect()->route('Laralum::patient_lab_test.index', ['patient_id' => $id])->with('error', 'Something went wrong!!!');
        }
        else{
            return redirect()->route('Laralum::patient_lab_test.index', ['patient_id' => $id])->with('success', 'Successfully Added');
        }
        
       
    }

    public function editLabTest($id)
    {
        $lab_test = PatientLabTest::find($id);
        $booking = Booking::where("id", $lab_test->booking_id)->first();
        if (!$booking->isEditable())
            return redirect()->back()->with('error', 'Patient is not active.');
        $patient = $lab_test->patient;
        return view('laralum.token.patient_lab_test_form', compact('lab_test', 'patient', 'booking'));
    }

    public function updateLabTest(Request $request, $id)
    {
        $lab_test = PatientLabTest::find($id);

        /*if (!($lab_test->date < date("Y-m-d") && $lab_test->status == PatientLabTest::STATUS_DISCHARGED))
            return redirect()->back()->with('error', 'Patient is not active.');*/

        $rules = PatientLabTest::getRules(true);
        if (!$request->get("lab_test_name")) {
            $rules['test_id'] = 'required';
        } else {
            $rules['lab_test_name'] = 'required';
        }
        $validator = \Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput()->with(['error' => 'Please check the details and try again.']);
        }
        if ($lab_test != null) {
            $test_ids = $request->get('test_id');
            $i = 1;
            foreach($test_ids as $test_id){
                if($i > 1){
                    $lab_test = new PatientLabTest();
                }
                $lab_test->setData($request,$test_id);
                $lab_test->save();
                $i++;
            }

           /* $lab_test->setData($request);
            $lab_test->save();*/
            return redirect()->route('Laralum::patient_lab_test.index', ['patient_id' => $lab_test->booking_id])->with('success', 'Successfully Saved');

        }
        return redirect()->back()->with('error', 'Something went wrong!!!');
    }

    public function deleteLabTest($id)
    {
        $test = PatientLabTest::find($id);
        $pat_id = $test->booking_id;

      //  if ($test->booking->status == Booking::STATUS_COMPLETED) {
            $test->delete();
            return redirect()->route('Laralum::patient_lab_test.index', ['patient_id' => $pat_id])->with('success', 'Successfully Deleted');
       // }

        return redirect()->back()->with('error', 'Can not delete lab test');
    }

    public function deleteDietChart($id)
    {
        $diet_chart = DietChart::find($id);
        $pat_id = $diet_chart->booking_id;
        if ($diet_chart->start_date >= (string)date("Y-m-d")) {
            $diet_chart->deletePreviousItems();
            $diet_chart->delete();
            return redirect("admin/patient-diet-chart/" . $pat_id)->with('success', 'Successfully Deleted diet');
        }

        return redirect()->back()->with('error', 'Can not delete past diet charts!!!');
    }

    public function attachments(Request $request, $id)
    {
        $booking = Booking::find($id);
        $attachments = SystemFile::where('model_id', $booking->id)->where('model_type', Booking::class)->where('field_name', 'attachments')->get();


        return view('laralum.token.attachments', compact('booking', 'attachments'));
    }

    public function attachmentStore(Request $request, $id)
    {
        $booking = Booking::find($id);

        $attachment_count = SystemFile::where('model_id', $booking->id)->where('model_type', Booking::class)->where('field_name', 'attachments')->count();

        $attachment_size = SystemFile::where('model_id', $booking->id)->where('model_type', Booking::class)->where('field_name', 'attachments')->sum('file_size');

        $file_size = number_format($attachment_size / 1048576, 2);

        if ($attachment_count < 10) {

            //uncomment to put file size limit

            /*if ($file_size < 20) {*/
            if ($request->file('attachments')) {
                SystemFile::saveUploadedFile($request->file('attachments'), $booking, 'attachments', true);
            }
            /*}else{
                return redirect()->back()->with('error', 'Maximum of 20Mb can be uploaded.');
            }*/
        } else {
            return redirect()->back()->with('error', '10 attachments can be uploaded.');
        }

        return redirect()->back()->with('success', 'Successfully Uploaded.');
    }

    public function sendEmail(Request $request, $id)
    {
        $validator = \Validator::make($request->all(), [
            'email' => 'required|email',
            'ids' => 'required'
        ], [
            'ids.required' => 'Please select file to be sent'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors());
        }

        $booking = Booking::find($id);
        $ids = is_array($request->ids) ? $request->ids : explode(',', $request->ids);

        if (count($ids) <= 10) {
            $attachments_size = SystemFile::whereIn('id', $ids)->where('model_id', $booking->id)->where('model_type', Booking::class)->where('field_name', 'attachments')->sum('file_size');

            $file_size = number_format($attachments_size / 1048576, 2);


            if ($file_size < 20) {
                $attachments = SystemFile::whereIn('id', $ids)->where('model_id', $booking->id)->where('model_type', Booking::class)->where('field_name', 'attachments')->pluck('disk_name', 'id')->toArray();

                $files = [];
                foreach ($attachments as $attachment) {
                    $files[] = storage_path() . '/app/' . $attachment;
                }

                $email = $request->email;

                Mail::send('email.attachments', ['name' => $booking->user->name], function ($message) use ($email, $files) {
                    $message->from(env("USER_EMAIL"), 'Kayakalp');
                    $message->subject('Attachments');
                    $message->to($email);
                    foreach ($files as $file) {
                        $message->attach($file);
                    }
                });
                return redirect()->back()->with('success', 'Successfully sent.');
            } else {
                return redirect()->back()->with('error', 'Maximum of 20Mb can be sent in mail.');
            }
        } else {
            return redirect()->back()->with('error', 'Maximum of 10 files can be sent in mail.');
        }

        return redirect()->back()->with('error', 'Something went wrong.');
    }

    public function sendEmailSummary(Request $request, $id)
    {
        $email = $request->send_to_email;

        if ($email) {
            $data = $this->_summarydata($id);
            $data['print'] = true;
            $html = view('laralum.token.print_summary_details_pdf', $data)->render();
            $path = storage_path() . '/app/uploads/summary_' . time() . '.pdf';


            /*$conv = new \Anam\PhantomMagick\Converter();
            $conv->addPage($html)
                ->save($path);*/
            $pdf = SnappyPDF::loadView('laralum.token.print_summary_details_pdf', $data);
            $pdf->save($path);

            $booking = Booking::find($id);


            Mail::send('email.summary', ['name' => $booking->user->name], function ($message) use ($email, $path) {
                $message->from(env("USER_EMAIL"), 'Kayakalp');
                $message->subject('Summary');
                $message->to($email);
                $message->attach($path);
            });

            @unlink($path);
            return redirect()->back()->with('success', 'Successfully send data in email to email id: ' . $request->send_to_email);
        }

        return redirect()->back()->with('error', 'Something went wrong.');
    }

    public function attachmentDestroy(Request $request, $id)
    {
        $attachment = SystemFile::where('id', $id)->where('uploaded_by', \Auth::user()->id)->first();

        if ($attachment) {
            $attachment->customDelete();
            return redirect()->back()->with('success', 'Successfully Deleted.');
        }

        return redirect()->back()->with('error', 'You can not delete this attachment.');
    }


    public function lab_tests_patients(Request $request){
        //return $request->all();
        $matchThese = [];
        $usermatchThese = [];
        $others = [];
        $filter_name = "";
        $search = false;
        $option_ar = [];
        $matchName = [];
        $searching = [];

        if (!empty($request->get('kid'))) {
            $option_ar[] = "Patient Id";
            $search = true;
            $matchThese['kid'] = $request->get('kid');
            $searching['kid'] = $request->get('kid');
        }
        if (!empty($request->get('contact'))) {
            $option_ar[] = "Contact";
            $search = true;
            $matchThese['mobile'] = $request->get('contact');
            $searching['contact'] = $request->get('contact');
        }

        if (!empty($request->get('uhid'))) {
            $option_ar[] = "UHID";
            $search = true;
            $usermatchThese['uhid'] = $request->get('uhid');
            $searching['uhid'] = $request->get('uhid');
        }

        if (!empty($request->get('name'))) {
            $option_ar[] = "Name";
            $search = true;

            $array = explode(' ', $request->get('name'));

            $matchName['first_name'] = $array[0];
            $matchName['last_name'] = '';

            if (isset($array[1])) {
                $matchName['last_name'] = $array[1];
            }
            $searching['name'] = $request->get('name');
        }

        //return $matchName;

        $data = array();
        $per_page = $request->get("per_page") ? $request->get("per_page") : 10;
        $pagination = true;
        if ($per_page == "All") {
            $pagination = false;
        }


        $lab_tests = PatientLabTest::where('status', PatientToken::STATUS_PENDING)->groupBy('booking_id')->orderBy("id", "DESC");

        if ($search == true) {
            if($request->get('name') == '' && $request->get('contact') == '' && $request->get('kid') == '' && $request->get('uhid') == ''){
                $lab_tests = PatientLabTest::where('status', PatientToken::STATUS_PENDING)->groupBy('booking_id');
            }
            else{
                $lab_tests = PatientLabTest::select('patient_lab_tests.*')->join('users', 'users.id', '=', 'patient_lab_tests.patient_id')->join('user_profiles', 'user_profiles.user_id', '=', 'patient_lab_tests.patient_id')->where(function ($query) use ($matchThese,  $usermatchThese, $matchName, $filter_name) {
                    foreach ($matchThese as $key => $match) {
                        $query->where('user_profiles.' . $key, 'like', "%$match%");
                    }
                    foreach ($matchName as $key => $match) {
                        $query->where('user_profiles.' . $key, 'like', "%$match%");
                    }
                    foreach ($usermatchThese as $key => $match) {
                        $query->where('users.' . $key, 'like', "%$match%");
                    }
                })->where('patient_lab_tests.status', PatientToken::STATUS_PENDING)->groupBy('patient_lab_tests.booking_id');
            }
           
        }
        

        if ($pagination == true) {
            $lab_tests = $lab_tests->paginate($per_page);
        } else {
            $lab_tests = $lab_tests->get();
        }
        if($lab_tests){
            $i = 0;
            foreach($lab_tests as $lab_test){
                //return $
                $booking_id = $lab_test->booking_id;
                $booking = Booking::find($booking_id);
                $patient = $booking->user;
                $patient_id = $patient->id;
                $patient_profile = UserProfile::where('user_id',$patient_id)->orderBy("id", "DESC")->first();
                // $data[$i]['lab_test'] = $lab_test;
                $data[$i]['booking'] = $booking;
                $data[$i]['patient'] = $patient;
                $data[$i]['patient_profile'] = $patient_profile;
                $i++;
            }    
        }





        if($search == true){
            /*$data = array_merge($matchThese, $usermatchThese, $matchName);

            dd($data);*/
            return [
                'html' => view('laralum/lab/_list', ['lab_tests' => $lab_tests, 'data'=>$data,  'search' => $search, 'search_data' => $searching])->render()
            ];
        }
        else{
            return view('laralum.lab.index', compact('data','lab_tests'));
        }
        
        //return view('laralum.lab.index', compact('data','lab_tests'));
        
    }

    public function lab_patient_details(Request $request, $booking_id){
        //return $booking_id;
        $user = Auth::user();

        $token = PatientToken::where('booking_id', $booking_id)->where('status', PatientToken::STATUS_PENDING)->orderBy('created_at', 'DESC')->whereHas('booking', function ($query) {
                $query->where('status', Booking::STATUS_COMPLETED);
            })->first();


        
        if ($token == null) {
            $token = new PatientToken();
        }

        $booking = Booking::find($booking_id);
        $patient = $booking->user;

        $patient_detail = PatientDetails::where('booking_id', $booking_id)/*->where(\DB::raw('date(`created_at`)'), date("Y-m-d"))*/
        ->orderBy('created_at', 'DESC')->where('type', PatientDetails::TYPE_ADMISSION)->first();

        if ($patient_detail == null)
            $patient_detail = new PatientDetails();

        return view('laralum.lab.patient_details', compact('token', 'patient_detail', 'patient', 'booking'));
    }

    public function lab_test_details(Request $request, $booking_id){
        //return $booking_id;
        $lab_tests = PatientLabTest::where('booking_id', $booking_id)
        ->get();
        $booking = Booking::find($booking_id);
        $patient = $booking->user;

        return view('laralum.lab.patient_lab_test_list', compact('lab_tests', 'patient', 'booking'));
    }

    public function lab_test_report(Request $request, $test_id = null){
        if($test_id != null){
            $lab_test = PatientLabTest::where('id', $test_id)->first();
            $booking_id = $lab_test->booking_id;
            $booking = Booking::find($booking_id);
            $patient = $booking->user;
            return view('laralum.lab.patient_lab_test_report',compact('lab_test', 'patient', 'booking'));
        }
        else{
            if ($request->hasFile('lab_report')) {
                $extension = Input::file('lab_report')->getClientOriginalExtension();
                $this_lab_test = PatientLabTest::where('id', $request->id)->first();
                $this_lab_test->lab_report = Settings::saveUploadedFile($request->file('lab_report'), $this_lab_test->lab_report);
                $this_lab_test->test_status = 1;
                $this_lab_test->report_type = $extension;
                $this_lab_test->save();
                $lab_test = PatientLabTest::where('id', $request->id)->first();
                $booking_id = $lab_test->booking_id;
                $booking = Booking::find($booking_id);
                $patient = $booking->user;
                return redirect()->route('Laralum::patient.lab-details', ['booking_id' => $booking_id])->with('success', 'Report Uploaded');
            }
            else{
                $lab_test = PatientLabTest::where('id', $request->id)->first();
                $booking_id = $lab_test->booking_id;
                $booking = Booking::find($booking_id);
                $patient = $booking->user;
                return redirect()->route('Laralum::patient.lab-details', ['booking_id' => $booking_id])->with('error', 'Something went wrong!!!');
            }        
        }
    }

    public function download_report($test_id = null){
        if($test_id != null){
            $lab_test = PatientLabTest::where('id', $test_id)->first(); 
            $file = $lab_test->lab_report;
            $path = storage_path() . '/app/'.$file;
            $type = $lab_test->report_type;
            /*$headers = array(
                        'Content-Type: application/pdf',
                    );*/

            return Response::download($path, 'reportfile.'.$type);
           //return redirect()->back();
        
        }
    }   

}

