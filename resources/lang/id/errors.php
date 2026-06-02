<?php

return [
    // General Errors
    'general' => [
        'title' => 'Terjadi Kesalahan',
        'message' => 'Maaf, terjadi kesalahan. Silakan coba lagi.',
        'not_found' => 'Data tidak ditemukan.',
        'unauthorized' => 'Anda tidak memiliki akses ke halaman ini.',
        'forbidden' => 'Akses ditolak.',
        'server_error' => 'Terjadi kesalahan pada server. Silakan hubungi administrator.',
    ],

    // Validation Errors
    'validation' => [
        'required' => 'Kolom :attribute wajib diisi.',
        'email' => 'Format email tidak valid.',
        'min' => 'Kolom :attribute minimal :min karakter.',
        'max' => 'Kolom :attribute maksimal :max karakter.',
        'unique' => ':attribute sudah digunakan.',
        'confirmed' => 'Konfirmasi :attribute tidak cocok.',
        'numeric' => 'Kolom :attribute harus berupa angka.',
        'in' => ':attribute yang dipilih tidak valid.',
        'file' => 'File harus berupa file.',
        'mimes' => 'File harus bertipe: :values.',
        'max_file' => 'Ukuran file maksimal :max kilobyte.',
    ],

    // Authentication Errors
    'auth' => [
        'failed' => 'Email atau password salah.',
        'throttle' => 'Terlalu banyak percobaan login. Silakan coba lagi dalam :seconds detik.',
        'logged_out' => 'Anda telah keluar dari sistem.',
        'session_expired' => 'Sesi Anda telah berakhir. Silakan login kembali.',
        'unauthorized' => 'Anda harus login terlebih dahulu.',
        'password_incorrect' => 'Password saat ini salah.',
        'account_disabled' => 'Akun Anda telah dinonaktifkan.',
        'email_not_verified' => 'Email Anda belum diverifikasi.',
    ],

    // Database Errors
    'database' => [
        'connection' => 'Gagal terhubung ke database.',
        'query' => 'Terjadi kesalahan saat mengakses database.',
        'duplicate' => 'Data sudah ada di database.',
        'foreign_key' => 'Data tidak dapat dihapus karena masih terkait dengan data lain.',
    ],

    // File Upload Errors
    'upload' => [
        'failed' => 'Gagal mengunggah file.',
        'too_large' => 'Ukuran file terlalu besar.',
        'invalid_type' => 'Tipe file tidak didukung.',
        'not_found' => 'File tidak ditemukan.',
        'permission_denied' => 'Tidak memiliki izin untuk mengunggah file.',
    ],

    // Environment Editor Errors
    'env_editor' => [
        'update_failed' => 'Gagal memperbarui environment variable.',
        'key_required' => 'Key wajib diisi.',
        'key_not_found' => 'Key tidak ditemukan.',
        'permission_denied' => 'Tidak memiliki izin untuk mengubah file .env.',
        'file_not_writable' => 'File .env tidak dapat ditulis. Periksa permission file.',
        'invalid_format' => 'Format environment variable tidak valid.',
        'update_success' => 'Environment variable berhasil diperbarui.',
        'delete_success' => 'Environment variable berhasil dihapus.',
        'add_success' => 'Environment variable berhasil ditambahkan.',
    ],

    // Permission Errors
    'permission' => [
        'denied' => 'Anda tidak memiliki izin untuk melakukan aksi ini.',
        'insufficient' => 'Izin Anda tidak mencukupi.',
        'role_required' => 'Role :role diperlukan untuk mengakses halaman ini.',
    ],

    // CRUD Operations
    'crud' => [
        'create_failed' => 'Gagal membuat :resource.',
        'update_failed' => 'Gagal memperbarui :resource.',
        'delete_failed' => 'Gagal menghapus :resource.',
        'retrieve_failed' => 'Gagal mengambil data :resource.',
        'create_success' => ':resource berhasil dibuat.',
        'update_success' => ':resource berhasil diperbarui.',
        'delete_success' => ':resource berhasil dihapus.',
    ],

    // Cache Errors
    'cache' => [
        'clear_failed' => 'Gagal membersihkan cache.',
        'clear_success' => 'Cache berhasil dibersihkan.',
        'invalid_path' => 'Path cache tidak valid.',
        'permission_denied' => 'Tidak memiliki izin untuk menulis cache.',
    ],

    // Storage Errors
    'storage' => [
        'permission_denied' => 'Tidak memiliki izin menulis ke storage.',
        'path_not_found' => 'Path storage tidak ditemukan.',
        'disk_full' => 'Ruang penyimpanan penuh.',
        'write_failed' => 'Gagal menulis file ke storage.',
    ],

    // Network Errors
    'network' => [
        'timeout' => 'Koneksi timeout. Silakan coba lagi.',
        'connection_failed' => 'Gagal terhubung ke server.',
        'no_internet' => 'Tidak ada koneksi internet.',
    ],

    // Form Errors
    'form' => [
        'invalid_data' => 'Data yang dikirim tidak valid.',
        'missing_fields' => 'Beberapa field wajib belum diisi.',
        'csrf_mismatch' => 'Token keamanan tidak valid. Silakan refresh halaman.',
    ],

    // API Errors
    'api' => [
        'invalid_token' => 'Token API tidak valid.',
        'rate_limit' => 'Terlalu banyak request. Silakan tunggu beberapa saat.',
        'endpoint_not_found' => 'Endpoint API tidak ditemukan.',
        'method_not_allowed' => 'Metode HTTP tidak diizinkan.',
    ],

    // Import/Export Errors
    'import' => [
        'failed' => 'Gagal mengimpor data.',
        'invalid_format' => 'Format file tidak valid.',
        'empty_file' => 'File kosong.',
        'processing_error' => 'Terjadi kesalahan saat memproses file.',
    ],

    'export' => [
        'failed' => 'Gagal mengekspor data.',
        'no_data' => 'Tidak ada data untuk diekspor.',
        'generation_error' => 'Terjadi kesalahan saat membuat file.',
    ],
];
