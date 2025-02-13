<?php

namespace App\Filament\Resources\Panel;

use App\Filament\Resources\BookingResource\Widgets\Overview;
use Filament\Forms;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Illuminate\Support\HtmlString;
use Livewire\Component;
use App\Models\Booking;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\DateTimePicker;
use App\Filament\Resources\Panel\BookingResource\Pages;
use App\Filament\Resources\Panel\BookingResource\RelationManagers;

class BookingResource extends Resource
{
    protected static ?string $model = Booking::class;

    protected static ?string $navigationIcon = 'heroicon-o-clock';

    protected static ?int $navigationSort = 5;

    protected static ?string $navigationGroup = 'Admin';

    public static function getEloquentQuery(): Builder
    {

        $role = auth()->user()->roles()->first()->name;
        $query = null;
        switch ($role) {
            case 'super_admin':
                $query = static::getModel()::query();
                break;

            case 'owner':
                $query = static::getModel()::query()->where('company_id', auth()->user()->company_num);
                break;
            case 'supervisor':
                $query = static::getModel()::query()->where('company_id', auth()->user()->company_num)->whereHas('parking',function($subQuery){
                    return $subQuery->where('supervisor_id',auth()->user()->id);
                });
                break;

            case 'driver':
            default:
                $query = static::getModel()::query()->where('company_id', auth()->user()->company_num)->where('parking_id', auth()->user()->parking_id);
                break;


        }
        return $query;
    }

    public static function getModelLabel(): string
    {
        return __('crud.bookings.itemTitle');
    }

    public static function getPluralModelLabel(): string
    {
        return __('crud.bookings.collectionTitle');
    }

    public static function getNavigationLabel(): string
    {
        return __('crud.bookings.collectionTitle');
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
     
            Section::make('Car')
                ->schema([
                    Grid::make(['default' => 4])->schema([
                        Select::make('car_id')
                        ->label(__('Car_Id'))
                            ->required()
                            ->label(__('Car Number'))
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
                        ->label(__('Parking_Id'))

                            ->required()
                            ->label('Name')
                            ->label(__('Name'))
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
                                    $set('supervisor', $parking->supervisor->name);
                                    $set('company', $parking->company_id);
                                } else {
                                    $set('supervisor_id', null);
                                    $set('company_id', null);
                                }
                            }),
                        // Hidden::make('supervisor_id'),
                        Hidden::make('company_id')
                        ->label(__('company_id')),
                        TextInput::make('supervisor')
                        ->label(__('Supervisor'))
                        ->disabled(),

                        Select::make('company')
                        ->label(__('Company'))
                            ->required()
                            ->label(__('Company'))
                            ->relationship('company', 'name')
                            ->searchable()
                            ->preload()
                            ->native(false)
                            ->disabled(),


                        DateTimePicker::make('start')
                        ->label(__('Start'))
                            ->rules(['date'])
                            ->disabled()
                            ->native(false),
                        DateTimePicker::make('end')
                        ->label(__('End'))

                            ->rules(['date'])
                            ->disabled()
                            ->native(false),
                        Select::make('slot_id')
                        ->label(__('Slot_Id'))

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

    public static function table(Table $table): Table
    {
        return $table
            ->poll('60s')
            ->columns([
                // TextColumn::make('id')->sortable(),
                TextColumn::make('code')
                ->label(__('Code'))
                    ->getStateUsing(function ($record) {
                        return new HtmlString($record->code);
                    }),
                TextColumn::make('parking.name')->sortable()
                ->label(__('Parking.Name'))

                ->html()
                ->getStateUsing(function ($record) {
                    return 'Name : ' . $record->parking->name .'<br/>' .  'Slot : ' . $record?->slot?->id;
                }),
                TextColumn::make('company.name')->sortable()
                ->label(__('Company.Name')),

                TextColumn::make('parking.supervisor.name')->sortable()
                ->label(__('Parking.Supervisor.Name')),

                TextColumn::make('car.number')->sortable()
                ->label(__('Car.Number'))

                ->html()
                ->getStateUsing(function ($record) {
                    return 'Number : ' . $record->car->number 
                    .'<br/>' .  'Brand : ' . $record->car->car_brand-> name
                    .'<br/>' .  'Model : ' . $record->car->car_model-> name
                    .'<br/>' .  'Owner : ' . $record->car->user-> name;
                }),
                TextColumn::make('employee.name')->sortable()
                ->label(__('Employee.Name')),
                TextColumn::make('duration')->sortable()
                ->label(__('Duration')),
                TextColumn::make('status')->badge()->colors([
                    'warning' => __('pending'),
                    'danger' => __('cancelled'),
                    'primary' => __('confirmed'),
                    'info' => __('parked'),
                    'success' => __('completed'),
                
                ])


            ])
            ->filters([])
            ->actions([
                // Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
                Action::make('change_status')
                ->label(__('Change_Status'))
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
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('id', 'desc');
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBookings::route('/'),
            'create' => Pages\CreateBooking::route('/create'),
            'view' => Pages\ViewBooking::route('/{record}'),
            'edit' => Pages\EditBooking::route('/{record}/edit'),
        ];
    }

    public static function getWidgets(): array
    {
        return [
            Overview::class
        ];
    }

}
