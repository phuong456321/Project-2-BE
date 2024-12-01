<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Premium Subscription</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            background-color: #061223;
            color: #fff;
            background-image: url('/images/profile/background.png');
            background-size: cover;
            background-position: center;
        }
    </style>
</head>

<body>
    <div class="min-h-screen flex flex-col items-center pt-12 pb-12 sm:pt-16 sm:pb-16">
        <div class="text-center mb-7">
            <a href="{{ route('home') }}" id="home">
                <img src="{{ asset('images/profile/logo-home.png') }}" alt="Logo" width="200" class="mx-auto">
            </a>
            <h1 class="text-3xl font-bold mb-6">Go Premium for Ad-Free Music and More!</h1>
            <p class="mb-4">Enjoy the benefits of Premium with a one-time payment! By signing up, you agree to our  <a
                href="#" class="underline">Terms of Service</a> and <a href="#"
                class="underline">Privacy Policy</a>. Renew manually when your plan expires.</p>
        </div>

        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Free Plan -->
                <div class="bg-gray-800 text-white p-6 rounded-lg border border-transparent hover:border-green-500 transition">
                    <h3 class="text-xl font-bold text-center">Free</h3>
                    <h2 class="text-2xl text-center">Free</h2>
                    <p>Enjoy music with ads.</p>
                
                    @if (!$activeProduct || $activeProduct->pivot->expired_at <= now())
                        <!-- Nếu không có gói nào đang hoạt động -->
                        <button class="btn btn-secondary mt-3 w-full py-2 bg-gray-600 cursor-not-allowed" disabled>Current Plan</button>
                    @else
                        <!-- Nếu đã mua gói, không cho phép chuyển đổi về Free -->
                        <button class="btn btn-secondary mt-3 w-full py-2 bg-gray-600 cursor-not-allowed" disabled>You already have a plan</button>
                    @endif
                </div>
                

                <!-- Premium Plans -->
                @foreach ($products as $product)
                    @if ($product->cycles !== 'free')
                        <div
                            class="bg-gray-800 text-white p-6 rounded-lg border border-transparent hover:border-blue-500 transition {{ $product->cycles === 'yearly' ? 'bg-blue-600 border-blue-700' : '' }}">
                            <h3 class="text-xl font-bold text-center">Premium - {{ ucfirst($product->name) }}</h3>
                            <h2
                                class="text-2xl text-center {{ $product->cycles === 'yearly' ? 'text-blue-500 font-bold text-white' : '' }}">
                                ${{ number_format($product->price, 2) }}
                                @if ($product->cycles === 'yearly')
                                    <span class="line-through text-gray-500 font-bold">$99.99</span>
                                @endif
                            </h2>
                            <div class="flex justify-center">
                                @if ($product->cycles === 'yearly')
                                    <h3 class="text-white text-sm bg-blue-700 py-1 px-2 rounded-full mt-2 font-bold">
                                        Recommended</h3>
                                @endif
                            </div>
                            <p class="mt-2">{{ $product->description }}</p>

                            <!-- Hiển thị nút Buy hoặc Current Plan -->
                            @if ($activeProduct && $activeProduct->id === $product->id)
                                <button class="mt-3 w-full py-2 bg-gray-600 cursor-not-allowed" disabled>Current
                                    Plan</button>
                                @if ($activeProduct->pivot->expired_at)
                                    <p class="mt-2">Expires on:
                                        {{ Carbon\Carbon::parse($activeProduct->pivot->expired_at)->format('d-m-Y') }}
                                    </p>
                                @endif
                            @elseif ($activeProduct && $activeProduct->cycles !== 'free')
                                <button class="mt-3 w-full py-2 bg-gray-600 cursor-not-allowed" disabled>You already
                                    have a Premium Plan</button>
                            @else
                                <a href="{{ route('checkout.show', ['product' => $product->id]) }}">
                                    <button
                                        class="mt-3 w-full py-2 bg-blue-500 text-white hover:bg-blue-600 rounded-md">Buy</button>
                                </a>
                            @endif
                        </div>
                    @endif
                @endforeach

            </div>
        </div>
    </div>

</body>

</html>
