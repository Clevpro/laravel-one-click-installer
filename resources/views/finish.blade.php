@extends('installer::layout')

@section('content')
<div class="installer-fade-in text-center" data-current-step="{{ $currentStep }}" data-total-steps="{{ $totalSteps }}">
    <div class="mb-8">
        <!-- Success Animation -->
        <div class="installer-success-bounce mx-auto w-24 h-24 bg-gradient-to-r from-green-400 to-emerald-500 rounded-full flex items-center justify-center mb-6 animate-pulse">
            <svg class="w-12 h-12 text-white" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
            </svg>
        </div>

        <h1 class="text-3xl font-bold text-gray-900 mb-4">
            🎉 Installation Complete!
        </h1>
        
        <p class="text-lg text-gray-600 mb-8 max-w-lg mx-auto">
            Congratulations! {{ $appName }} has been successfully installed and configured. 
            Your application is now ready to use.
        </p>
    </div>

    <!-- Installation Summary -->
    <div class="bg-gradient-to-br from-green-50 to-emerald-100 rounded-lg p-6 mb-8">
        <h3 class="text-lg font-semibold text-green-900 mb-4">What we've accomplished:</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="flex items-center space-x-3">
                <div class="flex-shrink-0">
                    <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                    </svg>
                </div>
                <span class="text-sm text-green-800">Environment configured</span>
            </div>
            <div class="flex items-center space-x-3">
                <div class="flex-shrink-0">
                    <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                    </svg>
                </div>
                <span class="text-sm text-green-800">Database setup complete</span>
            </div>
            <div class="flex items-center space-x-3">
                <div class="flex-shrink-0">
                    <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                    </svg>
                </div>
                <span class="text-sm text-green-800">Tables created & seeded</span>
            </div>
            <div class="flex items-center space-x-3">
                <div class="flex-shrink-0">
                    <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                    </svg>
                </div>
                <span class="text-sm text-green-800">Admin account created</span>
            </div>
            <div class="flex items-center space-x-3">
                <div class="flex-shrink-0">
                    <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                    </svg>
                </div>
                <span class="text-sm text-green-800">Application secured</span>
            </div>
            <div class="flex items-center space-x-3">
                <div class="flex-shrink-0">
                    <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                    </svg>
                </div>
                <span class="text-sm text-green-800">Cache & storage optimized</span>
            </div>
        </div>
    </div>

    <!-- Next Steps -->
    <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mb-8">
        <h3 class="text-lg font-semibold text-blue-900 mb-4">Next Steps:</h3>
        <div class="space-y-3 text-left">
            <div class="flex items-start space-x-3">
                <div class="flex-shrink-0 mt-1">
                    <span class="inline-flex items-center justify-center w-6 h-6 bg-blue-500 text-white text-xs font-medium rounded-full">1</span>
                </div>
                <div>
                    <h4 class="text-sm font-medium text-blue-900">Access your application</h4>
                    <p class="text-sm text-blue-700">Click the button below to start using your application</p>
                </div>
            </div>
            <div class="flex items-start space-x-3">
                <div class="flex-shrink-0 mt-1">
                    <span class="inline-flex items-center justify-center w-6 h-6 bg-blue-500 text-white text-xs font-medium rounded-full">2</span>
                </div>
                <div>
                    <h4 class="text-sm font-medium text-blue-900">Customize your settings</h4>
                    <p class="text-sm text-blue-700">Configure your application settings, themes, and preferences</p>
                </div>
            </div>
            <div class="flex items-start space-x-3">
                <div class="flex-shrink-0 mt-1">
                    <span class="inline-flex items-center justify-center w-6 h-6 bg-blue-500 text-white text-xs font-medium rounded-full">3</span>
                </div>
                <div>
                    <h4 class="text-sm font-medium text-blue-900">Secure your installation</h4>
                    <p class="text-sm text-blue-700">Remove or secure the installer files for production use</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Security Notice -->
    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-8">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                </svg>
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-medium text-yellow-800">Security Recommendation</h3>
                <div class="mt-2 text-sm text-yellow-700">
                    <p>For security reasons, consider removing the installer files from your production server or restricting access to them.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="space-y-4">
        <a href="{{ $redirectUrl }}" 
           class="installer-button installer-focus-visible inline-flex items-center px-8 py-3 border border-transparent text-base font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors duration-200">
            <svg class="mr-2 w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z" clip-rule="evenodd"></path>
            </svg>
            Go to Application
        </a>

        <div class="text-center">
            <a href="{{ url('/') }}" 
               class="inline-flex items-center px-6 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                <svg class="mr-2 w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10.293 15.707a1 1 0 010-1.414L14.586 10l-4.293-4.293a1 1 0 111.414-1.414l5 5a1 1 0 010 1.414l-5 5a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                    <path fill-rule="evenodd" d="M3 10a1 1 0 011-1h10a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"></path>
                </svg>
                Visit Website
            </a>
        </div>
    </div>

    <!-- Support Information -->
    <div class="mt-12 pt-8 border-t border-gray-200">
        <p class="text-sm text-gray-500 mb-4">
            Need help getting started? Check out the documentation or get support.
        </p>
        <div class="flex justify-center space-x-6 text-sm">
            <a href="https://laravel.com/docs" 
               class="text-blue-600 hover:text-blue-500" 
               target="_blank" 
               rel="noopener noreferrer">
                Laravel Documentation
            </a>
            <span class="text-gray-300">•</span>
            <a href="https://github.com/clevara/laravel-one-click-installer" 
               class="text-blue-600 hover:text-blue-500" 
               target="_blank" 
               rel="noopener noreferrer">
                Package Repository
            </a>
            <span class="text-gray-300">•</span>
            <a href="https://github.com/clevara/laravel-one-click-installer/issues" 
               class="text-blue-600 hover:text-blue-500" 
               target="_blank" 
               rel="noopener noreferrer">
                Get Support
            </a>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add some confetti or celebration effect if desired
    // You can integrate a library like canvas-confetti for a nice effect
    
    // Auto-redirect after 30 seconds (optional)
    setTimeout(function() {
        const redirectButton = document.querySelector('a[href="{{ $redirectUrl }}"]');
        if (redirectButton && confirm('Would you like to go to your application now?')) {
            window.location.href = '{{ $redirectUrl }}';
        }
    }, 30000);
});
</script>
@endsection