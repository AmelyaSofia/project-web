<div class="bg-[var(--primary)] h-16 flex items-center justify-between px-6 fixed top-0 left-64 right-0">
  <h1 class="text-lg font-semibold">Dashboard Admin</h1>
  <div class="flex items-center gap-2">
    <span class="font-medium">Halo, <?= htmlspecialchars($_SESSION['username']) ?></span>
    <div class="w-8 h-8 rounded-full bg-[var(--secondary)] text-white flex items-center justify-center">
      <?= strtoupper(substr($_SESSION['username'], 0, 1)) ?>
    </div>
  </div>
</div>