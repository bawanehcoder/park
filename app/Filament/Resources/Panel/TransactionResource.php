<?php

namespace App\Filament\Resources\Panel;

use App\Filament\Resources\Panel\TransactionResource\Pages;
use App\Filament\Resources\Panel\TransactionResource\RelationManagers;
use App\Filament\Resources\Panel\TransactionResource\Widgets\Overview;
use App\Models\Transaction;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TransactionResource extends Resource
{
    protected static ?string $model = Transaction::class;

    protected static ?string $navigationIcon = 'heroicon-o-arrow-trending-up';

    protected static ?string $navigationGroup = 'Payments';

    protected static ?int $navigationSort = 20;



    public static function getEloquentQuery(): Builder
    {
        return auth()->user()->hasRole('super_admin') ? static::getModel()::query() : static::getModel()::query()->where('company_id',auth()->user()->company_num);
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('transactionable.id'),
                TextColumn::make('transactionable_type'),
                TextColumn::make('amount')
                ->summarize([
                    Tables\Columns\Summarizers\Sum::make()
                        ->money(),
                ]),
                TextColumn::make('created_at'),
                TextColumn::make('status')->badge(),
                TextColumn::make('company.name'),

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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTransactions::route('/'),
            'create' => Pages\CreateTransaction::route('/create'),
            'edit' => Pages\EditTransaction::route('/{record}/edit'),
        ];
    }

    public static function getWidgets(): array
    {
        return [
            Overview::class
        ];
    }
}
