<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SystemFile extends Model
{
    //


    const IS_PUBLIC = 1;
    const IS_PRIVATE = 0;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'disk_name',
        'file_name',
        'file_size',
        'content_type',
        'field_name',
        'model_id',
        'model_type',
        'status',
        'uploaded_by'
    ];

    protected $appends = [
        'uploaded_by_department'
    ];

    public function getUploadedByDepartmentAttribute()
    {
        $user = User::find($this->uploaded_by);
        if ($user->isDoctor()){
            return $user->department->department->title;
        }

        return "";
    }

    public static function getImageUrl($model, $field = 'image')
    {
        if ($model != null) {
            try {
                $file = SystemFile::where([
                    'model_id' => $model->id,
                    'model_type' => get_class($model),
                    'field_name' => $field
                ])->first();

                if ($file != null) {
                    $path = storage_path() . '/app/' . $file->disk_name;
                    if (file_exists($path)) {
                        $file_path = base64_encode($file->disk_name);
                        return url('images/' . $file_path);
                    }
                }
            } catch (\Exception $e) {
                return "";
            }
        }

        return "";
    }


    public function getUrl2($model)
    {

        if (!empty($model)) {

            $path = storage_path('app/' . $model->disk_name) ;
            if (file_exists($path)) {
                $file_path = base64_encode($model->disk_name);
                return url('images/' . $file_path); ;
            }
        }


    }

    public static function getDownloadUrl($model, $field = 'image')
    {
        $file = SystemFile::where([
            'model_id' => $model->id,
            'model_type' => get_class($model),
            'field_name' => $field
        ])->orderBy('created_at', 'DESC')->first();
        if ($file != null) {
            $file_path = base64_encode($file->disk_name);
            return url('download/' . $file_path);
        }
        return "#";
    }

    public static function saveUploadedFile($image_file, $model, $field = 'image', $email =false)
    {


        if ($image_file != null) {
            $folder_name = "/uploads";

            $path = $image_file->store($folder_name);
            chmod(storage_path() . '/app/' . $path, 0777);

            /*$old_file = SystemFile::where([
                'model_id' =>$model->id,
                'model_type' => get_class($model),
                'field_name' => $field
            ])->first();

            if ($old_file != null) {
                @unlink(storage_path().'/app/'.$old_file->disk_name);
                $old_file->delete();
            }*/

            $image = SystemFile::where([
                'file_name' => $image_file->getClientOriginalName(),
                'session_id' => session()->getId(),
                'model_type' => get_class($model),
                'model_id' => $model->id != null ? $model->id : 0
            ])->first();

            if ($image == null) {
                $image = new SystemFile();
            }

            $image->disk_name = $path;
            $image->file_name = $image_file->getClientOriginalName();
            $image->file_size = $image_file->getClientSize();
            $image->content_type = $image_file->getMimeType();
            $image->field_name = $field;
            $image->model_id = $model->id != null ? $model->id : 0;
            $image->model_type = get_class($model);
            $image->session_id = session()->getId();
            $image->uploaded_by = \Auth::check() ? \Auth::user()->id : '';

            if ($image->save()) {
                return $image->id;
            }
        }
        return false;
    }

    public static function getImageName($model, $field = 'image')
    {
        $file = SystemFile::where([
            'model_id' => $model->id,
            'model_type' => get_class($model),
            'field_name' => $field
        ])->first();
        if ($file != null) {
            return $file->file_name;
        }
        return "";
    }

    public static function removeFile($model, $field = 'image')
    {
        $file = SystemFile::where([
            'model_id' => $model->id,
            'model_type' => get_class($model),
            'field_name' => $field
        ])->first();
        if ($file != null) {
            @unlink(storage_path() . '/app/' . $file->disk_name);
            $file->delete();
            return true;
        }

        return false;
    }

    public function customDelete()
    {
        @unlink(storage_path() . '/app/' . $this->disk_name);
        $this->delete();
        return true;

    }

    public function getUrl()
    {
        $file_path = base64_encode($this->disk_name);
        return url('images/' . $file_path);
    }


}
