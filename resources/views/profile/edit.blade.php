@extends('layouts.customer-layout')

@section('title', 'Profile — Cafe Delight')

@php $activeNav = 'Profile'; @endphp

@section('content')
<div class="space-y-6">
    <div data-aos="fade-up" class="rounded-3xl glass p-6 shadow-soft">
        <div class="mb-6">
            <h1 class="text-2xl font-extrabold">My Profile</h1>
            <p class="mt-1 text-sm text-ink/65 dark:text-orange-50/65">Manage your account information and preferences</p>
        </div>

        <div class="space-y-6">
            <div class="rounded-2xl glass-strong p-6">
                @include('profile.partials.update-profile-information-form')
            </div>

            <div class="rounded-2xl glass-strong p-6">
                @include('profile.partials.update-password-form')
            </div>

            <div class="rounded-2xl glass-strong p-6">
                @include('profile.partials.delete-user-form')
            </div>
        </div>
    </div>
</div>
@endsection
