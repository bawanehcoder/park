<?php
namespace App\Filament\Widgets;

use App\Models\Booking;
use App\Models\company;
use App\Models\Parking;
use App\Models\User;
use BezhanSalleh\FilamentShield\Traits\HasWidgetShield;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class Overview extends BaseWidget
{
    use HasWidgetShield;

    protected function getStats(): array
    {
        $user = auth()->user();

        // Check if the user has the super-admin role
        if ($user->hasRole('super_admin')) {
            return [
                Stat::make(__('Parkings Count'), Parking::all()->count()),
                Stat::make(__('Companies Count'), company::all()->count()),
                Stat::make(__('Employees Count'), User::all()->count()),
                Stat::make(__('Booked Cars Count'), Booking::all()->count()),
            ];
        }

        // Ensure that the user is associated with a company before querying
        if ($user->hasRole('owner')) {
            return [
                Stat::make('Parkings Count', Parking::where('company_id', $user->company_num)->count()),
                Stat::make('Employees Count', User::where('company_id', $user->company_num)->count()),
                Stat::make('Booked Cars Count', Booking::where('company_id', $user->company_num)->count()),
            ];
        }

        if ($user->hasRole('supervisor')) {
            return [
                Stat::make('Parkings Count', Parking::where('company_id', $user->company_num)->where('supervisor_id', auth()->user()->id)->count()),
                Stat::make('Employees Count', Parking::where('company_id', $user->company_num)->where('supervisor_id', auth()->user()->id)->get()->sum(function ($model) {
                    return $model->employees()->count();
                })),
                Stat::make('Booked Cars Count', Parking::where('company_id', $user->company_num)->where('supervisor_id', auth()->user()->id)->get()->sum(function ($model) {
                    return $model->bookings()->count();
                })),
            ];
        }
        if ($user->hasRole('driver')) {
            return [
                Stat::make('Parkings Count', Parking::where('company_id', $user->company_num)->where('id', auth()->user()->parking_id)->count()),
                // Stat::make('Employees Count', Parking::where('company_id', $user->company_num)->where('id', auth()->user()->parking_id)->get()->sum(function ($model) {
                //     return $model->employees()->count();
                // })),
                Stat::make('Pendings Cars Count', Parking::where('company_id', $user->company_num)->where('id', auth()->user()->parking_id)->get()->sum(function ($model) {
                    return $model->bookings()->where('status','pending')->count();
                })),
                Stat::make('Booked Cars Count', Parking::where('company_id', $user->company_num)->where('id', auth()->user()->parking_id)->get()->sum(function ($model) {
                    return $model->bookings()->count();
                })),
            ];
        }

        // Fallback for users without a company (if needed)
        return [];
    }
}
