<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SurveyResource\Pages;
use App\Models\Survey;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Malzariey\FilamentLexicalEditor\FilamentLexicalEditor;
use Malzariey\FilamentLexicalEditor\Enums\ToolbarItem;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Select;
use Filament\Facades\Filament;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;
use pxlrbt\FilamentExcel\Actions\Tables\ExportAction;
use pxlrbt\FilamentExcel\Exports\ExcelExport;
use pxlrbt\FilamentExcel\Columns\Column;

class SurveyResource extends Resource
{
    protected static ?string $model = Survey::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';
    protected static ?string $navigationLabel = 'Survey';
    protected static ?string $pluralModelLabel = 'Daftar Survey';
    protected static ?string $modelLabel = 'Survey';

    public static function shouldRegisterNavigation(): bool
    {
        // Buat array yang berisi peran (role) yang diizinkan
        $allowedRoles = ['author', 'admin', 'superadmin'];

        // Periksa apakah peran pengguna saat ini ada di dalam array tersebut
        return in_array(Filament::auth()->user()?->role, $allowedRoles);
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->label('Judul')
                    ->required()
                    ->columnspanfull()
                    ->maxLength(255),

                Fieldset::make('Deskripsi')
                    ->schema([
                        FilamentLexicalEditor::make('description')
                            ->label('Deskripsi')
                            ->nullable()
                            ->rules(['max:6000000'])
                            ->validationMessages([
                                'max' => 'The text is too long — maximum allowed is 6,000,000 characters (approximately 6 MB of file size).',
                            ])
                            ->helperText('Maximum limit: 6 million characters (approximately 6 MB of file size).')->enabledToolbars([
                                ToolbarItem::UNDO,
                                ToolbarItem::REDO,
                                // ToolbarItem::FONT_FAMILY,
                                ToolbarItem::NORMAL,
                                ToolbarItem::H1,
                                ToolbarItem::H2,
                                ToolbarItem::H3,
                                ToolbarItem::H4,
                                ToolbarItem::H5,
                                ToolbarItem::H6,
                                ToolbarItem::BULLET,
                                ToolbarItem::NUMBERED,
                                ToolbarItem::QUOTE,
                                // ToolbarItem::FONT_SIZE,
                                ToolbarItem::BOLD,
                                ToolbarItem::ITALIC,
                                ToolbarItem::UNDERLINE,
                                ToolbarItem::TEXT_COLOR,
                                ToolbarItem::BACKGROUND_COLOR,
                                ToolbarItem::LOWERCASE,
                                ToolbarItem::UPPERCASE,
                                ToolbarItem::CAPITALIZE,
                                ToolbarItem::STRIKETHROUGH,
                                ToolbarItem::SUBSCRIPT,
                                ToolbarItem::SUPERSCRIPT,
                                ToolbarItem::CLEAR,
                                ToolbarItem::LEFT,
                                ToolbarItem::CENTER,
                                ToolbarItem::RIGHT,
                                ToolbarItem::JUSTIFY,
                                ToolbarItem::HR,
                                ToolbarItem::IMAGE,
                            ]),
                    ]),

                Forms\Components\TextInput::make('link')
                    ->label(__('link.label'))
                    ->required()
                    ->url()
                    ->helperText(__('link.helper'))
                    ->validationMessages([
                        'required' => __('link.validation.required'),
                        'url' => __('link.validation.url'),
                    ]),


                Forms\Components\Select::make('icon')
                    ->label('Icon FontAwesome')
                    ->searchable()
                    ->options([
                        // 🎨 --- UI / Navigasi ---
                        'fas fa-link' => 'Link / Tautan',
                        'fas fa-home' => 'Beranda',
                        'fas fa-info-circle' => 'Informasi',
                        'fas fa-question-circle' => 'Pertanyaan / FAQ',
                        'fas fa-cog' => 'Pengaturan',
                        'fas fa-bell' => 'Notifikasi',
                        'fas fa-star' => 'Bintang / Favorit',
                        'fas fa-heart' => 'Suka / Cinta',
                        'fas fa-bookmark' => 'Penanda',
                        'fas fa-edit' => 'Edit / Ubah',
                        'fas fa-trash' => 'Hapus / Sampah',
                        'fas fa-eye' => 'Lihat',
                        'fas fa-search' => 'Cari',
                        'fas fa-check' => 'Centang / Selesai',
                        'fas fa-times' => 'Batal / Tutup',
                        'fas fa-download' => 'Unduh File',
                        'fas fa-upload' => 'Unggah File',
                        'fas fa-print' => 'Cetak Dokumen',

                        // 🧑‍🏫 --- Edukasi & Organisasi ---
                        'fas fa-user-graduate' => 'Mahasiswa / Lulusan',
                        'fas fa-university' => 'Kampus / Universitas',
                        'fas fa-graduation-cap' => 'Wisuda',
                        'fas fa-book' => 'Buku / Modul',
                        'fas fa-clipboard-list' => 'Daftar / Formulir',
                        'fas fa-chalkboard-teacher' => 'Pengajar / Dosen',
                        'fas fa-users' => 'Komunitas / Peserta',
                        'fas fa-user' => 'Profil / Pengguna',
                        'fas fa-handshake' => 'Kerjasama / Kolaborasi',
                        'fas fa-trophy' => 'Prestasi / Penghargaan',
                        'fas fa-certificate' => 'Sertifikat / Penghargaan',

                        // 💬 --- Komunikasi ---
                        'fas fa-comments' => 'Diskusi / Chat',
                        'fas fa-comment-dots' => 'Pesan / Balasan',
                        'fas fa-envelope' => 'Email / Surat',
                        'fas fa-phone' => 'Telepon / Kontak',
                        'fas fa-bullhorn' => 'Pengumuman / Informasi Publik',
                        'fas fa-headset' => 'Layanan / Bantuan',
                        'fas fa-life-ring' => 'Pusat Bantuan / Dukungan',
                        'fas fa-lightbulb' => 'Ide / Saran',
                        'fas fa-smile' => 'Kepuasan / Umpan Balik',

                        // 💰 --- Keuangan & Bisnis ---
                        'fas fa-credit-card' => 'Kartu / Pembayaran',
                        'fas fa-money-bill-wave' => 'Tagihan / Uang',
                        'fas fa-piggy-bank' => 'Tabungan / Donasi',
                        'fas fa-chart-line' => 'Grafik / Statistik',
                        'fas fa-briefcase' => 'Bisnis / Karier',
                        'fas fa-balance-scale' => 'Keadilan / Neraca',
                        'fas fa-gavel' => 'Kebijakan / Hukum',

                        // 🌐 --- Teknologi & Internet ---
                        'fas fa-globe' => 'Website / Dunia',
                        'fas fa-wifi' => 'Jaringan / Internet',
                        'fas fa-cloud' => 'Cloud / Penyimpanan',
                        'fas fa-database' => 'Database / Data',
                        'fas fa-server' => 'Server / Backend',
                        'fas fa-code' => 'Pemrograman / Coding',
                        'fas fa-terminal' => 'Terminal / Console',
                        'fas fa-laptop' => 'Laptop / Perangkat',
                        'fas fa-mobile-alt' => 'Smartphone / Mobile',
                        'fas fa-desktop' => 'Komputer / Desktop',
                        'fas fa-robot' => 'AI / Otomatisasi',
                        'fas fa-bug' => 'Bug / Error',
                        'fas fa-key' => 'Keamanan / Akses',
                        'fas fa-lock' => 'Privasi / Dikunci',
                        'fas fa-unlock' => 'Terbuka / Akses',

                        // 🏞️ --- Alam & Lingkungan ---
                        'fas fa-leaf' => 'Lingkungan / Alam',
                        'fas fa-tree' => 'Pohon / Hutan',
                        'fas fa-seedling' => 'Pertumbuhan / Hijau',
                        'fas fa-sun' => 'Matahari / Cuaca Cerah',
                        'fas fa-moon' => 'Malam / Gelap',
                        'fas fa-cloud-sun' => 'Cuaca Berawan',
                        'fas fa-water' => 'Air / Lingkungan',
                        'fas fa-recycle' => 'Daur Ulang / Ramah Lingkungan',

                        // 🚀 --- Aktivitas & Umum ---
                        'fas fa-rocket' => 'Inovasi / Peluncuran',
                        'fas fa-hands-helping' => 'Relawan / Bantuan',
                        'fas fa-calendar' => 'Kalender / Jadwal',
                        'fas fa-clock' => 'Waktu / Jam',
                        'fas fa-map-marker-alt' => 'Lokasi / Tempat',
                        'fas fa-route' => 'Rute / Perjalanan',
                        'fas fa-car' => 'Kendaraan / Mobilitas',
                        'fas fa-plane' => 'Penerbangan / Perjalanan',
                        'fas fa-gift' => 'Hadiah / Giveaway',
                        'fas fa-bolt' => 'Energi / Cepat',
                        'fas fa-fire' => 'Semangat / Populer',

                        // ⚙️ --- Tools & Kegiatan ---
                        'fas fa-hammer' => 'Perbaikan / Alat',
                        'fas fa-wrench' => 'Pengaturan / Alat',
                        'fas fa-ruler' => 'Ukuran / Desain',
                        'fas fa-paint-brush' => 'Desain / Kreatif',
                        'fas fa-palette' => 'Warna / Seni',
                        'fas fa-camera' => 'Kamera / Foto',
                        'fas fa-image' => 'Gambar / Galeri',
                        'fas fa-video' => 'Video / Multimedia',
                        'fas fa-microphone' => 'Audio / Rekaman',
                        'fas fa-music' => 'Musik / Lagu',
                        'fas fa-bullseye' => 'Target / Tujuan',
                    ])
                    ->nullable()
                    ->helperText('Pilih icon yang sesuai untuk mewakili jenis survey atau formulir.'),

                Forms\Components\Toggle::make('is_active')
                    ->label('Aktif')
                    ->default(true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')->label('Judul')->searchable(),
                Tables\Columns\TextColumn::make('link')->label('Link')->limit(30),
                Tables\Columns\IconColumn::make('is_active')->boolean()->label('Aktif'),
                Tables\Columns\TextColumn::make('created_at')->label('Dibuat')->dateTime('d M Y H:i'),
            ])
            ->filters([])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
                ExportBulkAction::make()
                    ->label('Export yang Dipilih')
                    ->exports([
                        ExcelExport::make()
                            ->fromTable()
                            ->withFilename('survey-terpilih-' . date('Y-m-d'))
                            ->withColumns([
                                Column::make('title')->heading('Judul'),
                                Column::make('link')->heading('Link'),
                                Column::make('icon')->heading('Icon'),
                                Column::make('is_active')
                                    ->heading('Aktif')
                                    ->formatStateUsing(fn ($state) => $state ? 'Ya' : 'Tidak'),
                                Column::make('created_at')
                                    ->heading('Dibuat')
                                    ->formatStateUsing(fn ($state) => $state?->format('d M Y H:i')),
                                Column::make('updated_at')
                                    ->heading('Diperbarui')
                                    ->formatStateUsing(fn ($state) => $state?->format('d M Y H:i')),
                            ]),
                    ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSurveys::route('/'),
            'create' => Pages\CreateSurvey::route('/create'),
            'edit' => Pages\EditSurvey::route('/{record}/edit'),
        ];
    }
}
