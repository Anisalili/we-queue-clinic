@extends('layouts.app')

@section('title', 'Registrasi Walk-in')

@section('content')
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Registrasi Pasien Walk-in</h3>
                <p class="text-subtitle text-muted">Daftarkan pasien yang datang langsung tanpa booking</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Registrasi Walk-in</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <section class="section">
        <div class="row">
            <div class="col-12 col-lg-8">
                <!-- Search Patient -->
                <div class="card mb-4">
                    <div class="card-header bg-primary text-white">
                        <h4 class="card-title mb-0 text-white">
                            <i class="bi bi-search"></i> Cari Pasien Existing
                        </h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-8 mb-3">
                                <label for="search-patient" class="form-label">Cari berdasarkan Nama atau Email</label>
                                <input type="text"
                                       class="form-control"
                                       id="search-patient"
                                       placeholder="Ketik nama atau email pasien..."
                                       autocomplete="off">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label d-block">&nbsp;</label>
                                <button type="button" class="btn btn-primary w-100" onclick="searchPatient()">
                                    <i class="bi bi-search"></i> Cari
                                </button>
                            </div>
                        </div>

                        <!-- Search Results -->
                        <div id="search-results" class="d-none">
                            <hr>
                            <h6>Hasil Pencarian:</h6>
                            <div id="patient-list"></div>
                        </div>
                    </div>
                </div>

                <!-- Registration Form -->
                <div class="card">
                    <div class="card-header bg-success text-white">
                        <h4 class="card-title mb-0 text-white">
                            <i class="bi bi-person-plus"></i> Form Registrasi Walk-in
                        </h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('patient.store-walkin') }}" method="POST" id="walkin-form">
                            @csrf

                            <input type="hidden" name="user_id" id="user_id" value="">
                            <input type="hidden" name="is_new_patient" id="is_new_patient" value="1">

                            <!-- Patient Info Section -->
                            <div id="patient-info-section">
                                <h5 class="mb-3">Data Pasien</h5>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="name" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                                        <input type="text"
                                               class="form-control @error('name') is-invalid @enderror"
                                               id="name"
                                               name="name"
                                               value="{{ old('name') }}"
                                               required>
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                        <input type="email"
                                               class="form-control @error('email') is-invalid @enderror"
                                               id="email"
                                               name="email"
                                               value="{{ old('email') }}"
                                               required>
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="phone" class="form-label">No. Telepon <span class="text-danger">*</span></label>
                                        <input type="text"
                                               class="form-control @error('phone') is-invalid @enderror"
                                               id="phone"
                                               name="phone"
                                               value="{{ old('phone') }}"
                                               required>
                                        @error('phone')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="date_of_birth" class="form-label">Tanggal Lahir</label>
                                        <input type="date"
                                               class="form-control @error('date_of_birth') is-invalid @enderror"
                                               id="date_of_birth"
                                               name="date_of_birth"
                                               value="{{ old('date_of_birth') }}">
                                        @error('date_of_birth')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="address" class="form-label">Alamat</label>
                                    <textarea class="form-control @error('address') is-invalid @enderror"
                                              id="address"
                                              name="address"
                                              rows="2">{{ old('address') }}</textarea>
                                    @error('address')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <hr>
                            </div>

                            <!-- Booking Info Section -->
                            <div>
                                <h5 class="mb-3">Informasi Booking</h5>

                                <div class="mb-3">
                                    <label for="patient_category" class="form-label">Kategori Pasien <span class="text-danger">*</span></label>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-check form-check-lg mb-2">
                                                <input class="form-check-input"
                                                       type="radio"
                                                       name="patient_category"
                                                       id="category-bpjs"
                                                       value="bpjs"
                                                       {{ old('patient_category') === 'bpjs' ? 'checked' : '' }}
                                                       required>
                                                <label class="form-check-label" for="category-bpjs">
                                                    <span class="badge bg-success me-2">BPJS</span>
                                                    Pasien dengan jaminan BPJS Kesehatan
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-check form-check-lg">
                                                <input class="form-check-input"
                                                       type="radio"
                                                       name="patient_category"
                                                       id="category-umum"
                                                       value="umum"
                                                       {{ old('patient_category') === 'umum' ? 'checked' : '' }}
                                                       required>
                                                <label class="form-check-label" for="category-umum">
                                                    <span class="badge bg-primary me-2">Umum</span>
                                                    Pasien dengan pembayaran mandiri
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <small class="text-muted">Antrian BPJS dan Umum digabung (FIFO)</small>
                                    @error('patient_category')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="notes" class="form-label">Catatan (Optional)</label>
                                    <textarea class="form-control"
                                              id="notes"
                                              name="notes"
                                              rows="2"
                                              placeholder="Keluhan atau catatan khusus...">{{ old('notes') }}</textarea>
                                </div>

                                <div class="alert alert-info">
                                    <i class="bi bi-info-circle"></i>
                                    <strong>Info:</strong> Pasien walk-in akan langsung mendapatkan nomor antrian
                                    dan masuk ke status <span class="badge bg-info">Menunggu</span>
                                </div>
                            </div>

                            <hr>

                            <div class="d-flex justify-content-between">
                                <button type="button" class="btn btn-secondary" onclick="resetForm()">
                                    <i class="bi bi-arrow-counterclockwise"></i> Reset
                                </button>
                                <button type="submit" class="btn btn-success btn-lg">
                                    <i class="bi bi-check-circle"></i> Daftar Walk-in
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Sidebar Info -->
            <div class="col-12 col-lg-4">
                <div class="card bg-light">
                    <div class="card-body">
                        <h5 class="card-title"><i class="bi bi-lightbulb"></i> Panduan Walk-in</h5>
                        <hr>

                        <p class="small"><strong>Langkah-langkah:</strong></p>
                        <ol class="small">
                            <li>Cari pasien existing (jika sudah pernah datang)</li>
                            <li>Atau isi data pasien baru</li>
                            <li>Pilih kategori (BPJS/Umum)</li>
                            <li>Klik "Daftar Walk-in"</li>
                            <li>Pasien otomatis dapat nomor antrian</li>
                        </ol>

                        <hr>

                        <div class="alert alert-success mb-0">
                            <small>
                                <strong>✓ Otomatis:</strong><br>
                                • Nomor antrian auto-generate<br>
                                • Status langsung "Menunggu"<br>
                                • Check-in time tercatat<br>
                                • Masuk ke antrian real-time
                            </small>
                        </div>
                    </div>
                </div>

                <!-- Today's Stats -->
                <div class="card mt-3">
                    <div class="card-body">
                        <h6><i class="bi bi-calendar-day"></i> Statistik Hari Ini</h6>
                        <hr>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="small">Total Pasien:</span>
                            <strong>{{ \App\Models\Booking::whereDate('booking_date', today())->whereNotIn('status', ['batal'])->count() }}</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="small">Walk-in:</span>
                            <strong>{{ \App\Models\Booking::whereDate('booking_date', today())->where('booking_type', 'walk-in')->whereNotIn('status', ['batal'])->count() }}</strong>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="small">Online Booking:</span>
                            <strong>{{ \App\Models\Booking::whereDate('booking_date', today())->where('booking_type', 'online')->whereNotIn('status', ['batal'])->count() }}</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
    // Search patient functionality
    function searchPatient() {
        const searchTerm = document.getElementById('search-patient').value;

        if (searchTerm.length < 3) {
            Toastify({
                text: "Minimal 3 karakter untuk pencarian",
                duration: 3000,
                close: true,
                gravity: "top",
                position: "right",
                backgroundColor: "#ffc107",
            }).showToast();
            return;
        }

        // AJAX search (implementation needed in backend)
        fetch(`/api/patients/search?q=${searchTerm}`)
            .then(response => response.json())
            .then(data => {
                displaySearchResults(data);
            })
            .catch(error => {
                console.error('Search error:', error);
            });
    }

    function displaySearchResults(patients) {
        const resultDiv = document.getElementById('search-results');
        const listDiv = document.getElementById('patient-list');

        if (patients.length === 0) {
            listDiv.innerHTML = '<p class="text-muted">Tidak ada pasien ditemukan. Silakan isi form sebagai pasien baru.</p>';
            resultDiv.classList.remove('d-none');
            return;
        }

        let html = '<div class="list-group">';
        patients.forEach(patient => {
            html += `
                <a href="#" class="list-group-item list-group-item-action" onclick="selectPatient(${patient.id}, '${patient.name}', '${patient.email}', '${patient.phone}', '${patient.date_of_birth || ''}', '${patient.address || ''}'); return false;">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <strong>${patient.name}</strong><br>
                            <small class="text-muted">${patient.email} • ${patient.phone || '-'}</small>
                        </div>
                        <span class="badge bg-primary">Pilih</span>
                    </div>
                </a>
            `;
        });
        html += '</div>';

        listDiv.innerHTML = html;
        resultDiv.classList.remove('d-none');
    }

    function selectPatient(id, name, email, phone, dob, address) {
        document.getElementById('user_id').value = id;
        document.getElementById('is_new_patient').value = '0';
        document.getElementById('name').value = name;
        document.getElementById('email').value = email;
        document.getElementById('phone').value = phone;
        document.getElementById('date_of_birth').value = dob;
        document.getElementById('address').value = address;

        // Disable patient fields
        document.getElementById('name').setAttribute('readonly', true);
        document.getElementById('email').setAttribute('readonly', true);
        document.getElementById('phone').setAttribute('readonly', true);

        // Hide search results
        document.getElementById('search-results').classList.add('d-none');

        Toastify({
            text: `Pasien "${name}" dipilih`,
            duration: 3000,
            close: true,
            gravity: "top",
            position: "right",
            backgroundColor: "#198754",
        }).showToast();

        // Scroll to category selection
        document.getElementById('category-bpjs').focus();
    }

    function resetForm() {
        document.getElementById('walkin-form').reset();
        document.getElementById('user_id').value = '';
        document.getElementById('is_new_patient').value = '1';
        document.getElementById('search-patient').value = '';
        document.getElementById('search-results').classList.add('d-none');

        // Enable all fields
        document.getElementById('name').removeAttribute('readonly');
        document.getElementById('email').removeAttribute('readonly');
        document.getElementById('phone').removeAttribute('readonly');
    }

    // Form validation
    document.getElementById('walkin-form').addEventListener('submit', function(e) {
        const category = document.querySelector('input[name="patient_category"]:checked');

        if (!category) {
            e.preventDefault();
            Toastify({
                text: "Pilih kategori pasien!",
                duration: 3000,
                close: true,
                gravity: "top",
                position: "right",
                backgroundColor: "#dc3545",
            }).showToast();
            return false;
        }
    });
</script>
@endsection
