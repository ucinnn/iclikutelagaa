<?php

return [
    'navigation' => [
        'group' => 'Sistem',
        'label' => 'Editor .Env',
    ],

    'page' => [
        'title' => 'Editor .Env',
    ],

    'tabs' => [
        'current-env' => [
            'title' => '.env Saat Ini',
        ],
        'backups' => [
            'title' => 'Cadangan',
        ],
    ],

    'actions' => [
        'add' => [
            'title' => 'Tambah Entri Baru',
            'modalHeading' => 'Tambah Entri Baru',
            'success' => [
                'title' => 'Kunci ":Name" berhasil ditulis.',
            ],
            'form' => [
                'fields' => [
                    'key' => 'Kunci',
                    'value' => 'Nilai',
                    'index' => 'Sisipkan setelah kunci yang sudah ada (opsional)',
                ],
                'helpText' => [
                    'index' => 'Jika Anda ingin menempatkan entri baru ini setelah kunci yang sudah ada, pilih salah satu kunci yang tersedia.',
                ],
            ],
        ],

        'edit' => [
            'tooltip' => 'Edit entri ":name"',
            'modal' => [
                'text' => 'Edit Entri',
            ],
        ],

        'delete' => [
            'tooltip' => 'Hapus entri ":name"',
            'confirm' => [
                'title' => 'Anda akan menghapus secara permanen entri ":name". Apakah Anda yakin?',
            ],
        ],

        'clear-cache' => [
            'title' => 'Bersihkan Cache',
            'tooltip' => 'Terkadang Laravel menyimpan cache variabel ENV. Jalankan "artisan optimize:clear" untuk memuat ulang perubahan pada file .env.',
        ],

        'backup' => [
            'title' => 'Buat Cadangan Baru',
            'success' => [
                'title' => 'Cadangan berhasil dibuat.',
            ],
        ],

        'download' => [
            'title' => 'Unduh File .env Saat Ini',
            'tooltip' => 'Unduh file cadangan ":name".',
        ],

        'upload-backup' => [
            'title' => 'Unggah File Cadangan',
        ],

        'show-content' => [
            'modalHeading' => 'Isi mentah dari cadangan ":name"',
            'tooltip' => 'Tampilkan isi mentah',
        ],

        'restore-backup' => [
            'confirm' => [
                'title' => 'Anda akan memulihkan ":name" untuk menggantikan file ".env" saat ini. Apakah Anda yakin ingin melanjutkan?',
            ],
            'modalSubmit' => 'Pulihkan',
            'tooltip' => 'Pulihkan ":name" sebagai file ENV saat ini',
        ],

        'delete-backup' => [
            'tooltip' => 'Hapus file cadangan ":name"',
            'confirm' => [
                'title' => 'Anda akan menghapus secara permanen file cadangan ":name". Apakah Anda yakin?',
            ],
        ],
    ],
];
