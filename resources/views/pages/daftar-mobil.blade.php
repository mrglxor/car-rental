@extends('layout.app')
@section('sidebar')
    @include('layout.sidebar.user')
@endsection
@section('content')
        <h1 class="h3 mb-2 text-gray-800">Daftar</h1>
        <p class="mb-4">Seluruh Daftar mobil</p>

        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Data Mobil</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Nomor Plat</th>
                                <th>Merek</th>
                                <th>Model</th>
                                <th>Tahun</th>
                                <th>Warna</th>
                                <th>Jarak Tempuh</th>
                                <th>Tarif Per Hari</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th>Nomor Plat</th>
                                <th>Merek</th>
                                <th>Model</th>
                                <th>Tahun</th>
                                <th>Warna</th>
                                <th>Jarak Tempuh</th>
                                <th>Tarif Per Hari</th>
                                <th>Status</th>
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
                                        $status = '';
                                        switch ($car->status) {
                                                case 'maintenance':
                                                $statusClass = 'text-warning';
                                                $status = 'Perbaikan';
                                                break;
                                                case 'available':
                                                $statusClass = 'text-success';
                                                $status = 'Tersedia';
                                                break;
                                                case 'not_available':
                                                $statusClass = 'text-danger';
                                                $status = 'Dalam Proses';
                                                break;
                                                default:
                                                $statusClass = 'text-secondary';
                                                $status = 'Sedang Dirental';
                                            }
                                            @endphp
                                    <span class="{{ $statusClass }}">{{ $status }}</span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
@endsection