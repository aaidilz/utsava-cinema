@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto py-12 px-6">
    <h2 class="text-2xl font-bold mb-6">Pengaturan Akun</h2>

    <div class="bg-white p-6 rounded-xl shadow">
        <form>
            <div class="mb-4">
                <label class="block text-sm font-medium">Nama</label>
                <input type="text"
                       class="w-full border rounded-lg px-3 py-2 mt-1"
                       value="{{ auth()->user()->name }}">
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium">Email</label>
                <input type="email"
                       class="w-full border rounded-lg px-3 py-2 mt-1"
                       value="{{ auth()->user()->email }}">
            </div>

            <div class="mb-6">
                <label class="block text-sm font-medium">Password Baru</label>
                <input type="password"
                       class="w-full border rounded-lg px-3 py-2 mt-1">
            </div>

            <button class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700">
                Simpan Perubahan
            </button>
        </form>
    </div>
</div>
@endsection
