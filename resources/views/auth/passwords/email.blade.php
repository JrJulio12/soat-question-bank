@extends('layouts.app')

@section('title', 'Reset Password')

@section('content')
<div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        <div>
            <h2 class="mt-6 text-center text-3xl font-extrabold text-[#1b1b18] dark:text-[#EDEDEC]">
                Reset your password
            </h2>
            <p class="mt-2 text-center text-sm text-[#706f6c] dark:text-[#A1A09A]">
                Enter your email address and we'll send you a link to reset your password.
            </p>
        </div>
        <form class="mt-8 space-y-6" method="POST" action="{{ route('password.email') }}">
            @csrf

            @if (session('status'))
                <div class="rounded-md bg-green-50 dark:bg-green-900 p-4">
                    <p class="text-sm text-green-800 dark:text-green-200">
                        {{ session('status') }}
                    </p>
                </div>
            @endif

            <div>
                <label for="email" class="block text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC]">Email address</label>
                <input id="email" name="email" type="email" autocomplete="email" required 
                       class="mt-1 appearance-none relative block w-full px-3 py-2 border border-[#e3e3e0] dark:border-[#3E3E3A] placeholder-gray-500 text-[#1b1b18] dark:text-[#EDEDEC] bg-white dark:bg-[#161615] rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('email') border-red-500 @enderror" 
                       placeholder="Email address" value="{{ old('email') }}">
                @error('email')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <button type="submit" 
                        class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Send Password Reset Link
                </button>
            </div>

            <div class="text-center">
                <a href="{{ route('login') }}" class="font-medium text-indigo-600 hover:text-indigo-500 dark:text-indigo-400 dark:hover:text-indigo-300">
                    Back to login
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
