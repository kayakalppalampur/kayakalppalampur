<?php

namespace App\Http\Controllers\Laralum;

use App\DiscountOffer;
use App\Settings;
use App\Transaction;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use PDF;


class DiscountController extends Controller
{
    //
    /**
     * discount_offer listing
     * @return View
     */
    public function index(Request $request)
    {
        Laralum::permissionToAccess('admin.admin_settings.discount_offers');
        $discount_offers = DiscountOffer::select('*')->where('status', DiscountOffer::STATUS_ACTIVE)->orderBy('discount_offers.created_at', 'DESC');

        if (!\Auth::user()->isAdmin()) {
            $discount_offers = $discount_offers->where('created_by', \Auth::user()->id);
        }
        $per_page = $request->get("per_page") ? $request->get("per_page") : 10;
        $pagination = true;
        if ($per_page == "All") {
            $pagination = false;
        }
        $count = $discount_offers->count();
        if ($pagination == true) {
            $discount_offers = $discount_offers->paginate($per_page);
        } else {
            $discount_offers = $discount_offers->get();
        }


        return view('laralum.discount_offer.index', compact('discount_offers', 'count'));
    }

    /**
     * discount_offer details with replies
     * @return View
     */
    public function view($id)
    {
        Laralum::permissionToAccess('admin.admin_settings.discount_offers');
        $discount_offer = DiscountOffer::find($id);

        return view('laralum.discount_offer.view', compact('discount_offer'));
    }

    public function edit($id)
    {
        # Check permissions
        Laralum::permissionToAccess('admin.admin_settings.discount_offers');

        # Find the discount_offer
        $row = DiscountOffer::findOrFail($id);
        \Session::put('discount_offer_id', $id);

        # Get all the data
        $data_index = 'discount_offers';
        require('Data/Edit/Get.php');

        # Return the view
        return view('laralum/discount_offer/edit', [
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
        Laralum::permissionToAccess('admin.admin_settings.discount_offers');

        # Find the row
        $discount_offer = DiscountOffer::findOrFail($id);

        try {

            if ($discount_offer->setData($request)) {
                $discount_offer->save();
                return redirect()->route('Laralum::discount_offers')->with('success', 'DiscountOffer edited successfully.');
            } else {
                return redirect()->route('Laralum::discount_offers')->with('error', 'Something went wrong. Please try again later.');
            }

        } catch (\Exception $e) {

            \Log::error("Failed to add the discount_offer, possible causes: " . $e->getMessage());
            //print_r($e->getMessage());exit;
            return redirect()->route('Laralum::discount_offers')->with('error', 'Something went wrong. Please try again later.');
        }

    }

    /**
     * add discount_offer for the staff
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        Laralum::permissionToAccess('admin.admin_settings.discount_offers');
        # Get all the data
        $data_index = 'discount_offers';
        require('Data/Create/Get.php');

        return view('laralum.discount_offer.create',
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
        Laralum::permissionToAccess('admin.admin_settings.discount_offers');
        $rules = DiscountOffer::getRules(true);

        $validator = \Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput()->with(['error' => 'Please check the errors below and try again.']);
        }

        try {
            $discount_offer = new DiscountOffer();

            if ($discount_offer->setData($request)) {
                ;
                $discount_offer->save();
                return redirect()->route('Laralum::discount_offers')->with('success', 'Discount Offer added successfully.');
            } else {
                return redirect()->route('Laralum::discount_offers')->with('error', 'Something went wrong. Please try again later.');
            }

        } catch (\Exception $e) {

            \Log::error("Failed to add the discount_offer, possible causes: " . $e->getMessage());
            //print_r($e->getMessage());exit;
            return redirect()->route('Laralum::discount_offers')->with('error', 'Something went wrong. Please try again later.');
        }

    }


    public function destroy($id)
    {   # Check permissions
        Laralum::permissionToAccess('admin.admin_settings.discount_offers');

        # Select DiscountOffer
        $discount_offer = DiscountOffer::findOrFail($id);

        # Check DiscountOffer Users
        /*if ($discount_offer->isAllowed()) {*/
        # Delete DiscountOffer
        $transaction = Transaction::where('discount_id', $discount_offer->id)->first();
        if ($transaction != null) {
            $discount_offer->update([
                'status' => DiscountOffer::STATUS_INACTIVE
            ]);
            # Redirect the admin
        } else {
            $discount_offer->delete();
        }
        return redirect()->route('Laralum::discount_offers')->with('success', 'Successfully Deleted Discount Offer.');
        /*}*/

        return redirect()->route('Laralum::discount_offers')->with('error', trans('laralum.msg_discount_offer_delete_not_allowed'));

    }

    public function getDiscountCode(Request $request)
    {
        $result = [
            'amount' => $request->get("price"),
            'discount' => 0,
            'id' => 0
        ];
        if ($request->get('code') != null) {
            $offer = DiscountOffer::where('code', $request->get('code'))->where('status', DiscountOffer::STATUS_ACTIVE)->first();
            if ($offer != null) {
                $discount = 0;
                if ($offer->type == DiscountOffer::TYPE_FLAT) {
                    $discount = $offer->discount_value;
                } else {
                    $discount = $offer->discount_value * $request->get("price") / 100;
                }
                $amount = $request->get("price") - $discount;
                $result = [
                    'discount' => $discount,
                    'amount' => $amount,
                    'id' => $offer->id
                ];
            }
        }
        return $result;
    }

    public function ajaxUpdate(Request $request)
    {
        Laralum::permissionToAccess('admin.admin_settings.discount_offers');
        $per_page = $request->get("per_page") ? $request->get("per_page") : 10;
        $pagination = true;
        if ($per_page == "All") {
            $pagination = false;
        }
        $matchThese = [];
        $search = false;

        $option_ar = [];

        if ($request->get('code')) {
            $search = true;
            $option_ar[] = "Code";
            $matchThese['code'] = $request->get('code');
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

        $discount_offers = DiscountOffer::select('discount_offers.*')->where('status', DiscountOffer::STATUS_ACTIVE)->orderBy('discount_offers.created_at', 'DESC');

        if ($search == true) {
            $discount_offers = DiscountOffer::select('discount_offers.*')->where('status', DiscountOffer::STATUS_ACTIVE)->orderBy('discount_offers.created_at', 'DESC')->where(function ($query) use ($matchThese) {
                foreach ($matchThese as $key => $match) {
                    $query->where($key, 'like', "%$match%");
                }
            });
            $count = $discount_offers->count();
            $discount_offers = $discount_offers->get();
        } else {
            $count = $discount_offers->count();
            if ($pagination == true) {
                $discount_offers = $discount_offers->paginate($per_page);
            } else {
                $discount_offers = $discount_offers->get();
            }
        }
        /*echo '<pre>'; print_r($matchThese['role_id']);exit;*/
        # Return the view
        return [
            'html' => view('laralum/discount_offer/_list', ['discount_offers' => $discount_offers, 'count' => $count, 'error' => $error, 'search' => $search, 'search_data' => $matchThese])->render()
        ];
    }

    public function printDiscountOffers(Request $request)
    {

        Laralum::permissionToAccess('admin.admin_settings.discount_offers');
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

            if (!empty($search_data['code'])) {
                $search = true;
                $option_ar[] = "Code";
                $matchThese['code'] = $search_data['code'];
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

        $discount_offers = DiscountOffer::select('discount_offers.*')->where('status', DiscountOffer::STATUS_ACTIVE)->orderBy('discount_offers.created_at', 'DESC');

        if ($search == true) {
            $discount_offers = DiscountOffer::select('discount_offers.*')->where('status', DiscountOffer::STATUS_ACTIVE)->orderBy('discount_offers.created_at', 'DESC')->where(function ($query) use ($matchThese) {
                foreach ($matchThese as $key => $match) {
                    $query->where($key, 'like', "%$match%");
                }
            });
            $count = $discount_offers->count();
            $discount_offers = $discount_offers->get();
        } else {
            $count = $discount_offers->count();
            if ($pagination == true) {
                $discount_offers = $discount_offers->paginate($per_page);
            } else {
                $discount_offers = $discount_offers->get();
            }
        }

        return view('laralum/discount_offer/print_discount_offer', [
            'discount_offers' => $discount_offers,
            'count' => $count,
            'print' => true

        ]);
    }

    public function exportDiscountOffers(Request $request, $type)
    {
        Laralum::permissionToAccess('admin.admin_settings.discount_offers');
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

            if (!empty($search_data['code'])) {
                $search = true;
                $option_ar[] = "Code";
                $matchThese['code'] = $search_data['code'];
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

        $discount_offers = DiscountOffer::select('discount_offers.*')->where('status', DiscountOffer::STATUS_ACTIVE)->orderBy('discount_offers.created_at', 'DESC');

        if ($search == true) {
            $discount_offers = DiscountOffer::select('discount_offers.*')->where('status', DiscountOffer::STATUS_ACTIVE)->orderBy('discount_offers.created_at', 'DESC')->where(function ($query) use ($matchThese) {
                foreach ($matchThese as $key => $match) {
                    $query->where($key, 'like', "%$match%");
                }
            });
            $count = $discount_offers->count();
            $discount_offers = $discount_offers->get();
        } else {
            $count = $discount_offers->count();
            if ($pagination == true) {
                $discount_offers = $discount_offers->paginate($per_page);
            } else {
                $discount_offers = $discount_offers->get();
            }
        }


        $all_ar[] = [
            'Code',
            'Type',
            'Discount Value',
        ];

        foreach ($discount_offers as $discount_offer)
        {
            $all_ar[] = [
                $discount_offer->code,
                $discount_offer->getTypeOptions($discount_offer->type),
                $discount_offer->discount_value,
            ];
        }


        // Generate and return the spreadsheet
        $excel = \App::make('excel');
        $excel = $excel->create('Discount Offers', function ($excel) use ($all_ar) {

            // Set the spreadsheet title, creator, and description
            $excel->setTitle('Discount Offers');

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
            return $pdf->download('discount_offers_list.pdf');
        }

        return redirect()->back()->with('error', 'Something went Wrong!!!');
    }
}
