<a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ route('admin') }}">
    <div class="sidebar-brand-icon rotate-n-15">
        <i class="fas fa-key"></i>
    </div>
    <div class="sidebar-brand-text mx-3">Sewa <sup>ADMIN</sup></div>
</a>

<hr class="sidebar-divider my-0">

<li class="nav-item active">
    <a class="nav-link" href="{{ route('admin') }}">
        <i class="fas fa-fw fa-tachometer-alt"></i>
        <span>Dashboard</span>
    </a>
</li>

<hr class="sidebar-divider">

<div class="sidebar-heading">
    Feature
</div>

<li class="nav-item">
    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#carOwner"
       aria-expanded="false" aria-controls="carOwner">
        <i class="fas fa-fw fa-cog"></i>
        <span>Konfirmasi Data</span>
    </a>
    <div id="carOwner" class="collapse" aria-labelledby="headingCarOwner" data-parent="#accordionSidebar">
        <div class="bg-white py-2 collapse-inner rounded">
            <h6 class="collapse-header">Halaman</h6>
            <a class="collapse-item" href="{{ route('renting') }}">Menyewa Mobil</a>
            <a class="collapse-item" href="{{ route('renting-out') }}">Menyewakan Mobil</a>
        </div>
    </div>
</li>
