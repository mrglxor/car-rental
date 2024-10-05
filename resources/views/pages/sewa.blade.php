<meta name="csrf-token" content="{{ csrf_token() }}">
<style>
        #flash-message {
            position: absolute;
            top: 70%; 
            left: 50%; 
            transform: translate(-50%, -50%);
            width: 80%; 
            max-width: 600px;
            padding: 15px;
            text-align: center;
            z-index: 9999;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); 
        }
        #flash-message.fade-out {
            opacity: 0;
        }
</style>
@extends('layout.app')
@section('sidebar')
    @include('layout.sidebar.user')
@endsection
@section('content')
        @if(session('message'))
            <div class="alert alert-success" id="flash-message">
                {{ session('message') }}
            </div>
        @endif
        <h1 class="h3 mb-4 text-gray-800">Sewa Mobil</h1>

        <form id="rentalForm" action="{{ route('rent') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="tanggal_mulai">Tanggal Mulai</label>
                <input type="date" class="form-control" id="tanggal_mulai" name="tanggal_mulai" required>
            </div>

            <div class="form-group">
                <label for="tanggal_selesai">Tanggal Selesai</label>
                <input type="date" class="form-control" id="tanggal_selesai" name="tanggal_selesai" required>
            </div>

            <div class="form-group">
                <label for="mobil">Pilih Mobil yang Tersedia</label>
                <select class="form-control" id="mobil" name="mobil" required>
                    <option value="">-- Pilih Mobil --</option>
                    @foreach($cars as $car)
                    <option value="{{ $car->id }}">{{ $car->brand }} {{ $car->model }} - {{ $car->color }} - {{ $car->year }} - {{ $car->plate_number }}</option>
                    @endforeach
                </select>
            </div>

            <button type="button" id="sewaButton" class="btn btn-primary" onclick="confirmRental()" disabled>Sewa</button>
        </form>

        <div id="statusMessage" class="mt-3 text-danger"></div>

    <script>

        document.addEventListener("DOMContentLoaded", function() {
            const flashMessage = document.getElementById('flash-message');
            if (flashMessage) {
                setTimeout(() => {
                    flashMessage.classList.add('fade-out');
                    setTimeout(() => flashMessage.remove(), 500);
                }, 5000);
            }
        });

        function confirmRental() {
            const confirmation = confirm("Anda yakin ingin menyewa mobil ini?");
            if (confirmation) {
                document.getElementById('rentalForm').submit();
            }
        }

        window.onload = function() {
            const today = new Date().toISOString().split('T')[0];
            document.getElementById('tanggal_mulai').setAttribute('min', today);
            document.getElementById('tanggal_mulai').value = today;
            document.getElementById('tanggal_selesai').setAttribute('min', today);
        };

        document.getElementById('rentalForm').addEventListener('input', function () {
            const tanggalMulai = document.getElementById('tanggal_mulai').value;
            const tanggalSelesai = document.getElementById('tanggal_selesai').value;
            const mobil = document.getElementById('mobil').value;
            const sewaButton = document.getElementById('sewaButton');
            const statusMessage = document.getElementById('statusMessage');

            if (tanggalSelesai && tanggalMulai && tanggalSelesai < tanggalMulai) {
                sewaButton.disabled = true;
                sewaButton.classList.remove('btn-danger');
                sewaButton.innerText = 'Sewa';
                statusMessage.innerText = 'Tanggal selesai tidak valid.';
                return;
            }

            if (tanggalMulai && tanggalSelesai && mobil) {
                checkCarAvailability(mobil)
                    .then(isAvailable => {
                        if (isAvailable) {
                            sewaButton.disabled = false;
                            sewaButton.classList.remove('btn-danger');
                            sewaButton.innerText = 'Sewa';
                            statusMessage.innerText = '';
                        } else {
                            sewaButton.disabled = true;
                            sewaButton.classList.add('btn-danger');
                            sewaButton.innerText = 'Tidak Tersedia';
                            statusMessage.innerText = 'Mobil sedang disewa atau dalam masa tinjau ataupun sedang dalam perbaikan.';
                        }
                    });
            } else {
                sewaButton.disabled = true;
                sewaButton.classList.remove('btn-danger');
                sewaButton.innerText = 'Sewa';
                statusMessage.innerText = '';
            }
        });


        async function checkCarAvailability( mobil) {
            try {

                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                const response = await fetch(`/is-available`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                    },
                    body: JSON.stringify({ mobil: mobil }),
                });

                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }

                const data = await response.json();
                return data.is_available;

            } catch (error) {
                console.error('Error fetching availability:', error);
                return false;
            }
        }
    </script>
@endsection