    <!-- sidenav  -->
    <aside
      x-data
      class="max-w-62.5 ease-nav-brand z-990 fixed inset-y-0 my-0 h-screen ml-4 block w-full -translate-x-full flex-wrap items-center justify-between overflow-y-auto rounded-2xl border-0 bg-white p-0 antialiased shadow-none transition-transform duration-200 xl:left-0 xl:translate-x-0 xl:bg-transparent">
      <div class="flex justify-center items-center py-6">
        <i
          class="absolute top-0 right-0 hidden p-4 opacity-50 cursor-pointer fas fa-times text-slate-400 xl:hidden"
          sidenav-close></i>

  <h1><center><span style="font-family: 'Open Sans', sans-serif;">DISTRO 1964</span></center></h1>
        {{-- <img
          src="{{ asset('assets/img/logo.jpg') }}"
          class="w-16 h-auto mx-auto transition-all duration-300 ease-in-out"
          alt="Logo" /> --}}
      </div>

      </div>

      <hr
        class="h-px mt-0 bg-transparent bg-gradient-to-r from-transparent via-black/40 to-transparent" />

      <div
        class="items-center block w-auto h-[calc(100vh-80px)] overflow-y-auto grow basis-full">
        <ul class="flex flex-col pl-0 mb-0">
          <li class="mt-0.5 w-full">
            @php $isDashboard = request()->routeIs('dashboard'); @endphp
            <a
              href="{{ route('dashboard') }}"
              class="py-2.7 text-sm ease-nav-brand my-0 mx-4 flex items-center whitespace-nowrap rounded-lg px-4 transition-colors
                {{ $isDashboard ? 'bg-white text-slate-700 shadow-soft-xl font-bold' : 'bg-transparent text-slate-500 font-normal' }}
                hover:bg-white hover:text-slate-700 hover:shadow-soft-xl">
              <div
                class="mr-2 flex h-8 w-8 items-center justify-center rounded-lg bg-center stroke-0 text-center xl:p-2.5
                   {{ $isDashboard ? 'bg-gradient-to-tl from-purple-700 to-pink-500 shadow-soft-2xl text-white' : 'shadow-soft-2xl bg-white text-slate-500' }}">
                <svg
                  width="12px"
                  height="12px"
                  viewBox="0 0 45 40"
                  version="1.1"
                  xmlns="http://www.w3.org/2000/svg"
                  xmlns:xlink="http://www.w3.org/1999/xlink">
                  <title>shop</title>
                  <g
                    stroke="none"
                    stroke-width="1"
                    fill="none"
                    fill-rule="evenodd">
                    <g
                      transform="translate(-1716.000000, -439.000000)"
                      fill="currentColor"
                      fill-rule="nonzero">
                      <g transform="translate(1716.000000, 291.000000)">
                        <g transform="translate(0.000000, 148.000000)">
                          <path
                            class="opacity-60"
                            d="M46.7199583,10.7414583 L40.8449583,0.949791667 C40.4909749,0.360605034 39.8540131,0 39.1666667,0 L7.83333333,0 C7.1459869,0 6.50902508,0.360605034 6.15504167,0.949791667 L0.280041667,10.7414583 C0.0969176761,11.0460037 -1.23209662e-05,11.3946378 -1.23209662e-05,11.75 C-0.00758042603,16.0663731 3.48367543,19.5725301 7.80004167,19.5833333 L7.81570833,19.5833333 C9.75003686,19.5882688 11.6168794,18.8726691 13.0522917,17.5760417 C16.0171492,20.2556967 20.5292675,20.2556967 23.494125,17.5760417 C26.4604562,20.2616016 30.9794188,20.2616016 33.94575,17.5760417 C36.2421905,19.6477597 39.5441143,20.1708521 42.3684437,18.9103691 C45.1927731,17.649886 47.0084685,14.8428276 47.0000295,11.75 C47.0000295,11.3946378 46.9030823,11.0460037 46.7199583,10.7414583 Z"></path>
                          <path
                            class=""
                            d="M39.198,22.4912623 C37.3776246,22.4928106 35.5817531,22.0149171 33.951625,21.0951667 L33.92225,21.1107282 C31.1430221,22.6838032 27.9255001,22.9318916 24.9844167,21.7998837 C24.4750389,21.605469 23.9777983,21.3722567 23.4960833,21.1018359 L23.4745417,21.1129513 C20.6961809,22.6871153 17.4786145,22.9344611 14.5386667,21.7998837 C14.029926,21.6054643 13.533337,21.3722507 13.0522917,21.1018359 C11.4250962,22.0190609 9.63246555,22.4947009 7.81570833,22.4912623 C7.16510551,22.4842162 6.51607673,22.4173045 5.875,22.2911849 L5.875,44.7220845 C5.875,45.9498589 6.7517757,46.9451667 7.83333333,46.9451667 L19.5833333,46.9451667 L19.5833333,33.6066734 L27.4166667,33.6066734 L27.4166667,46.9451667 L39.1666667,46.9451667 C40.2482243,46.9451667 41.125,45.9498589 41.125,44.7220845 L41.125,22.2822926 C40.4887822,22.4116582 39.8442868,22.4815492 39.198,22.4912623 Z"></path>
                        </g>
                      </g>
                    </g>
                  </g>
                </svg>
              </div>
              <span
                class="ml-1 duration-300 opacity-100 pointer-events-none ease-soft">Dashboard</span>
            </a>
          </li>

          <!-- Data Master Section -->
          <li class="w-full mt-4">
            <h6
              class="pl-6 ml-2 text-xs font-bold leading-tight uppercase opacity-60">
              Data Master
            </h6>
          </li>

          <li class="mt-0.5 w-full">
            <a
              class="py-2.7 text-sm ease-nav-brand my-0 mx-4 flex items-center whitespace-nowrap px-4 transition-colors"
              href="{{route('materials.index')}}"> <!-- arahkan ke route bahan -->
              <div
                class="shadow-soft-2xl mr-2 flex h-8 w-8 items-center justify-center rounded-lg bg-white text-center xl:p-2.5">
                <i class="fa-solid fa-boxes-stacked"></i>
              </div>
              <span class="ml-1 duration-300 opacity-100 pointer-events-none ease-soft">
                Bahan Baku
              </span>
            </a>
          </li>

          <li class="mt-0.5 w-full">
            <a
              class="py-2.7 text-sm ease-nav-brand my-0 mx-4 flex items-center whitespace-nowrap px-4 transition-colors"
              href="{{ route('services.index') }}"> <!-- arahkan ke route barang/jasa -->
              <div
                class="shadow-soft-2xl mr-2 flex h-8 w-8 items-center justify-center rounded-lg bg-white text-center xl:p-2.5">
                <i class="fa-solid fa-box-open"></i>
              </div>
              <span class="ml-1 duration-300 opacity-100 pointer-events-none ease-soft">
                Barang Jasa
              </span>
            </a>
          </li>

          <!-- Pembelian Section -->
          <li class="w-full mt-4">
            <h6
              class="pl-6 ml-2 text-xs font-bold leading-tight uppercase opacity-60">
              Pembelian
            </h6>
          </li>

          <li class="mt-0.5 w-full">
            <a
              class="py-2.7 text-sm ease-nav-brand my-0 mx-4 flex items-center whitespace-nowrap px-4 transition-colors"
              href="{{ route('materialneeds.index') }}"> <!-- arahkan ke route kebutuhan bahan -->
              <div
                class="shadow-soft-2xl mr-2 flex h-8 w-8 items-center justify-center rounded-lg bg-white text-center xl:p-2.5">
                <i class="fa-solid fa-box-open"></i>
              </div>
              <span class="ml-1 duration-300 opacity-100 pointer-events-none ease-soft">
                Kebutuhan Bahan
              </span>
            </a>
          </li>

          <li class="mt-0.5 w-full">
            <a
              class="py-2.7 text-sm ease-nav-brand my-0 mx-4 flex items-center whitespace-nowrap px-4 transition-colors"
              href="{{ route('purchasing.index') }}"> <!-- arahkan ke route pembelian -->
              <div
                class="shadow-soft-2xl mr-2 flex h-8 w-8 items-center justify-center rounded-lg bg-white text-center xl:p-2.5">
                <i class="fa-solid fa-cart-shopping"></i>
              </div>
              <span class="ml-1 duration-300 opacity-100 pointer-events-none ease-soft">
                Pembelian
              </span>
            </a>
          </li>

          <!-- Penjualan Section -->
          <li class="w-full mt-4">
            <h6
              class="pl-6 ml-2 text-xs font-bold leading-tight uppercase opacity-60">
              Penjualan
            </h6>
          </li>

          <li class="mt-0.5 w-full">
            <a
              class="py-2.7 text-sm ease-nav-brand my-0 mx-4 flex items-center whitespace-nowrap px-4 transition-colors"
              href="{{route('orders.index')}}"> <!-- arahkan ke route order -->
              <div
                class="shadow-soft-2xl mr-2 flex h-8 w-8 items-center justify-center rounded-lg bg-white text-center xl:p-2.5">
                <i class="fa-solid fa-list-check"></i>
              </div>
              <span class="ml-1 duration-300 opacity-100 pointer-events-none ease-soft">
                Order
              </span>
            </a>
          </li>

          <li class="mt-0.5 w-full">
            <a
              class="py-2.7 text-sm ease-nav-brand my-0 mx-4 flex items-center whitespace-nowrap px-4 transition-colors"
              href="{{route('sales.index')}}"> <!-- arahkan ke route penjualan -->
              <div
                class="shadow-soft-2xl mr-2 flex h-8 w-8 items-center justify-center rounded-lg bg-white text-center xl:p-2.5">
                <i class="fa-solid fa-dollar-sign"></i>
              </div>
              <span class="ml-1 duration-300 opacity-100 pointer-events-none ease-soft">
                Penjualan
              </span>
            </a>
          </li>

          <!-- Akuntansi Section -->
          <li class="w-full mt-4">
            <h6
              class="pl-6 ml-2 text-xs font-bold leading-tight uppercase opacity-60">
              Akuntansi
            </h6>
          </li>

          <li class="mt-0.5 w-full">
            @php $isAccounts = request()->routeIs('accounts.*'); @endphp
            <a
              href="{{ route('accounts.index') }}"
              class="py-2.7 text-sm ease-nav-brand my-0 mx-4 flex items-center whitespace-nowrap px-4 transition-colors
                {{ $isAccounts ? 'bg-white text-slate-700 shadow-soft-xl font-bold' : 'bg-transparent text-slate-500 font-normal' }}
                hover:bg-white hover:text-slate-700 hover:shadow-soft-xl"> <!-- arahkan ke route daftar akun -->
              <div
                class="mr-2 flex h-8 w-8 items-center justify-center rounded-lg bg-center text-center xl:p-2.5
                  {{ $isAccounts ? 'bg-gradient-to-tl from-purple-700 to-pink-500 shadow-soft-2xl text-white' : 'shadow-soft-2xl bg-white text-slate-500' }}">
                <i class="fa-solid fa-list"></i>
              </div>
              <span class="ml-1 duration-300 opacity-100 pointer-events-none ease-soft">
                Daftar Akun
              </span>
            </a>
          </li>

          <li class="mt-0.5 w-full">
            <a
              class="py-2.7 text-sm ease-nav-brand my-0 mx-4 flex items-center whitespace-nowrap px-4 transition-colors"
              href=""> <!-- arahkan ke route aset tetap -->
              <div
                class="shadow-soft-2xl mr-2 flex h-8 w-8 items-center justify-center rounded-lg bg-white text-center xl:p-2.5">
                <i class="fa-solid fa-building"></i>
              </div>
              <span class="ml-1 duration-300 opacity-100 pointer-events-none ease-soft">
                Aset Tetap
              </span>
            </a>
          </li>

          <li class="mt-0.5 w-full">
            @php $isJournal = request()->routeIs('journal.*'); @endphp
            <a
              href="{{ route('journal.index') }}"
              class="py-2.7 text-sm ease-nav-brand my-0 mx-4 flex items-center whitespace-nowrap px-4 transition-colors
                {{ $isJournal ? 'bg-white text-slate-700 shadow-soft-xl font-bold' : 'bg-transparent text-slate-500 font-normal' }}
                hover:bg-white hover:text-slate-700 hover:shadow-soft-xl"> <!-- arahkan ke route jurnal umum -->
              <div
                class="mr-2 flex h-8 w-8 items-center justify-center rounded-lg bg-center text-center xl:p-2.5
                  {{ $isJournal ? 'bg-gradient-to-tl from-purple-700 to-pink-500 shadow-soft-2xl text-white' : 'shadow-soft-2xl bg-white text-slate-500' }}">
                <i class="fa-solid fa-file-pen"></i>
              </div>
              <span class="ml-1 duration-300 opacity-100 pointer-events-none ease-soft">
                Jurnal Umum
              </span>
            </a>
          </li>

          <li class="mt-0.5 w-full">
            @php $isLedger = request()->routeIs('ledger.*'); @endphp
            <a
              href="{{ route('ledger.index') }}"
              class="py-2.7 text-sm ease-nav-brand my-0 mx-4 flex items-center whitespace-nowrap px-4 transition-colors
                {{ $isLedger ? 'bg-white text-slate-700 shadow-soft-xl font-bold' : 'bg-transparent text-slate-500 font-normal' }}
                hover:bg-white hover:text-slate-700 hover:shadow-soft-xl"> <!-- arahkan ke route buku besar -->
              <div
                class="mr-2 flex h-8 w-8 items-center justify-center rounded-lg bg-center text-center xl:p-2.5
                  {{ $isLedger ? 'bg-gradient-to-tl from-purple-700 to-pink-500 shadow-soft-2xl text-white' : 'shadow-soft-2xl bg-white text-slate-500' }} group-hover:bg-gradient-to-tl group-hover:from-purple-700 group-hover:to-pink-500 group-hover:text-white">
                <i class="fa-solid fa-book-open"></i>
              </div>
              <span class="ml-1 duration-300 opacity-100 pointer-events-none ease-soft">
                Buku Besar
              </span>
            </a>
          </li>

          <li class="mt-0.5 w-full">
            @php $isTrial = request()->routeIs('trial-balance.*'); @endphp
            <a
              href="{{ route('trial-balance.index') }}"
              class="py-2.7 text-sm ease-nav-brand my-0 mx-4 flex items-center whitespace-nowrap px-4 transition-colors
                {{ $isTrial ? 'bg-white text-slate-700 shadow-soft-xl font-bold' : 'bg-transparent text-slate-500 font-normal' }}
                hover:bg-white hover:text-slate-700 hover:shadow-soft-xl"> <!-- arahkan ke route neraca saldo -->
              <div
                class="mr-2 flex h-8 w-8 items-center justify-center rounded-lg bg-center text-center xl:p-2.5
                  {{ $isTrial ? 'bg-gradient-to-tl from-purple-700 to-pink-500 shadow-soft-2xl text-white' : 'shadow-soft-2xl bg-white text-slate-500' }} group-hover:bg-gradient-to-tl group-hover:from-purple-700 group-hover:to-pink-500 group-hover:text-white">
                <i class="fa-solid fa-table-columns"></i>
              </div>
              <span class="ml-1 duration-300 opacity-100 pointer-events-none ease-soft">
                Neraca Saldo
              </span>
            </a>
          </li>

          <!-- Laporan Section -->
          <li class="w-full mt-4">
            <h6
              class="pl-6 ml-2 text-xs font-bold leading-tight uppercase opacity-60">
              Laporan Keuangan
            </h6>
          </li>

          <li class="mt-0.5 w-full">
            <a
              class="py-2.7 text-sm ease-nav-brand my-0 mx-4 flex items-center whitespace-nowrap px-4 transition-colors"
              href=""> <!-- arahkan ke route laporan laba rugi -->
              <div
                class="shadow-soft-2xl mr-2 flex h-8 w-8 items-center justify-center rounded-lg bg-white text-center xl:p-2.5">
                <i class="fa-solid fa-chart-line"></i>
              </div>
              <span class="ml-1 duration-300 opacity-100 pointer-events-none ease-soft">
                Laporan Laba Rugi
              </span>
            </a>
          </li>

          <li class="mt-0.5 w-full">
            <a
              class="py-2.7 text-sm ease-nav-brand my-0 mx-4 flex items-center whitespace-nowrap px-4 transition-colors"
              href="/"> <!-- arahkan ke route neraca -->
              <div
                class="shadow-soft-2xl mr-2 flex h-8 w-8 items-center justify-center rounded-lg bg-white text-center xl:p-2.5">
                <i class="fa-solid fa-balance-scale"></i>
              </div>
              <span class="ml-1 duration-300 opacity-100 pointer-events-none ease-soft">
                Neraca
              </span>
            </a>
          </li>

        </ul>
      </div>

    </aside>

    <!-- end sidenav -->

    <main
      class="ease-soft-in-out xl:ml-68.5 relative h-full max-h-screen rounded-xl transition-all duration-200">
      <!-- Navbar -->
      <nav
        class="relative flex flex-wrap items-center justify-between px-0 py-2 mx-6 transition-all shadow-none duration-250 ease-soft-in rounded-2xl lg:flex-nowrap lg:justify-start"
        navbar-main
        navbar-scroll="true">
        <div
          class="flex items-center justify-between w-full px-4 py-1 mx-auto flex-wrap-inherit">
          <nav>
            <!-- breadcrumb -->
            <ol class="flex flex-wrap pt-1 mr-12 bg-transparent rounded-lg sm:mr-16">
              <li class="text-sm leading-normal">
                <a class="opacity-50 text-slate-700" href="javascript:;">Pages</a>
              </li>
              <li
                class="text-sm pl-2 capitalize leading-normal text-slate-700 before:float-left before:pr-2 before:text-gray-600 before:content-['/']"
                aria-current="page">
                @yield('title')
              </li>
            </ol>
            <h6 class="mb-0 font-bold capitalize">@yield('title')</h6>
          </nav>


          <div
            class="flex items-center mt-2 grow sm:mt-0 sm:mr-6 md:mr-0 lg:flex lg:basis-auto">
            <div class="flex items-center md:ml-auto md:pr-4">
              <div
                class="relative flex flex-wrap items-stretch w-full transition-all rounded-lg ease-soft">
                <span
                  class="text-sm ease-soft leading-5.6 absolute z-50 -ml-px flex h-full items-center whitespace-nowrap rounded-lg rounded-tr-none rounded-br-none border border-r-0 border-transparent bg-transparent py-2 px-2.5 text-center font-normal text-slate-500 transition-all">
                  <i class="fas fa-search"></i>
                </span>
                <input
                  type="text"
                  class="pl-8.75 text-sm focus:shadow-soft-primary-outline ease-soft w-1/100 leading-5.6 relative -ml-px block min-w-0 flex-auto rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding py-2 pr-3 text-gray-700 transition-all placeholder:text-gray-500 focus:border-fuchsia-300 focus:outline-none focus:transition-shadow"
                  placeholder="Type here..." />
              </div>
            </div>
            <ul
              class="flex flex-row justify-end pl-0 mb-0 list-none md-max:w-full">
              <!-- online builder btn  -->
              <!-- <li class="flex items-center">
                <a class="inline-block px-8 py-2 mb-0 mr-4 text-xs font-bold text-center uppercase align-middle transition-all bg-transparent border border-solid rounded-lg shadow-none cursor-pointer leading-pro border-fuchsia-500 ease-soft-in hover:scale-102 active:shadow-soft-xs text-fuchsia-500 hover:border-fuchsia-500 active:bg-fuchsia-500 active:hover:text-fuchsia-500 hover:text-fuchsia-500 tracking-tight-soft hover:bg-transparent hover:opacity-75 hover:shadow-none active:text-white active:hover:bg-transparent" target="_blank" href="https://www.creative-tim.com/builder/soft-ui?ref=navbar-dashboard&amp;_ga=2.76518741.1192788655.1647724933-1242940210.1644448053">Online Builder</a>
              </li> -->
              <li class="flex items-center">
                <form action="{{ route('logout') }}" method="POST">
                  @csrf
                  <button
                    type="submit"
                    class="block px-0 py-2 text-sm font-semibold transition-all ease-nav-brand text-slate-500">
                    <i class="fa fa-user sm:mr-1"></i>
                    <span class="hidden sm:inline">Sign Out</span>
                  </button>
                </form>
              </li>
              <li class="flex items-center pl-4 xl:hidden">
                <a
                  href="javascript:;"
                  class="block p-0 text-sm transition-all ease-nav-brand text-slate-500"
                  sidenav-trigger>
                  <div class="w-4.5 overflow-hidden">
                    <i
                      class="ease-soft mb-0.75 relative block h-0.5 rounded-sm bg-slate-500 transition-all"></i>
                    <i
                      class="ease-soft mb-0.75 relative block h-0.5 rounded-sm bg-slate-500 transition-all"></i>
                    <i
                      class="ease-soft relative block h-0.5 rounded-sm bg-slate-500 transition-all"></i>
                  </div>
                </a>
              </li>
              <li class="flex items-center px-4">
                <a
                  href="javascript:;"
                  class="p-0 text-sm transition-all ease-nav-brand text-slate-500">
                  <i
                    fixed-plugin-button-nav
                    class="cursor-pointer fa fa-cog"></i>
                  <!-- fixed-plugin-button-nav  -->
                </a>
              </li>

              <!-- notifications -->

              <li class="relative flex items-center pr-2">
                <p class="hidden transform-dropdown-show"></p>
                <a
                  href="javascript:;"
                  class="block p-0 text-sm transition-all ease-nav-brand text-slate-500"
                  dropdown-trigger
                  aria-expanded="false">
                  <i class="cursor-pointer fa fa-bell"></i>
                </a>

                <ul
                  dropdown-menu
                  class="text-sm transform-dropdown before:font-awesome before:leading-default before:duration-350 before:ease-soft lg:shadow-soft-3xl duration-250 min-w-44 before:sm:right-7.5 before:text-5.5 pointer-events-none absolute right-0 top-0 z-50 origin-top list-none rounded-lg border-0 border-solid border-transparent bg-white bg-clip-padding px-2 py-4 text-left text-slate-500 opacity-0 transition-all before:absolute before:right-2 before:left-auto before:top-0 before:z-50 before:inline-block before:font-normal before:text-white before:antialiased before:transition-all before:content-['\f0d8'] sm:-mr-6 lg:absolute lg:right-0 lg:left-auto lg:mt-2 lg:block lg:cursor-pointer">
                  <!-- add show class on dropdown open js -->
                  <li class="relative mb-2">
                    <a
                      class="ease-soft py-1.2 clear-both block w-full whitespace-nowrap rounded-lg bg-transparent px-4 duration-300 hover:bg-gray-200 hover:text-slate-700 lg:transition-colors"
                      href="javascript:;">
                      <div class="flex py-1">
                        <div class="my-auto">
                          <img
                            src="./assets/img/team-2.jpg"
                            class="inline-flex items-center justify-center mr-4 text-sm text-white h-9 w-9 max-w-none rounded-xl" />
                        </div>
                        <div class="flex flex-col justify-center">
                          <h6 class="mb-1 text-sm font-normal leading-normal">
                            <span class="font-semibold">New message</span> from
                            Laur
                          </h6>
                          <p class="mb-0 text-xs leading-tight text-slate-400">
                            <i class="mr-1 fa fa-clock"></i>
                            13 minutes ago
                          </p>
                        </div>
                      </div>
                    </a>
                  </li>

                  <li class="relative mb-2">
                    <a
                      class="ease-soft py-1.2 clear-both block w-full whitespace-nowrap rounded-lg px-4 transition-colors duration-300 hover:bg-gray-200 hover:text-slate-700"
                      href="javascript:;">
                      <div class="flex py-1">
                        <div class="my-auto">
                          <img
                            src="./assets/img/small-logos/logo-spotify.svg"
                            class="inline-flex items-center justify-center mr-4 text-sm text-white bg-gradient-to-tl from-gray-900 to-slate-800 h-9 w-9 max-w-none rounded-xl" />
                        </div>
                        <div class="flex flex-col justify-center">
                          <h6 class="mb-1 text-sm font-normal leading-normal">
                            <span class="font-semibold">New album</span> by
                            Travis Scott
                          </h6>
                          <p class="mb-0 text-xs leading-tight text-slate-400">
                            <i class="mr-1 fa fa-clock"></i>
                            1 day
                          </p>
                        </div>
                      </div>
                    </a>
                  </li>

                  <li class="relative">
                    <a
                      class="ease-soft py-1.2 clear-both block w-full whitespace-nowrap rounded-lg px-4 transition-colors duration-300 hover:bg-gray-200 hover:text-slate-700"
                      href="javascript:;">
                      <div class="flex py-1">
                        <div
                          class="inline-flex items-center justify-center my-auto mr-4 text-sm text-white transition-all duration-200 ease-nav-brand bg-gradient-to-tl from-slate-600 to-slate-300 h-9 w-9 rounded-xl">
                          <svg
                            width="12px"
                            height="12px"
                            viewBox="0 0 43 36"
                            version="1.1"
                            xmlns="http://www.w3.org/2000/svg"
                            xmlns:xlink="http://www.w3.org/1999/xlink">
                            <title>credit-card</title>
                            <g
                              stroke="none"
                              stroke-width="1"
                              fill="none"
                              fill-rule="evenodd">
                              <g
                                transform="translate(-2169.000000, -745.000000)"
                                fill="#FFFFFF"
                                fill-rule="nonzero">
                                <g
                                  transform="translate(1716.000000, 291.000000)">
                                  <g
                                    transform="translate(453.000000, 454.000000)">
                                    <path
                                      class="color-background"
                                      d="M43,10.7482083 L43,3.58333333 C43,1.60354167 41.3964583,0 39.4166667,0 L3.58333333,0 C1.60354167,0 0,1.60354167 0,3.58333333 L0,10.7482083 L43,10.7482083 Z"
                                      opacity="0.593633743"></path>
                                    <path
                                      class="color-background"
                                      d="M0,16.125 L0,32.25 C0,34.2297917 1.60354167,35.8333333 3.58333333,35.8333333 L39.4166667,35.8333333 C41.3964583,35.8333333 43,34.2297917 43,32.25 L43,16.125 L0,16.125 Z M19.7083333,26.875 L7.16666667,26.875 L7.16666667,23.2916667 L19.7083333,23.2916667 L19.7083333,26.875 Z M35.8333333,26.875 L28.6666667,26.875 L28.6666667,23.2916667 L35.8333333,23.2916667 L35.8333333,26.875 Z"></path>
                                  </g>
                                </g>
                              </g>
                            </g>
                          </svg>
                        </div>
                        <div class="flex flex-col justify-center">
                          <h6 class="mb-1 text-sm font-normal leading-normal">
                            Payment successfully completed
                          </h6>
                          <p class="mb-0 text-xs leading-tight text-slate-400">
                            <i class="mr-1 fa fa-clock"></i>
                            2 days
                          </p>
                        </div>
                      </div>
                    </a>
                  </li>
                </ul>
              </li>
            </ul>
          </div>
        </div>
      </nav>

      <!-- end Navbar -->
