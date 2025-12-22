@extends('layout.main')
@section('title', 'Dashboard')
@section('content')
<div class="w-full px-6 py-6 mx-auto">
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
                      <p class="mb-0 font-sans text-sm font-semibold leading-normal">Total Penjualan</p>
                      <h5 class="mb-0 font-bold"><span id="cardSales">0</span></h5>
                    </div>
                  </div>
                  <div class="px-3 text-right basis-1/3">
                    <div
                      class="inline-block w-12 h-12 text-center rounded-lg bg-gradient-to-tl from-purple-700 to-pink-500">
                      <i class="ni leading-none ni-money-coins text-lg relative top-3.5 text-white"></i>
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
                      <p class="mb-0 font-sans text-sm font-semibold leading-normal">Pendapatan</p>
                      <h5 class="mb-0 font-bold"><span id="cardRevenue">0</span></h5>
                    </div>
                  </div>
                  <div class="px-3 text-right basis-1/3">
                    <div
                      class="inline-block w-12 h-12 text-center rounded-lg bg-gradient-to-tl from-purple-700 to-pink-500">
                      <i class="ni leading-none ni-world text-lg relative top-3.5 text-white"></i>
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
                      <p class="mb-0 font-sans text-sm font-semibold leading-normal">Aset Aktif</p>
                      <h5 class="mb-0 font-bold"><span id="cardAssets">0</span></h5>
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
                      <p class="mb-0 font-sans text-sm font-semibold leading-normal">Nilai Aset</p>
                      <h5 class="mb-0 font-bold"><span id="cardAssetValue">0</span></h5>
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

  </div>
</div>

        <!-- cards row 2 -->

        <div class="flex flex-wrap mt-6 -mx-3">
          <div class="w-full max-w-full px-3 mt-0 lg:w-7/12 lg:flex-none">
            <div class="border-black/12.5 shadow-soft-xl relative z-20 flex min-w-0 flex-col break-words rounded-2xl border-0 border-solid bg-white bg-clip-border">
              <div class="border-black/12.5 mb-0 rounded-t-2xl border-b-0 border-solid bg-white p-6 pb-0">
                <h6>Jumlah Penjualan</h6>
              </div>
              <div class="flex-auto p-4">
                <div>
                  <div style="height:320px">
                    <canvas id="salesChart" style="width:100%;height:100%"></canvas>
                  </div>

                  <!-- small summary below the chart -->
                  <div class="mt-4 flex items-center justify-between text-sm text-gray-600">
                    <div>
                      <div class="text-xs text-gray-500">Total Penjualan</div>
                      <div id="chartTotalSales" class="font-semibold">0</div>
                    </div>
                    <div>
                      <div class="text-xs text-gray-500">Total Pendapatan</div>
                      <div id="chartTotalRevenue" class="font-semibold">Rp 0</div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- cards row 4 -->

        <div class="flex flex-wrap my-6 -mx-3">
          <!-- card 1 -->

          <!-- card 2 -->

          <!-- Orders overview (bottom) removed as requested -->
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
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
      const fmt = (v) => new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(v || 0);

      // keep summary values in variables for chart summary below
      let latestSummary = null;
      fetch('/dashboard/data/summary')
        .then(r => r.json())
        .then(d => {
          latestSummary = d;
          document.getElementById('cardSales').textContent = d.totalSales;
          document.getElementById('cardRevenue').textContent = fmt(d.totalRevenue);
          document.getElementById('cardAssets').textContent = d.activeAssets;
          document.getElementById('cardAssetValue').textContent = fmt(d.assetValue);
          // Also populate the chart summary placeholders if present
          if (document.getElementById('chartTotalSales')) {
            document.getElementById('chartTotalSales').textContent = d.totalSales;
          }
          if (document.getElementById('chartTotalRevenue')) {
            document.getElementById('chartTotalRevenue').textContent = fmt(d.totalRevenue);
          }
        }).catch(console.error);

      // sales bar chart only
      fetch('/dashboard/data/sales-monthly')
        .then(r => r.json())
        .then(p => {
          const container = document.getElementById('salesChart').parentElement;
          if (!p.hasData) {
            container.innerHTML = '<div class="flex items-center justify-center h-full text-gray-500">Tidak ada data penjualan.</div>';
            return;
          }

          const canvas = document.getElementById('salesChart');
          const ctx = canvas.getContext('2d');
          const gradient = ctx.createLinearGradient(0, 0, 0, canvas.height || 320);
          gradient.addColorStop(0, 'rgba(79,70,229,0.9)');
          gradient.addColorStop(1, 'rgba(79,70,229,0.5)');

          if (window._salesChartInstance) window._salesChartInstance.destroy();

          window._salesChartInstance = new Chart(ctx, {
            type: 'bar',
            data: { labels: p.labels, datasets: [{ label: 'Jumlah Penjualan', data: p.data, backgroundColor: gradient, borderRadius: 8, barThickness: 24 }] },
            options: {
              maintainAspectRatio: false,
              plugins: { legend: { display: false }, tooltip: { callbacks: { label: ctx => `${ctx.parsed.y} penjualan` } } },
              scales: { x: { ticks: { maxRotation: 0, autoSkip: false } }, y: { beginAtZero: true } }
            }
          });

          if (latestSummary) {
            if (document.getElementById('chartTotalSales')) document.getElementById('chartTotalSales').textContent = latestSummary.totalSales;
            if (document.getElementById('chartTotalRevenue')) document.getElementById('chartTotalRevenue').textContent = fmt(latestSummary.totalRevenue);
          }

        }).catch(err => { console.error(err); /* do not display a misleading error message */ });

      // recent activity (skip if the element is not on the page)
      fetch('/dashboard/data/recent-activity')
        .then(r => r.json())
        .then(items => {
          const el = document.getElementById('recentActivity');
          if (!el) return; // element removed; nothing to render
          if (!items || items.length === 0) {
            el.innerHTML = '<div class="text-sm text-gray-500">Tidak ada aktivitas terbaru.</div>';
            return;
          }
          const list = document.createElement('div');
          list.className = 'space-y-3';
          items.forEach(it => {
            const when = new Date(it.date);
            const row = document.createElement('div');
            row.className = 'flex items-start gap-3';
            row.innerHTML = `
              <div class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center text-sm">${it.type ? it.type.charAt(0).toUpperCase() : '•'}</div>
              <div class="flex-1">
                <div class="text-sm font-medium">${it.ref || it.title || 'Aktivitas'}</div>
                <div class="text-xs text-gray-500">${when.toLocaleString()}</div>
              </div>
              <div class="text-sm text-gray-700">${it.amount ? new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(it.amount) : (it.meta || '')}</div>
            `;
            list.appendChild(row);
          });
          el.innerHTML = '';
          el.appendChild(list);
        }).catch(console.error);

    });
    </script>
    @endsection
