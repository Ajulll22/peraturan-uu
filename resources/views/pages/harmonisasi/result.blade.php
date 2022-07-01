@extends('layouts.app-layout')

@section('content')
    <div class="p-5 lg:p-14 lg:py-7">
        <div class="grid lg:grid-cols-[1fr_3fr] gap-5 rounded min-w-0">
            <div></div>
            <div class="bg-white rounded shadow-lg">
                <div class="p-5 py-3 mb-3 flex justify-between items-center border-b-2 border-b-slate-200 font-bold">
                    <div>
                        Hasil Harmonisasi Undang-Undang
                    </div>
                </div>
                <div class="p-5 overflow-auto">
                    <table class="mt-3 overflow-auto" id="myTable" style="width:100%; padding-top: 1em;  padding-bottom: 1em;">
                        <thead class="stripes hover">
                            <tr class="">
                                <th class="bg-sky-900 text-white">No.</th>
                                <th class="bg-sky-900 text-white">Peraturan</th>
                                <th class="bg-sky-900 text-white">Tentang</th>
                                <th class="bg-sky-900 text-white">Similaritas</th>
                                <th class="bg-sky-900 text-white">
                                    <i class='bx bxs-download'></i>
                                </th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('datatable')
    <script>
        // DISPLAY THE TABLE
        $(document).ready(function() {
            var events = $('#events');
            let table = $('#myTable').DataTable({
                // dataTable query
                processing: true,
                serverSide: false,
                ajax: '{{ route('harmonisasi.result-data') }}',
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex'
                    },
                    {
                        data: 'uu',
                        name: 'uu'
                    },
                    {
                        data: 'tentang',
                        name: 'tentang'
                    },
                    {
                        data: 'presentase',
                        name: 'presentase'
                    },
                    {
                        data: 'file_arsip',
                        name: 'file_arsip'
                    }
                ],
                order: [],
                fixedHeader: {
                    header: false
                },
                columnDefs: [{
                        targets: '_all',
                        defaultContent: '',
                    },
                    {
                        targets: [2],
                        className: "text-center font-bold"
                    },
                    {
                        targets: [3],
                        orderable: false,
                        className: "text-center white-space-nowrap",
                    },
                ],
                stripeClasses: []
            });

            // APPLY FILTER
            let status = $('select[name=category]')

            $('#applyFilter').click(function() {
                let url = "{{ route('archive.data') }}"
                let paramUrl = getParamUrl(url);
                table.ajax.url(paramUrl)
                    .load();
            })

            function getParamUrl(url) {
                url = addParameter(url, 'category', status.val(), false)

                return url;
            }
        });
    </script>
@endsection
