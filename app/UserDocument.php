<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserDocument extends Model
{
    protected $fillable = [
        'document_type_id',
        'user_id',
        'file',
        'file_name',
        'id_number',
    ];

    public function document()
    {
        return $this->belongsTo('App\DocumentType', 'document_type_id');
    }

    public static function getRules()
    {
        return [
            'document_type_id' => 'required',
            'file' => 'required'
        ];
    }

    public function setData($request)
    {
        $this->document_type_id = $request->get('document_type_id');
        $this->user_id = $request->get('user_id');
        $this->id_number = $request->get('id_number');
        $this->file = Settings::saveUploadedFile($request->file('file'));

        if (!empty($request->file('file'))) {
            $this->file_name = $request->file('file')->getClientOriginalName();
        }

        return $this;
    }

    public function customDelete()
    {
        Settings::removeFile($this->file);
        $this->delete();
        return true;
    }
}
