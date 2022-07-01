@extends('layouts.app-layout')

@section('content')
    <div class="p-5 lg:p-14 lg:py-7">
        <div class="grid rounded min-w-0">

            <div class="bg-white rounded shadow-lg">
                <div class="p-5 py-3 mb-3 flex justify-between items-center border-b-2 border-b-slate-200 font-bold">
                    <div>
                        Akun
                    </div>
                    @auth
                        <a href="{{ route('account.create') }}">
                            <button class="btn-solid-cyan">
                                Akun Baru
                            </button>
                        </a>
                    @endauth
                </div>
                <div class="p-5 overflow-auto">
                    <table class="mt-3 overflow-auto" id="myTable" style="width:100%; padding-top: 1em;  padding-bottom: 1em;">
                        <thead class="stripes hover">
                            <tr class="">
                                <th class="bg-sky-900 text-white">Nama</th>
                                <th class="bg-sky-900 text-white">Email</th>
                                <th class="bg-sky-900 text-white">Role</th>
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
                serverSide: true,
                ajax: '{{ route('account.data') }}',
                columns: [{
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'email',
                        name: 'email'
                    },
                    {
                        data: 'role',
                        name: 'role'
                    },
                    {
                        data: 'account-actions',
                        name: 'account-actions'
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
                        targets: [3],
                        orderable: false,
                        className: "text-center whitespace-nowrap",
                    },
                ],
                stripeClasses: []
            });
        });
    </script>
@endsection
