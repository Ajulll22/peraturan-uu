@extends('layouts.app-layout')

@section('content')
    <div class="p-5 lg:p-14 lg:py-7">
        <div class=" grid lg:grid-cols-[1fr_4fr] rounded-md shadow-xl">
            <div class="p-5 bg-slate-100">
                <div class="mb-5 flex justify-center">
                    <div class="p-6 bg-white flex justify-center rounded-tl-3xl rounded-br-3xl">
                        <i class='bx bxs-file-pdf text-5xl lg:text-8xl'></i>
                    </div>
                </div>
                <div class="grid gap-2 divide-y divide-slate-300">
                    <div class="py-3">
                        <div class="font-bold">{{ $archiveUU->uu }}</div>
                        <div class="text-sm">
                            {{ $archiveUU->tentang }}
                        </div>
                    </div>
                    <div class="py-3">
                        <div class="font-bold">File</div>
                        <div class="text-sm">
                            <a href="{{ asset('assets/pdf/' . $archiveUU->file_arsip) }}" target="blank">
                                PDF <i class='bx bx-link text-cyan-500'></i>
                            </a>
                        </div>
                    </div>
                    <div class="py-3">
                        <div class="font-bold">Kategori</div>
                        <div class="">
                            {{ $archiveUU->category->nama_kategori }}
                        </div>
                    </div>
                    <div class="py-3">
                        <div class="font-bold">Status</div>
                        <div class="">
                            @if ($archiveUU->status == 1)
                                Belum Verifikasi
                            @elseif($archiveUU->status == 2)
                                Tidak berlaku
                            @elseif($archiveUU->status == 3)
                                Berlaku
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-white max-h-screen overflow-y-scroll relative">
                <div class="mt-3 ml-5 sticky top-3">
                    <div id='ck-button' class=' ck-button rounded-full border px-3 bg-cyan-500 hover:bg-cyan-600 hover:text-white'>
                        <label>
                            <input class='checkboxes ' type='checkbox' value='single' onchange='toggleSingleView(this)'><span><i class='bx bx-shuffle'></i></span>
                        </label>
                    </div>
                </div>
                <div class="p-5">
                    <div id="single-2" class="mt-5 grid gap-3">
                        @foreach ($simplePasal as $item)
                            @if ($item['uud_content']['count'] > 0)
                                <div class='p-5 bg-white border border-slate-300 rounded-xl ease-in-out duration-300 hover:shadow-lg'>
                                    <a data-bs-toggle='collapse' href='#collapseExample{{ $item['id'] }}' role='button' aria-expanded='false' aria-controls='collapseExample{{ $item['id'] }}'>
                                        <div class='grid grid-cols-[1fr_auto] gap-2'>
                                            <div class=''>
                                                <div class='text-sm capitalize font-bold'>{{ $item['uud_id'] }}</div>
                                            </div>
                                            <div class='grid place-items-center'><i class='bx bxs-chevron-down-circle text-sky-600 text-lg'></i></div>
                                        </div>
                                    </a>
                                    <div class='collapse' id='collapseExample{{ $item['id'] }}'>
                                        <div class='pt-4'>
                                            {!! $item['uud_content']['content'] !!}
                                        </div>
                                    </div>
                                    <div class='pt-3 mt-2 flex justify-between items-center border-t border-t-slate-100'>
                                        <div class='{{ $item['uud_content']['count'] > 0 ? 'font-bold text-cyan-600' : '' }}'>
                                            Disebut {{ $item['uud_content']['count'] }} kali
                                        </div>
                                        <div class='flex items-center gap-3'>
                                            <div id='ck-button' class='ck-button rounded-full border px-3 hover:bg-slate-300 hover:text-slate-800'>
                                                <label>
                                                    <input class='checkboxes ' type='checkbox' value='{{ $item['id'] }}}' onchange='toggleChecked(this)'><span>Check</span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                    <div id="single-1">
                        @if ($penuh)
                            <div id="penuh" class=" text-center">
                                {{ $penuh }}
                            </div>
                        @else
                            <div class="font-bold">
                                Belum ada data untuk Undang-Undang ini.
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="sticky bottom-5 left-5 flex justify-start mt-5 ml-7 md:ml-20">
        <label for="selectedPasalModal">
            <div id="checked-counter-container" class="hidden backdrop-blur-sm h-11 w-11 text-center align-middle border border-slate-300 bg-white rounded-md relative cursor-pointer hover:shadow-lg">
                <div class="absolute h-4 w-4 text-center -top-1 -right-2 rounded-full bg-sky-600 text-sky-600 text-xs font-bold animate-ping">
                    0
                </div>
                <div id="checked-counter" class="absolute h-4 w-4 text-center -top-1 -right-2 rounded-full bg-sky-600 text-white text-xs font-bold">
                    0
                </div>
                <i class='bx bxs-select-multiple text-slate-400 text-3xl'></i>
            </div>
        </label>
    </div>

    <!-- Put this part before </body> tag -->
    <input type="checkbox" id="selectedPasalModal" class="modal-toggle" />
    <div class="modal modal-bottom sm:modal-middle">
        <div class="modal-box bg-white text-slate-900">
            <div class="grid grid-cols-[1fr_auto]">
                <h3 class="font-bold text-lg">Pasal Terpilih</h3>
                <label for="selectedPasalModal" class="p-0 m-0 text-sky-600 rounded-full w-5 h-5 hover:bg-sky-600 hover:text-white text-center align-middle">
                    <i class='bx bx-x'></i>
                </label>
            </div>
            <div class="pt-5 grid gap-5">
                <div id="selectedPasalContainer" class="font-bold py-5">
                    Unduh <span id="countSelectedPasal">0</span> pasal terpilih?
                </div>
            </div>
            <div class="pt-3 flex gap-3 justify-end border-t border-t-slate-200 ">
                <button id="draftExportPDF" class="btn-rounded-solid-cyan">PDF</ id="draftExportPDF">
                    <button id="draftExportWord" class="btn-rounded-solid-cyan">Word</ id="draftExportWord">
            </div>
        </div>
    </div>
@endsection



@section('datatable')
    <script>
        let totalData
        $(document).ready(function() {
            let penuh = "<html>{{ json_encode($penuh) }}</html>"
            totalData = <?php json_encode($simplePasal); ?>
            $('#penuh').html(
                // create an element where the html content as the string
                $('<div/>', {
                    html: penuh
                    // get text content from element for decoded text  
                }).text()
            )
        });

        function toggleSingleView(el) {
            if ($(el).prop("checked")) {
                $(el).attr("checked", false);
                $('#single-2').hide()
                $('#single-1').show()
                $(el).parents().eq(1).toggleClass("bg-cyan-600");
            } else {
                $(el).attr("checked", true);
                $('#single-2').show()
                $('#single-1').hide()
                $(el).parents().eq(1).toggleClass("bg-cyan-600");
            }
        }

        let selected = []

        function toggleChecked(el) {
            if ($(el).prop("checked")) {
                $(el).attr("checked", false);
                $(el).siblings("span").html("Checked");
                $(el).parents().eq(1).toggleClass("bg-cyan-600");
            } else {
                $(el).attr("checked", true);
                $(el).siblings("span").html("Check");
                $(el).parents().eq(1).toggleClass("bg-cyan-600");
            }
            console.log(totalData)
            countSelected()
        }

        function countSelected() {
            selected = []
            $("input:checkbox.checkboxes").each(function() {
                if ($(this).prop('checked')) {
                    selected.push($(this).val())
                }
            })
            $('#checked-counter').html(selected.length)
            $('#countSelectedPasal').html(selected.length)
            if (selected.length > 0) {
                $('#checked-counter-container').show()
            } else {
                $('#checked-counter-contianer').hide()
            }
            console.log(selected)
            let cont = $('#selectedPasalContainer')
        }
        $('#draftExportPDF').click(function() {
            let url = "{{ route('draft.export-pasal-pdf') }}"
            let param = selected.toString()
            let paramUrl = `${url}?type=pdf&pasals=${param}`
            // window.location.href = paramUrl
            window.open(paramUrl, "_blank")
        })
        $('#draftExportWord').click(function() {
            let url = "{{ route('draft.export-pasal-word') }}"
            let param = selected.toString()
            let paramUrl = `${url}?type=word&pasals=${param}`
            // window.location.href = paramUrl
            window.open(paramUrl, "_blank")
        })
    </script>
@endsection
