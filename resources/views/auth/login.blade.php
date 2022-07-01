@extends('layouts.app-layout')

@section('content')
    <div class="p-5 lg:p-12 lg:py-7 h-full flex justify-center items-start bg-gradient-to-tr from-cyan-600/20 to-white">
        <div class="w-5/12 p-5 bg-white rounded-md shadow-xl">
            <form action="{{ route('login') }}" method="POST">
                @csrf
                <div class="inline-block pb-1 font-extrabold text-2xl border-b-2 border-b-cyan-600/50">
                    Sign In
                </div>
                @if ($errors->any())
                    <div class="font-bold text-red-500 mt-4">Whoops! Something went wrong!</div>
                    <ul class="ml-5 text-red-500 list-disc">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                @endif
                <div class="pt-5 pb-2 grid gap-3">
                    <div class="grid">
                        <label for="email" class="text-sm">Email</label>
                        <input type="text" name="email" class="p-1 input-rounded-cyan border text-sm border-cyan-600/40" value="{{ old('email') }}" autofocus>
                    </div>
                    <div class="grid">
                        <label for="password" class="text-sm">Password</label>
                        <input type="password" name="password" class="p-1 input-rounded-cyan border text-sm border-cyan-600/40">
                    </div>
                </div>

                <div class="mt-5">
                    <button type="submit" class="w-full btn-rounded-solid-cyan">
                        Login Now
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
