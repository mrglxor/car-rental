<a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ route('customer') }}">
    <div class="sidebar-brand-icon rotate-n-15">
        <i class="fas fa-car"></i>
    </div>
    <div class="sidebar-brand-text mx-3">Sewa <sup>Mobil</sup></div>
</a>

<hr class="sidebar-divider my-0">

<li class="nav-item active">
    <a class="nav-link" href="{{ route('customer') }}">
        <i class="fas fa-fw fa-tachometer-alt"></i>
        <span>Dashboard</span>
    </a>
</li>

<hr class="sidebar-divider">

<div class="sidebar-heading">
    Feature
</div>

<li class="nav-item">
    <a class="nav-link collapsed" href="{{ route('sewa') }}">
        <i class="fas fa-fw fa-car"></i>
        <span>Sewa Mobil</span>
    </a>
</li>

<li class="nav-item">
    <a class="nav-link collapsed" href="{{ route('daftar-mobil') }}">
        <i class="fas fa-fw fa-list"></i>
        <span>Daftar Mobil</span>
    </a>
</li>

<li class="nav-item">
    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#informasi"
       aria-expanded="false" aria-controls="informasi">
        <i class="fas fa-fw fa-cog"></i>
        <span>Rental Manajemen</span>
    </a>
    <div id="informasi" class="collapse" aria-labelledby="headingInformasi" data-parent="#accordionSidebar">
        <div class="bg-white py-2 collapse-inner rounded">
            <h6 class="collapse-header">Halaman</h6>
            <a class="collapse-item" href="{{ route('rental') }}">Sedang Disewa</a>
            <a class="collapse-item" href="{{ route('return') }}">Pengembalian</a>
        </div>
    </div>
</li>

<li class="nav-item">
    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#carOwner"
       aria-expanded="false" aria-controls="carOwner">
        <i class="fas fa-fw fa-user"></i>
        <span>Car Owner</span>
    </a>
    <div id="carOwner" class="collapse" aria-labelledby="headingCarOwner" data-parent="#accordionSidebar">
        <div class="bg-white py-2 collapse-inner rounded">
            <h6 class="collapse-header">Halaman</h6>
            <a class="collapse-item" href="{{ route('owner') }}">Sewakan Mobil</a>
        </div>
    </div>
</li>
