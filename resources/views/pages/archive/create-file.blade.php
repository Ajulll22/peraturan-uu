@extends('layouts.app-layout')

@section('content')
    <div class="p-5 lg:p-14 lg:py-7">
        {{-- <div class="p-3 bg-white rounded-md border border-slate-400 border-t-2 border-t-rose-700">
            <div class="flex justify-start w-full">
                <a href="{{ route('archive.create') }}">
                    <button class="bg-sky-800 p-1 px-3 text-white font-medium rounded">
                        <i class='bx bx-arrow-back'></i>
                    </button>
                </a>
            </div>

            <div class="py-3 mt-3 border-t border-t-slate-400">

            </div>
        </div> --}}
        <div class="w-full p-8 bg-white rounded-md shadow-lg">
            <div class="mb-8">
                <div class="text-lg font-bold">
                    Upload Arsip Undang-Undang
                </div>
                <div class="mt-5 ">
                    <div class="font-semibold">Ketentuan</div>
                    <ul class="list-disc list-inside">
                        <li>File Undang-Undang yang dapat diproses berformat <strong>PDF</strong></li>
                    </ul>
                </div>
            </div>
            <div class="flex justify-center">
                <form action="{{ route('archive-file.store') }}" method="POST" enctype="multipart/form-data" id="harmonisasi-upload" class="dropzone w-8/12 flex justify-center" style="border: 4px solid rgb(227, 227, 227); background: rgb(248, 248, 248); border-radius: 12px;">
                    @csrf
                    <div class="dz-default dz-message" style="margin: 0; ">
                        <div class="flex justify-center">
                            <i class='bx bx-cloud-upload text-8xl text-slate-400'></i>
                        </div>
                        Tap or drag files here to upload
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection


@section('datatable')
    <script type="text/javascript">
        let dropZone = Dropzone.options.harmonisasiUpload = {
            maxFiles: 1,
            acceptedFiles: ".pdf",
            init: function() {
                this.on("addedfile", file => {
                    console.log("A file has been added");
                });

                this.on('success', file => {
                    location.href = "{{ route('archive-file.process') }}"
                });
            }
        };
    </script>
@endsection
