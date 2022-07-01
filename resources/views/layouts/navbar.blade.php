<div class="hidden lg:flex px-12 bg-white justify-between items-center">
    @include('layouts.page-logo')
    <div>
        {{-- NAVIGATION MENU --}}
        <div class="inline-block">
            @foreach ($navs as $nav)
                <a href="{{ $nav['route'] }}">
                    <button class="p-3 py-5 font-medium hover:text-cyan-600 border-y-4 border-y-transparent {{ $active == $nav['title'] ? 'border-b-cyan-600 font-bold text-cyan-600' : '' }}">
                        {{ $nav['title'] }}
                    </button>
                </a>
            @endforeach
            @auth
                <div class="dropdown dropdown-end dropdown-hover">
                    <label tabindex="0" class="p-3 py-5 font-medium hover:text-cyan-600 border-y-4 border-y-transparent {{ $active == 'Admin' ? 'border-b-cyan-600 font-bold text-cyan-600' : '' }}">Admin</label>
                    <ul tabindex="0" class="dropdown-content menu p-2 shadow-lg bg-white rounded-md translate-y-2">
                        <li class="text-slate-700"><a href="{{ route('account.index') }}" class="whitespace-nowrap">Kelola Akun</a></li>
                        <li class="text-slate-700"><a href="{{ route('category.index') }}" class="whitespace-nowrap">Kelola Rumpun</a></li>
                        <li class="text-slate-700"><a href="{{ route('archive.index') }}" class="whitespace-nowrap">Kelola Undang-Undang</a></li>
                    </ul>
                </div>
            @endauth
        </div>
        {{-- IF USER LOGGED IN --}}
        <div class="inline-block ml-5 pl-5 border-l border-l-slate-200">
            @auth
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button class="p-1.5 px-7 text-red-500 rounded-full bg-white border border-red-600 hover:text-white hover:bg-red-600 focus:outline-none focus:ring focus:ring-red-200">
                        Logout
                    </button>
                </form>
            @endauth
            {{-- IF USER NOT LOGGED IN --}}
            @guest
                <a href="{{ route('login') }}">
                    <button class="btn-rounded-solid-cyan">
                        Login
                    </button>
                </a>
            @endguest
        </div>
    </div>
</div>

<div class="lg:hidden px-5 py-3 flex justify-between items-center bg-white">
    @include('layouts.page-logo')
    <label for="smallMenuModal" class="p-1 px-2 rounded-md bg-cyan-600 focus:outline-none focus:ring focus:ring-cyan-200">
        <i class='bx bx-menu-alt-left text-white text-2xl'></i>
    </label>
</div>
<!-- SMALL NAVIGATION MENU MODAL -->
<input type="checkbox" id="smallMenuModal" class="modal-toggle">
<label for="smallMenuModal" class="modal cursor-pointer">
    <label class="p-5 bg-white rounded-md modal-box relative">
        <div class="flex justify-between">
            <h2 class="text-lg w-10/12 font-bold">Menu</h2>
            <label for="smallMenuModal" class="rounded-full text-red-500 cursor-pointer">
                <i class='bx bx-x text-3xl'></i>
            </label>
        </div>
        <div class="flex flex-col items-start">
            @foreach ($navs as $nav)
                <a href="{{ $nav['route'] }}" class="w-full">
                    <button class="py-4 w-full text-left hover:font-bold hover:text-cyan-600 {{ $active == $nav['title'] ? 'font-bold text-cyan-600' : '' }}">
                        {{ $nav['title'] }}
                    </button>
                </a>
            @endforeach
            <a class="py-4 w-full text-left hover:font-bold hover:text-cyan-600 {{ $active == 'Admin' ? 'font-bold text-cyan-600' : '' }}" data-bs-toggle="collapse" href="#adminNavCollapse" role="button" aria-expanded="false" aria-controls="adminNavCollapse">Admin</a>
            <div class="collapse" id="adminNavCollapse">
                <ul tabindex="0" class="p-2 pl-5 shadow-lg bg-white rounded-md ">
                    <li class="list-disc text-slate-700"><a href="{{ route('account.index') }}" class="whitespace-nowrap">Kelola Akun</a></li>
                    <li class="list-disc text-slate-700"><a href="{{ route('category.index') }}" class="whitespace-nowrap">Kelola Rumpun</a></li>
                    <li class="list-disc text-slate-700"><a href="{{ route('archive.index') }}" class="whitespace-nowrap">Kelola Undang-Undang</a></li>
                </ul>
            </div>
        </div>
        <div class="py-5 flex gap-3 border-t border-t-slate-300">
            @guest
                <a href="{{ route('login') }}">
                    <button class="btn-rounded-solid-cyan">
                        Login
                    </button>
                </a>
            @endguest
            @auth
                <form action="{{ route('logout') }}" method="POST">
                    <button class="btn-rounded-outline-red">
                        Logout
                    </button>
                </form>
            @endauth
        </div>
    </label>
</label>
