<?php

namespace App\Filament\Resources\Panel;

use App\Filament\Resources\ParkingResource\RelationManagers\BookingsRelationManager;
use App\Filament\Resources\ParkingResource\RelationManagers\UsersRelationManager;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Tables;
use Livewire\Component;
use App\Models\company;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\FileUpload;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\Panel\CompanyResource\Pages;
use App\Filament\Resources\Panel\CompanyResource\RelationManagers;

class CompanyResource extends Resource
{
    protected static ?string $model = company::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-office';

    protected static ?int $navigationSort = 2;

    protected static ?string $navigationGroup = 'Admin';

    public static function getEloquentQuery(): Builder
    {
        return auth()->user()->hasRole('super_admin') ? static::getModel()::query()->withoutGlobalScopes([
            SoftDeletingScope::class,
        ]) : static::getModel()::query()->where('company_id',auth()->user()->company->id)->withoutGlobalScopes([
            SoftDeletingScope::class,
        ]);
    }

    public static function getModelLabel(): string
    {
        return __('crud.companies.itemTitle');
    }

    public static function getPluralModelLabel(): string
    {
        return __('crud.companies.collectionTitle');
    }

    public static function getNavigationLabel(): string
    {
        return __('crud.companies.collectionTitle');
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Section::make()->schema([
                Grid::make(['default' => 1])->schema([
                    FileUpload::make('image')
                    ->label(__('Image'))
                        ->rules(['image'])
                        ->nullable()
                        ->maxSize(1024)
                        ->image()
                        ->imageEditor()
                        ->imageEditorAspectRatios([null, '16:9', '4:3', '1:1']),

                    TextInput::make('name')
                    ->label(__('Name'))
                        ->required()
                        ->string(),

                    TextInput::make('address')
                    ->label(__('Address'))
                        ->required()
                        ->string(),
                    Select::make('owner_id')
                    ->label(__('Owner'))
                        ->searchable()
                        ->preload()
                        ->relationship('owner', 'name')
                ]),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->poll('60s')
            ->columns([
                ImageColumn::make('image')
                ->label(__('Image'))
                ->visibility('public'),

                TextColumn::make('name')
                ->label(__('Name'))
                    ->sortable()
                    ->toggleable()
                    ->searchable(),

                TextColumn::make('address')
                ->label(__('Address'))
                ,
                TextColumn::make('owner.name')
                ->label(__('Owner'))
                ->badge(),


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
        return [
            RelationManagers\ParkingsRelationManager::class,
            BookingsRelationManager::class,
            UsersRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCompanies::route('/'),
            'create' => Pages\CreateCompany::route('/create'),
            'view' => Pages\ViewCompany::route('/{record}'),
            'edit' => Pages\EditCompany::route('/{record}/edit'),
        ];
    }


}
