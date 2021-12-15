<?php

namespace App\Http\Controllers\Laralum;

use App\BlockedRoom;
use App\Booking;
use App\BookingRoom;
use App\Room;
use App\Settings;
use App\UserExtraService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Monolog\Handler\IFTTTHandler;
use Validator;
use App\Building;
use App\Room_Type;
use App\ExternalService;
use PDF;

class AccommodationController extends Controller
{
    /**
     * building listing
     * @return View
     */
    public function buildings()
    {

    }

    /**
     * create building
     * @return View
     */
    public function createBuilding()
    {
        //$users = User::get();

        //return view('laralum.attendance.create', compact('users', 'date'));
        return view('laralum.accommodation.create-building');
    }

    public function storeBuilding(Request $request)
    {
        //echo '<pre>';print_r($request->all());exit;
        $request_arr['building_name'] = 'required|max:255';
        $request_arr['number_of_floors'] = 'required|numeric|min:0';

        $validator = Validator::make($request->all(), $request_arr);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors())->withInput();
        }
        $building_arr = [];
        if (!empty($request['building_name'])) {
            $building_arr['name'] = $request['building_name'];
        }
        if (!empty($request['description'])) {
            $building_arr['description'] = $request['description'];
        }
        if (!empty($request['number_of_floors'])) {
            $building_arr['number_of_floors'] = $request['number_of_floors'];
        }

        if (!empty($request['room_types'])) {
            $building_arr['room_types'] = implode(',', $request['room_types']);
        }

        $building_arr['status'] = '1';
        //echo '<pre>';print_r($building_arr);exit;

        Building::create($building_arr);
        $data['page_title'] = 'Add Buildings';
        return redirect()->route('Laralum::buildings');
    }

    public function listBuilding(Request $request)
    {
        Laralum::permissionToAccess('admin.room_types');
        $models = Building::select('*')->orderBy('created_at', 'DESC');

        $search = false;
        $option_ar = [];
        $matchThese = [];

        if ($request->get('name')) {
            $models = $models->where('name', 'like', '%'.$request->get('name').'%');
            $matchThese['name'] = $request->get('name');
            $search = true;
            $option_ar[] = 'Name';
        }

        if ($request->get('no_floors')) {
            $models = $models->where('number_of_floors', $request->get('no_floors'));
            $matchThese['no_floors'] = $request->get('no_floors');
            $search = true;
            $option_ar[] = 'No of floors';
        }

        $data['page_title'] = 'Buildings List';
        $per_page = $request->get("per_page") ? $request->get("per_page") : 10;
        $pagination = true;
        if ($per_page == "All") {
            $pagination = false;
        }
        $count = $models->count();
        if ($pagination == true) {
            $models = $models->paginate($per_page);
        } else {
            $models = $models->get();
        }
        $data['models'] = $models;
        $data['count'] = $count;
        $data['page_title'] = 'Buildings List';
        $options = implode(", ", $option_ar);

        $error = "Entered " . $options . " is not valid,
make sure that you are entering valid " . $options . " 
or search by other options";

        if ($request->ajax()) {
            return [
                'html' => view('laralum/accommodation/_list-building', ['models' => $models, 'count' => $count, 'error' => $error, 'search' => $search, 'search_data' => array_merge($matchThese)])->render()
            ];
        }

        return view('laralum.accommodation.list-building', $data);
    }

    public function printBuildings(Request $request)
    {
        Laralum::permissionToAccess('admin.room_types');
        $models = Building::select('*')->orderBy('created_at', 'DESC');

        $search = false;
        $option_ar = [];
        $matchThese = [];


        if ($request->s && $request->s != 'null') {
            $search_data = json_decode($request->s, true);

            if (!empty($search_data['name'])) {
                $models = $models->where('name', 'like', '%' . $search_data['name'] . '%');
                $matchThese['name'] = $search_data['name'];
                $search = true;
                $option_ar[] = 'Name';
            }

            if (!empty($search_data['no_floors'])) {
                $models = $models->where('number_of_floors', $search_data['no_floors']);
                $matchThese['no_floors'] = $search_data['no_floors'];
                $search = true;
                $option_ar[] = 'No of floors';
            }
        }

        $data['page_title'] = 'Buildings List';
        $per_page = $request->get("per_page") ? $request->get("per_page") : 10;
        $pagination = true;
        if ($per_page == "All") {
            $pagination = false;
        }
        $count = $models->count();
        if ($pagination == true) {
            $models = $models->paginate($per_page);
        } else {
            $models = $models->get();
        }
        $data['models'] = $models;
        $data['count'] = $count;
        $data['page_title'] = 'Buildings List';
        $options = implode(", ", $option_ar);

        $error = "Entered " . $options . " is not valid,
make sure that you are entering valid " . $options . " 
or search by other options";

        $data['print'] = true;

        # Return the view
        return view('laralum/accommodation/print_list_buildings', $data);
    }

    public function exportBuildings(Request $request, $type)
    {
        Laralum::permissionToAccess('admin.room_types');
        $models = Building::select('*')->orderBy('created_at', 'DESC');

        $search = false;
        $option_ar = [];
        $matchThese = [];


        if ($request->s && $request->s != 'null') {
            $search_data = json_decode($request->s, true);

            if (!empty($search_data['name'])) {
                $models = $models->where('name', 'like', '%' . $search_data['name'] . '%');
                $matchThese['name'] = $search_data['name'];
                $search = true;
                $option_ar[] = 'Name';
            }

            if (!empty($search_data['no_floors'])) {
                $models = $models->where('number_of_floors', $search_data['no_floors']);
                $matchThese['no_floors'] = $search_data['no_floors'];
                $search = true;
                $option_ar[] = 'No of floors';
            }
        }

        $data['page_title'] = 'Buildings List';
        $per_page = $request->get("per_page") ? $request->get("per_page") : 10;
        $pagination = true;
        if ($per_page == "All") {
            $pagination = false;
        }
        $count = $models->count();
        if ($pagination == true) {
            $models = $models->paginate($per_page);
        } else {
            $models = $models->get();
        }
        $data['models'] = $models;
        $data['count'] = $count;
        $data['page_title'] = 'Buildings List';
        $options = implode(", ", $option_ar);

        $error = "Entered " . $options . " is not valid,
make sure that you are entering valid " . $options . " 
or search by other options";


        $all_ar[] = [
            'Name',
            'Number of Floors',
        ];

        foreach ($models as $model)
        {
            $all_ar[] = [
                $model->name,
                $model->number_of_floors,
            ];
        }

        // Generate and return the spreadsheet
        $excel = \App::make('excel');
        $excel = $excel->create('buildings', function ($excel) use ($all_ar) {

            // Set the spreadsheet title, creator, and description
            $excel->setTitle('Buildings');

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
            $pdf = \PDF::loadView('booking.pdf', array('data' => $all_ar));
            return $pdf->download('buildings.pdf');
        }

        return redirect()->back()->with('error', 'Something went Wrong!!!');
    }

    public function deleteBuilding($building_id)
    {
        $building = Building::where('id', $building_id)->first();
        if($building->checkDelete()) {
            return redirect()->route('Laralum::buildings')->with('error', "Building can't be deleted as there exists some bookings for this building");
        }

        $building->customDelete();
        return redirect()->route('Laralum::buildings')->with('success', 'Building has been deleted successfully');
    }

    public function editBuilding($building_id)
    {
        $building_arr = [];
        $building = new Building();
        if (Building::where('id', $building_id)->exists()) {
            $building = Building::where('id', $building_id)->first();
            $building_arr = Building::where('id', $building_id)->first()->toArray();
        }
        $data['building_arr'] = $building_arr;
        $data['building'] = $building;
        return view('laralum.accommodation.edit-building', $data);
    }

    public function updateBuilding($building_id, Request $request)
    {
        //echo '<pre>'; print_r($request->all()); echo '</pre>'; exit;
        $request_arr['building_name'] = 'required|max:255';
        $request_arr['number_of_floors'] = 'required|numeric|min:0';

        $validator = Validator::make($request->all(), $request_arr);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors())->withInput();
        }
        $building_data = [];

        if (!empty($request['building_id'])) {
            $building_id = $request['building_id'];
            $building_data['building_id'] = $building_id;
        }
        $building_name = "";
        if (!empty($request['building_name'])) {
            $building_name = $request['building_name'];
            $building_data['name'] = $building_name;
        }
        $number_of_floors = "";
        if (!empty($request['number_of_floors'])) {
            $number_of_floors = $request['number_of_floors'];
            $building_data['number_of_floors'] = $number_of_floors;
        }

        $room_types = '';
        if (!empty($request['room_types'])) {
            $room_types = implode(',', $request['room_types']);
        }

        if (!empty($request['description'])) {
            $building_data['description'] = $request['description'];
        }

        $building_data = ['name' => $building_name,
            'number_of_floors' => $number_of_floors,
            'room_types' => $room_types];
        Building::where('id', $building_id)->update($building_data);

        return redirect(route('Laralum::buildings'))->with('status', 'Building has been updated successfully');
    }

    /**
     * create Room Type
     * @return View
     */
    public function createRoomtype()
    {
        Laralum::permissionToAccess('admin.room_types');
        return view('laralum.accommodation.create-room-type');
    }

    /**
     * Store Room type
     * @return View
     */
    public function storeRoomtype(Request $request)
    {
        //echo '<pre>';print_r($request->all());exit;
        $request_arr['room_type_name'] = 'required|max:255';
        /*$request_arr['room_type_price'] = 'required|numeric|min:0';*/
        $request_arr['short_name'] = 'required|max:20';

        $validator = Validator::make($request->all(), $request_arr);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors())->withInput();
        }
        $room_type_arr = [];
        if (!empty($request['room_type_name'])) {
            $room_type_arr['name'] = $request['room_type_name'];
        }
        if (!empty($request['room_type_price'])) {
            $room_type_arr['price'] = $request['room_type_price'];
        }
        $room_type_arr['short_name'] = $request['short_name'];
        $room_type_arr['status'] = '1';
        Room_Type::create($room_type_arr);
        $data['page_title'] = 'Add Room Type';
        return redirect()->route('Laralum::room_types');
    }

    /**
     * List Room type
     * @return View
     */

    public function listRoomtype(Request $request)
    {
        Laralum::permissionToAccess('admin.room_types');
        $room_types = Room_Type::select('*')->orderBy('room_types.created_at', 'DESC');

        $search = false;
        $option_ar = [];
        $matchThese = [];
        if ($request->get('room_type_name')) {
            $room_types = $room_types->where('name', 'like', '%'.$request->get('room_type_name').'%');
            $matchThese['room_type_name'] = $request->get('room_type_name');
            $search = true;
            $option_ar[] = 'Room type name';
        }

        if ($request->get('room_type_short_name')) {
            $room_types = $room_types->where('short_name', $request->get('room_type_short_name'));
            $matchThese['room_type_short_name'] = $request->get('room_type_short_name');
            $search = true;
            $option_ar[] = 'Room type short name';
        }

        $data['page_title'] = 'Room Type List';
        $per_page = $request->get("per_page") ? $request->get("per_page") : 10;
        $pagination = true;
        if ($per_page == "All") {
            $pagination = false;
        }
        $count = $room_types->count();
        if ($pagination == true) {
            $room_types = $room_types->paginate($per_page);
        } else {
            $room_types = $room_types->get();
        }
        $data['room_types'] = $room_types;
        $data['count'] = $count;
        $data['page_title'] = 'Room Type List';
        $options = implode(", ", $option_ar);

        $error = "Entered " . $options . " is not valid,
make sure that you are entering valid " . $options . " 
or search by other options";

        if ($request->ajax()) {
            return [
                'html' => view('laralum/accommodation/_list_list-room', ['room_types' => $room_types, 'count' => $count, 'error' => $error, 'search' => $search, 'search_data' => array_merge($matchThese)])->render()
            ];
        }

        return view('laralum.accommodation.list-room-type', $data);
    }

    public function printRoomtype(Request $request)
    {
        Laralum::permissionToAccess('admin.room_types');
        $room_types = Room_Type::select('*')->orderBy('room_types.created_at', 'DESC');

        $search = false;
        $option_ar = [];
        $matchThese = [];

        if ($request->s && $request->s != 'null') {
            $search_data = json_decode($request->s, true);

            if (!empty($search_data['room_type_name'])) {
                $room_types = $room_types->where('name', 'like', '%' . $search_data['room_type_name'] . '%');
                $matchThese['room_type_name'] = $search_data['room_type_name'];
                $search = true;
                $option_ar[] = 'Room type name';
            }

            if (!empty($search_data['room_type_short_name'])) {
                $room_types = $room_types->where('short_name', $search_data['room_type_short_name']);
                $matchThese['room_type_short_name'] = $search_data['room_type_short_name'];
                $search = true;
                $option_ar[] = 'Room type short name';
            }
        }

        $data['page_title'] = 'Room Type List';
        $per_page = $request->get("per_page") ? $request->get("per_page") : 10;
        $pagination = true;
        if ($per_page == "All") {
            $pagination = false;
        }
        $count = $room_types->count();
        if ($pagination == true) {
            $room_types = $room_types->paginate($per_page);
        } else {
            $room_types = $room_types->get();
        }
        $data['room_types'] = $room_types;
        $data['count'] = $count;
        $data['page_title'] = 'Room Type List';
        $options = implode(", ", $option_ar);

        $error = "Entered " . $options . " is not valid,
make sure that you are entering valid " . $options . " 
or search by other options";
        $data['print'] = true;

        # Return the view
        return view('laralum/accommodation/print_room_types', $data);
    }

    public function exportRoomtype(Request $request, $type)
    {
        Laralum::permissionToAccess('admin.room_types');
        $room_types = Room_Type::select('*')->orderBy('room_types.created_at', 'DESC');

        $search = false;
        $option_ar = [];
        $matchThese = [];

        if ($request->s && $request->s != 'null') {
            $search_data = json_decode($request->s, true);

            if (!empty($search_data['room_type_name'])) {
                $room_types = $room_types->where('name', 'like', '%' . $search_data['room_type_name'] . '%');
                $matchThese['room_type_name'] = $search_data['room_type_name'];
                $search = true;
                $option_ar[] = 'Room type name';
            }

            if (!empty($search_data['room_type_short_name'])) {
                $room_types = $room_types->where('short_name', $search_data['room_type_short_name']);
                $matchThese['room_type_short_name'] = $search_data['room_type_short_name'];
                $search = true;
                $option_ar[] = 'Room type short name';
            }
        }

        $data['page_title'] = 'Room Type List';
        $per_page = $request->get("per_page") ? $request->get("per_page") : 10;
        $pagination = true;
        if ($per_page == "All") {
            $pagination = false;
        }
        $count = $room_types->count();
        if ($pagination == true) {
            $room_types = $room_types->paginate($per_page);
        } else {
            $room_types = $room_types->get();
        }
        $data['room_types'] = $room_types;
        $data['count'] = $count;
        $data['page_title'] = 'Room Type List';
        $options = implode(", ", $option_ar);

        $error = "Entered " . $options . " is not valid,
make sure that you are entering valid " . $options . " 
or search by other options";

        $all_ar[] = [
            'Room Type Name',
            'Room Type Short Name',
        ];

        foreach ($room_types as $room_type)
        {
            $all_ar[] = [
                $room_type->name,
                $room_type->short_name,
            ];
        }

        //return $all_ar;
        // Generate and return the spreadsheet
        $excel = \App::make('excel');
        $excel = $excel->create('room_types', function ($excel) use ($all_ar) {

            // Set the spreadsheet title, creator, and description
            $excel->setTitle('Room Types List');

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
            return $pdf->download('room_types.pdf');
        }

        return redirect()->back()->with('error', 'Something went Wrong!!!');
    }

    /**
     * Edit Room type
     * @return View
     */
    public function editRoomtype($room_type_id)
    {
        Laralum::permissionToAccess('admin.room_types');
        //echo '<pre>'; print_r($room_type_id); echo '</pre>'; exit;
        $room_type_arr = [];
        if (Room_Type::where('id', '=', $room_type_id)->exists()) {
            $room_type_arr = Room_Type::find($room_type_id);
        }
        $data['room_type_arr'] = $room_type_arr;
        return view('laralum.accommodation.edit-room-type', $data);
    }

    /**
     * Update Room type
     * @return View
     */
    public function updateRoomtype($room_type_id, Request $request)
    {
        //echo '<pre>'; print_r($request->all()); echo '</pre>'; exit;
        $request_arr['name'] = 'required|max:255';
        /*$request_arr['price'] = 'required|numeric|min:0';*/
        $request_arr['short_name'] = 'required|max:20';

        $validator = Validator::make($request->all(), $request_arr);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors())->withInput();
        }
        $room_type_data = [];
        if (!empty($request['room_type_id'])) {
            $room_type_id = $request['room_type_id'];
            $room_type_data['id'] = $room_type_id;
        }
        if (!empty($request['name'])) {
            $name = $request['name'];
            $room_type_data['name'] = $name;
        }
        if (!empty($request['price'])) {
            $price = $request['price'];
            $room_type_data['price'] = $price;
        }
        $room_type_data['short_name'] = $request['short_name'];
        Room_Type::where('id', $room_type_id)->update($room_type_data);
        return redirect(route('Laralum::room_types'))->with('status', 'Room Type has been updated successfully');
    }

    /**
     * Delete Room type
     * @return View
     */
    public function deleteRoomtype($room_type_id)
    {
        Laralum::permissionToAccess('admin.room_types');
        $room_type = Room_Type::find($room_type_id);

        if($room_type->checkDelete()) {
            return redirect()->route('Laralum::room_types')->with('error', "This room type can't be deleted.");
        }

        $room_type->customDelete();
        return redirect()->route('Laralum::room_types')->with('success', "Room type deleted successfully");
    }

    /**
     * create Room Type
     * @return View
     */
    public function createRoom()
    {
        $room_data = [];
        $room_type_obj = Room_Type::where('status', '1')->get();
        $room_type_name = '';
        $room_type_price = '';
        $room_type_status = '';
        $room_type_data = [];
        if (is_object($room_type_obj) && !empty($room_type_obj)) {
            foreach ($room_type_obj as $room_type_arr) {
                //echo '<pre>'; print_r($room_type_arr); echo '</pre>'; exit;
                $room_type_id = $room_type_arr->id;
                $room_type_name = $room_type_arr->name;

                $room_type_data[$room_type_id] = $room_type_name;
            }
        }

        $building_obj = Building::where('status', '1')->get();
        $building_name = '';
        $building_id = '';
        $building_data = [];
        if (is_object($building_obj) && !empty($building_obj)) {
            foreach ($building_obj as $building_arr) {
                //echo '<pre>'; print_r($building_arr); echo '</pre>'; exit;
                $building_id = $building_arr->id;
                $building_name = $building_arr->name;

                $building_data[$building_id] = $building_name;
            }
        }
        $room_data['room_types'] = $room_type_data;
        $room_data['building'] = $building_data;
        //echo '<pre>'; print_r($building_data); echo '</pre>'; exit;
        return view('laralum.accommodation.create-room', $room_data);
    }

    /**
     * Get building floor
     * @return View
     */
    public function getBuildingFloor(Request $request)
    {
        //echo '<pre>'; print_r($request->all()); echo '</pre>'; exit;
        $number_of_floors = '';
        $building_id = $request['building_id'];
        $floors = Building::getFloorOptions($building_id);
        $selected = $request->floor;

        $html = "";
        foreach ($floors as $no => $name) {

            $selected_html = "";
            if ($selected == $no){
                $selected_html = 'selected';
            }
            $html .= '<option value="' . $no . '" '.$selected_html.' >' . $name . '</option>';
        }

        $room_types = Building::getRoomTypes($building_id);
        $rhtml = "";
        foreach ($room_types as $r_id => $room_type) {
            $rhtml .= '<option value="' . $r_id . '">' . $room_type . '</option>';
        }

        $html = [
            'floors' => $html,
            'room_types' => $rhtml,
        ];
        return $html;
    }

    public function getBuildingRooms(Request $request, $id = null)
    {
        //return $id;
        $gender = $request['gender'];
$gender = null;
        $building_id = $request['building_id'];
        $floor = $request['floor'];
        $check_in_date = $request['check_in_date'];
        $check_out_date = $request['check_out_date'];
        $type = $request['type'];
        $rooms = Building::getRoomOptions($building_id, $floor, $gender);

        if ($request->has('booking_room_id')){
                $room_id = $request['booking_room_id'];
        }
        else{
                $room_id = $id;
        }

        $html = "";
        $member = false;
        if ($request->get('user_type') == 'member') {
            $member = true;
        }
        $booking_room = BookingRoom::find($room_id);

        foreach ($rooms as $room) {
            $selected_room_id = "";
            if ($booking_room != null) {
                if ($booking_room->room_id == $room->id) {
                    $selected_room_id = "selected";
                    $price = $room->room_price;
                    if ($type == Booking::BOOKING_TYPE_SINGLE_BED) {
                        $price = $room->bed_price;
                    }
                    if($member == true){
                        $html .= '<option ' . $selected_room_id . ' data-price="' . $price . '"  value="' . $room->id . '" >' . $room->room_number . '</option>';
                    }
                }
            }



            if (!$room->isBlocked(date("M", strtotime($check_in_date))) && !$room->isBlocked(date("M", strtotime($check_out_date)))) {

                if ($room->checkBooking($check_in_date, $check_out_date, $type, $gender, $request->get("booking_id"), $member, $request->get("member_id"), $room_id)) {
                    $price = $room->room_price;
                    if ($type == Booking::BOOKING_TYPE_SINGLE_BED) {
                        $price = $room->bed_price;
                    }
                    $html .= '<option ' . $selected_room_id . ' data-price="' . $price . '"  value="' . $room->id . '" >' . $room->room_number . '</option>';

                }

            }

        }

        if($html == ""){
            $html = "<option value=''>Room not available. Please check with all building and dormitory</option>";
        }

        $html = [
            'rooms' => $html
        ];

        return $html;
    }

    public function getRoomBeds(Request $request, $id)
    {
        $check_in_date = $request->get('check_in_date');
        $check_out_date = $request->get('check_out_date');
        $gender = $request->get('gender');
        $booking_id = $request->get('booking_id');
        $member = false;
        if ($request->get('user_type') == 'member') {
            $member = true;
        }
        $bed_status_ar = Room::getBedOptionsArray($id, $check_in_date, $check_out_date, $gender, $booking_id, $member);
        return $bed_status_ar;

    }

    public function getRoomServices(Request $request, $id = null)
    {
        $room_id = $request['room_id'];
        $booking = Booking::find($request->booking_id);
        $booking_room = BookingRoom::find($request->booking_room_id);
        if ($booking_room == null) {
            $booking_room = new BookingRoom();
        }

        $room = Room::find($room_id);
        if($room){
            $ext_services = $room->getServices();
        }
        else{
            $ext_services = array();
        }
        
        $data['ext_services'] = $ext_services;
        $data['booking'] = $booking;
        $data['booking_room'] = $booking_room;
        $data['booked_rooms'] = array();
        $count = count($ext_services);
        return [
            'html' => view('laralum.booking._external_services_div', $data)->render(),
            'count' => $count
        ];




        $html = "<option value='0' data-price='0'>Select Service</option>";

        $booking = BookingRoom::find($id);

        foreach ($services as $service) {
            $selected = "";
            if ($booking != null) {
                $userservice = UserExtraService::where([
                    'booking_id' => $booking->id,
                    'service_id' => $service->id])->first();
                if ($userservice != null) {
                    $selected = "selected";
                }
            }

            $html .= '<option ' . $selected . ' data-price= "' . $service->price . '" value="' . $service->id . '">' . $service->name . '</option>';
        }
        $html_ar = [
            'services' => $html,
            'count' => count($services)
        ];
        return $html_ar;

    }

    public function storeRoom(Request $request)
    {
        /*echo '<pre>'; print_r($request->all()); echo '</pre>'; exit;*/
        $room = Room::where([
            'room_number' => $request->get('room_number'),
            'building_id' => $request->get('building_id'),
        ])->first();

        if ($room == null)
            $room = new Room();

        $rules = $room->getRules();
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors())->withInput();
        }

        if (Room::customValidate($request)) {
            return redirect()->back()->withInput()->with('error', Room::customValidate($request));
        }

        if ($room->saveRooms($request)) {
            return redirect('admin/accommodation/rooms')->with('success', 'Successfully Added Rooms');
        }

    }

    public function storeRoomOld(Request $request)
    {
        $request_arr['room_number'] = 'required|max:255';
        $request_arr['room_type'] = 'required|numeric';
        $request_arr['gender'] = 'required|numeric';
        $request_arr['select_building'] = 'required|numeric';
        $request_arr['select_floor_number'] = 'required|numeric';
        /*$request_arr['bed_type'] = 'required';*/
        $request_arr['bed_count'] = 'required|numeric';

        $validator = Validator::make($request->all(), $request_arr);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors())->withInput();
        }
        //echo '<pre>';print_r($request->all());exit;

        $room_arr = [];
        if (!empty($request['room_number'])) {
            $room_arr['room_number'] = $request['room_number'];
        }
        if (!empty($request['room_type'])) {
            $room_arr['room_type_id'] = $request['room_type'];
        }
        if (!empty($request['gender'])) {
            $room_arr['gender'] = $request['gender'];
        }
        if (!empty($request['select_building'])) {
            $room_arr['building_id'] = $request['select_building'];
        }
        if (!empty($request['select_floor_number'])) {
            $room_arr['floor_number'] = $request['select_floor_number'];
        }
        if (!empty($request['bed_type'])) {
            $room_arr['bed_type'] = $request['bed_type'];
        }
        if (!empty($request['bed_count'])) {
            $room_arr['bed_count'] = $request['bed_count'];
        }

        if (!empty($request['bed_price'])) {
            $room_arr['bed_price'] = $request['bed_price'];
        }

        if (!empty($request['room_price'])) {
            $room_arr['room_price'] = $request['room_price'];
        }

        if (!empty($request['is_blocked'])) {
            $room_arr['is_blocked'] = $request['is_blocked'];
        }


        if (!empty($request['services'])) {
            $room_arr['services'] = implode(',', $request['services']);
        }
        //echo '<pre>'; print_r($room_arr); echo '</pre>'; exit;
        Room::create($room_arr);
        $data['page_title'] = 'Add Room';
        return redirect()->route('Laralum::rooms');
    }

    /**
     * List Rooms
     * @return View
     */

    public function listRoom(Request $request)
    {
        Laralum::permissionToAccess('admin.rooms');
        $rooms = Room::select('*')->orderBy('rooms.created_at', 'DESC');

        if ($request->get('building_id')) {
            $rooms = $rooms->where('building_id', $request->get('building_id'));
        }

        $data['page_title'] = 'Room List';
        $per_page = $request->get("per_page") ? $request->get("per_page") : 10;
        $pagination = true;
        if ($per_page == "All") {
            $pagination = false;
        }
        $count = $rooms->count();
        if ($pagination == true) {
            $rooms = $rooms->paginate($per_page);
        } else {
            $rooms = $rooms->get();
        }
        $data['rooms'] = $rooms;
        $data['count'] = $count;
        return view('laralum.accommodation.list-room', $data);
    }


    public function roomAjaxUpdate(Request $request)
    {
        $matchThese = [];
        $matchTheseN = [];
        $search = false;
        $option_ar = [];

        if (!empty($request->has('building_id'))) {
            $option_ar[] = "Building";
            $search = true;
            $matchTheseN['building_id'] = $request->get('building_id');
        }
        if (!empty($request->has('room_price'))) {
            $option_ar[] = "Room Price";
            $search = true;
            $matchThese['room_price'] = $request->get('room_price');
        }
        if (!empty($request->has('bed_price'))) {
            $option_ar[] = "Bed Price";
            $search = true;
            $matchThese['bed_price'] = $request->get('bed_price');
        }
        if (!empty($request->has('bed_count'))) {
            $option_ar[] = "Bed count";
            $search = true;
            $matchThese['bed_count'] = $request->get('bed_count');
        }
        if (!empty($request->has('gender'))) {
            $option_ar[] = "Gender";
            $search = true;
            $matchTheseN['gender'] = $request->get('gender');
        }

        if (!empty($request->has('room_type_id'))) {
            $option_ar[] = "Room type";
            $search = true;
            $matchTheseN['room_type_id'] = $request->get('room_type_id');
        }
        if (!empty($request->has('floor_number'))) {
            $option_ar[] = "Floor number";
            $search = true;
            $matchTheseN['floor_number'] = $request->get('floor_number');
        }

        if (!empty($request->has('room_number'))) {
            $option_ar[] = "Room number";
            $search = true;
            $matchThese['room_number'] = $request->get('room_number');
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

        $models = Room::select('rooms.*')->orderBy('rooms.created_at', 'DESC');

        if ($search == true) {
            $models = Room::select('rooms.*')->where(function ($query) use ($matchThese, $matchTheseN) {
                foreach ($matchThese as $key => $match) {
                    $query->where($key, 'like', "%$match%");
                }
                foreach ($matchTheseN as $key => $match) {
                    $query->where($key, $match);
                }
            })
                ->orderBy('rooms.created_at', 'DESC');
            $count = $models->count();
            $models = $models->get();
        } else {
            $count = $models->count();
            if ($pagination == true) {
                $models = $models->paginate($per_page);
            } else {
                $models = $models->get();
            }
        }

        # Return the view
        return [
            'html' => view('laralum/accommodation/_list-room', ['rooms' => $models, 'count' => $count, 'error' => $error, 'search' => $search, 'search_data' => array_merge($matchThese, $matchTheseN)])->render()
        ];

    }

    public function listRoomOld()
    {
        //$room_obj = Room::all();
        $room_obj = DB::table('rooms as r')
            ->join('buildings AS b', 'b.id', '=', 'r.building_id')
            ->join('room_types AS rt', 'rt.id', '=', 'r.room_type_id')
            ->select('r.id AS room_id',
                'r.room_number AS room_number',
                'rt.name AS room_type',
                'r.gender AS gender',
                'b.name AS building_name',
                'r.floor_number AS floor_number',
                'r.bed_type AS bed_type',
                'r.bed_count AS bed_count'
            )
            ->get();
        $room_id = '';
        $room_number = '';
        $room_type = '';
        $gender = '';
        $building_name = '';
        $floor_number = '';
        $bed_type = '';
        $bed_count = '';
        $room_data = [];
        if (is_object($room_obj) && !empty($room_obj)) {
            foreach ($room_obj as $room_arr) {
                $room_id = $room_arr->room_id;
                $room_number = $room_arr->room_number;
                $room_type = $room_arr->room_type;
                $gender = $room_arr->gender;
                $building_name = $room_arr->building_name;
                $floor_number = $room_arr->floor_number;
                $bed_type = $room_arr->bed_type;
                $bed_count = $room_arr->bed_count;

                $room_data[] = ['room_id' => $room_id,
                    'room_number' => $room_number,
                    'room_type' => $room_type,
                    'gender' => $gender,
                    'building_name' => $building_name,
                    'floor_number' => $floor_number,
                    'bed_type' => $bed_type,
                    'bed_count' => $bed_count
                ];
            }
        }
        $data['page_title'] = 'Room List';
        $data['room_arr'] = $room_data;
        return view('laralum.accommodation.list-room', $data);
    }

    /**
     * Edit Room
     * @return View
     */
    public function editRoom($room_id)
    {
        $room_arr = [];
        $room = Room::findOrFail($room_id);
        $data['room'] = $room;
        return view('laralum.accommodation.edit-room', $data);
    }

    /**
     * Update Room type
     * @return View
     */
    public function updateRoom($room_id, Request $request)
    {
        //echo '<pre>'; print_r($room_id); echo '</pre>'; exit;

        $request_arr['room_number'] = 'required|max:255';
        $request_arr['room_type'] = 'required|numeric';
        $request_arr['gender'] = 'required|numeric';
        $request_arr['select_building'] = 'required|numeric';
        $request_arr['select_floor_number'] = 'required|numeric';
        /*$request_arr['bed_type'] = 'required';*/
        $request_arr['bed_count'] = 'required|numeric';

        $validator = Validator::make($request->all(), $request_arr);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors())->withInput();
        }
        //echo '<pre>';print_r($request->all());exit;

        $room_arr = [];
        if (!empty($request['room_number'])) {
            $room_arr['room_number'] = $request['room_number'];
        }
        if (!empty($request['room_type'])) {
            $room_arr['room_type_id'] = $request['room_type'];
        }
        if (!empty($request['gender'])) {
            $room_arr['gender'] = $request['gender'];
        }
        if (!empty($request['select_building'])) {
            $room_arr['building_id'] = $request['select_building'];
        }
        if (!empty($request['select_floor_number'])) {
            $room_arr['floor_number'] = $request['select_floor_number'];
        }
        if (!empty($request['bed_type'])) {
            $room_arr['bed_type'] = $request['bed_type'];
        }
        if (!empty($request['bed_count'])) {
            $room_arr['bed_count'] = $request['bed_count'];
        }

        if (!empty($request['is_blocked'])) {
            $room_arr['is_blocked'] = $request['is_blocked'];
        }

        if (!empty($request['bed_price'])) {
            $room_arr['bed_price'] = $request['bed_price'];
        }
        if (!empty($request['room_price'])) {
            $room_arr['room_price'] = $request['room_price'];
        }

        if (!empty($request['services'])) {
            $room_arr['services'] = implode(',', $request['services']);
        }else{
$room_arr['services'] = '';
}
        try {
            Room::where('id', $room_id)->update($room_arr);
        } catch (\Exception $e) {
            return redirect(route('Laralum::rooms'))->with('error', 'Something went wrong!!!' . $e->getMessage());
        }
        return redirect(route('Laralum::rooms'))->with('success', 'Room has been updated successfully');
    }

    /**
     * Delete Room type
     * @return View
     */
    public function deleteRoom($room_id)
    {
        $room = Room::where('id', $room_id)->first();
        if($room->checkDelete()) {
            return redirect()->route('Laralum::rooms')->with('error', "Room can't be deleted as there exists some bookings for this room");
        }
        $room->customDelete();
        return redirect()->route('Laralum::rooms')->with('success', 'Room has been deleted successfully');
    }

    public function editRoomServices($room_id)
    {
        $room = Room::findOrFail($room_id);
        $data['room'] = $room;
        return view('laralum.accommodation.edit-room-services', $data);
    }

    public function storeRoomServices(Request $request, $room_id)
    {
//echo '<pre>'; print_r($request->all());exit;
        $room = Room::findOrFail($room_id);
        $data['room'] = $room;
        $room_arr = [];
        if ($request->get('services')) {
            $room_arr['services'] = is_array($request->get('services')) ? implode(',', $request['services']) : "";
        }else{
$room_arr['services'] = "";
}

        Room::where('id', $room_id)->update($room_arr);

        return redirect(route('Laralum::rooms'))->with('status', 'Room has been updated successfully');
    }

    /**
     * create External Services
     * @return View
     */
    public function createExternalServices()
    {
        return view('laralum.accommodation.create-external-service');
    }

    /**
     * Store external ervice
     * @return View
     */
    public function storeExternalServices(Request $request)
    {
        $request_arr['name'] = 'required|max:255';

        $validator = Validator::make($request->all(), $request_arr);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors())->withInput();
        }

        $ext_service_arr = [];
        if (!empty($request['name'])) {
            $ext_service_arr['name'] = $request['name'];
        }
        if (!empty($request['desc'])) {
            $ext_service_arr['description'] = $request['desc'];
        }
        if (!empty($request['price'])) {
            $ext_service_arr['price'] = $request['price'];
        }
        /*
                if (!empty($request['room_id'])) {
                    $ext_service_arr['room_id'] = $request['room_id'];
                }*/

        ExternalService::create($ext_service_arr);
        return redirect()->route('Laralum::external_services');
    }

    /**
     * List Room type
     * @return View
     */

    public function listExternalServices(Request $request)
    {
        Laralum::permissionToAccess('admin.room_types');
        $models = ExternalService::select('*')->orderBy('created_at', 'DESC');

        $search = false;
        $option_ar = [];
        $matchThese = [];

        if ($request->get('name')) {
            $models = $models->where('name', 'like', '%'.$request->get('name').'%');
            $matchThese['name'] = $request->get('name');
            $search = true;
            $option_ar[] = 'Name';
        }

        if ($request->get('price')) {
            $models = $models->where('price', $request->get('price'));
            $matchThese['price'] = $request->get('price');
            $search = true;
            $option_ar[] = 'Price';
        }

        $data['page_title'] = 'External Services List';
        $per_page = $request->get("per_page") ? $request->get("per_page") : 10;
        $pagination = true;
        if ($per_page == "All") {
            $pagination = false;
        }
        $count = $models->count();

        if ($pagination == true) {
            $models = $models->paginate($per_page);
        } else {
            $models = $models->get();
        }

        $data['models'] = $models;
        $data['count'] = $count;
        $data['page_title'] = 'External Services';
        $options = implode(", ", $option_ar);

        $error = "Entered " . $options . " is not valid,
make sure that you are entering valid " . $options . " 
or search by other options";

        if ($request->ajax()) {
            return [
                'html' => view('laralum/accommodation/_list-external-services', ['models' => $models, 'count' => $count, 'error' => $error, 'search' => $search, 'search_data' => array_merge($matchThese)])->render()
            ];
        }

        return view('laralum.accommodation.list-external-services', $data);
    }

    public function printExternalServices(Request $request)
    {
        Laralum::permissionToAccess('admin.room_types');
        $models = ExternalService::select('*')->orderBy('created_at', 'DESC');

        $search = false;
        $option_ar = [];
        $matchThese = [];


        if ($request->s && $request->s != 'null') {
            $search_data = json_decode($request->s, true);

            if (!empty($search_data['name'])) {
                $models = $models->where('name', 'like', '%' . $search_data['name'] . '%');
                $matchThese['name'] = $search_data['name'];
                $search = true;
                $option_ar[] = 'Name';
            }

            if (!empty($search_data['price'])) {
                $models = $models->where('price', $search_data['price']);
                $matchThese['price'] = $search_data['price'];
                $search = true;
                $option_ar[] = 'Price';
            }
        }

        $data['page_title'] = 'External Services List';
        $per_page = $request->get("per_page") ? $request->get("per_page") : 10;
        $pagination = true;
        if ($per_page == "All") {
            $pagination = false;
        }
        $count = $models->count();

        if ($pagination == true) {
            $models = $models->paginate($per_page);
        } else {
            $models = $models->get();
        }

        $data['models'] = $models;
        $data['count'] = $count;
        $data['page_title'] = 'External Services';
        $options = implode(", ", $option_ar);
        $data['print'] = true;

        $error = "Entered " . $options . " is not valid,
make sure that you are entering valid " . $options . " 
or search by other options";

        # Return the view
        return view('laralum/accommodation/print_list_external_services', $data);
    }

    public function exportExternalServices(Request $request, $type)
    {
        Laralum::permissionToAccess('admin.room_types');
        $models = ExternalService::select('*')->orderBy('created_at', 'DESC');

        $search = false;
        $option_ar = [];
        $matchThese = [];


        if ($request->s && $request->s != 'null') {
            $search_data = json_decode($request->s, true);

            if (!empty($search_data['name'])) {
                $models = $models->where('name', 'like', '%' . $search_data['name'] . '%');
                $matchThese['name'] = $search_data['name'];
                $search = true;
                $option_ar[] = 'Name';
            }

            if (!empty($search_data['price'])) {
                $models = $models->where('price', $search_data['price']);
                $matchThese['price'] = $search_data['price'];
                $search = true;
                $option_ar[] = 'Price';
            }
        }

        $data['page_title'] = 'External Services List';
        $per_page = $request->get("per_page") ? $request->get("per_page") : 10;
        $pagination = true;
        if ($per_page == "All") {
            $pagination = false;
        }
        $count = $models->count();

        if ($pagination == true) {
            $models = $models->paginate($per_page);
        } else {
            $models = $models->get();
        }

        $data['models'] = $models;
        $data['count'] = $count;
        $data['page_title'] = 'External Services';
        $options = implode(", ", $option_ar);
        $data['print'] = true;

        $error = "Entered " . $options . " is not valid,
make sure that you are entering valid " . $options . " 
or search by other options";


        $all_ar[] = [
            'Name',
            'Price',
            'Description',
        ];

        foreach ($models as $model)
        {
            $all_ar[] = [
                $model->name,
                $model->price,
                $model->description,
            ];
        }

        // Generate and return the spreadsheet
        $excel = \App::make('excel');
        $excel = $excel->create('external_services', function ($excel) use ($all_ar) {

            // Set the spreadsheet title, creator, and description
            $excel->setTitle('External Services List');

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
            return $pdf->download('external_services.pdf');
        }

        return redirect()->back()->with('error', 'Something went Wrong!!!');
    }

    /**
     * Delete Exrenal Services
     * @return View
     */
    public function deleteExternalServices($service_id)
    {
        $service = ExternalService::find($service_id);

        if($service->checkDelete()) {
            return redirect()->route('Laralum::external_services')->with('error', "Service can't be deleted as there exists some bookings for this service");
        }
        $service->customDelete();

        return redirect()->route('Laralum::external_services')->with('success', 'External service has been deleted successfully');
    }

    /**
     * Edit extermnal service
     * @return View
     */
    public function editExternalServices($service_id)
    {
        $service_arr = [];
        if (ExternalService::where('id', '=', $service_id)->exists()) {
            $service_obj = ExternalService::find($service_id);
            //echo '<pre>'; print_r($room_data); echo '</pre>'; exit;
            if (is_object($service_obj) && !empty($service_obj)) {
                $service_arr['id'] = $service_obj->id;
                $service_arr['name'] = $service_obj->name;
                $service_arr['price'] = $service_obj->price;
                /*$service_arr['room_id'] = $service_obj->room_id;*/
                $service_arr['desc'] = $service_obj->description;
            }
        }
        $data['service_arr'] = $service_arr;
        return view('laralum.accommodation.edit-external-services', $data);
    }

    /**
     * Update external service
     * @return View
     */
    public function updateExternalServices($service_id, Request $request)
    {
        $request_arr['name'] = 'required|max:255';

        $validator = Validator::make($request->all(), $request_arr);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors())->withInput();
        }
        //echo '<pre>';print_r($request->all());exit;

        $service_arr = [];
        if (!empty($request['name'])) {
            $service_arr['name'] = $request['name'];
        }
        if (!empty($request['desc'])) {
            $service_arr['description'] = $request['desc'];
        }
        if (!empty($request['price'])) {
            $service_arr['price'] = $request['price'];
        }
        /*
                if (!empty($request['room_id'])) {
                    $service_arr['room_id'] = $request['room_id'];
                }*/
        if (!empty($service_arr)) {
            ExternalService::find($service_id)->update($service_arr);
        }
        return redirect(route('Laralum::external_services'))->with('status', 'Service has been updated successfully');
    }

    public function blockRooms()
    {
        $blockedrooms = BlockedRoom::all();
        $room_id = [];
        foreach ($blockedrooms as $blockedroom) {
            $room_id[] = $blockedroom->room_id;
        }

        $room_id = implode(',', $room_id);
        return view('laralum.accommodation.block_rooms', compact('room_id'));
    }


    public function blockRoomsStore(Request $request)
    {
        $rules = BlockedRoom::rules();
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator->errors())->withInput();
        }

        $room_ar = explode(',', $request->get("room_id"));
        BlockedRoom::deleteOld();

        foreach ($room_ar as $room) {
            $blocked_room = BlockedRoom::where([
                'room_id' => $room
            ])->first();

            if ($request->get('blocked_month_' . $room) || $request->get("blocked_yearly_" . $room)) {
                if ($blocked_room == null) {
                    $blocked_room = new BlockedRoom();
                }
                $blocked_room->room_id = $room;
                if ($request->get('blocked_yearly_' . $room) == BlockedRoom::BLOCK_YEAR) {
                    $blocked_room->is_yearly = BlockedRoom::BLOCK_YEAR;
                }
                $blocked_room->blocked_months = is_array($request->get('blocked_month_' . $room)) ? implode(',', $request->get('blocked_month_' . $room)) : "";
                $blocked_room->created_by = \Auth::user()->id;
                $blocked_room->save();
            }
        }

        return redirect()->back()->with('success', 'Successfully Marked as Blocked');
    }

    public function accommodationStatus(Request $request)
    {
        Laralum::permissionToAccess('admin.accommodation.chart');
        $default_date = $request->get('select_date', date('Y-m-d'));
        $default_month_year = $request->get('select_month_year') ? $request->get('select_month_year') : date('m-Y');
        $select_month = $request->get('select_month') ? $request->get('select_month') : date("M");
        $select_year = $request->get('select_year') ? $request->get('select_year') : date("Y");
        $default_month_year = $select_month . "-" . $select_year;
        $overall = Booking::overall($default_date);
        $month_wise_arr = Booking::guestBookingChartmw($request, $default_month_year);
        $data['rooms_status_arr'] = $month_wise_arr['rooms_status_arr'];
        $data['accordian_status_mw'] = $month_wise_arr['accordian_status_mw'];
        $room_wise_arr = Booking::guestBookingChart($request, $default_date);
        $data = array_merge($data, $room_wise_arr);

         $default_date = date("d-m-Y", strtotime($default_date));
        $data['default_date'] = $default_date;
        $filter_date = false;
        if ($request->get('filter_date')) {
            $filter_date = true;
        }

        $filter_month = false;
        if ($request->get('filter_month')) {
            $filter_month = true;
        }
        $data['filter_date'] = $filter_date;
        $data['filter_month'] = $filter_month;
        $data['overall'] = $overall;
        $data['default_month_year'] = $default_month_year;
        $data['select_month'] = $select_month;
        $data['select_year'] = $select_year;
        //dd($data['rooms_status_arr']['rooms_data']['roomm-11']);
        //return $room_data = Booking::getBookingsChart(11, old('select_date', $default_date));

        return view('laralum.accommodation.room_status', $data);
    }

    public function getBookedRoomInfo($room_id, $bed)
    {
        $booked_rooms = BookingRoom::where('room_id', $room_id)->where(function ($query) {
            $query->whereNotIn('status', [BookingRoom::STATUS_DISCHARGED])->orWhereNull('status');
        })->get();

        $info = [];
        $booking = new Booking();
        foreach ($booked_rooms as $booked_room) {
            $info = $booked_room;
            $booking = $booked_room->booking;
            if ($booked_room->type == BookingRoom::BOOKING_TYPE_SINGLE_BED) {
                if ($booked_room->bed_number == $bed) {
                    break;
                }
            }
        }
        $discharge = false;

        $bed_booking = true;
        return view('laralum.booking.get-booking-info', compact('bed_booking', 'info', 'booking', 'discharge'));

    }

    public function getFullBookedRoomInfo($room_id)
    {
        $booking_rooms = BookingRoom::where('room_id', $room_id)->where(function ($query) {
            $query->whereNotIn('status', [BookingRoom::STATUS_DISCHARGED])->orWhereNull('status');
        })->get();
        $booking = new Booking;
        $discharge = false;
        //dd($booking_rooms[0]->userProfile);
        return view('laralum.booking.get-booking-info', compact('booking_rooms', 'booking', 'discharge'));

    }


    public function printListRooms(Request $request)
    {
        $matchThese = [];
        $matchTheseN = [];
        $search = false;
        $option_ar = [];

        if ($request->s && $request->s != 'null') {
            $search_data = json_decode($request->s, true);

            if (!empty($search_data['building_id'])) {
                $option_ar[] = "Building";
                $search = true;
                $matchTheseN['building_id'] = $search_data['building_id'];
            }
            if (!empty($search_data['room_price'])) {
                $option_ar[] = "Room Price";
                $search = true;
                $matchThese['room_price'] = $search_data['room_price'];
            }
            if (!empty($search_data['bed_price'])) {
                $option_ar[] = "Bed Price";
                $search = true;
                $matchThese['bed_price'] = $search_data['bed_price'];
            }
            if (!empty($search_data['bed_count'])) {
                $option_ar[] = "Bed count";
                $search = true;
                $matchThese['bed_count'] = $search_data['bed_count'];
            }
            if (!empty($search_data['gender'])) {
                $option_ar[] = "Gender";
                $search = true;
                $matchTheseN['gender'] = $search_data['gender'];
            }

            if (!empty($search_data['room_type_id'])) {
                $option_ar[] = "Room type";
                $search = true;
                $matchTheseN['room_type_id'] = $search_data['room_type_id'];
            }
            if (!empty($search_data['floor_number'])) {
                $option_ar[] = "Floor number";
                $search = true;
                $matchTheseN['floor_number'] = $search_data['floor_number'];
            }

            if (!empty($search_data['room_number'])) {
                $option_ar[] = "Room number";
                $search = true;
                $matchThese['room_number'] = $search_data['room_number'];
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

        $models = Room::select('rooms.*')->orderBy('rooms.created_at', 'DESC');

        if ($search == true) {
            $models = Room::select('rooms.*')->where(function ($query) use ($matchThese, $matchTheseN) {
                foreach ($matchThese as $key => $match) {
                    $query->where($key, 'like', "%$match%");
                }
                foreach ($matchTheseN as $key => $match) {
                    $query->where($key, $match);
                }
            })
                ->orderBy('rooms.created_at', 'DESC');
            $count = $models->count();
            $models = $models->get();
        } else {
            $count = $models->count();
            if ($pagination == true) {
                $models = $models->paginate($per_page);
            } else {
                $models = $models->get();
            }
        }

        $data['rooms'] = $models;
        $data['count'] = $count;
        $data['print'] = true;
        return view('laralum.accommodation.print_list_rooms', $data);
    }

    public function exportListRooms(Request $request, $type)
    {
        $matchThese = [];
        $matchTheseN = [];
        $search = false;
        $option_ar = [];

        if ($request->s && $request->s != 'null') {
            $search_data = json_decode($request->s, true);

            if (!empty($search_data['building_id'])) {
                $option_ar[] = "Building";
                $search = true;
                $matchTheseN['building_id'] = $search_data['building_id'];
            }
            if (!empty($search_data['room_price'])) {
                $option_ar[] = "Room Price";
                $search = true;
                $matchThese['room_price'] = $search_data['room_price'];
            }
            if (!empty($search_data['bed_price'])) {
                $option_ar[] = "Bed Price";
                $search = true;
                $matchThese['bed_price'] = $search_data['bed_price'];
            }
            if (!empty($search_data['bed_count'])) {
                $option_ar[] = "Bed count";
                $search = true;
                $matchThese['bed_count'] = $search_data['bed_count'];
            }
            if (!empty($search_data['gender'])) {
                $option_ar[] = "Gender";
                $search = true;
                $matchTheseN['gender'] = $search_data['gender'];
            }

            if (!empty($search_data['room_type_id'])) {
                $option_ar[] = "Room type";
                $search = true;
                $matchTheseN['room_type_id'] = $search_data['room_type_id'];
            }
            if (!empty($search_data['floor_number'])) {
                $option_ar[] = "Floor number";
                $search = true;
                $matchTheseN['floor_number'] = $search_data['floor_number'];
            }

            if (!empty($search_data['room_number'])) {
                $option_ar[] = "Room number";
                $search = true;
                $matchThese['room_number'] = $search_data['room_number'];
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

        $models = Room::select('rooms.*')->orderBy('rooms.created_at', 'DESC');

        if ($search == true) {
            $models = Room::select('rooms.*')->where(function ($query) use ($matchThese, $matchTheseN) {
                foreach ($matchThese as $key => $match) {
                    $query->where($key, 'like', "%$match%");
                }
                foreach ($matchTheseN as $key => $match) {
                    $query->where($key, $match);
                }
            })
                ->orderBy('rooms.created_at', 'DESC');
            $count = $models->count();
            $models = $models->get();
        } else {
            $count = $models->count();
            if ($pagination == true) {
                $models = $models->paginate($per_page);
            } else {
                $models = $models->get();
            }
        }


        $all_ar[] = [
            'Name',
            'Floor Number',
            'Room Number',
            'Room Type',
            'Gender',
            'Bed Count',
            'Bed Price',
            'Room Price',
        ];

        foreach ($models as $room)
        {
            $all_ar[] = [
                $room->building->name,
                \App\Building::getFloorName($room->floor_number),
                $room->room_number,
                $room->roomType->name,
                $room->getGenderOptions($room->gender),
                $room->bed_count,
                $room->bed_price,
                $room->room_price
            ];
        }

        // Generate and return the spreadsheet
        $excel = \App::make('excel');
        $excel = $excel->create('rooms', function ($excel) use ($all_ar) {

            // Set the spreadsheet title, creator, and description
            $excel->setTitle('Rooms List');

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
            $pdf = \PDF::loadView('booking.pdf', array('data' => $all_ar));
            return $pdf->download('rooms.pdf');
        }

        return redirect()->back()->with('error', 'Something went Wrong!!!');
    }

}

