@extends('layouts.guest')

@section('content')
<div class="w-full items-center justify-center bg-cover bg-no-repeat bg-center login-block text-center">
    <h2>Reset Hasła</h2>
    <div class="login-box text-left">
        <!-- Note the method attribute is still POST -->
        <form method="POST" action="{{ route('password.store') }}">
            @csrf
            <!-- Spoofing a PUT request -->
            {{-- <input type="hidden" name="_method" value="PUT"> --}}

            {{-- Password Reset Token --}}
            <input type="hidden" name="token" value="{{ request()->route('token') }}">

            {{-- Email Address --}}
            <div class="form-group">
                <label for="email" class="input-label">Email</label>
                <input id="email" type="email" class="block mt-1 w-full" name="email" value="{{ request()->email ?? old('email') }}" required autofocus autocomplete="email" placeholder="Enter your email">
                @error('email')
                    <div class="mt-2 text-sm text-red-600">{{ $message }}</div>
                @enderror
            </div>

            {{-- Password --}}
            <div class="form-group mt-4">
                <label for="password" class="input-label">Password</label>
                <input id="password" type="password" class="block mt-1 w-full" name="password" required autocomplete="new-password" placeholder="Enter new password">
                @error('password')
                    <div class="mt-2 text-sm text-red-600">{{ $message }}</div>
                @enderror
            </div>

            {{-- Confirm Password --}}
            <div class="form-group mt-4">
                <label for="password_confirmation" class="input-label">Confirm Password</label>
                <input id="password_confirmation" type="password" class="block mt-1 w-full" name="password_confirmation" required autocomplete="new-password" placeholder="Confirm new password">
                @error('password_confirmation')
                    <div class="mt-2 text-sm text-red-600">{{ $message }}</div>
                @enderror
            </div>

            {{-- Submit Button --}}
            <div class="flex items-center justify-center mt-4">
                <button type="submit" class="login-button">
                    Reset Password
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
