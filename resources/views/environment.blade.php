@extends('installer::layout')

@section('content')
<div class="installer-fade-in" data-current-step="{{ $currentStep }}" data-total-steps="{{ $totalSteps }}">
    <div class="text-center mb-8">
        <h1 class="text-2xl font-bold text-gray-900 mb-2">Environment Setup</h1>
        <p class="text-gray-600">Configure your application settings and database connection</p>
    </div>

    @if ($errors->any())
        <div class="bg-red-50 border border-red-200 rounded-md p-4 mb-6">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-red-800">Please fix the following errors:</h3>
                    <div class="mt-2 text-sm text-red-700">
                        <ul class="list-disc list-inside space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <form method="POST" action="{{ route('installer.environment.store') }}" class="space-y-6">
        @csrf

        <!-- Application Settings -->
        <div class="bg-gray-50 p-6 rounded-lg">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Application Settings</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="app_name" class="block text-sm font-medium text-gray-700 mb-2">
                        Application Name
                    </label>
                    <input type="text" 
                           name="app_name" 
                           id="app_name" 
                           value="{{ old('app_name', $envValues['app_name']) }}"
                           class="installer-input mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-[{{ config("one-click-installer.ui.theme_color", "#10b981") }}] focus:border-[{{ config("one-click-installer.ui.theme_color", "#10b981") }}] sm:text-sm"
                           placeholder="My Laravel App"
                           required>
                    @error('app_name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="app_url" class="block text-sm font-medium text-gray-700 mb-2">
                        Application URL
                    </label>
                    <input type="url" 
                           name="app_url" 
                           id="app_url" 
                           value="{{ old('app_url', $envValues['app_url']) }}"
                           class="installer-input mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-[{{ config("one-click-installer.ui.theme_color", "#10b981") }}] focus:border-[{{ config("one-click-installer.ui.theme_color", "#10b981") }}] sm:text-sm"
                           placeholder="https://yourapp.com"
                           required>
                    @error('app_url')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Database Settings -->
        <div class="bg-gray-50 p-6 rounded-lg">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Database Configuration</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="db_host" class="block text-sm font-medium text-gray-700 mb-2">
                        Database Host
                    </label>
                    <input type="text" 
                           name="db_host" 
                           id="db_host" 
                           value="{{ old('db_host', $envValues['db_host']) }}"
                           class="installer-input mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-[{{ config("one-click-installer.ui.theme_color", "#10b981") }}] focus:border-[{{ config("one-click-installer.ui.theme_color", "#10b981") }}] sm:text-sm"
                           placeholder="localhost"
                           required>
                    @error('db_host')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="db_port" class="block text-sm font-medium text-gray-700 mb-2">
                        Database Port
                    </label>
                    <input type="number" 
                           name="db_port" 
                           id="db_port" 
                           value="{{ old('db_port', $envValues['db_port']) }}"
                           class="installer-input mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-[{{ config("one-click-installer.ui.theme_color", "#10b981") }}] focus:border-[{{ config("one-click-installer.ui.theme_color", "#10b981") }}] sm:text-sm"
                           placeholder="3306"
                           min="1"
                           max="65535"
                           required>
                    @error('db_port')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="db_database" class="block text-sm font-medium text-gray-700 mb-2">
                        Database Name
                    </label>
                    <input type="text" 
                           name="db_database" 
                           id="db_database" 
                           value="{{ old('db_database', $envValues['db_database']) }}"
                           class="installer-input mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-[{{ config("one-click-installer.ui.theme_color", "#10b981") }}] focus:border-[{{ config("one-click-installer.ui.theme_color", "#10b981") }}] sm:text-sm"
                           placeholder="my_database"
                           required>
                    @error('db_database')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="db_username" class="block text-sm font-medium text-gray-700 mb-2">
                        Database Username
                    </label>
                    <input type="text" 
                           name="db_username" 
                           id="db_username" 
                           value="{{ old('db_username', $envValues['db_username']) }}"
                           class="installer-input mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-[{{ config("one-click-installer.ui.theme_color", "#10b981") }}] focus:border-[{{ config("one-click-installer.ui.theme_color", "#10b981") }}] sm:text-sm"
                           placeholder="root"
                           required>
                    @error('db_username')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="md:col-span-2">
                    <label for="db_password" class="block text-sm font-medium text-gray-700 mb-2">
                        Database Password
                    </label>
                    <input type="password" 
                           name="db_password" 
                           id="db_password" 
                           value="{{ old('db_password', $envValues['db_password']) }}"
                           class="installer-input mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-[{{ config("one-click-installer.ui.theme_color", "#10b981") }}] focus:border-[{{ config("one-click-installer.ui.theme_color", "#10b981") }}] sm:text-sm"
                           placeholder="Password (leave empty if none)">
                    @error('db_password')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-blue-700">
                        <strong>Note:</strong> We will test the database connection before proceeding. 
                        Make sure your database server is running and the database exists.
                    </p>
                </div>
            </div>
        </div>

        <!-- Navigation -->
        <div class="flex justify-between pt-6">
            <a href="{{ route('installer.welcome') }}" 
               class="inline-flex items-center px-6 py-3 border border-gray-300 text-base font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[{{ config("one-click-installer.ui.theme_color", "#10b981") }}]">
                <svg class="mr-2 w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd"></path>
                </svg>
                Back
            </a>

            <button type="submit" 
                    class="installer-button installer-focus-visible inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-[{{ config("one-click-installer.ui.theme_color", "#10b981") }}] hover:bg-black focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[{{ config("one-click-installer.ui.theme_color", "#10b981") }}]">
                Test & Continue
                <svg class="ml-2 w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                </svg>
            </button>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-fill localhost if empty
    const dbHost = document.getElementById('db_host');
    if (!dbHost.value) {
        dbHost.value = 'localhost';
    }
    
    // Auto-fill port if empty
    const dbPort = document.getElementById('db_port');
    if (!dbPort.value) {
        dbPort.value = '3306';
    }
});
</script>
@endsection