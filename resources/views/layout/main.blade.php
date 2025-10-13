<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link
    rel="apple-touch-icon"
    sizes="76x76"
    href="./assets/img/apple-icon.png" />
  <link rel="icon" type="image/png" href="./assets/img/favicon.png" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
  <!-- Select2 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

  <!-- Select2 JS -->
  <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

  <title>Distro 1964 - @yield('title')</title>
  @include('layout.partial.link')
  <!-- Nepcha Analytics (nepcha.com) -->
  <!-- Nepcha is a easy-to-use web analytics. No cookies and fully compliant with GDPR, CCPA and PECR. -->
  <script
    defer
    data-site="YOUR_DOMAIN_HERE"
    src="https://api.nepcha.com/js/nepcha-analytics.js"></script>
  <script src="//unpkg.com/alpinejs" defer></script>
  <script src="https://unpkg.com/flowbite@1.6.5/dist/flowbite.min.js"></script>
</head>

<body
  x-data
  x-init="Alpine.store('modal', { openAdd: false, openEdit: null })"
  class="m-0 font-sans text-base antialiased font-normal leading-default bg-gray-50 text-slate-500">
  @include('layout.partial.header')

  @yield('content')

  @include('layout.partial.footer')

  {{-- Script utama (jika ada) --}}
  @include('layout.partial.script')

  {{-- Tambahkan ini agar halaman anak bisa menambahkan script khusus --}}
  @stack('scripts')

</body>

</html>