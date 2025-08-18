@extends('installer::layout')

@section('content')
<div class="text-center installer-fade-in" data-current-step="{{ $currentStep }}" data-total-steps="{{ $totalSteps }}">
    <div class="mb-8">
        <div class="mx-auto w-20 h-20 bg-gradient-to-r from-[{{ config('one-click-installer.ui.theme_color', '#10b981') }}] to-[{{ config('one-click-installer.ui.theme_color', '#10b981') }}] rounded-full flex items-center justify-center mb-6">
            <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
            </svg>
        </div>
        <h1 class="text-3xl font-bold text-gray-900 mb-4">
            Welcome to {{ $appName }}
        </h1>
        <p class="text-lg text-gray-600 mb-8 max-w-lg mx-auto">
            This installation wizard will guide you through the setup process. 
            It should take only a few minutes to get your application up and running.
        </p>
    </div>

    <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mb-8">
        <h3 class="text-lg font-semibold text-blue-900 mb-3">What we'll set up:</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-left">
            <div class="flex items-center space-x-3">
                <div class="flex-shrink-0">
                    <svg class="w-5 h-5 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                    </svg>
                </div>
                <span class="text-sm text-blue-800">Environment configuration</span>
            </div>
            <div class="flex items-center space-x-3">
                <div class="flex-shrink-0">
                    <svg class="w-5 h-5 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                    </svg>
                </div>
                <span class="text-sm text-blue-800">Database setup</span>
            </div>
            <div class="flex items-center space-x-3">
                <div class="flex-shrink-0">
                    <svg class="w-5 h-5 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                    </svg>
                </div>
                <span class="text-sm text-blue-800">Database migrations</span>
            </div>
            <div class="flex items-center space-x-3">
                <div class="flex-shrink-0">
                    <svg class="w-5 h-5 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                    </svg>
                </div>
                <span class="text-sm text-blue-800">Admin account creation</span>
            </div>
        </div>
    </div>

    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-8">
        <div class="flex items-start">
            <div class="flex-shrink-0">
                <svg class="w-5 h-5 text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                </svg>
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-medium text-yellow-800">Before you begin</h3>
                <div class="mt-2 text-sm text-yellow-700">
                    <p>Make sure you have your database credentials ready. This installer will create and configure your database automatically.</p>
                </div>
            </div>
        </div>
    </div>

    <div class="flex justify-center">
        <a href="{{ route('installer.environment') }}" 
           class="inline-flex items-center px-8 py-3 border border-transparent text-base font-medium rounded-md text-white bg-[{{ config('one-click-installer.ui.theme_color', '#10b981') }}] hover:bg-black focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
            Get Started
            <svg class="ml-2 -mr-1 w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd"></path>
            </svg>
        </a>
    </div>
</div>
@endsection