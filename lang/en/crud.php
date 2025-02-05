<?php

return [
    'plans' => [
        'itemTitle' => 'Plan',
        'collectionTitle' => 'Plans',
        'inputs' => [
            'image' => [
                'label' => 'Image',
                'placeholder' => 'Image',
            ],
            'name' => [
                'label' => 'Name',
                'placeholder' => 'Name',
            ],
            'price' => [
                'label' => 'Price',
                'placeholder' => 'Price',
            ],
            'duration' => [
                'label' => 'Duration',
                'placeholder' => 'Duration',
            ],
            'slots' => [
                'label' => 'Slots',
                'placeholder' => 'Slots',
            ],
            'deleted_at' => [
                'label' => 'Deleted at',
                'placeholder' => 'Deleted at',
            ],
        ],
        'filament' => [
            'image' => [
                'helper_text' => '',
                'label' => '',
            ],
            'name' => [
                'helper_text' => '',
                'label' => '',
                'description' => '',
            ],
            'price' => [
                'helper_text' => '',
                'label' => '',
                'description' => '',
            ],
            'duration' => [
                'helper_text' => '',
                'label' => '',
                'description' => '',
            ],
            'slots' => [
                'helper_text' => '',
                'label' => '',
                'description' => '',
            ],
            'deleted_at' => [
                'helper_text' => '',
                'label' => '',
            ],
        ],
    ],
    'companies' => [
        'itemTitle' => 'Company',
        'collectionTitle' => 'Companies',
        'inputs' => [
            'image' => [
                'label' => 'Image',
                'placeholder' => 'Image',
            ],
            'name' => [
                'label' => 'Name',
                'placeholder' => 'Name',
            ],
            'address' => [
                'label' => 'Address',
                'placeholder' => 'Address',
            ],
            'deleted_at' => [
                'label' => 'Deleted at',
                'placeholder' => 'Deleted at',
            ],
        ],
        'filament' => [
            'image' => [
                'helper_text' => '',
                'label' => '',
            ],
            'name' => [
                'helper_text' => '',
                'label' => '',
                'description' => '',
            ],
            'address' => [
                'helper_text' => '',
                'label' => '',
                'description' => '',
            ],
            'deleted_at' => [
                'helper_text' => '',
                'label' => '',
            ],
        ],
    ],
    'parkings' => [
        'itemTitle' => 'Parking',
        'collectionTitle' => 'Parkings',
        'inputs' => [
            'name' => [
                'label' => 'Name',
                'placeholder' => 'Name',
            ],
            'address' => [
                'label' => 'Address',
                'placeholder' => 'Address',
            ],
            'company_id' => [
                'label' => 'Company id',
                'placeholder' => 'Company id',
            ],
        ],
        'filament' => [
            'name' => [
                'helper_text' => '',
                'label' => '',
                'description' => '',
            ],
            'address' => [
                'helper_text' => '',
                'label' => '',
                'description' => '',
            ],
            'company_id' => [
                'helper_text' => '',
                'loading_message' => '',
                'no_result_message' => '',
                'search_message' => '',
                'label' => '',
            ],
        ],
    ],
    'slots' => [
        'itemTitle' => 'Slot',
        'collectionTitle' => 'Slots',
        'inputs' => [
            'code' => [
                'label' => 'Code',
                'placeholder' => 'Code',
            ],
            'status' => [
                'label' => 'Status',
                'placeholder' => 'Status',
            ],
            'parking_id' => [
                'label' => 'Parking id',
                'placeholder' => 'Parking id',
            ],
        ],
        'filament' => [
            'code' => [
                'helper_text' => '',
                'label' => '',
            ],
            'status' => [
                'helper_text' => '',
                'label' => '',
                'description' => '',
            ],
            'parking_id' => [
                'helper_text' => '',
                'loading_message' => '',
                'no_result_message' => '',
                'search_message' => '',
                'label' => '',
            ],
        ],
    ],
    'bookings' => [
        'itemTitle' => 'Booking',
        'collectionTitle' => 'Bookings',
        'inputs' => [
            'start' => [
                'label' => 'Start',
                'placeholder' => 'Start',
            ],
            'end' => [
                'label' => 'End',
                'placeholder' => 'End',
            ],
            'user_id' => [
                'label' => 'User id',
                'placeholder' => 'User id',
            ],
            'car_id' => [
                'label' => 'Car id',
                'placeholder' => 'Car id',
            ],
            'parking_id' => [
                'label' => 'Parking id',
                'placeholder' => 'Parking id',
            ],
            'slot_id' => [
                'label' => 'Slot id',
                'placeholder' => 'Slot id',
            ],
            'company_id' => [
                'label' => 'Company id',
                'placeholder' => 'Company id',
            ],
        ],
        'filament' => [
            'start' => [
                'helper_text' => '',
                'label' => '',
            ],
            'end' => [
                'helper_text' => '',
                'label' => '',
            ],
            'user_id' => [
                'helper_text' => '',
                'loading_message' => '',
                'no_result_message' => '',
                'search_message' => '',
                'label' => '',
            ],
            'car_id' => [
                'helper_text' => '',
                'loading_message' => '',
                'no_result_message' => '',
                'search_message' => '',
                'label' => '',
            ],
            'parking_id' => [
                'helper_text' => '',
                'loading_message' => '',
                'no_result_message' => '',
                'search_message' => '',
                'label' => '',
            ],
            'slot_id' => [
                'helper_text' => '',
                'loading_message' => '',
                'no_result_message' => '',
                'search_message' => '',
                'label' => '',
            ],
            'company_id' => [
                'helper_text' => '',
                'loading_message' => '',
                'no_result_message' => '',
                'search_message' => '',
                'label' => '',
            ],
        ],
    ],
    'cars' => [
        'itemTitle' => 'Car',
        'collectionTitle' => 'Cars',
        'inputs' => [
            'number' => [
                'label' => 'Number',
                'placeholder' => 'Number',
            ],
            'brand' => [
                'label' => 'Brand',
                'placeholder' => 'Brand',
            ],
            'color' => [
                'label' => 'Color',
                'placeholder' => 'Color',
            ],
            'user_id' => [
                'label' => 'User id',
                'placeholder' => 'User id',
            ],
            'code' => [
                'label' => 'Code',
                'placeholder' => 'Code',
            ],
            'deleted_at' => [
                'label' => 'Deleted at',
                'placeholder' => 'Deleted at',
            ],
        ],
        'filament' => [
            'number' => [
                'helper_text' => '',
                'label' => '',
                'description' => '',
            ],
            'brand' => [
                'helper_text' => '',
                'label' => '',
            ],
            'color' => [
                'helper_text' => '',
                'label' => '',
                'description' => '',
            ],
            'user_id' => [
                'helper_text' => '',
                'loading_message' => '',
                'no_result_message' => '',
                'search_message' => '',
                'label' => '',
            ],
            'code' => [
                'helper_text' => '',
                'label' => '',
            ],
            'deleted_at' => [
                'helper_text' => '',
                'label' => '',
            ],
        ],
    ],
];
