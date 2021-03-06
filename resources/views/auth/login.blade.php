@extends('page')

@section('content')
    <div class="container mx-auto pt-8 px-4">
        <div class="md:w-1/2 mx-auto bg-white p-4 shadow-lg rounded border border-gray-300">
            <form action="{{ route('login') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="label" for="email">{{ __('Email') }}</label>
                    <input class="input" type="email" id="email" name="email" value="{{ old('email', '') }}" required autofocus>
                    @if ($errors->has('email'))
                        <small class="mt-1 text-xs text-red-400">{{ $errors->first('email') }}</small>
                    @endif
                </div>

                <div class="mb-4">
                    <label class="label" for="password">Password</label>
                    <input class="input" type="password" id="password" name="password" required>
                    @if ($errors->has('password'))
                        <small class="mt-1 text-xs text-red-400">{{ $errors->first('password') }}</small>
                    @endif

                    <a href="{{ route('password.request') }}" class="mt-1 text-sm text-gray-600 block">
                        Forgot password?
                    </a>
                </div>

                <div class="mb-4">
                    <input type="checkbox" name="remember" id="remember">
                    <label for="remember" class="text-sm text-gray-700">Stay logged in</label>
                </div>

                <button type="submit" class="bg-indigo-600 w-full py-3 text-white rounded font-bold test-sm">Login</button>

                <a href="{{ route('register') }}" class="mt-2 text-sm text-indigo-700 text-center block">
                    Or create a new account
                </a>
            </form>
        </div>
    </div>
@endsection
