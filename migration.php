<?php
// migration.php
require_once 'app/config/Config.php';
require_once 'app/core/Database.php';

class Migration
{
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function run()
    {
        try {
            // 1. Create table tahun_ajaran
            echo "Creating table 'tahun_ajaran'...\n";
            $this->db->query("CREATE TABLE IF NOT EXISTS tahun_ajaran (
                id INT AUTO_INCREMENT PRIMARY KEY,
                nama VARCHAR(50) NOT NULL,
                status ENUM('Aktif', 'Tidak Aktif') DEFAULT 'Tidak Aktif'
            )");
            $this->db->execute();
            echo "✓ Table 'tahun_ajaran' created.\n";

            // 2. Create table jurusan
            echo "Creating table 'jurusan'...\n";
            $this->db->query("CREATE TABLE IF NOT EXISTS jurusan (
                id INT AUTO_INCREMENT PRIMARY KEY,
                nama_jurusan VARCHAR(100) NOT NULL,
                singkatan VARCHAR(20) NOT NULL
            )");
            $this->db->execute();
            echo "✓ Table 'jurusan' created.\n";

            // 3. Alter table kelas
            echo "Altering table 'kelas'...\n";
            // Check columns first
            $this->addColumnIfNotExists('kelas', 'jurusan_id', 'INT NULL');
            $this->addColumnIfNotExists('kelas', 'angkatan', 'VARCHAR(4) NULL');
            echo "✓ Table 'kelas' updated.\n";

            // 4. Alter table matakuliah
            echo "Altering table 'matakuliah'...\n";
            $this->addColumnIfNotExists('matakuliah', 'singkatan', 'VARCHAR(20) NULL');
            $this->addColumnIfNotExists('matakuliah', 'semester', "ENUM('Ganjil', 'Genap') NULL");
            $this->addColumnIfNotExists('matakuliah', 'sks', 'INT NULL');
            $this->addColumnIfNotExists('matakuliah', 'jurusan_id', 'INT NULL');
            echo "✓ Table 'matakuliah' updated.\n";

            echo "\nMigration completed successfully!\n";

        } catch (PDOException $e) {
            echo "Migration Failed: " . $e->getMessage() . "\n";
        }
    }

    private function addColumnIfNotExists($table, $column, $definition)
    {
        try {
            $this->db->query("SHOW COLUMNS FROM $table LIKE '$column'");
            $result = $this->db->single();
            if (!$result) {
                $this->db->query("ALTER TABLE $table ADD COLUMN $column $definition");
                $this->db->execute();
                echo "  + Added column '$column' to '$table'\n";
            } else {
                echo "  - Column '$column' already exists in '$table'\n";
            }
        } catch (Exception $e) {
            echo "  ! Error adding column '$column': " . $e->getMessage() . "\n";
        }
    }
}

$migration = new Migration();
$migration->run();
