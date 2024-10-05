@extends('layout.app')
@section('sidebar')
    @include('layout.sidebar.user')
@endsection
@section('content')
        <h1 class="h3 text-gray-800">Sewakan Mobil</h1>
        <p class="mb-4">Untuk menyewakan mobil anda masukan data-data mobil dibawah ini!</p>

        <form id="rentalForm">
            <div class="form-group">
                <label for="merek">Merek</label>
                <input type="text" class="form-control" id="merek" name="merek" required>
                <small id="merekError" class="text-danger"></small>
            </div>
            <div class="form-group">
                <label for="model">Model</label>
                <input type="text" class="form-control" id="model" name="model" required>
                <small id="modelError" class="text-danger"></small>
            </div>
            <div class="form-group">
                <label for="plateNumber">Plat Nomor</label>
                <input type="text" class="form-control" id="plateNumber" name="plate_number" required>
                <small id="plateNumberError" class="text-danger"></small>
            </div>
            <div class="form-group">
                <label for="year">Tahun Pembuatan</label>
                <input type="text" class="form-control" id="year" name="year" required>
                <small id="yearError" class="text-danger"></small>
            </div>
            <div class="form-group">
                <label for="color">Warna</label>
                <input type="text" class="form-control" id="color" name="color" required>
                <small id="colorError" class="text-danger"></small>
            </div>
            <div class="form-group">
                <label for="tarifSewa">Tarif sewa (Per Hari)</label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text">Rp</span>
                    </div>
                    <input type="text" class="form-control" id="tarifSewa" name="tarif_sewa" required oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                </div>
                <small id="tarifSewaError" class="text-danger"></small>
            </div>

            <button type="button" id="sewaButton" class="btn btn-primary" disabled>Sewakan</button>
        </form>

        <div id="statusMessage" class="mt-3 text-danger"></div>

        <!-- Modal Confirmation -->
        <div id="confirmModal" style="display:none;">
            <div class="modal-content">
                <h5>Konfirmasi Penyewaan</h5>
                <p>Apakah anda yakin untuk menyewakan mobil ini?</p>
                <button type="button" id="confirmYes" class="btn btn-success">Ya</button>
                <button type="button" id="confirmNo" class="btn btn-danger">Tidak</button>
            </div>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const merekInput = document.getElementById('merek');
                const modelInput = document.getElementById('model');
                const plateNumberInput = document.getElementById('plateNumber');
                const yearInput = document.getElementById('year');
                const colorInput = document.getElementById('color');
                const tarifSewaInput = document.getElementById('tarifSewa');
                const sewaButton = document.getElementById('sewaButton');
                const statusMessage = document.getElementById('statusMessage');
            
                // Error fields
                const merekError = document.getElementById('merekError');
                const modelError = document.getElementById('modelError');
                const plateNumberError = document.getElementById('plateNumberError');
                const yearError = document.getElementById('yearError');
                const colorError = document.getElementById('colorError');
                const tarifSewaError = document.getElementById('tarifSewaError');
            
                // Validasi plat nomor minimal 6 karakter dan maksimal 8 karakter
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
            
                // Validasi tahun harus angka dan 4 digit
                function validateYear() {
                    const yearValue = yearInput.value;
                    // Validasi tahun harus berupa angka dan memiliki 4 digit
                    if (!/^\d{4}$/.test(yearValue)) {
                        yearError.innerText = 'Tahun harus berupa angka, dan 4 digit.';
                        return false;
                    } else {
                        yearError.innerText = '';
                        return true;
                    }
                }
            
                // Validasi tarif sewa harus angka
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
            
                // Cek apakah semua field terisi dan plat nomor serta tahun valid
                function validateForm() {
                    let isValid = true;
            
                    // Merek dan model harus diisi
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
            
                    // Validasi plat nomor
                    if (!validatePlateNumber()) {
                        isValid = false;
                    }
            
                    // Validasi tahun
                    if (!validateYear()) {
                        isValid = false;
                    }
            
                    // Validasi tarif sewa
                    if (!validateTarifSewa()) {
                        isValid = false;
                    }
            
                    // Warna harus diisi
                    if (!colorInput.value) {
                        colorError.innerText = 'Warna wajib diisi.';
                        isValid = false;
                    } else {
                        colorError.innerText = '';
                    }
            
                    sewaButton.disabled = !isValid;
                }
            
                // Event listeners untuk input real-time validation
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
            
                // Konfirmasi saat menekan button sewa
                sewaButton.addEventListener('click', function() {
                    const confirmModal = document.getElementById('confirmModal');
                    confirmModal.style.display = 'block';  // Tampilkan modal
            
                    document.getElementById('confirmYes').addEventListener('click', function() {
                        // Submit atau simpan data form disini
                        alert('Mobil berhasil disewakan!');
                        confirmModal.style.display = 'none';  // Tutup modal setelah konfirmasi
                    });
            
                    document.getElementById('confirmNo').addEventListener('click', function() {
                        confirmModal.style.display = 'none';  // Tutup modal jika tidak jadi
                    });
                });
            });
            </script>

@endsection