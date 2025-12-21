@extends('layout.main')
@section('title', 'About Us')

@section('content')
<div class="w-full px-6 py-6 mx-auto">

    <!-- Bagian Profil Usaha -->
    <div class="bg-white shadow-lg rounded-2xl p-8 border border-slate-200 mb-10">
        <h2 class="text-2xl font-bold text-slate-700 mb-4">About Us</h2>
        <p class="text-slate-600 leading-relaxed">
            Distro 1964 merupakan sebuah platform yang dikembangkan untuk membantu proses
            pencatatan, pengelolaan data, serta pembuatan laporan secara lebih mudah, cepat,
            dan akurat. Sistem ini dibangun untuk mendukung aktivitas operasional agar lebih
            efisien dan terstruktur.
        </p>
    </div>

    <!-- Our Team Section -->
    <div class="bg-white shadow-lg rounded-2xl p-8 border border-slate-200">
        <h2 class="text-3xl font-bold text-slate-800 mb-8 tracking-wide">Our Team</h2>

        <!-- KETUA + PENANGGUNG JAWAB -->
        <h3 class="text-xl font-semibold text-slate-700 mb-4 border-l-4 border-purple-500 pl-3">
            Ketua & Penanggung Jawab
        </h3>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-10">
            <!-- Ketua -->
            <div class="p-6 rounded-xl bg-gradient-to-br from-slate-50 to-white shadow hover:shadow-md transition-all border border-slate-200">
                <h4 class="text-lg font-bold text-slate-800">Dr. Nyata Nugraha, SE, M.Si, Akt</h4>
                <p class="text-sm text-slate-500 mt-1">Ketua Tim Penelitian</p>
            </div>

            <!-- Penanggung Jawab 1 -->
            <div class="p-6 rounded-xl bg-gradient-to-br from-slate-50 to-white shadow hover:shadow-md transition-all border border-slate-200">
                <h4 class="text-lg font-bold text-slate-800">Afiat Sadida, S.Kom., MM.</h4>
                <p class="text-sm text-slate-500 mt-1">Penanggung Jawab Pengembangan Sistem</p>
            </div>

            <!-- Penanggung Jawab 2 -->
            <div class="p-6 rounded-xl bg-gradient-to-br from-slate-50 to-white shadow hover:shadow-md transition-all border border-slate-200">
                <h4 class="text-lg font-bold text-slate-800">Iwan Budiyono, SE., M.Si., Akt</h4>
                <p class="text-sm text-slate-500 mt-1">Penanggung Jawab Akuntansi dan UMKM</p>
            </div>
        </div>

        <!-- ANGGOTA AKUNTANSI -->
        <h3 class="text-xl font-semibold text-slate-700 mb-4 border-l-4 border-blue-500 pl-3">
            Anggota Bidang Akuntansi
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-10">
            <div class="p-6 rounded-xl bg-gradient-to-br from-slate-50 to-white shadow hover:shadow-md transition-all border border-slate-200">
                <h4 class="text-lg font-bold text-slate-800">Mezzaluna Dissa Sayyidina</h4>
                <p class="text-sm text-slate-500 mt-1">Anggota Bidang Akuntansi</p>
            </div>
            <div class="p-6 rounded-xl bg-gradient-to-br from-slate-50 to-white shadow hover:shadow-md transition-all border border-slate-200">
                <h4 class="text-lg font-bold text-slate-800">Salsabila Ashofa</h4>
                <p class="text-sm text-slate-500 mt-1">Anggota Bidang Akuntansi</p>
            </div>
        </div>

        <!-- ANGGOTA SISTEM -->
        <h3 class="text-xl font-semibold text-slate-700 mb-4 border-l-4 border-green-500 pl-3">
            Anggota Bidang Sistem Informasi
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <div class="p-6 rounded-xl bg-gradient-to-br from-slate-50 to-white shadow hover:shadow-md transition-all border border-slate-200">
                <h4 class="text-lg font-bold text-slate-800">Mutiara Maulida Nafilatu Zahroh</h4>
                <p class="text-sm text-slate-500 mt-1">Anggota Bidang Sistem Informasi</p>
            </div>
            <div class="p-6 rounded-xl bg-gradient-to-br from-slate-50 to-white shadow hover:shadow-md transition-all border border-slate-200">
                <h4 class="text-lg font-bold text-slate-800">Violeta Chaitra Orvala</h4>
                <p class="text-sm text-slate-500 mt-1">Anggota Bidang Sistem Informasi</p>
            </div>
            <div class="p-6 rounded-xl bg-gradient-to-br from-slate-50 to-white shadow hover:shadow-md transition-all border border-slate-200">
                <h4 class="text-lg font-bold text-slate-800">Alisha Rachma Nurfarizka</h4>
                <p class="text-sm text-slate-500 mt-1">Anggota Bidang Sistem Informasi</p>
            </div>
        </div>
    </div>
</div>
@endsection
