<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UrlResource\Pages;
use App\Filament\Resources\UrlResource\RelationManagers;
use App\Models\Url;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Forms;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UrlResource extends Resource
{
    protected static ?string $label = '网址管理';
    protected static ?string $model = Url::class;
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('url_groups')
                    ->relationship(titleAttribute: 'name')
                    ->multiple()
                    ->label('网址分组')
                    ->required()
                    ->columnSpan(10)
                    ->searchable()
                    ->preload(),
                Forms\Components\TextInput::make('title')->label('名称')->required()->columnSpan(10),
                Forms\Components\TextInput::make('url')->url()->label('地址')->required()->columnSpan(10),
                Forms\Components\FileUpload::make('icon')
                    ->disk('public')
                    ->directory(config('app.env') . '/fuli/cover/' . now()->format('Y/m/d'))
                    ->image()
                    ->imageEditor()
                    ->imageEditorAspectRatios([
                        '1:1',
                    ])
                    ->label('图标(150*150)')
                    ->required()
                    ->columnSpan(10),
                Forms\Components\FileUpload::make('cover')
                    ->disk('public')
                    ->directory(config('app.env') . '/fuli/cover/' . now()->format('Y/m/d'))
                    ->image()
                    ->imageEditor()
                    ->imageEditorAspectRatios([
                        '16:9',
                        '4:3',
                        '1:1',
                    ])
                    ->label('封面(600*400)')
                    ->required()
                    ->columnSpan(10),
                Forms\Components\Toggle::make('is_recommended')
                    ->label('推荐')
                    ->default(true)
                    ->inline(false)
                    ->columnSpan(12),
                Forms\Components\Radio::make('status')
                    ->required()
                    ->options([
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
                Tables\Columns\TextColumn::make('title')->label('名称'),
                Tables\Columns\TextColumn::make('created_at')->label('创建时间')
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
            'index' => Pages\ListUrls::route('/'),
            'create' => Pages\CreateUrl::route('/create'),
            'edit' => Pages\EditUrl::route('/{record}/edit'),
        ];
    }
}
