# 📚 MODUL INTERNAL - PANDUAN LENGKAP FRONT-END HINGGA BACK-END

> Dokumentasi lengkap untuk presentasi & pembelajaran PHP MVC, CRUD, Database Operations

---

## 🎯 OVERVIEW SISTEM

**Apa itu Modul Internal?**
Modul khusus untuk **Dosen, Tendik, dan Mahasiswa Internal** yang ingin:

- ✅ Booking Lab (membuat jadwal penggunaan lab)
- ✅ Lihat Jadwal (melihat semua lab + slot waktu)
- ✅ History (riwayat booking sendiri + edit/delete)
- ✅ Profile (lihat & edit data profil + upload foto)

---

## 📊 ARSITEKTUR SISTEM

```
┌─────────────────────────────────────────────────────────────┐
│                    FRONT-END (HTML/CSS/JS)                  │
│  Booking Form | Jadwal View | History List | Profile Form   │
└────────────────────┬────────────────────────────────────────┘
                     │ HTTP Request (GET/POST/PUT/DELETE)
                     ↓
┌─────────────────────────────────────────────────────────────┐
│              CONTROLLER (app/controllers/Internal.php)       │
│  - Validasi input user                                      │
│  - Koordinasi service & model                               │
│  - Return response (HTML/JSON)                              │
└────────────────────┬────────────────────────────────────────┘
                     │
        ┌────────────┼────────────┐
        ↓            ↓            ↓
    ┌────────┐  ┌──────────┐  ┌───────┐
    │Service │  │  Model   │  │Helper │
    │Layer   │  │(Database)│  │       │
    └────────┘  └──────────┘  └───────┘
        │            │
        └────────────┼────────────┐
                     ↓
┌─────────────────────────────────────────────────────────────┐
│              DATABASE (MySQL)                               │
│  users | ruangan | jadwal | peminjaman | kelas | matakuliah│
└─────────────────────────────────────────────────────────────┘
```

---

## 🚀 FLOW DETAIL - BOOKING LAB

### **Fase 1: User Membuka Halaman Booking**

```
1. User klik menu "Booking" di sidebar
   └─> Browser kirim GET request ke: /internal/booking

2. Controller Internal.php jalankan method booking()
   └─> $data = $this->bookingService->getCommonScheduleData('booking');
   └─> Ambil data jadwal lab yang tersedia

3. Controller render views:
   └─> internal_head.php (header, CSS, title)
   └─> internal_navbar.php (top navigation)
   └─> internal_sidebar.php (sidebar menu)
   └─> /internal/booking/index.php (FORM BOOKING)
   └─> internal_footer.php (footer, JS)

4. Browser tampilkan halaman dengan form booking
```

**Kode Real - Controller:**

```php
public function booking()
{
    $data = $this->bookingService->getCommonScheduleData('booking');
    $data['judul'] = 'Booking Laboratorium';

    $userId = $_SESSION['user_id'] ?? null;
    if ($userId) {
        $userModel = $this->model('UserModel');
        $data['current_user'] = $userModel->getUserById($userId);
    }

    $this->renderPage('/internal/booking/index', $data);  // ← Helper baru!
}
```

**Analogi**: Seperti membuka buku di perpustakaan:

- Kamu tanya librarian "Buku apa?"
- Librarian cari data buku, ambil buku, taruh di meja kamu
- Kamu baca buku itu

---

### **Fase 2: User Submit Form Booking**

```
1. User isi form:
   ✓ Pilih Lab: "Lab A"
   ✓ Tanggal: "2026-02-03"
   ✓ Jam Mulai: "09:00"
   ✓ Jam Selesai: "11:00"
   ✓ Nama Peminjam: "Budi Santoso"
   ✓ Nama Kegiatan: "Praktikum Basis Data"

2. User klik tombol "BOOKING" (AJAX)
   └─> JavaScript kirim POST request ke: /internal/submitBooking
   └─> Kirim data JSON ke backend

3. Content-Type: application/json
   ├─> tanggal: "2026-02-03"
   ├─> lab: "Lab A"
   ├─> jamMulai: "09:00"
   ├─> jamSelesai: "11:00"
   ├─> namaPeminjam: "Budi Santoso"
   └─> namaKegiatan: "Praktikum Basis Data"
```

**Kode Real - HTML Form:**

```html
<form id="bookingForm">
  <select name="lab" required>
    <option value="">-- Pilih Lab --</option>
    <option value="Lab A">Lab A</option>
    <option value="Lab B">Lab B</option>
  </select>

  <input type="date" name="tanggal" required />
  <input type="time" name="jamMulai" required />
  <input type="time" name="jamSelesai" required />
  <input type="text" name="namaPeminjam" required />
  <textarea name="namaKegiatan" required></textarea>

  <button type="submit">BOOKING</button>
</form>
```

**Kode Real - JavaScript AJAX:**

```javascript
document.getElementById("bookingForm").addEventListener("submit", function (e) {
  e.preventDefault();

  const formData = new FormData(this);

  fetch("/internal/submitBooking", {
    method: "POST",
    body: new URLSearchParams(formData),
  })
    .then((response) => response.json())
    .then((data) => {
      if (data.success) {
        alert("Booking berhasil!");
        location.reload();
      } else {
        alert("Error: " + data.message);
      }
    });
});
```

---

### **Fase 3: Backend Validasi & Simpan**

```
1. Controller terima POST request di method submitBooking()

2. STEP 1: Validasi Method
   if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
       ❌ Reject! Hanya POST yang diperbolehkan
   }

3. STEP 2: Ambil Input Data
   $formData = [
       'tanggal'      => $_POST['tanggal'],
       'labName'      => $_POST['lab'],
       'jamMulai'     => $_POST['jamMulai'],
       'jamSelesai'   => $_POST['jamSelesai'],
       'namaPeminjam' => $_POST['namaPeminjam'],
       'namaKegiatan' => $_POST['namaKegiatan']
   ];

4. STEP 3: Validasi Input (via Service)
   $validation = $this->bookingService->validateBookingInput($formData);
   if (!$validation['valid']) {
       ❌ Reject! Form tidak lengkap
   }

5. STEP 4: Cari Lab ID (dari nama lab)
   $labId = $this->bookingService->getLabIdByName('Lab A');
   if (!$labId) {
       ❌ Reject! Lab tidak ditemukan
   }

6. STEP 5: Cek Konflik dengan Jadwal Tetap (Praktikum)
   $scheduleConflict = $this->bookingService->checkScheduleConflict(...);
   if ($scheduleConflict) {
       ❌ Reject! Sudah ada praktikum di jam itu
   }

7. STEP 6: Validasi Waktu Operasional
   if (time < '07:00' OR time > '18:20') {
       ❌ Reject! Lab tutup jam 18:20
   }

8. STEP 7: Cek Konflik dengan Booking Lain
   if (sudah ada booking user lain jam yang sama) {
       ❌ Reject! Slot sudah terisi
   }

9. STEP 8: Siapkan Data Insert
   $bookingData = [
       'user_id'            => 5,
       'lab_id'             => 3,
       'tanggal_peminjaman' => '2026-02-03',
       'jam_mulai'          => '09:00',
       'jam_selesai'        => '11:00',
       'nama_peminjam'      => 'Budi Santoso',
       'kegiatan'           => 'Praktikum Basis Data',
       'tipe'               => 'internal',
       'status'             => 'disetujui',    ← AUTO-APPROVE!
       'catatan'            => ''
   ];

10. STEP 9: Simpan ke Database
    if ($this->bookingService->createBooking($bookingData)) {
        ✅ INSERT ke tabel `peminjaman` berhasil!

        // Kirim notifikasi WhatsApp ke Lab Admin (jika dosen)
        $this->sendWhatsappNotificationIfNeeded($labId, $formData);

        // Return response SUCCESS ke frontend
        return json_encode(['success' => true, 'message' => '...']);
    } else {
        ❌ Database error
    }
```

**Database Record yang Dibuat:**

```sql
INSERT INTO peminjaman (
    user_id,
    lab_id,
    tanggal_peminjaman,
    jam_mulai,
    jam_selesai,
    nama_peminjam,
    kegiatan,
    tipe,
    status,
    catatan,
    created_at
) VALUES (
    5,
    3,
    '2026-02-03',
    '09:00',
    '11:00',
    'Budi Santoso',
    'Praktikum Basis Data',
    'internal',
    'disetujui',
    '',
    NOW()
);

/* Hasil: INSERT dengan ID = 25 */
```

**Tabel peminjaman di Database:**

```
ID  | user_id | lab_id | tanggal | jam_mulai | jam_selesai | status      | tipe
----|---------|--------|---------|-----------|-------------|-------------|----------
25  | 5       | 3      | 2026-02-03 | 09:00   | 11:00      | disetujui   | internal
```

---

### **Fase 4: Frontend Terima Response**

```
1. Backend kirim response JSON:
   {
       "success": true,
       "message": "Booking berhasil dibuat!"
   }

2. JavaScript (AJAX) terima:
   if (data.success) {
       ✅ Tampilkan alert "Booking berhasil!"
       ✅ Reload halaman (user lihat booking baru mereka)
   } else {
       ❌ Tampilkan error message
   }
```

---

## 📋 FLOW DETAIL - HISTORY (Lihat & Edit & Delete)

### **Fase 1: User Buka Halaman History**

```
1. User klik "History" di sidebar
   └─> Browser kirim GET /internal/history

2. Controller method history() dijalankan
   └─> Ambil booking milik user LOGIN dari database:
       SELECT * FROM peminjaman WHERE user_id = 5

3. Return 3 booking:
   - ID 25 | Lab A | 2026-02-03 | 09:00-11:00 | Budi Santoso
   - ID 24 | Lab B | 2026-02-02 | 14:00-16:00 | Budi Santoso
   - ID 23 | Lab C | 2026-02-01 | 10:00-12:00 | Budi Santoso

4. Render di view:
   ├─ Header, Navbar, Sidebar
   ├─ Tabel History dengan:
   │  ├─ Nomor
   │  ├─ Lab Name
   │  ├─ Tanggal & Jam
   │  ├─ Status (badge hijau/merah)
   │  ├─ Tombol EDIT
   │  └─ Tombol DELETE
   └─ Footer
```

**Kode Real - Controller:**

```php
public function history()
{
    $data['judul'] = 'Data Peminjaman Saya';
    $data['active_page'] = 'history';
    $userId = $_SESSION['user_id'] ?? null;

    // Query: SELECT * FROM peminjaman WHERE user_id = 5
    $data['peminjaman'] = $userId ? $this->bookingService->getBookingByUserId($userId) : [];

    $this->renderPage('/internal/history/index', $data);
}
```

**Kode Real - View (Tabel):**

```html
<table>
  <thead>
    <tr>
      <th>No</th>
      <th>Lab</th>
      <th>Tanggal</th>
      <th>Jam</th>
      <th>Status</th>
      <th>Aksi</th>
    </tr>
  </thead>
  <tbody>
    <?php $no = 1; foreach($peminjaman as $p): ?>
    <tr>
      <td><?= $no++ ?></td>
      <td><?= $p['lab_name'] ?></td>
      <td><?= $p['tanggal_peminjaman'] ?></td>
      <td>
        <?= $p['jam_mulai'] ?>
        - <?= $p['jam_selesai'] ?>
      </td>
      <td>
        <span
          class="badge <?= $p['status'] == 'disetujui' ? 'success' : 'warning' ?>"
        >
          <?= ucfirst($p['status']) ?>
        </span>
      </td>
      <td>
        <button onclick="editBooking(<?= $p['id'] ?>)">EDIT</button>
        <button onclick="deleteBooking(<?= $p['id'] ?>)" class="btn-danger">
          DELETE
        </button>
      </td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>
```

---

### **Fase 2: User Edit Booking**

```
1. User klik tombol EDIT untuk booking ID 25

2. Modal popup muncul dengan data:
   ✓ Lab: Lab A
   ✓ Tanggal: 2026-02-03
   ✓ Jam Mulai: 09:00
   ✓ Jam Selesai: 11:00
   ✓ Keterangan: Praktikum Basis Data

3. User ubah jam dari 09:00 menjadi 10:00

4. User klik "SIMPAN PERUBAHAN" (AJAX)
   └─> JavaScript kirim PUT request ke /internal/updatePeminjaman
   └─> JSON body:
       {
           "id": 25,
           "tanggal": "2026-02-03",
           "jam_mulai": "10:00",      ← BERUBAH
           "jam_selesai": "11:00",
           "keterangan": "Praktikum Basis Data"
       }
```

**Kode Real - JavaScript Update:**

```javascript
function editBooking(id) {
  // 1. Show modal dengan data lama dari backend
  const booking = getBookingData(id); // Ambil dari view

  // 2. Fill form di modal
  document.getElementById("editId").value = booking.id;
  document.getElementById("editTanggal").value = booking.tanggal;
  document.getElementById("editJamMulai").value = booking.jam_mulai;
  document.getElementById("editJamSelesai").value = booking.jam_selesai;

  // 3. Show modal
  document.getElementById("editModal").style.display = "block";
}

function saveEdit() {
  const data = {
    id: document.getElementById("editId").value,
    tanggal: document.getElementById("editTanggal").value,
    jam_mulai: document.getElementById("editJamMulai").value,
    jam_selesai: document.getElementById("editJamSelesai").value,
    keterangan: document.getElementById("editKeterangan").value,
  };

  fetch("/internal/updatePeminjaman", {
    method: "PUT",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify(data),
  })
    .then((response) => response.json())
    .then((data) => {
      if (data.success) {
        alert("Update berhasil!");
        location.reload();
      }
    });
}
```

---

### **Fase 3: Backend Update Database**

```
1. Controller method updatePeminjaman() menerima PUT request

2. STEP 1: Ambil data dari JSON body
   $input = json_decode(file_get_contents('php://input'), true);
   /*
       Array (
           [id] => 25
           [tanggal] => 2026-02-03
           [jam_mulai] => 10:00
           [jam_selesai] => 11:00
           [keterangan] => Praktikum Basis Data
       )
   */

3. STEP 2: Validasi Kepemilikan
   $peminjaman = $this->bookingService->getBookingById(25);

   if ($peminjaman['user_id'] != $_SESSION['user_id']) {
       ❌ Reject! User tidak punya hak edit booking orang lain
   }

4. STEP 3: Update Database
   UPDATE peminjaman SET
       jam_mulai = '10:00',
       jam_selesai = '11:00',
       kegiatan = 'Praktikum Basis Data'
   WHERE id = 25;

5. STEP 4: Return Response
   {
       "success": true,
       "message": "Update berhasil!"
   }
```

**SQL Query Real:**

```sql
UPDATE peminjaman
SET
    tanggal_peminjaman = '2026-02-03',
    jam_mulai = '10:00',
    jam_selesai = '11:00',
    kegiatan = 'Praktikum Basis Data'
WHERE id = 25 AND user_id = 5;

/* Hasil: 1 row affected */
```

---

### **Fase 4: User Delete Booking**

```
1. User klik tombol DELETE untuk booking ID 25

2. Confirm dialog: "Yakin hapus booking?"

3. User klik OK, kirim DELETE request via AJAX
   └─> /internal/deletePeminjaman
   └─> JSON body:
       {
           "id": 25
       }

4. Backend proses:
   - Validasi kepemilikan (user_id harus cocok)
   - DELETE FROM peminjaman WHERE id = 25
   - Return success

5. Frontend reload halaman
   └─> Booking hilang dari tabel
```

**Kode Real - JavaScript Delete:**

```javascript
function deleteBooking(id) {
  if (confirm("Yakin hapus booking ini?")) {
    fetch("/internal/deletePeminjaman", {
      method: "DELETE",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ id: id }),
    })
      .then((response) => response.json())
      .then((data) => {
        if (data.success) {
          alert("Booking dihapus!");
          location.reload();
        }
      });
  }
}
```

**Kode Real - Controller Delete:**

```php
public function deletePeminjaman()
{
    header('Content-Type: application/json');

    $input = json_decode(file_get_contents('php://input'), true);
    $userId = $_SESSION['user_id'] ?? null;

    if (!$userId) {
        echo json_encode(['success' => false, 'message' => 'Unauthorized']);
        return;
    }

    // Validasi kepemilikan
    $peminjaman = $this->bookingService->getBookingById($input['id']);
    if (!$peminjaman || $peminjaman['user_id'] != $userId) {
        echo json_encode(['success' => false, 'message' => 'Tidak diizinkan menghapus data ini']);
        return;
    }

    // DELETE
    $result = $this->bookingService->deleteBooking($input['id']);
    echo json_encode(['success' => $result]);
}
```

**SQL Query Delete:**

```sql
DELETE FROM peminjaman
WHERE id = 25 AND user_id = 5;

/* Hasil: 1 row deleted */
```

---

## 👤 FLOW DETAIL - PROFILE (Upload Foto & Edit Data)

### **Fase 1: User Buka Profile**

```
1. User klik "Profile" di sidebar
   └─> GET /internal/profile

2. Controller method profile() ambil user data:
   SELECT * FROM users WHERE id = 5

3. Return data user:
   {
       "id": 5,
       "nama": "Budi Santoso",
       "email": "budi@student.com",
       "telepon": "08123456789",
       "foto": "profile_5_1706865000.jpg",
       "nim": "2023001",
       "jurusan": "Teknik Informatika"
   }

4. Render profile view dengan:
   ├─ Foto profil (bisa cropped)
   ├─ Form edit nama, email, telepon
   ├─ Tombol Upload Foto Baru
   └─ Tombol Simpan
```

**Kode Real - Controller:**

```php
public function profile()
{
    $data['judul'] = 'Profil Saya';
    $data['active_page'] = 'profile';

    $userModel = $this->model('UserModel');
    $data['user'] = $userModel->getUserById($_SESSION['user_id']);

    $this->renderPage('internal/profile/index', $data);
}
```

---

### **Fase 2: User Upload & Crop Foto**

```
1. User klik input file untuk upload foto

2. User pilih foto dari komputer
   └─> Browser load gambar

3. JavaScript library "Cropper.js" tampilkan:
   ├─ Preview gambar
   ├─ Area selection untuk crop
   ├─ Tombol rotate, zoom, dll

4. User crop foto sesuai keinginan
   └─> Convert ke Base64 string

5. User klik "UPLOAD FOTO BARU" (AJAX)
   └─> Kirim POST dengan cropped image
   └─> Content: Base64 encoded image

6. Form data:
   POST /internal/prosesUpdateProfile
   {
       "cropped_image": "data:image/jpeg;base64,/9j/4AAQSkZJRgABA..."
       "nama": "Budi Santoso",
       "email": "budi@student.com",
       "telepon": "08123456789"
   }
```

**Kode Real - View (Upload Form):**

```html
<form id="profileForm" method="POST" enctype="multipart/form-data">
  <!-- Foto Upload -->
  <input type="file" id="fotoInput" accept="image/*" />
  <div id="cropperContainer">
    <img id="cropperImage" src="" />
  </div>
  <button type="button" onclick="cropImage()">Crop & Upload</button>

  <!-- Form Edit Data -->
  <input type="text" name="nama" value="<?= $user['nama'] ?>" />
  <input type="email" name="email" value="<?= $user['email'] ?>" />
  <input type="tel" name="telepon" value="<?= $user['telepon'] ?>" />

  <!-- Simpan base64 di hidden input -->
  <input type="hidden" name="cropped_image" id="croppedImage" />

  <button type="submit">SIMPAN PERUBAHAN</button>
</form>
```

**Kode Real - JavaScript Cropper:**

```javascript
let cropper = null;

document.getElementById("fotoInput").addEventListener("change", function (e) {
  const file = e.target.files[0];
  const reader = new FileReader();

  reader.onload = function (event) {
    const img = document.getElementById("cropperImage");
    img.src = event.target.result;

    // Initialize Cropper
    cropper = new Cropper(img, {
      aspectRatio: 1,
      viewMode: 1,
    });
  };

  reader.readAsDataURL(file);
});

function cropImage() {
  const canvas = cropper.getCroppedCanvas();
  const croppedImage = canvas.toDataURL("image/jpeg");

  // Simpan ke hidden input
  document.getElementById("croppedImage").value = croppedImage;

  // Submit form
  document.getElementById("profileForm").submit();
}
```

---

### **Fase 3: Backend Proses Upload & Simpan**

```
1. Controller method prosesUpdateProfile() terima POST

2. STEP 1: Ambil foto lama dari database
   $userLama = $userModel->getUserById($userId);
   $foto = $userLama['foto'];  // Misal: "profile_5_1706865000.jpg"

3. STEP 2: Cek apakah ada foto baru (Base64)
   if (!empty($_POST['cropped_image'])) {

       // a. Decode Base64
       $data_uri = $_POST['cropped_image'];
       // "data:image/jpeg;base64,/9j/4AAQSkZJRgABA..."

       $encoded_image = explode(",", $data_uri)[1];
       // "/9j/4AAQSkZJRgABA..."

       $decoded_image = base64_decode($encoded_image);
       // Binary image data

       // b. Buat nama file baru
       $namaFileBaru = 'profile_' . $userId . '_' . time() . '.jpg';
       // Misal: "profile_5_1706900000.jpg"

       // c. Tentukan folder upload
       $targetDir = __DIR__ . '/../../public/storage/uploads/profile/';
       // /Applications/XAMPP/xamppfiles/htdocs/Sistem-Peminjaman-Lab/public/storage/uploads/profile/

       // d. Buat folder jika belum ada
       if (!file_exists($targetDir)) {
           mkdir($targetDir, 0755, true);
       }

       // e. Simpan file
       if (file_put_contents($targetDir . $namaFileBaru, $decoded_image)) {

           // f. Hapus foto lama dari server
           if ($foto && file_exists($targetDir . $foto)) {
               unlink($targetDir . $foto);  // Hapus file lama
           }

           // g. Update variabel $foto
           $foto = $namaFileBaru;
       }
   }

4. STEP 3: Update data user di database
   UPDATE users SET
       nama = 'Budi Santoso',
       email = 'budi@student.com',
       telepon = '08123456789',
       foto = 'profile_5_1706900000.jpg'
   WHERE id = 5;

5. STEP 4: Redirect atau alert
   Flasher::setFlash('Berhasil', 'Profil diupdate!', 'success');
```

**Struktur Folder Upload:**

```
public/
└── storage/
    └── uploads/
        └── profile/
            ├── profile_5_1706865000.jpg     ← Foto lama (dihapus)
            ├── profile_5_1706900000.jpg     ← Foto baru
            ├── profile_6_1706910000.jpg
            └── ...
```

**Kode Real - Controller:**

```php
public function prosesUpdateProfile()
{
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header('Location: ' . BASE_URL . '/internal/profile');
        exit;
    }

    $userModel = $this->model('UserModel');
    $userLama = $userModel->getUserById($_SESSION['user_id']);
    $foto = $userLama['foto']; // Default: foto lama

    // Handle Foto Upload (Base64)
    if (!empty($_POST['cropped_image'])) {
        $data_uri = $_POST['cropped_image'];
        $encoded_image = explode(",", $data_uri)[1];
        $decoded_image = base64_decode($encoded_image);

        $namaFileBaru = 'profile_' . $_SESSION['user_id'] . '_' . time() . '.jpg';
        $targetDir = __DIR__ . '/../../public/storage/uploads/profile/';

        if (!file_exists($targetDir)) {
            mkdir($targetDir, 0755, true);
        }

        if (file_put_contents($targetDir . $namaFileBaru, $decoded_image)) {
            if ($foto && file_exists($targetDir . $foto)) {
                unlink($targetDir . $foto);
            }
            $foto = $namaFileBaru;
        }
    }

    // Update Database
    $updateData = [
        'nama'     => $_POST['nama'] ?? $userLama['nama'],
        'email'    => $_POST['email'] ?? $userLama['email'],
        'telepon'  => $_POST['telepon'] ?? $userLama['telepon'],
        'foto'     => $foto
    ];

    if ($userModel->updateUser($_SESSION['user_id'], $updateData)) {
        Flasher::setFlash('Berhasil', 'Profil diupdate!', 'success');
    } else {
        Flasher::setFlash('Gagal', 'Gagal update profil', 'danger');
    }

    header('Location: ' . BASE_URL . '/internal/profile');
}
```

---

## 💾 DATABASE SCHEMA

**Tabel Peminjaman:**

```sql
CREATE TABLE peminjaman (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    lab_id INT NOT NULL,
    tanggal_peminjaman DATE NOT NULL,
    jam_mulai TIME NOT NULL,
    jam_selesai TIME NOT NULL,
    nama_peminjam VARCHAR(100),
    kegiatan VARCHAR(255),
    tipe ENUM('internal', 'eksternal'),
    status ENUM('pending', 'disetujui', 'ditolak') DEFAULT 'pending',
    catatan TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (lab_id) REFERENCES ruangan(id)
);
```

**Tabel Users:**

```sql
CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nama VARCHAR(100),
    email VARCHAR(100) UNIQUE,
    password VARCHAR(255),
    telepon VARCHAR(20),
    foto VARCHAR(255),
    role ENUM('internal', 'eksternal', 'admin'),
    nim VARCHAR(20),
    is_verified TINYINT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

**Tabel Ruangan (Lab):**

```sql
CREATE TABLE ruangan (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nama VARCHAR(100),
    kapasitas INT,
    deskripsi TEXT,
    lokasi VARCHAR(100)
);
```

---

## 🎨 ARSITEKTUR MVC YANG DIGUNAKAN

```
┌─────────────────────────────────────────────────────────────┐
│                     PRESENTASI (VIEW)                       │
│  - HTML template dengan PHP echo untuk data                 │
│  - JavaScript untuk interaksi (AJAX, event handlers)        │
│  - CSS untuk styling                                        │
│  Lokasi: app/views/                                         │
└────────────────────┬────────────────────────────────────────┘
                     │
                     │ Mengirim data + menerima response
                     ↓
┌─────────────────────────────────────────────────────────────┐
│                   LOGIKA (CONTROLLER)                       │
│  - Menerima request dari user (GET/POST/PUT/DELETE)         │
│  - Koordinasi dengan Model & Service                        │
│  - Return response (HTML view atau JSON)                    │
│  Lokasi: app/controllers/Internal.php                       │
│  Method: booking(), jadwal(), history(), profile()          │
└────────────────────┬────────────────────────────────────────┘
                     │
                     │ Memanggil untuk CRUD
                     ↓
┌──────────────────────────────────────┬──────────────────────┐
│         SERVICE LAYER                │      MODEL LAYER     │
│  (Business Logic)                    │   (Database Queries) │
│                                      │                      │
│  - BookingService                    │ - UserModel          │
│    ├─ validateBookingInput()         │ - RuanganModel       │
│    ├─ getLabIdByName()               │ - PeminjamanModel    │
│    ├─ checkScheduleConflict()        │ - JadwalModel        │
│    ├─ checkBookingConflict()         │ - KelasModel         │
│    ├─ createBooking()                │ - JurusanModel       │
│    ├─ updateBooking()                │ - MatakuliahModel    │
│    ├─ deleteBooking()                │                      │
│    └─ getBookingByUserId()           │ Method CRUD:         │
│                                      │ - create()           │
│  - WhatsAppService                   │ - read() / getById() │
│    └─ sendNotification()             │ - update()           │
│                                      │ - delete()           │
└──────────────────────────────────────┴──────────────────────┘
                     │
                     │ Menulis/membaca data
                     ↓
┌─────────────────────────────────────────────────────────────┐
│                 DATABASE (MySQL)                            │
│  - Tabel peminjaman                                         │
│  - Tabel users                                              │
│  - Tabel ruangan                                            │
│  - Tabel jadwal                                             │
│  - dll                                                      │
└─────────────────────────────────────────────────────────────┘
```

---

## 📚 KONSEP CRUD DIJELASKAN

**CRUD = Create, Read, Update, Delete**

### **1. CREATE (INSERT)**

```
Booking baru →
Controller terima form →
Validasi →
Buat record di database

Kode:
$bookingService->createBooking([
    'user_id' => 5,
    'lab_id' => 3,
    ...
]);

SQL: INSERT INTO peminjaman (...) VALUES (...)
```

### **2. READ (SELECT)**

```
User buka halaman history →
Controller query: "Ambil semua booking user ini" →
Database return 5 rows →
Tampilkan di tabel

Kode:
$bookingService->getBookingByUserId(5);

SQL: SELECT * FROM peminjaman WHERE user_id = 5
```

### **3. UPDATE**

```
User edit jam booking →
Submit form AJAX →
Controller validasi →
Update record di database

Kode:
$bookingService->updateBooking(25, [
    'jam_mulai' => '10:00',
    'jam_selesai' => '11:00'
]);

SQL: UPDATE peminjaman SET jam_mulai='10:00', jam_selesai='11:00' WHERE id=25
```

### **4. DELETE**

```
User klik delete booking →
Confirm dialog →
Delete record dari database →
Booking hilang dari history

Kode:
$bookingService->deleteBooking(25);

SQL: DELETE FROM peminjaman WHERE id = 25
```

---

## 🔒 SECURITY CONCEPTS

### **1. Session Validation**

```php
// Di constructor Internal.php
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'internal') {
    // ❌ Redirect to login
    // Proteksi: hanya user login + role internal yang bisa akses
}
```

### **2. Ownership Verification**

```php
// Saat edit/delete booking
$peminjaman = $this->bookingService->getBookingById(25);

if ($peminjaman['user_id'] != $_SESSION['user_id']) {
    // ❌ Reject! User tidak bisa edit booking orang lain
}
```

### **3. Input Validation**

```php
// Service layer melakukan validasi
$validation = $this->bookingService->validateBookingInput($formData);

if (!$validation['valid']) {
    // ❌ Reject dengan error message
}
```

---

## 📝 TIPS PRESENTASI BESOK

**Flow untuk dijelaskan:**

1. **Opening (2 menit)**
   - "Modul Internal untuk Dosen, Tendik, Mahasiswa"
   - "3 fitur utama: Booking, History, Profile"

2. **Booking Flow (5 menit)**
   - User form → AJAX submit → Backend validasi → Database → Response
   - Tunjukkan kode controller + database result

3. **History/Edit/Delete (3 menit)**
   - User lihat list booking → Edit jam → Delete booking
   - Tunjukkan modal popup + database UPDATE/DELETE

4. **Profile (2 menit)**
   - Upload foto dengan Cropper.js
   - Base64 encode → Server save → Database update

5. **Database Schema (2 menit)**
   - Tunjukkan relationship antar tabel
   - ERD diagram

6. **Closing**
   - "Semua fitur sudah terintegrasi dengan WhatsApp notification"
   - "Security: Session validation + Ownership check"

---

## 🚀 REFACTORING YANG BARU DILAKUKAN

**Sebelum:**

- 571 baris dengan duplicate view rendering code

**Sesudah:**

- 484 baris
- Extract `renderPage()` helper
- Simplified docblocks
- Remove verbose comments
- 87 baris lebih ringkas (15% reduction)

**Manfaat:**
✅ Kode lebih mudah dibaca
✅ Maintenance lebih mudah
✅ Presentasi lebih clean

---

## 📞 CONTACT & REFERENCE

**Fitur Utama:**

- `/internal/booking` - Booking form
- `/internal/jadwal` - Schedule view
- `/internal/history` - Booking history + edit/delete
- `/internal/profile` - User profile + upload foto

**Services Used:**

- `BookingService` - Booking logic
- `WhatsAppService` - Notification

**Database Tables:**

- `users` - User data
- `peminjaman` - Booking records
- `ruangan` - Lab info
- `jadwal` - Schedule tetap

---

**Good luck dengan presentasi besok! 🎉**
