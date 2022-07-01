@extends('layouts.app-layout')

@section('content')
    <div class="p-5 lg:p-14 lg:py-7">
        <div class="grid lg:grid-cols-[1fr_3fr] gap-5 rounded overflow-x-scroll">
            <div class="">
                <div class="p-5 px-0 flex flex-col gap-5">
                    <div class="flex flex-col gap-2">
                        <label for="category" class="font-semibold">Kategori</label>
                        <select name="category" class="w-full input-rounded-cyan">
                            <option value="">--Pilih Kategori--</option>
                            @foreach ($categories as $item)
                                <option value="{{ $item->kategori_id }}">{{ $item->nama_kategori }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex flex-col gap-2">
                        <label for="category" class="font-semibold">Tahun</label>
                        <input type="text" name="tahun">
                    </div>

                    <div class="flex justify-end">
                        <button id="applyFilter" class="btn-rounded-outline-cyan">
                            Terapkan Filter
                        </button>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded shadow-lg">
                <div class="p-5 py-3 mb-3 flex justify-between items-center border-b-2 border-b-slate-200 font-bold">
                    <div>
                        Arsip Undang-Undang
                    </div>
                    @auth
                        <a href="{{ route('archive.create') }}" class="btn-solid-cyan">
                            Arsip Baru
                        </a>
                    @endauth
                </div>
                <div class="p-5 overflow-auto">
                    <table class="mt-3 overflow-auto" id="myTable" style="width:100%; padding-top: 1em;  padding-bottom: 1em;">
                        <thead class="stripes hover">
                            <tr class="">
                                <th class="bg-sky-900 text-white">No.</th>
                                <th class="bg-sky-900 text-white">Peraturan</th>
                                <th class="bg-sky-900 text-white">Tentang</th>
                                <th class="bg-sky-900 text-white">Kategori</th>
                                <th class="bg-sky-900 text-white">Status</th>
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
                ajax: '{{ route('archive.data') }}',
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
                        data: 'id_kategori',
                        name: 'id_kategori'
                    },
                    {
                        data: 'status',
                        name: 'status',
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
                        targets: [0, 3, 4],
                        orderable: false,
                    },
                    {
                        targets: [4],
                        orderable: false,
                        className: "text-center",
                    },
                    {
                        targets: [3],
                        className: "text-center whitespace-nowrap"
                    }
                ],
                stripeClasses: []
            });

            // APPLY FILTER
            let kategori = $('select[name=category]')
            let tahun = $('input[name=tahun]')

            $('#applyFilter').click(function() {
                let url = "{{ route('archive.data') }}"
                let paramUrl = getParamUrl(url);
                table.ajax.url(paramUrl)
                    .load();
            })

            function getParamUrl(url) {
                url = addParameter(url, 'category', kategori.val(), false)
                url = addParameter(url, 'tahun', tahun.val(), false)

                return url;
            }
        });
    </script>
@endsection
