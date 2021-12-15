<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\Mail;
use Larablocks\Pigeon\Pigeon;

class EmailTemplate extends Model
{

    const GROUP_BOOKING = 0;
    const GROUP_DISCHARGE_DAY = 1;
    const GROUP_FOLLOWUP = 2;
    const GROUP_OTHER = 3;

    const EVENT_BOOKING = 0;
    const EVENT_REGISTRATION = 1;
    const EVENT_REGISTRATION_RECEPTION = 2;
    const EVENT_DIET_CHART = 3;
    const EVENT_TREATMENT_ALLOCATION = 4;
    const EVENT_DISCHARGE_DAY = 5;
    const EVENT_VITAL_DATA = 6;
    const EVENT_EXAMINATION = 7;
    const EVENT_DISCHARGE_SUMMARY = 8;
    const EVENT_TREATMENT = 9;
    const EVENT_FOLLOWUP_PLAN = 10;
    const EVENT_DIET_FOLLOWUP_PLAN = 11;
    const EVENT_OTHER = 12;
    const EVENT_QUERY_SUBMITTED = 13;

    const STATUS_ENABLE = 1;
    const STATUS_DISABLE = 0;


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'handle',
        'subject',
        'content',
        'owner_id',
        'layout_id',
        'language',
        'group_id',
        'event_id',
        'status',
        'from_email',
        'from_name',
        'reply_to_email',
        'sent_date_time'
    ];

    protected $table = 'email_template';

    public static function getGroupOptions($id = null)
    {
        $list = [
            self::GROUP_BOOKING => 'Booking',
            self::GROUP_DISCHARGE_DAY => 'Discharge day',
            self::GROUP_FOLLOWUP => 'Followup',
            self::GROUP_OTHER => 'Other',
        ];

        if ($id === null)
            return $list;

        if (isset($list[$id]))
            return $list[$id];

        return $id;
    }

    public static function getTemplates($type)
    {
        $templates = self::where('group_id', $type)->get();
        return $templates;
    }

    public static function sendEmail($event, $fieldArray, $email)
    {

        try {
            $subject = '';
            $template = EmailTemplate::where('event_id', $event)->first();
            if($template){
                $subject = $template->subject;
            }
            if ($template != null) {
                $mailer = app()->make(EmailTemplateMailer::class);
                $slug = $template->handle;
                $mailer->send($slug, $fieldArray, function ($m) use ($email,$subject) {
                    $m->subject($subject);
                    $m->to($email);
                });
                /*
                $email_tem = app('EmailTemplate');
                $mail = $email_tem->fetch($template->handle, $fieldArray);*//*->from($template->from_email, $template->from_name)->replyTo($template->reply_to_email);*/


                /*Mail::to($email)->send($mail);*/
            }
        } catch (\Exception $e) {
            \Log::error('Email Message' . $e->getMessage());
            print_r($e->getMessage());exit;
        }
    }

    public static function getCustomText()
    {
        $fields = [
            '$name',
            '$contact',
            '$email',
            '$date',
            '$booking_id',
            '$registration_id',
            '$patient_id',
            '$booking_dates'
        ];
        return $fields;
    }

    public static function getRoutesArray()
    {
        return [
            'Laralum::email_templates',
            'Laralum::email_templates.create',
            'Laralum::email_templates.edit',
            'Laralum::email_templates.destroy'
        ];
    }

    public function layout()
    {
        return $this->belongsTo('App\EmailLayout', 'layout_id');
    }

    public function rules()
    {
        $rules = [
            'event_id' => 'required',
            'subject' => 'required',
            'content' => 'required',
            'layout_id' => 'required',
        ];

        return $rules;
    }

    public function setData($data)
    {
        $this->handle = str_slug($data->get('subject')) . time();
        $this->subject = $data->get('subject');
        $this->content = $data->get('content');
        $this->layout_id = $data->get('layout_id');
        $this->group_id = $data->get('group_id');
        $this->event_id = $data->get('event_id');
        $this->from_email = $data->get('from_email');
        $this->from_name = $data->get('from_name');
        $this->reply_to_email = $data->get('reply_to_email');
        $this->sent_date_time = date("Y-m-d H:i:s", strtotime($data->get('sent_date_time')));
        $this->status = $data->get('status') ? $data->get('status') : self::STATUS_DISABLE;
        $this->owner_id = \Auth::user()->id;
        return $this;
    }

    public function getEvent()
    {
        if ($this->event_id == self::EVENT_OTHER) {
            return date("d-m-Y h:i a", strtotime($this->sent_date_time));
        }

        return $this->getEventOptions($this->event_id);
    }

    public static function getEventOptions($id = null, $group = null)
    {
        $list = [
            self::GROUP_BOOKING => [
                self::EVENT_BOOKING => 'Booking',
                self::EVENT_REGISTRATION => 'Registration',
                self::EVENT_REGISTRATION_RECEPTION => 'Registration Mail to reception',
                self::EVENT_DIET_CHART => 'Assign Diet Chart',
                self::EVENT_TREATMENT_ALLOCATION => 'Allocation of treatments',
                self::EVENT_VITAL_DATA => 'Vital data',
                self::EVENT_EXAMINATION => 'Examination data',
                self::EVENT_TREATMENT => 'On Treatment date',
            ],
            self::GROUP_DISCHARGE_DAY => [
                self::EVENT_DISCHARGE_DAY => 'Discharge day of patients',
                self::EVENT_DISCHARGE_SUMMARY => 'Discharge Summary',
            ],
            self::GROUP_FOLLOWUP => [
                self::EVENT_FOLLOWUP_PLAN => 'Followup',
                self::EVENT_DIET_FOLLOWUP_PLAN => 'Diet followpup',
            ],
            self::GROUP_OTHER => [
                self::EVENT_OTHER => 'Other',
                self::EVENT_QUERY_SUBMITTED => 'Submission of queries'
            ],

        ];

        $new_list = [];
        foreach ($list as $key => $value) {
            // echo '<pre>'; print_r($value);exit;
            foreach ($value as $k => $v) {
                $new_list[$k] = $v;
            }
        }
        ksort($new_list);

        if ($group !== null) {
            return $list[$group];
        }

        if ($id === null && $group === null) {
            return $new_list;
        }


        if (isset($list[$id]))
            return $new_list[$id];

        return $id;
    }

}
