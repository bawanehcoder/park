<?php

namespace App\Filament\Resources\ParkingResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\HtmlString;

class BookingsRelationManager extends RelationManager
{
    protected static string $relationship = 'bookings';

    public function form(Form $form): Form
    {
        return $form->schema([
     
            Section::make('Car')
                ->schema([
                    Grid::make(['default' => 4])->schema([
                        Select::make('car_id')
                            ->required()
                            ->label('Car Number')
                            ->relationship('car', 'number')
                            ->searchable()
                            ->preload()
                            ->native(false)
                            ->reactive()

                            ->afterStateUpdated(function ($state, $set, $get) {
                                $car = \App\Models\Car::find($state);
                                if ($car) {
                                    $set('car_brand', $car->car_brand->name);
                                    $set('car_model', $car->car_model->name);
                                    $set('car_color', $car->color);
                                    $set('user_id', $car->user_id);
                                    $user = \App\Models\User::find($car->user_id);
                                    if ($user) {
                                        $set('user_phone', $user->phone);
                                    } else {
                                        $set('user_phone', null);
                                    }
                                } else {
                                    $set('car_brand', null);
                                    $set('car_model', null);
                                    $set('car_color', null);
                                    $set('user_id', null);
                                }

                            }),

                        TextInput::make('car_brand')
                            ->label('Car Brand')
                            ->disabled(),

                        TextInput::make('car_model')
                            ->label('Car Model')
                            ->disabled(),

                        ColorPicker::make('car_color')
                            ->label('Car Color')
                            ->disabled(),

                    ]),
                ]),


            Section::make('Client')
                ->schema([
                    Grid::make(['default' => 2])->schema([
                        Select::make('user_id')
                            ->required()
                            ->label('Name')
                            ->relationship('user', 'name')
                            ->searchable()
                            ->preload()
                            ->native(false)
                            ->reactive()
                            ->afterStateUpdated(function ($state, $set) {
                                $user = \App\Models\User::find($state);
                                if ($user) {
                                    $set('user_phone', $user->phone);
                                } else {
                                    $set('user_phone', null);
                                }
                            }),

                        TextInput::make('user_phone')
                            ->label('Phone Number')
                            ->disabled(),


                    ]),
                ]),


            Section::make('Parking')
                ->schema([
                    Grid::make(['default' => 3])->schema([
                        Select::make('parking_id')
                            ->required()
                            ->label('Name')
                            ->relationship('parking', 'name')
                            ->searchable()
                            ->preload()
                            ->native(false)
                            ->reactive()
                            ->afterStateUpdated(function ($state, $set) {
                                $parking = \App\Models\Parking::find($state);
                                if ($parking) {
                                    $set('supervisor_id', $parking->supervisor_id);
                                    $set('company_id', $parking->company_id);
                                    $set('supervisor', $parking->supervisor_id);
                                    $set('company', $parking->company_id);
                                } else {
                                    $set('supervisor_id', null);
                                    $set('company_id', null);
                                }
                            }),
                        // Hidden::make('supervisor_id'),
                        Hidden::make('company_id'),
                        Select::make('supervisor')
                            ->required()
                            ->label('Supervisor')
                            ->relationship('user', 'name')
                            ->searchable()
                            ->preload()
                            ->native(false)
                            ->disabled(),

                        Select::make('company')
                            ->required()
                            ->label('Company')
                            ->relationship('company', 'name')
                            ->searchable()
                            ->preload()
                            ->native(false)
                            ->disabled(),


                        DateTimePicker::make('start')
                            ->rules(['date'])
                            ->disabled()
                            ->native(false),
                        DateTimePicker::make('end')
                            ->rules(['date'])
                            ->disabled()
                            ->native(false),
                        Select::make('slot_id')
                            ->disabled()
                            ->label('Slot number')
                            ->relationship('slot', 'id', function ($query, $get) {
                                if ($parkingId = $get('parking_id')) {
                                    $query->where('parking_id', $parkingId); // جلب السلوتات بناءً على Parking المختار
                                }
                            })
                            ->reactive()
                            ->searchable()
                            ->preload()
                            ->native(false),


                    ]),
                ]),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')
            ->columns([
                // TextColumn::make('id')->sortable(),
                TextColumn::make('code')
                    ->getStateUsing(function ($record) {
                        return new HtmlString($record->code);
                    }),
                TextColumn::make('parking.name')->sortable()
                ->html()
                ->getStateUsing(function ($record) {
                    return 'Name : ' . $record->parking->name .'<br/>' .  'Slot : ' . $record?->slot?->id;
                }),
                TextColumn::make('company.name')->sortable(),
                TextColumn::make('parking.supervisor.name')->sortable(),
                TextColumn::make('car.number')->sortable()
                ->html()
                ->getStateUsing(function ($record) {
                    return 'Number : ' . $record->car->number 
                    .'<br/>' .  'Brand : ' . $record->car->car_brand-> name
                    .'<br/>' .  'Model : ' . $record->car->car_model-> name
                    .'<br/>' .  'Owner : ' . $record->car->user-> name;
                }),
                TextColumn::make('employee.name')->sortable(),
                TextColumn::make('duration')->sortable(),
                TextColumn::make('status')->badge()->colors([
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
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
