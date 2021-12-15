<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;

class Settings extends Model
{
    const BASIC_PRICE = 2000;
    const EXPORT_CSV = 0;
    const EXPORT_PDF = 1;
    const EXPORT_EXCEL = 2;

    protected $table = "settings";


    public static function saveUploadedFile($image_file, $old_image = NULL, $folder_name = 'uploads')
    {
        $path = '';

        if (!empty($image_file)) {
            $path = $image_file->store($folder_name);

            /*  if(file_exists(storage_path() . '/app/'.$path)) {
                  if ($old_image != null) {
                      $old_path = storage_path() . '/app/' . $old_image;
                      if (file_exists($old_path)) {
                          @unlink($old_path);
                      }
                  }
              }*/
        } else {
            $path = $old_image;
        }

        return $path;
    }

    public static function getImageUrl($file, $file_name = "test")
    {

        return url('images/' . base64_encode($file . '---' . $file_name));
    }

    public static function getDownloadUrl($file, $file_name = "")
    {
        return url('image/download/' . base64_encode($file . '---' . $file_name));
    }

    public static function createDateRangeArray($strDateFrom, $strDateTo)
    {
        $dates = array();
        $current = strtotime($strDateFrom);
        $last = strtotime($strDateTo);
        $output_format = 'Y-m-d';
        $step = '+1 day';

        while ($current <= $last) {

            $dates[] = date($output_format, $current);
            $current = strtotime($step, $current);
        }

        return $dates;
    }

    public static function paginate($array, $perPage, $pageStart = 1)
    {

        $offset = ($pageStart * $perPage) - $perPage;

        return new Paginator(array_slice($array, $offset, $perPage, true), $perPage, $pageStart,
            [
                'path' => Paginator::resolveCurrentPath(),
            ]
        );
    }

    public static function noOfDays($start, $end)
    {
        $now = strtotime($start);
        $your_date = strtotime($end);
        $datediff = $your_date - $now;
        return floor($datediff / (60 * 60 * 24));
    }

    public static function perPageOptions($count = 0)
    {

        $current_url = \Request::path();
        $append = "?";
        if (isset($_GET['page'])) {
            $append = "?page=" . $_GET['page'] . "&";

        }
        $links = link_to($current_url . $append . "per_page=10", "10");
        if ($count != 0) {
            if ($count <= 10) {
                $links = "";
            }

            if ($count > 10) {
                if ($count <= 30) {
                    $links .= " | " . link_to($current_url . $append . "per_page=All", "All");
                } else {
                    $links .= " | " . link_to($current_url . $append . "per_page=30", "30");
                    if ($count <= 50) {
                        $links .= " | " . link_to($current_url . $append . "per_page=All", "All");
                    } else {
                        $links .= " | " . link_to($current_url . $append . "per_page=50", "50");
                        if ($count <= 100) {
                            $links .= " | " . link_to($current_url . $append . "per_page=All", "All");
                        } else {
                            $links .= " | " . link_to($current_url . $append . "per_page=100", "100");
                            $links .= " | " . link_to($current_url . $append . "per_page=All", "All");
                        }
                    }
                }
            }
        } else {
            $links = link_to($current_url . $append . "per_page=10", "10") . " | " . link_to($current_url . $append . "per_page=30", "30") . " | " . link_to($current_url . $append . "per_page=50", "50") . " | " . link_to($current_url . $append . "per_page=100", "100") . " | " . link_to($current_url . $append . "per_page=All", "All");
        }
        return $links;
    }

    public static function convertToHoursMins($time, $format = '%02d:%02d')
    {
        if ($time < 1) {
            return;
        }
        $hours = floor($time / 60);
        $minutes = ($time % 60);
        if ($hours == 0) {
            return $minutes . ' m';
        }
        return sprintf($format, $hours, $minutes);
    }

    public static function removeFile($file)
    {
        if (file_exists(storage_path() . '/app/' . $file)) {
            @unlink(storage_path() . '/app/' . $file);
        }

        return "";
    }

    public static function months()
    {
        return [
            "Jan",
            "Feb",
            "Mar",
            "Apr",
            "May",
            "Jun",
            "Jul",
            "Aug",
            "Sep",
            "Oct",
            "Nov",
            "Dec"
        ];
    }

    public static function years()
    {
        $years = [];
        for ($i = date("Y"); $i < date("Y", strtotime("+15 years")); $i++) {
            $years[] = $i;
        }
        return $years;
    }

    public static function checkHospitalSettingsUrl($url = null)
    {
        if (\Request::route()->getName() == 'Laralum::admin.hospital_info' || \Request::route()->getName() == 'Laralum::admin.tax_details' || \Request::route()->getName() == 'Laralum::admin.consultation_charges' || \Request::route()->getName() == 'Laralum::admin.hospital_bank_account') {
            return true;
        }
        return false;
    }

    public static function getDisabledMonths()
    {
        $disabled_month = [];
        for ($i = 1; $i <= 12; $i++) {
            if ($i < date("m")) {
                $disabled_month[] = date("M", mktime(0, 0, 0, $i, 10));
            }
        }
        return implode(',', $disabled_month);
    }

    public static function getActiveClass($permission, $sub = false)
    {

        if ($permission == 'account.treatment_tokens') {
            $routes_ar = Treatment::getAccountRoutesArray();
            return self::getClass($routes_ar, $sub);
        }
        if ($permission == 'kitchen.diet_management') {
            $routes_ar = DietChart::getRoutesArray();
            $requirements_routes_ar = DietChart::getRequirementsRoutesArray();
            $meal_routes_ar = DietChart::getMealStatusRoutesArray();
            $serving_routes_ar = DietChart::getMealServingsRoutesArray();
            $routes_ar = array_merge($routes_ar, $requirements_routes_ar, $meal_routes_ar, $serving_routes_ar);
            return self::getClass($routes_ar, $sub);
        }
        if ($permission == 'kitchen.patient_diet') {
            $routes_ar = DietChart::getRoutesArray();
            /*$routes_ar = array_merge($routes_ar);*/
            return self::getClass($routes_ar, $sub);
        }
        if ($permission == 'kitchen.requirements') {
            $routes_ar = DietChart::getRequirementsRoutesArray();
            /*$routes_ar = array_merge($routes_ar);*/
            return self::getClass($routes_ar, $sub);
        }
        if ($permission == 'kitchen.meal-status') {
            $routes_ar = DietChart::getMealStatusRoutesArray();
            /*$routes_ar = array_merge($routes_ar);*/
            return self::getClass($routes_ar, $sub);
        }
        if ($permission == 'kitchen.meal-servings') {
            $routes_ar = DietChart::getMealServingsRoutesArray();
            /*$routes_ar = array_merge($routes_ar);*/
            return self::getClass($routes_ar, $sub);
        }


        if ($permission == 'doctor.tokens') {
            $routes_ar = PatientToken::getRoutesArray();
            /*$routes_ar = array_merge($routes_ar);*/
            return self::getClass($routes_ar, $sub);
        }

        if ($permission == 'doctor.patients') {
            $routes_ar = PatientDetails::getRoutesArray();
            /*$routes_ar = array_merge($routes_ar);*/
            return self::getClass($routes_ar, $sub);
        }


        if ($permission == 'admin.user_staff_management') {
            $routes_ar = Staff::getRoutesArray();
            $role_routes = Role::getRoutesArray();
            $permission_routes = Permission::getRoutesArray();
            $department_routes = Department::getRoutesArray();
            $staff_department_routes = StaffDepartment::getRoutesArray();
            $user_department_routes = User::getRoutesArray();
            $doctors_ar = User::getRoutesArray(true);
            $patients_ar = Booking::getRoutesArray(true);
            $staff_ar = Staff::getRoutesArray();
            $attendance_ar = Attendance::getRoutesArray();

            $routes_ar = array_merge($routes_ar, $role_routes, $permission_routes, $department_routes, $staff_department_routes, $user_department_routes, $doctors_ar, $patients_ar, $staff_ar, $attendance_ar, ['Laralum::attendance.leaves']);
            return self::getClass($routes_ar, $sub);
        }

        if ($permission == 'admin.roles.list') {
            $routes_ar = Role::getRoutesArray();
            return self::getClass($routes_ar, $sub);
        }

        if ($permission == 'admin.permissions.list') {
            $routes_ar = Permission::getRoutesArray();
            return self::getClass($routes_ar, $sub);
        }

        if ($permission == 'admin.doctor_departments.list') {
            $routes_ar = Department::getRoutesArray();
            return self::getClass($routes_ar, $sub);
        }

        if ($permission == 'admin.staff_departments.list') {
            $routes_ar = StaffDepartment::getRoutesArray();
            return self::getClass($routes_ar, $sub);
        }

        if ($permission == 'admin.users.list') {
            $routes_ar = User::getRoutesArray();
            return self::getClass($routes_ar, $sub);
        }

        if ($permission == 'admin.doctors.list') {
            $routes_ar = User::getRoutesArray(true);
            return self::getClass($routes_ar, $sub);
        }

        if ($permission == 'admin.patients.list') {
            $routes_ar = Booking::getRoutesArray(true);
            return self::getClass($routes_ar, $sub);
        }

        if ($permission == 'admin.staff.list') {
            $routes_ar = Staff::getRoutesArray();
            return self::getClass($routes_ar, $sub);
        }

        if ($permission == 'admin.bookings.list') {
            $routes_ar = Booking::getListRoutesArray();
            return self::getClass($routes_ar, $sub);
        }

        if ($permission == 'admin.attendance.list') {
            $routes_ar = Attendance::getRoutesArray();
            return self::getClass($routes_ar, $sub);
        }

        if ($permission == 'admin.attendance.leaves') {
            $routes_ar = ['Laralum::attendance.leaves'];
            return self::getClass($routes_ar, $sub);
        }


        if ($permission == 'admin.bookings_management') {
            $routes_ar = Booking::getRoutesArray();
            $new_routes_ar = Booking::getNewBookingArray();
            $token_routes_ar = Booking::getPatientTokenArray();
            $treatment_routes_ar = Booking::getTreatmentTokenArray();
            $dis_routes_ar = Booking::getDischargeBiilingArray();
            $follow_routes_ar = Booking::getFolowupArray();

            $routes_ar = array_merge($routes_ar, $new_routes_ar, $token_routes_ar, $treatment_routes_ar, $dis_routes_ar, $follow_routes_ar);
            return self::getClass($routes_ar, $sub);
        }

        if ($permission == 'admin.booking.pending') {
            $routes_ar = [
                'Laralum::admin.booking.pending'
            ];
            return self::getClass($routes_ar, $sub);
        }

        if ($permission == 'admin.ipd_bookings_management') {
            $routes_ar = [
                'Laralum::ipd.bookings.list',
                'Laralum::ipd.booking.show',
                'Laralum::ipd.booking.personalDetails',
                'Laralum::ipd.booking.health_issues',
                'Laralum::ipd.booking.payment',
                'Laralum::ipd.booking.confirm',
                'Laralum::ipd.booking.print_kid',
                'Laralum::ipd-tokens',
                'Laralum::ipd.bookings.account',
                'Laralum::ipd.booking.accommodation',
                'Laralum::booking.allot.rooms',
            ];
            //$list_routes_ar = Booking::getListRoutesArray();
            //$routes_ar = array_merge($list_routes_ar, $routes_ar);
            return self::getClass($routes_ar, $sub);
        }

        if ($permission == 'admin.future_patients_management') {
            $routes_ar = [
                'Laralum::future.booking.show',
                'Laralum::future.booking.personal_details',
                'Laralum::future.booking.health_issues',
                'Laralum::future.booking.accommodation',
                'Laralum::future.booking.payment',
                'Laralum::future.booking.confirm',
                'Laralum::future.booking.print_kid',
                'Laralum::admin.future.patients.list',
                'Laralum::future.booking.allot.rooms',
            ];
            $list_routes_ar = Booking::getListRoutesArray();
            //$routes_ar = array_merge($list_routes_ar, $routes_ar);
            $routes_ar = array_merge($routes_ar, $list_routes_ar);
            return self::getClass($routes_ar, $sub);
        }

        if ($permission == 'admin.opd_patients_management') {
            $routes_ar = [
                'Laralum::opd.booking.show',
                'Laralum::bookings',
                'Laralum::booking.opd.generate_token',
                 'Laralum::booking.show',
                'Laralum::booking.personalDetails',
                'Laralum::booking.personalDetails.store',
                'Laralum::booking.health_issues',
                'Laralum::booking.health_issues.store',
                'Laralum::booking.accommodation',
                'Laralum::booking.payment',
                'Laralum::booking.confirm',
                'Laralum::booking.print_kid',
                'Laralum::bookings.print_patient_card',
                'Laralum::bookings.print_patient_card',
                'Laralum::booking.info',
                'Laralum::booked.room.info',
                'Laralum::full.booked.room.info',
                'Laralum::bookings.account',
                'Laralum::opd.booking.personalDetails',
                'Laralum::opd.booking.health_issues',
                'Laralum::opd.booking.payment',
                'Laralum::opd.booking.confirm',
                'Laralum::opd.booking.print_kid',
                'Laralum::opd-tokens',
                'Laralum::bookings.generate_opd_token',
                'Laralum::opd.bookings.account'
            ];
            $routes_ar = array_merge($routes_ar);
            return self::getClass($routes_ar, $sub);
        }

        if ($permission == 'admin.opd_patients_list') {
            $routes_ar = [
                'Laralum::opd.booking.show',
                'Laralum::bookings',
                'Laralum::booking.show',
                'Laralum::booking.opd.generate_token',
                'Laralum::opd.booking.personalDetails',
                'Laralum::opd.booking.health_issues',
                'Laralum::opd.booking.payment',
                'Laralum::opd.booking.confirm',
                'Laralum::opd.booking.print_kid',
                'Laralum::opd.bookings.account',
                'Laralum::booking.personalDetails',
                'Laralum::booking.personalDetails.store',
                'Laralum::booking.health_issues',
                'Laralum::booking.health_issues.store',
                'Laralum::booking.accommodation',
                'Laralum::booking.payment',
                'Laralum::booking.confirm',
                'Laralum::booking.print_kid',
                'Laralum::bookings.print_patient_card',
                'Laralum::bookings.print_patient_card',
            ];
            $routes_ar = array_merge($routes_ar);
            return self::getClass($routes_ar, $sub);
        }

        if ($permission == 'opd-tokens') {
            $routes_ar = [
                'Laralum::opd-tokens',
            ];
            $routes_ar = array_merge($routes_ar);
            return self::getClass($routes_ar, $sub);
        }

        if ($permission == 'generate-opd-token') {
            $routes_ar = [
                'Laralum::bookings.generate_opd_token',
            ];
            $routes_ar = array_merge($routes_ar);
            return self::getClass($routes_ar, $sub);
        }


        if ($permission == 'admin.issues_management') {
            $routes_ar = Issue::getRoutesArray();
            return self::getClass($routes_ar, $sub);
        }

        if ($permission == 'admin.bookings.list') {
            $routes_ar = Booking::getListRoutesArray();
            return self::getClass($routes_ar, $sub);
        }

        if ($permission == 'admin.bookings.create') {
            $routes_ar = Booking::getNewBookingArray();
            return self::getClass($routes_ar, $sub);
        }
        if ($permission == 'admin.bookings.tokens.list') {
            $routes_ar = Booking::getPatientTokenArray();
            return self::getClass($routes_ar, $sub);
        }
        if ($permission == 'admin.bookings.treatment_tokens.list') {
            $routes_ar = Booking::getTreatmentTokenArray();
            return self::getClass($routes_ar, $sub);
        }
        if ($permission == 'admin.bookings.discharge_patient_billing') {
            $routes_ar = Booking::getDischargeBiilingArray();
            return self::getClass($routes_ar, $sub);
        }
        if ($permission == 'admin.bookings.follow_ups') {
            $routes_ar = Booking::getFolowupArray();
            return self::getClass($routes_ar, $sub);
        }

        if ($permission == 'accomodations') {
            $routes_ar = Booking::getAccomodationArray();
            return self::getClass($routes_ar, $sub);
        }

        if ($permission == 'admin.archived_patients_management') {
            $routes_ar = Booking::getArchivedArray();
            return self::getClass($routes_ar, $sub);
        }

        if ($permission == 'admin.physiotherpy_exercises.index') {
            $routes_ar = PhysiotherapyExercise::getRoutesArray();
            return self::getClass($routes_ar, $sub);
        }

        if ($permission == 'admin.permission_exercise_categories.list') {
            $routes_ar = PhysiotherapyExerciseCategory::getRoutesArray();
            return self::getClass($routes_ar, $sub);
        }

        if ($permission == 'admin.accommodation_management') {
            $routes_ar = Building::getRoutesArray();
            $chart_ar = Building::getChartArray();
            $room_type_routes_ar = Building::getRoomTypeArray();
            $services_routes_ar = Building::getServicesArray();
            $room_routes_ar = Building::getRoomArray();
            $block_routes_ar = Building::getBlockedRoomArray();
            $routes_ar = array_merge($chart_ar, $routes_ar, $room_type_routes_ar, $services_routes_ar, $room_routes_ar, $block_routes_ar);
            return self::getClass($routes_ar, $sub);
        }


        if ($permission == 'admin.accommodation.chart') {
            $routes_ar = Building::getChartArray();
            return self::getClass($routes_ar, $sub);
        }

        if ($permission == 'admin.room_types') {
            $routes_ar = Building::getRoomTypeArray();
            return self::getClass($routes_ar, $sub);
        }

        if ($permission == 'admin.room_services') {
            $routes_ar = Building::getServicesArray();
            return self::getClass($routes_ar, $sub);
        }

        if ($permission == 'admin.buildings') {
            $routes_ar = Building::getRoutesArray();
            return self::getClass($routes_ar, $sub);
        }

        if ($permission == 'admin.rooms') {
            $routes_ar = Building::getRoomArray();
            return self::getClass($routes_ar, $sub);
        }

        if ($permission == 'admin.block_rooms') {
            $routes_ar = Building::getBlockedRoomArray();
            return self::getClass($routes_ar, $sub);
        }

        if ($permission == 'admin.inventory_management') {
            $routes_ar = InventoryGroup::getRoutesArray();
            $item_routes_ar = InventoryGroup::getItemRoutesArray();
            $stock_routes_ar = InventoryGroup::getStockRoutesArray();
            $request_routes_ar = InventoryGroup::getStockRequestArray();
            $routes_ar = array_merge($routes_ar, $item_routes_ar, $stock_routes_ar, $request_routes_ar);
            return self::getClass($routes_ar, $sub);
        }

        if ($permission == 'admin.inventory_groups') {
            $routes_ar = InventoryGroup::getRoutesArray();
            return self::getClass($routes_ar, $sub);
        }

        if ($permission == 'admin.inventory_group_items') {
            $routes_ar = InventoryGroup::getItemRoutesArray();
            return self::getClass($routes_ar, $sub);
        }

        if ($permission == 'admin.stock') {
            $routes_ar = InventoryGroup::getStockRoutesArray();
            return self::getClass($routes_ar, $sub);
        }

        if ($permission == 'admin.stock_item_request') {
            $routes_ar = InventoryGroup::getStockRequestArray();
            return self::getClass($routes_ar, $sub);
        }

        if ($permission == 'admin.kitchen_management') {
            $routes_ar = KitchenItem::getRoutesArray();
            $requirement_routes_ar = KitchenItem::getRequirementsRoutesArray();
            $diets_routes_ar = KitchenItem::getDietRoutesArray();
            $meal_routes_ar = KitchenItem::getMealRoutesArray();
            $meal_serving_routes_ar = KitchenItem::getMealServingRoutesArray();
            $routes_ar = array_merge($requirement_routes_ar, $routes_ar, $diets_routes_ar, $meal_routes_ar, $meal_serving_routes_ar);
            return self::getClass($routes_ar, $sub);
        }

        if ($permission == 'admin.kitchen_items') {
            $routes_ar = KitchenItem::getRoutesArray();
            return self::getClass($routes_ar, $sub);
        }

        if ($permission == 'admin.kitchen_item.requirements') {
            $routes_ar = KitchenItem::getRequirementsRoutesArray();
            return self::getClass($routes_ar, $sub);
        }

        if ($permission == 'admin.kitchen_item.diet_chart') {
            $routes_ar = KitchenItem::getDietRoutesArray();
            return self::getClass($routes_ar, $sub);
        }

        if ($permission == 'admin.meal_status') {
            $routes_ar = KitchenItem::getMealRoutesArray();
            return self::getClass($routes_ar, $sub);
        }

        if ($permission == 'admin.meal_servings') {
            $routes_ar = KitchenItem::getMealServingRoutesArray();
            return self::getClass($routes_ar, $sub);
        }

        if ($permission == 'admin.admin_settings') {
            $routes_ar = DocumentType::getRoutesArray();
            $per_routes_ar = Profession::getRoutesArray();
            $bank_routes_ar = HospitalBankaccount::getRoutesArray();
            $info_routes_ar = HospitalInfo::getRoutesArray();
            $tax_routes_ar = TaxDetail::getRoutesArray();
            $consult_routes_ar = ConsultationCharge::getRoutesArray();
            $disc_routes_ar = DiscountOffer::getRoutesArray();
            $temp_routes_ar = EmailTemplate::getRoutesArray();
            $feedback_routes_ar = FeedbackQuestion::getRoutesArray();
            $treatment_routes_ar = Treatment::getRoutesArray();
            $lab_routes_ar = LabTest::getRoutesArray();
            $admin_settings_ar = AdminSetting::getRoutesArray();

            $routes_ar = array_merge($admin_settings_ar, $lab_routes_ar, $treatment_routes_ar, $feedback_routes_ar, $temp_routes_ar, $disc_routes_ar, $consult_routes_ar, $tax_routes_ar, $routes_ar, $per_routes_ar, $bank_routes_ar, $info_routes_ar);
            return self::getClass($routes_ar, $sub);
        }

        if ($permission == 'admin.admin_settings.document_types') {
            $routes_ar = DocumentType::getRoutesArray();
            return self::getClass($routes_ar, $sub);
        }

        if ($permission == 'admin.admin_settings.professions') {
            $routes_ar = Profession::getRoutesArray();
            return self::getClass($routes_ar, $sub);
        }

        if ($permission == 'admin.admin_settings.hospital_info') {
            $routes_ar = HospitalInfo::getRoutesArray();
            return self::getClass($routes_ar, $sub);
        }

        if ($permission == 'admin.admin_settings.hospital_bank_account') {
            $routes_ar = HospitalBankaccount::getRoutesArray();
            return self::getClass($routes_ar, $sub);
        }

        if ($permission == 'admin.admin_settings.tax_details') {
            $routes_ar = TaxDetail::getRoutesArray();
            return self::getClass($routes_ar, $sub);
        }

        if ($permission == 'admin.admin_settings.consultation_charges') {
            $routes_ar = ConsultationCharge::getRoutesArray();
            return self::getClass($routes_ar, $sub);
        }

        if ($permission == 'admin.admin_settings.email_templates') {
            $routes_ar = EmailTemplate::getRoutesArray();
            return self::getClass($routes_ar, $sub);
        }

        if ($permission == 'admin.admin_settings.discount_offers') {
            $routes_ar = DiscountOffer::getRoutesArray();
            return self::getClass($routes_ar, $sub);
        }

        if ($permission == 'admin.admin_settings.feedback_questions') {
            $routes_ar = FeedbackQuestion::getRoutesArray();
            return self::getClass($routes_ar, $sub);
        }

        if ($permission == 'admin.admin_settings.treatments') {
            $routes_ar = Treatment::getRoutesArray();
            return self::getClass($routes_ar, $sub);
        }

        if ($permission == 'admin.admin_settings.lab_tests') {
            $routes_ar = LabTest::getRoutesArray();
            return self::getClass($routes_ar, $sub);
        }
        if ($permission == 'admin.admin_settings.price_settings') {
            $routes_ar = AdminSetting::getRoutesArray();
            return self::getClass($routes_ar, $sub);
        }

    }

    public static function getClass($routes_ar, $sub)
    {
        if (in_array(\Route::getCurrentRoute()->getName(), $routes_ar)) {
            if ($sub == true) {
                return 'active';
            } else {
                return 'openTooltip';
            }
        }
    }

	public static function getFormattedDate($date) 
	{
		$dateAr = explode('-', $date);

		$revar = array_reverse($dateAr);
		$fdate = implode('-', $revar);
		return $fdate;
	}

public static function amountInWords($number) {
   $no = floor($number);
   $point = round($number - $no, 2) * 100;
   $hundred = null;
   $digits_1 = strlen($no);
   $i = 0;
   $str = array();
   $words = array('0' => '', '1' => 'One', '2' => 'Two',
    '3' => 'Three', '4' => 'Four', '5' => 'Five', '6' => 'Six',
    '7' => 'Seven', '8' => 'Eight', '9' => 'Nine',
    '10' => 'Ten', '11' => 'Eleven', '12' => 'Twelve',
    '13' => 'Thirteen', '14' => 'Fourteen',
    '15' => 'Fifteen', '16' => 'Sixteen', '17' => 'Seventeen',
    '18' => 'Eighteen', '19' =>'Nineteen', '20' => 'Twenty',
    '30' => 'Thirty', '40' => 'Forty', '50' => 'Fifty',
    '60' => 'Sixty', '70' => 'Seventy',
    '80' => 'Eighty', '90' => 'Ninety');
   $digits = array('', 'Hundred', 'Thousand', 'Lakh', 'Crore');
   while ($i < $digits_1) {
     $divider = ($i == 2) ? 10 : 100;
     $number = floor($no % $divider);
     $no = floor($no / $divider);
     $i += ($divider == 10) ? 1 : 2;
     if ($number) {
        $plural = (($counter = count($str)) && $number > 9) ? 's' : null;
        $hundred = ($counter == 1 && $str[0]) ? ' and ' : null;
        $str [] = ($number < 21) ? $words[$number] .
            " " . $digits[$counter] . $plural . " " . $hundred
            :
            $words[floor($number / 10) * 10]
            . " " . $words[$number % 10] . " "
            . $digits[$counter] . $plural . " " . $hundred;
     } else $str[] = null;
  }
  $str = array_reverse($str);
  $result = implode('', $str);
  $points = ($point) ?
    "." . $words[$point / 10] . " " . 
          $words[$point = $point % 10] : '';
  echo $result . "Rupees  ";
}
}
