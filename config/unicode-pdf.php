<?php

return [
    'default_engine' => env('UNICODE_PDF_ENGINE', 'mpdf'),

    'fonts' => [
        'default' => env('UNICODE_PDF_DEFAULT_FONT', 'SolaimanLipi'),
        'fallback' => explode(',', env('UNICODE_PDF_FALLBACK_FONTS', 'Nikosh,Kalpurush,Amiri,DejaVuSans')),
        'custom_path' => storage_path('fonts/'),
    ],

    'rtl' => (bool) env('UNICODE_PDF_RTL_SUPPORT', true),

    'report_letterhead' => [
        'logo' => public_path('images/center-logo.png'),
        'center_name' => 'APEX DIAGNOSTIC & ADVANCED RESEARCH CENTER',
        'sub_title' => 'ISO 15189:2022 Certified Molecular & Clinical Reference Laboratory',
        'address' => 'Plot 42, Health Avenue, Medical City, Dhaka 1212',
        'phone' => '+880 1700-000000, +880 2-9876543',
        'email' => 'reports@apexdiagnostics.test',
        'web' => 'https://diagnostics.apex-health.test',
    ],
];
