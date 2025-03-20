<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AgentResource\Pages;
use App\Filament\Resources\AgentResource\RelationManagers;
use App\Models\Agent;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AgentResource extends Resource
{
    protected static ?string $label = "代理管理";
    protected static ?string $model = Agent::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()
                    ->schema([
                        Forms\Components\TextInput::make('name')->label('名称')
                            ->required()
                            ->maxLength(255)->columnSpan(10)
                            ->unique(ignoreRecord: true),
                        Forms\Components\TextInput::make('email')->label('邮箱')
                            ->required()
                            ->maxLength(255)->columnSpan(10)
                            ->unique(ignoreRecord: true),
                        Forms\Components\TextInput::make('password')->label('密码')
                            ->password()
                            ->required(function (string $operation) {
                                return $operation == 'create';
                            })
                            ->revealable()
                            ->dehydrated(fn($state) => filled($state))
                            ->dehydrateStateUsing(
                                fn($state) =>
                                filled($state) ? bcrypt($state) : null
                            )
                            ->afterStateHydrated(function (Forms\Components\TextInput $component, $state) {
                                $component->state('');
                            })
                            ->maxLength(255)
                            ->columnSpan(10)

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
            'index' => Pages\ListAgents::route('/'),
            'create' => Pages\CreateAgent::route('/create'),
            'edit' => Pages\EditAgent::route('/{record}/edit'),
        ];
    }
}
