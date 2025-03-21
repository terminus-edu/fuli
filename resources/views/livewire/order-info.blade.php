<div class="space-y-4 md:space-y-6">
    @if(!empty($uuid))
            <div class="mt-4">
                <a href="{{ route('orders.index', ['uuid' => $uuid]) }}" class="inline-block px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 transition-colors">
                    返回列表
                </a>
            </div>
    @endif
    <div class="bg-white rounded-lg shadow p-4 md:p-6">


        
        <h2 class="text-lg md:text-xl font-semibold mb-3 md:mb-4">订单基本信息</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 md:gap-4">
            <div>
                <p class="text-sm md:text-base text-gray-600">订单编号</p>
                <p class="text-sm md:text-base font-medium">{{ $order->no }}</p>
            </div>
            <div>
                <p class="text-sm md:text-base text-gray-600">套餐名称</p>
                <p class="text-sm md:text-base font-medium">{{ $order->package->name }}</p>
            </div>
            <div>
                <p class="text-sm md:text-base text-gray-600">订单状态</p>
                <p class="text-sm md:text-base font-medium">
                    @switch($order->status)
                        @case('pending')
                            <span class="text-yellow-600">待处理</span>
                            @break
                        @case('completed')
                            <span class="text-green-600">已完成</span>
                            @break
                        @case('expired')
                            <span class="text-red-600">已过期</span>
                            @break
                    @endswitch
                </p>
            </div>
            <div>
                <p class="text-sm md:text-base text-gray-600">创建时间</p>
                <p class="text-sm md:text-base font-medium">{{ $order->created_at }}</p>
            </div>
            <div>
                <p class="text-sm md:text-base text-gray-600">订单金额</p>
                <p class="text-sm md:text-base font-medium">¥{{ number_format($order->amount, 2) }}</p>
            </div>
            <div>
                <p class="text-sm md:text-base text-gray-600">支付金额</p>
                <p class="text-sm md:text-base font-medium">¥{{ number_format($order->pay_amount, 2) }}</p>
            </div>
            <div>
                <p class="text-sm md:text-base text-gray-600">支付状态</p>
                <p class="text-sm md:text-base font-medium">
                    @if($order->pay_status === 'paid')
                        <span class="text-green-600">已支付</span>
                    @else
                        <span class="text-red-600">未支付</span>
                    @endif
                </p>
            </div>
            @if($order->pay_status === 'paid')
                <div>
                    <p class="text-sm md:text-base text-gray-600">支付时间</p>
                    <p class="text-sm md:text-base font-medium">{{ $order->pay_at ?? '暂无' }}</p>
                </div>
            @endif
            @if(!empty($order->member_id) || $order->pay_status == "paid")
                <div>
                    <p class="text-sm md:text-base text-gray-600">兑换码</p>
                    <p class="text-sm md:text-base font-medium">
                        <span>{{ $order->code }}</span>
                        <button type="button" x-data="{ copied: false }" x-on:click="navigator.clipboard.writeText('{{ $order->code }}').then(() => { copied = true; setTimeout(() => copied = false, 2000) })" class="ml-2 text-blue-600 hover:text-blue-800 focus:outline-none">
                            <span x-text="copied ? '已复制!' : '复制'"></span>
                        </button>
                    </p>
                </div>
            @endif
            @if($order->pay_status === 'unpaid')
                <div>
                    <a href="{{ route('orders.pay', ['no' => $order->no,'uuid'=>request()->input('uuid')]) }}" class="inline-block px-4 py-2 bg-green-600 text-center text-white w-full text-sm rounded-md hover:bg-blue-700">立即支付</a>
                </div>
            @endif
        </div>
    </div>
</div>