<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
@extends('layout.app')
@section('sidebar')
    @include('layout.sidebar.user')
@endsection

@section('content')
    <h1 class="h3 mb-4 text-gray-800">Pengembalian Mobil</h1>

    <form id="returnForm">
        <div class="form-group">
            <label for="plateNumber">Plat Nomor</label>
            <input type="text" class="form-control" id="plateNumber" name="plate_number" required>
        </div>

        <button type="button" id="returnButton" class="btn btn-primary" disabled>Pengembalian</button>
    </form>

    <div id="statusMessage" class="mt-3 text-danger"></div>

    <div class="modal fade" id="returnModal" tabindex="-1" aria-labelledby="returnModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="returnModalLabel">Konfirmasi Pengembalian Mobil</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="closeModal"></button>
                </div>
                <div class="modal-body">
                    <p><strong>Jumlah Biaya Sewa:</strong> <span id="rentalCost"></span></p>
                    <p><strong>Durasi Sewa:</strong> <span id="rentalDuration"></span></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="tutupModal">Tutup</button>
                    <button type="button" class="btn btn-primary" id="confirmReturn">Kembalikan</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>

        const registeredPlateNumbers = ['ABC123', 'XYZ456', 'LMN789'];
        const rentalCostPerDay = 100;
        const rentalDuration = 3;

        async function checkPlateNumber(plateNumber) {
            return new Promise((resolve) => {
                setTimeout(() => {
                    const isRegistered = registeredPlateNumbers.includes(plateNumber);
                    resolve(isRegistered);
                }, 500);
            });
        }

        document.getElementById('returnForm').addEventListener('input', async function () {
            const plateNumber = document.getElementById('plateNumber').value.trim();
            const returnButton = document.getElementById('returnButton');
            const statusMessage = document.getElementById('statusMessage');

            // Validasi panjang plat nomor
            if (plateNumber.length < 6 || plateNumber.length > 8) {
                returnButton.disabled = true;
                returnButton.classList.remove('btn-danger');
                returnButton.innerText = 'Pengembalian';
                statusMessage.innerText = 'Plat nomor harus terdiri dari 6 hingga 8 karakter.';
            } else {
                const isRegistered = await checkPlateNumber(plateNumber);

                if (isRegistered) {
                    returnButton.disabled = false;
                    returnButton.classList.remove('btn-danger');
                    returnButton.innerText = 'Pengembalian';
                    statusMessage.innerText = '';
                } else {
                    returnButton.disabled = true;
                    returnButton.classList.add('btn-danger');
                    returnButton.innerText = 'Tidak Valid';
                    statusMessage.innerText = `Anda tidak menyewa mobil dengan plat nomor: ${plateNumber}`;
                }
            }
        });

        document.getElementById('returnButton').addEventListener('click', function () {
            const totalCost = rentalCostPerDay * rentalDuration;
            const durationMessage = `${rentalDuration} hari`;

            document.getElementById('rentalCost').innerText = `Rp ${totalCost}`;
            document.getElementById('rentalDuration').innerText = durationMessage;

            const returnModal = new bootstrap.Modal(document.getElementById('returnModal'));
            returnModal.show();

            document.getElementById('tutupModal').addEventListener('click', function(){
                returnModal.hide();
            });

            document.getElementById('closeModal').addEventListener('click', function(){
                returnModal.hide();
            });
        });

        document.getElementById('confirmReturn').addEventListener('click', function () {
            alert('Mobil berhasil dikembalikan!');
            bootstrap.Modal.getInstance(document.getElementById('returnModal')).hide();
            document.getElementById('returnForm').reset();
            document.getElementById('returnButton').disabled = true;
            document.getElementById('statusMessage').innerText = '';
        });

    </script>
@endsection
