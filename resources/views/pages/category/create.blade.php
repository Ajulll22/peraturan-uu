@extends('layouts.app-layout')

@section('content')
    <div class="p-5 lg:p-14 lg:py-7">
        <div class="grid rounded min-w-0">
            <div class="bg-white rounded-lg shadow-lg">
                <div class="p-5 py-2 bg-cyan-600 text-white font-bold text-lg rounded-tl-lg rounded-tr-lg">
                    Rumpun Baru
                </div>
                <div class="p-5">
                    <form action="{{ route('category.store') }}" method="POST">
                        @csrf
                        <div class=" pb-2 grid md:grid-cols-1 lg:grid-cols-1 gap-5">
                            <div class="grid">
                                <label for="nama_kategori" class="text-sm">Nama Rumpun</label>
                                <input type="text" name="nama_kategori" class="p-1 input-rounded-cyan border text-sm border-cyan-600/40" value="{{ old('nama_kategori') }}" autofocus>
                            </div>
                        </div>
                        <div class="mt-5 pt-3 flex justify-end border-t border-t-slate-300">
                            <button class="btn-rounded-solid-cyan">
                                Simpan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('datatable')
    <script>
    </script>
@endsection
