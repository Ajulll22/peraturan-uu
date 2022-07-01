@extends('layouts.app-layout')

@section('content')
<div class="p-5 lg:p-14 lg:py-7 grid lg:grid-cols-[1fr_3fr] content-start gap-4">
    {{-- FILTER --}}
    <div class=" self-start">
        <form action="{{ route('draft.index') }}" method="GET">
            @csrf
            <div>
                <i class='bx bxs-filter-alt text-lg'></i>
                <span class="ml-2 text-lg font-bold">Drafting</span>
            </div>

            <div>
                <div class="mb-3">
                    <label class="block text-gray-700 text-sm font-medium mb-2" for="username">
                        Masukkan Tema
                    </label>
                    <textarea name="theme" id="theme" rows="10" class="block appearance-none w-full input-rounded-cyan text-sm shadow" placeholder="Masukkan tema drafting..."></textarea>
                </div>
                <button id="applyFilter" class="w-full btn-rounded-solid-cyan" type="button">
                    Cari
                </button>
            </div>
        </form>
    </div>
    {{-- DRAFT RESULT --}}
    <div class="self-start">
        <div class="flex justify-end gap-3">
            <a href="{{ route('draft.index') . '?mode=full' }}">
                <button class="{{ !$mode || $mode != 'pasal' ? 'btn-solid-cyan' : 'btn-cyan' }}">Penuh</button>
            </a>
            <a href="{{ route('draft.index') . '?mode=pasal' }}">
                <button class="{{ $mode == 'pasal' ? 'btn-solid-cyan' : 'btn-cyan' }}">Per pasal</button>
            </a>
        </div>
        @if (!$mode || $mode != 'pasal')
        @include('pages.draft-full')
        @else
        @include('pages.drafting.draft-pasal')
        @endif
        {{-- @include('pages.drafting.draft-pasal') --}}

    </div>
</div>
@endsection