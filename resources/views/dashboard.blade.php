@extends('layout.main')
@section('title', 'Dashboard')
@section('content')
      <!-- cards -->
      <div class="w-full px-6 py-6 mx-auto">
        <!-- row 1 -->
        <div class="flex flex-wrap -mx-3">
          <!-- card1 -->
          <div
            class="w-full max-w-full px-3 mb-6 sm:w-1/2 sm:flex-none xl:mb-0 xl:w-1/4">
            <div
              class="relative flex flex-col min-w-0 break-words bg-white shadow-soft-xl rounded-2xl bg-clip-border">
              <div class="flex-auto p-4">
                <div class="flex flex-row -mx-3">
                  <div class="flex-none w-2/3 max-w-full px-3">
                    <div>
                      <p
                        class="mb-0 font-sans text-sm font-semibold leading-normal">
                        Today's Money
                      </p>
                      <h5 class="mb-0 font-bold">
                        $53,000
                        <span
                          class="text-sm leading-normal font-weight-bolder text-lime-500"
                          >+55%</span
                        >
                      </h5>
                    </div>
                  </div>
                  <div class="px-3 text-right basis-1/3">
                    <div
                      class="inline-block w-12 h-12 text-center rounded-lg bg-gradient-to-tl from-purple-700 to-pink-500">
                      <i
                        class="ni leading-none ni-money-coins text-lg relative top-3.5 text-white"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- card2 -->
          <div
            class="w-full max-w-full px-3 mb-6 sm:w-1/2 sm:flex-none xl:mb-0 xl:w-1/4">
            <div
              class="relative flex flex-col min-w-0 break-words bg-white shadow-soft-xl rounded-2xl bg-clip-border">
              <div class="flex-auto p-4">
                <div class="flex flex-row -mx-3">
                  <div class="flex-none w-2/3 max-w-full px-3">
                    <div>
                      <p
                        class="mb-0 font-sans text-sm font-semibold leading-normal">
                        Today's Users
                      </p>
                      <h5 class="mb-0 font-bold">
                        2,300
                        <span
                          class="text-sm leading-normal font-weight-bolder text-lime-500"
                          >+3%</span
                        >
                      </h5>
                    </div>
                  </div>
                  <div class="px-3 text-right basis-1/3">
                    <div
                      class="inline-block w-12 h-12 text-center rounded-lg bg-gradient-to-tl from-purple-700 to-pink-500">
                      <i
                        class="ni leading-none ni-world text-lg relative top-3.5 text-white"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- card3 -->
          <div
            class="w-full max-w-full px-3 mb-6 sm:w-1/2 sm:flex-none xl:mb-0 xl:w-1/4">
            <div
              class="relative flex flex-col min-w-0 break-words bg-white shadow-soft-xl rounded-2xl bg-clip-border">
              <div class="flex-auto p-4">
                <div class="flex flex-row -mx-3">
                  <div class="flex-none w-2/3 max-w-full px-3">
                    <div>
                      <p
                        class="mb-0 font-sans text-sm font-semibold leading-normal">
                        New Clients
                      </p>
                      <h5 class="mb-0 font-bold">
                        +3,462
                        <span
                          class="text-sm leading-normal text-red-600 font-weight-bolder"
                          >-2%</span
                        >
                      </h5>
                    </div>
                  </div>
                  <div class="px-3 text-right basis-1/3">
                    <div
                      class="inline-block w-12 h-12 text-center rounded-lg bg-gradient-to-tl from-purple-700 to-pink-500">
                      <i
                        class="ni leading-none ni-paper-diploma text-lg relative top-3.5 text-white"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- card4 -->
          <div class="w-full max-w-full px-3 sm:w-1/2 sm:flex-none xl:w-1/4">
            <div
              class="relative flex flex-col min-w-0 break-words bg-white shadow-soft-xl rounded-2xl bg-clip-border">
              <div class="flex-auto p-4">
                <div class="flex flex-row -mx-3">
                  <div class="flex-none w-2/3 max-w-full px-3">
                    <div>
                      <p
                        class="mb-0 font-sans text-sm font-semibold leading-normal">
                        Sales
                      </p>
                      <h5 class="mb-0 font-bold">
                        $103,430
                        <span
                          class="text-sm leading-normal font-weight-bolder text-lime-500"
                          >+5%</span
                        >
                      </h5>
                    </div>
                  </div>
                  <div class="px-3 text-right basis-1/3">
                    <div
                      class="inline-block w-12 h-12 text-center rounded-lg bg-gradient-to-tl from-purple-700 to-pink-500">
                      <i
                        class="ni leading-none ni-cart text-lg relative top-3.5 text-white"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- cards row 2 -->

        <!-- cards row 3 -->

        <div class="flex flex-wrap mt-6 -mx-3">
          <div class="w-full max-w-full px-3 mt-0 lg:w-7/12 lg:flex-none">
            <div
              class="border-black/12.5 shadow-soft-xl relative z-20 flex min-w-0 flex-col break-words rounded-2xl border-0 border-solid bg-white bg-clip-border">
              <div
                class="border-black/12.5 mb-0 rounded-t-2xl border-b-0 border-solid bg-white p-6 pb-0">
                <h6>Sales overview</h6>
                <p class="text-sm leading-normal">
                  <i class="fa fa-arrow-up text-lime-500"></i>
                  <span class="font-semibold">4% more</span> in 2021
                </p>
              </div>
              <div class="flex-auto p-4">
                <div>
                  <canvas id="chart-line" height="300"></canvas>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- cards row 4 -->

        <div class="flex flex-wrap my-6 -mx-3">
          <!-- card 1 -->

          <!-- card 2 -->

          <div
            class="w-full max-w-full px-3 md:w-1/2 md:flex-none lg:w-1/3 lg:flex-none">
            <div
              class="border-black/12.5 shadow-soft-xl relative flex h-full min-w-0 flex-col break-words rounded-2xl border-0 border-solid bg-white bg-clip-border">
              <div
                class="border-black/12.5 mb-0 rounded-t-2xl border-b-0 border-solid bg-white p-6 pb-0">
                <h6>Orders overview</h6>
                <p class="text-sm leading-normal">
                  <i class="fa fa-arrow-up text-lime-500"></i>
                  <span class="font-semibold">24%</span> this month
                </p>
              </div>
              <div class="flex-auto p-4">
                <div
                  class="before:border-r-solid relative before:absolute before:top-0 before:left-4 before:h-full before:border-r-2 before:border-r-slate-100 before:content-[''] before:lg:-ml-px">
                  <div
                    class="relative mb-4 mt-0 after:clear-both after:table after:content-['']">
                    <span
                      class="w-6.5 h-6.5 text-base absolute left-4 z-10 inline-flex -translate-x-1/2 items-center justify-center rounded-full bg-white text-center font-semibold">
                      <i
                        class="relative z-10 leading-none text-transparent ni ni-bell-55 leading-pro bg-gradient-to-tl from-green-600 to-lime-400 bg-clip-text fill-transparent"></i>
                    </span>
                    <div
                      class="ml-11.252 pt-1.4 lg:max-w-120 relative -top-1.5 w-auto">
                      <h6
                        class="mb-0 text-sm font-semibold leading-normal text-slate-700">
                        $2400, Design changes
                      </h6>
                      <p
                        class="mt-1 mb-0 text-xs font-semibold leading-tight text-slate-400">
                        22 DEC 7:20 PM
                      </p>
                    </div>
                  </div>
                  <div
                    class="relative mb-4 after:clear-both after:table after:content-['']">
                    <span
                      class="w-6.5 h-6.5 text-base absolute left-4 z-10 inline-flex -translate-x-1/2 items-center justify-center rounded-full bg-white text-center font-semibold">
                      <i
                        class="relative z-10 leading-none text-transparent ni ni-html5 leading-pro bg-gradient-to-tl from-red-600 to-rose-400 bg-clip-text fill-transparent"></i>
                    </span>
                    <div
                      class="ml-11.252 pt-1.4 lg:max-w-120 relative -top-1.5 w-auto">
                      <h6
                        class="mb-0 text-sm font-semibold leading-normal text-slate-700">
                        New order #1832412
                      </h6>
                      <p
                        class="mt-1 mb-0 text-xs font-semibold leading-tight text-slate-400">
                        21 DEC 11 PM
                      </p>
                    </div>
                  </div>
                  <div
                    class="relative mb-4 after:clear-both after:table after:content-['']">
                    <span
                      class="w-6.5 h-6.5 text-base absolute left-4 z-10 inline-flex -translate-x-1/2 items-center justify-center rounded-full bg-white text-center font-semibold">
                      <i
                        class="relative z-10 leading-none text-transparent ni ni-cart leading-pro bg-gradient-to-tl from-blue-600 to-cyan-400 bg-clip-text fill-transparent"></i>
                    </span>
                    <div
                      class="ml-11.252 pt-1.4 lg:max-w-120 relative -top-1.5 w-auto">
                      <h6
                        class="mb-0 text-sm font-semibold leading-normal text-slate-700">
                        Server payments for April
                      </h6>
                      <p
                        class="mt-1 mb-0 text-xs font-semibold leading-tight text-slate-400">
                        21 DEC 9:34 PM
                      </p>
                    </div>
                  </div>
                  <div
                    class="relative mb-4 after:clear-both after:table after:content-['']">
                    <span
                      class="w-6.5 h-6.5 text-base absolute left-4 z-10 inline-flex -translate-x-1/2 items-center justify-center rounded-full bg-white text-center font-semibold">
                      <i
                        class="relative z-10 leading-none text-transparent ni ni-credit-card leading-pro bg-gradient-to-tl from-red-500 to-yellow-400 bg-clip-text fill-transparent"></i>
                    </span>
                    <div
                      class="ml-11.252 pt-1.4 lg:max-w-120 relative -top-1.5 w-auto">
                      <h6
                        class="mb-0 text-sm font-semibold leading-normal text-slate-700">
                        New card added for order #4395133
                      </h6>
                      <p
                        class="mt-1 mb-0 text-xs font-semibold leading-tight text-slate-400">
                        20 DEC 2:20 AM
                      </p>
                    </div>
                  </div>
                  <div
                    class="relative mb-4 after:clear-both after:table after:content-['']">
                    <span
                      class="w-6.5 h-6.5 text-base absolute left-4 z-10 inline-flex -translate-x-1/2 items-center justify-center rounded-full bg-white text-center font-semibold">
                      <i
                        class="relative z-10 leading-none text-transparent ni ni-key-25 leading-pro bg-gradient-to-tl from-purple-700 to-pink-500 bg-clip-text fill-transparent"></i>
                    </span>
                    <div
                      class="ml-11.252 pt-1.4 lg:max-w-120 relative -top-1.5 w-auto">
                      <h6
                        class="mb-0 text-sm font-semibold leading-normal text-slate-700">
                        Unlock packages for development
                      </h6>
                      <p
                        class="mt-1 mb-0 text-xs font-semibold leading-tight text-slate-400">
                        18 DEC 4:54 AM
                      </p>
                    </div>
                  </div>
                  <div
                    class="relative mb-0 after:clear-both after:table after:content-['']">
                    <span
                      class="w-6.5 h-6.5 text-base absolute left-4 z-10 inline-flex -translate-x-1/2 items-center justify-center rounded-full bg-white text-center font-semibold">
                      <i
                        class="relative z-10 leading-none text-transparent ni ni-money-coins leading-pro bg-gradient-to-tl from-gray-900 to-slate-800 bg-clip-text fill-transparent"></i>
                    </span>
                    <div
                      class="ml-11.252 pt-1.4 lg:max-w-120 relative -top-1.5 w-auto">
                      <h6
                        class="mb-0 text-sm font-semibold leading-normal text-slate-700">
                        New order #9583120
                      </h6>
                      <p
                        class="mt-1 mb-0 text-xs font-semibold leading-tight text-slate-400">
                        17 DEC
                      </p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <footer class="pt-4">
          <div class="w-full px-6 mx-auto">
            <div class="flex flex-wrap items-center -mx-3 lg:justify-between">
              <div
                class="w-full max-w-full px-3 mt-0 mb-6 shrink-0 lg:mb-0 lg:w-1/2 lg:flex-none">
                <div
                  class="text-sm leading-normal text-center text-slate-500 lg:text-left">
                  ©
                  <script>
                    document.write(new Date().getFullYear() + ",");
                  </script>
                  made with <i class="fa fa-heart"></i> by
                  <a
                    href="https://www.creative-tim.com"
                    class="font-semibold text-slate-700"
                    target="_blank"
                    >Creative Tim</a
                  >

                  for a better web.
                  <span class="w-full"> Distributed by ❤️ ThemeWagon </span>
                </div>
              </div>
              <div
                class="w-full max-w-full px-3 mt-0 shrink-0 lg:w-1/2 lg:flex-none">
                <ul
                  class="flex flex-wrap justify-center pl-0 mb-0 list-none lg:justify-end">
                  <li class="nav-item">
                    <a
                      href="#!"
                      class="block px-4 pt-0 pb-1 text-sm font-normal transition-colors ease-soft-in-out text-slate-500"
                      >Creative Tim</a
                    >
                  </li>
                  <li class="nav-item">
                    <a
                      href="#!"
                      class="block px-4 pt-0 pb-1 text-sm font-normal transition-colors ease-soft-in-out text-slate-500"
                      >About Us</a
                    >
                  </li>
                  <li class="nav-item">
                    <a
                      href="#!"
                      class="block px-4 pt-0 pb-1 text-sm font-normal transition-colors ease-soft-in-out text-slate-500"
                      >Blog</a
                    >
                  </li>
                  <li class="nav-item">
                    <a
                      href="#!"
                      class="block px-4 pt-0 pb-1 pr-0 text-sm font-normal transition-colors ease-soft-in-out text-slate-500"
                      target="_blank"
                      >License</a
                    >
                  </li>
                </ul>
              </div>
            </div>
          </div>
        </footer>
      </div>
      <!-- end cards -->
@endsection