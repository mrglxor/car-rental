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
    <h1 class="h3 text-gray-800">Sewakan Mobil</h1>
    <p class="mb-4">Untuk menyewakan mobil, masukkan data-data mobil di bawah ini! (<span class="text-info">Mode Dev</span>: data otomatis dibuat, min. edit 1 input untuk mengaktifkan tombol sewakan)</p>

    <form id="rentalForm" action="{{ route('ownerRental') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="merek">Merek</label>
            <input type="text" class="form-control" id="merek" name="merek" value="Testing" required>
            <small id="merekError" class="text-danger"></small>
        </div>
        <div class="form-group">
            <label for="model">Model</label>
            <input type="text" class="form-control" id="model" name="model" value="Testing" required>
            <small id="modelError" class="text-danger"></small>
        </div>
        <div class="form-group">
            <label for="plateNumber">Plat Nomor</label>
            <input type="text" class="form-control" id="plateNumber" name="plate_number" value="123456" required>
            <small id="plateNumberError" class="text-danger"></small>
        </div>
        <div class="form-group">
            <label for="year">Tahun Pembuatan</label>
            <input type="text" class="form-control" id="year" name="year" value="2024" required>
            <small id="yearError" class="text-danger"></small>
        </div>
        <div class="form-group">
            <label for="color">Warna</label>
            <input type="text" class="form-control" id="color" name="color" value="Merah" required>
            <small id="colorError" class="text-danger"></small>
        </div>
        <div class="form-group">
            <label for="mileage">Mileage (Kilometer)</label>
            <input type="number" class="form-control" id="mileage" name="mileage" value="1000" required>
            <small id="mileageError" class="text-danger"></small>
        </div>
        <div class="form-group">
            <label for="tarifSewa">Tarif sewa (Per Hari)</label>
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text">Rp</span>
                </div>
                <input type="text" class="form-control" id="tarifSewa" name="tarif_sewa" value="650000" required oninput="this.value = this.value.replace(/[^0-9]/g, '')">
            </div>
            <small id="tarifSewaError" class="text-danger"></small>
        </div>

        <button type="button" id="sewaButton" class="btn btn-primary" disabled>Sewakan</button>
    </form>

    <div id="statusMessage" class="mt-3 text-danger"></div>

    <div class="modal fade" id="confirmModal" tabindex="-1" role="dialog" aria-labelledby="confirmModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmModalLabel">Konfirmasi Penyewaan</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Apakah Anda yakin untuk menyewakan mobil ini?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tidak</button>
                    <button type="button" id="confirmYes" class="btn btn-success">Ya</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const merekInput = document.getElementById('merek');
            const modelInput = document.getElementById('model');
            const plateNumberInput = document.getElementById('plateNumber');
            const yearInput = document.getElementById('year');
            const colorInput = document.getElementById('color');
            const mileageInput = document.getElementById('mileage');
            const tarifSewaInput = document.getElementById('tarifSewa');
            const sewaButton = document.getElementById('sewaButton');
            const statusMessage = document.getElementById('statusMessage');

            const merekError = document.getElementById('merekError');
            const modelError = document.getElementById('modelError');
            const plateNumberError = document.getElementById('plateNumberError');
            const yearError = document.getElementById('yearError');
            const colorError = document.getElementById('colorError');
            const tarifSewaError = document.getElementById('tarifSewaError');
            const mileageError = document.getElementById('mileageError');

            function validatePlateNumber() {
                const plateValue = plateNumberInput.value;
                if (plateValue.length < 6 || plateValue.length > 8) {
                    plateNumberError.innerText = 'Plat nomor harus antara 6 hingga 8 karakter.';
                    return false;
                } else {
                    plateNumberError.innerText = '';
                    return true;
                }
            }

            function validateYear() {
                const yearValue = yearInput.value;
                if (!/^\d{4}$/.test(yearValue)) {
                    yearError.innerText = 'Tahun harus berupa angka dan 4 digit.';
                    return false;
                } else {
                    yearError.innerText = '';
                    return true;
                }
            }

            function validateTarifSewa() {
                const tarifValue = tarifSewaInput.value;
                if (!/^\d+$/.test(tarifValue)) {
                    tarifSewaError.innerText = 'Tarif sewa harus berupa angka.';
                    return false;
                } else {
                    tarifSewaError.innerText = '';
                    return true;
                }
            }

            function validateMileage() {
                const mileageValue = mileageInput.value;
                if (mileageValue < 0) {
                    mileageError.innerText = 'Mileage tidak boleh negatif.';
                    return false;
                } else {
                    mileageError.innerText = '';
                    return true;
                }
            }

            function validateForm() {
                let isValid = true;

                if (!merekInput.value) {
                    merekError.innerText = 'Merek wajib diisi.';
                    isValid = false;
                } else {
                    merekError.innerText = '';
                }

                if (!modelInput.value) {
                    modelError.innerText = 'Model wajib diisi.';
                    isValid = false;
                } else {
                    modelError.innerText = '';
                }

                if (!validatePlateNumber()) {
                    isValid = false;
                }

                if (!validateYear()) {
                    isValid = false;
                }

                if (!validateTarifSewa()) {
                    isValid = false;
                }

                if (!colorInput.value) {
                    colorError.innerText = 'Warna wajib diisi.';
                    isValid = false;
                } else {
                    colorError.innerText = '';
                }

                if (!validateMileage()) {
                    isValid = false;
                }

                sewaButton.disabled = !isValid;
            }

            merekInput.addEventListener('input', validateForm);
            modelInput.addEventListener('input', validateForm);
            plateNumberInput.addEventListener('input', () => {
                validateForm();
                validatePlateNumber();
            });
            yearInput.addEventListener('input', () => {
                validateForm();
                validateYear();
            });
            colorInput.addEventListener('input', validateForm);
            tarifSewaInput.addEventListener('input', () => {
                validateForm();
                validateTarifSewa();
            });
            mileageInput.addEventListener('input', () => {
                validateForm();
                validateMileage();
            });

            sewaButton.addEventListener('click', function() {
                $('#confirmModal').modal('show');

                document.getElementById('confirmYes').addEventListener('click', function() {
                    document.getElementById('rentalForm').submit();
                });
            });
        });
    </script>

@endsection
