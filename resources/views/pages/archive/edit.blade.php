@extends('layouts.app-layout')

@section('content')
    <div class="p-5 lg:p-14 lg:py-7">


        <div id='container'>
            <form action="{{ route('archive.update', $archive->id_tbl_uu) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="p-5 mb-5 grid gap-5 shadow border border-slate-300 border-t-8 border-t-sky-700 rounded-md overflow-hidden bg-white">
                    <input type="text" name="uu" value="{{ $archive->uu }}" class="w-full font-semibold border-0 border-b-2 border-b-slate-400 focus:ring-0 p-2 text-2xl" required>
                    <input type="text" name="tentang" value="{{ $archive->tentang }}" placeholder="Tentang" class="w-full h-5 border-0 border-b-2 border-b-slate-400 overflow-y-auto focus:ring-0 p-2 pt-0" required>
                    <select name="category" class="w-auto rounded" required>
                        @foreach ($categories as $category)
                            <option value="{{ $category->kategori_id }}" {{ $archive->id_tbl_uu == $category->kategori_id ? 'selected' : '' }}>{{ $category->nama_kategori }}</option>
                        @endforeach
                    </select>
                    <div>
                        <a href="{{ asset('assets/pdf/') . '/' . $archive->file_arsip }}" target="blank" class="text-lg text-sky-600">
                            <i class='bx bxs-file-pdf'></i> {{ $archive->uu }}
                        </a>
                    </div>
                    <div>
                        <div>Arsip Baru</div>
                        <input type="file" name="arsip">
                    </div>
                </div>
                <ul id='sortable-input' class="grid gap-2">
                    @isset($result)
                        @foreach ($result as $pasal)
                            <li class="border border-slate-300 list-none rounded-lg overflow-hidden shadow bg-white">
                                <div class="header py-2 flex justify-center hover:bg-slate-100 border-b border-b-slate-300 cursor-move">
                                    <img src="{{ asset('assets/svg/grabber.svg') }}" class="rotate-90">
                                </div>
                                <div class="title px-3 py-2 pb-3 font-bold">Pasal</div>
                                <div class="content grid gap-5">
                                    @foreach ($pasal['content'] as $ayat)
                                        <div class="content-item px-3 grid grid-cols-[1fr_auto_auto]">
                                            {{-- <textarea name="" class="ayat-input w-full h-5 border-0 border-b-2 border-b-slate-400 overflow-y-auto focus:ring-0 p-2 pt-0" required>{{ $ayat }}</textarea> --}}
                                            <textarea name="" class="ayat-input input-rounded-cyan border-2 border-slate-200" required>{{ $ayat }}</textarea>
                                            <div class="content-grab mb-5 cursor-move flex items-center hover:bg-slate-200 h-full">
                                                <img src="{{ asset('assets/svg/grabber.svg') }}" class="p-2">
                                            </div>
                                            <div class="content-grab mb-5 cursor-pointer flex items-center h-full">
                                                <img src="{{ asset('assets/svg/x.svg') }}" onclick="removeInput(this)" class="p-2 rounded-full hover:bg-slate-200">
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                <div class="flex justify-end gap-1 border-t border-t-slate-200 mt-2 p-2">
                                    <button type="button" onclick="addInput(this)">
                                        <img src="{{ asset('assets/svg/plus-circle.svg') }}" class="p-2 rounded-full fill-slate-600 hover:fill-slate-900 hover:bg-slate-200">
                                    </button>
                                    <button type="button" onclick="removePasal(this)">
                                        <img src="{{ asset('assets/svg/trash.svg') }}" class="p-2 rounded-full fill-slate-600 hover:fill-slate-900 hover:bg-slate-200">
                                    </button>
                                </div>
                            </li>
                        @endforeach
                    @else
                        <li class="border border-slate-300 rounded-lg list-none overflow-hidden shadow bg-white">
                            <div class="header py-2 flex justify-center hover:bg-slate-100 border-b border-b-slate-300 cursor-move">
                                <img src="{{ asset('assets/svg/grabber.svg') }}" class="rotate-90">
                            </div>
                            <div class="title px-3 py-2 pb-3 font-bold">Pasal</div>
                            <div class="content">
                                <div class="content-item px-3 grid grid-cols-[1fr_auto_auto]">
                                    <textarea name="" class="ayat-input w-full h-5 border-0 border-b-2 border-b-slate-400 overflow-y-auto focus:ring-0 p-2 pt-0" required></textarea>
                                    <div class="content-grab mb-5 cursor-move flex items-center hover:bg-slate-200 h-full">
                                        <img src="{{ asset('assets/svg/grabber.svg') }}" class="p-2">
                                    </div>
                                    <div class="content-grab mb-5 cursor-pointer flex items-center h-full">
                                        <img src="{{ asset('assets/svg/x.svg') }}" onclick="removeInput(this)" class="p-2 rounded-full hover:bg-slate-200">
                                    </div>
                                </div>
                            </div>
                            <div class="flex justify-end gap-1 border-t border-t-slate-200 mt-2 p-2">
                                <button type="button" onclick="addInput(this)">
                                    <img src="{{ asset('assets/svg/plus-circle.svg') }}" class="p-2 rounded-full fill-slate-600 hover:fill-slate-900 hover:bg-slate-200">
                                </button>
                                <button type="button" onclick="removePasal(this)">
                                    <img src="{{ asset('assets/svg/trash.svg') }}" class="p-2 rounded-full fill-slate-600 hover:fill-slate-900 hover:bg-slate-200">
                                </button>
                            </div>
                        </li>
                    @endisset
                </ul>

                <button type="button" onclick="addPasal(this)" class="py-1 px-2 rounded-sm bg-sky-500 mt-3 text-white font-semibold">Pasal baru</button>

                <div class="flex justify-center">
                    <button class="px-3 py-2 font-medium text-lg bg-sky-800 rounded text-white">Submit</button>
                </div>
            </form>
        </div>
    </div>
@endsection


@section('datatable')
    <script src="https://code.jquery.com/ui/1.13.1/jquery-ui.js"></script>
    <script src="{{ asset('js/input-sort.js') }}"></script>
@endsection
