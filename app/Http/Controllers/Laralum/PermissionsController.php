<?php

namespace App\Http\Controllers\Laralum;

use App\Permission;
use App\Role;
use App\Settings;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Permission_Role;
use Laralum;
use PDF;

class PermissionsController extends Controller
{
    public function index(Request $request)
    {
        Laralum::permissionToAccess('admin.permissions.list');

        # Get all the permissions
        //$permissions = Laralum::permissions()->paginate(10);
        $per_page = $request->get("per_page") ? $request->get("per_page") : 10;
        $pagination = true;
        if ($per_page == "All") {
            $pagination = false;
        }
        $count = Permission::count();

        $role = "";
        $search = false;
        $matchThese = [];

        if ($request->s && $request->s != 'null') {
            $search_data = json_decode($request->s, true);

            if (!empty($search_data['name'])) {
                $option_ar[] = "Name";
                $search = true;
                $matchThese['name'] = $search_data['name'];
            }


            if (!empty($search_data['role_id'])) {
                $option_ar[] = "Role";
                $search = true;
                $role = $request->get('role_id');
                $matchThese['role_id'] = $search_data['role_id'];
            }

            if (!empty($search_data['slug'])) {
                $option_ar[] = "Slug";
                $search = true;
                $matchThese['slug'] = $search_data['slug'];
            }
        }

        $models = Permission::select('permissions.*')->orderBy('permissions.created_at', 'DESC');

        if ($search == true) {
            $models = Permission::select('permissions.*')->leftJoin('permission_role', 'permission_role.permission_id', '=', 'permissions.id')->where(function ($query) use ($matchThese) {
                foreach ($matchThese as $key => $match) {
                    $query->where($key, 'like', "%$match%");
                }
            })
                ->orderBy('permissions.created_at', 'DESC')->distinct();
            if ($role != "") {
                $models = $models->where('permission_role.role_id', $role);
            }
            $count = $models->count();
            $permissions = $models->get();
        } else {
            $count = $models->count();
            if ($pagination == true) {
                $permissions = $models->paginate($per_page);
            } else {
                $permissions = $models->get();
            }
        }
        $search_data = array_merge($matchThese);

        # Return the view
        return view('laralum/permissions/index', ['search_data' => $search_data, 'permissions' => $permissions, 'count' => $count]);
    }

    public function create()
    {
        Laralum::permissionToAccess('admin.permissions.list');

        # Check permissions
        //   Laralum::permissionToAccess('laralum.permissions.create');


        $data_index = 'permissions';
        require('Data/Create/Get.php');

        # Return the creation view
        return view('laralum/permissions/create', [
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
        Laralum::permissionToAccess('admin.permissions.list');

        # Check permissions
        //   Laralum::permissionToAccess('laralum.permissions.create');

        # Create the permission
        $row = Laralum::newPermission();
        $data_index = 'permissions';
        require('Data/Create/Save.php');

        # return a redirect
        return redirect()->route('Laralum::permissions')->with('success', trans('laralum.msg_permission_created'));
    }

    public function edit($id)
    {
        Laralum::permissionToAccess('admin.permissions.list');

        # Check permissions
        /*Laralum::permissionToAccess('laralum.permissions.edit');*/

        # Get the permission
        $row = Laralum::permission('id', $id);

        $data_index = 'permissions';
        require('Data/Edit/Get.php');


        # Return the view
        return view('laralum/permissions/edit', [
            'row' => $row,
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

    public function update($id, Request $request)
    {
        Laralum::permissionToAccess('laralum.permissions.access');

        # Check permissions
        Laralum::permissionToAccess('laralum.permissions.edit');

        # Get the permission
        $row = Laralum::permission('id', $id);

        $data_index = 'permissions';
        require('Data/Edit/Save.php');

        # return a redirect
        return redirect()->route('Laralum::permissions')->with('success', trans('laralum.msg_permission_updated'));
    }

    public function destroy($id)
    {
        Laralum::permissionToAccess('laralum.permissions.access');

        # Check permissions
        Laralum::permissionToAccess('laralum.permissions.delete');

        # Get the permission
        $perm = Laralum::permission('id', $id);

        # Check if it's su
        if ($perm->su) {
            abort(403, trans('laralum.error_security_reasons'));
        }

        # Delete relationships
        $rels = Permission_Role::where('permission_id', $perm->id)->get();
        foreach ($rels as $rel) {
            $rel->delete();
        }

        # Delete Permission
        $perm->delete();

        # Return a redirect
        return redirect()->route('Laralum::permissions')->with('success', trans('laralum.msg_permission_deleted'));
    }

    public function rolesEdit($id)
    {
        $model = Permission::find($id);
        return view('laralum.permissions.roles_edit', compact('model'));
    }

    public function rolesUpdate(Request $request, $id)
    {
        $model = Permission::find($id);

        if (!empty($request->get('roles'))) {
            $model->deleteOldRoles();
            $model->saveRoles($request);
            return view('laralum.permissions.roles_edit', compact('model'));
        }
        return redirect()->back()->with('error', 'Please select atleast one role.');
    }

    public function ajaxUpdate(Request $request)
    {
        $per_page = $request->get("per_page") ? $request->get("per_page") : 10;
        $pagination = true;
        if ($per_page == "All") {
            $pagination = false;
        }
        $matchThese = [];
        $search = false;
        $option_ar = [];
        if (!empty($request->has('name'))) {
            $option_ar[] = "Name";
            $search = true;
            $matchThese['name'] = $request->get('name');
        }
        $role = "";
        if (!empty($request->has('role_id'))) {
            $option_ar[] = "Role";
            $search = true;
            $role = $request->get('role_id');
            $matchThese['role_id'] = $request->get('role_id');
        }

        if (!empty($request->has('slug'))) {
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

        $models = Permission::select('permissions.*')->orderBy('permissions.created_at', 'DESC');

        if ($search == true) {
            $models = Permission::select('permissions.*')->leftJoin('permission_role', 'permission_role.permission_id', '=', 'permissions.id')->where(function ($query) use ($matchThese) {
                foreach ($matchThese as $key => $match) {
                    $query->where($key, 'like', "%$match%");
                }
            })
                ->orderBy('permissions.created_at', 'DESC')->distinct();
            if ($role != "") {
                $models = $models->where('permission_role.role_id', $role);
            }
            $count = $models->count();
            $permissions = $models->get();
        } else {
            $count = $models->count();
            if ($pagination == true) {
                $permissions = $models->paginate($per_page);
            } else {
                $permissions = $models->get();
            }
        }

        # Return the view
        return [
            'html' => view('laralum/permissions/_index', ['permissions' => $permissions, 'count' => $count, 'error' => $error, 'search' => $search, 'search_data' => $matchThese])->render()
        ];

    }

    public function printPermission(Request $request)
    {
        $page = $request->page;
        $s = $request->s;
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
                $option_ar[] = "Name";
                $search = true;
                $matchThese['name'] = $search_data['name'];
            }

            $role = "";
            if (!empty($search_data['role_id'])) {
                $option_ar[] = "Role";
                $search = true;
                $role = $search_data['role_id'];
                $matchThese['role_id'] = $search_data['role_id'];
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

        $models = Permission::select('permissions.*')->orderBy('permissions.created_at', 'DESC');

        if ($search == true) {
            $models = Permission::select('permissions.*')->leftJoin('permission_role', 'permission_role.permission_id', '=', 'permissions.id')->where(function ($query) use ($matchThese) {
                foreach ($matchThese as $key => $match) {
                    $query->where($key, 'like', "%$match%");
                }
            })
                ->orderBy('permissions.created_at', 'DESC')->distinct();
            if ($role != "") {
                $models = $models->where('permission_role.role_id', $role);
            }
            $count = $models->count();
            $permissions = $models->get();
        } else {
            $count = $models->count();
            if ($pagination == true) {
                $permissions = $models->paginate($per_page);
            } else {
                $permissions = $models->get();
            }
        }

        # Return the view
        return view('laralum/permissions/print_permissions', [
            'permissions' => $permissions,
            'count' => $count,
            'print' => true,
        ]);
    }

    public function exportPermissions(Request $request, $type)
    {
        $page = $request->page;
        $s = $request->s;
        $per_page = $request->get("per_page") ? $request->get("per_page") : 10;
        $pagination = true;
        if ($per_page == "All") {
            $pagination = false;
        }
        $matchThese = [];
        $search = false;
        $option_ar = [];
        $role = "";

        if ($request->s && $request->s != 'null') {
            $search_data = json_decode($request->s, true);

            if (!empty($search_data['name'])) {
                $option_ar[] = "Name";
                $search = true;
                $matchThese['name'] = $search_data['name'];
            }


            if (!empty($search_data['role_id'])) {
                $option_ar[] = "Role";
                $search = true;
                $role = $search_data['role_id'];
                $matchThese['role_id'] = $search_data['role_id'];
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

        $models = Permission::select('permissions.*')->orderBy('permissions.created_at', 'DESC');

        if ($search == true) {
            $models = Permission::select('permissions.*')->leftJoin('permission_role', 'permission_role.permission_id', '=', 'permissions.id')->where(function ($query) use ($matchThese) {
                foreach ($matchThese as $key => $match) {
                    $query->where($key, 'like', "%$match%");
                }
            })
                ->orderBy('permissions.created_at', 'DESC')->distinct();
            if ($role != "") {
                $models = $models->where('permission_role.role_id', $role);
            }
            $count = $models->count();
            $permissions = $models->get();
        } else {
            $count = $models->count();
            if ($pagination == true) {
                $permissions = $models->paginate($per_page);
            } else {
                $permissions = $models->get();
            }
        }

        $all_ar[] = [
            'Name',
            'Description',
            'Slug',
            'Roles',
        ];

        foreach ($permissions as $permission)
        {
            $all_ar[] = [
                Laralum::permissionName($permission->slug),
                Laralum::permissionDescription($permission->slug),
                $permission->slug,
                $permission->getRoleNames()
            ];
        }


        // Generate and return the spreadsheet
        $excel = \App::make('excel');
        $excel = $excel->create('Permissions', function ($excel) use ($all_ar) {

            // Set the spreadsheet title, creator, and description
            $excel->setTitle('Permissions List');

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
            return $pdf->download('permissions_list.pdf');
        }

        return redirect()->back()->with('error', 'Something went Wrong!!!');
    }
}
