<?php

return [
    'plans' => [
        'itemTitle' => 'الخطة',
        'collectionTitle' => 'الخطط',
        'inputs' => [
            'image' => [
                'label' => 'الصورة',
                'placeholder' => 'الصورة',
            ],
            'name' => [
                'label' => 'الاسم',
                'placeholder' => 'الاسم',
            ],
            'price' => [
                'label' => 'السعر',
                'placeholder' => 'السعر',
            ],
            'duration' => [
                'label' => 'المدة',
                'placeholder' => 'المدة',
            ],
            'slots' => [
                'label' => 'المقاعد',
                'placeholder' => 'المقاعد',
            ],
            'deleted_at' => [
                'label' => 'تاريخ الحذف',
                'placeholder' => 'تاريخ الحذف',
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
        'itemTitle' => 'الشركة',
        'collectionTitle' => 'الشركات',
        'inputs' => [
            'image' => [
                'label' => 'الصورة',
                'placeholder' => 'الصورة',
            ],
            'name' => [
                'label' => 'الاسم',
                'placeholder' => 'الاسم',
            ],
            'address' => [
                'label' => 'العنوان',
                'placeholder' => 'العنوان',
            ],
            'deleted_at' => [
                'label' => 'تاريخ الحذف',
                'placeholder' => 'تاريخ الحذف',
            ],
        ],
    ],
    'parkings' => [
        'itemTitle' => 'موقف السيارات',
        'collectionTitle' => 'مواقف السيارات',
        'inputs' => [
            'name' => [
                'label' => 'الاسم',
                'placeholder' => 'الاسم',
            ],
            'address' => [
                'label' => 'العنوان',
                'placeholder' => 'العنوان',
            ],
            'company_id' => [
                'label' => 'معرف الشركة',
                'placeholder' => 'معرف الشركة',
            ],
        ],
    ],
    'slots' => [
        'itemTitle' => 'المقعد',
        'collectionTitle' => 'المقاعد',
        'inputs' => [
            'code' => [
                'label' => 'الرمز',
                'placeholder' => 'الرمز',
            ],
            'status' => [
                'label' => 'الحالة',
                'placeholder' => 'الحالة',
            ],
            'parking_id' => [
                'label' => 'معرف موقف السيارات',
                'placeholder' => 'معرف موقف السيارات',
            ],
        ],
    ],
    'bookings' => [
        'itemTitle' => 'الحجز',
        'collectionTitle' => 'الحجوزات',
        'inputs' => [
            'start' => [
                'label' => 'البداية',
                'placeholder' => 'البداية',
            ],
            'end' => [
                'label' => 'النهاية',
                'placeholder' => 'النهاية',
            ],
            'user_id' => [
                'label' => 'معرف المستخدم',
                'placeholder' => 'معرف المستخدم',
            ],
            'car_id' => [
                'label' => 'معرف السيارة',
                'placeholder' => 'معرف السيارة',
            ],
            'parking_id' => [
                'label' => 'معرف موقف السيارات',
                'placeholder' => 'معرف موقف السيارات',
            ],
            'slot_id' => [
                'label' => 'معرف المقعد',
                'placeholder' => 'معرف المقعد',
            ],
            'company_id' => [
                'label' => 'معرف الشركة',
                'placeholder' => 'معرف الشركة',
            ],
        ],
    ],
    'cars' => [
        'itemTitle' => 'السيارة',
        'collectionTitle' => 'السيارات',
        'inputs' => [
            'number' => [
                'label' => 'الرقم',
                'placeholder' => 'الرقم',
            ],
            'brand' => [
                'label' => 'الماركة',
                'placeholder' => 'الماركة',
            ],
            'color' => [
                'label' => 'اللون',
                'placeholder' => 'اللون',
            ],
            'user_id' => [
                'label' => 'معرف المستخدم',
                'placeholder' => 'معرف المستخدم',
            ],
            'code' => [
                'label' => 'الرمز',
                'placeholder' => 'الرمز',
            ],
            'deleted_at' => [
                'label' => 'تاريخ الحذف',
                'placeholder' => 'تاريخ الحذف',
            ],
        ],
    ],
];
