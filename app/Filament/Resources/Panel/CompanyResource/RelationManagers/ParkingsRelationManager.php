<?php

namespace App\Filament\Resources\Panel\CompanyResource\RelationManagers;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Forms\Components\Grid;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\Panel\CompanyResource;
use Filament\Resources\RelationManagers\RelationManager;

class ParkingsRelationManager extends RelationManager
{
    protected static string $relationship = 'parkings';

    protected static ?string $recordTitleAttribute = 'name';

    // protected static ?string $label = __('parkings');
    

    public function form(Form $form): Form
    {
        return $form->schema([
            Grid::make(['default' => 1])->schema([
                TextInput::make('name')
                    ->label(__('Name'))
                    ->required()
                    ->string()
                    ->autofocus(),

                TextInput::make('address')
                    ->label(__('Address'))
                    ->required()
                    ->string(),
            ]),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                ->label(__('Name'))
                ,
                TextColumn::make('address')
                ->label(__('Address'))
            ])
            ->filters([])
            ->headerActions([Tables\Actions\CreateAction::make()])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
