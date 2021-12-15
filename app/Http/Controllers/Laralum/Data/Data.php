<?php


/*
+---------------------------------------------------------------------------+
| Laralum Data Configuration												|
+---------------------------------------------------------------------------+
|                                                               			|
| * Available settings:                  									|
|																			|
| table: The table name 												    +-------------+
| hidden: Columns that will not be displayed in the edit form, and they won't be updated +----------------------------+
| empty: Columns that will not have their current value when editing them (eg: password field is hidden in the model) |
| confirmed: fields that will need to be confirmed twice                                                              +-+
| encrypted: Fields that will be encrypted using: Crypt::encrypt(); when they are saved and decrypted when editing them +---------------------------+
| hashed: Fields that will be hashed when they are saved in the database, will be empty on editing, and if saved as empty they will not be modified |
| masked: Fields that will be displayed as a type='password', so their content when beeing modified won't be visible +------------------------------+
| default_random: Fields that if no data is set, they will be randomly generated (10 characters) +-------------------+
| su_hidden: Columns that will be added to the hidden array if the user is su +------------------+
| code: Fields that can be edited using a code editor                       +-+
| wysiwyg: Fields that can be edited using a wysiwyg editor                 |
| validator: validator settings when executing: $this->validate();          |
| relations: a relationship between a column and a table, or a dropdown     |
|																			|
| Note: Do not change the first index               						|
|																			|
+---------------------------------------------------------------------------+
|																			|
| This file allows you to setup all the information                         |
| to be able to manage your app without problems            				|
|																			|
+---------------------------------------------------------------------------+
*/

if (!isset($row)) {
    # the row will be the user logged in if no row is set
    $row = Auth::user();
}

$data = [


    'users' => [

        'table' => 'users',
        'create' => [
            'hidden' => ['id', 'su', 'active', 'banned', 'register_ip', 'activation_key', 'locale', 'remember_token', 'created_at', 'updated_at', 'is_discharged', 'registration_id', 'uhid'],
            'default_random' => ['password'],
            'confirmed' => ['password'],
            'encrypted' => [],
            'hashed' => ['password'],
            'masked' => ['password'],
            'code' => [],
            'wysiwyg' => [],
            'validator' => [
                'name' => 'required|max:255',
                'email' => 'required|email|unique:users',
                'password' => 'required|confirmed|min:6',
                'country_code' => 'required',
                'mobile_number' => 'required|numeric|unique:users',
            ],

        ],
        'edit' => [
            'hidden' => ['id', 'su', 'email', 'register_ip', 'activation_key', 'locale', 'remember_token', 'created_at', 'updated_at', 'is_discharged', 'registration_id', 'uhid'],
            'su_hidden' => ['name', 'active', 'banned', 'password', 'country_code'],
            'empty' => ['password'],
            'default_random' => [],
            'confirmed' => ['password'],
            'encrypted' => [],
            'hashed' => ['password'],
            'masked' => ['password'],
            'code' => [],
            'wysiwyg' => [],
            'validator' => [
                'name' => 'sometimes|required|max:255',
                'password' => 'sometimes|confirmed|min:6',
                'country_code' => 'sometimes|required',
            ],
        ],
    ],

    'profile' => [

        'table' => 'users',
        'edit' => [
            'hidden' => ['id', 'su', 'email', 'register_ip', 'active', 'banned', 'activation_key', 'locale', 'remember_token', 'created_at', 'updated_at'],
            'empty' => ['password'],
            'default_random' => [],
            'confirmed' => ['password'],
            'encrypted' => [],
            'hashed' => ['password'],
            'masked' => ['password'],
            'code' => [],
            'wysiwyg' => [],
            'validator' => [
                'name' => 'sometimes|required|max:255',
                'password' => 'sometimes|confirmed|min:6',
                'country_code' => 'sometimes|required',
            ],
        ],
    ],


    'users_settings' => [

        'table' => 'users_settings',
        'edit' => [
            'hidden' => ['id', 'created_at', 'updated_at'],
            'empty' => [],
            'default_random' => [],
            'confirmed' => [],
            'encrypted' => [],
            'hashed' => [],
            'masked' => [],
            'code' => [],
            'wysiwyg' => [],
            'relations' => [
                'default_role' => [
                    'data' => Laralum::roles(),
                    'value' => 'id',
                    'show' => 'name',
                ],
                'default_active' => [
                    'data' => Laralum::dropdown('users_default_active'),
                    'value' => 'value',
                    'show' => 'show',
                ],
            ],
            'validator' => [
                'default_role' => 'sometimes|required',
                'location' => 'sometimes|required',
                'register_enabled' => 'sometimes|required',
                'default_active' => 'sometimes|required',
                'welcome_email' => 'sometimes|required',
            ],
        ],

    ],


    'roles' => [

        'table' => 'roles',
        'create' => [
            'hidden' => ['id', 'su', 'created_at', 'updated_at'],
            'default_random' => [],
            'confirmed' => [],
            'encrypted' => [],
            'hashed' => [],
            'masked' => [],
            'code' => [],
            'wysiwyg' => [],
            'relations' => [
                'color' => [
                    'data' => Laralum::dropdown('colors_name'),
                    'value' => 'value',
                    'show' => 'show',
                ],
            ],
            'validator' => [
                'name' => 'required|unique:roles',
                'color' => 'required',
            ],
        ],
        'edit' => [
            'hidden' => ['id', 'su', 'created_at', 'updated_at'],
            'su_hidden' => ['name'],
            'empty' => [],
            'default_random' => [],
            'confirmed' => [],
            'encrypted' => [],
            'hashed' => [],
            'masked' => [],
            'code' => [],
            'wysiwyg' => [],
            'relations' => [
                'color' => [
                    'data' => Laralum::dropdown('colors_name'),
                    'value' => 'value',
                    'show' => 'show',
                ],
            ],
            'validator' => [
                'name' => 'sometimes|required|unique:roles,name,' . $row->id,
                'color' => 'required',
            ],
        ],
    ],


    'permissions' => [
        'table' => 'permissions',
        'create' => [
            'hidden' => ['id', 'su', 'created_at', 'updated_at'],
            'default_random' => [],
            'confirmed' => [],
            'encrypted' => [],
            'hashed' => [],
            'masked' => [],
            'code' => [],
            'wysiwyg' => [],
            'validator' => [
                'slug' => 'required|max:255|unique:permissions',
            ],
        ],
        'edit' => [
            'hidden' => ['id', 'su', 'created_at', 'updated_at', 'slug'],
            'su_hidden' => ['slug'],
            'empty' => [],
            'default_random' => [],
            'confirmed' => [],
            'encrypted' => [],
            'hashed' => [],
            'masked' => [],
            'code' => [],
            'wysiwyg' => [],
            'validator' => [
                'slug' => 'sometimes|required|max:255|unique:permissions,slug,' . $row->id,
            ],
        ],
    ],


    'blogs' => [

        'table' => 'blogs',
        'create' => [
            'hidden' => ['id', 'user_id', 'created_at', 'updated_at'],
            'default_random' => [],
            'confirmed' => [],
            'encrypted' => [],
            'hashed' => [],
            'masked' => [],
            'code' => [],
            'wysiwyg' => [],
            'validator' => [
                'name' => 'required|max:255|unique:blogs',
            ],
        ],
        'edit' => [
            'hidden' => ['id', 'user_id', 'created_at', 'updated_at'],
            'empty' => [],
            'default_random' => [],
            'confirmed' => [],
            'encrypted' => [],
            'hashed' => [],
            'masked' => [],
            'code' => [],
            'wysiwyg' => [],
            'validator' => [
                'name' => 'sometimes|required|max:255|unique:blogs,name,' . $row->id,
            ],
        ],
    ],


    'posts' => [

        'table' => 'posts',
        'create' => [
            'hidden' => ['id', 'user_id', 'edited_by', 'blog_id', 'created_at', 'updated_at'],
            'default_random' => [],
            'confirmed' => [],
            'encrypted' => [],
            'hashed' => [],
            'masked' => [],
            'code' => [],
            'wysiwyg' => ['body'],
            'validator' => [
                'title' => 'required|max:255',
                'body' => 'required',
            ],
        ],
        'edit' => [
            'hidden' => ['id', 'user_id', 'edited_by', 'blog_id', 'created_at', 'updated_at'],
            'empty' => [],
            'default_random' => [],
            'confirmed' => [],
            'encrypted' => [],
            'hashed' => [],
            'masked' => [],
            'code' => [],
            'wysiwyg' => ['body'],
            'validator' => [
                'title' => 'sometimes|required|max:255',
                'body' => 'required',
            ],
        ],
    ],


    'comments' => [

        'table' => 'post_comments',
        'create' => [
            'hidden' => ['id', 'post_id', 'user_id', 'created_at', 'updated_at'],
            'default_random' => [],
            'confirmed' => [],
            'encrypted' => [],
            'hashed' => [],
            'masked' => [],
            'code' => [],
            'wysiwyg' => [],
            'validator' => [
                'name' => 'sometimes|required',
                'email' => 'sometimes|required',
                'content' => 'required',
            ],
        ],
        'edit' => [
            'hidden' => ['id', 'post_id', 'user_id', 'created_at', 'updated_at'],
            'empty' => [],
            'default_random' => [],
            'confirmed' => [],
            'encrypted' => [],
            'hashed' => [],
            'masked' => [],
            'code' => [],
            'wysiwyg' => [],
            'validator' => [
                'name' => 'sometimes|required',
                'email' => 'sometimes|required',
                'content' => 'required',
            ],
        ],
    ],


    'settings' => [

        'table' => 'settings',
        'create' => [
            'hidden' => ['id', 'laralum_version', 'created_at', 'updated_at'],
            'default_random' => [],
            'confirmed' => [],
            'encrypted' => [],
            'hashed' => [],
            'masked' => [],
            'code' => [],
            'wysiwyg' => [],
            'validator' => [],
        ],
        'edit' => [
            'hidden' => ['id', 'laralum_version', 'created_at', 'updated_at'],
            'empty' => [],
            'default_random' => [],
            'confirmed' => [],
            'encrypted' => [],
            'hashed' => [],
            'masked' => [],
            'code' => [],
            'wysiwyg' => [],
            'relations' => [
                'header_color' => [
                    'data' => Laralum::dropdown('colors_hex'),
                    'value' => 'value',
                    'show' => 'show',
                ],
                'button_color' => [
                    'data' => Laralum::dropdown('colors_name'),
                    'value' => 'value',
                    'show' => 'show',
                ],
                'pie_chart_source' => [
                    'data' => Laralum::dropdown('settings_pie_chart_source'),
                    'value' => 'value',
                    'show' => 'show',
                ],
                'bar_chart_source' => [
                    'data' => Laralum::dropdown('settings_bar_chart_source'),
                    'value' => 'value',
                    'show' => 'show',
                ],
                'line_chart_source' => [
                    'data' => Laralum::dropdown('settings_line_chart_source'),
                    'value' => 'value',
                    'show' => 'show',
                ],
                'geo_chart_source' => [
                    'data' => Laralum::dropdown('settings_geo_chart_source'),
                    'value' => 'value',
                    'show' => 'show',
                ],
            ],
            'validator' => [],
        ],
    ],

    'documents' => [

        'table' => 'documents',
        'create' => [
            'hidden' => ['id', 'slug', 'downloads', 'name', 'user_id', 'created_at', 'updated_at'],
            'default_random' => [],
            'confirmed' => [],
            'encrypted' => ['password'],
            'hashed' => [],
            'masked' => [],
            'code' => [],
            'wysiwyg' => [],
            'validator' => [],
        ],
        'edit' => [
            'hidden' => ['id', 'slug', 'downloads', 'name', 'user_id', 'created_at', 'updated_at'],
            'default_random' => [],
            'confirmed' => [],
            'encrypted' => ['password'],
            'hashed' => [],
            'masked' => [],
            'code' => [],
            'wysiwyg' => [],
            'validator' => [],
        ],
    ],

    'issues' => [
        'table' => 'issues',
        'create' => [
            'hidden' => ['id', 'created_at', 'updated_at', 'status', 'type', 'created_by', 'deleted_at'],
            'default_random' => [],
            'confirmed' => [],
            'encrypted' => [],
            'hashed' => [],
            'masked' => [],
            'code' => [],
            'wysiwyg' => [],
            'validator' => [
                'title' => 'required|max:255|',
                'description' => 'required'
            ],
        ],
        'edit' => [
            'hidden' => ['id', 'created_at', 'updated_at', 'status', 'type', 'created_by', 'deleted_at'],
            'empty' => [],
            'default_random' => [],
            'confirmed' => [],
            'encrypted' => [],
            'hashed' => [],
            'masked' => [],
            'code' => [],
            'wysiwyg' => [],
            'validator' => [
                'title' => 'required|max:255',
                'description' => 'required',
            ],
        ],
    ],

    'departments' => [
        'table' => 'departments',
        'create' => [
            'hidden' => ['id', 'created_at', 'updated_at', 'status', 'created_by', 'deleted_at', 'incharge_id'],
            'default_random' => [],
            'confirmed' => [],
            'encrypted' => [],
            'hashed' => [],
            'masked' => [],
            'code' => [],
            'wysiwyg' => [],
            'validator' => [
                'title' => 'required|max:255|',
                'description' => '',
                'incharge_id' => ''
            ],
            'relations' => [
                /* 'incharge_id' => [
                     'data' => Laralum::dropdown('users'),
                     'value' => 'value',
                     'show' => 'show',
                 ],*/
                'color' => [
                    'data' => Laralum::dropdown('colors_name'),
                    'value' => 'value',
                    'show' => 'show',
                ],
            ],
        ],
        'edit' => [
            'hidden' => ['id', 'created_at', 'updated_at', 'status', 'created_by', 'deleted_at', 'incharge_id'],
            'empty' => [],
            'default_random' => [],
            'confirmed' => [],
            'encrypted' => [],
            'hashed' => [],
            'masked' => [],
            'code' => [],
            'wysiwyg' => [],
            'validator' => [
                'title' => 'required|max:255|',
                'description' => '',
                'incharge_id' => ''
            ],
            'relations' => [
                /* 'incharge_id' => [
                     'data' => Laralum::dropdown('usersedit'),
                     'value' => 'value',
                     'show' => 'show',
                 ],*/
                'color' => [
                    'data' => Laralum::dropdown('colors_name'),
                    'value' => 'value',
                    'show' => 'show',
                ],

            ],
        ],
    ],

    'discount_offers' => [
        'table' => 'discount_offers',
        'create' => [
            'hidden' => ['id', 'created_at', 'expiry_date', 'updated_at', 'status', 'created_by', 'deleted_at'],
            'default_random' => [],
            'confirmed' => [],
            'encrypted' => [],
            'hashed' => [],
            'masked' => [],
            'code' => [],
            'wysiwyg' => [],
            'validator' => [
                'code' => 'required|max:255|',
                'description' => '',
                'incharge_id' => ''
            ],
            'relations' => [
                'type' => [
                    'data' => Laralum::dropdown('offerType'),
                    'value' => 'value',
                    'show' => 'show',
                ],
            ],
        ],
        'edit' => [
            'hidden' => ['id', 'created_at', 'expiry_date', 'updated_at', 'status', 'created_by', 'deleted_at'],
            'empty' => [],
            'default_random' => [],
            'confirmed' => [],
            'encrypted' => [],
            'hashed' => [],
            'masked' => [],
            'code' => [],
            'wysiwyg' => [],
            'validator' => [
                'code' => 'required|max:255|',
                'description' => '',
            ],
            'relations' => [
                'type' => [
                    'data' => Laralum::dropdown('offerType'),
                    'value' => 'value',
                    'show' => 'show',
                ],

            ],

        ],
    ],
    'kitchen_items' => [
        'table' => 'kitchen_items',
        'create' => [
            'hidden' => ['id', 'created_at', 'updated_at', 'created_by', 'deleted_at', 'quantity',],
            'default_random' => [],
            'confirmed' => [],
            'encrypted' => [],
            'hashed' => [],
            'masked' => [],
            'code' => [],
            'wysiwyg' => [],
            'validator' => [
                'name' => 'required|max:255|',
                /* 'quantity' => 'required',*/
                'price' => 'required',
                'type' => 'required'
            ],
            'relations' => [
                'type' => [
                    'data' => Laralum::dropdown('meal_type'),
                    'value' => 'value',
                    'show' => 'show',
                ],

            ],
        ],
        'edit' => [
            'hidden' => ['id', 'created_at', 'updated_at', 'status', 'created_by', 'deleted_at', 'quantity'],
            'empty' => [],
            'default_random' => [],
            'confirmed' => [],
            'encrypted' => [],
            'hashed' => [],
            'masked' => [],
            'code' => [],
            'wysiwyg' => [],
            'validator' => [
                'name' => 'required|max:255|',/*
                'quantity' => 'required',*/
                'price' => 'required',
                'type' => 'required'
            ],
            'relations' => [
                'type' => [
                    'data' => Laralum::dropdown('meal_type'),
                    'value' => 'value',
                    'show' => 'show',
                ],

            ],
        ],
    ],
    'stock_categories' => [
        'table' => 'stock_categories',
        'create' => [
            'hidden' => ['id', 'created_at', 'updated_at', 'created_by', 'deleted_at'],
            'default_random' => [],
            'confirmed' => [],
            'encrypted' => [],
            'hashed' => [],
            'masked' => [],
            'code' => [],
            'wysiwyg' => [],
            'validator' => [
                'name' => 'required|max:255|',
                /*'quantity' => 'required',*/
                /* 'price' => 'required'*/
            ],
        ],
        'edit' => [
            'hidden' => ['id', 'created_at', 'updated_at', 'status', 'created_by', 'deleted_at'],
            'empty' => [],
            'default_random' => [],
            'confirmed' => [],
            'encrypted' => [],
            'hashed' => [],
            'masked' => [],
            'code' => [],
            'wysiwyg' => [],
            'validator' => [
                'name' => 'required|max:255|',
            ],
        ],
    ],

    'stock' => [
        'table' => 'stock',
        'create' => [
            'hidden' => ['id', 'created_at', 'updated_at', 'created_by', 'deleted_at', 'product_type', 'product_id', 'alert_quantity'],
            'default_random' => [],
            'confirmed' => [],
            'encrypted' => [],
            'hashed' => [],
            'masked' => [],
            'code' => [],
            'wysiwyg' => [],
            'validator' => [
                'name' => 'required|max:255|',
                /*'quantity' => 'required',*/
              //  'product_id' => 'required',
                /* 'price' => 'required'*/
            ],/*
            'relations' => [
                'product_id' => [
                    'data' => Laralum::dropdown('products'),
                    'value' => 'value',
                    'show' => 'show',
                    'multiple' => true
                ],

            ],*/
        ],
        'edit' => [
            'hidden' => ['id', 'created_at', 'updated_at', 'status', 'created_by', 'deleted_at', 'product_type', 'product_id', 'quantity'],
            'empty' => [],
            'default_random' => [],
            'confirmed' => [],
            'encrypted' => [],
            'hashed' => [],
            'masked' => [],
            'code' => [],
            'wysiwyg' => [],
            'validator' => [
                'name' => 'required|max:255|',
                /*'quantity' => 'required',*/
                'product_id' => 'required',
                /*  'price' => 'required',*/
            ],/*
            'relations' => [
                'product_id' => [
                    'data' => Laralum::dropdown('products'),
                    'value' => 'value',
                    'show' => 'show',
                    'multiple' => true
                ],

            ],*/
        ],
    ],

    'stock_log' => [
        'table' => 'item_quantity_logs',
        'create' => [
            'hidden' => ['id', 'created_at', 'updated_at', 'user_id', 'deleted_at', 'item_id', 'item_request_id'],
            'default_random' => [],
            'confirmed' => [],
            'encrypted' => [],
            'hashed' => [],
            'masked' => [],
            'code' => [],
            'wysiwyg' => [],
            'validator' => [
                'quantity' => 'required|integer',
                'action' => 'required|integer',
                /*'quantity' => 'required',*/
                //  'product_id' => 'required',
                /* 'price' => 'required'*/
            ],
            'relations' => [
                'action' => [
                    'data' => Laralum::dropdown('stock_actions'),
                    'value' => 'value',
                    'show' => 'show',
                    'multiple' => true
                ],

            ],
        ],
        'edit' => [
            'hidden' => ['id', 'created_at', 'updated_at', 'user_id', 'deleted_at', 'item_id'],
            'default_random' => [],
            'confirmed' => [],
            'encrypted' => [],
            'hashed' => [],
            'masked' => [],
            'code' => [],
            'wysiwyg' => [],
            'validator' => [
                'quantity' => 'required|integer',
                'action' => 'required|integer',
                /*'quantity' => 'required',*/
                //  'product_id' => 'required',
                /* 'price' => 'required'*/
            ],
            'relations' => [
                'action' => [
                    'data' => Laralum::dropdown('stock_actions'),
                    'value' => 'value',
                    'show' => 'show',
                    'multiple' => true
                ],

            ],
        ],
    ],

    'treatments' => [
        'table' => 'treatments',
        'create' => [
            'hidden' => ['id', 'created_at', 'updated_at', 'created_by', 'deleted_at', 'status'],
            'default_random' => [],
            'confirmed' => [],
            'encrypted' => [],
            'hashed' => [],
            'masked' => [],
            'code' => [],
            'wysiwyg' => [],
            'validator' => [
                'title' => 'required|max:255|',
                /*'quantity' => 'required',*/
                'duration' => 'required',
                'department_id' => 'required',
                'price' => 'required'
                /* 'price' => 'required'*/
            ],
            'relations' => [
                'type' => [
                    'data' => Laralum::dropdown('duration_types'),
                    'value' => 'value',
                    'show' => 'show',
                ],
                'department_id' => [
                    'data' => Laralum::dropdown('departments'),
                    'value' => 'value',
                    'show' => 'show',
                ],

            ],
        ],
        'edit' => [
            'hidden' => ['id', 'created_at', 'updated_at', 'status', 'created_by', 'deleted_at',],
            'empty' => [],
            'default_random' => [],
            'confirmed' => [],
            'encrypted' => [],
            'hashed' => [],
            'masked' => [],
            'code' => [],
            'wysiwyg' => [],
            'validator' => [
                'title' => 'required|max:255|',
                /*'quantity' => 'required',*/
                'duration' => 'required',
                'price' => 'required',
                'department_id' => 'required',
                /*  'price' => 'required',*/
            ],
            'relations' => [
                'type' => [
                    'data' => Laralum::dropdown('duration_types'),
                    'value' => 'value',
                    'show' => 'show',
                ],
                'department_id' => [
                    'data' => Laralum::dropdown('departments'),
                    'value' => 'value',
                    'show' => 'show',
                ],

            ],
        ],
    ],

    'lab_tests' => [
        'table' => 'lab_tests',
        'create' => [
            'hidden' => ['id', 'created_at', 'updated_at', 'created_by', 'deleted_at'],
            'default_random' => [],
            'confirmed' => [],
            'encrypted' => [],
            'hashed' => [],
            'masked' => [],
            'code' => [],
            'wysiwyg' => [],
            'validator' => [
                'name' => 'required|max:255|',
                /*'quantity' => 'required',*/
                'price' => 'required|numeric'
            ],
            'relations' => [
                'department_id' => [
                    'data' => Laralum::dropdown('departments'),
                    'value' => 'value',
                    'show' => 'show',
                ],
                'type' => [
                    'data' => Laralum::dropdown('labTypes'),
                    'value' => 'value',
                    'show' => 'show',
                ],
                'category_id' => [
                    'data' => Laralum::dropdown('categories'),
                    'value' => 'value',
                    'show' => 'show',
                ],
            ],
        ],
        'edit' => [
            'hidden' => ['id', 'created_at', 'updated_at', 'status', 'created_by', 'deleted_at'],
            'empty' => [],
            'default_random' => [],
            'confirmed' => [],
            'encrypted' => [],
            'hashed' => [],
            'masked' => [],
            'code' => [],
            'wysiwyg' => [],
            'validator' => [
                'name' => 'required|max:255|',
            ],
            'relations' => [
                'department_id' => [
                    'data' => Laralum::dropdown('departments'),
                    'value' => 'value',
                    'show' => 'show',
                ],
                'type' => [
                    'data' => Laralum::dropdown('labTypes'),
                    'value' => 'value',
                    'show' => 'show',
                ],
                'category_id' => [
                    'data' => Laralum::dropdown('categories'),
                    'value' => 'value',
                    'show' => 'show',
                ],
            ],
        ],
    ],
    'professions' => [
        'table' => 'professions',
        'create' => [
            'hidden' => ['id', 'created_at', 'updated_at', 'status', 'created_by', 'deleted_at', 'is_private'],
            'default_random' => [],
            'confirmed' => [],
            'encrypted' => [],
            'hashed' => [],
            'masked' => [],
            'code' => [],
            'wysiwyg' => [],
            'validator' => [
                'name' => 'required|max:255|',
            ],
        ],
        'edit' => [
            'hidden' => ['id', 'created_at', 'updated_at', 'status', 'created_by', 'deleted_at', 'is_private'],
            'empty' => [],
            'default_random' => [],
            'confirmed' => [],
            'encrypted' => [],
            'hashed' => [],
            'masked' => [],
            'code' => [],
            'wysiwyg' => [],
            'validator' => [
                'name' => 'required|max:255|',
            ],
        ],
    ],
    'document_types' => [
        'table' => 'document_types',
        'create' => [
            'hidden' => ['id', 'created_at', 'updated_at', 'status', 'deleted_at', 'file_name', 'is_downloadable', 'file'],
            'default_random' => [],
            'confirmed' => [],
            'encrypted' => [],
            'hashed' => [],
            'masked' => [],
            'code' => [],
            'wysiwyg' => [],
            'validator' => [
                'title' => 'required|max:255|',
                'description' => '',
            ],
        ],
        'edit' => [
            'hidden' => ['id', 'created_at', 'updated_at', 'status', 'deleted_at', 'is_downloadable', 'file_name', 'file'],
            'empty' => [],
            'default_random' => [],
            'confirmed' => [],
            'encrypted' => [],
            'hashed' => [],
            'masked' => [],
            'code' => [],
            'wysiwyg' => [],
            'validator' => [
                'title' => 'required|max:255|',
                'description' => '',
            ],
        ],
    ],
    'staff' => [
        'table' => 'staff',
        'create' => [
            'hidden' => ['id', 'created_at', 'updated_at', 'status', 'deleted_at', 'user_id',
                'gender',
                'marital_status',
                'date_of_birth',
                'address',
                'contact_no',
                'contact_email',
                'created_by',
                'status',],
            'default_random' => [],
            'confirmed' => [],
            'encrypted' => [],
            'hashed' => [],
            'masked' => [],
            'code' => [],
            'wysiwyg' => [],
            'validator' => [
                'title' => 'required|max:255|',
                'department' => 'required',
            ],
            'relations' => [
                'department' => [
                    'data' => Laralum::dropdown('staffDepartments'),
                    'value' => 'value',
                    'show' => 'show',
                ],
            ],
        ],
        'edit' => [
            'hidden' => ['id', 'created_at', 'updated_at', 'status', 'deleted_at', 'gender', 'user_id',
                'marital_status',
                'date_of_birth',
                'address',
                'contact_no',
                'contact_email',
                'created_by',
                'status',],
            'empty' => [],
            'default_random' => [],
            'confirmed' => [],
            'encrypted' => [],
            'hashed' => [],
            'masked' => [],
            'code' => [],
            'wysiwyg' => [],
            'validator' => [
                'name' => 'required|max:255|',
                'department' => 'required',
            ],
            'relations' => [
                'department' => [
                    'data' => Laralum::dropdown('staffDepartments'),
                    'value' => 'value',
                    'show' => 'show',
                ],
            ],
        ],
    ],
];
