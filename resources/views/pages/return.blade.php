@extends('layout.app')
@section('sidebar')
    @include('layout.sidebar.user')
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
    <h1 class="h3 mb-4 text-gray-800">Pengembalian Mobil</h1>

    <form id="returnForm" action="{{ route('rentPost') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="plateNumber">Plat Nomor</label>
            <input type="text" class="form-control" id="plateNumber" name="plate_number" required>
        </div>

        <input type="hidden" id="rentalId" name="rental_id">
        <input type="hidden" id="carId" name="car_id">
        <input type="hidden" id="totalFee" name="total_fee">
        <input type="hidden" id="returnedAt" name="returned_at">

        <button type="button" id="returnButton" class="btn btn-primary" disabled>Pengembalian</button>
    </form>

    <div id="statusMessage" class="mt-3 text-danger"></div>

    <div class="modal fade" id="returnModal" tabindex="-1" aria-labelledby="returnModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="returnModalLabel">Konfirmasi Pengembalian Mobil</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
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
        const rentals = @json($rentals);

        document.getElementById('plateNumber').addEventListener('input', function () {
            const plateNumber = this.value.trim();
            const returnButton = document.getElementById('returnButton');
            const statusMessage = document.getElementById('statusMessage');

            const rental = rentals.find(rental => rental.car.plate_number === plateNumber);
            if (rental) {
                const dailyRate = rental.car.daily_rental_rate;
                const totalCost = dailyRate * rental.duration;

                document.getElementById('rentalId').value = rental.id;
                document.getElementById('carId').value = rental.car.id;

                document.getElementById('rentalCost').innerText = `Rp ${totalCost.toFixed(2)}`;
                document.getElementById('rentalDuration').innerText = `${rental.duration} hari`;

                returnButton.disabled = false; 
                statusMessage.innerText = ''; 
            } else {
                returnButton.disabled = true; 
                document.getElementById('rentalCost').innerText = '';
                document.getElementById('rentalDuration').innerText = ''; 
                statusMessage.innerText = `Anda tidak menyewa mobil dengan plat nomor: ${plateNumber}`;
            }
        });

        document.getElementById('returnButton').addEventListener('click', function () {
            const returnModal = new bootstrap.Modal(document.getElementById('returnModal'));
            returnModal.show();

            document.getElementById('tutupModal').addEventListener('click', function() {
                returnModal.hide();
            });

            document.getElementById('closeModal').addEventListener('click', function() {
                returnModal.hide();
            });
        });

        document.getElementById('confirmReturn').addEventListener('click', function () {
            const totalCost = parseFloat(document.getElementById('rentalCost').innerText.replace(/Rp\s/g, '').replace(/,/g, ''));
            const currentDateTime = new Date().toISOString();
            
            document.getElementById('totalFee').value = totalCost.toFixed(2);
            document.getElementById('returnedAt').value = currentDateTime;

            console.log('Total Fee:', document.getElementById('totalFee').value);
            console.log('Returned At:', document.getElementById('returnedAt').value);


            document.getElementById('returnForm').submit();
        });
    </script>
@endsection
