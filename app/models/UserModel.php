<?php

class UserModel
{
    private $table = 'users'; // Nama tabel di database
    private $db;

    public function __construct()
    {
        $this->db = new Database;
    }

    // 1. Ambil data user berdasarkan ID (Untuk ditampilkan di form profil)
    // Ambil 1 user berdasarkan ID
    public function getUserById($id)
    {
        $this->db->query("SELECT id, nama, email, password, telepon, role, status, foto, created_at FROM " . $this->table . " WHERE id = :id");
        $this->db->bind('id', $id);
        return $this->db->single();
    }

    // Update Profile
    public function updateUserProfile($data)
    {
        // Cek apakah user ingin ganti password?
        if (!empty($data['password'])) {
            // Jika ada password baru
            $query = "UPDATE " . $this->table . " SET 
                        nama = :nama, 
                        email = :email, 
                        telepon = :telepon, 
                        password = :password,
                        foto = :foto 
                      WHERE id = :id";
        } else {
            // Jika tidak ganti password
            $query = "UPDATE " . $this->table . " SET 
                        nama = :nama, 
                        email = :email, 
                        telepon = :telepon,
                        foto = :foto
                      WHERE id = :id";
        }

        $this->db->query($query);
        $this->db->bind('nama', $data['nama']);
        $this->db->bind('email', $data['email']);
        $this->db->bind('telepon', $data['telepon']);
        $this->db->bind('foto', $data['foto']);
        $this->db->bind('id', $data['id']);

        if (!empty($data['password'])) {
            $this->db->bind('password', password_hash($data['password'], PASSWORD_DEFAULT));
        }

        $this->db->execute();
        return $this->db->rowCount();
    }

    // 3. Update Password (Opsional)
    public function updatePassword($id, $newPassword)
    {
        $query = "UPDATE " . $this->table . " SET password = :password WHERE id = :id";

        $this->db->query($query);
        // Password wajib di-hash demi keamanan
        $this->db->bind('password', password_hash($newPassword, PASSWORD_DEFAULT));
        $this->db->bind('id', $id);

        $this->db->execute();
    }

    // 4. Ambil Semua User (Untuk Admin)
    public function getAll()
    {
        $this->db->query("SELECT id, nama, email, role, status, telepon, foto, created_at FROM " . $this->table . " ORDER BY id DESC");
        return $this->db->resultSet();
    }

    // 5. Create User (Admin Version - with Status)
    public function create($data)
    {
        $query = "INSERT INTO " . $this->table . "
                  (nama, email, status, role, telepon, password)
                  VALUES
                  (:nama, :email, :status, :role, :telepon, :password)";

        $this->db->query($query);
        $this->db->bind('nama', htmlspecialchars(strip_tags($data['nama'])));
        $this->db->bind('email', htmlspecialchars(strip_tags($data['email'])));
        $this->db->bind('status', htmlspecialchars(strip_tags($data['status'])));
        $this->db->bind('role', $data['role']);
        $this->db->bind('telepon', htmlspecialchars(strip_tags($data['telepon'])));

        // Hash password
        $this->db->bind('password', password_hash($data['password'], PASSWORD_BCRYPT));

        $this->db->execute();

        return $this->db->rowCount();
    }

    // 6. Update User (Admin Version)
    public function update($id, $data)
    {
        if (!empty($data['password'])) {
            $query = "UPDATE " . $this->table . "
                      SET nama=:nama, email=:email, status=:status, 
                          role=:role, telepon=:telepon, password=:password
                      WHERE id = :id";
        } else {
            $query = "UPDATE " . $this->table . "
                      SET nama=:nama, email=:email, status=:status, 
                          role=:role, telepon=:telepon
                      WHERE id = :id";
        }

        $this->db->query($query);
        $this->db->bind('id', $id);
        $this->db->bind('nama', htmlspecialchars(strip_tags($data['nama'])));
        $this->db->bind('email', htmlspecialchars(strip_tags($data['email'])));
        $this->db->bind('status', htmlspecialchars(strip_tags($data['status'])));
        $this->db->bind('role', $data['role']);
        $this->db->bind('telepon', htmlspecialchars(strip_tags($data['telepon'])));

        if (!empty($data['password'])) {
            $this->db->bind('password', password_hash($data['password'], PASSWORD_BCRYPT));
        }

        $this->db->execute();

        return $this->db->rowCount();
    }

    // 7. Delete User
    public function delete($id)
    {
        $query = "DELETE FROM " . $this->table . " WHERE id = :id";
        $this->db->query($query);
        $this->db->bind('id', $id);
        $this->db->execute();

        return $this->db->rowCount();
    }

    // LOGIN: Cari user berdasarkan email
    public function getUserByEmail($email)
    {
        $this->db->query("SELECT id, nama, email, password, role, telepon, is_verified FROM " . $this->table . " WHERE email = :email");
        $this->db->bind('email', $email);
        return $this->db->single();
    }

    // REGISTER: Tambah user baru (Public/Auth Version)
    public function tambahUser($data)
    {
        $query = "INSERT INTO " . $this->table . " 
                    (nama, email, password, role, telepon, verification_code, is_verified)
                  VALUES
                    (:nama, :email, :password, :role, :telepon, :code, 0)";

        $this->db->query($query);
        $this->db->bind('nama', $data['nama']);
        $this->db->bind('email', $data['email']);
        $this->db->bind('password', $data['password']);
        $this->db->bind('role', 'external');
        $this->db->bind('telepon', $data['telepon']);
        $this->db->bind('code', $data['verification_code']);

        $this->db->execute();

        return $this->db->rowCount();
    }

    public function verifyUser($email, $code)
    {
        $this->db->query("SELECT id FROM " . $this->table . " WHERE email = :email AND verification_code = :code");
        $this->db->bind('email', $email);
        $this->db->bind('code', $code);
        $user = $this->db->single();

        if ($user) {
            $this->db->query("UPDATE " . $this->table . " SET is_verified = 1, verification_code = NULL WHERE id = :id");
            $this->db->bind('id', $user['id']);
            $this->db->execute();
            return true;
        }
        return false;
    }

    public function updateVerificationCode($email, $code)
    {
        $this->db->query("UPDATE " . $this->table . " SET verification_code = :code WHERE email = :email");
        $this->db->bind('code', $code);
        $this->db->bind('email', $email);
        $this->db->execute();
        return $this->db->rowCount();
    }

    public function getUserByResetToken($token)
    {
        $query = "SELECT id, email, reset_token_expire FROM " . $this->table . " 
                  WHERE reset_token = :token 
                  AND reset_token_expire > NOW()";

        $this->db->query($query);
        $this->db->bind('token', $token);

        return $this->db->single();
    }

    public function updatePasswordAndClearToken($userId, $hashedPassword)
    {
        $query = "UPDATE " . $this->table . " 
                  SET password = :password, 
                      reset_token = NULL, 
                      reset_token_expire = NULL 
                  WHERE id = :id";

        $this->db->query($query);
        $this->db->bind('password', $hashedPassword);
        $this->db->bind('id', $userId);

        $this->db->execute();

        return $this->db->rowCount();
    }

    public function updateResetToken($userId, $token, $expireTime)
    {
        $query = "UPDATE " . $this->table . " 
                  SET reset_token = :token, 
                      reset_token_expire = :expire 
                  WHERE id = :id";

        $this->db->query($query);
        $this->db->bind('token', $token);
        $this->db->bind('expire', $expireTime);
        $this->db->bind('id', $userId);

        $this->db->execute();

        return $this->db->rowCount();
    }
}