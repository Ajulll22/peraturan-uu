@extends('layouts.app-layout')

@section('content')
    <div class="h-full p-5 lg:p-14 lg:py-7">
        <div class="w-full p-8 bg-white rounded-md shadow-lg">
            <div class="mb-8 flex justify-between">
                <div class="text-lg font-bold">
                    Harmonisasi Undang-Undang
                </div>
                <a href="{{ route('harmonisasi.file-process') }}" class="btn-rounded-solid-cyan">
                    Lihat Hasil
                </a>
            </div>
            <div class="flex justify-center">
                <form action="{{ route('harmonisasi.file-store') }}" method="POST" enctype="multipart/form-data" id="harmonisasi-upload" class="dropzone w-8/12 flex justify-center" style="border: 4px solid rgb(227, 227, 227); background: rgb(248, 248, 248); border-radius: 12px;">
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
                    location.href = "{{ route('harmonisasi.file-process') }}"
                });
            }
        };
    </script>
@endsection
