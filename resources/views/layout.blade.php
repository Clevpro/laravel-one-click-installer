<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $appName }} - Installation Wizard</title>
    
    <!-- TailwindCSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '{{ config("one-click-installer.ui.theme_color", "#10b981") }}',
                    }
                }
            }
        }
    </script>

    <!-- Installer CSS Assets -->
    <link rel="stylesheet" href="{{ route('installer.assets.css', 'installer.css') }}">

    <!-- Custom Favicon -->
    @if(config('one-click-installer.ui.favicon_url'))
        <link rel="icon" href="{{ config('one-click-installer.ui.favicon_url') }}">
    @endif
    <style>
        .step-indicator {
            transition: all {{config('one-click-installer.ui.animation_duration', 300)}}

            ms ease-in-out;
        }
        
        .step-indicator.active {
            background-color: {{ config('one-click-installer.ui.theme_color', '#10b981') }};
            color: white;
        }

        .step-indicator.completed {
            background-color: #10b981;
            color: white;
        }

        .fade-in {
            animation: fadeIn {
                    {
                    config('one-click-installer.ui.animation_duration', 300)
                }
            }

            ms ease-in-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>

<body class="bg-gray-50 min-h-screen">
    <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-2xl w-full space-y-8">
            <!-- Header -->
            <div class="text-center">
                @if(config('one-click-installer.ui.logo_url'))
                <img src="{{ config('one-click-installer.ui.logo_url') }}" alt="{{ $appName }}"
                    class="mx-auto h-16 w-auto">
                @else
                <div class="mx-auto h-16 w-16 bg-[{{ config("one-click-installer.ui.theme_color", "#10b981") }}] rounded-lg flex items-center justify-center">
                    <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                </div>
                @endif
                <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
                    {{ $appName }}
                </h2>
                <p class="mt-2 text-center text-sm text-gray-600">
                    Installation Wizard
                </p>
            </div>

            <!-- Progress Bar -->
            @if(config('one-click-installer.ui.show_progress_bar', true))
            <div class="w-full bg-gray-200 rounded-full h-2 mb-8">
                <div class="bg-[{{ config("one-click-installer.ui.theme_color", "#10b981") }}] h-2 rounded-full transition-all duration-300"
                    style="width: {{ ($currentStep / $totalSteps) * 100 }}%"></div>
            </div>

            <!-- Step Indicators -->
            <div class="flex justify-between mb-8">
                @for($i = 1; $i <= $totalSteps; $i++) <div class="flex flex-col items-center">
                    <div class="step-indicator w-10 h-10 rounded-full border-2 border-gray-300 flex items-center justify-center text-sm font-medium
                                {{ $i < $currentStep ? 'completed' : ($i == $currentStep ? 'active' : '') }}">
                        @if($i < $currentStep) <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                clip-rule="evenodd"></path>
                            </svg>
                            @else
                            {{ $i }}
                            @endif
                    </div>
                    <div class="mt-2 text-xs text-center text-gray-500">
                        @if($i == 1) Welcome
                        @elseif($i == 2) Environment
                        @elseif($i == 3) Database
                        @elseif($i == 4) Admin
                        @elseif($i == 5) Finish
                        @endif
                    </div>
            </div>
            @endfor
        </div>
        @endif

        <!-- Main Content -->
        <div class="bg-white shadow-xl rounded-lg">
            <div class="px-6 py-8 sm:px-10 fade-in">
                @yield('content')
            </div>
        </div>

        <!-- Footer -->
        <div class="text-center text-sm text-gray-500">
            Powered by <a href="https://clevpro.com"
                class="text-blue-500 hover:text-blue-600">Clevpro</a>
        </div>
    </div>
    </div>

    <!-- Installer JavaScript Assets -->
    @if(file_exists(public_path('vendor/installer/installer.js')))
        <script src="{{ asset('vendor/installer/installer.js') }}"></script>
    @endif

    <!-- Additional JavaScript for smooth transitions -->
    <script>
        // Configuration for installer JS
        window.installerConfig = {
            autoSave: {{ config('one-click-installer.ui.auto_save', 'false') }},
            animationDuration: {{ config('one-click-installer.ui.animation_duration', 300) }},
            currentStep: {{ $currentStep ?? 1 }},
            totalSteps: {{ $totalSteps ?? 5 }}
        };

        // Legacy support for existing functionality
        document.addEventListener('DOMContentLoaded', function() {
            // Auto-focus first input field if not handled by installer.js
            setTimeout(() => {
                const firstInput = document.querySelector('input[type="text"], input[type="email"], input[type="password"]');
                if (firstInput && document.activeElement !== firstInput) {
                    firstInput.focus();
                }
            }, 100);
        });
    </script>
</body>

</html>