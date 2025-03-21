<div class="w-full">
    <div class="bg-white shadow rounded-lg overflow-hidden">
        <div class="divide-y divide-gray-200">
            @foreach($orders as $order)
                <a href="{{ route('orders.info', ['no' => $order->no,'uuid'=>request()->input('uuid')]) }}" class="block p-3 space-y-2 hover:bg-gray-50">
                    <div class="flex flex-col gap-2">
                        <div class="flex justify-between items-center">
                            <span class="text-sm font-medium text-gray-900 text-truncate">订单号：{{ $order->no }}</span>
                            <span class="px-2 py-1 text-xs rounded-full
                                @if($order->pay_status === 'paid')
                                    bg-green-100 text-green-800
                                @else
                                    bg-yellow-100 text-yellow-800
                                @endif">
                                {{ $order->pay_status === 'paid' ? '已支付' : '未支付' }}
                            </span>
                        </div>
                        <div class="flex flex-col gap-1 text-sm text-gray-500">
                            <span>套餐：{{ $order->package->name }}</span>
                            <span>金额：¥{{ number_format($order->amount, 2) }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-xs text-gray-400">创建时间：{{ $order->created_at}}</span>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
        <div class="px-4 py-3 bg-gray-50 border-t border-gray-200">
            {{ $orders->links() }}
        </div>
    </div>
</div>