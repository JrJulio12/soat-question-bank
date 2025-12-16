@extends('layouts.app')

@section('title', 'Verify Email')

@section('content')
<div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        <div>
            <h2 class="mt-6 text-center text-3xl font-extrabold text-[#1b1b18] dark:text-[#EDEDEC]">
                Verify Your Email Address
            </h2>
        </div>

        <div class="text-center">
            @if (session('resent'))
                <div class="rounded-md bg-green-50 dark:bg-green-900 p-4 mb-4">
                    <p class="text-sm text-green-800 dark:text-green-200">
                        A fresh verification link has been sent to your email address.
                    </p>
                </div>
            @endif

            <p class="text-sm text-[#706f6c] dark:text-[#A1A09A] mb-4">
                Before proceeding, please check your email for a verification link.
            </p>
            <p class="text-sm text-[#706f6c] dark:text-[#A1A09A] mb-4">
                If you did not receive the email,
            </p>

            <form method="POST" action="{{ route('verification.resend') }}">
                @csrf
                <button type="submit" 
                        class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Click here to request another
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
