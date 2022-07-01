@extends('layouts.app-layout')

@section('content')
    <div class="p-5 lg:p-14 lg:py-7">
        <div class="grid rounded min-w-0">
            <div class="bg-white rounded-lg shadow-lg">
                <div class="p-5 py-2 bg-cyan-600 text-white font-bold text-lg rounded-tl-lg rounded-tr-lg">
                    Edit Akun
                </div>
                <div class="p-5">
                    <form action="{{ route('account.update', $account->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class=" pb-2 grid md:grid-cols-2 lg:grid-cols-2 gap-5">
                            <div class="grid">
                                <label for="name" class="text-sm">Nama</label>
                                <input type="text" name="name" class="p-1 input-rounded-cyan border text-sm border-cyan-600/40" value="{{ $account->name }}" autofocus>
                            </div>
                            <div class="grid">
                                <label for="email" class="text-sm">Email</label>
                                <input type="email" name="email" class="p-1 input-rounded-cyan border text-sm border-cyan-600/40" value="{{ $account->email }}">
                            </div>
                            <div class="grid">
                                <label for="password" class="text-sm">Password</label>
                                <input type="password" name="password" class="p-1 input-rounded-cyan border text-sm border-cyan-600/40" value="">
                            </div>
                            <div class="grid">
                                <label for="password" class="text-sm">Role</label>
                                <select name="role" class="p-1 input-rounded-cyan border text-sm border-cyan-600/40">
                                    @foreach ($roles as $role)
                                        <option value="{{ $role }}" {{ old('role') == $role ? 'selected' : '' }}>{{ $role }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="mt-5 pt-3 flex justify-end border-t border-t-slate-300">
                            <button class="btn-rounded-solid-cyan">
                                Simpan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('datatable')
    <script>
    </script>
@endsection
