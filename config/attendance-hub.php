<?php

return [
    'device' => [
        'ip' => env('ATTENDANCE_DEVICE_IP', '192.168.1.201'),
        'port' => (int) env('ATTENDANCE_DEVICE_PORT', 4370),
        'protocol' => env('ATTENDANCE_PROTOCOL', 'adms'), // adms, zkteco, standalone
        'timeout' => 10,
    ],

    'shifts' => [
        'morning' => [
            'name' => 'Morning Shift',
            'start' => '07:30',
            'end' => '15:30',
            'grace_minutes' => 15,
        ],
        'evening' => [
            'name' => 'Evening Shift',
            'start' => '14:30',
            'end' => '22:30',
            'grace_minutes' => 15,
        ],
        'night' => [
            'name' => 'Night / Emergency Shift',
            'start' => '22:00',
            'end' => '08:00',
            'grace_minutes' => 20,
        ],
    ],

    'multi_tenancy' => [
        'enabled' => true,
        'branch_column' => 'branch_id',
    ],
];
