@extends('layouts.app-layout')

@section('content')
    <div class="p-5 lg:p-14 lg:py-7">
        <div class="bg-white rounded shadow-lg p-5">
            <div class="mb-5">
                <div class="font-bold text-xl">Update Pasal Undang-Undang</div>
            </div>
            <div class="grid gap-3">
                <form action="{{ route('archive.update-pasal', $pasal->id) }}" method="POST">
                    @csrf
                    <div class="grid md:grid-cols-[1fr_4fr]">
                        <div class="font-bold">Undang-Undang</div>
                        <div>{{ $pasal->uu->uu }}</div>
                    </div>
                    <div class="grid md:grid-cols-[1fr_4fr]">
                        <div class="font-bold">Tentang</div>
                        <div>{{ $pasal->uu->tentang }}</div>
                    </div>
                    <div class="grid md:grid-cols-[1fr_4fr]">
                        <div class="font-bold">Kategori</div>
                        <div>{{ $pasal->uu->category->nama_kategori }}</div>
                    </div>
                    <div class="grid md:grid-cols-[1fr_4fr]">
                        <div class="font-bold">Pasal</div>
                        <div class="capitalize">{{ str_replace('~', ' ', str_replace(' ', '>', $pasal->uud_id)) }}</div>
                    </div>
                    <div class="grid md:grid-cols-[1fr_4fr]">
                        <div class="font-bold">Bunyi</div>
                        <textarea name="uud_content" rows="6" class="input-rounded-cyan border-2 border-slate-300">
                            {{ trim($pasal->uud_content) }}
                        </textarea>
                    </div>
                    <div class="text-center mt-5">
                        <button class="btn-rounded-solid-cyan">
                            Update
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
