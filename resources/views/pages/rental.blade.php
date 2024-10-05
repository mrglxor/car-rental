@extends('layout.app')
@section('sidebar')
    @include('layout.sidebar.user')
@endsection
@section('content')
    <h1 class="h3 mb-2 text-gray-800">Sedang Disewa</h1>
    <p class="mb-4">Data mobil-mobil yang sedang anda sewa.</p>

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
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>Mobil</th>
                            <th>Tanggal Mulai</th>
                            <th>Tanggal Selesai</th>
                            <th>Status</th>
                            <th>Detail</th>
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
                                        Lihat Detail
                                    </button>
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
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
