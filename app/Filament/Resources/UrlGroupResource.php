<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UrlGroupResource\Pages;
use App\Filament\Resources\UrlGroupResource\RelationManagers;
use App\Models\UrlGroup;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Section;
class UrlGroupResource extends Resource
{
    protected static ?string $label = "网址分组";
    protected static ?string $model = UrlGroup::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')->label('名称')->columnSpan(5),
                Forms\Components\Radio::make('status')->options([
                    'enabled' => '启用',
                    'disabled' => '禁用',
                ])
                ->default('enabled')
                ->label('状态')
                ->inline()
                ->inlineLabel(false)
                ->columnSpan(span: 7),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->label('名称'),
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
            'index' => Pages\ListUrlGroups::route('/'),
            'create' => Pages\CreateUrlGroup::route('/create'),
            'edit' => Pages\EditUrlGroup::route('/{record}/edit'),
        ];
    }
}
