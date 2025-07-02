<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>KKHS Concert Ticketing System</title>
        <link rel="icon" href="/favicon.ico" sizes="any">
        <link rel="icon" href="/favicon.svg" type="image/svg+xml">
        <link rel="apple-touch-icon" href="/apple-touch-icon.png">
        
        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />
        
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <style>
            /* SVG Animation Styles */
            @keyframes headSway {
                0%, 100% { transform: translateX(0px) rotate(0deg); }
                25% { transform: translateX(2px) rotate(1deg); }
                50% { transform: translateX(-2px) rotate(-1deg); }
                75% { transform: translateX(1px) rotate(0.5deg); }
            }
            
            @keyframes floatUp {
                0%, 100% { transform: translateY(0px); }
                50% { transform: translateY(-8px); }
            }
            
            @keyframes musicFloat {
                0%, 100% { transform: translateY(0px) rotate(0deg); }
                33% { transform: translateY(-5px) rotate(2deg); }
                66% { transform: translateY(-2px) rotate(-1deg); }
            }
            
            @keyframes pulse {
                0%, 100% { transform: scale(1); }
                50% { transform: scale(1.05); }
            }
            
            .animate-head-sway {
                animation: headSway 4s ease-in-out infinite;
                transform-origin: center bottom;
            }
            
            .animate-float {
                animation: floatUp 6s ease-in-out infinite;
            }
            
            .animate-music-float {
                animation: musicFloat 5s ease-in-out infinite;
            }
            
            .animate-pulse-gentle {
                animation: pulse 8s ease-in-out infinite;
            }
        </style>
    </head>
    <body class="min-h-screen bg-gradient-to-br from-stone-50 to-rose-50 dark:from-stone-950 dark:to-rose-950 font-sans antialiased">

        <!-- Hero Section -->
        <main class="relative">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-20 pb-32">
                <div class="grid lg:grid-cols-2 gap-12 items-center">
                    <!-- Text Content -->
                    <div class="text-center lg:text-left">
                        <!-- Badge -->
                        <div class="inline-flex items-center px-4 py-2 bg-white/80 dark:bg-stone-800/80 backdrop-blur-sm border border-stone-200 dark:border-stone-700 rounded-full text-sm font-medium text-stone-700 dark:text-stone-300 mb-8">
                            <flux:icon.academic-cap variant="solid" class="w-5 h-5 text-rose-500 mr-2" />
                            Kota Kinabalu High School
                        </div>
                        
                        <!-- Main Heading -->
                        <h1 class="text-4xl md:text-6xl lg:text-7xl font-bold tracking-tight text-stone-900 dark:text-stone-100 mb-6">
                           KKHS Concert
                            <span class="block text-transparent bg-clip-text bg-gradient-to-r from-rose-500 to-pink-500">
                                Ticketing
                            </span>
                            <span class="block">System</span>
                        </h1>
                        
                        <!-- Subtitle -->
                        <p class="text-lg md:text-xl text-stone-600 dark:text-stone-400 max-w-2xl mx-auto lg:mx-0 mb-12 leading-relaxed">
                            Your gateway to unforgettable musical experiences. 
                            Secure your tickets for KKHS concerts with ease and convenience.
                        </p>
                        
                        <!-- CTA Buttons -->
                        <div class="flex flex-col sm:flex-row items-center justify-center lg:justify-start gap-4 sm:gap-6">
                            @if (Route::has('login'))
                                @auth
                                    <a
                                        href="{{ url('/dashboard') }}"
                                        class="inline-flex items-center px-8 py-4 bg-rose-500 text-white font-semibold rounded-xl hover:bg-rose-600 transform hover:scale-105 transition-all duration-200 shadow-lg hover:shadow-xl"
                                    >
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                                        </svg>
                                        Go to Dashboard
                                    </a>
                                @else
                                    <a
                                        href="{{ route('login') }}"
                                        class="inline-flex items-center px-8 py-4 bg-rose-500 text-white font-semibold rounded-xl hover:bg-rose-600 transform hover:scale-105 transition-all duration-200 shadow-lg hover:shadow-xl"
                                    >
                                        <flux:icon.arrow-right-start-on-rectangle variant="solid" class="w-6 h-6 mr-2" />
                                        Login to Get Started
                                    </a>
                                @endauth
                            @endif
                        </div>
                    </div>

                    <!-- Illustration -->
                    <div class="flex items-center justify-center lg:justify-end">
                        <div class="relative max-w-lg w-full animate-float">
                            <!-- Animated background glow -->
                            <div class="absolute inset-0 bg-gradient-to-r from-rose-400/20 to-pink-500/20 rounded-3xl blur-3xl transform rotate-6 animate-pulse-gentle"></div>
                            
                            <!-- Main illustration container -->
                            <div class="relative bg-white/80 dark:bg-stone-800/80 backdrop-blur-sm rounded-3xl p-8 border border-stone-200 dark:border-stone-700 animate-music-float">
                                <div class="animate-head-sway">
                                    <img 
                                        src="{{ asset('images/undraw-music.svg') }}" 
                                        alt="Music Concert Illustration" 
                                        class="w-full h-auto object-contain transition-all duration-300 hover:scale-105"
                                        style="filter: drop-shadow(0 10px 25px rgba(0, 0, 0, 0.1));"
                                    />
                                </div>
                                
                                <!-- Floating musical notes -->
                                <div class="absolute top-4 right-4 animate-music-float opacity-60">
                                    <svg class="w-6 h-6 text-rose-400" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M12 3v10.55c-.59-.34-1.27-.55-2-.55-2.21 0-4 1.79-4 4s1.79 4 4 4 4-1.79 4-4V7h4V3h-6z"/>
                                    </svg>
                                </div>
                                
                                <div class="absolute top-8 left-4 animate-float opacity-40" style="animation-delay: -2s;">
                                    <svg class="w-4 h-4 text-pink-400" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M12 3v10.55c-.59-.34-1.27-.55-2-.55-2.21 0-4 1.79-4 4s1.79 4 4 4 4-1.79 4-4V7h4V3h-6z"/>
                                    </svg>
                                </div>
                                
                                <div class="absolute bottom-8 right-8 animate-pulse-gentle opacity-50" style="animation-delay: -4s;">
                                    <svg class="w-5 h-5 text-rose-500" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M12 3v10.55c-.59-.34-1.27-.55-2-.55-2.21 0-4 1.79-4 4s1.79 4 4 4 4-1.79 4-4V7h4V3h-6z"/>
                                    </svg>
                                </div>
                                
                                <!-- Sparkle effects -->
                                <div class="absolute top-1/4 left-1/4 w-2 h-2 bg-yellow-400 rounded-full animate-ping opacity-60" style="animation-delay: -1s;"></div>
                                <div class="absolute top-3/4 right-1/4 w-1 h-1 bg-pink-400 rounded-full animate-ping opacity-40" style="animation-delay: -3s;"></div>
                                <div class="absolute top-1/2 left-3/4 w-1.5 h-1.5 bg-rose-400 rounded-full animate-ping opacity-50" style="animation-delay: -5s;"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- User Features Section -->
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-20">
                <div class="text-center mb-16">
                    <h2 class="text-3xl md:text-4xl font-bold text-stone-900 dark:text-stone-100 mb-4">
                        Designed for Everyone
                    </h2>
                    <p class="text-lg text-stone-600 dark:text-stone-400 max-w-2xl mx-auto">
                        Whether you're a teacher managing ticket sales or a student attending concerts, we've got you covered.
                    </p>
                </div>

                <div class="grid lg:grid-cols-2 gap-12">
                    <!-- Teachers Section -->
                    <div class="bg-white/60 dark:bg-stone-800/60 backdrop-blur-sm rounded-3xl border border-stone-200 dark:border-stone-700 p-8 lg:p-10">
                        <div class="flex items-center mb-8">
                            <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl flex items-center justify-center mr-4">
                                <flux:icon.user-group variant="solid" class="w-8 h-8 text-white" />
                            </div>
                            <div>
                                <h3 class="text-2xl font-bold text-stone-900 dark:text-stone-100">For Teachers</h3>
                                <p class="text-stone-600 dark:text-stone-400">Manage ticket sales with ease</p>
                            </div>
                        </div>

                        <div class="space-y-6">
                            <!-- Feature 1 -->
                            <div class="flex items-start space-x-4">
                                <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="font-semibold text-stone-900 dark:text-stone-100 mb-1">Sell E-Tickets</h4>
                                    <p class="text-stone-600 dark:text-stone-400 text-sm">Issue digital tickets to students directly through the platform with instant confirmation.</p>
                                </div>
                            </div>

                            <!-- Feature 2 -->
                            <div class="flex items-start space-x-4">
                                <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V6a1 1 0 00-1-1H5a1 1 0 00-1 1v1a1 1 0 001 1zm12 0h2a1 1 0 001-1V6a1 1 0 00-1-1h-2a1 1 0 00-1 1v1a1 1 0 001 1zM5 20h2a1 1 0 001-1v-1a1 1 0 00-1-1H5a1 1 0 00-1 1v1a1 1 0 001 1z"/>
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="font-semibold text-stone-900 dark:text-stone-100 mb-1">Scan Tickets</h4>
                                    <p class="text-stone-600 dark:text-stone-400 text-sm">Quickly verify student tickets at the entrance using our built-in QR code scanner.</p>
                                </div>
                            </div>

                            <!-- Feature 3 -->
                            <div class="flex items-start space-x-4">
                                <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="font-semibold text-stone-900 dark:text-stone-100 mb-1">Track Sales</h4>
                                    <p class="text-stone-600 dark:text-stone-400 text-sm">Monitor ticket sales in real-time with detailed analytics and reporting features.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Students Section -->
                    <div class="bg-white/60 dark:bg-stone-800/60 backdrop-blur-sm rounded-3xl border border-stone-200 dark:border-stone-700 p-8 lg:p-10">
                        <div class="flex items-center mb-8">
                            <div class="w-16 h-16 bg-gradient-to-br from-rose-500 to-pink-500 rounded-2xl flex items-center justify-center mr-4">
                                <flux:icon.user variant="solid" class="w-8 h-8 text-white" />
                            </div>
                            <div>
                                <h3 class="text-2xl font-bold text-stone-900 dark:text-stone-100">For Students</h3>
                                <p class="text-stone-600 dark:text-stone-400">Access your tickets anytime</p>
                            </div>
                        </div>

                        <div class="space-y-6">
                            <!-- Feature 1 -->
                            <div class="flex items-start space-x-4">
                                <div class="w-10 h-10 bg-rose-100 dark:bg-rose-900/30 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <svg class="w-5 h-5 text-rose-600 dark:text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="font-semibold text-stone-900 dark:text-stone-100 mb-1">View Your Tickets</h4>
                                    <p class="text-stone-600 dark:text-stone-400 text-sm">Login to your account to see all purchased tickets with concert details and seat information.</p>
                                </div>
                            </div>

                            <!-- Feature 2 -->
                            <div class="flex items-start space-x-4">
                                <div class="w-10 h-10 bg-rose-100 dark:bg-rose-900/30 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <svg class="w-5 h-5 text-rose-600 dark:text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="font-semibold text-stone-900 dark:text-stone-100 mb-1">Print E-Tickets</h4>
                                    <p class="text-stone-600 dark:text-stone-400 text-sm">Download and print your tickets as backup or for easy access at the venue entrance.</p>
                                </div>
                            </div>

                            <!-- Feature 3 -->
                            <div class="flex items-start space-x-4">
                                <div class="w-10 h-10 bg-rose-100 dark:bg-rose-900/30 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <svg class="w-5 h-5 text-rose-600 dark:text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="font-semibold text-stone-900 dark:text-stone-100 mb-1">Digital Entry</h4>
                                    <p class="text-stone-600 dark:text-stone-400 text-sm">Show your e-ticket directly from your phone at the entrance for quick and contactless entry.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Background Decorations -->
            <div class="absolute inset-0 overflow-hidden pointer-events-none">
                <div class="absolute -top-40 -right-40 w-80 h-80 bg-gradient-to-br from-rose-400/20 to-pink-500/20 rounded-full blur-3xl"></div>
                <div class="absolute -bottom-40 -left-40 w-80 h-80 bg-gradient-to-br from-rose-400/20 to-pink-500/20 rounded-full blur-3xl"></div>
            </div>
        </main>

        <!-- Footer -->
        <footer class="border-t border-stone-200 dark:border-stone-800 bg-white/80 dark:bg-stone-900/80 backdrop-blur-sm">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                <div class="text-center text-stone-600 dark:text-stone-400">
                    <p>&copy; {{ date('Y') }} Kota Kinabalu High School. All rights reserved.</p>
                    <p class="mt-2 text-sm">KKHS Concert Ticketing System</p>
                </div>
            </div>
        </footer>
    </body>
</html>
