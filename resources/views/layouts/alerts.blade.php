<div class="absolute right-5 top-5 lg:right-14 lg:top-16 max-w-md">
    @if ($errors->any())
        <div class="grid grid-cols-[1fr_auto] items-start gap-8 p-3.5 bg-red-400 rounded alert alert-dismissible fade show" role="alert">
            <div class="text-white">
                <ul>
                    <div class="font-bold">Some errors has occured:</div>
                    @foreach ($errors->all() as $error)
                        <li class="ml-5 list-disc">{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            <button type="button" class="h-auto py-0 px-3 text-white font-bold" data-bs-dismiss="alert">x</button>
        </div>
    @endif

    @if (session()->has('success'))
        <div class="grid grid-cols-[1fr_auto] items-start gap-8 p-3.5 bg-teal-400 rounded alert alert-dismissible fade show" role="alert">
            <div class="text-slate-700">
                {{ session('success') }}
            </div>
            <button type="button" class="h-auto py-0 px-3 text-slate-700 font-bold" data-bs-dismiss="alert">x</button>
        </div>
    @endif

    @if (session()->has('failed'))
        <div class="grid grid-cols-[1fr_auto] items-start gap-8 p-3.5 bg-red-400 rounded alert alert-dismissible fade show" role="alert">
            <div class="text-slate-700">
                {{ session('failed') }}
            </div>
            <button type="button" class="h-auto py-0 px-3 text-slate-700 font-bold" data-bs-dismiss="alert">x</button>
        </div>
    @endif
</div>
