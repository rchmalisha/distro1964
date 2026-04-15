@extends('layout.main')
@section('title', 'Dashboard')

@section('content')
<div class="w-full px-6 py-6 mx-auto">

  <!-- 🔹 CARD SUMMARY -->
  <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6">

    <!-- Card -->
    <div class="bg-white rounded-2xl shadow-soft-xl p-4 flex justify-between items-center hover:scale-105 transition">
      <div>
        <p class="text-sm text-gray-500">Total Penjualan</p>
        <h5 id="cardSales" class="text-xl font-bold">0</h5>
      </div>
      <div class="w-12 h-12 flex items-center justify-center rounded-lg bg-purple-500 text-white">
        💰
      </div>
    </div>

    <div class="bg-white rounded-2xl shadow-soft-xl p-4 flex justify-between items-center hover:scale-105 transition">
      <div>
        <p class="text-sm text-gray-500">Pendapatan</p>
        <h5 id="cardRevenue" class="text-xl font-bold">Rp 0</h5>
      </div>
      <div class="w-12 h-12 flex items-center justify-center rounded-lg bg-green-500 text-white">
        📈
      </div>
    </div>

    <div class="bg-white rounded-2xl shadow-soft-xl p-4 flex justify-between items-center hover:scale-105 transition">
      <div>
        <p class="text-sm text-gray-500">Aset Aktif</p>
        <h5 id="cardAssets" class="text-xl font-bold">0</h5>
      </div>
      <div class="w-12 h-12 flex items-center justify-center rounded-lg bg-blue-500 text-white">
        📦
      </div>
    </div>

    <div class="bg-white rounded-2xl shadow-soft-xl p-4 flex justify-between items-center hover:scale-105 transition">
      <div>
        <p class="text-sm text-gray-500">Nilai Aset</p>
        <h5 id="cardAssetValue" class="text-xl font-bold">Rp 0</h5>
      </div>
      <div class="w-12 h-12 flex items-center justify-center rounded-lg bg-pink-500 text-white">
        🏷️
      </div>
    </div>

  </div>


  <!-- 🔹 FILTER + CHART -->
  <div class="mt-8 bg-white rounded-2xl shadow-soft-xl p-6">

    <!-- Header -->
    <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4 mb-4">
      <h6 class="font-bold text-lg">Analisis Penjualan</h6>

      <!-- Filter -->
      <div class="flex gap-2">
        <select id="filterYear" class="border rounded-lg px-3 py-2 text-sm">
          <option value="">Semua Tahun</option>
        </select>

        <select id="filterMonth" class="border rounded-lg px-3 py-2 text-sm">
          <option value="">Semua Bulan</option>
        </select>
      </div>
    </div>

    <!-- Chart -->
    <div style="height:320px">
      <canvas id="salesChart"></canvas>
    </div>

    <!-- Summary bawah chart -->
    <div class="mt-6 grid grid-cols-2 gap-4 text-sm text-gray-600">
      <div class="bg-gray-50 p-3 rounded-lg">
        <div class="text-xs">Total Penjualan</div>
        <div id="chartTotalSales" class="font-bold text-lg">0</div>
      </div>
      <div class="bg-gray-50 p-3 rounded-lg">
        <div class="text-xs">Total Pendapatan</div>
        <div id="chartTotalRevenue" class="font-bold text-lg">Rp 0</div>
      </div>
    </div>

  </div>


  <!-- 🔹 RECENT ACTIVITY -->
  <div class="mt-8 bg-white rounded-2xl shadow-soft-xl p-6">
    <h6 class="font-bold mb-4">Aktivitas Terbaru</h6>
    <div id="recentActivity" class="space-y-3 text-sm text-gray-600">
      Loading...
    </div>
  </div>

</div>

<!-- 🔹 SCRIPT -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {

  const fmt = (v) =>
    new Intl.NumberFormat('id-ID', {
      style: 'currency',
      currency: 'IDR',
      minimumFractionDigits: 0
    }).format(v || 0);

  let chartInstance;

  // 🔹 LOAD SUMMARY
  fetch('/dashboard/data/summary')
    .then(res => res.json())
    .then(d => {
      cardSales.textContent = d.totalSales;
      cardRevenue.textContent = fmt(d.totalRevenue);
      cardAssets.textContent = d.activeAssets;
      cardAssetValue.textContent = fmt(d.assetValue);

      chartTotalSales.textContent = d.totalSales;
      chartTotalRevenue.textContent = fmt(d.totalRevenue);
    });

  // 🔹 LOAD CHART
  function loadChart(year = '', month = '') {
    fetch(`/dashboard/data/sales-monthly?year=${year}&month=${month}`)
      .then(res => res.json())
      .then(p => {

        if (chartInstance) chartInstance.destroy();

        chartInstance = new Chart(document.getElementById('salesChart'), {
          type: 'bar',
          data: {
            labels: p.labels,
            datasets: [{
              label: 'Penjualan',
              data: p.data,
              borderRadius: 8
            }]
          },
          options: {
            maintainAspectRatio: false,
            plugins: { legend: { display: false } }
          }
        });
      });
  }

  loadChart();

  // 🔹 FILTER EVENT
  document.getElementById('filterYear').addEventListener('change', e => {
    loadChart(e.target.value, filterMonth.value);
  });

  document.getElementById('filterMonth').addEventListener('change', e => {
    loadChart(filterYear.value, e.target.value);
  });

  // 🔹 RECENT ACTIVITY
  fetch('/dashboard/data/recent-activity')
    .then(res => res.json())
    .then(items => {
      const el = document.getElementById('recentActivity');

      if (!items.length) {
        el.innerHTML = 'Tidak ada aktivitas';
        return;
      }

      el.innerHTML = items.map(it => `
        <div class="flex justify-between bg-gray-50 p-3 rounded-lg">
          <div>
            <div class="font-medium">${it.title || it.ref}</div>
            <div class="text-xs text-gray-400">${new Date(it.date).toLocaleString()}</div>
          </div>
          <div>${it.amount ? fmt(it.amount) : ''}</div>
        </div>
      `).join('');
    });

});
</script>
@endsection