<?php

namespace App\Filament\Resources\Panel;

use Filament\Forms;
use App\Models\Car;
use Filament\Forms\Components\ColorPicker;
use Filament\Tables;
use Filament\Tables\Columns\ColorColumn;
use Illuminate\Support\HtmlString;
use Livewire\Component;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\DateTimePicker;
use App\Filament\Resources\Panel\CarResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\Panel\CarResource\RelationManagers;

class CarResource extends Resource
{
    protected static ?string $model = Car::class;

    protected static ?string $navigationIcon = 'heroicon-o-truck';

    protected static ?int $navigationSort = 4;

    protected static ?string $navigationGroup = 'Admin';

    public static function getModelLabel(): string
    {
        return __('crud.cars.itemTitle');
    }

    public static function getPluralModelLabel(): string
    {
        return __('crud.cars.collectionTitle');
    }

    public static function getNavigationLabel(): string
    {
        return __('crud.cars.collectionTitle');
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Section::make()->schema([
                Grid::make(['default' => 2])->schema([


                    TextInput::make('number')
                        ->required()
                        ->numeric()
                        ->step(1)
                        ->autofocus(),

                    Select::make('user_id')
                        ->required()
                        ->relationship('user', 'name')
                        ->searchable()
                        ->preload()
                        ->native(false),

                    Select::make('brand')
                        ->required()
                        ->relationship('car_brand', 'name')
                        ->searchable()
                        ->preload()
                        ->native(false)
                        ->reactive()
                        ->afterStateUpdated(function ($set) {
                            $set('model', null);
                        }),

                    Select::make('model')
                        ->required()
                        ->relationship('car_model', 'name', function ($query, $get) {
                            if ($brandId = $get('brand')) {
                                $query->where('brand_id', $brandId);
                            }
                        })
                        ->searchable()
                        ->preload()
                        ->native(false)
                        ->reactive(),

                    ColorPicker::make('color')
                        ->required(),




                ]),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->poll('60s')
            ->columns([
                TextColumn::make('code')
                ->getStateUsing(function ($record) {
                    return new HtmlString('<div style="width:50px">'. $record->code .'</div>');
                }),

                TextColumn::make('number'),

                TextColumn::make('car_brand.name')->limit(255),
                TextColumn::make('car_model.name')->limit(255),

                ColorColumn::make('color'),

                TextColumn::make('user.name'),

                
            ])
            ->filters([Tables\Filters\TrashedFilter::make()])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),

                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
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
            'index' => Pages\ListCars::route('/'),
            'create' => Pages\CreateCar::route('/create'),
            'view' => Pages\ViewCar::route('/{record}'),
            'edit' => Pages\EditCar::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return auth()->user()->hasRole('super_admin') ? static::getModel()::query()->withoutGlobalScopes([
            SoftDeletingScope::class,
        ]) : static::getModel()::query()->where('company_id',auth()->user()->company_num)->withoutGlobalScopes([
            SoftDeletingScope::class,
        ]);
    }
}
