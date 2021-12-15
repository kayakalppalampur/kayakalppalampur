<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    protected $fillable = [
        'question_id', 'feedback','rate', 'user_id', 'doctor_id', 'booking_id'
    ];

    protected $table = 'patient_feedbacks';

    public function checkValue($id, $value)
    {
        $question = explode(",",$this->question_id);
        $answer = explode(",", $this->rate);
        $ques_ans = array_combine($question, $answer);
        if (isset($ques_ans[$id])) {
            if ($ques_ans[$id] == $value) {
                return true;
            }
        }

        return false;
    }
}
