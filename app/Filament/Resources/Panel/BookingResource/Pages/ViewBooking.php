<?php

namespace App\Filament\Resources\Panel\BookingResource\Pages;

use Carbon\Carbon;
use Filament\Actions;
use Filament\Forms\Components\Select;
use Filament\Pages\Actions\Action;
use Filament\Resources\Pages\ViewRecord;
use App\Filament\Resources\Panel\BookingResource;
use Notification;

class ViewBooking extends ViewRecord
{
    protected static string $resource = BookingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\EditAction::make(),
            Action::make('change_status')
                ->form([
                    // Status dropdown
                    Select::make('status')
                        ->label('Booking Status')
                        ->options(function ($record) {
                            // Base options
                            $options = [
                                'pending' => 'Pending',
                                'confirmed' => 'Confirmed',
                                'parked' => 'Parked',
                                'completed' => 'Completed',
                                'cancelled' => 'Cancelled',
                            ];
            
                            // Disable options based on the current status
                            if ($record->status === 'confirmed') {
                                unset($options['pending']); // Disable Pending if confirmed
                            } elseif ($record->status === 'parked') {
                                unset($options['confirmed'], $options['pending']); // Disable Pending and Confirmed if Parked
                            } elseif ($record->status === 'completed') {
                                unset($options['pending'], $options['confirmed'], $options['parked']); // Disable Pending, Confirmed, and Parked if Completed
                            } elseif ($record->status === 'cancelled') {
                                unset($options['pending'], $options['confirmed'], $options['parked'], $options['completed']); // Disable all except Cancelled
                            }
            
                            return $options;
                        })
                        ->required()
                        ->placeholder('Select a status')
                        ->default(fn($record) => $record->status ?? 'pending')
                        ->reactive(),

                    Select::make('slot_id')
                        ->required()
                        ->label('Slot number')
                        ->relationship('slot', 'id', function ($query, $record) {
                            if ($parkingId = $record->parking_id) {
                                $query->where('parking_id', $parkingId);
                            }
                        })
                        ->reactive()
                        ->searchable()
                        ->preload()
                        ->disabled( fn($record) => $record->slot_id ?? null )
                        ->visible(fn($get) => $get('status') === 'parked' || $get('status') === 'completed' || $get('status') === 'cancelled')
                        ->default(fn($record) => $record->slot_id ?? null)

                        ->native(false),


                ])
                ->action(function ($record, array $data) {
                    // Update the status when the action is triggered
                    $record->status = $data['status'];
                    if(isset($data['slot_id'])){
                        $record->slot_id = $data['slot_id'];
                    }
                    $record->employee_id = auth()->user()->id;
                    $record->save();

                })
                ->keyBindings(['command+c', 'ctrl+c'])
        ];
    }
}
