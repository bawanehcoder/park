<?php

namespace App\Filament\Resources\Panel;

use App\Filament\Resources\Panel\UserResource\Pages;
use App\Filament\Resources\Panel\UserResource\RelationManagers;
use App\Filament\Resources\Panel\UserResource\RelationManagers\CarsRelationManager;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $navigationGroup = 'User Management';

    protected static ?int $navigationSort = 15;

    public static function getEloquentQuery(): Builder
    {
        return auth()->user()->hasRole('super_admin') ? static::getModel()::query()->doesntHave('roles') : static::getModel()::query()->where('company_id', auth()->user()->company_num)->doesntHave('roles');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()->schema([
                    TextInput::make('name')
                        ->required()
                        ->maxLength(255),
                    TextInput::make('email')
                        ->email()
                        ->required(),
                    TextInput::make('password')
                        ->password()
                        ->confirmed() // Add confirmation validation
                        ->maxLength(255)
                        ->label('Password'), // Optional: Add a label for clarity
                    TextInput::make('password_confirmation')
                        ->password()
                        ->maxLength(255)
                        ->label('Confirm Password')
                        ->visible(fn($livewire) => $livewire instanceof \Filament\Resources\Pages\CreateRecord || $livewire instanceof \Filament\Resources\Pages\EditRecord),
                    TextInput::make('phone')
                        ->tel()
                        ->maxLength(255),
                    Forms\Components\Select::make('roles')
                        ->relationship('roles', 'name')
                        ->preload()
                        ->searchable(),
                ])->columns(2)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name'),
                TextColumn::make('phone'),
                TextColumn::make('roles.name')->badge(),
                TextColumn::make('company_name'),
                // TextColumn::make('company_id'),

            ])
            ->filters([
                //
            ])
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
        return [
            CarsRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
