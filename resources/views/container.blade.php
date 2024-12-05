<ul class="nav nav-tabs justify-content-center w-100 mt-3">
  <li class="nav-item">
      <a class="nav-link {{ Request::is('indexKaryawan') ? 'active' : '' }}" href="{{ url('/datakaryawan') }}">Data Karyawan</a>
  </li>
  <li class="nav-item">
      <a class="nav-link {{ Request::is('indexUpah') ? 'active' : '' }}" href="{{ url('/upah/upah') }}">Upah Karyawan</a>
  </li>
  <li class="nav-item">
    <a class="nav-link {{ Request::is('laporan') ? 'active' : '' }}" href="{{ url('/laporan') }}">Laporan Gaji Mingguan</a>
</li>
</ul>

<script>
document.addEventListener("DOMContentLoaded", function() {
  const navLinks = document.querySelectorAll('.nav-link');
  const currentPath = window.location.pathname;

  navLinks.forEach(link => {
    if (link.href.includes(currentPath)) {
      link.classList.add('active');
    }
  });
});
</script>