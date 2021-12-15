<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Issue extends Model
{
    //
    const STATUS_PENDING = 0;
    const STATUS_PROCESSING = 1;
    const STATUS_RESOLVED = 2;

    const TYPE_QUERY = 1;
    const TYPE_ISSUE = 0;

    protected $fillable = ['status', 'type', 'title', 'description', 'name', 'email_id'];

    /*
     * define relation with user
     * @return array
     */
    public function user()
    {
        return $this->belongsTo('App\User', 'created_by');
    }

    /*
    * define relation with replies
    * @return array
    */
    public function replies()
    {
        return $this->hasMany('App\IssueReply', 'issue_id');
    }

    /**
     * create validation rules
     * @return array
     */
    public static function getRules()
    {
        $rules = [
            'title'       =>  'required',
            //'description' => 'required'
        ];
        return $rules;
    }

    public function setData($data, $id = null)
    {
        $this->title = $data->get('title');
        $this->name = $data->get('name');
        $this->email_id = $data->get('email_id');
        $this->description = $data->get('description') != null ? $data->get('description') : "";
        $this->status = $data->get('status') != null ? $data->get('status') : self::STATUS_PENDING ;
        if (\Auth::check())
            $this->created_by = $data->get('created_by') != null ? $data->get('created_by') : \Auth::user()->id;
        $this->type = $data->get('type') != null ? $data->get('type') : self::TYPE_ISSUE;

        return true;
    }

    public static function getStatusOptions($id = null)
    {
        $list = [
            self::STATUS_PENDING => 'New',
            self::STATUS_PROCESSING => 'Processing',
            self::STATUS_RESOLVED => 'Resolved'
        ];

        if ($id === null) {
            return $list;
        }

        return $list[$id];
    }
    
    public static function getOptionList($id)
    {
        $option = '';
        foreach (\App\Issue::getStatusOptions() as $k => $status) {
            $selected = $id == $k ? 'selected' : "" ;
            $option .= '<option ' . $selected. 'value="' . $k . '">' . $status . '</option>';
        }

        return $option;
    }

    public static function getStatusLabelOptions($id)
    {
        switch ($id) {
            case self::STATUS_PENDING : return '<div style="margin-left: 4px;" class="ui blue label">'.self::getStatusOptions($id).'</div>';
                break;
            case self::STATUS_RESOLVED : return '<div style="margin-left: 4px;" class="ui green label">'.self::getStatusOptions($id).'</div>';
                break;
            case self::STATUS_PROCESSING :
            default :
                return '<div style="margin-left: 4px;" class="ui orange label">'.self::getStatusOptions($id).'</div>';
                break;
        }

    }

    public function getAllReplies()
    {
        $replies = IssueReply::where('issue_id', $this->id)->orderBy('created_at', 'DESC')->get();
        return $replies;
    }

    public function customeDelete()
    {
        $replies = $this->replies;
        if ($replies->count() > 0) {
            foreach ($replies as $reply) {
                $reply->delete();
            }
        }
        $this->delete();
        return true;
    }

    public static function getRoutesArray()
    {
        return [
            'Laralum::issues',
            'Laralum::issue.create',
            'Laralum::issue.edit',
            'Laralum::issue.view',
            'Laralum::issue.send_reply',
            'Laralum::issue.delete',
        ];
    }
}
