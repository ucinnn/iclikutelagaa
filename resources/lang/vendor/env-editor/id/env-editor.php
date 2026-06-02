<?php

return [
    'menuTitle' => 'Editor .env',
    'controllerMessages' => [
        'backupWasCreated' => 'Cadangan baru telah dibuat',
        'fileWasRestored' => 'File cadangan ":name" telah dipulihkan sebagai .env utama',
        'fileWasDeleted' => 'File cadangan ":name" telah dihapus',
        'currentEnvWasReplacedByTheUploadedFile' => 'File telah diunggah dan menjadi file .env baru',
        'uploadedFileSavedAsBackup' => 'File telah diunggah sebagai cadangan dengan nama ":name"',
        'keyWasAdded' => 'Kunci ":name" telah ditambahkan',
        'keyWasEdited' => 'Kunci ":name" telah diperbarui',
        'keyWasDeleted' => 'Kunci ":name" telah dihapus',
    ],
    'views' => [
        'tabTitles' => [
            'upload' => 'Unggah',
            'backup' => 'Cadangan',
            'currentEnv' => 'File .env Saat Ini',
        ],
        'currentEnv' => [
            'title' => 'Isi File .env Saat Ini',
            'tableTitles' => [
                'key' => 'Kunci',
                'value' => 'Nilai',
                'actions' => 'Aksi',
            ],
            'btn' => [
                'edit' => 'Edit File',
                'delete' => 'Hapus Kunci',
                'addAfterKey' => 'Tambah kunci baru setelah kunci ini',
                'addNewKey' => 'Tambah Kunci Baru',
                'deleteConfigCache' => 'Hapus Cache Konfigurasi',
                'deleteConfigCacheDesc' => 'Di lingkungan produksi, perubahan nilai mungkin tidak langsung diterapkan karena konfigurasi di-cache. Anda dapat mencoba menghapus cache untuk memperbaruinya.',
            ],
            'modal' => [
                'title' => [
                    'new' => 'Kunci Baru',
                    'edit' => 'Edit Kunci',
                    'delete' => 'Hapus Kunci',
                ],
                'input' => [
                    'key' => 'Kunci',
                    'value' => 'Nilai',
                ],
                'btn' => [
                    'close' => 'Tutup',
                    'new' => 'Tambah Kunci',
                    'edit' => 'Perbarui Kunci',
                    'delete' => 'Hapus Kunci',
                ],
            ],
        ],
        'upload' => [
            'title' => 'Di sini Anda dapat mengunggah file ".env" baru sebagai cadangan atau mengganti file ".env" saat ini',
            'selectFilePrompt' => 'Pilih File',
            'btn' => [
                'clearFile' => 'Batal',
                'uploadAsBackup' => 'Unggah sebagai Cadangan',
                'uploadAndReplace' => 'Unggah dan Ganti .env Saat Ini',
            ],
        ],
        'backup' => [
            'title' => 'Di sini Anda dapat melihat daftar file cadangan (jika ada), membuat cadangan baru, atau mengunduh file .env',
            'tableTitles' => [
                'filename' => 'Nama File',
                'created_at' => 'Tanggal Dibuat',
                'actions' => 'Aksi',
            ],
            'noBackUpItems' => 'Tidak ada file cadangan di direktori yang dipilih. <br> Anda dapat membuat cadangan pertama dengan menekan tombol "Buat Cadangan Baru".',
            'btn' => [
                'backUpCurrentEnv' => 'Buat Cadangan Baru',
                'downloadCurrentEnv' => 'Unduh .env Saat Ini',
                'download' => 'Unduh File',
                'delete' => 'Hapus File',
                'restore' => 'Pulihkan File',
                'viewContent' => 'Lihat Isi File',
            ],
        ],
    ],
    'exceptions' => [
        'fileNotExists' => 'File ":name" tidak ditemukan!',
        'keyAlreadyExists' => 'Kunci ":name" sudah ada!',
        'keyNotExists' => 'Kunci ":name" tidak ditemukan!',
        'provideFileName' => 'Anda harus memberikan nama file!',
    ],
];
