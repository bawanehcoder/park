<?php

namespace App\Filament\Widgets;

use App\Models\Booking;
use BezhanSalleh\FilamentShield\Traits\HasWidgetShield;
use Filament\Forms\Components\Select;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\HtmlString;

class LatestBooking extends BaseWidget
{
    use HasWidgetShield;

    protected int|string|array $columnSpan = 'full';

    protected static ?string $heading = 'Latest Booking';


    protected static ?int $sort = 2;

    public function getTableHeading(): string | Htmlable | null
    {
        return __('Latest Booking');
    }


    public function table(Table $table): Table
    {
        return $table
            ->query(function () {
                $role = auth()->user()->roles()->first()->name;
                $query = null;
                switch ($role) {
                    case 'super_admin':
                        $query = Booking::query();
                        break;

                    case 'owner':
                        $query = Booking::query()->where('company_id', auth()->user()->company_num);
                        break;
                    case 'supervisor':
                        $query = Booking::query()->where('company_id', auth()->user()->company_num)->whereHas('parking', function ($subQuery) {
                            return $subQuery->where('supervisor_id', auth()->user()->id);
                        });
                        break;

                    case 'driver':
                    default:
                        $query = Booking::query()->where('company_id', auth()->user()->company_num)->where('parking_id', auth()->user()->parking_id);
                        break;


                }
                return $query->orderBy('id', 'desc');
            })
            ->columns([
                // TextColumn::make('id')->sortable(),
                TextColumn::make('code')
                ->label(__('Code'))
                    ->getStateUsing(function ($record) {
                        return new HtmlString($record->code);
                    }),
                TextColumn::make('parking.name')->sortable()
                ->label(__('Parking'))
                    ->html()
                    ->getStateUsing(function ($record) {
                        return __('Name').' : ' . $record->parking->name . '<br/>' . __('Slot').' : ' . $record?->slot?->id;
                    }),
                TextColumn::make('company.name')->label(__('Company'))->sortable(),
                TextColumn::make('parking.supervisor.name')->sortable()->label(__('Supervisor')),
                TextColumn::make('car.number')->sortable()
                ->label(__('Car'))
                    ->html()
                    ->getStateUsing(function ($record) {
                        return __('Number').' : ' . $record->car->number
                            . '<br/>' .__('Brand'). ' : ' . $record->car->car_brand->name
                            . '<br/>' . __('Model').' : ' . $record->car->car_model->name
                            . '<br/>' . __('Owner').' : ' . $record->car->user->name;
                    }),
                TextColumn::make('employee.name')
                ->label(__('Employee'))
                ->sortable(),
                TextColumn::make('duration')
                ->label(__('Duration'))
                ->sortable(),
                TextColumn::make('status')
                ->label(__('Status'))
                ->badge()->colors([
                    'warning' => 'pending',
                    'danger' => 'cancelled',
                    'primary' => 'confirmed',
                    'info' => 'parked',
                    'success' => 'completed',
                ])


            ])
            ->filters([])
            ->actions([
                // Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
                Action::make('change_status')
                    ->iconButton()
                    ->icon('heroicon-m-arrow-path')
                    ->tooltip('Change Status')
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
                            ->disabled(fn($record) => $record->slot_id ?? null)
                            ->visible(fn($get) => $get('status') === 'parked' || $get('status') === 'completed' || $get('status') === 'cancelled')
                            ->default(fn($record) => $record->slot_id ?? null)

                            ->native(false),


                    ])
                    ->action(function ($record, array $data) {
                        // Update the status when the action is triggered
                        $record->status = $data['status'];
                        if (isset($data['slot_id'])) {
                            $record->slot_id = $data['slot_id'];
                        }
                        $record->employee_id = auth()->user()->id;
                        $record->save();

                    })
            ]);
    }
}
