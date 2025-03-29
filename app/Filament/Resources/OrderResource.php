<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Filament\Resources\OrderResource\RelationManagers;
use App\Models\Order;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Infolists;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Infolist;

class OrderResource extends Resource
{
    protected static ?string $label = "订单管理";
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

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
            // ->query(function (): Builder {
            //     $query = self::getEloquentQuery();
            //     $user = auth()->user();
            //     if ($user->role != "admin") {
            //         $query = $query->where('agent_id', $user->id);
            //     }
            //     return $query;
            // })
            ->columns([
                Tables\Columns\TextColumn::make('no')->label('订单编号'),
                Tables\Columns\TextColumn::make('merchant_no')->label('商户单号'),
                Tables\Columns\TextColumn::make('amount')->label('订单金额'),
                Tables\Columns\TextColumn::make('pay_amount')->label('支付金额'),
                Tables\Columns\TextColumn::make('status')->label('状态')->formatStateUsing(function (string $state) {
                    $text = "";
                    switch ($state) {
                        case 'pending':
                            $text = '未完成';
                            break;
                        case 'completed':
                            $text = '已完成';
                            break;
                        case 'expired':
                            $text = '已过期';
                            break;
                        default:
                            break;
                    }
                    return $text;
                }),
                // 
                Tables\Columns\TextColumn::make('pay_status')->label('支付状态')->formatStateUsing(function (string $state) {
                    return $state == 'unpaid' ? '未支付' : '已支付';
                }),
                Tables\Columns\TextColumn::make('pay_at')->label('支付时间'),
                // 
                Tables\Columns\TextColumn::make('exchange_status')->label('兑换状态')->formatStateUsing(function (string $state) {
                    $text = "";
                    switch ($state) {
                        case 'pending':
                            $text = '未完成';
                            break;
                        case 'completed':
                            $text = '已完成';
                            break;
                        case 'expired':
                            $text = '已过期';
                            break;
                        default:
                            break;
                    }
                    return $text;
                }),
                // 兑换时间
                Tables\Columns\TextColumn::make('exchange_at')->label('兑换时间'),
                Tables\Columns\TextColumn::make('code')->label('兑换码'),
                Tables\Columns\TextColumn::make('package.name')->label('套餐'),
                Tables\Columns\TextColumn::make('member.uuid')->label('用户'),
                Tables\Columns\TextColumn::make('agent.name')->label('代理'),
                Tables\Columns\TextColumn::make('created_at')->label('创建时间'),
            ])
            ->filters([
                //
            ])
            ->defaultSort('created_at', 'desc')
            ->actions([
                //Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make()
            ])
            ->bulkActions([
                // Tables\Actions\BulkActionGroup::make([
                //     Tables\Actions\DeleteBulkAction::make(),
                // ]),
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
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }
    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make('基本信息')
                    ->schema([
                        Infolists\Components\TextEntry::make('no')->label('订单号')->columnSpan(6),
                        Infolists\Components\TextEntry::make('remark')->label('备注')->columnSpan(6),
                        Infolists\Components\TextEntry::make('agent.name')->label('代理')->columnSpan(6),
                        Infolists\Components\TextEntry::make('member.uuid')->label('用户id')->columnSpan(6),
                        Infolists\Components\TextEntry::make('package.name')->label('套餐')->columnSpan(6),
                        Infolists\Components\TextEntry::make('amount')->label('金额')->columnSpan(6),
                        Infolists\Components\TextEntry::make('created_at')->label('创建时间')->columnSpan(6),
                        Infolists\Components\TextEntry::make('status')->formatStateUsing(function ($state) {
                            $text = "";
                            switch ($state) {
                                case "pending":
                                    $text = "未完成";
                                    break;
                                case "completed":
                                    $text = "已完成";
                                case "expired":
                                    $text = "已过期";
                                    break;
                                default:
                                    break;
                            }
                            return $text;
                        })->label('状态')->columnSpan(6),

                    ])->columns(12)->columnSpan(12),

                Section::make('支付信息')
                    ->schema([

                        Infolists\Components\TextEntry::make('merchant_no')->label('商户号')->columnSpan(6),
                        Infolists\Components\TextEntry::make('pay_amount')->label('支付金额')->columnSpanFull(),
                        Infolists\Components\TextEntry::make('pay_status')->formatStateUsing(function ($state) {
                            $text = "";
                            switch ($state) {
                                case "unpaid":
                                    $text = "未支付";
                                    break;
                                case "paid":
                                    $text = "已支付";
                                default:
                                    break;
                            }
                            return $text;
                        })->label('支付状态')->columnSpanFull(),
                        Infolists\Components\TextEntry::make('pay_at')->label('支付时间')->columnSpanFull(),

                    ])->columns(12)->columnSpan(12),
                Section::make('支付信息')
                    ->schema([
                        Infolists\Components\TextEntry::make('code')->label('兑换码')->columnSpanFull(),
                        Infolists\Components\TextEntry::make('exchange_status')->formatStateUsing(function ($state) {
                            $text = "";
                            switch ($state) {
                                case "pending":
                                    $text = "未完成";
                                    break;
                                case "completed":
                                    $text = "已完成";
                                case "expired":
                                    $text = "已过期";
                                    break;
                                default:
                                    break;
                            }
                            return $text;
                        })->label('兑换状态')->columnSpanFull(),
                        Infolists\Components\TextEntry::make('exchange_at')->label('兑换时间')->columnSpanFull(),

                    ])->columns(12)->columnSpan(12),






            ]);
    }
}
