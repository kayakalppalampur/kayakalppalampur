<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

# Welcome route
Route::post('/elfinder/ckeditor', function () {

});

Route::get('test-email', 'HomeController@testemail');
Route::post('get_building_floor', ['as' => 'room.get_building_floor', 'uses' => 'Laralum\AccommodationController@getBuildingFloor']);
Route::post('get_building_rooms/{id?}', ['as' => 'room.get_building_rooms', 'uses' => 'Laralum\AccommodationController@getBuildingRooms']);
Route::post('get_room_services/{id?}', ['as' => 'room.get_room_services', 'uses' => 'Laralum\AccommodationController@getRoomServices']);
Route::post('get_room_beds/{room_id}', ['as' => 'room.get_room_beds', 'uses' => 'Laralum\AccommodationController@getRoomBeds']);
Route::post('get-meal-servings-ajax', ['as' => 'meal.servings.ajax', 'uses' => 'Laralum\KitchenItemController@mealServingsAjax']);


Route::post('get_department_doctors', ['as' => 'department.get_department_doctors', 'uses' => 'Laralum\DepartmentController@getDepartmentDoctors']);


Route::post('admin/get-treatments/{id}', ['as' => 'department.get_treatments', 'uses' => 'Laralum\DepartmentController@getTreatments']);


Route::group(['middleware' => 'laralum.base'], function () {

    /*
    |--------------------------------------------------------------------------
    | Add your website routes here
    |--------------------------------------------------------------------------
    |
    | The laralum.base middleware will be applied
    |
    */

    # Welcome route
    Route::get('/', function () {
        if (!\Auth::check())
            return view('welcome');
        return redirect('/home');
    });
    Route::post('upload-document/{id?}', 'Laralum\DocumentsController@uploadDocument');


    Route::get('images/{filename}', function ($filepath) {
        $filepath = base64_decode($filepath);
        $filear = explode('---', $filepath);
        $file = $filear[0];

        $filename = $filear[1];
        $path = storage_path() . '/app/' . $file;

        if (!File::exists($path)) abort(404);

        $file = File::get($path);
        $type = File::mimeType($path);
        $response = Response::make($file, 200);
        $response->header("Content-Type", $type);

        return $response;
    });


    Route::get('image/download/{filename}', function ($filepath) {

        $filepath = base64_decode($filepath);
        $filear = explode('---', $filepath);
        $file = $filear[0];
        $filename = time();
        if (isset($filear[1]))
            $filename = $filear[1];

        $path = storage_path() . '/app/' . $file;

        if (!File::exists($path)) abort(404);

        $file = File::get($path);
        $type = File::mimeType($path);

        // Send Download
        return Response::download($path, $filename, [
            'Content-Length: ' . filesize($path)
        ]);

        /*$response = Response::make($file, 200);
        $response->header("Content-Type", $type);*/
    });

    #Admin login

    Route::get('/admin/login', ['as' => 'admin.login', 'uses' => 'Auth\AdminLoginController@login']);
    Route::post('/admin/login', ['as' => 'admin.postLogin', 'uses' => 'Auth\AdminLoginController@postLogin']);

    # Guest Booking Form
    Route::post('/check-email', ['as' => 'check-email', 'uses' => 'BookingController@checkEmail']);
    Route::post('/get-discount-code', ['as' => 'get.discount.code', 'uses' => 'Laralum\DiscountController@getDiscountCode']);

    Route::get('/patient/query', ['as' => 'patient.query', 'uses' => 'HomeController@patientQuery']);
    Route::post('/patient/query/store', ['as' => 'patient.query.store', 'uses' => 'HomeController@patientQueryStore']);
    Route::get('/guest/booking', ['as' => 'guest.booking', 'uses' => 'HomeController@guestBooking']);
    Route::post('/guest/booking', ['as' => 'guest.booking', 'uses' => 'HomeController@postBooking']);

    Route::get('/guest/booking/signup', ['as' => 'guest.booking.signup', 'uses' => 'BookingController@guestBookingSignup']);
    Route::post('/guest/booking/signup', ['as' => 'guest.booking.signup', 'uses' => 'BookingController@guestBookingSignupStore']);

    Route::get('/guest/booking/personal_details', ['as' => 'guest.booking.personalDetails', 'uses' => 'BookingController@guestBookingPersonalDetails']);
    Route::post('/guest/booking/personal_details', ['as' => 'guest.booking.personalDetails', 'uses' => 'BookingController@guestBookingPersonalDetailsStore']);

    Route::get('booking/registration/get_states/{id}','BookingController@getStates');
    

    Route::get('/guest/booking/health_issues', ['as' => 'guest.booking.health_issues', 'uses' => 'BookingController@guestBookingHealthIssues']);
    Route::post('/guest/booking/health_issues', ['as' => 'guest.booking.health_issues', 'uses' => 'BookingController@guestBookingHealthIssuesStore']);


    Route::any('/guest/booking/accommodation', ['as' => 'guest.booking.accommodation', 'uses' => 'BookingController@guestBookingAccommodation']);
    Route::post('/guest/booking/accommodation', ['as' => 'guest.booking.accommodation_request', 'uses' => 'BookingController@guestBookingAccommodationRequest']);
    Route::post('guest/booking/delete-members', 'BookingController@deleteMember')->name('guest.booking.delete_member');


    Route::any('/guest/booking/aggreement', ['as' => 'guest.booking.aggreement', 'uses' => 'BookingController@guestBookingAggreement']);
    Route::post('/guest/booking/aggreement', ['as' => 'guest.booking.aggreement.store', 'uses' => 'BookingController@guestBookingAggreementStore']);

    Route::any('/guest/booking/payment', ['as' => 'guest.booking.payment', 'uses' => 'BookingController@guestBookingPayment']);
    Route::post('/guest/booking/payment', ['as' => 'guest.booking.payment.store', 'uses' => 'BookingController@guestBookingPaymentStore']);


    Route::post('room/get-status', ['as' => 'room.get.status', 'uses' => 'BookingController@getRoomStatus']);

    Route::any('/guest/booking-chart', ['as' => 'guest.bookingRm', 'uses' => 'HomeController@guestBookingChart']);
    Route::any('/guest/booking-chart-mw', ['as' => 'guest.bookingMw', 'uses' => 'HomeController@guestBookingChartmw']);

    Route::get('/guest/booking/confirm', ['as' => 'guest.booking.confirm', 'uses' => 'BookingController@guestBookingConfirm']);

    Route::get('/guest/booking-chart', ['as' => 'guest.booking', 'uses' => 'HomeController@guestBookingChart']);
    Route::get('/guest/booking-chart-mw', ['as' => 'guest.booking', 'uses' => 'HomeController@guestBookingChartmw']);
    Route::get('/booking/roomwise/chart', function () {
        return view('booking.booking_chart_room_wise1');
    });
    Route::get('/booking/periodwise/chart', function () {
        return view('booking.booking_chart_period_wise');
    });

    Route::get('/guest/booking/get_booking_info/{booking_id}/{room_id}', ['as' => 'guest.booking.info', 'uses' => 'BookingController@getBookingInfo']);
    Route::get('/guest/booking/accomm_booking_form/{room_id}/{booking_id?}', ['as' => 'guest.accombooking.form', 'uses' => 'BookingController@accommBookingForm']);
    Route::post('/guest/booking/accomm_booking_form', ['as' => 'guest.accombookingstore.form', 'uses' => 'BookingController@accommBookingFormStore']);
    Route::post('/guest/booking/confirm', ['as' => 'guest.booking.confirm.post', 'uses' => 'BookingController@guestBookingConfirmStore']);
    Route::get('/thank-you', function () {
        \Session::forget('user_id');
        \Session::forget('profile_id');
        \Session::forget('health_issues');
        \Session::forget('txn_id');
        return view('booking.thank_you');
    })->name('thank-you');
    Route::post('/checkMail', array(
        'as' => 'user/checkMail',
        'uses' => 'UserProfileController@checkUserMail'
    ));
    # Auth Route
    Auth::routes();
});

Route::group(['middleware' => ['auth', 'laralum.base']], function () {


    /*
    |--------------------------------------------------------------------------
    | Add your website routes here (users are forced to login to access those)
    |--------------------------------------------------------------------------
    |
    | The laralum.base and auth middlewares will be applied
    |
    */

    # Default home route
    Route::get('/home', ['as' => 'home', 'uses' => 'HomeController@index']);
});


/*
+---------------------------------------------------------------------------+
| Laralum Routes															|
+---------------------------------------------------------------------------+
|  _                     _													|
| | |                   | |													|
| | |     __ _ _ __ __ _| |_   _ _ __ ___									|
| | |    / _` | '__/ _` | | | | | '_ ` _ \									|
| | |___| (_| | | | (_| | | |_| | | | | | |									|
| \_____/\__,_|_|  \__,_|_|\__,_|_| |_| |_| Administration Panel			|
|																			|
+---------------------------------------------------------------------------+
|																			|
| This route group applies the "web" middleware group to every route		|
| it contains. The "web" middleware group is defined in your HTTP			|
| kernel and includes session state, CSRF protection, and more.				|
| This routes are made to manage laralum administration panel, please		|
| don't change anything unless you know what you're doing.					|
|																			|
+---------------------------------------------------------------------------+
*/

Route::group(['middleware' => ['web', 'auth', 'laralum.base'], 'as' => 'Laralum::'], function () {


    Route::get('activate/{token?}', 'Auth\ActivationController@activate')->name('activate_account');
    Route::post('activate', 'Auth\ActivationController@activateWithForm')->name('activate_form');
    Route::get('/banned', function () {
        return view('auth/banned');
    })->name('banned');

    Route::post('/user/profile/update', 'UserProfileController@updateProfile');
    Route::get('/user/change-password', 'UserProfileController@changePassword');
    Route::post('/user/change-password', ['as' => 'user.post.change.password', 'uses' => 'UserProfileController@postChangePassword']);


    /*User Bookings Routes*/
    Route::get('/user/bookings', ['as' => 'user.bookings', 'uses' => 'UserProfileController@bookings']);
    Route::get('/booking/{booking_id}/booking-detail', ['as' => 'user.booking-detail', 'uses' => 'UserProfileController@bookingDetail']);
    Route::get('bookings/{booking_id}/print', 'UserProfileController@printBooking')->name('user.booking.print');
    Route::get('bookings/{booking_id}/account', 'UserProfileController@account')->name('user.booking.account');
    Route::get('bookings/{booking_id}/print-account', 'UserProfileController@printAccount')->name('user.booking.print.account');
    Route::get('bookings/{booking_id}/personal-details', 'BookingController@personalDetails')->name('user.booking.personal.details');
    Route::post('bookings/{booking_id}/personal-details', 'BookingController@personalDetailsStore')->name('user.booking.personalDetails.store');
    Route::get('booking/{booking_id}/health_issues', 'BookingController@healthIssues')->name('user.booking.health_issues');
    Route::post('bookings/{booking_id}/health_issues', 'BookingController@healthIssuesStore')->name('user.booking.healthIssues.store');
    Route::get('booking/{booking_id}/accommodation', 'BookingController@accommodation')->name('user.booking.accommodation');
    Route::post('bookings/{booking_id}/accommodation', 'BookingController@accommodationRequest')->name('user.booking.accommodation_request');


    Route::get('booking/{booking_id}/payment', 'BookingController@payment')->name('user.booking.payment');
    Route::post('bookings/{booking_id}/payment', 'BookingController@paymentStore')->name('user.booking.payment.store');
    Route::get('booking/{booking_id}/confirm', 'BookingController@confirm')->name('user.booking.confirm');
    Route::post('booking/{booking_id}/confirm', 'BookingController@confirmStore')->name('user.booking.confirm.store');
    Route::get('/bookings/{id}/delete', 'BookingController@confirmDelete')->name('user.bookings.delete');
    Route::post('/bookings/{id}/delete', 'UserProfileController@deleteBooking');

    /*Route::get('/user/booking/personal_details/{user_id}', 'BookingController@personalDetails');
    Route::post('/user/booking/personal_details/{user_id}', ['as' => 'user.booking.personalDetails.store', 'uses' => 'BookingController@guestBookingPersonalDetailsStore']);
    Route::get('/user/booking/health_issues/{user_id}', 'Laralum\BookingController@healthIssues')->name('user.booking.health_issues');
    Route::post('/user/booking/health_issues/{user_id}', 'BookingController@healthIssuesStore')->name('user.booking.health_issues.store');
    Route::get('/user/booking/accommodation/{user_id}', 'BookingController@userAccommodation')->name('user.booking.accommodation');
    Route::post('user/booking/accommodation/{user_id}', 'Laralum\BookingController@userAccommodationStore')->name('user.booking.accommodation.store');
    Route::post('user/booking/accommodation/{user_id}', 'Laralum\BookingController@userAccommodationRequest')->name('user.booking.accommodation_request');*/


    Route::get('user/booking/get_booking_info/{booking_id}/{room_id}/{user_id}', 'Laralum\BookingController@getBookingInfo')->name('user.booking.info');
    Route::get('user/booking/accomm_booking_form/{user_id}/{room_id}/{booking_id?}', 'Laralum\BookingController@accommBookingForm')->name('user.accombooking.form');
    Route::post('user/booking/accomm_booking_form/{user_id}', 'Laralum\BookingController@accommBookingFormStore')->name('user.accombookingstore.form');

    /*Route::get('user/booking/payment/{user_id}', 'Laralum\BookingController@payment')->name('user.booking.payment');
    Route::post('user/booking/payment/{user_id}', 'Laralum\BookingController@paymentStore')->name('user.booking.payment.store');

    Route::get('user/booking/confirm/{user_id}', 'Laralum\BookingController@confirm')->name('user.booking.confirm');
    Route::post('user/booking/confirm/{user_id}', 'Laralum\BookingController@confirmStore')->name('user.booking.confirm.store');*/

    Route::get('/user/booking/print-kid/{booking_id}', 'Laralum\BookingController@generatePatientCard')->name('user.bookings.print_kid');

});

Route::group(['middleware' => ['web', 'laralum.base'], 'namespace' => 'Laralum', 'as' => 'Laralum::'], function () {

    # Public document downloads
    Route::get('/document/{slug}', 'DownloadsController@downloader')->name('document_downloader');
    Route::post('/document/{slug}', 'DownloadsController@download');

    # Social auth
    Route::get('/social/{provider}', 'SocialController@redirectToProvider')->name('social');
    Route::get('/social/{provider}/callback', 'SocialController@handleProviderCallback')->name('social_callback');

    # Public language changer
    Route::get('/locale/{locale}', 'LocaleController@set')->name('locale');

});

Route::group(['middleware' => ['laralum.base'], 'prefix' => 'admin', 'namespace' => 'Laralum', 'as' => 'Laralum::'], function () {

    # Public document downloads
    Route::get('/install', 'InstallerController@locale')->name('install_locale');
    Route::get('/install/{locale}', 'InstallerController@show')->name('install');
    Route::post('/install/{locale}', 'InstallerController@installConfig');
    Route::get('/install/{locale}/confirm', 'InstallerController@install')->name('install_confirm');

});

Route::group(['middleware' => ['auth', 'laralum.base', 'laralum.auth'], 'prefix' => 'admin', 'namespace' => 'Laralum', 'as' => 'Laralum::'], function () {

    

    Route::get('delete-all-data', 'BookingController@deleteAllData' );

    # Home Controller
    Route::get('/', 'DashboardController@index')->name('dashboard');
    Route::post('/', 'DashboardController@index')->name('dashboard');

    //Dashboard Actions
    Route::any('/daily-building-status/{date?}', 'DashboardController@dailyBuildingStatus')->name('daily.building.status');
    Route::get('/daily-building-status-print/{date?}/{building_id?}', 'DashboardController@dailyBuildingStatusPrint')->name('daily.building.status.print');

    Route::get('/daily-situation-report', 'DashboardController@dailySituationReport')->name('daily.situation.report');
    Route::get('/daily-situation-report-print', 'DashboardController@dailySituationReportPrint')->name('daily.building.status.print');
    //

    # Feedback Questions Routes
    Route::get('/feedback-questions', 'FeedbackQuestionController@index')->name('feedback-questions');
    Route::get('/feedback-questions/print', 'FeedbackQuestionController@printFeedback')->name('feedback-questions.print');
    Route::get('/feedback-questions/export/{type}', 'FeedbackQuestionController@exportFeedback')->name('feedback-questions.export');

    Route::post('/feedback-questions', 'FeedbackQuestionController@index')->name('feedback-questions');
    Route::post('/feedback-question/add', 'FeedbackQuestionController@store')->name('feedback-question.add.store');
    Route::get('/feedback-question/{question_id}/edit', 'FeedbackQuestionController@edit')->name('feedback-question.edit');
    Route::post('/feedback-question/{question_id}/edit', 'FeedbackQuestionController@update')->name('feedback-question.update');
    Route::get('/feedback-question/{question_id}/delete', 'SecurityController@confirm')->name('feedback-question.delete');
    Route::post('/feedback-question/{question_id}/delete', 'FeedbackQuestionController@destroy');

    # Feedback Questions Routes
    Route::get('/treatments', 'TreatmentController@index')->name('treatments');
    Route::get('/treatments/print', 'TreatmentController@printTreatments')->name('treatments.print');
    Route::get('/treatments/export/{type}', 'TreatmentController@export')->name('treatments.export');
    Route::get('/treatment/add', 'TreatmentController@create')->name('treatment.create');
    Route::post('/treatment/add', 'TreatmentController@store')->name('treatment.add.store');
    Route::get('/treatment/{treatment_id}/edit', 'TreatmentController@edit')->name('treatment.edit');
    Route::post('/treatment/{treatment_id}/edit', 'TreatmentController@update')->name('treatment.update');
    Route::get('/treatment/{treatment_id}/delete', 'SecurityController@confirm')->name('treatment.delete');
    Route::post('/treatment/{treatment_id}/delete', 'TreatmentController@destroy');
    Route::post('/treatments/get-duration', 'TreatmentController@getDuration');


     # Admin Setting Routes
    Route::get('/price-settings', 'DashboardController@priceSettings')->name('price-settings');
    Route::post('/price-settings', 'DashboardController@priceSettings')->name('price-settings');

    /*Treatment Packages*/
    Route::get('treatment-packages', [
        'as' => 'treatment_packages',
        'uses' => 'TreatmentPackageController@index',
    ]);
    Route::post('treatment-packages', [
        'as' => 'treatment_packages.search',
        'uses' => 'TreatmentPackageController@index',
    ]);

    Route::get('treatment-packages/add', [
        'as' => 'treatment_packages.create',
        'uses' => 'TreatmentPackageController@create',
    ]);

    Route::post('treatment-packages/add', [
        'as' => 'treatment_packages.store',
        'uses' => 'TreatmentPackageController@store',
    ]);

    Route::get('treatment-packages/{id}/edit', [
        'as' => 'treatment_packages.edit',
        'uses' => 'TreatmentPackageController@edit',
    ]);

    Route::post('treatment-packages/{id}/edit', [
        'as' => 'treatment_packages.update',
        'uses' => 'TreatmentPackageController@update',
    ]);
    Route::get('/treatment-packages/{id}/delete', 'SecurityController@confirm')->name('treatment_packages.delete');
    Route::post('/treatment-packages/{id}/delete', 'TreatmentPackageController@destroy');


    # Kitchen Items Routes
    Route::get('/kitchen-items', 'KitchenItemController@index')->name('kitchen-items');
    Route::get('/kitchen-items/print', 'KitchenItemController@printItems')->name('kitchen-items.print');
    Route::get('/kitchen-items/export/{type}', 'KitchenItemController@export')->name('kitchen-item.export');
    Route::get('/kitchen-item/add', 'KitchenItemController@create')->name('kitchen-item.create');
    Route::post('/kitchen-item/add', 'KitchenItemController@store')->name('kitchen-item.add.store');
    Route::get('/kitchen-item/{item_id}/edit', 'KitchenItemController@edit')->name('kitchen-item.edit');
    Route::post('/kitchen-item/{item_id}/edit', 'KitchenItemController@update')->name('kitchen-item.update');
    Route::get('/kitchen-item/{item_id}/delete', 'SecurityController@confirm')->name('kitchen-item.delete');
    Route::post('/kitchen-item/{item_id}/delete', 'KitchenItemController@destroy');
    Route::post('/kitchen-items', 'KitchenItemController@ajaxUpdate');

    Route::get('/kitchen-item/requirements', 'KitchenItemController@requirements')->name('kitchen-item.requirements');
    Route::post('/kitchen-item/requirements', 'KitchenItemController@ajaxRequirements')->name('kitchen-item.requirements.ajax');
    Route::get('/kitchen-item/requirements/print', 'KitchenItemController@printRequirements')->name('kitchen-item.requirements.print');
    Route::get('/kitchen-item/exportRequirements/{type}', 'KitchenItemController@exportRequirements')->name('kitchen-item.exportRequirements');

    Route::get('/kitchen-item/requests', 'KitchenItemController@itemRequests')->name('kitchen-item.requests');
    Route::get('/kitchen_request/{request_id}/delete', 'SecurityController@confirm')->name('kitchen_request.delete');
    Route::post('/kitchen_request/{request_id}/delete', 'KitchenItemController@destroyRequest');
    Route::get('/kitchen-item-requests/print', 'KitchenItemController@printItemRequests')->name('kitchen.item_requests.print');
    Route::get('/kitchen-item-requests/export/{type}', 'KitchenItemController@exportItemRequests')->name('kitchen.item_requests.export');

    Route::get('/kitchen-item/{item_id}/request', 'KitchenItemController@request')->name('kitchen.selected_item.request');
    Route::get('/kitchen-item/request', 'KitchenItemController@request')->name('kitchen-item.request');
    Route::post('/kitchen-item/request', 'KitchenItemController@request')->name('kitchen-item.request.store');
    Route::post('/kitchen-item/{item_id}/request', 'KitchenItemController@requestStore')->name('kitchen-item.request.store');
    Route::get('/item/get-stock-items-list/{item_id}', 'KitchenItemController@getStockItems')->name('kitchen-item.stock.items');


    //Diet Chart
    Route::get('/diet-chart', 'KitchenItemController@patientDiets')->name('diet-chart');
    Route::get('/diet-chart/{booking_id}', 'KitchenItemController@patientDiets')->name('kitchen-patient.diet-chart');
    Route::post('/diet-chart', 'KitchenItemController@patientDiets')->name('patient-diet-chart');
    Route::post('/diet-chart/toggle-state/{diet_id}', 'KitchenItemController@patientDietToggle')->name('patient-diet-chart-toggle-state');

    Route::get('/meal-status', 'KitchenItemController@mealStatus')->name('meal-status');
    Route::get('/kitchen-item/meal-status/print', 'KitchenItemController@printMealStatus')->name('print-meal-status');
    Route::get('/kitchen-item/export-meal-status/{type_id}', 'KitchenItemController@exportMealStatus')->name('export-meal-status');

    Route::get('/kitchen-item/meal-status-combined/print', 'KitchenItemController@printMealStatusCombined')->name('print-meal-status-combined');
    Route::get('/kitchen-item/export-meal-status-combined/{type_id}', 'KitchenItemController@exportMealStatusCombined')->name('export-meal-status-combined');

    Route::get('/meal-servings/{meal_type?}', 'KitchenItemController@mealServing')->name('meal-servings');
    Route::post('/meal-servings/{meal_type?}', 'KitchenItemController@mealServing')->name('meal-servings.ajax');

    Route::get('/print-meal-servings/{type_id}', 'KitchenItemController@printMealServing')->name('print-meal-serving');
    Route::get('/export-meal-servings/{meal_type_id}/{type_id}', 'KitchenItemController@exportMealServings')->name('export-meal-serving');


    Route::get('/kitchen-item/meal-status/print', 'KitchenItemController@printMealStatus')->name('print-meal-status');
   Route::post('/kitchen-item/meal-served', 'KitchenItemController@patientDietToggleAjax')->name('meal-served-by-ajax');


    # Stock Routes
    Route::get('/groups', 'InventoryGroupController@index')->name('groups');

    Route::get('/groups/print', 'InventoryGroupController@printGroups')->name('groups.print');
    Route::get('/groups/export/{type}', 'InventoryGroupController@exportGroups')->name('groups.export');
    Route::get('/group/add', 'InventoryGroupController@create')->name('group.add');
    Route::post('/group/add', 'InventoryGroupController@store')->name('group.store');
    Route::get('/group/edit/{group_id}', 'InventoryGroupController@edit')->name('group.edit');
    Route::post('/group/edit/{group_id}', 'InventoryGroupController@update')->name('group.update');
    Route::get('/group/{group_id}/delete', 'SecurityController@confirm')->name('group.delete');
    Route::post('/group/{group_id}/delete', 'InventoryGroupController@destroy');

    Route::get('/group-items', 'InventoryGroupItemController@index')->name('group-items');
    Route::post('/group-item/import', 'InventoryGroupItemController@import')->name('group-items.import');

    Route::post('/treatment/import', 'TreatmentController@import')->name('treatment.import');
    Route::post('/staff/import', 'StaffController@import')->name('staff.import');

    Route::get('/group-items/print', 'InventoryGroupItemController@printGroupsItems')->name('group-items.print');
    Route::get('/group-items/export/{type}', 'InventoryGroupItemController@exportGroupsItems')->name('group-items.export');
    Route::get('/group-item/add', 'InventoryGroupItemController@create')->name('group-item.add');
    Route::post('/group-item/add', 'InventoryGroupItemController@store')->name('group-item.store');
    Route::get('/group-item/edit/{id}', 'InventoryGroupItemController@edit')->name('group-item.edit');
    Route::post('/group-item/edit/{id}', 'InventoryGroupItemController@update')->name('group-item.update');
    Route::get('/group-item/{id}/delete', 'SecurityController@confirm')->name('group-item.delete');
    Route::post('/group-item/{id}/delete', 'InventoryGroupItemController@destroy');


    Route::post('/group-item', 'InventoryGroupItemController@ajaxUpdate');

    Route::get('/stock', 'StockController@index')->name('stock');
    Route::get('/stock/print', 'StockController@printStock')->name('stock.print');
    Route::get('/stock/export/{type}', 'StockController@exportStock')->name('stock.export');
    Route::post('/stock', 'StockController@ajaxUpdate');
    Route::get('/stock/add', 'StockController@create')->name('stock.create');
    Route::post('/stock/add', 'StockController@store')->name('stock.add.store');
    Route::get('/stock/{item_id}/edit', 'StockController@edit')->name('stock.edit');
    Route::post('/stock/{item_id}/edit', 'StockController@update')->name('stock.update');
    Route::get('/stock/{item_id}/delete', 'SecurityController@confirm')->name('stock.delete');
    Route::post('/stock/{item_id}/delete', 'StockController@destroy');
    Route::get('/stock/requests', 'StockController@itemRequests')->name('stock.item_requests');;
    Route::post('/stock/requests', 'StockController@itemRequests')->name('stock.item_requests');;
    Route::get('/stock-item-requests/print', 'StockController@printItemRequests')->name('stock.item_requests.print');
    Route::get('/stock-item-requests/export/{type}', 'StockController@exportItemRequests')->name('stock.item_requests.export');


    //Stock Logs
    Route::get('/stock/{id}/add-remove', 'StockController@addRemoveStock')->name('stock.add_remove_stock');
    Route::post('/stock/{id}/add-remove', 'StockController@storeLog')->name('stock.add_remove_stock_store');;


    Route::get('/item_request/{request_id}/delete', 'SecurityController@confirm')->name('item_request.delete');
    Route::post('/item_request/{request_id}/delete', 'StockController@destroyRequest');
    Route::get('/item-request/{item_request_id}/approve', 'StockController@approve')->name('stock.approve');

    # Users Routess
    Route::get('/users', 'UsersController@index')->name('users');
    Route::get('/users/print/{user_type}', 'UsersController@printUsers')->name('print');
    Route::get('/users/export/{type}/{user_type}', 'UsersController@export')->name('users.export');
   // Route::post('/users', 'UsersController@index')->name('users');
    Route::post('/users', 'UsersController@ajaxUpdate');

    #Staff
    Route::get('/staff', 'StaffController@index')->name('staff');/*
    Route::post('/staff', 'StaffController@index')->name('staff.search');*/
    Route::get('/staff/add', 'StaffController@create')->name('staff.add');
    Route::post('/staff/add', 'StaffController@store')->name('staff.store');
    Route::get('/staff/{id}/edit', 'StaffController@edit')->name('staff.edit');
    Route::post('/staff/{id}/edit', 'StaffController@update')->name('staff.update');
    Route::get('/staff/{id}/delete', 'SecurityController@confirm')->name('staff.delete');
    Route::post('/staff/{id}/delete', 'StaffController@destroy');
    Route::post('/staff', 'StaffController@ajaxUpdate');
    Route::get('/staff/print', 'StaffController@printStaff')->name('staff.print');
    Route::get('/staff/export/{type}', 'StaffController@exportStaff')->name('staff.export');


    Route::get('/patient-list', 'BookingController@index')->name('patient.list');
    Route::post('/patient-list', 'BookingController@ajaxUpdate')->name('patient.list');
    
    Route::get('patient/acc/print', 'BookingController@printPatientsWithAccomodation')->name('printPatientsWithAccomodation.print');
    Route::get('patient/acc/export/{type}', 'BookingController@exportPatientsWithAccomodation')->name('printPatientsWithAccomodation.export');

    Route::get('/patient/{id}/print', 'BookingController@printPatientDetails')->name('print.patient.details');

    Route::get('/patient-list/print', 'UsersController@printPatients')->name('print.patients');
    Route::get('/archived-patient-list', 'UsersController@archivedPatients')->name('archived.patients.list');
    Route::post('/archived-patient-list', 'UsersController@archivedPatients')->name('archived.patients.list.search');
    Route::get('/archived-patient-with-accomodation-list', 'UsersController@archivedPatientsWithAccomodations')->name('archived.patients.list.searchlist');

    Route::post('/archived-patient-with-accomodation-list', 'UsersController@archivedPatientsWithAccomodations')->name('archived.patients.list.searchaccomodations');

    Route::post('/archived-patient-search', 'UsersController@archivedPatient')->name('bookings.archived-patient-ajax');
    Route::post('/archived-patient-search/{view}', 'UsersController@archivedPatient')->name('bookings.archived-patient-search');

    Route::get('/archived-patients/export/{type}', 'PatientController@export')->name('archived.patients.export');
    Route::get('/archived-patient-list/print', 'UsersController@printArchivedPatients')->name('print.archived-patients');
//    Route::post('/archived-patient-list', 'UsersController@archivedPatients')->name('archived.patients.list');


    //Pending
    Route::get('/pending-list', 'BookingController@pendingPatients')->name('admin.booking.pending');
    //Route::post('/pending-list', ['as' => 'pending.ajax', 'uses' => 'BookingController@ajaxUpdate']);
    Route::post('/pending-search', 'BookingController@pendingPatients')->name('bookings.pending-ajax');

    //Future Patients
    Route::get('/future-patient-list', 'BookingController@futurePatients')->name('admin.future.patients.list');
    Route::post('/future-patient-list', 'BookingController@futurePatients')->name('bookings.future-patient-ajax');

    Route::get('/future-patients/export/{type}', 'BookingController@futurePatientsExport')->name('archived.patients.export');
    Route::get('/future-patient-list/print', 'UsersController@printFuturePatients')->name('print.future-patients');


    Route::get('/doctors', 'UsersController@doctors')->name('doctors');
    Route::get('/doctors/print', 'UsersController@printDoctors')->name('doctors.print');
    Route::post('/doctors', 'UsersController@doctors')->name('doctors');

    Route::get('/users/create', 'UsersController@create')->name('users_create');
    Route::get('/users/create/{user_type}', 'UsersController@create')->name('doctors_create');
    Route::post('/users/create/{user_type}', 'UsersController@store')->name('doctors_create.post');
    Route::post('/users/create', 'UsersController@store');

    Route::get('/users/settings', 'UsersController@editSettings')->name('users_settings');
    Route::post('/users/settings', 'UsersController@updateSettings');

    Route::get('/users/{id}', 'UsersController@show')->name('users_profile');

    Route::get('/users/{id}/edit', 'UsersController@edit')->name('users_edit');
    Route::get('/doctors/{id}/edit', 'UsersController@edit')->name('doctors_edit');
    Route::post('/users/{id}/edit', 'UsersController@update');
    Route::post('/doctors/{id}/edit', 'UsersController@update');

    Route::get('/users/{id}/roles', 'UsersController@editRoles')->name('users_roles');
    Route::post('/users/{id}/roles', 'UsersController@saveRole');

    Route::get('/users/{id}/department', 'UsersController@editDepartment')->name('users_department');
    Route::post('/users/{id}/department', 'UsersController@setDepartment');

    Route::get('/users/{id}/delete', 'SecurityController@confirm')->name('users_delete');
    Route::get('/doctors/{id}/delete', 'SecurityController@confirm')->name('doctors_delete');
    Route::post('/doctors/{id}/delete', 'UsersController@destroy');
    Route::post('/users/{id}/delete', 'UsersController@destroy');


    # Roles Routes
    Route::get('/roles', 'RolesController@index')->name('roles');
    Route::get('/roles/print', 'RolesController@printRoles')->name('roles_print');
    Route::get('/roles/export/{type}', 'RolesController@exportRoles')->name('roles_export');

    Route::get('/roles/create', 'RolesController@create')->name('roles_create');
    Route::post('/roles/create', 'RolesController@store');

    Route::get('/roles/{id}', 'RolesController@show')->name('roles_show');

    Route::get('/roles/{id}/edit', 'RolesController@edit')->name('roles_edit');
    Route::post('/roles/{id}/edit', 'RolesController@update');

    Route::get('/roles/{id}/permissions', 'RolesController@editPermissions')->name('roles_permissions');
    Route::post('/roles/{id}/permissions', 'RolesController@setPermissions');

    Route::get('/roles/{id}/delete', 'SecurityController@confirm')->name('roles_delete');
    Route::post('/roles/{id}/delete', 'RolesController@destroy');


    # PhysiotherpyExcerciseCategory Routes

    Route::get('physiotherpy_exercise_categories', ['as' => 'physiotherpy_exercise_categories.index', 'uses' => 'PhysiotherapyExerciseCategoryController@index']);
    Route::get('physiotherpy_exercise_categories/print', ['as' => 'physiotherpy_exercise_categories.print', 'uses' => 'PhysiotherapyExerciseCategoryController@printPhysiotherpyExerciseCategorie']);

    Route::get('/physiotherpy_exercise_categories/create', 'PhysiotherapyExerciseCategoryController@create')->name('physiotherpy_exercise_category_create');
    Route::post('/physiotherpy_exercise_categories/create', 'PhysiotherapyExerciseCategoryController@store');

    Route::get('/physiotherpy_exercise_categories/{id}', 'PhysiotherapyExerciseCategoryController@show')->name('physiotherpy_exercise_category_show');

    Route::get('/physiotherpy_exercise_categories/{id}/edit', 'PhysiotherapyExerciseCategoryController@edit')->name('physiotherpy_exercise_category_edit');
    Route::post('/physiotherpy_exercise_categories/{id}/edit', 'PhysiotherapyExerciseCategoryController@update')->name('physiotherpy_exercise_category_update');


    Route::post('/physiotherpy_exercise_categories/{id}', 'PhysiotherapyExerciseCategoryController@destroy')->name('physiotherpy_exercise_category_delete');


    # PhysiotherpyExcerciseCategory Routes

    Route::get('physiotherpy_exercises', ['as' => 'physiotherpy_exercises.index', 'uses' => 'PhysiotherapyExerciseController@index']);
    Route::get('/physiotherpy_exercises/print', 'PhysiotherapyExerciseController@printPhysiotherpyExercises')->name('physiotherpy_exercises.print');

    Route::get('/physiotherpy_exercises/create', 'PhysiotherapyExerciseController@create')->name('physiotherpy_exercise_create');
    Route::post('/physiotherpy_exercises/create', 'PhysiotherapyExerciseController@store');

    Route::get('/physiotherpy_exercises/{id}', 'PhysiotherapyExerciseController@show')->name('physiotherpy_exercise_show');

    Route::get('/physiotherpy_exercises/{id}/edit', 'PhysiotherapyExerciseController@edit')->name('physiotherpy_exercise_edit');
    Route::post('/physiotherpy_exercises/{id}/edit', 'PhysiotherapyExerciseController@update')->name('physiotherpy_exercise_update');


    Route::post('/physiotherpy_exercises/{id}', 'PhysiotherapyExerciseController@destroy')->name('physiotherpy_exercise_delete');

    Route::post('/physiotherpy_exercises-image', 'PhysiotherapyExerciseController@deleteSystemFile')->name('delete-exercise-images');


    # Permissions Routes
    Route::get('/permissions', 'PermissionsController@index')->name('permissions');
    Route::post('/permissions', 'PermissionsController@ajaxUpdate');
    Route::get('/permissions/print', 'PermissionsController@printPermission')->name('permissions.print');
    Route::get('/permissions/export/{type}', 'PermissionsController@exportPermissions')->name('permissions.export');

    Route::get('/permissions/create', 'PermissionsController@create')->name('permissions_create');
    Route::post('/permissions/create', 'PermissionsController@store');

    Route::get('/permissions/{id}/edit', 'PermissionsController@edit')->name('permissions_edit');
    Route::post('/permissions/{id}/edit', 'PermissionsController@update');


    Route::get('/permissions/roles/{id}/edit', 'PermissionsController@rolesEdit')->name('permissions_roles_edit');
    Route::post('/permissions/roles/{id}/edit', 'PermissionsController@rolesUpdate');

    Route::get('/permissions/{id}/delete', 'SecurityController@confirm')->name('permissions_delete');
    Route::post('/permissions/{id}/delete', 'PermissionsController@destroy');

    # Blogs Routes
    Route::get('/blogs', 'BlogsController@index')->name('blogs');

    Route::get('/blogs/create', 'BlogsController@create')->name('blogs_create');
    Route::post('/blogs/create', 'BlogsController@store');

    Route::get('/blogs/{id}', 'BlogsController@posts')->name('blogs_posts');

    Route::get('/blogs/{id}/edit', 'BlogsController@edit')->name('blogs_edit');
    Route::post('/blogs/{id}/edit', 'BlogsController@update');

    Route::get('/blogs/{id}/roles', 'BlogsController@roles')->name('blogs_roles');
    Route::post('/blogs/{id}/roles', 'BlogsController@updateRoles');

    Route::get('/blogs/{id}/delete', 'SecurityController@confirm')->name('blogs_delete');
    Route::post('/blogs/{id}/delete', 'BlogsController@destroy');

    # Posts Routes
    Route::get('/posts/{id}', 'PostsController@index')->name('posts');

    Route::get('/posts/create/{id}', 'PostsController@create')->name('posts_create');
    Route::post('/posts/create/{id}', 'PostsController@store');

    Route::get('/posts/{id}/edit', 'PostsController@edit')->name('posts_edit');
    Route::post('/posts/{id}/edit', 'PostsController@update');

    Route::get('/posts/{id}/graphics', 'PostsController@graphics')->name('posts_graphics');

    Route::get('/posts/{id}/delete', 'SecurityController@confirm')->name('posts_delete');
    Route::post('/posts/{id}/delete', 'PostsController@destroy');

    # Comments Routes
    Route::post('/comments/create/{id}', 'CommentsController@create')->name('comments_create');

    Route::get('/comments/{id}/edit', 'CommentsController@edit')->name('comments_edit');
    Route::post('/comments/{id}/edit', 'CommentsController@update');

    Route::get('/comments/{id}/delete', 'SecurityController@confirm')->name('comments_delete');
    Route::post('/comments/{id}/delete', 'CommentsController@destroy');


    # Database CRUD
    Route::get('/CRUD', 'CRUDController@index')->name('CRUD');

    Route::get('/CRUD/{table}', 'CRUDController@table')->name('CRUD_table');

    Route::get('/CRUD/{table}/create', 'CRUDController@create')->name('CRUD_create');
    Route::post('/CRUD/{table}/create', 'CRUDController@createRow');

    Route::get('/CRUD/{table}/{id}', 'CRUDController@row')->name('CRUD_edit');
    Route::post('/CRUD/{table}/{id}', 'CRUDController@saveRow');

    Route::get('/CRUD/{table}/{id}/delete', 'SecurityController@confirm')->name('CRUD_delete');
    Route::post('/CRUD/{table}/{id}/delete', 'CRUDController@deleteRow');

    # API
    Route::get('/API', 'APIController@index')->name('API');

    # File Manager
    Route::get('/files', 'FilesController@files')->name('files');

    Route::get('/files/upload', 'FilesController@showUpload')->name('files_upload');
    Route::post('/files/upload', 'FilesController@upload');

    Route::get('/documents/{file}/create', 'DocumentsController@showCreate')->name('documents_create');
    Route::post('/documents/{file}/create', 'DocumentsController@createDocument');

    Route::get('/documents/{slug}/edit', 'DocumentsController@edit')->name('documents_edit');
    Route::post('/documents/{slug}/edit', 'DocumentsController@update');

    Route::get('/documents/{slug}/delete', 'SecurityController@confirm')->name('documents_delete');
    Route::post('/documents/{slug}/delete', 'DocumentsController@delete');

    Route::get('/files/{file}/delete', 'SecurityController@confirm')->name('files_delete');
    Route::post('/files/{file}/delete', 'FilesController@delete');

    Route::get('/files/{file}/download', 'FilesController@fileDownload')->name('files_download');

    # Settings
    Route::get('/settings', 'SettingsController@edit')->name('settings');
    Route::post('/settings', 'SettingsController@update');

    # Profile
    Route::get('/profile', 'ProfileController@edit')->name('profile');
    Route::post('/profile', 'ProfileController@update');

    # About
    Route::get('/about', 'AboutController@index')->name('about');

    #Attendance routes
    Route::get('/attendances', 'AttendanceController@index')->name('attendances');
    Route::get('/leaves', 'AttendanceController@leaves')->name('attendance.leaves');
    Route::post('/leaves', 'AttendanceController@leaves')->name('attendance.leaves.ajax');
    Route::get('/leaves-edit/{id}', 'AttendanceController@editLeave')->name('leave_edit');
    Route::post('/leaves-edit/{id}/store', 'AttendanceController@editLeaveStore')->name('leave_edit_store');

    Route::get('/leaves/{id}/delete', 'SecurityController@confirm')->name('leave_delete');
    Route::post('/leaves/{id}/delete', 'AttendanceController@deleteLeave');

    Route::get('/attendances/print/{date}', 'AttendanceController@printAttendance')->name('attendances.print');
    Route::get('/attendance/export/{type}', 'AttendanceController@export')->name('attendance.export');


    Route::get('/attendance-leaves/print/{date?}', 'AttendanceController@printAttendanceLeaves')->name('attendances.print');
    Route::get('/attendance-leaves/export/{type}', 'AttendanceController@exportLeaves')->name('attendance.export');


    Route::post('/attendances', 'AttendanceController@index')->name('attendances.post');
    Route::get('/attendance/search', 'AttendanceController@search')->name('attendance.search');
    Route::get('/attendance/create', 'AttendanceController@create')->name('attendance.create');
    Route::post('/attendance/create', 'AttendanceController@store')->name('attendance.create');
    Route::get('/attendance/create/date/{date}', 'AttendanceController@create')->name('attendance.create.date');
    Route::post('/attendance/create/date/{date}', 'AttendanceController@store')->name('attendance.create.date');
    Route::get('/attendance/add-leave/{user_id?}', 'AttendanceController@addLeave')->name('attendance.add_leave');
    Route::post('/attendance/add-leave', 'AttendanceController@saveLeave')->name('attendance.add_leave');
    Route::get('/attendance/add-leave/{user_id}/{date}', 'AttendanceController@addLeave')->name('attendance.add_leave_date');
    Route::get('/attendance/add-leave-all', 'AttendanceController@addLeave')->name('attendance.add_leave_any');
    Route::post('/attendance/add-leave/{user_id}', 'AttendanceController@saveLeave')->name('attendance.add_leave');
    Route::get('/attendance/list_leave/{id}', 'AttendanceController@listLeaves')->name('attendance.listLeaves');
    Route::post('/attendance/list_leave/{id}', 'AttendanceController@listLeaves')->name('attendance.listLeaves.search');
    Route::post('/attendance', 'AttendanceController@ajaxUpdate')->name('attendance.ajax');

    #Issues routes
    Route::get('/issues', 'IssueController@index')->name('issues');
    Route::get('/issues/print', 'IssueController@printIssues')->name('issues.print');
    Route::get('/issues/export/{type}', 'IssueController@exportIssues')->name('issues.export');
    Route::get('/issue/create', 'IssueController@create')->name('issue.create');
    Route::post('/issue/create', 'IssueController@store')->name('issue.create');
    Route::get('/issue/edit/{issue_id}', 'IssueController@edit')->name('issue.edit');
    Route::get('/issue/view/{issue_id}', 'IssueController@view')->name('issue.view');
    Route::post('/issue/reply/{issue_id}', 'IssueController@reply')->name('issue.send_reply');
    Route::post('/issue/edit/{issue_id}', 'IssueController@update')->name('issue.edit');
    Route::get('/issue/{id}/delete', 'SecurityController@confirm')->name('issue.delete');
    Route::post('/issue/{id}/delete', 'IssueController@destroy');
    Route::post('/issue/change_status/{issue_id}', 'IssueController@changeStatus')->name('issue.change_status');
    Route::post('/issues', 'IssueController@ajaxUpdate');

    #Issues routes
    Route::get('/queries', 'IssueController@queries')->name('queries');
    Route::get('/query/view/{issue_id}', 'IssueController@view')->name('query.view');
    Route::get('/query/{id}/delete', 'SecurityController@confirm')->name('query.delete');
    Route::post('/query/{id}/delete', 'IssueController@destroy')->name('query.delete');

    #Department routes
    Route::get('/departments', 'DepartmentController@index')->name('departments');
    Route::get('/department/create', 'DepartmentController@create')->name('department_create');
    Route::post('/department/create', 'DepartmentController@store')->name('department_create');
    Route::get('/department/edit/{department_id}', 'DepartmentController@edit')->name('department_edit');
    Route::post('/department/edit/{department_id}', 'DepartmentController@update')->name('department_edit');
    Route::get('/department/view/{department_id}', 'DepartmentController@view')->name('department.view');
    Route::post('/department/reply/{department_id}', 'DepartmentController@reply')->name('department.send_reply');
    Route::get('/department/{id}/delete', 'SecurityController@confirm')->name('department_delete');
    Route::post('/department/{id}/delete', 'DepartmentController@destroy');
    Route::post('/departments', 'DepartmentController@ajaxUpdate');
    Route::get('/departments/print', 'DepartmentController@printDepartments')->name('departments.print');
    Route::get('/departments/export/{type}', 'DepartmentController@exportDepartments')->name('departments.export');

    #Document Type routes
    Route::get('/document_types', 'DocumentTypeController@index')->name('document_types');
    Route::post('/document_types', 'DocumentTypeController@index')->name('document_types');
    Route::get('/document_types/print', 'DocumentTypeController@printDocumentType')->name('document_types.print');
    Route::get('/document_types/export/{type}', 'DocumentTypeController@exportDocumentType')->name('document_types.print');

    Route::get('/document_type/create', 'DocumentTypeController@create')->name('document_type_create');
    Route::post('/document_type/create', 'DocumentTypeController@store')->name('document_type_create');
    Route::get('/document_type/edit/{document_type_id}', 'DocumentTypeController@edit')->name('document_type_edit');
    Route::post('/document_type/edit/{document_type_id}', 'DocumentTypeController@update')->name('document_type_edit');
    Route::get('/document_type/view/{document_type_id}', 'DocumentTypeController@view')->name('document_type.view');
    Route::post('/document_type/reply/{document_type_id}', 'DocumentTypeController@reply')->name('document_type.send_reply');
    Route::get('/document_type/{id}/delete', 'SecurityController@confirm')->name('document_type_delete');
    Route::post('/document_type/{id}/delete', 'DocumentTypeController@destroy');

    #Profession routes
    Route::get('/professions', 'ProfessionController@index')->name('professions');
    Route::post('/professions', 'ProfessionController@index')->name('professions');
    Route::get('/professions/print', 'ProfessionController@printProfessions')->name('professions.print');
    Route::get('/professions/export/{type}', 'ProfessionController@exportProfessions')->name('professions.export');
    Route::get('/professions/create', 'ProfessionController@create')->name('profession_create');
    Route::post('/professions/create', 'ProfessionController@store')->name('profession_create');
    Route::get('/professions/edit/{profession_id}', 'ProfessionController@edit')->name('profession_edit');
    Route::post('/professions/edit/{profession_id}', 'ProfessionController@update')->name('profession_edit');
    Route::get('/professions/view/{profession_id}', 'ProfessionController@view')->name('professions.view');
    Route::post('/professions/reply/{profession_id}', 'ProfessionController@reply')->name('professions.send_reply');
    Route::get('/professions/{id}/delete', 'SecurityController@confirm')->name('profession_delete');
    Route::post('/professions/{id}/delete', 'ProfessionController@destroy');

# Lab Tests Routes
    Route::get('/lab-tests', 'LabTestController@index')->name('lab-tests');
    Route::get('/lab-tests/import', 'LabTestController@import')->name('lab-tests.import');

    Route::get('/lab-tests/print', 'LabTestController@printTests')->name('lab-tests.print');
    Route::get('/lab-tests/export/{type}', 'LabTestController@export')->name('lab-tests.export');
    Route::get('/lab-test/add', 'LabTestController@create')->name('lab-test.create');
    Route::post('/lab-test/add', 'LabTestController@store')->name('lab-test.add.store');
    Route::get('/lab-test/{item_id}/edit', 'LabTestController@edit')->name('lab-test.edit');
    Route::post('/lab-test/{item_id}/edit', 'LabTestController@update')->name('lab-test.update');
    Route::get('/lab-test/{item_id}/delete', 'SecurityController@confirm')->name('lab-test.delete');
    Route::post('/lab-test/{item_id}/delete', 'LabTestController@destroy');


#   Lab Tests Attendent Routes
    Route::get('/lab_dashboard', 'DashboardController@index')->name('lab_dashboard');
    Route::get('/lab-test-patients', 'TokenController@lab_tests_patients')->name('lab-test-patients');
    Route::post('/lab-test-patients', 'TokenController@lab_tests_patients')->name('lab-test-patients');
    Route::get('/patient/{booking_id}/patient-details', 'TokenController@lab_patient_details')->name('patient.patient-details');
    Route::get('/patient/{booking_id}/lab-test-details', 'TokenController@lab_test_details')->name('patient.lab-details');
    Route::get('/patient/lab_test_report/{test_id}', 'TokenController@lab_test_report')->name('patient.lab_test_report');
    Route::post('/patient/lab_test_report', 'TokenController@lab_test_report')->name('patient.lab_test_report');
    Route::get('/patient/download_report/{test_id}', 'TokenController@download_report')->name('patient.download_report');

    #Discount Offers routes
    Route::get('/discount_offers', 'DiscountController@index')->name('discount_offers');
    Route::get('/discount_offers/print', 'DiscountController@printDiscountOffers')->name('discount_offers.print');
    Route::get('/discount_offers/export/{type}', 'DiscountController@exportDiscountOffers')->name('discount_offers.export');

    Route::get('/discount_offer/create', 'DiscountController@create')->name('discount_offer_create');
    Route::post('/discount_offer/create', 'DiscountController@store')->name('discount_offer_create');
    Route::get('/discount_offer/edit/{discount_offer_id}', 'DiscountController@edit')->name('discount_offer_edit');
    Route::post('/discount_offer/edit/{discount_offer_id}', 'DiscountController@update')->name('discount_offer_edit');
    Route::get('/discount_offer/view/{discount_offer_id}', 'DiscountController@view')->name('discount_offer.view');
    Route::post('/discount_offer/reply/{discount_offer_id}', 'DiscountController@reply')->name('discount_offer.send_reply');
    Route::get('/discount_offer/{id}/delete', 'SecurityController@confirm')->name('discount_offer_delete');
    Route::post('/discount_offer/{id}/delete', 'DiscountController@destroy');
    Route::post('/discount_offers', 'DiscountController@ajaxUpdate');



    #Patients History routes
    Route::get('/patient-history', 'PatientController@index')->name('patient-history');
    Route::post('/patient-history', 'PatientController@index')->name('patient-history-search');
    Route::get('/patient_details/{patient_id}', 'PatientController@view')->name('patient_details');
    Route::get('/patient/get-accommodation-details/{patient_id}', 'PatientController@accommodationDetails')->name('accommodation_details');
    Route::get('/patient/get-account-details/{patient_id}', 'PatientController@accountDetails')->name('account_details');

    #Booking registration routes
    Route::get('booking/registration/signup', 'BookingRegistrationController@create')->name('booking.registration.create');
    Route::post('booking/registration/signup', 'BookingRegistrationController@signupStore')->name('booking.registration.create.store');


    Route::get('booking/registration/personal_details/{user_id?}/{reregister?}', 'BookingRegistrationController@personalDetails')->name('booking.registration.personalDetails');

    Route::post('booking/registration/personal_details/{user_id?}/{reregister?}', 'BookingRegistrationController@personalDetailsStore')->name('booking.registration.personalDetails.store');
    Route::get('booking/registration/get_states/{id}','BookingRegistrationController@getStates');

    Route::get('booking/registration/health_issues/{user_id}/{reregister?}', 'BookingRegistrationController@healthIssues')->name('booking.registration.health_issues');
    Route::post('booking/registration/health_issues/{user_id}/{reregister?}', 'BookingRegistrationController@healthIssuesStore')->name('booking.registration.health_issues.store');

    Route::get('booking/registration/accommodation/{user_id}', 'BookingRegistrationController@accommodation')->name('booking.registration.accommodation');
    Route::post('booking/registration/accommodation/{user_id}', 'BookingRegistrationController@accommodationRequest')->name('booking.registration.accommodation_request');

    Route::get('booking/registration/payment/{user_id}', 'BookingRegistrationController@payment')->name('booking.registration.payment');
    Route::post('booking/registration/payment/{user_id}', 'BookingRegistrationController@paymentStore')->name('booking.registration.payment.store');

    Route::get('booking/registration/confirm/{user_id}', 'BookingRegistrationController@confirm')->name('booking.registration.confirm');
    Route::post('booking/registration/confirm/{user_id}', 'BookingRegistrationController@confirmStore')->name('booking.registration.confirm.store');

    Route::post('bookings-delete-members', 'BookingController@deleteMember')->name('booking.delete_member');
    #Booking Routes
    Route::any('ipd-bookings', 'BookingController@ipdIndex')->name('ipd.bookings.list');

    Route::get('accomodations', 'BookingController@patientsWithAccomodation')->name('accomodations');
    Route::post('accomodations', 'BookingController@patientsWithAccomodation')->name('accomodations.list');

    Route::get('pending-bookings/print', 'BookingController@printPendingBooking')->name('bookings.ipd.print');
    Route::get('pending-bookings/export/{type}', 'BookingController@exportPending')->name('bookings.ipd.export');
    Route::get('pending-bookings/export/{type}/{per_page}', 'BookingController@exportPending')->name('bookings.ipd.export');
    Route::get('pending-bookings/export/{type}/{per_page}/{page}', 'BookingController@exportPending')->name('bookings.ipd.export');



    Route::get('bookings/print', 'BookingController@printBookings')->name('bookings.print');
    Route::get('bookings/export/{type}', 'BookingController@export')->name('bookings.export');;
    Route::get('bookings/export/{type}/{per_page}', 'BookingController@export')->name('bookings.export');;
    Route::get('bookings/export/{type}/{per_page}/{page}', 'BookingController@export')->name('bookings.export');;

    Route::get('ipd-bookings/print', 'BookingController@printIpdBookings')->name('bookings.ipd.print');
    Route::get('ipd-bookings/export/{type}', 'BookingController@exportIpd')->name('bookings.ipd.export');
    Route::get('ipd-bookings/export/{type}/{per_page}', 'BookingController@exportIpd')->name('bookings.ipd.export');
    Route::get('ipd-bookings/export/{type}/{per_page}/{page}', 'BookingController@exportIpd')->name('bookings.ipd.export');

    Route::get('future-bookings/print', 'BookingController@printFutureBookings')->name('bookings.future.print');
    Route::get('future-bookings/export/{type}', 'BookingController@exportFuture')->name('bookings.future.export');
    Route::get('future-bookings/export/{type}/{per_page}', 'BookingController@exportFuture')->name('bookings.future.export');
    Route::get('future-bookings/export/{type}/{per_page}/{page}', 'BookingController@exportFuture')->name('bookings.future.export');


    Route::get('archived-bookings/export/{type}', 'BookingController@exportArchived')->name('bookings.exportArchived');;
    Route::get('archived-bookings/export/{type}/{per_page}', 'BookingController@exportArchived')->name('bookings.exportArchived');;
    Route::get('archived-bookings/export/{type}/{per_page}/{page}', 'BookingController@exportArchived')->name('bookings.exportArchived');;

    /*
        Route::post('bookings', 'BookingController@index')->name('bookings');*/
    Route::get('bookings', 'BookingController@index')->name('bookings');
    Route::post('bookings', ['as' => 'bookings.ajax', 'uses' => 'BookingController@ajaxUpdate']);
    Route::get('setUhid', ['as' => 'bookings.setUhid', 'uses' => 'BookingController@setUhid']);


    Route::get('booking/{booking_id}/show', 'BookingController@show')->name('booking.show');

    Route::get('future-bookings/{booking_id}/show', 'BookingController@show')->name('future.booking.show');
    Route::get('opd-bookings/{booking_id}/show', 'BookingController@show')->name('opd.booking.show');
    Route::get('ipd-bookings/{booking_id}/show', 'BookingController@show')->name('ipd.booking.show');

    Route::get('future-bookings/{booking_id}/personal_details', 'BookingController@personalDetails')->name('future.booking.personal_details');
    Route::get('booking/personal_details/{booking_id}', 'BookingController@personalDetails')->name('booking.personalDetails');
    Route::get('opd-bookings/personal_details/{booking_id}', 'BookingController@personalDetails')->name('opd.booking.personalDetails');
    Route::get('ipd-bookings/personal_details/{booking_id}', 'BookingController@personalDetails')->name('ipd.booking.personalDetails');
    Route::post('booking/personal_details/{booking_id}', 'BookingController@personalDetailsStore')->name('booking.personalDetails.store');


    Route::get('future-bookings/{booking_id}/health_issues', 'BookingController@healthIssues')->name('future.booking.health_issues');
    Route::get('booking/health_issues/{booking_id}', 'BookingController@healthIssues')->name('booking.health_issues');
    Route::get('opd-bookings/health_issues/{booking_id}', 'BookingController@healthIssues')->name('opd.booking.health_issues');
    Route::get('ipd-bookings/health_issues/{booking_id}', 'BookingController@healthIssues')->name('ipd.booking.health_issues');
    Route::post('booking/health_issues/{booking_id}', 'BookingController@healthIssuesStore')->name('booking.health_issues.store');

    Route::get('future-bookings/{booking_id}/accommodation', 'BookingController@accommodation')->name('future.booking.accommodation');
    Route::get('ipd-booking/accommodation/{booking_id}', 'BookingController@accommodation')->name('ipd.booking.accommodation');
    Route::get('booking/accommodation/{booking_id}', 'BookingController@accommodation')->name('booking.accommodation');
    Route::post('booking/accommodation/{booking_id}', 'BookingController@accommodation')->name('booking.accommodation.store');


    Route::get('future-bookings/{booking_id}/payment', 'BookingController@payment')->name('future.booking.payment');
    Route::get('booking/payment/{booking_id}', 'BookingController@payment')->name('booking.payment');
    Route::get('opd-bookings/payment/{booking_id}', 'BookingController@payment')->name('opd.booking.payment');
    Route::get('ipd-bookings/payment/{booking_id}', 'BookingController@payment')->name('ipd.booking.payment');
    Route::post('booking/payment/{booking_id}', 'BookingController@paymentStore')->name('booking.payment.store');

    Route::get('future-bookings/{booking_id}/confirm', 'BookingController@confirm')->name('future.booking.confirm');
    Route::get('booking/confirm/{booking_id}', 'BookingController@confirm')->name('booking.confirm');
    Route::get('opd-bookings/confirm/{booking_id}', 'BookingController@confirm')->name('opd.booking.confirm');
    Route::get('ipd-bookings/confirm/{booking_id}', 'BookingController@confirm')->name('ipd.booking.confirm');
    Route::post('booking/confirm/{booking_id}', 'BookingController@confirmStore')->name('booking.confirm.store');

    Route::get('future-bookings/{booking_id}/print-kid', 'BookingController@generatePatientCard')->name('future.booking.print_kid');
    Route::get('/booking/print-kid/{booking_id}', 'BookingController@generatePatientCard')->name('booking.print_kid');
    Route::get('/opd-bookings/print-kid/{booking_id}', 'BookingController@generatePatientCard')->name('opd.booking.print_kid');
    Route::get('/ipd-bookings/print-kid/{booking_id}', 'BookingController@generatePatientCard')->name('ipd.booking.print_kid');

    Route::get('/booking/print/patient-card/{booking_id}', 'BookingController@printPatientCard')->name('bookings.print_patient_card');
    Route::post('/booking/print/patient-card/{booking_id}', 'BookingController@printPatientCard')->name('bookings.print_patient_card');
    Route::get('/booking/get_booking_info/{booking_id}/{room_id}/{user_id}', 'BookingController@getBookingInfo')->name('booking.info');
    Route::get('get_booked_room_info/{room_id}/{bed_id}', 'AccommodationController@getBookedRoomInfo')->name('booked.room.info');
    Route::get('get_full_booked_room_info/{room_id}', 'AccommodationController@getFullBookedRoomInfo')->name('full.booked.room.info');


    Route::get('/booking/accomm_booking_form/{user_id}/{room_id}/{booking_id?}/{member_id?}/{r_id?}', 'BookingController@accommBookingForm')->name('accombooking.form');

    Route::post('/booking/accomm_booking_form/{user_id}/{room_id?}', 'BookingController@accommBookingFormStore')->name('accombookingstore.form');
    Route::post('booking/accommodation/{booking_id}', 'BookingController@accommodationRequest')->name('booking.accommodation_request');

    Route::post('/booking-booked-room-update', 'BookingController@bookedroomupdate')->name('accombookingstore.bookedroomupdate');

    Route::get('booking/allot-accommodation/{booking_id}', 'BookingController@allotRooms')->name('booking.allot.rooms');

    Route::get('future-bookings/allot-accommodation/{booking_id}', 'BookingController@allotRooms')->name('future.booking.allot.rooms');

    Route::get('booking/accommodation-print/{booking_id}', 'BookingController@accommodationPrint')->name('booking.accommodation.print');

    Route::get('booking/get_edit_accom_form/{booking_id}/{booking_room_id?}', 'BookingController@getEditAccomform')->name('booking.get.edit.accom.form');

    Route::post('booking/delete_booked_room/{booking_room_id}', 'BookingController@deleteBookedRoom')->name('booking.delete.booked.room');


    Route::get('booking/allot-room-form/{booking_id}', 'BookingController@allotRoomForm')->name('booking.allot.room.form');
    Route::get('booking/allot-room-form/{booking_id}/{member_id}', 'BookingController@allotRoomForm')->name('booking.allot.room.form');


    Route::get('booking/edit-room-form/{booking_room_id}', 'BookingController@editRoomForm')->name('booking.edit.room.form');
    Route::get('booking/edit-room-form/{booking_room_id}', 'BookingController@editRoomForm')->name('booking.edit.room.form');

    Route::post('booking/allot-accommodation/{booking_id}', 'BookingController@allotRoomsStore')->name('booking.allot.rooms.store');

    Route::get('/booking/{id}/delete', 'BookingController@confirmDelete')->name('bookings.delete');
    Route::post('/booking/{id}/delete', 'BookingController@destroy');

    Route::get('/token-list', 'BookingController@tokenList')->name('token.list');
    Route::post('/patient-tokens/ajax', 'BookingController@tokenAjaxUpdate')->name('token.ajax');

    Route::get('/token-list/{id}/print', 'BookingController@printPatientToken')->name('tokens.print.token');


    Route::get('/token-list/export/{type}', 'BookingController@exportTokens')->name('tokens.export');


    Route::get('token-list-all/print', 'BookingController@printTokenList')->name('bookings.ipd.print');
    Route::get('token-list-all/export/{type}', 'BookingController@exportTokenList')->name('bookings.ipd.export');
    Route::get('token-list-all/export/{type}/{per_page}', 'BookingController@exportTokenList')->name('bookings.ipd.export');
    Route::get('token-list-all/export/{type}/{per_page}/{page}', 'BookingController@exportTokenList')->name('bookings.ipd.export');


    Route::get('/booking/opd-generate-token', 'BookingController@generateToken')->name('bookings.opd.generate_token');
    Route::get('/booking/generate-token', 'BookingController@generateToken')->name('bookings.generate_token');

    Route::post('/booking/generate-token', 'BookingController@generateToken')->name('bookings.generate_token');
    Route::get('/booking/generate-patient-card', 'BookingController@generatePatientCard')->name('bookings.generate_card');
    Route::post('/booking/generate-patient-card', 'BookingController@generatePatientCard')->name('bookings.generate_card');
    Route::post('/booking/generate-token', 'BookingController@generateToken')->name('bookings.generate_token');
    Route::get('/booking/generate-token/{booking_id}', 'BookingController@generateToken')->name('booking.generate_token');
    Route::get('/booking/opd-generate-token/{booking_id}', 'BookingController@generateToken')->name('booking.opd.generate_token');

    Route::post('/booking/print-token', 'BookingController@printToken')->name('bookings.print_token');
    Route::post('/booking/print-token/{booking_id}', 'BookingController@printToken')->name('bookings.print_token.individual');
    Route::get('/booking/print-token', 'BookingController@printToken')->name('bookings.print_token');


    //Opd Tokens
    Route::get('/opd-token-list', 'BookingController@opdTokenList')->name('opd-tokens');
    Route::post('/opd-token-list', 'BookingController@opdTokenAjaxUpdate')->name('opd.token.ajax');

    Route::get('opd-token-list/print', 'BookingController@printOpdTokenList')->name('bookings.ipd.print');
    Route::get('opd-token-list/export/{type}', 'BookingController@exportOpdTokenList')->name('bookings.ipd.export');
    Route::get('opd-token-list/export/{type}/{per_page}', 'BookingController@exportOpdTokenList')->name('bookings.ipd.export');
    Route::get('opd-token-list/export/{type}/{per_page}/{page}', 'BookingController@exportOpdTokenList')->name('bookings.ipd.export');



    Route::get('/booking/generate-opd-token', 'BookingController@generateOpdToken')->name('bookings.generate_opd_token');
    Route::post('/booking/generate-opd-token', 'BookingController@generateOpdToken')->name('bookings.generate_opd_token');
    Route::get('/token-list/{id}/print', 'BookingController@printPatientToken')->name('tokens.print.opd.token');
    Route::get('/token-list/export/{type}', 'BookingController@exportTokens')->name('opd.tokens.export');

    Route::get('/tokens/covert/{id}', 'BookingController@opdTokensConvert')->name('opd.tokens.convert');

    Route::get('/bookings/{id}/delete-opd-token', 'SecurityController@confirm')->name('bookings.delete_opd_token');
    Route::post('/bookings/{id}/delete-opd-token', 'BookingController@deleteOpdToken');
    Route::post('/booking/print-opd-token', 'BookingController@printOpdToken')->name('bookings.print_opd_token');
    Route::post('/booking/print-opd-token/{booking_id}', 'BookingController@printOpdToken')->name('bookings.print_opd_token.individual');
    Route::get('/booking/print-opd-token', 'BookingController@printOpdToken')->name('bookings.print_token');
    Route::get('/opd-token-list/{id}/print', 'BookingController@printOpdPatientToken')->name('opd.tokens.print.token');
    Route::get('/opd-token-list/{id}/print-bill', 'BookingController@printOpdPatientTokenBill')->name('opd.tokens.print.token-bill');


    Route::get('/booking/account/{booking_id}', 'BookingController@account')->name('bookings.account');
    Route::get('/opd-bookings/account/{booking_id}', 'BookingController@account')->name('opd.bookings.account');
    Route::get('/ipd-bookings/account/{booking_id}', 'BookingController@account')->name('ipd.bookings.account');

    Route::post('/booking/account/{booking_id}', 'BookingController@accountStore')->name('bookings.account.store');

    Route::get('/booking/account-print/{booking_id}', 'BookingController@accountPrint')->name('bookings.account.print');


    Route::get('/bookings/{id}/delete-token', 'SecurityController@confirm')->name('bookings.delete_token');
    Route::post('/bookings/{id}/delete-token', 'BookingController@deleteToken');


    Route::get('/booking/discharge-patient-billing', 'BookingController@dischargeBillings')->name('bookings.discharge-patient-billing');
    Route::post('/booking/discharge-patient-billing', 'BookingController@dischargeBillings')->name('bookings.discharge-patient-billing-search');


    Route::get('/booking/discharge-patient-billing/{transaction_id}', 'BookingController@dischargeBillings')->name('bookings.discharge-patient-billing-individual');
    Route::post('/booking/print-bill/{user_id}', 'BookingController@printBill')->name('bookings.print_bill');
    Route::post('/booking/generate-bill/{id}', 'BookingController@generateBill')->name('bookings.generate_bill');
    
    Route::get('/booking/get-accommodation-billing-details/{booking_id}/{discharge?}', 'BookingController@getAccommodationDetails')->name('bookings.accommodation-billing-details');
    Route::get('/booking/get-services-billing-details/{transaction_id}/{discharge?}', 'BookingController@getServicesDetails')->name('bookings.services-billing-details');
    Route::get('/booking/get-paid-billing-details/{transaction_id}', 'BookingController@getPaidDetails')->name('bookings.paid-billing-details');

    Route::get('/booking/get-discount-details/{booking_id}', 'BookingController@getDiscountDetails')->name('bookings.discount-details');
    Route::get('/booking/add-discount/{booking_id}', 'BookingController@addDiscount')->name('bookings.add-discount');
    Route::post('/booking/avail-discount/{booking_id}', 'BookingController@availDiscount')->name('bookings.avail-discount');
    Route::post('/booking/delete-discount/{discount_id}', ['as' => 'delete.discount', 'uses' => 'BookingController@deleteDiscount']);
    Route::get('/booking/pay/{booking_id}/{discharge?}', 'BookingController@payDueAmount')->name('bookings.pay-discount');
    Route::post('/booking/pay/{booking_id}', 'BookingController@payDueAmountStore')->name('bookings.pay-discount-store');


    #Bills routes
    Route::get('/bills', 'BillController@index')->name('bills');
    
    Route::post('/bills', 'BillController@ajaxUpdate')->name('bills.ajax');
    
    Route::get('/bill/view/{bill_id}', 'BillController@view')->name('bills.view');
    Route::get('/bill/{id}/delete', 'SecurityController@confirm')->name('bills.delete');
    Route::post('/bill/{id}/delete', 'BillController@destroy');
    Route::post('/bills', 'BillController@ajaxUpdate');
    Route::get('/bills/print', 'BillController@printBills')->name('bills.print');    
    Route::get('/bill/{id}/print', 'BillController@print')->name('bills.bill_print');
    Route::get('/bills/export/{type}', 'BillController@exportBills')->name('bills.export');

    Route::get('/booking/get-discount-details-discharge/{booking_id}', 'BookingController@getDiscountDetailsWithoutBill')->name('bookings.discount-details');
   


//Misc routes
 Route::post('/booking/save-misc', 'BookingController@saveMisc')->name('bookings.save-misc');


    Route::get('/user/get-feedback/{booking_id}', 'BookingController@getFeedbackForm')->name('bookings.feedback-form');
    Route::post('/user/submit-feedback/{booking_id}', 'BookingController@submitFeedbackForm')->name('bookings.submit-feedback-form');

    Route::get('/user/get-noc/{user_id}', 'BookingController@getNoc')->name('bookings.noc');
    Route::get('/user/get-diet-details/{user_id}', 'BookingController@getDietPrices')->name('user.diet-price-details');
    Route::get('/user/get-daily-diet-details/{daily_diet_id}', 'BookingController@getDailyDietDetails')->name('user.daily-diet-details');
    Route::get('/user/get-treatments-details/{page}/{user_id}', 'BookingController@getTreatmentDetails')->name('user.treatment-details');
    Route::get('/user/treatmemt_detail_print/{page}/{booking_id}', 'BookingController@getTreatmentDetailsPrint')->name('user.treatment-details-print');
    Route::get('user/get_all_lab_details/{page}/{booking_id}', 'BookingController@getallLabDetails')->name('user.all-lab-details');
    Route::get('/user/lab_detail_print/{page}/{booking_id}', 'BookingController@getLabDetailsPrint')->name('user.treatment-details-print');


    Route::get('/user/check_treatment_status/{booking_id}', 'BookingController@checkTreatmentStatus')->name('user.check-treatments-status');
    Route::get('/admin/user/get-lab-details/{user_id}/{discharge?}', 'BookingController@getLabDetails')->name('user.lab-details');


    Route::get('/user/get-all-treatments', 'PatientController@')->name('user.all-treatment-details');
    Route::get('/booking/treatment-tokens', 'BookingController@treatmentTokens')->name('booking.treatment-tokens');
    Route::post('/booking/treatment-tokens', 'BookingController@treatmentTokens')->name('bookings.treatment-tokens');

    Route::get('treatment-token-list/print', 'BookingController@printTreatmentTokenList')->name('bookings.ipd.print');
    Route::get('treatment-token-list/export/{type}', 'BookingController@exportTreatmentTokenList')->name('bookings.ipd.export');
    Route::get('treatment-token-list/export/{type}/{per_page}', 'BookingController@exportTreatmentTokenList')->name('bookings.ipd.export');
    Route::get('treatment-token-list/export/{type}/{per_page}/{page}', 'BookingController@exportTreatmentTokenList')->name('bookings.ipd.export');


    //Follow Ups
    Route::get('/bookings/follow-ups', 'BookingController@followups')->name('bookings.follow-ups');
    Route::get('/follow-ups/export/{type}', 'BookingController@followupsExport')->name('bookings.follow-ups.export');


    //Patients & Tokens Routes
    Route::get('/patients', 'BookingController@index')->name('patients');
    Route::post('/patients', 'BookingController@ajaxUpdate')->name('patients.post');

    Route::get('/summary/{id}', 'TokenController@summary')->name('summary');
    Route::get('/print-summary/{id}', 'TokenController@printsummary')->name('print.summary');
    Route::post('/summary/{id}/send-in-mail', 'TokenController@sendEmailSummary')->name('summary.send-in-mail');
    Route::get('/archived-summary/{id}', 'TokenController@archived_summary')->name('archived-summary');
    Route::get('/print_archived_summary/{id}', 'TokenController@print_archived_summary')->name('print.archived_summary');

    Route::get('/ayurved', 'TokenController@ayurved')->name('ayurved');

    Route::get('/patient-diet-chart/{patient_id}', 'TokenController@patientDietChart')->name('patient.diet-chart');
    Route::get('/patient/edit-diet-chart/{patient_id}', 'TokenController@editDietChart')->name('patient.edit-diet-chart');
    Route::get('/patient/diet-chart/{patient_id}/delete', 'SecurityController@confirm')->name('diet_chart.delete');
    Route::post('/patient/diet-chart/{patient_id}/delete', 'TokenController@deleteDietChart');

    Route::post('/patient-diet-chart/{patient_id}', 'TokenController@patientDietChart')->name('patient.diet-chart');

    Route::get('/add-patient-diet-chart/{patient_id}', 'TokenController@patientDietForm')->name('add-patient-diet-chart-details');
    Route::post('/add-patient-diet-chart/{patient_id}', 'TokenController@patientDietStore')->name('add-patient-diet-chart-details');

    Route::get('/patient-treatment/{patient_id}', 'TokenController@allotTreatment')->name('patient.treatment');
    Route::post('/patient-treatment/{patient_id}', 'TokenController@allotTreatmentStore')->name('patient.treatment.store');
    Route::post('/patient-detail-ajax/{patient_id}', 'TokenController@savePatientDetails')->name('patient.details.ajax.store');


    /*    Route::post('/patients', 'TokenController@searchPatients')->name('patients.post');*/

    Route::get('/tokens', 'TokenController@tokens')->name('tokens');
    Route::post('/patient/search', 'BookingController@searchPatient')->name('bookings.search_patient');
    Route::get('/patient/{patient_id}/show', 'TokenController@showPatient')->name('patient.show');

    Route::get('/patient/{patient_id}/diagnosis', 'TokenController@diagnosePatient')->name('patient.diagnosis');


    Route::post('/patient/{patient_id}/diagnosis', 'TokenController@diagnosePatientStore')->name('patient.diagnosis.store');

    Route::post('/token/get-patient-details/{patient_id}', 'TokenController@getPatientDetails')->name('patient.details');
    Route::post('/token/post-patient-details/{patient_id}', 'TokenController@storePatientDetails')->name('patient.details.store');
    Route::get('/patient/{patient_id}/vital_data', 'TokenController@vitalData')->name('patient.vital_data');
    Route::get('/patient/{patient_id}/ayurvedic_vital_data', 'TokenController@ayurvedvitalData')->name('patient.ayurvedic_vital_data');
    Route::get('/patient/{patient_id}/physiotherapy_vital_data', 'TokenController@physiotherpyVitalData')->name('patient.physiotherpy_vital_data');
    Route::post('/patient/{patient_id}/physiotherapy_vital_data', 'TokenController@physiotherpyVitalDataStore')->name('patient.physiotherpy_vital_data_store');
    Route::get('/get/joint/subcat', 'TokenController@getJointSubCat');
    Route::get('/get/joint/getHtml', 'TokenController@getHtml');

    Route::post('/patient/{patient_id}/vital_data', 'TokenController@storeVitalData')->name('patient.store_vital_data');

    Route::get('/patient/{patient_id}/treatment_history', 'TokenController@treatmentHistory')->name('patient.treatment_history');
    Route::post('/patient/{patient_id}/treatment_history/store', 'TokenController@treatmentHistory')->name('patient.treatment_history_store');

    Route::get('patient/print-treatment/{treatment_id}', 'TokenController@printTreatment')->name('patient.print_treatment');
    Route::get('booking/print-treatment/{treatment_id}', 'BookingController@printTreatment')->name('patient.print_treatment');

    Route::get('/patient/edit-treatment/{treatment_id}', 'TokenController@editTreatment')->name('patient.treatment_edit');
    Route::post('/patient/edit-treatment/{treatment_id}', 'TokenController@updateTreatment')->name('patient.treatment_edit');
    Route::get('/treatment-token/{id}/delete', 'SecurityController@confirm')->name('treatment_token.delete');
    Route::post('/treatment-token/{id}/delete', 'TokenController@deleteTreatmentToken');


    Route::get('/patient/{token_id}/ayurved_vital_data', 'TokenController@ayurvedVitalData')->name('patient.ayurved_vital_data');
    Route::post('/patient/{patient_id}/ayurved_vital_data', 'TokenController@storeAyurvedVitalData')->name('patient.store_ayurved_vital_data');

    Route::post('/pat-treatment-update-ajax/{pat_treatment_id}', 'TokenController@updatePatientTreatment')->name('patient.patient_treatment.update');
    Route::post('/treatment-token-update-ajax/{pat_treatment_id}', 'TokenController@updateTreatmentToken')->name('patient.patient_treatment_token.update');

//Patient Lab Test Routes
    Route::get('/patient/{patient_id}/lab-tests', 'TokenController@listLabTest')->name('patient_lab_test.index');
    Route::get('/patient/lab-test/{patient_id}/add', 'TokenController@addLabTest')->name('patient_lab_test.add');
    Route::post('/patient/lab-test/{patient_id}/add', 'TokenController@storeLabTest')->name('patient_lab_test.add');
    Route::get('/patient/lab-test/{lab_test_id}/edit', 'TokenController@editLabTest')->name('patient_lab_test.add');
    Route::post('/patient/lab-test/{lab_test_id}/edit', 'TokenController@updateLabTest')->name('patient_lab_test.add');

    Route::get('/patient/lab-test/{lab_test_id}/delete', 'SecurityController@confirm')->name('patient_lab_test.delete');
    Route::post('/patient/lab-test/{lab_test_id}/delete', 'TokenController@deleteLabTest');
    Route::get('patient/print-lab-test/{id}', 'TokenController@printLabTest')->name('patient.print_lab_test');


    //Discharge/Recommendations/FollowUp Routes
    Route::get('/patient/discharge/{token_id}', 'TokenController@discharge')->name('discharge.patient');
    Route::post('/patient/discharge/{token_id}', 'TokenController@dischargeStore')->name('discharge.patient.store');
    Route::get('/print-patient-discharge/{token_id}', 'TokenController@printDischarge')->name('print.discharge.patient');

    //RecommendExercises Routes
    Route::get('/recommend-exercise/assign/{token_id}', 'TokenController@assign')->name('recommend-exercise.assign');
    Route::post('/recommend-exercise/assign', 'TokenController@recommendByAjax')->name('recommend-exercise.assign.ajax');
    Route::get('/recommend-exercise/print/{exercise_id}', 'TokenController@printExercise')->name('recommend-exercise.print');

    Route::get('/attachments/{booking_id}', 'TokenController@attachments')->name('attachments');
    Route::post('/attachments/{booking_id}', 'TokenController@attachmentStore')->name('attachments.store');
    Route::delete('/attachment/{id}/delete', 'TokenController@attachmentDestroy')->name('attachments.destroy');
    Route::post('/attachment/{id}/send-in-mail', 'TokenController@sendEmail')->name('attachments.send-in-mail');

    //Treatment Tokens

    Route::get('/treatment/tokens', 'TreatmentController@treatmentTokens')->name('treatment_tokens');
    Route::post('/treatment/tokens', 'TreatmentController@treatmentTokens')->name('treatment_tokens');
    Route::post('/patient/treatment/{treatment_id}/update', 'TreatmentController@updatePatientTreatment')->name('treatment_tokens.update');

    #Accommodation Routes(Buildings and Rooms and External Services)
    Route::get('accommodation/building/create', ['as' => 'building.create', 'uses' => 'AccommodationController@createBuilding']);
    Route::post('accommodation/building/create', ['as' => 'building.store', 'uses' => 'AccommodationController@storeBuilding']);
    Route::get('accommodation/buildings', ['as' => 'buildings', 'uses' => 'AccommodationController@listBuilding']);
    Route::post('accommodation/buildings', ['as' => 'buildings', 'uses' => 'AccommodationController@listBuilding']);

    Route::get('accommodation/building/print', ['as' => 'buildings.print', 'uses' => 'AccommodationController@printBuildings']);
    Route::get('accommodation/building/export/{type}', ['as' => 'buildings.export', 'uses' => 'AccommodationController@exportBuildings']);


    Route::get('/accommodation/building/{building_id}/delete', 'SecurityController@confirm')->name('building.delete');
    Route::post('/accommodation/building/{building_id}/delete', 'AccommodationController@deleteBuilding');

    Route::get('accommodation/building/{building_id}/edit', ['as' => 'building.edit', 'uses' => 'AccommodationController@editBuilding']);
    Route::post('accommodation/building/{building_id}/update', ['as' => 'building.update', 'uses' => 'AccommodationController@updateBuilding']);

    Route::get('accommodation/room_type/create', ['as' => 'room_type.create', 'uses' => 'AccommodationController@createRoomtype']);
    Route::post('accommodation/room_type/create', ['as' => 'room_type.store', 'uses' => 'AccommodationController@storeRoomtype']);
    Route::get('accommodation/room_type', ['as' => 'room_types', 'uses' => 'AccommodationController@listRoomtype']);
    Route::post('accommodation/room_type', ['as' => 'room_types_post', 'uses' => 'AccommodationController@listRoomtype']);
    Route::get('accommodation/room_type/print', ['as' => 'room_types.print', 'uses' => 'AccommodationController@printRoomtype']);
    Route::get('accommodation/room_type/export/{type}', ['as' => 'room_types.export', 'uses' => 'AccommodationController@exportRoomtype']);

    Route::get('/accommodation/room_type/{room_type_id}/delete', 'SecurityController@confirm')->name('room_type.delete');
    Route::post('/accommodation/room_type/{room_type_id}/delete', 'AccommodationController@deleteRoomtype');

    Route::get('accommodation/room_type/{room_type_id}/edit', ['as' => 'room_type.edit', 'uses' => 'AccommodationController@editRoomtype']);
    Route::post('accommodation/room_type/{room_type_id}/update', ['as' => 'room_type.update', 'uses' => 'AccommodationController@updateRoomtype']);



    Route::get('updateallBooking', ['as' => 'updateallBooking', 'uses' => 'BookingController@updateallBooking']);

    Route::get('accommodation/room/create', ['as' => 'room.create', 'uses' => 'AccommodationController@createRoom']);
    Route::post('accommodation/room/create/old', ['as' => 'room.create.store', 'uses' => 'AccommodationController@storeRoomold']);
    Route::post('accommodation/room/create', ['as' => 'room.store', 'uses' => 'AccommodationController@storeRoom']);
    Route::get('accommodation/rooms', ['as' => 'rooms', 'uses' => 'AccommodationController@listRoom']);
    Route::get('accommodation/rooms/print', ['as' => 'rooms.print', 'uses' => 'AccommodationController@printListRooms']);
    Route::get('accommodation/rooms/export/{type}', ['as' => 'rooms.export', 'uses' => 'AccommodationController@exportListRooms']);
    Route::post('accommodation/rooms', ['as' => 'rooms', 'uses' => 'AccommodationController@roomAjaxUpdate']);
    Route::delete('accommodation/room/{room_id}/delete', ['as' => 'room.delete', 'uses' => 'AccommodationController@deleteRoom']);
    Route::get('accommodation/room/{room_id}/edit', ['as' => 'room.edit', 'uses' => 'AccommodationController@editRoom']);

    Route::get('accommodation/room/{room_id}/services', ['as' => 'room.services', 'uses' => 'AccommodationController@editRoomServices']);
    Route::post('accommodation/room/{room_id}/services', ['as' => 'room.services.store', 'uses' => 'AccommodationController@storeRoomServices']);

    Route::post('accommodation/room/{room_id}/update', ['as' => 'room.update', 'uses' => 'AccommodationController@updateRoom']);

    Route::get('accommodation/room-status', ['as' => 'accommodation.roomStatus', 'uses' => 'AccommodationController@accommodationStatus']);

    Route::post('accommodation/room-status', ['as' => 'accommodation.room_status', 'uses' => 'AccommodationController@accommodationStatus']);

    Route::get('accommodation/external_service/create', ['as' => 'external_service.create', 'uses' => 'AccommodationController@createExternalServices']);
    Route::post('accommodation/external_service/create', ['as' => 'external_service.store', 'uses' => 'AccommodationController@storeExternalServices']);
    Route::get('accommodation/external_services', ['as' => 'external_services', 'uses' => 'AccommodationController@listExternalServices']);
    Route::post('accommodation/external_services', ['as' => 'external_services', 'uses' => 'AccommodationController@listExternalServices']);
    Route::get('accommodation/external_services/{service_id}/delete', ['as' => 'external_service.delete', 'uses' => 'AccommodationController@deleteExternalServices']);
    Route::get('accommodation/external_services/{service_id}/edit', ['as' => 'external_service.edit', 'uses' => 'AccommodationController@editExternalServices']);
    Route::post('accommodation/external_services/{service_id}/update', ['as' => 'external_service.update', 'uses' => 'AccommodationController@updateExternalServices']);

    Route::get('accommodation/external_service/print', ['as' => 'external_service.print', 'uses' => 'AccommodationController@printExternalServices']);
    Route::get('accommodation/external_service/export/{type}', ['as' => 'external_service.print', 'uses' => 'AccommodationController@exportExternalServices']);

    Route::get('accommodation/block-rooms', ['as' => 'block-rooms', 'uses' => 'AccommodationController@blockRooms']);
    Route::post('accommodation/block-rooms', ['as' => 'block-rooms.store', 'uses' => 'AccommodationController@blockRoomsStore']);


    /*Hospital Info*/
    Route::get('hospital-info', [
        'as' => 'admin.hospital_info', 'uses' => 'HospitalInfoController@index'
    ]);
    Route::post('hospital-info', [
        'as' => 'admin.hospital_info.store', 'uses' => 'HospitalInfoController@store'
    ]);
    /*Staff Departments*/
    Route::get('staff-departments', [
        'as' => 'admin.staff_departments',
        'uses' => 'StaffDepartmentController@index'
    ]);

    Route::get('/staff-departments/print', 'StaffDepartmentController@printStaffDepartments')->name('staff.departments.print');
    Route::get('/staff-departments/export/{type}', 'StaffDepartmentController@exportStaffDepartments')->name('staff.departments.export');
    Route::post('staff-departments', [
        'as' => 'admin.staff_departments.search', 'uses' => 'StaffDepartmentController@ajaxUpdate',
    ]);
    Route::get('staff-departments/add', [
        'as' => 'admin.staff_departments.add', 'uses' => 'StaffDepartmentController@create',
    ]);
    Route::post('staff-departments/add', [
        'as' => 'admin.staff_departments.add', 'uses' => 'StaffDepartmentController@store',
    ]);
    Route::get('staff-departments/{id}/edit', [
        'as' => 'admin.staff_departments.edit', 'uses' => 'StaffDepartmentController@edit',
    ]);
    Route::post('staff-departments/{id}/edit', [
        'as' => 'admin.staff_departments.update', 'uses' => 'StaffDepartmentController@update',
    ]);
    Route::get('/staff-departments/{id}/delete', 'SecurityController@confirm')->name('staff_departments.delete');
    Route::post('/staff-departments/{id}/delete', 'StaffDepartmentController@destroy');

    Route::post('/staff-departments/ajax', 'StaffDepartmentController@ajaxUpdate');

    /*Hospital Tax Details*/
    Route::get('tax-details', [
        'as' => 'admin.tax_details',
        'uses' => 'TaxDetailController@index'
    ]);
    Route::post('/tax-details', 'TaxDetailController@ajaxUpdate');

    Route::get('tax-details/print', [
        'as' => 'admin.tax_details.print',
        'uses' => 'TaxDetailController@printTaxDetails'
    ]);


    Route::get('tax-details/export/{type}', [
        'as' => 'admin.tax_details.export',
        'uses' => 'TaxDetailController@exportTaxDetails'
    ]);

    Route::get('tax-details/add', [
        'as' => 'admin.tax_details.add', 'uses' => 'TaxDetailController@create',
    ]);
    Route::post('tax-details/add', [
        'as' => 'admin.tax_details.add', 'uses' => 'TaxDetailController@store',
    ]);
    Route::get('tax-details/{id}/edit', [
        'as' => 'admin.tax_details.edit', 'uses' => 'TaxDetailController@edit',
    ]);
    Route::post('tax-details/{id}/edit', [
        'as' => 'admin.tax_details.update', 'uses' => 'TaxDetailController@update',
    ]);
    Route::get('/tax-details/{id}/delete', 'SecurityController@confirm')->name('tax_details.delete');
    Route::post('/tax-details/{id}/delete', 'TaxDetailController@destroy');



    /*Hospital Bank Account Details*/
    Route::get('bank-accounts', [
        'as' => 'admin.hospital_bank_account', 'uses' => 'HospitalBankaccountController@index',
    ]);

    Route::get('bank-accounts/print', [
        'as' => 'admin.hospital_bank_account.print', 'uses' => 'HospitalBankaccountController@printHospitalBankAccount',
    ]);

    Route::get('bank-accounts/export/{type}', [
        'as' => 'admin.hospital_bank_account.export', 'uses' => 'HospitalBankaccountController@exportHospitalBankAccount',
    ]);

    Route::get('bank-accounts/add', [
        'as' => 'admin.hospital_bank_account.add', 'uses' => 'HospitalBankaccountController@create',
    ]);
    Route::post('bank-accounts/add', [
        'as' => 'admin.hospital_bank_account.add', 'uses' => 'HospitalBankaccountController@store',
    ]);
    Route::get('bank-accounts/{account_id}/edit', [
        'as' => 'admin.hospital_bank_account.edit', 'uses' => 'HospitalBankaccountController@edit',
    ]);
    Route::post('bank-accounts/{account_id}/edit', [
        'as' => 'admin.hospital_bank_account.update', 'uses' => 'HospitalBankaccountController@update',
    ]);
    Route::get('/bank-accounts/{account_id}/delete', 'SecurityController@confirm')->name('hospital_bankaccount.delete');
    Route::post('/bank-accounts/{account_id}/delete', 'HospitalBankaccountController@destroy');
    Route::post('/bank-accounts', 'HospitalBankaccountController@ajaxUpdate');

    /*Consultation Charges*/
    Route::get('consultation-charges', [
        'as' => 'admin.consultation_charges', 'uses' => 'ConsultationChargeController@index',
    ]);
    Route::get('consultation-charges/add', [
        'as' => 'admin.consultation_charges.create', 'uses' => 'ConsultationChargeController@create',
    ]);
    Route::post('consultation-charges', [
        'as' => 'admin.consultation_charges.store', 'uses' => 'ConsultationChargeController@store',
    ]);


    /*Email Templates*/

    Route::get('/email-templates/print', 'EmailTemplateController@printEmailTemplate')->name('email-templates.print');
    Route::get('/email-templates/export/{type}', 'EmailTemplateController@exportEmailTemplate')->name('email-templates.export');


    Route::resource('email-templates', 'EmailTemplateController', [
        'as' => 'email_templates',
        'names' => [
            'index' => 'email_templates',
            'create' => 'email_templates.create',
            'store' => 'email_templates.store',
            'edit' => 'email_templates.edit',
        ]
    ]);
    Route::post('/email-templates', 'EmailTemplateController@index')->name('email-templates.post');
    Route::post('/email-templates/store', 'EmailTemplateController@store')->name('email-templates.store');
    Route::get('/email-templates/{id}/delete', 'SecurityController@confirm')->name('email_templates.destroy');
    Route::post('/email-templates/{id}/delete', 'EmailTemplateController@destroy');
    Route::post('/email-template/group-events', 'EmailTemplateController@getEvents');


    /*Followup Settings*/
    Route::resource('followup-settings', 'FollowupSettingController');
    Route::get('/followup-settings/{id}/delete', 'SecurityController@confirm')->name('followp_settings.destroy');
    Route::post('/followup-settings/{id}/delete', 'FollowupSettingController@destroy');

});


/**
Edit and Delete by Doctor
 **/
Route::delete('patient/delete/{id}',array('uses' => 'Laralum\TokenController@deleteDiagnose', 'as' => 'patient.diagnosis.delete'));
