@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-md mx-auto bg-white dark:bg-[#161615] rounded-lg shadow-md overflow-hidden">
        <div class="px-6 py-8">
            <h2 class="text-2xl font-bold text-center text-[#1b1b18] dark:text-[#EDEDEC] mb-6">{{ __('Reset Password') }}</h2>

            @if (session('status'))
                <div class="bg-green-100 dark:bg-green-900 border border-green-400 dark:border-green-700 text-green-700 dark:text-green-300 px-4 py-3 rounded mb-4" role="alert">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('password.email') }}">
                @csrf

                <div class="mb-6">
                    <label for="email" class="block text-sm font-medium text-[#706f6c] dark:text-[#A1A09A] mb-2">{{ __('Email') }}</label>
                    <input id="email" type="email" class="w-full px-3 py-2 border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-md bg-white dark:bg-[#161615] text-[#1b1b18] dark:text-[#EDEDEC] @error('email') border-red-500 @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                    @error('email')
                        <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-4">
                    <button type="submit" class="w-full px-4 py-2 bg-gradient-to-r from-[#0E73B9] to-[#55B7E3] text-white rounded-md hover:shadow-lg transition duration-300">
                        {{ __('Kirim Link Reset Password') }}
                    </button>
                </div>

                <div class="text-center">
                    <a href="{{ route('login') }}" class="text-sm text-[#0E73B9] dark:text-[#55B7E3] hover:underline">
                        {{ __('Kembali ke halaman login') }}
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection