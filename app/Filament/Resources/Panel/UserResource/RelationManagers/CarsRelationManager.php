<?php

namespace App\Filament\Resources\Panel\UserResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Columns\ColorColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\HtmlString;

class CarsRelationManager extends RelationManager
{
    protected static string $relationship = 'cars';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
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

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')
            ->columns([
                TextColumn::make('code')
                ->getStateUsing(function ($record) {
                    return new HtmlString($record->code );
                }),

                TextColumn::make('number'),

                TextColumn::make('car_brand.name')->limit(255),
                TextColumn::make('car_model.name')->limit(255),

                ColorColumn::make('color'),

                TextColumn::make('user.name'),

                
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
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
