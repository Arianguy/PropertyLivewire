<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-100 dark:from-slate-900 dark:via-slate-800 dark:to-indigo-900 antialiased">
        <!-- Background Pattern -->
        <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg width="60" height="60" viewBox="0 0 60 60" xmlns="http://www.w3.org/2000/svg"%3E%3Cg fill="none" fill-rule="evenodd"%3E%3Cg fill="%23f1f5f9" fill-opacity="0.4"%3E%3Ccircle cx="7" cy="7" r="1"/%3E%3C/g%3E%3C/g%3E%3C/svg%3E')] dark:bg-[url('data:image/svg+xml,%3Csvg width="60" height="60" viewBox="0 0 60 60" xmlns="http://www.w3.org/2000/svg"%3E%3Cg fill="none" fill-rule="evenodd"%3E%3Cg fill="%23334155" fill-opacity="0.1"%3E%3Ccircle cx="7" cy="7" r="1"/%3E%3C/g%3E%3C/g%3E%3C/svg%3E')]"></div>
        
        <div class="relative flex min-h-svh">
            <!-- Left Side - Branding -->
            <div class="hidden lg:flex lg:w-1/2 bg-gradient-to-br from-blue-600 via-indigo-600 to-purple-700 relative overflow-hidden">
                <!-- Decorative Elements -->
                <div class="absolute inset-0 bg-black/10"></div>
                <div class="absolute top-0 left-0 w-full h-full">
                    <div class="absolute top-20 left-20 w-32 h-32 bg-white/10 rounded-full blur-xl"></div>
                    <div class="absolute bottom-20 right-20 w-40 h-40 bg-white/5 rounded-full blur-2xl"></div>
                    <div class="absolute top-1/2 left-1/3 w-24 h-24 bg-white/10 rounded-full blur-lg"></div>
                </div>
                
                <!-- Content -->
                <div class="relative z-10 flex flex-col justify-center px-12 py-16 text-white">
                    <div class="mb-8">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="p-3 bg-white/20 backdrop-blur-sm rounded-xl">
                                <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z"/>
                                </svg>
                            </div>
                            <h1 class="text-3xl font-bold">RentalHub</h1>
                        </div>
                        <h2 class="text-4xl font-bold mb-4 leading-tight">Manage Your Properties with Confidence</h2>
                        <p class="text-xl text-blue-100 leading-relaxed">Streamline your rental business with our comprehensive property management platform. Track payments, manage tenants, and grow your portfolio effortlessly.</p>
                    </div>
                    
                    <!-- Features -->
                    <div class="space-y-4">
                        <div class="flex items-center gap-3">
                            <div class="w-2 h-2 bg-white rounded-full"></div>
                            <span class="text-blue-100">Automated rent collection & tracking</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="w-2 h-2 bg-white rounded-full"></div>
                            <span class="text-blue-100">Comprehensive tenant management</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="w-2 h-2 bg-white rounded-full"></div>
                            <span class="text-blue-100">Financial reporting & analytics</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Right Side - Login Form -->
            <div class="flex-1 flex items-center justify-center p-6 lg:p-12">
                <div class="w-full max-w-md">
                    <!-- Mobile Logo -->
                    <div class="lg:hidden text-center mb-8">
                        <a href="{{ route('home') }}" class="inline-flex flex-col items-center gap-3" wire:navigate>
                            <div class="p-4 bg-gradient-to-br from-blue-600 to-indigo-600 rounded-2xl shadow-lg">
                                <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z"/>
                                </svg>
                            </div>
                            <div>
                                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">RentalHub</h1>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Property Management Platform</p>
                            </div>
                        </a>
                    </div>
                    
                    <!-- Desktop Logo -->
                    <div class="hidden lg:block text-center mb-8">
                        <a href="{{ route('home') }}" class="inline-flex items-center gap-3 group" wire:navigate>
                            <div class="p-3 bg-gradient-to-br from-blue-600 to-indigo-600 rounded-xl shadow-lg group-hover:shadow-xl transition-shadow duration-200">
                                <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z"/>
                                </svg>
                            </div>
                            <div class="text-left">
                                <h1 class="text-xl font-bold text-gray-900 dark:text-white">RentalHub</h1>
                                <p class="text-xs text-gray-600 dark:text-gray-400">Property Management</p>
                            </div>
                        </a>
                    </div>
                    
                    <!-- Form Container -->
                    <div class="bg-white/80 dark:bg-slate-800/80 backdrop-blur-sm rounded-2xl shadow-xl border border-white/20 dark:border-slate-700/50 p-8">
                        {{ $slot }}
                    </div>
                    
                    <!-- Footer -->
                    <div class="text-center mt-6">
                        <p class="text-sm text-gray-600 dark:text-gray-400">
                            © {{ date('Y') }} RentalHub. Streamlining property management.
                        </p>
                    </div>
                </div>
            </div>
        </div>
        @fluxScripts
    </body>
</html>
