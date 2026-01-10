<!-- Internal Booking Page Styles -->
<style>
/* Hide default navbar from header.php (navbar-expand class) */
nav.navbar.navbar-expand.fixed-top {
    display: none !important;
}

/* Reset body padding */
body {
    padding-top: 0 !important;
    margin-top: 0 !important;
}

/* Hero Section - Blue Gradient - directly below internal_navbar */
.hero-section-internal {
    background: linear-gradient(135deg, #2C5282 0%, #1A365D 100%);
    padding: 60px 0 60px 0;
    margin-top: 56px;
}

.hero-section-internal h1 {
    font-size: 2rem;
    font-weight: 700;
    color: white;
}

.hero-section-internal p {
    font-size: 0.95rem;
    color: rgba(255,255,255,0.9);
}

/* Lab Section */
.labs-section {
    background-color: #F7FAFC;
    padding: 40px 0;
}

/* Lab Card */
.lab-card {
    background: white;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
    transition: all 0.3s ease;
    height: 100%;
    border: 1px solid #E2E8F0;
}

.lab-card:hover {
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
    transform: translateY(-4px);
}

.lab-image {
    position: relative;
    width: 100%;
    height: 180px;
    overflow: hidden;
}

.lab-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

/* Badge - Inside image top right - GREEN for Tersedia */
.badge-status {
    position: absolute;
    top: 12px;
    right: 12px;
    padding: 6px 14px;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 600;
    color: white;
    z-index: 10;
}

.badge-tersedia {
    background-color: #48BB78;
}

.badge-terpakai {
    background-color: #ED8936;
}

.lab-card-body {
    padding: 16px;
}

.lab-name {
    font-size: 0.95rem;
    font-weight: 700;
    color: #1A202C;
    margin-bottom: 10px;
}

.lab-info {
    font-size: 0.8rem;
    color: #718096;
    margin-bottom: 4px;
    display: flex;
    align-items: center;
    gap: 6px;
}

.lab-info i {
    font-size: 0.85rem;
    color: #A0AEC0;
}

.btn-booking {
    background-color: #3182CE;
    border: none;
    border-radius: 8px;
    padding: 10px 20px;
    font-weight: 600;
    font-size: 0.85rem;
    color: white;
    width: 100%;
    margin-top: 12px;
}

.btn-booking:hover {
    background-color: #2C5282;
    color: white;
}

.btn-primary {
    background-color: #3182CE !important;
    border: none !important;
    border-radius: 8px !important;
}

.btn-primary:hover {
    background-color: #2C5282 !important;
}

</style>

<!-- Hero Section - Blue Gradient -->
<section class="hero-section-internal">
    <div class="container text-center">
        <h1>Booking Laboratorium</h1>
        <p class="mb-0">Pilih laboratorium dan waktu yang tersedia</p>
    </div>
</section>


<section class="labs-section py-5">
    <div class="container">
        <div class="row g-4">
            <!-- Lab Card 1 -->
            <div class="col-md-6 col-lg-4">
                <div class="lab-card">
                    <div class="lab-image">
                        <img src="<?= BASE_URL ?>public/img/StartUp.jpg" alt="Laboratorium Startup">
                        <span class="badge-status badge-tersedia">Tersedia</span>
                    </div>
                    <div class="lab-card-body">
                        <h5 class="lab-name">Laboratorium Startup</h5>
                        <p class="lab-info"><i class="bi bi-people-fill"></i> Kapasitas: 30 orang</p>
                        <p class="lab-info"><i class="bi bi-building"></i> Gedung F, Lantai 3</p>
                        <p class="lab-info"><i class="bi bi-person-badge"></i> Dr. Budi Santoso</p>
                        <button class="btn btn-primary w-100" onclick="openScheduleModal()">Booking</button>
                    </div>
                </div>
            </div>

            <!-- Lab Card 2 -->
            <div class="col-md-6 col-lg-4">
                <div class="lab-card">
                    <div class="lab-image">
                        <img src="<?= BASE_URL ?>public/img/IoT.jpg" alt="Laboratorium Internet of Things">
                        <span class="badge-status badge-terpakai">Terpakai</span>
                    </div>
                    <div class="lab-card-body">
                        <h5 class="lab-name">Laboratorium Internet of Things</h5>
                        <p class="lab-info"><i class="bi bi-people-fill"></i> Kapasitas: 25 orang</p>
                        <p class="lab-info"><i class="bi bi-building"></i> Gedung E, Lantai 1</p>
                        <p class="lab-info"><i class="bi bi-person-badge"></i> Dr. Budi Santoso</p>
                        <button class="btn btn-primary w-100" onclick="openScheduleModal()">Booking</button>
                    </div>
                </div>
            </div>

            <!-- Lab Card 3 -->
            <div class="col-md-6 col-lg-4">
                <div class="lab-card">
                    <div class="lab-image">
                        <img src="<?= BASE_URL ?>public/img/Mulmed.jpg" alt="Laboratorium Multimedia">
                        <span class="badge-status badge-tersedia">Tersedia</span>
                    </div>
                    <div class="lab-card-body">
                        <h5 class="lab-name">Laboratorium Multimedia</h5>
                        <p class="lab-info"><i class="bi bi-people-fill"></i> Kapasitas: 28 orang</p>
                        <p class="lab-info"><i class="bi bi-building"></i> Gedung F, Lantai 2</p>
                        <p class="lab-info"><i class="bi bi-person-badge"></i> Dr. Budi Santoso</p>
                        <button class="btn btn-primary w-100" onclick="openScheduleModal()">Booking</button>
                    </div>
                </div>
            </div>

            <!-- Lab Card 4 -->
            <div class="col-md-6 col-lg-4">
                <div class="lab-card">
                    <div class="lab-image">
                        <img src="<?= BASE_URL ?>public/img/comnet.png" alt="Laboratorium Computer Networking">
                        <span class="badge-status badge-tersedia">Tersedia</span>
                    </div>
                    <div class="lab-card-body">
                        <h5 class="lab-name">Laboratorium Computer Networking</h5>
                        <p class="lab-info"><i class="bi bi-people-fill"></i> Kapasitas: 30 orang</p>
                        <p class="lab-info"><i class="bi bi-building"></i> Gedung F, Lantai 3</p>
                        <p class="lab-info"><i class="bi bi-person-badge"></i> Dr. Budi Santoso</p>
                        <button class="btn btn-primary w-100" onclick="openScheduleModal()">Booking</button>
                    </div>
                </div>
            </div>

            <!-- Lab Card 5 -->
            <div class="col-md-6 col-lg-4">
                <div class="lab-card">
                    <div class="lab-image">
                        <img src="<?= BASE_URL ?>public/img/DS.jpg" alt="Laboratorium Data Science">
                        <span class="badge-status badge-tersedia">Tersedia</span>
                    </div>
                    <div class="lab-card-body">
                        <h5 class="lab-name">Laboratorium Data Science</h5>
                        <p class="lab-info"><i class="bi bi-people-fill"></i> Kapasitas: 32 orang</p>
                        <p class="lab-info"><i class="bi bi-building"></i> Gedung F, Lantai 1</p>
                        <p class="lab-info"><i class="bi bi-person-badge"></i> Dr. Budi Santoso</p>
                        <button class="btn btn-primary w-100" onclick="openScheduleModal()">Booking</button>
                    </div>
                </div>
            </div>

            <!-- Lab Card 6 -->
            <div class="col-md-6 col-lg-4">
                <div class="lab-card">
                    <div class="lab-image">
                        <img src="<?= BASE_URL ?>public/img/CV.jpg" alt="Laboratorium Computer Vision">
                        <span class="badge-status badge-tersedia">Tersedia</span>
                    </div>
                    <div class="lab-card-body">
                        <h5 class="lab-name">Laboratorium Computer Vision</h5>
                        <p class="lab-info"><i class="bi bi-people-fill"></i> Kapasitas: 20 orang</p>
                        <p class="lab-info"><i class="bi bi-building"></i> Gedung F, Lantai 2</p>
                        <p class="lab-info"><i class="bi bi-person-badge"></i> Dr. Budi Santoso</p>
                        <button class="btn btn-primary w-100" onclick="openScheduleModal()">Booking</button>
                    </div>
                </div>
            </div>

            <!-- Lab Card 7 -->
            <div class="col-md-6 col-lg-4">
                <div class="lab-card">
                    <div class="lab-image">
                        <img src="<?= BASE_URL ?>public/img/Micro.jpg" alt="Laboratorium Microcontroller" onerror="this.src='https://images.unsplash.com/photo-1581092918056-0c4c3acd3789?w=400&h=250&fit=crop'">
                        <span class="badge-status badge-tersedia">Tersedia</span>
                    </div>
                    <div class="lab-card-body">
                        <h5 class="lab-name">Laboratorium Microcontroller</h5>
                        <p class="lab-info"><i class="bi bi-people-fill"></i> Kapasitas: 24 orang</p>
                        <p class="lab-info"><i class="bi bi-building"></i> Gedung F, Lantai 3</p>
                        <p class="lab-info"><i class="bi bi-person-badge"></i> Dr. Budi Santoso</p>
                        <button class="btn btn-primary w-100" onclick="openScheduleModal()">Booking</button>
                    </div>
                </div>
            </div>

            <!-- Lab Card 8 -->
            <div class="col-md-6 col-lg-4">
                <div class="lab-card">
                    <div class="lab-image">
                        <img src="<?= BASE_URL ?>public/img/Riset.jpg" alt="Riset 2" onerror="this.src='https://images.unsplash.com/photo-1497366811353-6870744d04b2?w=400&h=250&fit=crop'">
                        <span class="badge-status badge-tersedia">Tersedia</span>
                    </div>
                    <div class="lab-card-body">
                        <h5 class="lab-name">Riset 2</h5>
                        <p class="lab-info"><i class="bi bi-people-fill"></i> Kapasitas: 28 orang</p>
                        <p class="lab-info"><i class="bi bi-building"></i> Gedung F, Lantai 3</p>
                        <p class="lab-info"><i class="bi bi-person-badge"></i> Dr. Budi Santoso</p>
                        <button class="btn btn-primary w-100" onclick="openScheduleModal()">Booking</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Embedded Critical Styles for Schedule Modal -->
<style>
    /* Nested Modal - bookingModal appears above scheduleModal */
    #bookingModal {
        z-index: 1065 !important;
    }
    
    #scheduleModal {
        z-index: 1055 !important;
    }
    
    /* Make sure bookingModal backdrop is above scheduleModal */
    .modal-backdrop + .modal-backdrop {
        z-index: 1060 !important;
    }
    
    /* Schedule Grid - 4 columns */
    .schedule-grid-container {
        display: grid !important;
        grid-template-columns: repeat(4, 1fr) !important;
        gap: 10px !important;
        margin-top: 12px !important;
    }
    
    @media (max-width: 768px) {
        .schedule-grid-container {
            grid-template-columns: repeat(2, 1fr) !important;
        }
    }
    
    /* Column Box - Proper size */
    .schedule-col {
        background: #fff !important;
        border: 1px solid #E2E8F0 !important;
        border-radius: 8px !important;
        padding: 14px !important;
        min-height: 280px !important;
    }
    
    .schedule-col-title {
        font-size: 0.85rem !important;
        font-weight: 700 !important;
        color: #2D3748 !important;
        margin-bottom: 12px !important;
    }
    
    /* Base Slot Item - Proper size */
    .slot-item {
        background: #EBF8FF !important;
        border: 1px solid #3182CE !important;
        border-radius: 6px !important;
        padding: 10px 12px !important;
        margin-bottom: 8px !important;
        cursor: pointer !important;
    }
    
    .slot-item:hover {
        opacity: 0.85 !important;
    }
    
    /* Praktikum Tetap - Blue */
    .slot-praktikum {
        background: #EBF8FF !important;
        border: 1px solid #3182CE !important;
    }
    
    /* Peminjaman Internal - Green */
    .slot-internal {
        background: #C6F6D5 !important;
        border: 1px solid #38A169 !important;
    }
    
    /* Peminjaman External - Red */
    .slot-external {
        background: #FED7D7 !important;
        border: 1px solid #E53E3E !important;
    }
    
    /* Jadwal Tergeser - Orange */
    .slot-tergeser {
        background: #FEEBC8 !important;
        border: 1px solid #DD6B20 !important;
    }
    
    /* Empty Slot - Dashed border */
    .slot-item-empty {
        background: #fff !important;
        border: 1px dashed #A0AEC0 !important;
        border-radius: 4px !important;
        padding: 6px 8px !important;
        margin-bottom: 6px !important;
        cursor: pointer !important;
        color: #3182CE !important;
        font-size: 0.65rem !important;
    }
    
    .slot-item-empty:hover {
        border-color: #3182CE !important;
        background: #F7FAFC !important;
    }
    
    /* Slot Labels - Very Small */
    .slot-time-label {
        color: #3182CE !important;
        font-weight: 600 !important;
        font-size: 0.65rem !important;
        white-space: normal !important;
        word-break: break-word !important;
    }
    
    .slot-class-label {
        color: #4A5568 !important;
        font-size: 0.65rem !important;
    }
    
    /* Legend Container */
    .legend-container {
        display: flex !important;
        flex-wrap: wrap !important;
        gap: 8px !important;
        margin-top: 16px !important;
        padding-top: 12px !important;
        border-top: 1px solid #E2E8F0 !important;
    }
    
    /* Legend Items - Small Pills */
    .legend-item {
        display: inline-flex !important;
        align-items: center !important;
        padding: 4px 10px !important;
        border-radius: 12px !important;
        font-size: 0.65rem !important;
        font-weight: 500 !important;
    }
    
    .legend-praktikum {
        background: #EBF8FF !important;
        border: 1px solid #3182CE !important;
        color: #2D3748 !important;
    }
    
    .legend-internal {
        background: #C6F6D5 !important;
        border: 1px solid #38A169 !important;
        color: #2D3748 !important;
    }
    
    .legend-eksternal {
        background: #FED7D7 !important;
        border: 1px solid #E53E3E !important;
        color: #2D3748 !important;
    }
    
    .legend-urgent {
        background: #FEEBC8 !important;
        border: 1px solid #DD6B20 !important;
        color: #2D3748 !important;
    }
    
    /* Date Input Styling */
    #scheduleDate {
        max-width: 140px !important;
        border: 1px solid #E2E8F0 !important;
        border-radius: 4px !important;
        padding: 5px 8px !important;
        font-size: 0.8rem !important;
    }
    
    /* Modal - Centered and properly sized */
    #scheduleModal .modal-dialog {
        max-width: 950px !important;
        margin: 15px auto !important;
    }
    
    #scheduleModal .modal-content {
        border-radius: 8px !important;
    }
    
    #scheduleModal .modal-header {
        padding: 12px 16px !important;
        border-bottom: 1px solid #E2E8F0 !important;
    }
    
    #scheduleModal .modal-body {
        padding: 20px 24px !important;
        overflow-y: auto !important;
        overflow-x: hidden !important;
    }
    
    #scheduleModal .modal-title {
        font-size: 1rem !important;
        font-weight: 700 !important;
    }
    
    /* Form label */
    #scheduleModal .form-label {
        font-size: 0.8rem !important;
        margin-bottom: 4px !important;
    }
    
    #scheduleModal .mb-4 {
        margin-bottom: 10px !important;
    }
</style>


<!-- Modal: Tambah Peminjaman (Schedule View) -->
<div class="modal fade" id="scheduleModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Peminjaman</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <!-- Date Picker -->
                <div class="mb-4">
                    <label class="form-label fw-semibold">Tanggal</label>
                    <input type="date" class="form-control date-input" id="scheduleDate" value="2025-12-24">
                </div>

                <!-- Schedule Grid -->
                <div class="schedule-grid-container">
                    <!-- Lab Start Up Column -->
                    <div class="schedule-col">
                        <div class="schedule-col-title">Lab Start Up</div>
                        <div class="slot-item slot-praktikum" onclick="openBookingModal('Lab Start Up', '10:30-14:30', 'P. Pemrograman (A1)')">
                            <div class="slot-time-label">Praktikum Tetap : 10:30-14:30</div>
                            <div class="slot-class-label">P. Pemrograman (A1)</div>
                        </div>
                        <div class="slot-item slot-praktikum" onclick="openBookingModal('Lab Start Up', '14:30-18:30', 'P. Pemrograman (A2)')">
                            <div class="slot-time-label">Praktikum Tetap : 14:30-18:30</div>
                            <div class="slot-class-label">P. Pemrograman (A2)</div>
                        </div>
                        <div class="slot-item-empty" onclick="openBookingModal('Lab Start Up', '07:00-10:30', 'Kosong')">
                            + Pinjam (Kosong 07:00-10:30)
                        </div>
                    </div>

                    <!-- Lab Internet of Things Column -->
                    <div class="schedule-col">
                        <div class="schedule-col-title">Lab Internet of Things</div>
                        <div class="slot-item slot-praktikum" onclick="openBookingModal('Lab Internet of Things', '14:30-18:29', 'P. Pemrograman (A4)')">
                            <div class="slot-time-label">Praktikum Tetap : 14:30-18:29</div>
                            <div class="slot-class-label">P. Pemrograman (A4)</div>
                        </div>
                        <div class="slot-item slot-internal">
                            <div class="slot-time-label">Peminjaman Internal : 09:00-11:00</div>
                            <div class="slot-class-label">Rapat Jurusan TI</div>
                        </div>
                        <div class="slot-item-empty" onclick="openBookingModal('Lab Internet of Things', '07:00-09:00', 'Kosong')">
                            + Pinjam (Kosong 07:00-09:00)
                        </div>
                    </div>

                    <!-- Lab Microcontroller Column -->
                    <div class="schedule-col">
                        <div class="schedule-col-title">Lab Microcontroller</div>
                        <div class="slot-item slot-praktikum" onclick="openBookingModal('Lab Microcontroller', '07:00-09:30', 'Microcontroller (B1)')">
                            <div class="slot-time-label">Praktikum Tetap : 07:00-09:30</div>
                            <div class="slot-class-label">Microcontroller (B1)</div>
                        </div>
                        <div class="slot-item slot-praktikum" onclick="openBookingModal('Lab Microcontroller', '09:40-12:10', 'Microcontroller (A7)')">
                            <div class="slot-time-label">Praktikum Tetap : 09:40-12:10</div>
                            <div class="slot-class-label">Microcontroller (A7)</div>
                        </div>
                        <div class="slot-item slot-external">
                            <div class="slot-time-label">Peminjaman Eksternal : 13:00-15:30</div>
                            <div class="slot-class-label">Pelatihan Polri Makassar</div>
                        </div>
                        <div class="slot-item-empty" onclick="openBookingModal('Lab Microcontroller', '12:10-13:00', 'Kosong')">
                            + Pinjam (Kosong 12:10-13:00)
                        </div>
                        <div class="slot-item-empty" onclick="openBookingModal('Lab Microcontroller', '15:30-18:25', 'Kosong')">
                            + Pinjam (Kosong 15:30-18:25)
                        </div>
                    </div>

                    <!-- Lab Computer Vision Column -->
                    <div class="schedule-col">
                        <div class="schedule-col-title">Lab Computer Vision</div>
                        <div class="slot-item slot-praktikum" onclick="openBookingModal('Lab Computer Vision', '09:40-12:10', 'Struktur Data (A7)')">
                            <div class="slot-time-label">Praktikum Tetap : 09:40-12:10</div>
                            <div class="slot-class-label">Struktur Data (A7)</div>
                        </div>
                        <div class="slot-item slot-praktikum" onclick="openBookingModal('Lab Computer Vision', '13:00-15:30', 'Struktur Data (A5)')">
                            <div class="slot-time-label">Praktikum Tetap : 13:00-15:30</div>
                            <div class="slot-class-label">Struktur Data (A5)</div>
                        </div>
                        <div class="slot-item slot-tergeser">
                            <div class="slot-time-label">Jadwal Tergeser : 07:00-09:40</div>
                            <div class="slot-class-label">Pindah dari Lab DS</div>
                        </div>
                        <div class="slot-item-empty" onclick="openBookingModal('Lab Computer Vision', '15:30-18:25', 'Kosong')">
                            + Pinjam (Kosong 15:30-18:25)
                        </div>
                    </div>
                </div>

                <!-- Second Row -->
                <div class="schedule-grid-container" style="margin-top: 16px;">
                    <!-- Lab Data Science Column -->
                    <div class="schedule-col">
                        <div class="schedule-col-title">Lab Data Science</div>
                        <div class="slot-item slot-praktikum" onclick="openBookingModal('Lab Data Science', '07:00-09:30', 'Basis Data II (B4)')">
                            <div class="slot-time-label">Praktikum Tetap : 07:00-09:30</div>
                            <div class="slot-class-label">Basis Data II (B4)</div>
                        </div>
                        <div class="slot-item slot-praktikum" onclick="openBookingModal('Lab Data Science', '09:40-12:15', 'Struktur Data (A8)')">
                            <div class="slot-time-label">Praktikum Tetap : 09:40-12:15</div>
                            <div class="slot-class-label">Struktur Data (A8)</div>
                        </div>
                        <div class="slot-item slot-praktikum" onclick="openBookingModal('Lab Data Science', '13:00-15:30', 'Struktur Data (A6)')">
                            <div class="slot-time-label">Praktikum Tetap : 13:00-15:30</div>
                            <div class="slot-class-label">Struktur Data (A6)</div>
                        </div>
                        <div class="slot-item-empty" onclick="openBookingModal('Lab Data Science', '12:15-13:00', 'Kosong')">
                            + Pinjam (Kosong 12:15-13:00)
                        </div>
                        <div class="slot-item-empty" onclick="openBookingModal('Lab Data Science', '15:30-18:25', 'Kosong')">
                            + Pinjam (Kosong 15:30-18:25)
                        </div>
                    </div>

                    <!-- Lab Computer Networking Column -->
                    <div class="schedule-col">
                        <div class="schedule-col-title">Lab Computer Networking</div>
                        <div class="slot-item-empty" onclick="openBookingModal('Lab Computer Networking', '07:00-18:25', 'Kosong')">
                            + Pinjam (Kosong 07:00-18:25)
                        </div>
                    </div>

                    <!-- Lab Multimedia Column -->
                    <div class="schedule-col">
                        <div class="schedule-col-title">Lab Multimedia</div>
                        <div class="slot-item-empty" onclick="openBookingModal('Lab Multimedia', '07:00-18:25', 'Kosong')">
                            + Pinjam (Kosong 07:00-18:25)
                        </div>
                    </div>

                    <!-- Riset 2 Column -->
                    <div class="schedule-col">
                        <div class="schedule-col-title">Riset 2</div>
                        <div class="slot-item-empty" onclick="openBookingModal('Riset 2', '07:00-18:25', 'Kosong')">
                            + Pinjam (Kosong 07:00-18:25)
                        </div>
                    </div>
                </div>

                <!-- Legend -->
                <div class="legend-container">
                    <span class="legend-item legend-praktikum">Praktikum Tetap</span>
                    <span class="legend-item legend-internal">Peminjaman Internal</span>
                    <span class="legend-item legend-eksternal">Peminjaman Eksternal</span>
                    <span class="legend-item legend-urgent">Jadwal Tergeser</span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal: Tambah Peminjaman (Internal) -->
<div class="modal fade" id="bookingModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 450px;">
        <div class="modal-content" style="border-radius: 12px; border: none; box-shadow: 0 10px 40px rgba(0,0,0,0.15);">
            <div class="modal-body" style="padding: 20px 18px;">
                <!-- Title -->
                <h5 style="font-size: 1rem; font-weight: 700; color: #1A202C; margin-bottom: 14px;">Tambah Peminjaman (Internal)</h5>
                
                <form id="bookingForm">
                    <!-- Tanggal & Hari -->
                    <div class="row" style="margin-bottom: 10px;">
                        <div class="col-6" style="padding-right: 6px;">
                            <label style="font-size: 0.7rem; color: #4A5568; margin-bottom: 4px; display: block;">Tanggal</label>
                            <input type="text" class="form-control" id="bookingDate" value="2025-12-24" readonly style="border: 1px solid #E2E8F0; border-radius: 6px; font-size: 0.8rem; padding: 8px 10px; background: #fff;">
                        </div>
                        <div class="col-6" style="padding-left: 6px;">
                            <label style="font-size: 0.7rem; color: #4A5568; margin-bottom: 4px; display: block;">Hari</label>
                            <input type="text" class="form-control" id="bookingDay" value="RABU" readonly style="border: 1px solid #E2E8F0; border-radius: 6px; font-size: 0.8rem; padding: 8px 10px; background: #fff;">
                        </div>
                    </div>
                    
                    <!-- Laboratorium -->
                    <div style="margin-bottom: 10px;">
                        <label style="font-size: 0.7rem; color: #4A5568; margin-bottom: 4px; display: block;">Laboratorium</label>
                        <input type="text" class="form-control" id="bookingLab" value="Lab IoT" readonly style="border: 1px solid #E2E8F0; border-radius: 6px; font-size: 0.8rem; padding: 8px 10px; background: #fff;">
                    </div>
                    
                    <!-- Jam Mulai & Jam Selesai -->
                    <div class="row" style="margin-bottom: 10px;">
                        <div class="col-6" style="padding-right: 6px;">
                            <label style="font-size: 0.7rem; color: #4A5568; margin-bottom: 4px; display: block;">Jam Mulai</label>
                            <input type="text" class="form-control" id="jamMulai" value="07.00" style="border: 1px solid #E2E8F0; border-radius: 6px; font-size: 0.8rem; padding: 8px 10px;">
                        </div>
                        <div class="col-6" style="padding-left: 6px;">
                            <label style="font-size: 0.7rem; color: #4A5568; margin-bottom: 4px; display: block;">Jam Selesai</label>
                            <input type="text" class="form-control" id="jamSelesai" value="08.00" style="border: 1px solid #E2E8F0; border-radius: 6px; font-size: 0.8rem; padding: 8px 10px;">
                        </div>
                    </div>
                    
                    <!-- Slot Info Box -->
                    <div style="background: #F7FAFC; border-radius: 6px; padding: 10px 12px; border: 1px solid #E2E8F0; margin-bottom: 10px;">
                        <div style="font-weight: 600; font-size: 0.75rem; color: #2D3748;">Slot kosong: 09:40-10:30</div>
                        <div style="font-size: 0.65rem; color: #718096;">Pilih jam mulai/selesai di dalam slot kosong.</div>
                    </div>
                    
                    <!-- Nama Peminjam -->
                    <div style="margin-bottom: 10px;">
                        <label style="font-size: 0.7rem; color: #4A5568; margin-bottom: 4px; display: block;">Nama Peminjam</label>
                        <input type="text" class="form-control" id="namaPeminjam" value="Admin" style="border: 1px solid #E2E8F0; border-radius: 6px; font-size: 0.8rem; padding: 8px 10px;">
                    </div>
                    
                    <!-- Nama Kegiatan -->
                    <div style="margin-bottom: 16px;">
                        <label style="font-size: 0.7rem; color: #4A5568; margin-bottom: 4px; display: block;">Nama Kegiatan</label>
                        <input type="text" class="form-control" id="namaKegiatan" value="Ujian Sertifikasi" style="border: 1px solid #E2E8F0; border-radius: 6px; font-size: 0.8rem; padding: 8px 10px;">
                    </div>
                    
                    <!-- Buttons -->
                    <div style="display: flex; justify-content: flex-end; gap: 10px;">
                        <button type="button" class="btn" data-bs-dismiss="modal" style="background: #fff; border: 1px solid #E2E8F0; color: #4A5568; padding: 8px 20px; border-radius: 6px; font-size: 0.75rem;">Batal</button>
                        <button type="button" class="btn" onclick="submitBooking()" style="background: #1E3A5F; color: #fff; border: none; padding: 8px 16px; border-radius: 6px; font-size: 0.75rem;">Simpan Peminjaman</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function openScheduleModal() {
    const modal = new bootstrap.Modal(document.getElementById('scheduleModal'));
    modal.show();
}

function openBookingModal(labName, timeSlot, className) {
    // Set the lab name in booking modal
    document.getElementById('bookingLab').value = labName;
    
    // Get or create booking modal instance
    let bookingModalEl = document.getElementById('bookingModal');
    let bookingModal = bootstrap.Modal.getInstance(bookingModalEl);
    
    if (!bookingModal) {
        bookingModal = new bootstrap.Modal(bookingModalEl, {
            backdrop: 'static',
            keyboard: false
        });
    }
    
    bookingModal.show();
}

function submitBooking() {
    const formData = {
        tanggal: document.getElementById('bookingDate').value,
        lab: document.getElementById('bookingLab').value,
        jamMulai: document.getElementById('jamMulai').value,
        jamSelesai: document.getElementById('jamSelesai').value,
        peminjam: document.getElementById('namaPeminjam').value,
        kegiatan: document.getElementById('namaKegiatan').value
    };
    
    console.log('Booking Data:', formData);
    alert('Peminjaman berhasil disimpan!\n\nLab: ' + formData.lab + '\nTanggal: ' + formData.tanggal + '\nWaktu: ' + formData.jamMulai + ' - ' + formData.jamSelesai);
    
    const modal = bootstrap.Modal.getInstance(document.getElementById('bookingModal'));
    modal.hide();
    document.getElementById('bookingForm').reset();
}
</script>
