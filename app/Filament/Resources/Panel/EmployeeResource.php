<?php

namespace App\Filament\Resources\Panel;

use App\Filament\Resources\Panel\EmployeeResource\Pages;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class EmployeeResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';
    protected static ?string $navigationLabel = ('Employees');
    protected static ?string $navigationGroup = ('User Management');
    
    protected static ?int $navigationSort = 16;
    public static function getModelLabel(): string
    {
        return __('Employees');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Employee'); 
    }

    public static function getNavigationLabel(): string
{
    return __('Employees'); // تم إغلاق القوس هنا
}
    public static function getEloquentQuery(): Builder
    {
        return auth()->user()->hasRole('super_admin') 
            ? static::getModel()::query()->whereHas('roles') 
            : static::getModel()::query()->where('company_id', auth()->user()->company_num)->whereHas('roles');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->label(__('Name'))
                    ->required()
                    ->maxLength(255),

                TextInput::make('email')
                    ->label(__('Email'))
                    ->email()
                    ->required(),

                TextInput::make('password')
                    ->label(__('Password'))
                    ->password()
                    ->confirmed()
                    ->maxLength(255),

                TextInput::make('password_confirmation')
                    ->label(__('Confirm Password'))
                    ->password()
                    ->maxLength(255)
                    ->visible(fn($livewire) => $livewire instanceof \Filament\Resources\Pages\CreateRecord || $livewire instanceof \Filament\Resources\Pages\EditRecord),

                TextInput::make('phone')
                    ->label(__('Phone'))
                    ->tel()
                    ->maxLength(255),

                Forms\Components\Select::make('roles')
                    ->label(__('Roles'))
                    ->relationship('roles', 'name')
                    ->preload()
                    ->searchable(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->label(__('Name')),
                TextColumn::make('phone')->label(__('Phone')),
                TextColumn::make('roles.name')->badge()->label(__('Roles')),
                TextColumn::make('company_name')->label(__('Company Name')),
            ])
            ->filters([])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEmployees::route('/'),
            'create' => Pages\CreateEmployee::route('/create'),
            'edit' => Pages\EditEmployee::route('/{record}/edit'),
        ];
    }  
}
