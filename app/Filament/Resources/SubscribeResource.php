<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SubscribeResource\Pages;
use App\Filament\Resources\SubscribeResource\RelationManagers;
use App\Models\Subscribe;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SubscribeResource extends Resource
{
    protected static ?string $label = "订阅管理";
    protected static ?string $model = Subscribe::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()->schema([
                    Forms\Components\TextInput::make('name')->label('名称')
                        ->required()
                        ->maxLength(255)->columnSpan(10),
                    Forms\Components\Radio::make('status')->label('状态')
                        ->default('enabled')
                        ->options([
                            'enabled' => '启用',
                            'disabled' => '禁用',
                        ])
                        ->inline()
                        ->inlineLabel(false)
                        ->columnSpan(12),
                    Forms\Components\Toggle::make('is_free')->label('免费')
                        ->default(true)
                        ->inline(false)
                        ->columnSpan(12),
                    Forms\Components\Textarea::make('content')->label("内容")
                        ->rows(10)
                        ->cols(20)
                        ->columnSpan(10)
                ])->columns(12)->columnSpan(8)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->label('名称'),
                Tables\Columns\TextColumn::make('status')->label('状态')->formatStateUsing(function (string $state) {
                    return $state == 'enabled' ? '启用' : '禁用';
                }),
                Tables\Columns\TextColumn::make('is_free')->label('免费')->formatStateUsing(function (string $state) {
                    return $state ? '是' : '否';
                }),
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
            'index' => Pages\ListSubscribes::route('/'),
            'create' => Pages\CreateSubscribe::route('/create'),
            'edit' => Pages\EditSubscribe::route('/{record}/edit'),
        ];
    }
}
