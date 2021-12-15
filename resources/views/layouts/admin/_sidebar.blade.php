{{--@if(\Auth::user()->isAdmin())
    <div class="item item_icon {{ \App\Settings::getActiveClass('admin.permissions_management') }}">
        <div class="header"><i class="fa fa-user"></i> {{ trans('laralum.permissions') }}</div>
        <div class="menu">
            <a href="{{ route('Laralum::permissions') }}"
               class="item {{ \Request::route()->getName() == 'Laralum::permissions' ? 'active': '' }}">{{ trans('laralum.permission_list') }}</a>
            <a href="{{ route('Laralum::permissions_create') }}"
               class="item {{ \Request::route()->getName() == 'Laralum::permissions_create' ? 'active': '' }}">{{ trans('laralum.create_permission') }}</a>
        </div>
    </div>

@endif--}}

@if(Laralum::loggedInUser()->hasPermission('admin.dashboard'))
    <div class="item item_icon {{ \Request::route()->getName() == 'Laralum::dashboard' ? 'openTooltip' : '' }}">
        <a href="{{ route('Laralum::dashboard') }}"
           class="left-dash {{ \Request::route()->getName() == 'Laralum::dashboard' ? 'active' : '' }}"> <i
                    class="fa fa-tachometer"></i> {{ trans('laralum.dashboard') }}</a>
    </div>
@endif

@if(Laralum::loggedInUser()->hasPermission('admin.user_staff_management'))
    <div class="item item_icon {{ \App\Settings::getActiveClass('admin.user_staff_management') }}">
        <div class="header"><i class="fa fa-user"></i> {{ trans('laralum.user_staff_manager') }}</div>
        <div class="menu">
            @if(Laralum::loggedInUser()->hasPermission('admin.roles.list'))
                <a href="{{ route('Laralum::roles') }}"
                   class="item {{ \App\Settings::getActiveClass('admin.roles.list', true) }}">{{ trans('laralum.role_list') }}</a>
            @endif

            @if(Laralum::loggedInUser()->hasPermission('admin.permissions.list'))
                <a href="{{ route('Laralum::permissions') }}"
                   class="item {{ \App\Settings::getActiveClass('admin.permissions.list', true) }}">{{ trans('laralum.permission_list') }}</a>
            @endif

            @if(Laralum::loggedInUser()->hasPermission('admin.doctor_departments.list'))
                <a href="{{ route('Laralum::departments') }}"
                   class="item {{ \App\Settings::getActiveClass('admin.doctor_departments.list', true) }}">{{ trans('laralum.manage_departments') }}</a>
            @endif

            @if(Laralum::loggedInUser()->hasPermission('admin.staff_departments.list'))
                <a href="{{ route('Laralum::admin.staff_departments') }}"
                   class="item {{ \App\Settings::getActiveClass('admin.staff_departments.list', true) }}">Staff
                    Departments</a>
            @endif

            @if(Laralum::loggedInUser()->hasPermission('admin.users.list'))
                <a href="{{ route('Laralum::users') }}"
                   class="item {{ \App\Settings::getActiveClass('admin.users.list', true) }}">{{ trans('laralum.user_management') }}</a>
            @endif

            @if(Laralum::loggedInUser()->hasPermission('admin.doctors.list'))
                <a href="{{ route('Laralum::doctors') }}"
                   class="item {{ \App\Settings::getActiveClass('admin.doctors.list', true) }}">{{ trans('laralum.doctor_management') }}</a>
            @endif

           {{-- @if(Laralum::loggedInUser()->hasPermission('admin.patients.list'))
                <a href="{{ route('Laralum::patient.list') }}"
                   class="item {{ \App\Settings::getActiveClass('admin.patients.list', true) }}">{{ trans('laralum.patient_list') }}</a>
            @endif--}}

            @if(Laralum::loggedInUser()->hasPermission('admin.staff.list'))
                <a href="{{ route('Laralum::staff') }}"
                   class="item {{ \App\Settings::getActiveClass('admin.staff.list', true) }}">Staff
                    List</a>
            @endif

            @if(Laralum::loggedInUser()->hasPermission('admin.attendance.list'))
                <a href="{{ route('Laralum::attendances') }}"
                   class="item {{ \App\Settings::getActiveClass('admin.attendance.list', true) }}">{{ trans('laralum.attendance_list') }}</a>
            @endif
            @if(Laralum::loggedInUser()->hasPermission('admin.attendance.list'))
                <a href="{{ route('Laralum::attendance.leaves') }}"
                   class="item {{ \App\Settings::getActiveClass('admin.attendance.leaves', true) }}">Leave list</a>
             @endif
        </div>
    </div>
@endif

@if(Laralum::loggedInUser()->hasPermission('view_lab_test'))
    <div class="item item_icon {{ \App\Settings::getActiveClass('view_lab_test') }}">
        <div class="header"><i class="fa fa-medkit"></i>Lab Test</div>
        <div class="menu">
            @if(Laralum::loggedInUser()->hasPermission('view_lab_test'))
                <a href="{{ route('Laralum::lab-test-patients') }}"
                   class="item  {{ \App\Settings::getActiveClass('view_lab_test', true) }}">{{ trans('laralum.lab_test_list') }}</a>
            @endif
        </div>
    </div>
@endif

@if(Laralum::loggedInUser()->hasPermission('admin.issues_management'))
    <div class="item item_icon {{ \App\Settings::getActiveClass('admin.issues_management') }}">
        <div class="header"><i class="fa fa-exclamation-circle"></i> {{ trans('laralum.issues') }}</div>
        <div class="menu">
            @if(Laralum::loggedInUser()->hasPermission('admin.issues_management'))
                <a href="{{ route('Laralum::issues') }}"
                   class="item  {{ \App\Settings::getActiveClass('admin.issues_management', true) }}">{{ trans('laralum.issue_list') }}</a>
            @endif
        </div>
    </div>
@endif


@if(Laralum::loggedInUser()->hasPermission('admin.bookings_management'))
    <div class="item {{ \App\Settings::getActiveClass('admin.bookings_management') }}">
        <div class="header"><i class="fa fa-calendar"></i> Bookings</div>
        <div class="menu">
            @if(Laralum::loggedInUser()->hasPermission('admin.bookings.create'))
                <a href="{{ route('Laralum::booking.registration.create') }}"
                   class="item {{ \App\Settings::getActiveClass('admin.bookings.create', true) }}">New Registration</a>
            @endif

               {{-- @if(Laralum::loggedInUser()->hasPermission('admin.booking.pending'))--}}
                    <a href="{{ route('Laralum::admin.booking.pending') }}"
                       class="item {{ \App\Settings::getActiveClass('admin.booking.pending', true) }}">Pending Bookings</a>
                {{--@endif--}}

            {{--@if(Laralum::loggedInUser()->hasPermission('admin.bookings.revisit'))
                <a href="{{ route('Laralum::booking.revisit') }}"
                   class="item {{ \App\Settings::getActiveClass('admin.bookings.revisit', true) }}">Revisit</a>
            @endif--}}
            {{-- @if(Laralum::loggedInUser()->hasPermission('admin.bookings.generate_card'))
                 <a href="{{ route('Laralum::bookings.generate_card') }}"
                    class="item  {{ \App\Settings::getActiveClass('admin.bookings.generate_card', true) }}">Generate
                     Patient Card</a>
             @endif--}}

            @if(Laralum::loggedInUser()->hasPermission('admin.bookings.tokens.list'))
                <a href="{{ route('Laralum::token.list') }}"
                   class="item {{ \App\Settings::getActiveClass('admin.bookings.tokens.list', true) }}">Generate
                    Patient Tokens</a>
            @endif
            @if(Laralum::loggedInUser()->hasPermission('admin.bookings.treatment_tokens.list'))
                <a href="{{ url('admin/booking/treatment-tokens') }}"
                   class="item {{ \App\Settings::getActiveClass('admin.bookings.treatment_tokens.list', true) }}">Treatment
                    Tokens</a>
            @endif
            @if(Laralum::loggedInUser()->hasPermission('admin.bookings.discharge_patient_billing'))
                <a href="{{ route('Laralum::bookings.discharge-patient-billing') }}"
                   class="item {{ \App\Settings::getActiveClass('admin.bookings.discharge_patient_billing', true) }}">Discharge
                    Patients</a>
            @endif
            @if(Laralum::loggedInUser()->hasPermission('admin.bookings.follow_ups'))
                <a href="{{ route('Laralum::bookings.follow-ups') }}"
                   class="item {{ \App\Settings::getActiveClass('admin.bookings.follow_ups', true) }}">Follow
                    Ups</a>
            @endif
            {{--<a href="{{ route('Laralum::accomodations') }}"
               class="item  {{ \App\Settings::getActiveClass('accomodations', true) }}">Allot Accomodations
            </a>--}}
        </div>
    </div>
@endif


@if(Laralum::loggedInUser()->hasPermission('admin.bookings.list'))
    <div class="item {{ \App\Settings::getActiveClass('admin.opd_patients_management') }}">
        <div class="header"><i class="fa fa-user"></i> OPDs</div>
        <div class="menu">
            <a href="{{ route('Laralum::bookings') }}"
               class="item  {{ \App\Settings::getActiveClass('admin.opd_patients_list', true) }}">OPD Booking List</a>

            @if(Laralum::loggedInUser()->hasPermission('admin.bookings.opd.tokens.list'))
                <a href="{{ route('Laralum::opd-tokens') }}"
                   class="item {{ \App\Settings::getActiveClass('opd-tokens', true) }}">OPD
                    Consultation Slip List</a>

                <a href="{{ url("admin/booking/generate-opd-token") }}" class="item {{ \App\Settings::getActiveClass('generate-opd-token', true) }}">Generate OPD Consulation Slip
                </a>
            @endif
        </div>
    </div>
@endif


@if(Laralum::loggedInUser()->hasPermission('admin.future_patients_management'))
    <div class="item {{ \App\Settings::getActiveClass('admin.future_patients_management') }}">
        <div class="header"><i class="fa fa-user"></i> {{ trans('laralum.future_patients') }}</div>
        <div class="menu">
            <a class="item {{ \App\Settings::getActiveClass('admin.future_patients_management', true) }}"
               href="{{ route('Laralum::admin.future.patients.list') }}"
               class="item">{{ trans('laralum.future_patients_list') }}</a>
        </div>
    </div>
@endif


@if(Laralum::loggedInUser()->hasPermission('admin.bookings.list'))
    <div class="item {{ \App\Settings::getActiveClass('admin.ipd_bookings_management') }}">
        <div class="header"><i class="fa fa-user"></i> Ipds</div>
        <div class="menu">
            <a href="{{ route('Laralum::ipd.bookings.list') }}"
               class="item  {{ \App\Settings::getActiveClass('admin.ipd_bookings_management', true) }}">Ipd Booking
                List</a>
        </div>
    </div>
@endif



@if(Laralum::loggedInUser()->hasPermission('admin.archived_patients_management'))
    <div class="item {{ \App\Settings::getActiveClass('admin.archived_patients_management') }}">
        <div class="header"><i class="fa fa-user"></i> {{ trans('laralum.archived_patients') }}</div>
        <div class="menu">
            <a class="item {{ \App\Settings::getActiveClass('admin.archived_patients_management', true) }}"
               href="{{ route('Laralum::archived.patients.list') }}"
               class="item">{{ trans('laralum.archived_patients_list') }}</a>
        </div>
    </div>
@endif


@if(Laralum::loggedInUser()->hasPermission('admin.accommodation_management'))
    <div class="item {{ \App\Settings::getActiveClass('admin.accommodation_management') }}">
        <div class="header"><i class="fa fa-bed"></i> Accommodation Status</div>
        <div class="menu">
            @if(Laralum::loggedInUser()->hasPermission('admin.accommodation.chart'))
                <a class="item {{ \App\Settings::getActiveClass('admin.accommodation.chart', true) }}"
                   href="{{ route('Laralum::accommodation.roomStatus') }}" class="item">Status
                    Chart</a>
            @endif
            @if(Laralum::loggedInUser()->hasPermission('admin.room_types'))
                <a href="{{ route('Laralum::room_types') }}"
                   class="item {{ \App\Settings::getActiveClass('admin.room_types', true) }}">{{ trans('laralum.room_types') }}</a>
            @endif
            @if(Laralum::loggedInUser()->hasPermission('admin.room_services'))
                <a href="{{ route('Laralum::external_services') }}"
                   class="item {{ \App\Settings::getActiveClass('admin.room_services', true) }}">{{ trans('laralum.external_service_list') }}</a>
            @endif
            @if(Laralum::loggedInUser()->hasPermission('admin.buildings'))
                <a href="{{ route('Laralum::buildings') }}"
                   class="item {{ \App\Settings::getActiveClass('admin.buildings', true) }}">{{ trans('laralum.building_list') }}</a>
            @endif
            @if(Laralum::loggedInUser()->hasPermission('admin.rooms'))
                <a href="{{ route('Laralum::rooms') }}"
                   class="item {{ \App\Settings::getActiveClass('admin.rooms', true) }}">{{ trans('laralum.rooms') }}</a>
            @endif
            @if(Laralum::loggedInUser()->hasPermission('admin.block_rooms'))
                <a href="{{ route('Laralum::block-rooms') }}"
                   class="item {{ \App\Settings::getActiveClass('admin.block_rooms', true) }}">{{ trans('laralum.block_rooms') }}</a>
            @endif
        </div>
    </div>
@endif

@if(Laralum::loggedInUser()->hasPermission('admin.inventory_management'))
    <div class="item {{ \App\Settings::getActiveClass('admin.inventory_management') }}">
        <div class="header"><i class="fa fa-users"></i> {{ trans('laralum.inventory_management') }}</div>
        <div class="menu">
            @if(Laralum::loggedInUser()->hasPermission('admin.inventory_groups'))
                <a href="{{ route('Laralum::groups') }}"
                   class="item {{ \App\Settings::getActiveClass('admin.inventory_groups', true) }}">{{ trans('laralum.groups_list') }}</a>
            @endif

            @if(Laralum::loggedInUser()->hasPermission('admin.inventory_group_items'))
                <a href="{{ route('Laralum::group-items') }}"
                   class="item {{ \App\Settings::getActiveClass('admin.inventory_group_items', true) }}">{{ trans('laralum.group_items_list') }}</a>
            @endif

            @if(Laralum::loggedInUser()->hasPermission('admin.stock'))
                <a href="{{ route('Laralum::stock') }}"
                   class="item {{ \App\Settings::getActiveClass('admin.stock', true) }}">{{ trans('laralum.stock_list') }}</a>
            @endif

            @if(Laralum::loggedInUser()->hasPermission('admin.stock_item_request'))
                <a href="{{ route('Laralum::stock.item_requests') }}"
                   class="item {{ \App\Settings::getActiveClass('admin.stock_item_request', true) }}">{{ trans('laralum.item_requests') }}</a>
            @endif
        </div>
    </div>
@endif

@if(Laralum::loggedInUser()->hasPermission('admin.kitchen_management'))
    <div class="item {{ \App\Settings::getActiveClass('admin.kitchen_management') }}">
        <div class="header"><i class="fa fa-cutlery"></i> {{ trans('laralum.manage_kitchen') }}</div>
        <div class="menu">
            @if(Laralum::loggedInUser()->hasPermission('admin.kitchen_items'))
                <a href="{{ route('Laralum::kitchen-items') }}"
                   class="item {{ \App\Settings::getActiveClass('admin.kitchen_items', true) }}">{{ trans('laralum.kitchen_items') }}</a>
            @endif
            {{-- @if(Laralum::loggedInUser()->hasPermission('admin.kitchen_item.requirements'))
                 <a href="{{ route('Laralum::kitchen-item.requirements') }}"
                    class="item {{ \App\Settings::getActiveClass('admin.kitchen_item.requirements', true) }}">{{ trans('laralum.kitchen_item_requirements_list') }}</a>
             @endif
             @if(Laralum::loggedInUser()->hasPermission('admin.kitchen_item.diet_chart'))
                 <a href="{{ route('Laralum::diet-chart') }}"
                    class="item {{ \App\Settings::getActiveClass('admin.kitchen_item.diet_chart', true) }}">{{ trans('laralum.daily_diet_chart') }}</a>
             @endif
             @if(Laralum::loggedInUser()->hasPermission('admin.meal_status'))
                 <a href="{{ route('Laralum::meal-status') }}"
                    class="item {{ \App\Settings::getActiveClass('admin.meal_status', true) }}">
                     Meal Status</a>
             @endif
             @if(Laralum::loggedInUser()->hasPermission('admin.meal_servings'))
                 <a href="{{ route('Laralum::meal-servings') }}"
                    class="item  {{ \App\Settings::getActiveClass('admin.meal_servings', true) }}">
                     Meal Serving List</a>
             @endif--}}
        </div>
    </div>
@endif

@if(Laralum::loggedInUser()->hasPermission('admin.admin_settings'))
    <div class="item {{ \App\Settings::getActiveClass('admin.admin_settings') }}">
        <div class="header"><i class="fa fa-cog"></i> {{ trans('laralum.admin_settings') }}</div>
        <div class="menu">
            @if(Laralum::loggedInUser()->hasPermission('admin.admin_settings.document_types'))
                <a href="{{ route('Laralum::document_types') }}"
                   class="item {{ \App\Settings::getActiveClass('admin.admin_settings.document_types', true) }}">Document
                    Types</a>
            @endif

            @if(Laralum::loggedInUser()->hasPermission('admin.admin_settings.professions'))
                <a href="{{ route('Laralum::professions') }}"
                   class="item {{ \App\Settings::getActiveClass('admin.admin_settings.professions', true) }}">{{ trans('laralum.profession_list') }}</a>
            @endif

            @if(Laralum::loggedInUser()->hasPermission('admin.admin_settings.hospital_info'))
                <a href="{{ route('Laralum::admin.hospital_info') }}"
                   class="item {{ \App\Settings::getActiveClass('admin.admin_settings.hospital_info', true) }}">Hospital
                    Info</a>
            @endif

            @if(Laralum::loggedInUser()->hasPermission('admin.admin_settings.hospital_bank_account'))
                <a class="item {{ \App\Settings::getActiveClass('admin.admin_settings.hospital_bank_account', true) }}"
                   href="{{ route('Laralum::admin.hospital_bank_account') }}"
                   class="item">Hospital Bank Accounts</a>
            @endif

            @if(Laralum::loggedInUser()->hasPermission('admin.admin_settings.tax_details'))
                <a class="item {{ \App\Settings::getActiveClass('admin.admin_settings.tax_details', true) }}"
                   href="{{ route('Laralum::admin.tax_details') }}"
                   class="item">Tax Details</a>
            @endif

           <!--  @if(Laralum::loggedInUser()->hasPermission('admin.admin_settings.consultation_charges'))
                <a class="item {{ \App\Settings::getActiveClass('admin.admin_settings.consultation_charges', true) }}"
                   href="{{ route('Laralum::admin.consultation_charges') }}"
                   class="item">Consultation Charge</a>
            @endif -->

            @if(Laralum::loggedInUser()->hasPermission('admin.admin_settings.email_templates'))
                <a href="{{ url('admin/email-templates') }}"
                   class="item {{ \App\Settings::getActiveClass('admin.admin_settings.email_templates', true) }}">{{ trans('laralum.email_templates') }}</a>
            @endif

            @if(Laralum::loggedInUser()->hasPermission('admin.admin_settings.discount_offers'))
                <a href="{{ route('Laralum::discount_offers') }}"
                   class="item {{ \App\Settings::getActiveClass('admin.admin_settings.discount_offers', true) }}">{{ trans('laralum.discount_offer_list') }}</a>
            @endif

            @if(Laralum::loggedInUser()->hasPermission('admin.admin_settings.feedback_questions'))
                <a href="{{ route('Laralum::feedback-questions') }}"
                   class="item {{ \App\Settings::getActiveClass('admin.admin_settings.feedback_questions', true) }}">{{ trans('laralum.feedback_questions_list') }}</a>
            @endif

            @if(Laralum::loggedInUser()->hasPermission('admin.admin_settings.treatments'))
                <a class="item {{ \App\Settings::getActiveClass('admin.admin_settings.treatments', true) }}"
                   href="{{ route('Laralum::treatments') }}"
                   class="item">{{ trans('laralum.treatments_list') }}</a>
            @endif
            @if(Laralum::loggedInUser()->hasPermission('admin.admin_settings.lab_tests'))
                <a href="{{ route('Laralum::lab-tests') }}"
                   class="item {{ \App\Settings::getActiveClass('admin.admin_settings.lab_tests', true) }}">{{ trans('laralum.lab_tests_list') }}</a>
            @endif



            <!-- @if(Laralum::loggedInUser()->hasPermission('admin.permission_exercise_categories.list'))
                <a href="{{ route('Laralum::physiotherpy_exercise_categories.index') }}"
                   class="item {{ \App\Settings::getActiveClass('admin.permission_exercise_categories.list', true) }}">{{ trans('laralum.physiotherpy_exercise_categories') }}</a>
            @endif -->

            @if(Laralum::loggedInUser()->hasPermission('admin.physiotherpy_exercises.index'))
                <a href="{{ route('Laralum::physiotherpy_exercises.index') }}"
                   class="item {{ \App\Settings::getActiveClass('admin.physiotherpy_exercises.index', true) }}">{{ trans('laralum.physiotherpy_exercises') }}</a>
            @endif

                @if(Laralum::loggedInUser()->hasPermission('admin.admin_settings.consultation_charges'))

            <a href="{{ route('Laralum::price-settings') }}" class="item {{ \App\Settings::getActiveClass('admin.admin_settings.price_settings', true) }}">Price Setting</a>
                @endif





            {{--
                        @if(Laralum::loggedInUser()->hasPermission('admin.kitchen_items'))
                            <a href="{{ route('Laralum::kitchen-items') }}"
                               class="item {{ \App\Settings::getActiveClass('admin.kitchen_items', true) }}">{{ trans('laralum.kitchen_items_list') }}</a>
                        @endif--}}
        </div>
    </div>
@endif


@if(Laralum::loggedInUser()->isDoctor())
    <div class="item {{ \App\Settings::getActiveClass('doctor.tokens') }}">
        <div class="header"><i class="fa fa-cutlery"></i> {{ trans('laralum.tokens') }}</div>
        <div class="menu">
            @if(Laralum::loggedInUser()->hasPermission('doctor.tokens'))
                <a href="{{ route('Laralum::tokens') }}"
                   class="item {{ \App\Settings::getActiveClass('doctor.tokens', true) }}">{{ trans('laralum.tokens') }}</a>
            @endif
        </div>
    </div>
@endif
{{--

@if(Laralum::loggedInUser()->hasPermission('doctor.patients'))
    <div class="item {{ \App\Settings::getActiveClass('doctor.patients') }}">
        <div class="header"><i class="fa fa-cutlery"></i> Patients</div>
        <div class="menu">
            @if(Laralum::loggedInUser()->hasPermission('doctor.patients'))
                <a href="{{ route('Laralum::patients') }}"
                   class="item {{ \App\Settings::getActiveClass('doctor.patients', true) }}">Patients</a>
            @endif
        </div>
    </div>
@endif
--}}

@if(Laralum::loggedInUser()->hasPermission('kitchen.diet_management'))
    <div class="item {{ \App\Settings::getActiveClass('kitchen.diet_management') }}">
        <div class="header"><i class="fa fa-user"></i> Manage Diets</div>
        <div class="menu">
            <a class="item {{ \App\Settings::getActiveClass('kitchen.patient_diet', true) }}"
               href="{{ route('Laralum::diet-chart') }}"
               class="item">Diet Of Patient</a>

            <a class="item {{ \App\Settings::getActiveClass('kitchen.meal-status', true) }}"
               href="{{ route('Laralum::meal-status') }}"
               class="item">Daily Meal Status</a>
            <a class="item {{ \App\Settings::getActiveClass('kitchen.meal-servings', true) }}"
               href="{{ route('Laralum::meal-servings') }}"
               class="item">Meal Serving Status</a>
        </div>
    </div>
@endif

@if(Laralum::loggedInUser()->hasPermission('kitchen.requirements'))
    <div class="item {{ \App\Settings::getActiveClass('kitchen.requirements') }}">
        <div class="header"><i class="fa fa-asterisk"></i> Requirements</div>
        <div class="menu">
            <a class="item {{ \App\Settings::getActiveClass('kitchen.requirements', true) }}"
               href="{{ route('Laralum::kitchen-item.requirements') }}"
               class="item">Requirements</a>
            <a class="item {{ \App\Settings::getActiveClass('kitchen.requests', true) }}"
               href="{{ route('Laralum::kitchen-item.requests') }}"
               class="item">Requests</a>

        </div>
    </div>
@endif

@if(Laralum::loggedInUser()->hasPermission('account.management'))
    <div class="item {{ \App\Settings::getActiveClass('account.treatment_tokens') }}">
        <div class="header"><i class="fa fa-user"></i> Manage Treatments</div>
        <div class="menu">
            <a class="item {{ \App\Settings::getActiveClass('account.treatment_tokens', true) }}"
               href="{{ route('Laralum::treatment_tokens') }}"
               class="item">Treatment Tokens</a>
        </div>
    </div>
@endif

