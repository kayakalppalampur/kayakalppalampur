<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DocumentType extends Model
{
    const STATUS_INDIAN_CLIENT = 1;
    const STATUS_FOREIGN_CLIENT = 2;

    const IS_DOWNLOADABLE = 1;

    protected $fillable = [
        'title',
        'description',
        'is_downloadable',
        'file',
        'status'
    ];

    public static function getRules()
    {
        return [
            'title' => 'required'
        ];
    }

    public static function getDocuments($type = self::STATUS_INDIAN_CLIENT)
    {
        $types = DocumentType::where('status', "Like", "%" . $type . "%")->get();
        return $types;
    }

    public static function getRoutesArray()
    {
        return [
            'Laralum::document_types',
            'Laralum::document_type_create',
            'Laralum::document_type_edit',
            'Laralum::document_type.view',
            'Laralum::document_type_delete'
        ];
    }

    public function documents()
    {
        return $this->hasMany('App\UserDocument', 'document_type_id');
    }

    public function setData($request)
    {
        $this->title = $request->get('title');
        $this->description = $request->get('description');
        $this->is_downloadable = $request->get('is_downloadable');
        $this->status = is_array($request->get('status')) ? implode(',', $request->get('status')) : $request->get('status');

        $this->file = Settings::saveUploadedFile($request->file('file'));

        if (!empty($request->file('file'))) {
            $this->file_name = $request->file('file')->getClientOriginalName();
            $this->is_downloadable = self::IS_DOWNLOADABLE;
        }

        return $this;
    }

    public function customDelete()
    {
        $documents = $this->documents->count();
        if ($documents > 0) {
            foreach ($documents as $document) {
                $document->delete();
            }
        } else {
            $this->delete();
        }

        return true;
    }

    public function getUserType()
    {
        $types = explode(',', $this->status);
        $user_type = [];
        foreach ($types as $type) {
            if ($type == self::STATUS_FOREIGN_CLIENT) {
                $user_type[] = "Foreigners";
            } else {
                $user_type[] = "Indians";
            }
        }

        return implode(',', $user_type);
    }

    public static function getStatusOptions($id = null)
    {
        $list = [
            self::STATUS_INDIAN_CLIENT => 'Indians',
            self::STATUS_FOREIGN_CLIENT => 'Foreigners'
        ];

        if (isset($list[$id])) {
            return $list[$id];
        }

        return $list;
    }

    public function isChecked($type)
    {
        $types = explode(',', $this->status);
        if (in_array($type, $types)) {
            return "checked";
        }

        return "";
    }
}
