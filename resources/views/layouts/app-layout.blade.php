<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Peraturan UU</title>

    <link rel="shortcut icon" href="{{ asset('assets/img/logo-icon.png') }}" type="image/x-icon">


    <!--Regular Datatables CSS-->
    <link href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css" rel="stylesheet">
    <!--Responsive Extension Datatables CSS-->
    <link href="https://cdn.datatables.net/responsive/2.2.3/css/responsive.dataTables.min.css" rel="stylesheet">

    <link rel="stylesheet" href="https://unpkg.com/flowbite@1.4.7/dist/flowbite.min.css" />

    {{-- BOXICONS CDN --}}
    <link href='https://unpkg.com/boxicons@2.1.2/css/boxicons.min.css' rel='stylesheet'>
    {{-- TAILWIND CSS --}}
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    {{-- CHECKBOX BUTTON LIKE STYLE --}}
    <link rel="stylesheet" href="{{ asset('css/button-like-checkbox.css') }}">
    {{-- DATATABLE CSS --}}
    <link rel="stylesheet" href="{{ asset('css/dataTable.css') }}">
    {{-- FONT CDN --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lato&family=Poppins&family=Rubik:wght@700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://unpkg.com/dropzone@5/dist/min/dropzone.min.css" type="text/css" />
</head>

@yield('script-header')

<body class="min-h-screen text-slate-700 bg-gradient-to-tr from-slate-100 to-slate-50">

    <a href="#footer" id="scrollToBottom" class="grid place-items-center rounded-full h-11 w-11 bg-white border border-sky-400 fixed right-8 bottom-8 opacity-0 ease-out animate-bounce hover:shadow-lg text-sky-600 z-50">
        <i class='bx bx-down-arrow-alt text-3xl font-bold'></i>
    </a>

    <div class="grid grid-rows-[auto_1fr_auto] min-h-screen">
        <div id="loader" class="fixed hidden w-screen h-screen">
            <div class="grid place-items-center h-full ">
                <div class="p-14 rounded-2xl text-white flex flex-col justify-center gap-4 bg-slate-800/60 backdrop-blur-sm">
                    <div class="text-center">
                        <i class='bx bx-loader-alt animate-spin text-5xl p-0 m-0'></i>
                    </div>
                    <div class="font-bold text-xl">
                        Loading...
                    </div>
                </div>
            </div>

        </div>
        {{-- NAVBAR --}}
        @include('layouts.navbar')

        {{-- VALIDATION ERROR --}}
        @include('layouts.alerts')

        {{-- MAIN CONTENT --}}
        {{-- <div class="p-5 lg:p-12 lg:py-7"> --}}
        @yield('content')
        {{-- </div> --}}
        {{-- FOOTER --}}
        <div id="footer" class="py-3 text-center text-sm bg-slate-200">
            Copyright &copy; {{ date('Y') }} Powered by Universitas Lampung
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/pdfjs-dist@2.13.216/build/pdf.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/v/bs5/dt-1.11.3/b-2.0.1/fc-4.0.1/sl-1.3.3/datatables.min.js"></script>
    <script src="https://unpkg.com/dropzone@5/dist/min/dropzone.min.js"></script>

    <script src="{{ asset('js/dataTableFilter.js') }}"></script>
    <script src="{{ asset('js/components.js') }}"></script>
    <script src="https://unpkg.com/flowbite@1.4.7/dist/flowbite.js"></script>

    @yield('datatable')

</body>

</html>
