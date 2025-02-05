<?php

namespace App\Filament\Resources\Panel\ParkingResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Components\Hidden;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Forms\Components\Grid;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\RichEditor;
use App\Filament\Resources\Panel\ParkingResource;
use Filament\Resources\RelationManagers\RelationManager;
use Illuminate\Support\HtmlString;

class SlotsRelationManager extends RelationManager
{
    protected static string $relationship = 'slots';

    protected static ?string $recordTitleAttribute = 'id';

    public function form(Form $form): Form
    {
        return $form->schema([
            Grid::make(['default' => 1])->schema([
                // RichEditor::make('code')
                //     ->nullable()
                //     // ->readonly()
                //     ->fileAttachmentsVisibility('public'),

                Hidden::make('status')
                    // ->readonly()
                    ->default('available')
                    ->string(),
            ]),
        ]);
    }
    //->description(fn () => new HtmlString('<span class="text-red-500">hi</span>'))
    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id'),
                TextColumn::make('code')
                ->getStateUsing(function ($record) {
                    return new HtmlString('<div style="width:50px">'. $record->code .'</div>');
                }),

                TextColumn::make('status')->badge()->colors([
                    'success' => 'available',
                    'danger' => 'booked',
                ]),
            ])
            ->filters([])
            ->headerActions([Tables\Actions\CreateAction::make()])
            ->actions([
                // Tables\Actions\EditAction::make(),
                // Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
