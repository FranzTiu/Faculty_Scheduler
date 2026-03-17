@extends('layouts.app')

@section('content')
    <div id="loginPage"
        class="login-card w-full max-w-4xl flex flex-col md:flex-row shadow-2xl rounded-2xl overflow-hidden bg-white mx-auto transition-all duration-300">
        <div
            class="login-left w-full md:w-1/2 p-12 bg-[#1e1b4b] text-white flex flex-col justify-center items-center text-center">

            <h2 class="text-4xl md:text-4xl font-extrabold font-['Playfair_Display'] leading-tight whitespace-nowrap"><span
                    class="text-[#fbbf24]">ComLab</span> Scheduler</h2>
        </div>
        <div class="login-right w-full md:w-1/2 p-6 md:p-10 flex flex-col justify-center relative">
            <!-- Login Section -->
            <div id="loginSection">
                <form id="loginForm" autocomplete="off" method="POST" action="{{ route('login.post') }}"
                    class="flex flex-col gap-3">
                    @csrf
                    <h1 class="text-5xl font-bold text-[#fbbf24] mb-4 font-['Playfair_Display']">Login</h1>
                    <div class="form-group flex flex-col">
                        <label class="mb-1 text-sm font-bold text-[#1e1b4b]">Username</label>
                        <input type="text" id="username" name="username" required autocomplete="off"
                            class="border border-slate-300 rounded-lg px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-[#1e1b4b] focus:border-transparent transition-all shadow-sm w-full">
                        <p id="usernameError" class="text-red-500 text-xs mt-0.5 font-bold hidden"></p>
                    </div>
                    <div class="form-group flex flex-col">
                        <label class="mb-1 text-sm font-bold text-[#1e1b4b]">Password</label>
                        <div class="relative w-full">
                            <input type="password" id="password" name="password" required autocomplete="new-password"
                                class="border border-slate-300 rounded-lg px-4 py-2.5 pr-12 focus:outline-none focus:ring-2 focus:ring-[#1e1b4b] focus:border-transparent transition-all shadow-sm w-full">
                            <span id="passwordToggle" class="password-toggle-icon">
                                <svg id="eyeIcon" xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-eye-off">
                                    <path d="M9.88 9.88a3 3 0 1 0 4.24 4.24" />
                                    <path
                                        d="M10.73 5.08A10.43 10.43 0 0 1 12 5c7 0 10 7 10 7a13.16 13.16 0 0 1-1.67 2.68" />
                                    <path d="M6.61 6.61A13.52 13.52 0 0 0 2 12s3 7 10 7a9.74 9.74 0 0 0 5.39-1.61" />
                                    <line x1="2" x2="22" y1="2" y2="22" />
                                </svg>
                            </span>
                        </div>
                        <p id="passwordError" class="text-red-500 text-xs mt-0.5 font-bold hidden"></p>
                    </div>
                    <div class="remember-me flex justify-between items-center text-sm mt-0.5">
                        <div class="remember-left flex items-center gap-2">
                            <input type="checkbox" id="remember" name="remember"
                                class="rounded border-slate-300 text-[#1e1b4b] focus:ring-[#1e1b4b] w-4 h-4 cursor-pointer">
                            <label for="remember" class="text-[#1e1b4b] cursor-pointer font-bold">Remember Me</label>
                        </div>
                        <span id="forgotPasswordBtn" class="forgot-link">Forgot Password?</span>
                    </div>
                    <button type="submit" class="toggle-btn active !w-full !max-w-none !mt-2 !py-3 !px-6 !text-[15px] md:!text-lg !font-extrabold !rounded-full transition-all duration-300">Login</button>
                    <p id="loginPostResetSuccess" class="text-green-600 text-xs mt-2 font-bold hidden text-center">
                        Password reset successfully! Please login.
                    </p>
                    <p id="loginError" class="text-red-500 text-xs mt-2 font-bold hidden"></p>
                </form>
            </div>

            <!-- Forgot Password Section (Hidden by default) -->
            <div id="forgotPasswordSection" class="hidden">
                <form id="forgotPasswordForm" class="flex flex-col gap-3">
                    <h1
                        class="text-3xl font-bold text-[#1e1b4b] mb-1 font-['Playfair_Display'] uppercase whitespace-nowrap">
                        FORGOT PASSWORD</h1>
                    <p class="text-[#1e1b4b] text-xs mb-3 leading-tight font-medium">Reset your password by entering your username <br> and 
                        new password. <br/> 
                    </p>
                    <div class="form-group flex flex-col">
                        <label class="mb-0.5 text-sm font-bold text-[#1e1b4b]">Username</label>
                        <input type="text" id="resetUsername" required placeholder="Enter your username"
                            class="border border-[#1e1b4b] border-2 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#fbbf24] focus:border-transparent transition-all shadow-sm w-full text-sm text-[#1e1b4b]">
                        <p id="resetUsernameError" class="text-red-500 text-xs mt-0.5 font-bold hidden"></p>
                    </div>
                    <div class="form-group flex flex-col">
                        <label class="mb-0.5 text-sm font-bold text-[#1e1b4b]">New Password</label>
                        <input type="password" id="resetPassword" required placeholder="Enter new password"
                            class="border border-[#1e1b4b] border-2 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#fbbf24] focus:border-transparent transition-all shadow-sm w-full text-sm text-[#1e1b4b]">
                        <p id="resetPasswordError" class="text-red-500 text-xs mt-0.5 font-bold hidden"></p>
                    </div>
                    <div class="form-group flex flex-col">
                        <label class="mb-0.5 text-sm font-bold text-[#1e1b4b]">Confirm Password</label>
                        <input type="password" id="resetPassword_confirmation" required placeholder="Confirm new password"
                            class="border border-[#1e1b4b] border-2 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#fbbf24] focus:border-transparent transition-all shadow-sm w-full text-sm text-[#1e1b4b]">
                        <p id="resetPassword_confirmationError" class="text-red-500 text-xs mt-0.5 font-bold hidden"></p>
                    </div>
                    <button type="submit" class="toggle-btn active !w-full !max-w-none !mt-2 !py-3 !px-6 !text-[15px] md:!text-lg !font-extrabold !rounded-full transition-all duration-300">Reset Password</button>
                    <x-button type="button" variant="secondary" id="backToLogin" class="w-full !py-3 !px-6 !text-[15px] md:!text-lg !font-extrabold !font-['Playfair_Display'] back-to-login-btn !rounded-full hover:!shadow-lg hover:!-translate-y-0.5 transition-all duration-200 ease-in-out">Back to Login</x-button>
                    <p id="forgotError" class="text-red-500 text-xs mt-1 text-center font-bold hidden"></p>
                    <p id="forgotSuccess" class="text-green-600 text-xs mt-2 text-center font-bold hidden"></p>
                </form>
            </div>
        </div>
    </div>
@endsection