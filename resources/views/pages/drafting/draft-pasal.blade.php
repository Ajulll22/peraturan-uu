<div class="w-full mt-5">
    <div id="pasal-result" class="grid gap-5">
        <div class="text-center font-bold text-xl p-5 rounded-lg">
            <img src="{{ asset('assets/img/creative-team.png') }}" class="h-2/6 mx-auto mb-5" alt="">
            Masukkan tema untuk drafting...
        </div>
    </div>
    <div id="result-paginate" class="hidden flex justify-between items-center gap-3 mt-5">
        <div>Showing 1 to 10 of 12323 entries</div>
        <div class="flex justify-end gap-3">
            <button class="btn-rounded-solid-cyan text-sm">Previous</button>
            <button class="btn-rounded-solid-cyan text-sm">Next</button>
        </div>
    </div>
</div>

<div class="sticky bottom-5 left-5 flex justify-start mt-5">
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
            <div id="selectedPasalContainer" class="font-bold">
            </div>
        </div>
        <div class="pt-3 flex gap-3 justify-end border-t border-t-slate-200 ">
            <button id="draftExportPDF" class="btn-rounded-solid-cyan">PDF</ id="draftExportPDF">
                <button id="draftExportWord" class="btn-rounded-solid-cyan">Word</ id="draftExportWord">
        </div>
    </div>
</div>

@section('datatable')
    <script>
        // / DISPLAY THE TABLE
        let totalData = []

        $(document).ready(function() {
            // APPLY FILTER
            let theme = $("#theme");

            $("#applyFilter").click(function() {
                let url = "{{ route('draft.calc-pasal') }}";
                let paramUrl = getParamUrl(url);
                $.ajax({
                    type: "GET",
                    url: paramUrl,
                    success: function(response) {
                        renderData(response);
                    },
                    beforeSend: function() {
                        $('#loader').show()
                    },
                    complete: function() {
                        $('#loader').hide();
                    }
                });
            });

            function getParamUrl(url) {
                url = addParameter(url, "theme", theme.val().split('\n').join(' '), false);

                return url;
            }

            function renderData(data) {
                let pasalContainer = $("#pasal-result");
                let paginatedData = paginate(data, 150, 1);
                totalData = paginatedData

                if (paginatedData.length > 0) {
                    pasalContainer.html('')

                    paginatedData.forEach((element) => {
                        let pasalRow = ""
                        element.pasal.forEach((row) => {
                            let uudId = row.uud_id.trim()
                            uudId = uudId.toLowerCase().replace(/\b[a-z]/g, function(letter) { 
                                return letter.toUpperCase();
                            });
                            uudId = uudId.replaceAll(' ', ' > ')
                            uudId = uudId.replaceAll('~', ' ')                            
                            let res = 
                            "<div class='pt-3 mt-2 flex justify-between items-center border-t border-t-slate-100'>" +
                                `<div class='font-bold text-cyan-600'>${uudId}</div>` +
                                "<div class='flex items-center gap-3'>" +
                                    "<div id='ck-button' class='ck-button rounded-full border px-3 hover:bg-slate-300 hover:text-slate-800'>" +
                                        "<label>" +
                                        `<input class='checkboxes ' type='checkbox' value='${row.id}' onchange='toggleChecked(this)'><span>Check</span>` +
                                        "</label>" +
                                    "</div>" +
                                "</div>" +
                            "</div>" +
                            `<div>${row.uud_content}</div>` ;
                            pasalRow = pasalRow + res;
                        });
                        let comp =
                            "<div class='p-5 bg-white border border-slate-300 rounded-xl ease-in-out duration-300 hover:shadow-lg'>" +
                            `<a data-bs-toggle='collapse' href='#collapseExample${element.id_tbl_uu}' role='button' aria-expanded='false' aria-controls='collapseExample${element.id_tbl_uu}'>` +
                            "<div class='grid grid-cols-[1fr_auto] gap-2'>" +
                            "<div class=''>" +
                            `<div class='text-lg font-bold'>${element.uu} - <span class='text-cyan-600 '>${element.tentang}</span></div>` +
                            `<div class='text-sm capitalize'>Jumlah ${element.jumlah} Pasal</div>` +
                            `</div>` +
                            "<div class='grid place-items-center'><i class='bx bxs-chevron-down-circle text-sky-600 text-lg'></i></div></div>" +
                            "</a>" +
                            `<div class='collapse' id='collapseExample${element.id_tbl_uu}'>` +
                            pasalRow +
                            "</div>" +
                            "</div>";
                        pasalContainer.append(comp);
                    });
                    $totalPage = parseInt(data.length / 10)
                } else {
                    pasalContainer.html('')
                    let emptyContainer =
                        "<div class='text-center font-bold text-xl p-5 rounded-lg'>" +
                        "<img src='/assets/img/empty.svg' class='h-2/6 mx-auto mb-5'>" +
                        "Tidak ditemukan similaritas antar pasal" +
                        "</div>";
                    pasalContainer.append(emptyContainer)
                }

                kataArr = theme.val().split(" ")
                let text = document.getElementById("pasal-result").innerHTML;
                    for(var i = 0; i < kataArr.length; i++){
                        let re = new RegExp(kataArr[i],"gi"); // search for all instances
                        text = text.replace(re, `<mark style="background-color: yellow;">${kataArr[i]}</mark>`);  
                    }
                document.getElementById("pasal-result").innerHTML = text;
            }

            const paginate = (array, pageSize, pageNumber) => {
                return array.slice((pageNumber - 1) * pageSize, pageNumber * pageSize);
            };

        });

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

            countSelected()
        }

        function getTheme() {
            return $('#theme').val()
        }

        function countSelected() {
            selected = []
            $("input:checkbox.checkboxes").each(function() {
                if ($(this).prop('checked')) {
                    selected.push($(this).val())
                }
            })
            $('#checked-counter').html(selected.length)
            if (selected.length > 0) {
                $('#checked-counter-container').show()
            } else {
                $('#checked-counter-contianer').hide()
            }

            let cont = $('#selectedPasalContainer')
            cont.html('')
            totalData.forEach(function(element) {
                if (selected.includes(String(element.id))) {
                    let el =
                        "<div class='font-bold mt-3'>" +
                        "<div>" +
                        `${element.uu} - <span class='text-sky-600'>${element.tentang}</span>` +
                        "</div>" +
                        `<div class='font-normal text-sm text-slate-500'>${element.uud_id}</div>` +
                        "</div>";
                    cont.append(el)
                }
            })
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
