@extends('layout.app')
@section('sidebar')
    @include('layout.sidebar.admin')
@endsection
@section('content')
@if(session('success'))
<div class="alert alert-success">
    {{ session('success') }}
</div>
@endif

@if(session('error'))
<div class="alert alert-danger">
    {{ session('error') }}
</div>
@endif
<h1 class="h3 mb-2 text-gray-800">Pengajuan Sewa</h1>
<p class="mb-4">Data rental user dan pengajuan sewa.</p>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Data Mobil</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>Mobil</th>
                        <th>Tanggal Mulai</th>
                        <th>Tanggal Selesai</th>
                        <th>Status</th>
                        <th>Detail</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th>Mobil</th>
                        <th>Tanggal Mulai</th>
                        <th>Tanggal Selesai</th>
                        <th>Status</th>
                        <th>Detail</th>
                        <th>Aksi</th>
                    </tr>
                </tfoot>
                <tbody>
                    @foreach ($rents as $rent)
                        <tr>
                            <td>{{ $rent->car->brand }} {{ $rent->car->model }} - {{ $rent->car->color }} ({{ $rent->car->plate_number }})</td>
                            <td>{{ $rent->start_date }}</td>
                            <td>{{ $rent->end_date }}</td>
                            <td>    
                            @php
                                $statusClass = '';
                                switch ($rent->status) {
                                    case 'pending':
                                        $statusClass = 'text-warning';
                                        break;
                                    case 'active':
                                        $statusClass = 'text-success';
                                        break;
                                    case 'canceled':
                                        $statusClass = 'text-danger';
                                        break;
                                    default:
                                        $statusClass = 'text-secondary';
                                }
                            @endphp
                            <span class="{{ $statusClass }}">{{ ucfirst($rent->status) }}</span></td>
                            <td>
                                <button class="btn btn-info" data-toggle="modal" data-target="#carModal{{ $rent->car->id }}">
                                    Mobil
                                </button>
                                <button class="btn btn-info" data-toggle="modal" data-target="#userModal{{ $rent->user->id }}">
                                    User
                                </button>
                            </td>
                            <td>
                                @if ($rent->status === "pending")
                                    <button class="btn btn-success" data-toggle="modal" data-target="#activeModal{{ $rent->id }}">Setujui</button>
                                    <button class="btn btn-danger" data-toggle="modal" data-target="#canceledModal{{ $rent->id }}">Batalkan</button>
                                @elseif ($rent->status === "active")
                                    <button class="btn btn-danger" data-toggle="modal" data-target="#canceledModal{{ $rent->id }}">Batalkan</button>
                                @else
                                    <p>-</p>
                                @endif
                            </td>
                        </tr>

                        <div class="modal fade" id="carModal{{ $rent->car->id }}" tabindex="-1" role="dialog" aria-labelledby="carModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="carModalLabel">Detail Mobil {{ $rent->car->brand }} {{ $rent->car->model }}</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <p><strong>Brand:</strong> {{ $rent->car->brand }}</p>
                                        <p><strong>Model:</strong> {{ $rent->car->model }}</p>
                                        <p><strong>Warna:</strong> {{ $rent->car->color }}</p>
                                        <p><strong>Tahun:</strong> {{ $rent->car->year }}</p>
                                        <p><strong>Kilometer:</strong> {{ $rent->car->mileage }}</p>
                                        <p><strong>Harga Sewa Per Hari:</strong> RP.{{ $rent->car->daily_rental_rate }}</p>
                                        <p><strong>Nomor Plat:</strong> {{ $rent->car->plate_number }}</p>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="modal fade" id="userModal{{ $rent->user->id }}" tabindex="-1" role="dialog" aria-labelledby="userModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="userModalLabel">Detail User</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <p><strong>Nama Lengkap:</strong> {{ $rent->user->name }}</p>
                                        <p><strong>Email:</strong> {{ $rent->user->email }}</p>
                                        <p><strong>No HP:</strong> {{ $rent->user->phone_number }}</p>
                                        <p><strong>Alamat:</strong> {{ $rent->user->address }}</p>
                                        <p><strong>NO SIM:</strong> {{ $rent->user->sim_number }}</p>
                                        <p><strong>Bergabung Sejak:</strong> {{ $rent->user->created_at }}</p>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="modal fade" id="canceledModal{{ $rent->id }}" tabindex="-1" role="dialog" aria-labelledby="canceledModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="canceledModalLabel">Konfirmasi Pembatalan</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <p>Pastikan <strong>CEK DATA</strong> terlebih dahulu sebelum memutuskan!</p>
                                        <p>Yakin ingin <strong class="text-danger">membatalkan</strong> pengajuan ini?</p>
                                    </div>
                                    <div class="modal-footer">
                                        <form action="{{ url('/rent/canceled/' . $rent->id) }}" method="POST">
                                            @csrf
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                                            <button type="submit" class="btn btn-danger">Batalkan</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="modal fade" id="activeModal{{ $rent->id }}" tabindex="-1" role="dialog" aria-labelledby="activeModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="activeModalLabel">Konfirmasi Persetujuan</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <p>Pastikan <strong>CEK DATA</strong> terlebih dahulu sebelum memutuskan!</p>
                                        <p>Yakin ingin <strong class="text-success">menyetujui</strong> pengajuan ini?</p>
                                    </div>
                                    <div class="modal-footer">
                                        <form action="{{ url('/rent/active/' . $rent->id) }}" method="POST">
                                            @csrf
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                                            <button type="submit" class="btn btn-success">Setujui</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection