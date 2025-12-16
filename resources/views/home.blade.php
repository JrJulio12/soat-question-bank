@extends('layouts.app')

@section('title', 'Home')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-[#161615] overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-[#1b1b18] dark:text-[#EDEDEC]">
                <h1 class="text-2xl font-bold mb-4">Welcome, {{ Auth::user()->name }}!</h1>
                <p class="mb-4">You are successfully logged in.</p>
                
                @if (session('verified'))
                    <div class="rounded-md bg-green-50 dark:bg-green-900 p-4 mb-4">
                        <p class="text-sm text-green-800 dark:text-green-200">
                            Your email has been verified!
                        </p>
                    </div>
                @endif

                @if (!Auth::user()->hasVerifiedEmail())
                    <div class="rounded-md bg-yellow-50 dark:bg-yellow-900 p-4 mb-4">
                        <p class="text-sm text-yellow-800 dark:text-yellow-200">
                            Please verify your email address. 
                            <a href="{{ route('verification.notice') }}" class="font-medium underline">
                                Click here to resend verification email.
                            </a>
                        </p>
                    </div>
                @endif

                <div class="mt-6">
                    <h2 class="text-xl font-semibold mb-2">User Information:</h2>
                    <ul class="list-disc list-inside space-y-1">
                        <li><strong>Name:</strong> {{ Auth::user()->name }}</li>
                        <li><strong>Email:</strong> {{ Auth::user()->email }}</li>
                        <li><strong>Email Verified:</strong> {{ Auth::user()->hasVerifiedEmail() ? 'Yes' : 'No' }}</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
