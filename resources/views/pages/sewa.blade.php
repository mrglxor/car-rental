@extends('layout.app')
@section('sidebar')
    @include('layout.sidebar.user')
@endsection
@section('content')
        <h1 class="h3 mb-4 text-gray-800">Sewa Mobil</h1>

        <form id="rentalForm">
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
                    <!-- Contoh data mobil, bisa diambil dari database -->
                    <option value="mobil1">Mobil 1</option>
                    <option value="mobil2">Mobil 2</option>
                    <option value="mobil3">Mobil 3</option>
                </select>
            </div>

            <button type="button" id="sewaButton" class="btn btn-primary" disabled>Sewa</button>
        </form>

        <div id="statusMessage" class="mt-3 text-danger"></div>

    <script>
        // Mengupdate batas tanggal untuk input
        window.onload = function() {
            const today = new Date().toISOString().split('T')[0]; // Mendapatkan tanggal sekarang
            document.getElementById('tanggal_mulai').setAttribute('min', today);
            document.getElementById('tanggal_selesai').setAttribute('min', today);
        };

        // Mengupdate status tombol berdasarkan input
        document.getElementById('rentalForm').addEventListener('input', function () {
            const tanggalMulai = document.getElementById('tanggal_mulai').value;
            const tanggalSelesai = document.getElementById('tanggal_selesai').value;
            const mobil = document.getElementById('mobil').value;
            const sewaButton = document.getElementById('sewaButton');
            const statusMessage = document.getElementById('statusMessage');

            // Memastikan tanggal selesai tidak lebih kecil dari tanggal mulai
            if (tanggalSelesai && tanggalMulai && tanggalSelesai < tanggalMulai) {
                sewaButton.disabled = true;
                sewaButton.classList.remove('btn-danger');
                sewaButton.innerText = 'Sewa';
                statusMessage.innerText = 'Tanggal selesai tidak valid.';
                return;
            }

            if (tanggalMulai && tanggalSelesai && mobil) {
                // Cek ketersediaan mobil
                checkCarAvailability(tanggalMulai, tanggalSelesai, mobil)
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
                            statusMessage.innerText = 'Mobil tidak tersedia pada tanggal tersebut.';
                        }
                    });
            } else {
                sewaButton.disabled = true;
                sewaButton.classList.remove('btn-danger');
                sewaButton.innerText = 'Sewa';
                statusMessage.innerText = '';
            }
        });

        // Fungsi untuk memeriksa ketersediaan mobil
        async function checkCarAvailability(tanggalMulai, tanggalSelesai, mobil) {
            try {
                // const response = await fetch(`/api/check-car-availability`, {
                //     method: 'POST',
                //     headers: {
                //         'Content-Type': 'application/json',
                //     },
                //     body: JSON.stringify({ tanggal_mulai: tanggalMulai, tanggal_selesai: tanggalSelesai, mobil: mobil }),
                // });

                // if (!response.ok) {
                //     throw new Error('Network response was not ok');
                // }

                // const data = await response.json();
                // return data.is_available;
                return true; // asumsikan respons memiliki properti isAvailable
            } catch (error) {
                console.error('Error fetching availability:', error);
                return false; // jika ada error, asumsikan mobil tidak tersedia
            }
        }
    </script>
@endsection