<?php

namespace App\Http\Controllers\Laralum;

use App\DocumentType;
use App\Http\Controllers\Controller;
use App\Settings;
use Illuminate\Http\Request;
use PDF;

class DocumentTypeController extends Controller
{
    //
    /**
     * document_type listing
     * @return View
     */
    public function index(Request $request)
    {
        Laralum::permissionToAccess('admin.admin_settings.document_types');
        $per_page = $request->get("per_page") ? $request->get("per_page") : 10;
        $pagination = true;
        if ($per_page == "All") {
            $pagination = false;
        }
        $matchThese = [];
        $search = false;
        $option_ar = [];

        if (!empty($request->get('title'))) {
            $option_ar[] = "Title";
            $search = true;
            $role = $request->get('title');
            $matchThese['title'] = $request->get('title');
        }
        $status = "";
        if (!empty($request->get('status'))) {
            $option_ar[] = "Status";
            $search = true;
            $status = $request->get('status');
        }

        $options = implode(", ", $option_ar);

        $error = "Entered " . $options . " is not valid,
make sure that you are entering valid " . $options . " 
or search by other options";

        $per_page = $request->get("per_page") ? $request->get("per_page") : 10;
        $pagination = true;
        if ($per_page == "All") {
            $pagination = false;
        }

        $document_types = DocumentType::select('*');

        if ($search == true) {
            $document_types = DocumentType::select('document_types.*')->where(function ($query) use ($matchThese, $status) {
                foreach ($matchThese as $key => $match) {
                    $query->where($key, 'like', "%$match%");
                }
                if ($status) {
                    $query->where('status',  'like', "%$status%");
                }

            });

            $count = $document_types->count();
            $document_types = $document_types->get();

            if ($status) {
                $matchThese['status'] = $status;
            }
        } else {
            $count = $document_types->count();
            if ($pagination == true) {
                $document_types = $document_types->paginate($per_page);
            } else {
                $document_types = $document_types->get();
            }
        }

        if ($request->ajax()) {
            # Return the view
            return [
                'html' => view('laralum/document_types/_list', ['document_types' => $document_types, 'count' => $count, 'error' => $error, 'search' => $search, 'search_data' => $matchThese])->render()
            ];
        }

        return view('laralum.document_types.index', compact('document_types', 'count'));
    }

    /**
     * document_type details with replies
     * @return View
     */
    public function view($id)
    {
        Laralum::permissionToAccess('admin.admin_settings.document_types');
        $document_type = DocumentType::find($id);

        return view('laralum.document_types.view', compact('document_type'));
    }

    public function edit($id)
    {
        # Check permissions
        Laralum::permissionToAccess('admin.admin_settings.document_types');

        # Find the document_type
        $row = DocumentType::findOrFail($id);
        \Session::put('document_type_id', $id);

        # Get all the data
        $data_index = 'document_types';
        require('Data/Edit/Get.php');

        # Return the view
        return view('laralum/document_types/edit', [
            'row' => $row,
            'fields' => $fields,
            'confirmed' => $confirmed,
            'empty' => $empty,
            'encrypted' => $encrypted,
            'hashed' => $hashed,
            'masked' => $masked,
            'table' => $table,
            'code' => $code,
            'wysiwyg' => $wysiwyg,
            'relations' => $relations,
        ]);
    }

    public function update($id, Request $request)
    {
        # Check permissions
        Laralum::permissionToAccess('admin.admin_settings.document_types');

        # Find the row
        $document_type = DocumentType::findOrFail($id);
        try {

            if ($document_type->setData($request)) {
                $document_type->save();
                return redirect()->route('Laralum::document_types')->with('success', 'DocumentType edited successfully.');
            } else {
                return redirect()->route('Laralum::document_types')->with('error', 'Something went wrong. Please try again later.');
            }

        } catch (\Exception $e) {

            \Log::error("Failed to add the document_type, possible causes: " . $e->getMessage());
            //print_r($e->getMessage());exit;
            return redirect()->route('Laralum::document_types')->with('error', 'Something went wrong. Please try again later.');
        }

    }

    /**
     * add document_type for the staff
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        Laralum::permissionToAccess('admin.admin_settings.document_types');
        # Get all the data
        $data_index = 'document_types';
        require('Data/Create/Get.php');

        return view('laralum.document_types.create',
            [
                'fields' => $fields,
                'confirmed' => $confirmed,
                'encrypted' => $encrypted,
                'hashed' => $hashed,
                'masked' => $masked,
                'table' => $table,
                'code' => $code,
                'wysiwyg' => $wysiwyg,
                'relations' => $relations,
            ]);
    }

    public function store(Request $request)
    {
        Laralum::permissionToAccess('admin.admin_settings.document_types');
        $rules = DocumentType::getRules(true);

        $validator = \Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput()->with(['error' => 'Please check the errors below and try again.']);
        }

        try {
            $document_type = new DocumentType();

            if ($document_type->setData($request)) {
                ;
                $document_type->save();
                return redirect()->route('Laralum::document_types')->with('success', 'Discount Offer added successfully.');
            } else {
                return redirect()->route('Laralum::document_types')->with('error', 'Something went wrong. Please try again later.');
            }

        } catch (\Exception $e) {

            \Log::error("Failed to add the document_type, possible causes: " . $e->getMessage());
            //print_r($e->getMessage());exit;
            return redirect()->route('Laralum::document_types')->with('error', 'Something went wrong. Please try again later.');
        }

    }


    public function destroy($id)
    {   # Check permissions
        Laralum::permissionToAccess('admin.admin_settings.document_types');

        # Select DocumentType
        $document_type = DocumentType::findOrFail($id);

        # Check DocumentType Users
        /*if ($document_type->isAllowed()) {*/
        # Delete DocumentType
        $document_type->customDelete();

        return redirect()->route('Laralum::document_types')->with('success', 'Successfully Deleted Document Type.');
        /*}*/

        return redirect()->route('Laralum::document_types')->with('error', trans('laralum.msg_document_type_delete_not_allowed'));

    }

    public function getDiscountCode(Request $request)
    {
        $result = [
            'amount' => 0,
            'id' => 0
        ];
        if ($request->get('code') != null) {
            $offer = DocumentType::where('code', $request->get('code'))->where('status', DocumentType::STATUS_ACTIVE)->first();
            if ($offer != null) {
                $amount = 0;
                if ($offer->type == DocumentType::TYPE_FLAT) {
                    $amount = $offer->discount_value;
                } else {
                    $amount = $offer->discount_value * $request->get("price") / 100;
                }
                $result = [
                    'amount' => $amount,
                    'id' => $offer->id
                ];
            }
        }
        return $result;
    }

    public function printDocumentType(Request $request)
    {
        Laralum::permissionToAccess('admin.admin_settings.document_types');
        $per_page = $request->get("per_page") ? $request->get("per_page") : 10;
        $pagination = true;
        if ($per_page == "All") {
            $pagination = false;
        }
        $matchThese = [];
        $search = false;
        $option_ar = [];

        if ($request->s && $request->s != 'null') {
            $search_data = json_decode($request->s, true);

            if (!empty($search_data['title'])) {
                $option_ar[] = "Title";
                $search = true;
                $matchThese['title'] = $search_data['title'];
            }
            $status = "";
            if (!empty($search_data['status'])) {
                $option_ar[] = "Status";
                $search = true;
                $status = $search_data['status'];
            }
        }

        $options = implode(", ", $option_ar);

        $error = "Entered " . $options . " is not valid,
make sure that you are entering valid " . $options . " 
or search by other options";

        $per_page = $request->get("per_page") ? $request->get("per_page") : 10;
        $pagination = true;
        if ($per_page == "All") {
            $pagination = false;
        }

        $document_types = DocumentType::select('*');

        if ($search == true) {
            $document_types = DocumentType::select('document_types.*')->where(function ($query) use ($matchThese, $status) {
                foreach ($matchThese as $key => $match) {
                    $query->where($key, 'like', "%$match%");
                }
                if ($status) {
                    $query->where('status',  'like', "%$status%");
                }

            });

            $count = $document_types->count();
            $document_types = $document_types->get();

            if ($status) {
                $matchThese['status'] = $status;
            }
        } else {
            $count = $document_types->count();
            if ($pagination == true) {
                $document_types = $document_types->paginate($per_page);
            } else {
                $document_types = $document_types->get();
            }
        }

        return view('laralum/document_types/print_document_types', [
            'document_types' => $document_types,
            'count' => $count,
            'print' => true

        ]);
    }

    public function exportDocumentType(Request $request, $type)
    {
        Laralum::permissionToAccess('admin.admin_settings.document_types');
        $per_page = $request->get("per_page") ? $request->get("per_page") : 10;
        $pagination = true;
        if ($per_page == "All") {
            $pagination = false;
        }
        $matchThese = [];
        $search = false;
        $option_ar = [];

        if ($request->s && $request->s != 'null') {
            $search_data = json_decode($request->s, true);

            if (!empty($search_data['title'])) {
                $option_ar[] = "Title";
                $search = true;
                $matchThese['title'] = $search_data['title'];
            }
            $status = "";
            if (!empty($search_data['status'])) {
                $option_ar[] = "Status";
                $search = true;
                $status = $search_data['status'];
            }
        }

        $options = implode(", ", $option_ar);

        $error = "Entered " . $options . " is not valid,
make sure that you are entering valid " . $options . " 
or search by other options";

        $per_page = $request->get("per_page") ? $request->get("per_page") : 10;
        $pagination = true;
        if ($per_page == "All") {
            $pagination = false;
        }

        $document_types = DocumentType::select('*');

        if ($search == true) {
            $document_types = DocumentType::select('document_types.*')->where(function ($query) use ($matchThese, $status) {
                foreach ($matchThese as $key => $match) {
                    $query->where($key, 'like', "%$match%");
                }
                if ($status) {
                    $query->where('status',  'like', "%$status%");
                }

            });

            $count = $document_types->count();
            $document_types = $document_types->get();

            if ($status) {
                $matchThese['status'] = $status;
            }
        } else {
            $count = $document_types->count();
            if ($pagination == true) {
                $document_types = $document_types->paginate($per_page);
            } else {
                $document_types = $document_types->get();
            }
        }

        $all_ar[] = [
            'Title',
            'User Type',
        ];

        foreach ($document_types as $document_type)
        {
            $doc_type = $document_type->getStatusOptions($document_type->status);
            if(is_array($doc_type)){
               $doc_type = implode(",",$doc_type);
            }
            $all_ar[] = [
                $document_type->title,
                @$doc_type,
            ];
        }

        //return $all_ar;
        // Generate and return the spreadsheet
        $excel = \App::make('excel');
        $excel = $excel->create('Document Types', function ($excel) use ($all_ar) {

            // Set the spreadsheet title, creator, and description
            $excel->setTitle('Document Types');

            // Build the spreadsheet, passing in the payments array
            $excel->sheet('sheet1', function ($sheet) use ($all_ar) {
                $sheet->fromArray($all_ar, null, 'A1', false, false);
            });

        });
        //return $excel;
        if ($type == Settings::EXPORT_CSV) {
            $excel->download('csv');
        } elseif ($type == Settings::EXPORT_EXCEL) {
            $excel->download('xls');
        } else {
            $pdf = PDF::loadView('booking.pdf', array('data' => $all_ar));
            return $pdf->download('document_types_list.pdf');
        }

        return redirect()->back()->with('error', 'Something went Wrong!!!');
    }

}
