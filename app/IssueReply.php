<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class IssueReply extends Model
{
    //
    protected $fillable = ['issue_id', 'status', 'message', 'created_by'];

    public function user()
    {
        return $this->belongsTo('App\User', 'created_by');
    }
    
    public function issue()
    {
        return $this->belongsTo('App\Issue', 'issue_id');
    }
}
