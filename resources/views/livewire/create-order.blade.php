<div>
    <form wire:submit.prevent="createOrder">
        <!-- 套餐选择 -->
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">
                选择套餐
            </label>
            <div class="space-y-2">
                @foreach ($packages as $package)
                    <label class="flex items-center">
                        <input type="radio" wire:model.live="packageId" value="{{ $package->id }}" class="mr-2">
                        {{ $package->name }} - ¥{{ $package->price }}/月
                    </label>
                @endforeach
            </div>
        </div>
        <!-- 支付方式 -->
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">
                支付方式
            </label>
            <div class="space-y-2">
                <label class="flex items-center">
                    <input type="radio" wire:model="paymentMethod" value="alipay" class="mr-2">
                    支付宝
                </label>
                <label class="flex items-center">
                    <input type="radio" wire:model="paymentMethod" value="wechat" class="mr-2">
                    微信支付
                </label>
            </div>
        </div>

        <!-- 金额显示 -->
        <div class="mb-6">
            <label class="block text-gray-700 text-sm font-bold mb-2">
                订单金额
            </label>
            <div class="text-2xl font-bold text-green-600">
                ¥{{ $totalAmount }}
            </div>
        </div>

        <!-- 提交按钮 -->
        <button type="submit" class="w-full bg-blue-500 text-white py-2 px-4 rounded-lg hover:bg-blue-600 transition-colors">
            立即支付
        </button>
    </form>
    @if (session('error'))
    <div class="alert alert-success text-red-600">
        {{ session('error') }}
    </div>
@endif
</div>
