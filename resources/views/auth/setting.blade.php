<x-layout title="Redirecting">
    <main class="flex-1 container mx-auto max-w-4xl p-4 md:p-6 text-white min-h-screen">
        <div class="py-12 px-6 text-center">
            <p class="mb-4">Halaman pengaturan lama. Mengalihkan ke halaman pengaturan terbaru…</p>
            <a href="{{ route('auth.settings') }}" class="underline text-blue-400">Jika tidak dialihkan, klik di sini.</a>
        </div>
    </main>
</x-layout>
