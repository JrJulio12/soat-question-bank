@extends('layouts.app')

@section('title', 'Register')

@section('content')
<div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        <div>
            <h2 class="mt-6 text-center text-3xl font-extrabold text-[#1b1b18] dark:text-[#EDEDEC]">
                Create your account
            </h2>
        </div>
        <form class="mt-8 space-y-6" method="POST" action="{{ route('register') }}">
            @csrf

            <div class="rounded-md shadow-sm space-y-4">
                <div>
                    <label for="name" class="block text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC]">Name</label>
                    <input id="name" name="name" type="text" autocomplete="name" required 
                           class="mt-1 appearance-none relative block w-full px-3 py-2 border border-[#e3e3e0] dark:border-[#3E3E3A] placeholder-gray-500 text-[#1b1b18] dark:text-[#EDEDEC] bg-white dark:bg-[#161615] rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('name') border-red-500 @enderror" 
                           placeholder="Your name" value="{{ old('name') }}">
                    @error('name')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

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
                    <label for="password" class="block text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC]">Password</label>
                    <input id="password" name="password" type="password" autocomplete="new-password" required 
                           class="mt-1 appearance-none relative block w-full px-3 py-2 border border-[#e3e3e0] dark:border-[#3E3E3A] placeholder-gray-500 text-[#1b1b18] dark:text-[#EDEDEC] bg-white dark:bg-[#161615] rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('password') border-red-500 @enderror" 
                           placeholder="Password">
                    @error('password')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password-confirm" class="block text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC]">Confirm Password</label>
                    <input id="password-confirm" name="password_confirmation" type="password" autocomplete="new-password" required 
                           class="mt-1 appearance-none relative block w-full px-3 py-2 border border-[#e3e3e0] dark:border-[#3E3E3A] placeholder-gray-500 text-[#1b1b18] dark:text-[#EDEDEC] bg-white dark:bg-[#161615] rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" 
                           placeholder="Confirm password">
                </div>
            </div>

            <div>
                <button type="submit" 
                        class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Register
                </button>
            </div>

            <div class="text-center">
                <span class="text-sm text-[#706f6c] dark:text-[#A1A09A]">
                    Already have an account?
                    <a href="{{ route('login') }}" class="font-medium text-indigo-600 hover:text-indigo-500 dark:text-indigo-400 dark:hover:text-indigo-300">
                        Login
                    </a>
                </span>
            </div>
        </form>
    </div>
</div>
@endsection
