@extends('layouts.app-layout')

@section('content')
    <div class="snap-y">
        <div class="py-10 lg:p-0 lg:h-screen relative bg-white overflow-hidden">
            <div class="-z-0 absolute -top-0 -left-0 h-1/2 w-1/2 bg-sky-400/30 rounded-tr-full rounded-br-full blur-3xl"></div>
            <div class="-z-0 absolute bottom-0 -right-20 h-full w-full bg-sky-400/30 rounded-tl-full blur-3xl"></div>
            <div class="z-0 relative h-full grid lg:grid-cols-2">
                <div class="hidden lg:grid place-items-center">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/f/fe/Garuda_Pancasila%2C_Coat_of_Arms_of_Indonesia.svg" alt="" class="w-1/2">
                </div>
                <div class="z-0 px-8 grid content-center">
                    <b class="bg-contain pb-1 mb-8 text-4xl text-sky-900 font-bold text-left" style="font-family: 'Rubik', sans-serif; background: url('{{ asset('assets/svg/underline.svg') }}') no-repeat; background-position-y: 100%;">
                        Aplikasi Omnilaw
                    </b>
                    <div class="mb-8 text-lg text-slate-900">
                        Aplikasi yang ditujukan untuk membantu efektivitas dan efisiensi perancangangan Undang-Undang, dengan dukungan informasi kemiripan antar Undang-Undang dan informasi spesifik per-pasal dalam berbagai tema Undang-Undang. Nikmati berbagai kemudahan dalam merancang Undang-Undang dengan menekan tombol masuk.
                    </div>
                    <a href="{{ route('draft.index') }}" class="btn-rounded-solid-cyan justify-self-start text-white font-semibold">
                        Mulai Drafting
                    </a>
                </div>
            </div>
        </div>
        <div class="py-14 lg:h-sceen grid grid-rows-[auto_1fr] bg-white relative">
            <div class="-z-0 absolute -top-0 -left-0 h-3/4 w-1/2 bg-sky-400/30 rounded-br-full blur-3xl"></div>
            <div class="-z-0 absolute bottom-0 -right-20 h-52 w-52 bg-sky-400/30 rounded-tl-full blur-3xl"></div>

            <div class="text-center text-xl" style="font-family: 'Rubik', sans-serif;">
                Services We
                <span class="pb-1 bg-contain text-sky-600" style="background: url('{{ asset('assets/svg/underline.svg') }}') no-repeat; background-position-y: 100%;">
                    Provided
                </span>
            </div>
            <div class="mt-14 grid grid-c] relative">
                {{-- <div class="hidden py-12 h-full border sticky top-0 bottom-0 md:gri place-items-center">
                    <img src="{{ asset('assets/img/services.svg') }}" class="w-4/6">
                </div> --}}
                <div class="lg:px-32 grid grid-cols-1 md:grid-cols-3 justify-items-center gap-5">
                    <div class="p-8 pb-16 max-w-xs bg-white rounded-lg border border-slate-400">
                        <div class="py-5 relative w-fit">
                            <div class="absolute -z-0 h-12 w-12 bg-sky-300 rounded-full top-0 -right-3"></div>
                            <i class='relative z-0 bx bxs-file-archive text-6xl text-sky-600'></i>
                        </div>
                        <div class="font-bold text-lg">Arsip UU</div>
                        <div class="mt-5">
                            Temukan data UU terkini sesuai dengan kebutuhan anda
                        </div>
                    </div>
                    <div class="p-8 pb-16 max-w-xs bg-white rounded-lg border border-slate-400">
                        <div class="py-5 relative w-fit">
                            <div class="absolute -z-0 h-12 w-12 bg-green-300 rounded-full top-0 -right-3"></div>
                            <i class='relative z-0 bx bxs-layer text-6xl text-green-600'></i>
                        </div>
                        <div class="font-bold text-lg">Drafting UU</div>
                        <div class="mt-5">
                            Penyusunan UU berdasarkan kata kunci yang memiliki kemiripan dengan pasal yang terkait!
                        </div>
                    </div>
                    <div class="p-8 pb-16 max-w-xs bg-white rounded-lg border border-slate-400">
                        <div class="py-5 relative w-fit">
                            <div class="absolute -z-0 h-12 w-12 bg-red-300 rounded-full top-0 -right-3"></div>
                            <i class='relative z-0 bx bxs-book-open text-6xl text-red-600'></i>
                        </div>
                        <div class="font-bold text-lg">Harmonisasi UU</div>
                        <div class="mt-5">
                            Lakukan perancangan Undang-Undang dengan dukungan pengecekan kemiripan antar Undang-Undang!
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="py-14 relative bg-white overflow-clip">
            <div class="z-0 hidden md:block absolute top-10 -right-20 h-64 w-64 border-[50px] border-slate-200 rounded-full bg-transparent"></div>
            <div class="z-0 hidden md:block absolute bottom-10 -left-10 h-52 w-52 border-[40px] border-slate-200 rounded-full bg-transparent"></div>
            <div class="z-50 relative">
                <div class="text-center text-xl" style="font-family: 'Rubik', sans-serif;">
                    Tim
                    <span class="pb-1 bg-contain text-sky-600" style="background: url('{{ asset('assets/svg/underline.svg') }}') no-repeat; background-position-y: 100%;">Omnilaw</span>
                </div>
                <div class="mt-5 text-center">
                    Brought to you by
                </div>
                {{-- <div class="pt-14 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 justify-items-center gap-8"> --}}
                <div class=" pt-16 flex justify-around flex-wrap gap-y-6">
                    <div class="grid basis-full md:basis-1/2 lg:basis-1/3 text-center justify-items-center ">
                        <img src="{{ asset('assets/img/testimonials-1.jpg') }}" class="rounded-full h-32 md:h-44 w-32 md:w-44">
                        <div class="font-bold text-lg my-3" style="font-family: 'rubik';">Rudy, S.H., LL.M., LL.D.</div>
                        <div class="text-center">Fakultas Hukum
                            <br>
                            Universitas Lampung
                        </div>
                    </div>
                    <div class="grid basis-full md:basis-1/2 lg:basis-1/3 text-center justify-items-center ">
                        <img src="{{ asset('assets/img/testimonials-2.jpg') }}" class="rounded-full h-32 md:h-44 w-32 md:w-44">
                        <div class="font-bold text-lg my-3" style="font-family: 'rubik';">Dr. Robi Cahyadi Kurniawan, S.I.P., M.A.</div>
                        <div class="text-center">Fakultas Sosial dan Politik
                            <br>
                            Universitas Lampung
                        </div>
                    </div>
                    <div class="grid basis-full md:basis-1/2 lg:basis-1/3 text-center justify-items-center ">
                        <img src="{{ asset('assets/img/testimonials-3.jpg') }}" class="rounded-full h-32 md:h-44 w-32 md:w-44">
                        <div class="font-bold text-lg my-3" style="font-family: 'rubik';">Aristoteles, S.Si., M.Si.</div>
                        <div class="text-center">Fakultas Matematika dan Ilmu Pengetahuan Alam
                            <br>
                            Universitas Lampung
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('datatable')
@endsection
