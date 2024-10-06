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
        <h1 class="h3 mb-2 text-gray-800">Kontrol Data</h1>
        <p class="mb-4">Data Pengembalian (untuk saat ini hanya hapus saja)</p>

        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Data Pengembalian</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Condition</th>
                                <th>Comments</th>
                                <th>Total Fee</th>
                                <th>Dikembalikan</th>
                                <th>Detail</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th>Condition</th>
                                <th>Comments</th>
                                <th>Total Fee</th>
                                <th>Dikembalikan</th>
                                <th>Detail</th>
                                <th>Aksi</th>
                            </tr>
                        </tfoot>
                        <tbody>
                            @foreach ($returns as $return)
                                <tr>
                                    <td>{{ $return->condition }}</td>
                                    <td>{{ $return->comments ?: '-' }}</td>
                                    <td>{{ $return->total_fee }}</td>
                                    <td>{{ $return->returned_at }}</td>
                                    <td>
                                        <button class="btn btn-info" data-toggle="modal" data-target="#rentalModal{{ $return->rental->id }}">
                                            Rental
                                        </button>
                                        <button class="btn btn-info" data-toggle="modal" data-target="#carModal{{ $return->car->id }}">
                                            Mobil
                                        </button>
                                    </td>
                                    <td>
                                        <button class="btn btn-danger" data-toggle="modal" data-target="#deleteModal{{ $return->id }}">Hapus</button>
                                    </td>
                                </tr>

                                <div class="modal fade" id="carModal{{ $return->car->id }}" tabindex="-1" role="dialog" aria-labelledby="carModalLabel" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="carModalLabel">Detail Mobil {{ $return->car->brand }} {{ $return->car->model }}</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <p><strong>Brand:</strong> {{ $return->car->brand }}</p>
                                                <p><strong>Model:</strong> {{ $return->car->model }}</p>
                                                <p><strong>Warna:</strong> {{ $return->car->color }}</p>
                                                <p><strong>Tahun:</strong> {{ $return->car->year }}</p>
                                                <p><strong>Kilometer:</strong> {{ $return->car->mileage }}</p>
                                                <p><strong>Harga Sewa Per Hari:</strong> RP.{{ $return->car->daily_rental_rate }}</p>
                                                <p><strong>Nomor Plat:</strong> {{ $return->car->plate_number }}</p>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
        
                                <div class="modal fade" id="rentalModal{{ $return->rental->id }}" tabindex="-1" role="dialog" aria-labelledby="rentalModalLabel" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="rentalModalLabel">Detail Rental</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <p><strong>Tanggal Mulai:</strong> {{ $return->rental->start_date }}</p>
                                                <p><strong>Tanggal Selesai:</strong> {{ $return->rental->end_date }}</p>
                                                <p><strong>Status:</strong> {{ $return->rental->status }}</p>
                                                <p><strong>Dirental:</strong> {{ $return->rental->created_at }}</p>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="modal fade" id="deleteModal{{ $return->id }}" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="deleteModalLabel">Kontrol Data</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <p>Hapus data Pengembalian ini?</p>
                                            </div>
                                            <div class="modal-footer">
                                                <form action="{{ url('/delete/return/' . $return->id) }}" method="POST">
                                                    @csrf
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                                                    <button type="submit" class="btn btn-danger">Hapus</button>
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