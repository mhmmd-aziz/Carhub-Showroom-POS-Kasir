<?php
try {
    $pdo = new PDO('mysql:host=localhost;dbname=pos_showroom;charset=utf8mb4', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "=== Running database migrations ===\n\n";

    // 1. Tambah harga_jual_snapshot ke pemesanan
    try {
        $pdo->exec("ALTER TABLE pemesanan ADD COLUMN harga_jual_snapshot DECIMAL(15,2) NOT NULL DEFAULT 0 COMMENT 'Snapshot harga jual saat order dibuat' AFTER tgl_pesan");
        echo "OK: Column harga_jual_snapshot added to pemesanan\n";
    } catch (PDOException $e) {
        if (str_contains($e->getMessage(), 'Duplicate')) {
            echo "SKIP: harga_jual_snapshot already exists\n";
        } else {
            throw $e;
        }
    }

    // 2. Tambah alasan_batal ke pemesanan
    try {
        $pdo->exec("ALTER TABLE pemesanan ADD COLUMN alasan_batal TEXT NULL COMMENT 'Diisi saat pembatalan' AFTER status_pemesanan");
        echo "OK: Column alasan_batal added to pemesanan\n";
    } catch (PDOException $e) {
        if (str_contains($e->getMessage(), 'Duplicate')) {
            echo "SKIP: alasan_batal already exists\n";
        } else {
            throw $e;
        }
    }

    // 3. Tambah bukti_ktp ke pembayaran_penjualan
    try {
        $pdo->exec("ALTER TABLE pembayaran_penjualan ADD COLUMN bukti_ktp VARCHAR(255) NULL COMMENT 'Upload fotokopi KTP' AFTER bukti_transfer");
        echo "OK: Column bukti_ktp added to pembayaran_penjualan\n";
    } catch (PDOException $e) {
        if (str_contains($e->getMessage(), 'Duplicate')) {
            echo "SKIP: bukti_ktp already exists\n";
        } else {
            throw $e;
        }
    }

    // 4. Update harga_jual_snapshot dari harga_jual mobil untuk data existing
    $affected = $pdo->exec("UPDATE pemesanan ps 
        JOIN mobil m ON m.id_mobil = ps.id_mobil 
        SET ps.harga_jual_snapshot = m.harga_jual 
        WHERE ps.harga_jual_snapshot = 0");
    echo "OK: harga_jual_snapshot updated for $affected existing records\n";

    // 5. Cek dan buat tabel laporan
    try {
        $pdo->exec("CREATE TABLE IF NOT EXISTS laporan (
            id_laporan INT(11) NOT NULL AUTO_INCREMENT,
            jenis_laporan VARCHAR(50) NOT NULL,
            periode_awal DATE,
            periode_akhir DATE,
            id_user INT(11),
            created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (id_laporan)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
        echo "OK: Table laporan created/verified\n";
    } catch (PDOException $e) {
        echo "SKIP: laporan - " . $e->getMessage() . "\n";
    }

    // 6. Cek ENUM status_stok
    $res = $pdo->query("SHOW COLUMNS FROM mobil WHERE Field = 'status_stok'")->fetch(PDO::FETCH_ASSOC);
    echo "Current status_stok ENUM: " . $res['Type'] . "\n";

    // 7. Verifikasi kolom baru
    echo "\n=== VERIFIKASI FINAL ===\n";
    $cols = $pdo->query('DESCRIBE pemesanan')->fetchAll(PDO::FETCH_ASSOC);
    echo "Kolom pemesanan:\n";
    foreach ($cols as $c) {
        echo "  - " . $c['Field'] . " (" . $c['Type'] . ")\n";
    }

    $check_laporan = $pdo->query("SHOW TABLES LIKE 'laporan'")->fetch();
    echo "\nTabel laporan: " . ($check_laporan ? 'EXISTS' : 'MISSING') . "\n";

    echo "\n=== All migrations completed! ===\n";

} catch (PDOException $e) {
    echo "FATAL ERROR: " . $e->getMessage() . "\n";
}
