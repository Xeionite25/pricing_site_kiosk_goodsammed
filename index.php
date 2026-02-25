<?php

use Tracy\Debugger;

require 'vendor/autoload.php'; // alternatively tracy.phar

Debugger::enable();
Debugger::$showBar = false;
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
  <script src="JavaScript/funcs.js" defer></script>
  <title>Kiosk v1 rev. 0.0</title>
  <style>
    .scrollable-box {
      scrollbar-width: thin;
      scrollbar-color: #cbd5e1 #f1f5f9;
    }
    .scrollable-box::-webkit-scrollbar {
      width: 6px;
      height: 6px;
    }
    .scrollable-box::-webkit-scrollbar-track {
      background: #f1f5f9;
    }
    .scrollable-box::-webkit-scrollbar-thumb {
      background: #cbd5e1;
      border-radius: 8px;
    }
    .tab-active {
      background-color: #29842e;
      color: white;
      border-color: #29842e;
    }
    /* Fade animation for tab content */
    .tab-content {
      transition: opacity 0.2s ease-in-out;
      grid-area: tab;
    }
    .tab-inactive {
      opacity: 0;
      pointer-events: none;
    }
  </style>
</head>
<body class="bg-gray-50 flex flex-col min-h-screen">

  <!-- Top bar -->
  <div style="background-color: #29842e" class="h-11 grid grid-cols-3">
    <div class="flex"></div>
    <div class="flex" style="background-color: #29842e"></div>
    <div class="flex"></div>
  </div>

  <!-- Logo -->
  <div class="pl-3">
    <a href="/">
      <img src="Images/gsmc-logo.png" alt="goodsamaritan-logo" class="h-28 max-w-full">
    </a>
  </div>

  <div class="text-center py-10 px-4 bg-[#F6F6F6]">
    <div class="text-3xl font-semibold flex justify-center items-center gap-2">
      <span>Pricing</span>
      <span class="text-gray-400">|</span>
      <div class="flex">
        <span class="text-[#3A9F3F]">Health</span>
        <span class="text-[#188FD4] ml-1">Services</span>
      </div>
    </div>
    <div class="text-base text-gray-600 mt-1 italic">
      Note: Prices are subject to change.
    </div>
  </div>

  <div class="flex-grow max-w-8xl mx-auto px-4 py-8 w-full">

    <div class="flex justify-center space-x-2 border-b border-gray-200 mb-6">
      <button id="tabServices" class="text-[#188FD4] tab-button px-6 py-3 text-xl font-medium rounded-t-lg border border-transparent hover:bg-gray-100 focus:outline-none tab-active" data-tab="services">Services</button>
      <button id="tabSupplies" class="text-[#188FD4] tab-button px-6 py-3 text-xl font-medium rounded-t-lg border border-transparent hover:bg-gray-100 focus:outline-none" data-tab="supplies">Medical Supplies</button>
      <button id="tabMedicines" class="text-[#188FD4] tab-button px-6 py-3 text-xl font-medium rounded-t-lg border border-transparent hover:bg-gray-100 focus:outline-none" data-tab="medicines">Medicines</button>
    </div>

    <!-- Search and filter bar -->
    <div class="mt-4 flex flex-wrap items-center gap-3 bg-gray-100 p-3 rounded-lg">
      <span class="font-medium text-gray-700">Search</span>
      <input type="text" id="searchInput" placeholder="Search in description..." 
             class="flex-1 min-w-[200px] px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-green-200 focus:border-green-400">
      <button id="searchBtn" class="bg-[#29842e] hover:bg-green-700 text-white px-5 py-2 rounded-md text-sm font-medium">Go</button>

      <!-- Category dropdown -->
      <div class="relative max-w-sm w-full sm:w-auto">
        <select id="categorySelect" class="w-full appearance-none p-2 border border-gray-300 rounded-md pr-8 focus:outline-none focus:ring-2 focus:ring-blue-400">
          <option value="OPD CASH" selected>OPD CASH</option>
          <option value="ER-EMERGENCY">ER-EMERGENCY</option>
          <option value="WARD">WARD</option>
          <option value="SEMI-PRIVATE">SEMI-PRIVATE</option>
          <option value="PRIVATE">PRIVATE</option>
          <option value="SUITE/ICU">SUITE/ICU</option>
          <option value="HMO OPD">HMO OPD</option>
          <option value="HMO IPD">HMO IPD</option>
        </select>
        <div class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none">
          <svg class="h-4 w-4 text-gray-500" fill="none" stroke="black" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
          </svg>
        </div>
      </div>
    </div>

    <div class="tab-content-container grid mt-4" style="grid-template-areas: 'tab';">
      <!-- Services Tab -->
      <div id="servicesTab" class="tab-content">
        <div class="border border-gray-200 rounded-xl overflow-hidden shadow">
          <div class="overflow-auto max-h-80 scrollable-box">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
              <thead class="bg-gray-100 sticky top-0 z-10">
                <tr>
                  <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">DEPARTMENT</th>
                  <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Item Code</th>
                  <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Item Description</th>
                  <th class="px-4 py-3 text-right text-xs font-semibold text-gray-600 uppercase">Price (PHP)</th>
                </tr>
              </thead>
              <tbody id="servicesTableBody" class="bg-white divide-y divide-gray-100"></tbody>
            </table>
          </div>
        </div>
      </div>

      <!-- Medical Supplies Tab -->
      <div id="suppliesTab" class="tab-content tab-inactive">
        <div class="border border-gray-200 rounded-xl overflow-hidden shadow">
          <div class="overflow-auto max-h-80 scrollable-box">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
              <thead class="bg-gray-100 sticky top-0 z-10">
                <tr>
                  <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">DEPARTMENT</th>
                  <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Item Code</th>
                  <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Item Description</th>
                  <th class="px-4 py-3 text-right text-xs font-semibold text-gray-600 uppercase">Price (PHP)</th>
                </tr>
              </thead>
              <tbody id="suppliesTableBody" class="bg-white divide-y divide-gray-100"></tbody>
            </table>
          </div>
        </div>
      </div>

      <!-- Medicines Tab -->
      <div id="medicinesTab" class="tab-content tab-inactive">
        <div class="border border-gray-200 rounded-xl overflow-hidden shadow">
          <div class="overflow-auto max-h-80 scrollable-box">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
              <thead class="bg-gray-100 sticky top-0 z-10">
                <tr>
                  <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">DEPARTMENT</th>
                  <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Item Code</th>
                  <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Item Description</th>
                  <th class="px-4 py-3 text-right text-xs font-semibold text-gray-600 uppercase">Price (PHP)</th>
                </tr>
              </thead>
              <tbody id="medicinesTableBody" class="bg-white divide-y divide-gray-100"></tbody>
            </table>
          </div>
        </div>
      </div>
    </div>

  </div>

  <div style="background-color: #29842e" class="h-15 grid grid-cols-3 w-full">
    <div class="flex"></div>
    <div class="flex" style="background-color: #29842e">
      <div class="flex m-auto" style="color: white; font-size: 18px">Copyright 2025 - GoodSam Medical Center</div> 
    </div>
    <div class="flex"></div>
  </div>

</body>
</html>
