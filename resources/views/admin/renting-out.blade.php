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
<h1 class="h3 mb-2 text-gray-800">Pengajuan Menyewakan</h1>
<p class="mb-4">Data mobil user yang ingin menyewakan mobil.</p>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Data Mobil</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>Brand</th>
                        <th>Model</th>
                        <th>Year</th>
                        <th>Color</th>
                        <th>Mileage</th>
                        <th>DailyRentalRate</th>
                        <th>Status</th>
                        <th>Detail</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th>Brand</th>
                        <th>Model</th>
                        <th>Year</th>
                        <th>Color</th>
                        <th>Mileage</th>
                        <th>DailyRentalRate</th>
                        <th>Status</th>
                        <th>Detail</th>
                        <th>Aksi</th>
                    </tr>
                </tfoot>
                <tbody>
                    @foreach ($cars as $car)
                        <tr>
                            <td>{{ $car->brand }}</td>
                            <td>{{ $car->model }}</td>
                            <td>{{ $car->year }}</td>
                            <td>{{ $car->color }}</td>
                            <td>{{ $car->mileage }}</td>
                            <td>{{ $car->daily_rental_rate }}</td>
                            <td><p class="text-danger">{{ $car->status }}</p></td>
                            <td><button class="btn btn-info" data-toggle="modal" data-target="#userModal{{ $car->owner->id }}">
                                User
                            </button></td>
                            <td>
                                @if ($car->status  == 'not_available')
                                    <button class="btn btn-success" data-toggle="modal" data-target="#activeModal{{ $car->id }}">Setujui</button>
                                @endif
                            </td>
                        </tr>

                        <div class="modal fade" id="userModal{{ $car->owner->id }}" tabindex="-1" role="dialog" aria-labelledby="userModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="userModalLabel">Detail User</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <p><strong>Nama Lengkap:</strong> {{ $car->owner->name }}</p>
                                        <p><strong>Email:</strong> {{ $car->owner->email }}</p>
                                        <p><strong>No HP:</strong> {{ $car->owner->phone_number }}</p>
                                        <p><strong>Alamat:</strong> {{ $car->owner->address }}</p>
                                        <p><strong>NO SIM:</strong> {{ $car->owner->sim_number }}</p>
                                        <p><strong>Bergabung Sejak:</strong> {{ $car->owner->created_at }}</p>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="modal fade" id="activeModal{{ $car->id }}" tabindex="-1" role="dialog" aria-labelledby="activeModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="activeModalLabel">Konfirmasi Persetujuan</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <p>Menyetujui untuk menyewakan mobil mereka</p>
                                    </div>
                                    <div class="modal-footer">
                                        <form action="{{ url('/rent-out/active/' . $car->id) }}" method="POST">
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