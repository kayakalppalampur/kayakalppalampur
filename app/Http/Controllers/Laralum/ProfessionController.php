<?php

namespace App\Http\Controllers\Laralum;

use App\Http\Controllers\Controller;
use App\Profession;
use App\Settings;
use Illuminate\Http\Request;
use PDF;

class ProfessionController extends Controller
{
    /**
     * professions listing
     * @return View
     */
    public function index(Request $request)
    {
        Laralum::permissionToAccess('admin.admin_settings.professions');

        $per_page = $request->get("per_page") ? $request->get("per_page") : 10;
        $pagination = true;
        if ($per_page == "All") {
            $pagination = false;
        }
        $matchThese = [];
        $search = false;
        $option_ar = [];

        if (!empty($request->get('name'))) {
            $option_ar[] = "Title";
            $search = true;
            $matchThese['name'] = $request->get('name');
        }
        if (!empty($request->get('slug'))) {
            $option_ar[] = "Slug";
            $search = true;
            $matchThese['slug'] = $request->get('slug');
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

        $professions = Profession::select('*');

        if ($search == true) {
            $professions = Profession::select('professions.*')->where(function ($query) use ($matchThese) {
                foreach ($matchThese as $key => $match) {
                    $query->where($key, 'like', "%$match%");
                }

            });

            $count = $professions->count();
            $professions = $professions->get();

        } else {
            $count = $professions->count();
            if ($pagination == true) {
                $professions = $professions->paginate($per_page);
            } else {
                $professions = $professions->get();
            }
        }

        if ($request->ajax()) {
            # Return the view
            return [
                'html' => view('laralum/professions/_list', ['professions' => $professions, 'count' => $count, 'error' => $error, 'search' => $search, 'search_data' => $matchThese])->render()
            ];
        }

        return view('laralum.professions.index', compact('professions'));
    }

    /**
     * professions details with replies
     * @return View
     */
    public function view($id)
    {
        Laralum::permissionToAccess('admin.admin_settings.professions');
        $profession = Profession::find($id);

        return view('laralum.professions.view', compact('profession'));
    }

    public function edit($id)
    {
        # Check permissions
        Laralum::permissionToAccess('admin.admin_settings.professions');

        # Find the professions
        $row = Profession::findOrFail($id);
        \Session::put('profession_id', $id);

        # Get all the data
        $data_index = 'professions';
        require('Data/Edit/Get.php');

        # Return the view
        return view('laralum/professions/edit', [
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
        Laralum::permissionToAccess('admin.admin_settings.professions');

        # Find the row
        $profession = Profession::findOrFail($id);

        try {

            if ($profession->setData($request)) {
                $profession->save();
                return redirect()->route('Laralum::professions')->with('success', 'Profession edited successfully.');
            } else {
                return redirect()->route('Laralum::professions')->with('error', 'Something went wrong. Please try again later.');
            }

        } catch (\Exception $e) {

            \Log::error("Failed to add the professions, possible causes: " . $e->getMessage());
            //print_r($e->getMessage());exit;
            return redirect()->route('Laralum::professions')->with('error', 'Something went wrong. Please try again later.');
        }

    }

    /**
     * add professions for the staff
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        Laralum::permissionToAccess('admin.admin_settings.professions');
        # Get all the data
        $data_index = 'professions';
        require('Data/Create/Get.php');

        return view('laralum.professions.create',
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
        Laralum::permissionToAccess('admin.admin_settings.professions');
        $rules = Profession::getRules(true);

        $validator = \Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput()->with(['error' => 'Please check the errors below and try again.']);
        }

        try {
            $profession = new Profession();

            if ($profession->setData($request)) {
                $profession->save();
                return redirect()->route('Laralum::professions')->with('success', 'Profession added successfully.');
            } else {
                return redirect()->route('Laralum::professions')->with('error', 'Something went wrong. Please try again later.');
            }

        } catch (\Exception $e) {

            \Log::error("Failed to add the professions, possible causes: " . $e->getMessage());
            //print_r($e->getMessage());exit;
            return redirect()->route('Laralum::professions')->with('error', 'Something went wrong. Please try again later.');
        }

    }

    public function destroy($id)
    {   # Check permissions
        Laralum::permissionToAccess('admin.admin_settings.professions');

        # Select Profession
        $profession = Profession::findOrFail($id);

        # Check Profession Users
        /*if ($profession->isAllowed()) {*/
        # Delete Profession
        $profession->customDelete();
        # Redirect the admin
        return redirect()->route('Laralum::professions')->with('success', trans('laralum.msg_profession_deleted'));
        /* }

         return redirect()->route('Laralum::professions')->with('error', trans('laralum.msg_profession_delete_not_allowed'));*/

    }


    public function printProfessions(Request $request)
    {
        Laralum::permissionToAccess('admin.admin_settings.professions');
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

            if (!empty($search_data['name'])) {
                $option_ar[] = "Title";
                $search = true;
                $matchThese['name'] = $search_data['name'];
            }

            if (!empty($search_data['slug'])) {
                $option_ar[] = "Slug";
                $search = true;
                $matchThese['slug'] = $search_data['slug'];
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

        $professions = Profession::select('*');

        if ($search == true) {
            $professions = Profession::select('professions.*')->where(function ($query) use ($matchThese) {
                foreach ($matchThese as $key => $match) {
                    $query->where($key, 'like', "%$match%");
                }

            });

            $count = $professions->count();
            $professions = $professions->get();

        } else {
            $count = $professions->count();
            if ($pagination == true) {
                $professions = $professions->paginate($per_page);
            } else {
                $professions = $professions->get();
            }
        }

        return view('laralum/professions/print_professions', [
            'professions' => $professions,
            'count' => $count,
            'print' => true

        ]);
    }

    public function exportProfessions(Request $request, $type)
    {
        Laralum::permissionToAccess('admin.admin_settings.professions');
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

            if (!empty($search_data['name'])) {
                $option_ar[] = "Title";
                $search = true;
                $matchThese['name'] = $search_data['name'];
            }

            if (!empty($search_data['slug'])) {
                $option_ar[] = "Slug";
                $search = true;
                $matchThese['slug'] = $search_data['slug'];
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

        $professions = Profession::select('*');


        if ($search == true) {
            $professions = Profession::select('professions.*')->where(function ($query) use ($matchThese) {
                foreach ($matchThese as $key => $match) {
                    $query->where($key, 'like', "%$match%");
                }

            });

            $count = $professions->count();
            $professions = $professions->get();
        } else {
            $count = $professions->count();
            if ($pagination == true) {
                $professions = $professions->paginate($per_page);
            } else {
                $professions = $professions->get();
            }
        }

        $all_ar[] = [
            'Title',
            'Slug',
        ];

        foreach ($professions as $profession)
        {
            $all_ar[] = [
                $profession->name,
                @$profession->slug,
            ];
        }


        // Generate and return the spreadsheet
        $excel = \App::make('excel');
        $excel = $excel->create('Professions', function ($excel) use ($all_ar) {

            // Set the spreadsheet title, creator, and description
            $excel->setTitle('Professions');

            // Build the spreadsheet, passing in the payments array
            $excel->sheet('sheet1', function ($sheet) use ($all_ar) {
                $sheet->fromArray($all_ar, null, 'A1', false, false);
            });

        });
        if ($type == Settings::EXPORT_CSV) {
            $excel->download('csv');
        } elseif ($type == Settings::EXPORT_EXCEL) {
            $excel->download('xls');
        } else {
            $pdf = PDF::loadView('booking.pdf', array('data' => $all_ar));
            return $pdf->download('professions_list.pdf');
        }

        return redirect()->back()->with('error', 'Something went Wrong!!!');
    }
}
