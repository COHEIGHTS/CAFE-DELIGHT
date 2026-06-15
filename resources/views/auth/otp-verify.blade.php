@extends('layouts.app')

@section('title', 'Verify OTP — Cafe Delight')

@section('content')
<div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        <div>
            <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
                Verify Your Email
            </h2>
            <p class="mt-2 text-center text-sm text-gray-600">
                We've sent a 6-digit OTP to your email address. Please enter it below to continue.
            </p>
        </div>

        @if(session('success'))
        <div class="rounded-md bg-green-50 p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                </div>
            </div>
        </div>
        @endif

        @if($errors->any())
        <div class="rounded-md bg-red-50 p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-red-800">{{ $errors->first() }}</p>
                </div>
            </div>
        </div>
        @endif

        <form class="mt-8 space-y-6" method="POST" action="{{ route('otp.verify.submit') }}">
            @csrf
            <div class="rounded-md shadow-sm -space-y-px">
                <div>
                    <label for="otp" class="sr-only">OTP Code</label>
                    <input id="otp" name="otp" type="text" required
                           class="appearance-none rounded-lg relative block w-full px-3 py-3 border border-gray-300 placeholder-gray-500 text-gray-900 text-center text-2xl tracking-widest focus:outline-none focus:ring-brand-500 focus:border-brand-500 focus:z-10 sm:text-sm"
                           placeholder="000000" maxlength="6" pattern="[0-9]{6}" autocomplete="one-time-code">
                </div>
            </div>

            <div>
                <button type="submit"
                        class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-medium rounded-lg text-white bg-brand-600 hover:bg-brand-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-500">
                    Verify OTP
                </button>
            </div>
        </form>

        <div class="text-center mt-4">
            <p class="text-sm text-gray-600">
                Didn't receive the code?
            </p>
            <form method="POST" action="{{ route('otp.resend') }}" class="mt-2">
                @csrf
                <button type="submit" class="font-medium text-brand-600 hover:text-brand-500">
                    Resend OTP
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
