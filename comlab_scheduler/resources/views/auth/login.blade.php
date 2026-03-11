@extends('layouts.app')

@section('content')
<div id="loginPage" class="login-card w-full max-w-4xl flex flex-col md:flex-row shadow-2xl rounded-2xl overflow-hidden bg-white mx-auto transition-all duration-300">
    <div class="login-left w-full md:w-1/2 p-12 bg-[#1e1b4b] text-white flex flex-col justify-center items-center text-center">
        <span class="it-faculty text-xl font-medium tracking-wider mb-4 opacity-90 font-['Inter'] uppercase">IT Faculty</span>
        <h2 class="text-3xl md:text-4xl font-extrabold font-['Playfair_Display'] leading-tight"><span class="text-[#fbbf24]">COMLAB</span><br>Scheduler</h2>
    </div>
    <div class="login-right w-full md:w-1/2 p-8 md:p-12 flex flex-col justify-center">
        <h1 class="text-2xl font-bold text-slate-800 mb-6 font-['Playfair_Display']">Login</h1>
        <form id="loginForm" autocomplete="off" method="POST" action="{{ route('login.post') }}" class="flex flex-col gap-5">
            @csrf
            <div class="form-group flex flex-col">
                <label class="mb-1.5 text-sm font-bold text-slate-700">Username</label>
                <input type="text" id="username" name="username" required autocomplete="off" class="border border-slate-300 rounded-lg px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-[#1e1b4b] focus:border-transparent transition-all shadow-sm w-full">
            </div>
            <div class="form-group flex flex-col">
                <label class="mb-1.5 text-sm font-bold text-slate-700">Password</label>
                <input type="password" id="password" name="password" required autocomplete="new-password" class="border border-slate-300 rounded-lg px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-[#1e1b4b] focus:border-transparent transition-all shadow-sm w-full">
            </div>
            <div class="remember-me flex justify-between items-center text-sm mt-1">
                <div class="remember-left flex items-center gap-2">
                    <input type="checkbox" id="remember" name="remember" class="rounded border-slate-300 text-[#1e1b4b] focus:ring-[#1e1b4b] w-4 h-4 cursor-pointer">
                    <label for="remember" class="text-slate-600 cursor-pointer font-medium">Remember Me</label>
                </div>
                <span class="forgot-link text-[#4f46e5] cursor-pointer hover:underline font-bold transition-all">Forgot Password?</span>
            </div>
            <x-button type="submit" variant="primary" class="w-full mt-2 py-3 text-lg">Login</x-button>
            <p id="loginError" style="display: none;" class="text-red-500 text-sm mt-3 text-center font-bold"></p>
        </form>
    </div>
</div>
@endsection
