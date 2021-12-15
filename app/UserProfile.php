<?php

namespace App;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\User;

if (version_compare(PHP_VERSION, '7.2.0', '>=')) {
    // Ignores notices and reports all other kinds... and warnings
    error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);
    // error_reporting(E_ALL ^ E_WARNING); // Maybe this is enough
}

class UserProfile extends Model
{
    use SoftDeletes;

    const GENDER_FEMALE = 1;
    const GENDER_MALE = 2;
    const GENDER_NOT_SPECIFIED = 0;

    const UNMARRIED = 1;
    const MARRIED = 2;

    const PATIENT_TYPE_IPD = 1;
    const PATIENT_TYPE_OPD = 2;
    
    protected $fillable = [
        'first_name',
        'last_name',
        'about',
        'gender',
        'dob',
        'location',
        'mobile',
        'office',
        'designation',
        'facebook_url',
        'twitter_url',
        'linkedin_url',
        'google_plus_url',
        'instagram_url',
        'youtube_url',
        'pinterest_url',
        'user_id',
        'patient_type',
        'age',
        'relative_name',
        'profession_id',
        'marital_status',
        'country_code',
        'landline_number',
        'whatsapp_number',
        'health_issues',
        'kid',
        'barcode',
        'booking_id',
        'uhid'
    ];

    protected $table = 'user_profiles';

    public function setData($data, $id = null) {

        if(!is_object($data)) {
            $data = new Collection($data);
        }

      
        $this->gender = $data->get('gender');
        $this->dob = $data->get('dob') != null ? $data->get('dob') : null;

        if(\Auth::check()) {
            if($id == null) {
                $id = \Auth::user()->id;
            }
        }

        $this->user_id = $id;
        $this->about = $data->get('about');
        $this->dob = date("Y-m-d", strtotime($data->get('dob')));
        //$this->health_issues = $data->get('health_issues');
        $this->about = $data->get('about');
        $this->last_name = $data->get('last_name');
        $this->first_name = $data->get('first_name');
        $this->designation = $data->get('designation');


        if(method_exists($data, 'file')) {
            $this->profile_picture = Settings::saveUploadedFile($data->file('profile_picture'), $this->profile_picture);
        }elseif($data->get('file') != null) {
            $this->profile_picture = Settings::saveUploadedFile($data->get('file'), $this->profile_picture);
        }

        if($data->get("remove-profile_picture")) {
            $this->profile_picture = Settings::removeFile($this->profile_picture);
        }

        $this->mobile =  $data->get('mobile');
        $this->whatsapp_number =  $data->get('same_as_above') ?  $data->get('mobile') : $data->get('whatsapp_number');
        $this->office =  $data->get('office');
        $this->location =  $data->get('location');
        $this->facebook_url =  $data->get('facebook_url');
        $this->twitter_url =  $data->get('twitter_url');
        $this->youtube_url =  $data->get('youtube_url');
        $this->linkedin_url =  $data->get('linkedin_url');
        $this->google_plus_url =  $data->get('google_plus_url');
        $this->instagram_url =  $data->get('instagram_url');
        $this->pinterest_url =  $data->get('pinterest_url');

        if (empty($this->patient_type))
            $this->patient_type = $data->get('patient_type');

        $this->age = $this->getAge();
        $this->relative_name = $data->get('relative_name');
        $this->profession_id = $data->get('profession_id');
        $this->marital_status = $data->get('marital_status');
        $this->country_code = $data->get('country_code');
        $this->landline_number = $data->get('landline_number');

        return $this;
    }

    public function user() {
        return $this->belongsTo('App\User','user_id');
    }

    public function profession() {
        return $this->belongsTo('App\Profession','profession_id');
    }

    public function address() {
        return $this->hasOne('App\UserAddress','profile_id')->where('user_id', $this->user_id);
    }

    public function getAddress()
    {
        $address = $this->address;
        $add_ar = [];

        if ($address != null) {
            $add_ar[] = $address->address1;
            $add_ar[] = $address->address2;
            $add_ar[] = $address->city;
            $add_ar[] = $address->state;
            $add_ar[] = $address->country;
            $add_ar[] = $address->zip;
        }
        $add_ar = array_filter($add_ar);
        return implode(',', $add_ar);
    }

    public static function getErrorMessages() {
        $messages   =   [
            'userProfile.first_name.required'       =>  'First name field is required',
            'userProfile.last_name.required'        =>  'Last name field is required',
            'userProfile.patient_type.required'     =>  'Patient type field is required',
            'userProfile.dob.required'              =>  'Date of Birth field is required',
            'userProfile.gender.required'           =>  'Gender field is required',
            'userProfile.profession_id.required'    =>  'Professions field is required',
            'userProfile.marital_status.required'   =>  'Marital status field is required',
            'userProfile.country_code.required'     =>  'Country code field is required',
            'userProfile.mobile.required'           =>  'Mobile Number field is required',
        ];

        return $messages;
    }

    public static function getRules() {
        $rules = [
            'userProfile.first_name'        =>  'required',
            'userProfile.patient_type'      =>  'required',
            'userProfile.dob'               =>  'required',
            'userProfile.gender'            =>  'required',
            'userProfile.profession_id'     =>  'required_without:userProfile.profession_name',
            'userProfile.profession_name'   =>  'required_without:userProfile.profession_id',
            'userProfile.marital_status'    =>  'required',
            'userProfile.country_code'      =>  'required',
            'userProfile.mobile'            =>  'required',
        ];

        return $rules;
    }

    public static function getGenderOptions($id = null) {
        $list = [
            self::GENDER_FEMALE => 'Female',
            self::GENDER_MALE => 'Male',
        ];

        if($id ===  null)
            return $list;

        $list[self::GENDER_NOT_SPECIFIED] = 'Not Specified';

        return $list[$id];
    }


    public static function getPatientType($id = null) {
        $list = [
            self::PATIENT_TYPE_IPD => 'IPD',
            self::PATIENT_TYPE_OPD => 'OPD'
        ];

        if($id ===  null)
            return $list;

        return $list[$id];
    }

    public static function getMaritalStatus($id = null) {
        $list = [
            self::MARRIED => 'Married',
            self::UNMARRIED => 'Unmarried'
        ];

        if($id ===  null)
            return $list;

        if (isset($list[$id]))
            return $list[$id];

        return $id;
    }

    public static function getProfessionType($id = null) {
        $professions = Profession::all();
        if($id ===  null)
            return $professions;

        $profession = Profession::find($id);

        if($profession != null)
            return $profession->name;

        return "--";
    }


    public function getAge()
    {
        $age = 'NA';
        /*if ($this->age != null) {
            return $this->age;
        }*/

        if (!empty($this->dob) && $this->dob != NUll) {
            $from = new \DateTime($this->dob);
            $to = new \DateTime('today');
            $age = $from->diff($to)->y;
        }
        return $age;
    }

    public function checkDocuments($request)
    {

        $documents = DocumentType::getDocuments();
        $ok = true;
        foreach ($documents as $document) {
            if (!empty($request->file('document_file-'.$document->id))) {
                if ($request->file('document_file-'.$document->id)->getSize() > 2097152 || $request->file('document_file-'.$document->id)->getSize() == "") {
                    $ok = false;
                }else {
                    $ok = true;
                }
            }
        }
        return $ok;
    }
    public function saveDocuments($request)
    {
        $documents = DocumentType::getDocuments();

        foreach ($documents as $document) {
            $userDocument = UserDocument::where([
                'user_id' => $this->id,
                'document_type_id' => $document->id
            ])->first();
            
            if (!empty($request->file('document_file-'.$document->id))) {


                if ($userDocument == null) {
                    $userDocument = new UserDocument();
                }

                $userDocument->user_id = $this->id;
                $userDocument->id_number = $request->get('document_id_'.$document->id);
                $userDocument->document_type_id = $document->id;
                $userDocument->file = Settings::saveUploadedFile($request->file('document_file-'.$document->id), $userDocument->file);
                $userDocument->file_name = $request->file('document_file-'.$document->id)->getClientOriginalName();
                $userDocument->save();
            }

            if ($userDocument != null && $request->get('remove_document-'.$document->id)) {
                $userDocument->customDelete();
            }
        }


        $documents = DocumentType::getDocuments(DocumentType::STATUS_FOREIGN_CLIENT);

        foreach ($documents as $document) {
            $userDocument = UserDocument::where([
                'user_id' => $this->id,
                'document_type_id' => $document->id
            ])->first();

            if (!empty($request->file('foreign_document_file-'.$document->id))) {
                if ($userDocument == null) {
                    $userDocument = new UserDocument();
                }
                $userDocument->user_id = $this->id;
                $userDocument->id_number = $request->get('foreign_document_id_'.$document->id);
                $userDocument->document_type_id = $document->id;
                $userDocument->file = Settings::saveUploadedFile($request->file('foreign_document_file-'.$document->id), $userDocument->file);
                $userDocument->file_name = $request->file('foreign_document_file-'.$document->id)->getClientOriginalName();

                $userDocument->save();
            }

            if ($userDocument != null && $request->get('remove_document-'.$document->id)) {
                $userDocument->customDelete();
            }
        }

    }


    public function getDocument($id, $attr = 'id')
    {
        $document = UserDocument::where('document_type_id', $id)->where('user_id', $this->id)->first();
        if ($document != null) {
            return $document->$attr;
        }

        return false;
    }

    public function documents()
    {
        return $this->hasMany('App\UserDocument', 'user_id');
    }

    public function customDelete()
    {
        $docs = $this->documents();
        foreach ($docs as $doc) {
            $doc->customDelete();
        }
        $this->delete();
    }

    public function getIdNumber()
    {
        $profile = UserProfile::where('patient_type', $this->patient_type)->orderBy('id', 'DESC')->where('id', '!=', $this->id)->whereNotNull('kid')->first();

        if ($profile) {
            $kid = str_replace('K-OPD', '', $profile->kid);

            if ($this->patient_type == UserProfile::PATIENT_TYPE_IPD) {
                $kid = str_replace('K-IPD', '', $profile->kid);
            }

            $kid = (int)$kid + 1;
            $limit =  $kid;
            for($i = $kid; $i = $limit ; $i++){
                $kid_check = User::getId("K-OPD", $i);
                if ($this->patient_type == UserProfile::PATIENT_TYPE_IPD) {
                    $kid_check = User::getId("K-IPD", $i);
                }
                $profile_check = UserProfile::where('kid', '=', $kid_check)->first();
                if($profile_check){
                    $limit = $i + 1;
                }else{
                    $final_kid = $i;
                    break;
                }   
            }
            return (int) $final_kid;
        }

        return ($profile + 1);
    }

    public function getGeneralIdNumber()
    {
        $profile = UserProfile::orderBy('id', 'DESC')->where('id', '!=', $this->id)->whereNotNull('uhid')->first();

        if ($profile) {
            if ($profile->uhid != null) {
                return $profile->uhid + 1;
            }
        }

        return 1;
    }
}
