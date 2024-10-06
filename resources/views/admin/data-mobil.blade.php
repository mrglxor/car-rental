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
        <p class="mb-4">Data mobil-mobil (untuk saat ini hanya hapus saja)</p>

        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Data Mobil</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Plate Number</th>
                                <th>Brand</th>
                                <th>Model</th>
                                <th>Year</th>
                                <th>Color</th>
                                <th>Mileage</th>
                                <th>DailyRentalRate</th>
                                <th>Status</th>
                                <th>Dibuat</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th>Plate Number</th>
                                <th>Brand</th>
                                <th>Model</th>
                                <th>Year</th>
                                <th>Color</th>
                                <th>Mileage</th>
                                <th>DailyRentalRate</th>
                                <th>Status</th>
                                <th>Dibuat</th>
                                <th>Aksi</th>
                            </tr>
                        </tfoot>
                        <tbody>
                            @foreach ($cars as $car)
                                <tr>
                                    <td>{{ $car->plate_number }}</td>
                                    <td>{{ $car->brand }}</td>
                                    <td>{{ $car->model }}</td>
                                    <td>{{ $car->year }}</td>
                                    <td>{{ $car->color }}</td>
                                    <td>{{ $car->mileage }}</td>
                                    <td>{{ $car->daily_rental_rate }}</td>
                                    <td>    
                                        @php
                                        $statusClass = '';
                                        switch ($car->status) {
                                            case 'maintenance':
                                            $statusClass = 'text-warning';
                                                break;
                                                case 'available':
                                                $statusClass = 'text-success';
                                                break;
                                                case 'not_available':
                                                $statusClass = 'text-danger';
                                                break;
                                                default:
                                                $statusClass = 'text-secondary';
                                            }
                                            @endphp
                                    <span class="{{ $statusClass }}">{{ ucfirst($car->status) }}</span>
                                    </td>
                                    <td>{{ $car->created_at }}</td>
                                    <td>
                                        <button class="btn btn-danger" data-toggle="modal" data-target="#deleteModal{{ $car->id }}">Hapus</button>
                                    </td>
                                </tr>

                                <div class="modal fade" id="deleteModal{{ $car->id }}" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="deleteModalLabel">Kontrol Data</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <p>Hapus data mobil ini?</p>
                                            </div>
                                            <div class="modal-footer">
                                                <form action="{{ url('/delete/car/' . $car->id) }}" method="POST">
                                                    @csrf
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                                                    <button type="submit" class="btn btn-danger">Batalkan</button>
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