@extends('layouts.app-layout')

@section('content')
<div class="p-5 lg:p-14 lg:py-7">
    <div class="grid lg:grid-cols-[1fr_4fr] rounded-md shadow-xl">
        <div class="p-5 bg-slate-100">
            <div class="mb-5 flex justify-center">
                <div class="p-6 bg-white flex justify-center rounded-tl-3xl rounded-br-3xl">
                    <i class='bx bxs-file-pdf text-5xl lg:text-8xl'></i>
                </div>
            </div>
            <div class="grid gap-2 divide-y divide-slate-300">
                <div class="py-3">
                    <div class="font-bold">{{ $archive->uu }}</div>
                    <div class="text-sm">
                        {{ $archive->tentang }}
                    </div>
                </div>
                <div class="py-3">
                    <div class="font-bold">File</div>
                    <div class="text-sm">
                        <a href="{{ asset('assets/pdf/' . $archive->file_arsip) }}" target="blank">
                            PDF <i class='bx bx-link text-cyan-500'></i>
                        </a>
                    </div>
                </div>
                <div class="py-3">
                    <div class="font-bold">Kategori</div>
                    <div class="">
                        {{ $archive->category->nama_kategori }}
                    </div>
                </div>
                <div class="py-3">
                    <div class="font-bold">Status</div>
                    <div class="">
                        @if ($archive->status == 1)
                        Belum Verifikasi
                        @elseif($archive->status == 2)
                        Tidak berlaku
                        @elseif($archive->status == 3)
                        Berlaku
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div class="bg-white">
            @auth
            <div class="p-5 py-2 flex justify-end gap-2 border-b-2 border-b-slate-200">
                <a href="{{ route('archive.index') }}">
                    <button class="py-1 px-2 rounded-md btn-rounded-solid-cyan text-sm"><i class='bx bx-arrow-back'></i></button>
                </a>
                <a href="{{ route('archive.edit', $archive->id_tbl_uu) }}">
                    <button class="py-1 px-4 rounded-md btn-rounded-solid-cyan text-sm">Edit</button>
                </a>
                <label for="confirmArchiveDeleteModal" class="py-1 px-4 rounded-md btn-rounded-solid-cyan text-sm">
                    Hapus
                </label>
            </div>
            @endauth
            <div class="p-5">
                <?php $tempPasal = ''; ?>
                @forelse ($pasals as $pasal)
                <?php
                $arrPasal = explode(' ', $pasal->uud_id);
                $pasalTitle = count($arrPasal) <= 1 ? $pasal->uud_id : $arrPasal[0];
                $pasalTitle = str_replace('~', ' ', $pasalTitle);
                $noPasal = explode(' ', $pasalTitle);
                $noPasal = count($noPasal) > 1 ? $noPasal[1] : $pasalTitle;
                ?>
                <div class="grid grid-cols-[auto_1fr] gap-5 {{ $noPasal != $tempPasal ? 'mt-5' : '' }}">
                    <div class="flex justify-center ">
                        @if ($noPasal != $tempPasal)
                        <div class="p-1 h-7 w-7 lg:h-8 lg:w-8 grid place-content-center font-extrabold text-sm bg-slate-800 rounded-lg text-white">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24">
                                <path class="fill-white stroke-2" fill-rule="evenodd" d="M18.25 15.5a.75.75 0 00.75-.75v-9a.75.75 0 00-.75-.75h-9a.75.75 0 000 1.5h7.19L6.22 16.72a.75.75 0 101.06 1.06L17.5 7.56v7.19c0 .414.336.75.75.75z"></path>
                            </svg>
                        </div>
                        @else
                        <div class="p-1 h-7 w-7 lg:h-8 lg:w-8 grid place-content-center font-extrabold text-sm bg-transparent rounded-lg text-transparent">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24">
                                <path class="fill-transparent" fill-rule="evenodd" d="M18.25 15.5a.75.75 0 00.75-.75v-9a.75.75 0 00-.75-.75h-9a.75.75 0 000 1.5h7.19L6.22 16.72a.75.75 0 101.06 1.06L17.5 7.56v7.19c0 .414.336.75.75.75z"></path>
                            </svg>
                        </div>
                        @endif
                    </div>
                    <div class="group relative p-3 border-b border-l border-slate-300">
                        <div class="font-bold capitalize">{{ str_replace('~', ' ', $pasal->uud_id) }}</div>
                        <div>
                            <?php $pasal->uud_content = str_replace('<br>', "\r\n", $pasal->uud_content); ?>
                            <div class="whitespace-pre-wrap">{{ strip_tags($pasal->uud_content) }}</div>
                        </div>
                        <a href="{{ route('archive.show-pasal', $pasal->id) }}" class="absolute hidden group-hover:block hover:text-sky-600 hover:bg-slate-200 hover:shadow-md top-3 w-6 h-6 rounded-full border border-slate-400 text-center align-middle right-0 bg-white -z-0">
                            <i class='bx bxs-edit-alt'></i>
                        </a>
                    </div>
                </div>
                <?php
                $tempPasal = $noPasal;
                ?>
                @empty
                <div class="font-bold">
                    Belum ada data untuk Undang-Undang ini.
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<input type="checkbox" id="confirmArchiveDeleteModal" class="modal-toggle">
<label for="confirmArchiveDeleteModal" class="modal cursor-pointer">
    <label class="p-5 bg-white rounded-md modal-box relative">
        <div class="flex justify-between">
            <h2 class="text-lg w-10/12 font-bold">Menu</h2>
            <label for="confirmArchiveDeleteModal" class="rounded-full text-red-500 cursor-pointer">
                <i class='bx bx-x text-3xl'></i>
            </label>
        </div>
        <div class="flex flex-col items-start mb-5">
            Yakin untuk menghapus arsip "{{ $archive->uu }}"?
        </div>
        <div class="py-5 flex justify-end gap-3 border-t border-t-slate-300">
            <label for="confirmArchiveDeleteModal" class="btn-rounded-solid-cyan">
                Cancel
            </label>
            <form action="{{ route('archive.destroy', $archive->id_tbl_uu) }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn-rounded-outline-red">
                    Hapus
                </button>
            </form>
        </div>
    </label>
</label>
@endsection