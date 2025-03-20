<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PackageResource\Pages;
use App\Filament\Resources\PackageResource\RelationManagers;
use App\Models\Package;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PackageResource extends Resource
{
    protected static ?string $label = "套餐管理";
    protected static ?string $model = Package::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()
                    ->schema([
                        Forms\Components\TextInput::make('name')->label('名称')->required()->columnSpan(10),
                        Forms\Components\TextInput::make('price')->numeric()->step(0.01)->label('价格')->required()->columnSpan(10),
                        Forms\Components\TextInput::make('duration')->integer()->step(1)->label('时长(天)')->required()->columnSpan(10),
                        Forms\Components\Select::make('subscribes')
                            ->relationship(
                                name: 'subscribes',
                                titleAttribute: 'name',
                                modifyQueryUsing: fn (Builder $query) => $query->where('is_free',false),
                            )
                            ->multiple()
                            ->label('订阅')
                            ->required()
                            ->columnSpan(10)
                            ->searchable()
                            ->preload(),
                        Forms\Components\Radio::make('status')->required()->options([
                            'enabled' => '启用',
                            'disabled' => '禁用',
                        ])
                            ->default('enabled')
                            ->label('状态')
                            ->inline()
                            ->inlineLabel(false)
                            ->columnSpan(span: 7),

                    ])
                    ->columns(12)
                    ->columnSpan(8)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->label('名称'),
                Tables\Columns\TextColumn::make('created_at')->label('创建时间'),
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
            'index' => Pages\ListPackages::route('/'),
            'create' => Pages\CreatePackage::route('/create'),
            'edit' => Pages\EditPackage::route('/{record}/edit'),
        ];
    }
}
