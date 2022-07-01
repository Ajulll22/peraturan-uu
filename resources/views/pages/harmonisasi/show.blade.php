@extends('layouts.app-layout')

@section('content')
<div class="p-5 lg:p-14 lg:py-7">
    <div class="grid lg:grid-cols-2 rounded-md shadow-xl">
        <div class="bg-white">

            <div class="p-5">
                <div class="grid place-content-center ...">
                    <p class="text-2xl font-bold">{{ $pembanding->judul_ruu }}</p>
                </div>
                <div class="grid place-content-center ...">
                    <p class="text-xl ">{{ $pembanding->tentang_ruu }}</p>
                </div>
                <br><br>
                <?php $tempPasal = ''; ?>
                @forelse ($pasals as $pasal)
                <?php
                $arrPasal = explode(' ', $pasal->section_ruu);
                $pasalTitle = count($arrPasal) <= 1 ? $pasal->section_ruu : $arrPasal[0];
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
                    <div class="group relative p-3 border-b border-l border-slate-300" id="sumber{{ $pasal->id_ruu_pasal }}" onclick="showMatch({{ $pasal->id_ruu_pasal }})">
                        <div class="font-bold capitalize">{{ str_replace('~', ' ', $pasal->section_ruu) }}</div>
                        <div>
                            <?php $pasal->content_ruu = str_replace('<br>', "\r\n", $pasal->content_ruu); ?>
                            <div class="whitespace-pre-wrap">{{ strip_tags($pasal->content_ruu) }}</div>
                        </div>
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
        <div class="p-5 bg-slate-100">
            <div class="mb-5 flex justify-center">
                <p class="text-2xl font-bold">Harmonisasi Materi Muatan</p>
            </div>
            <div class="grid gap-2 divide-y divide-slate-300">
                <div class="py-3">
                    <div class="font-bold">{{ $archive->uu }}</div>
                    <div class="text-sm">
                        {{ $archive->tentang }}
                    </div>
                </div>
                <div id="match-pasal" >

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
    <script src="https://code.jquery.com/jquery-3.5.0.js"></script>
    <script src="https://unpkg.com/@popperjs/core@2.9.1/dist/umd/popper.min.js" charset="utf-8"></script>
    <script type="text/javascript">
        var tooltipTriggerList = [].slice.call(
            document.querySelectorAll('[data-bs-toggle="tooltip"]')
        );
        var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new Tooltip(tooltipTriggerEl);
        });
    </script>
    <script>
        const data = [
            @foreach($pasals as $pasal)
                {
                    "hasil": [
                        @foreach($pasal->hasil as $hasil)
                            {
                                "id": {{ $hasil->id }},
                                "presentase": {{ $hasil->presentase }},
                                "uud_content": `{{ strip_tags($hasil->uud_content) }}`,
                                "uud_id": `{{ $hasil->uud_id }}`,
                            },
                        @endforeach
                    ],
                    "id_ruu_pasal": {{$pasal->id_ruu_pasal}},
                    "kata": `{{$pasal->kata}}`,
                },
            @endforeach
        ];

        function showMatch(id_ruu) {
            const match = data.find((val) => val.id_ruu_pasal == id_ruu);
            pilih = match.hasil
            kata = match.kata
            kataArr = kata.split(" ")
            var uniqueKata = [];
            $.each(kataArr, function(i, el){
                if($.inArray(el, uniqueKata) === -1) uniqueKata.push(el);
            });
            for(var i = 0; i < uniqueKata.length; i++){
            }
            console.log(uniqueKata)

            let row = ''
            match.hasil.forEach((element) => {
                row += 
                    "<div class='py-3'>" +
                        `<a data-bs-toggle='collapse' href='#collapseExample${element.id}' role='button' aria-expanded='false' aria-controls='collapseExample'>` +
                            "<div class='grid grid-cols-[1fr_auto] gap-2'>" +
                                "<div class=''>" +
                                    `<div class='text-lg font-bold'>${element.uud_id}</div>` +
                                    `<div class='text-sm capitalize'>${element.presentase}%</div>` +
                                "</div>" +
                                "<div class='grid place-items-center'><i class='bx bxs-chevron-down-circle text-sky-600 text-lg'></i></div>" +
                            "</div>" +
                        "</a>" +
                        `<div class='collapse' id='collapseExample${element.id}'>` +
                            "<div class='pt-4'>" +
                                element.uud_content +
                                "<div class='flex items-center gap-3'>" +
                                    "<div id='ck-button' class='ck-button rounded-full border px-3 hover:bg-slate-300 hover:text-slate-800'>" +
                                        "<label>" +
                                        `<input class='checkboxes ' type='checkbox' value='${element.id}' onchange='toggleChecked(this)'><span>Check</span>` +
                                        "</label>" +
                                    "</div>" +
                                "</div>" +
                            "</div>" +
                        "</div>" +
                    "</div>" ;
            });
            $("#match-pasal").html(row);
            let text = document.getElementById("match-pasal").innerHTML;
            for(var i = 0; i < uniqueKata.length; i++){
                let re = new RegExp(uniqueKata[i],"gi"); // search for all instances
                text = text.replace(re, `<mark style="background-color: yellow;">${uniqueKata[i]}</mark>`);  
            }
            document.getElementById("match-pasal").innerHTML = text;
            
            let sumberText = document.getElementById(`sumber${id_ruu}`).innerHTML;
            for(var i = 0; i < uniqueKata.length; i++){
                let re = new RegExp(uniqueKata[i],"gi"); // search for all instances
                sumberText = sumberText.replace(re, `<mark style="background-color: yellow;">${uniqueKata[i]}</mark>`);
            }
            document.getElementById(`sumber${id_ruu}`).innerHTML = sumberText;
            window.scrollTo(0, 0);

        }



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
        };

        $('#draftExportPDF').click(function() {
            let url = "{{ route('draft.export-pasal-pdf') }}"
            let param = selected.toString()
            let paramUrl = `${url}?type=pdf&pasals=${param}`
            // window.location.href = paramUrl
            window.open(paramUrl, "_blank")
        });
        $('#draftExportWord').click(function() {
            let url = "{{ route('draft.export-pasal-word') }}"
            let param = selected.toString()
            let paramUrl = `${url}?type=word&pasals=${param}`
            // window.location.href = paramUrl
            window.open(paramUrl, "_blank")
        });
    </script>


    @endsection