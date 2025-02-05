<?php

namespace App\Filament\Resources\Panel;

use App\Filament\Resources\Panel\ParkingResource\RelationManagers\EmployessRelationManager;
use App\Filament\Resources\ParkingResource\RelationManagers\BookingsRelationManager;
use App\Filament\Resources\ParkingResource\RelationManagers\UsersRelationManager;
use Filament\Forms;
use Filament\Support\Enums\FontWeight;
use Filament\Tables;
use Livewire\Component;
use App\Models\Parking;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\Panel\ParkingResource\Pages;
use App\Filament\Resources\Panel\ParkingResource\RelationManagers;

class ParkingResource extends Resource
{
    protected static ?string $model = Parking::class;

    protected static ?string $navigationIcon = 'heroicon-o-map-pin';

    protected static ?int $navigationSort = 3;

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
                $query = static::getModel()::query()->where('company_id', auth()->user()->company_num)->where('supervisor_id', auth()->user()->id);
                break;

            case 'driver':
            default:
                $query = static::getModel()::query()->where('company_id', auth()->user()->company_num)->where('id', auth()->user()->parking_id);
                break;


        }
        return $query;
    }

    public static function getModelLabel(): string
    {
        return __('crud.parkings.itemTitle');
    }

    public static function getPluralModelLabel(): string
    {
        return __('crud.parkings.collectionTitle');
    }

    public static function getNavigationLabel(): string
    {
        return __('crud.parkings.collectionTitle');
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Section::make()->schema([
                Grid::make(['default' => 2])->schema([
                    TextInput::make('name')
                        ->required()
                        ->string()
                        ->autofocus(),

                    TextInput::make('address')
                        ->required()
                        ->string(),
                    TextInput::make('price_per_hour')
                        ->required()
                        ->default(0)
                        ->numeric(),

                    Select::make('company_id')
                        ->required()
                        ->relationship('company', 'name')
                        ->searchable()
                        ->preload()
                        ->native(false)
                        ->visible(auth()->user()->hasRole('super_admin')),

                    Select::make('supervisor_id')
                        ->required()
                        ->relationship('supervisor', 'name')
                        ->searchable()
                        ->preload()
                        ->native(false)
                ]),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->poll('60s')
            ->columns([
                Tables\Columns\Layout\Stack::make([

                    Tables\Columns\Layout\Stack::make([
                        Tables\Columns\TextColumn::make('name')
                            ->getStateUsing(function ($record) {
                                return 'Name : ' . $record->name;
                            }),
                        Tables\Columns\TextColumn::make('company.name')
                            ->getStateUsing(function ($record) {
                                return 'Company Name : ' . $record->company->name;
                            }),
                        Tables\Columns\TextColumn::make('supervisor.name')
                            ->getStateUsing(function ($record) {
                                return 'supervisor Name : ' . $record->supervisor->name;
                            }),
                        Tables\Columns\TextColumn::make('slot.count')
                            ->getStateUsing(function ($record) {
                                return 'Total capacity : ' . $record->slots()->count();
                            }),
                        Tables\Columns\TextColumn::make('employees')
                            ->getStateUsing(function ($record) {
                                return 'Employees Count : ' . $record->employees()->count();
                            }),



                    ]),
                ])->space(3),

            ])

            ->contentGrid([
                'md' => 2,
                'xl' => 3,
            ])
            ->filters([])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
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
        return [
            BookingsRelationManager::class,
            RelationManagers\SlotsRelationManager::class,
            EmployessRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListParkings::route('/'),
            'create' => Pages\CreateParking::route('/create'),
            'view' => Pages\ViewParking::route('/{record}'),
            'edit' => Pages\EditParking::route('/{record}/edit'),
        ];
    }
}
