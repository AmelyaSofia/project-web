<!-- include/sidebar.php -->
<aside class="w-64 bg-[#6d4c41] text-white fixed top-0 left-0 h-full p-6 space-y-6 shadow-lg z-50">
  <div class="text-xl font-serif font-bold text-center text-[#fbe9e7] tracking-wide">ROYAL SALON</div>
  <nav class="space-y-2 text-sm">
    <!-- Dashboard -->
    <a href="#" class="flex items-center gap-3 p-3 rounded-md hover:bg-[#5d4037] transition-all duration-200">
      <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 stroke-current" fill="none" viewBox="0 0 24 24">
        <path d="M3 13h8V3H3v10zm10 8h8v-6h-8v6zm0-8h8V3h-8v10zM3 21h8v-6H3v6z"/>
      </svg>
      <span>Dashboard</span>
    </a>

    <!-- Client -->
    <a href="../index.php?modul=client&fitur=list" class="flex items-center gap-3 p-3 rounded-md hover:bg-[#5d4037] transition-all duration-200">
      <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 stroke-current" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
        <circle cx="12" cy="7" r="4"></circle>
        <path d="M5.5 21a8.38 8.38 0 0113 0"></path>
      </svg>
      <span>Client</span>
    </a>

    <!-- Data Stylist -->
    <a href="../index.php?modul=stylist&fitur=list" class="flex items-center gap-3 p-3 rounded-md hover:bg-[#5d4037] transition-all duration-200">
      <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 stroke-current" fill="none" viewBox="0 0 24 24">
        <path d="M12 12c2.21 0 4-1.79 4-4S14.21 4 12 4 8 5.79 8 8s1.79 4 4 4z"/>
        <path d="M4 20v-1c0-2.21 3.58-4 8-4s8 1.79 8 4v1"/>
      </svg>
      <span>Data Stylist</span>
    </a>

    <!-- Layanan (scissors icon) -->
    <a href="../index.php?modul=layanan&fitur=list" class="flex items-center gap-3 p-3 rounded-md hover:bg-[#5d4037] transition-all duration-200">
      <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 stroke-current" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
        <circle cx="6" cy="6" r="3"></circle>
        <circle cx="6" cy="18" r="3"></circle>
        <line x1="20" y1="4" x2="8.12" y2="15.88"></line>
        <line x1="14.47" y1="14.48" x2="20" y2="20"></line>
        <line x1="8.12" y1="8.12" x2="12" y2="12"></line>
      </svg>
      <span>Layanan</span>
    </a>

    <!-- Booking -->
    <a href="#" class="flex items-center gap-3 p-3 rounded-md hover:bg-[#5d4037] transition-all duration-200">
      <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 stroke-current" fill="none" viewBox="0 0 24 24">
        <rect width="18" height="18" x="3" y="4" rx="2"/>
        <path d="M16 2v4M8 2v4M3 10h18"/>
      </svg>
      <span>Booking</span>
    </a>

    <!-- Logout -->
    <a href="logout.php" class="flex items-center gap-3 p-3 rounded-md hover:bg-red-600 transition-all duration-200 mt-4 text-red-200">
      <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 stroke-current" fill="none" viewBox="0 0 24 24">
        <path d="M15 3h4a2 2 0 012 2v14a2 2 0 01-2 2h-4"/>
        <path d="M10 17l5-5-5-5M15 12H3"/>
      </svg>
      <span>Logout</span>
    </a>
  </nav>
</aside>