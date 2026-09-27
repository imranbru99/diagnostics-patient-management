<?php

namespace App\Filament\Widgets;

use App\Models\AttendanceLog;
use App\Models\User;
use Filament\Widgets\Widget;

class AttendanceBiometricWidget extends Widget
{
    protected static string $view = 'filament.widgets.attendance-biometric-widget';
    protected static ?int $sort = 4;
    protected int | string | array $columnSpan = [
        'md' => 1,
        'xl' => 2,
    ];

    public function getViewData(): array
    {
        $today = today();
        $totalStaff = User::where('is_active', true)->where('role', '!=', 'patient')->count();
        $presentToday = AttendanceLog::whereDate('attendance_date', $today)->whereIn('status', ['present', 'late'])->count();
        $lateToday = AttendanceLog::whereDate('attendance_date', $today)->where('status', 'late')->count();

        $activeStaffLogs = AttendanceLog::with(['user', 'shift', 'branch'])
            ->whereDate('attendance_date', $today)
            ->latest('check_in')
            ->take(5)
            ->get();

        return [
            'totalStaff' => $totalStaff,
            'presentToday' => $presentToday,
            'lateToday' => $lateToday,
            'activeStaffLogs' => $activeStaffLogs,
            'deviceStatus' => 'Online (ADMS Syncing)',
        ];
    }
}
