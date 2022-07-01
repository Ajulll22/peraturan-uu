@extends('layouts.app-layout')

@section('content')
    <div class="p-3 bg-white rounded-md border border-slate-400 border-t-2 border-t-rose-700">
        <div class="flex justify-start w-full">
            <a href="{{ route('archive.create') }}">
                <button class="bg-sky-800 p-1 px-3 text-white font-medium rounded">
                    <i class='bx bx-arrow-back'></i>
                </button>
            </a>
        </div>

        <div class="py-3 mt-3 border-t border-t-slate-400">
            <form action="{{ route('archive-file.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="file" name="archive">
                <button type="submit">Upload</button>
            </form>
        </div>
    </div>
@endsection


@section('datatable')
@endsection
