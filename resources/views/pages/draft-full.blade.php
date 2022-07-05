<div class="w-full p-3 mt-5 bg-white rounded shadow-lg">
    <table class="mt-3 table-auto" id="myTable" style="width:100%; padding-top: 1em;  padding-bottom: 1em;">
        <thead class="stripes hover">
            <tr class="">
                <th class="bg-sky-900 text-white">No.</th>
                <th class="bg-sky-900 text-white"></th>
                <th class="bg-sky-900 text-white">Peraturan</th>
                <th class="bg-sky-900 text-white">Tentang</th>
                <th class="bg-sky-900 text-white">Kategori</th>
                <th class="bg-sky-900 text-white">
                    Similaritas
                </th>
            </tr>
        </thead>
    </table>
</div>

@section('datatable')
    <script>
        // DISPLAY THE TABLE
        $(document).ready(function() {
            var events = $('#events');
            let table = $('#myTable').DataTable({
                // dataTable query
                processing: true,
                serverSide: false,
                ajax: '{{ route('draft.data') }}',
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex'
                    },
                    {
                        data: 'detail',
                        name: 'detail'
                    },
                    {
                        data: 'judul_arsip',
                        name: 'judul_arsip'
                    },
                    {
                        data: 'jenis_arsip',
                        name: 'jenis_arsip'
                    },
                    {
                        data: 'kategori',
                        name: 'kategori'
                    },
                    {
                        data: 'cosSim',
                        name: 'cosSim'
                    },
                ],
                order: [],
                fixedHeader: {
                    header: false
                },
                columnDefs: [{
                        targets: [0],
                        className: 'text-center align-top',
                        orderable: false,
                    },
                    {
                        targets: [1, 2, 3],
                        defaultContent: '',
                        className: 'align-top'
                    },
                    {
                        targets: [4],
                        className: 'text-center align-top'
                    },
                ],
                stripeClasses: []
            });

            // APPLY FILTER
            let theme = $('#theme')

            $('#applyFilter').click(function() {
                let url = "{{ route('draft.data') }}"
                let paramUrl = getParamUrl(url);
                table.ajax.url(paramUrl)
                    .load();
            })

            function getParamUrl(url) {
                url = addParameter(url, 'theme', theme.val(), false)

                return url;
            }
        });

        function redirectToDetail(id) {
            window.open("{{ route('draft.index') }}" + `/${id}?theme=` + $('#theme').val())
        }
    </script>
@endsection
