<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FeedbackQuestion extends Model
{

    protected $fillable = [
        'question', 'type', 'created_by'
    ];

    protected $table = 'feedback_questions';

    public static function getRules()
    {
        return [
            'question' => 'required'
        ];
    }

    public static function getRoutesArray()
    {
        return [
            'Laralum::feedback-questions',
            'feedback-question.edit',
            'feedback-question.delete'
        ];
    }

    public function setData($data)
    {
        $this->question = $data->get("question");
        $this->type = $data->get("type") != null ? $data->get("type") : 0;
        return $this;
    }
}
