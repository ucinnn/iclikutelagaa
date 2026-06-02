<?php

namespace App\Filament\Resources;

use App\Filament\Resources\NewsResource\Pages;

use Carbon\Carbon;

use App\Models\News;
use App\Models\User;

use Filament\Facades\Filament;
use Filament\Resources\Resource;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;


use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Builder;
use Filament\Forms\Components\Builder\Block;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Datepicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\View;
use Filament\Forms\Set;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Tabs\Tab;

use Filament\Forms\Get;
use Filament\Forms\Components\Actions\Action;

use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\IconColumn;

use Malzariey\FilamentLexicalEditor\Enums\ToolbarItem;
use Malzariey\FilamentLexicalEditor\FilamentLexicalEditor;

use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;
use pxlrbt\FilamentExcel\Actions\Tables\ExportAction;
use pxlrbt\FilamentExcel\Exports\ExcelExport;
use pxlrbt\FilamentExcel\Columns\Column;

use Filament\Notifications\Notification;

use Filament\Forms\Components\ViewField;

class NewsResource extends Resource

{
    protected static ?string $model = News::class;
    protected static ?string $navigationIcon = 'heroicon-o-newspaper';

    public static function getNavigationGroup(): ?string
    {
        return __('news.title_group');
    }

    public static function getNavigationLabel(): string
    {
        return __('news.navigation_label');
    }

    public static function getNavigationSort(): int
    {
        return 5;
    }

    public static function can(string $action, ?Model $record = null): bool
    {
        $user = Filament::auth()->user();
        if (!$user || !isset($user->role)) {
            return false;
        }
        return in_array($user->role, ['superadmin', 'admin', 'author']);
    }

    public static function canDelete(Model $record): bool
    {
        $user = Filament::auth()->user();

        return in_array($user->role, ['admin', 'superadmin']);
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            TextInput::make('title')
                ->label(__('news.form.title'))
                ->required()
                ->maxLength(350)
                ->helperText(__('news.form.title_helper'))
                ->live(true)
                ->afterStateUpdated(function (Set $set, $state) {
                    $set('slug', Str::slug($state));
                }),

            TextInput::make('slug')
                ->label(__('news.form.slug'))
                ->required()
                ->live(true)
                ->unique(News::class, 'slug', ignoreRecord: true)
                ->disabled(),

            Hidden::make('updated_by')
                ->dehydrated()
                ->default(fn() => Filament::auth()->user()?->name . ' (' . Filament::auth()->user()?->NIK . ')' ?? 'System'),

            Select::make('category_id')
                ->label(__('news.form.category'))
                ->multiple() // kalau relasinya many-to-many
                ->relationship('category', 'name') // pastikan nama relasinya sesuai di model News
                ->preload()
                ->searchable()
                ->required()
                ->createOptionForm([
                    TextInput::make('name')
                        ->label(__('category.fields.name'))
                        ->required()
                        ->reactive()
                        ->afterStateUpdated(fn($state, callable $set) => $set('slug', Str::slug($state))),
                    TextInput::make('slug')
                        ->label(__('category.fields.slug'))
                        ->disabled()
                        ->dehydrated(),
                ])
                ->createOptionAction(function (Action $action) {
                    return $action
                        ->modalHeading(__('news.form.create_category_modal_heading'))
                        ->modalButton(__('news.form.create_category_modal_button'))
                        ->label(__('news.form.create_category_label'))
                        ->icon('heroicon-o-plus');
                }),

            Select::make('tags')
                ->label(__('news.form.tags'))
                ->multiple()
                ->relationship('tags', 'name')
                ->searchable()
                ->preload(),

            Hidden::make('author')
                ->dehydrated()
                ->default(fn() => Filament::auth()->user()?->name ?? 'System'),

            FileUpload::make('thumbnail')
                ->label(__('news.form.thumbnail'))
                ->image()
                ->directory('news/thumbnails')
                ->visibility('public')
                ->maxSize(10240) // 10 MB
                ->helperText(__('news.form.thumbnail_helper'))
                ->columnSpanFull(),

            /***************************************************************************************************
             * SECTION: CONTENT SETTINGS
             * -----------------------------------------------------------------------------------------------
             * This section contains all fields related to the main content of a news post or article.
             * It includes the post title, body text, category, featured image, and other content-specific data.
             * These fields define what the reader will actually see when the article is published.
             **************************************************************************************************/

            Section::make(__('news.form.section_content'))
                ->schema([
                    Builder::make('content')
                        ->label(__('news.form.content_label'))
                        ->required()
                        ->rules(['max:64000000'])
                        ->validationMessages([
                            'max' => __('news.form.content_validation_max'),
                        ])
                        ->helperText(__('news.form.content_helper'))
                        ->columnSpanFull()
                        ->afterStateUpdated(function (Set $set, $state) {
                            // Maksimal 64 MB = 64 * 1024 * 1024 bytes
                            $maxBytes = 64 * 1024 * 1024;

                            // Ubah ke string kalau $state bukan string
                            if (is_array($state)) {
                                $contentString = json_encode($state);
                            } elseif (is_object($state)) {
                                $contentString = json_encode((array) $state);
                            } else {
                                $contentString = (string) ($state ?? '');
                            }

                            $currentLength = strlen($contentString);

                            if ($currentLength > $maxBytes) {
                                // Potong isi agar tidak melebihi batas (hanya aman untuk string)
                                $trimmed = substr($contentString, 0, $maxBytes);

                                $set('content', $trimmed);

                                Notification::make()
                                    ->title(__('news.notification.content_too_large_title'))
                                    ->body(__('news.notification.content_too_large_body'))
                                    ->danger()
                                    ->send();
                            }
                        })
                        ->blocks([
                            // ... (Your other blocks like 'heading', 'paragraph', 'image', etc. are fine)
                            // === Heading Block ===
                            Block::make('heading')
                                ->icon('heroicon-o-bars-3-bottom-left')
                                ->extraAttributes(['x-ref' => 'lexicalEditor'])
                                ->schema([
                                    TextInput::make('text')->label(__('news.form.builder.heading_text'))->required(),
                                    Select::make('level')
                                        ->label(__('news.form.builder.heading_level'))
                                        ->options([
                                            'h1' => __('news.form.builder.heading_level_h1'),
                                            'h2' => __('news.form.builder.heading_level_h2'),
                                            'h3' => __('news.form.builder.heading_level_h3'),
                                            'h4' => __('news.form.builder.heading_level_h4'),
                                            'h5' => __('news.form.builder.heading_level_h5'),
                                            'h6' => __('news.form.builder.heading_level_h6'),
                                        ])
                                        ->required(),
                                ]),

                            // === Paragraph Block ===
                            Block::make('paragraph')
                                ->icon('heroicon-o-pencil-square')
                                ->schema([
                                    Fieldset::make(__('news.form.builder.paragraph_fieldset'))
                                        ->schema([
                                            FilamentLexicalEditor::make('text')
                                                ->label(__('news.form.builder.paragraph_content'))
                                                ->required()
                                                ->rules(['max:12000000'])
                                                ->validationMessages([
                                                    'max' => __('news.form.builder.paragraph_validation_max'),
                                                ])
                                                ->helperText(__('news.form.builder.paragraph_helper'))
                                                ->enabledToolbars([
                                                    ToolbarItem::UNDO,
                                                    ToolbarItem::REDO,
                                                    ToolbarItem::FONT_FAMILY,
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
                                                    ToolbarItem::CODE,
                                                    ToolbarItem::FONT_SIZE,
                                                    ToolbarItem::BOLD,
                                                    ToolbarItem::ITALIC,
                                                    ToolbarItem::UNDERLINE,
                                                    ToolbarItem::ICODE,
                                                    ToolbarItem::LINK,
                                                    ToolbarItem::TEXT_COLOR,
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
                                                    ToolbarItem::INDENT,
                                                    ToolbarItem::OUTDENT,
                                                    ToolbarItem::HR,
                                                    ToolbarItem::IMAGE,
                                                ]),
                                        ]),
                                ]),

                            // === Image Block ===
                            Block::make('image')
                                ->icon('heroicon-o-photo')
                                ->label(__('news.form.builder.image_label'))
                                ->schema([
                                    Tabs::make('ImageSource')
                                        ->tabs([
                                            Tabs\Tab::make(__('news.form.builder.upload_file'))
                                                ->icon('heroicon-o-arrow-up-tray')->schema([
                                                    FileUpload::make('url')
                                                        ->label(__('news.form.builder.image_upload'))
                                                        ->image()
                                                        ->helperText(__('news.form.builder.image_upload_helper'))
                                                        ->maxSize(12576)
                                                        ->directory('news/images')
                                                        ->columnSpanFull()
                                                        ->reactive(),
                                                ]),
                                        ])
                                        ->columnSpanFull(),

                                    TextInput::make('alt')
                                        ->label(__('news.form.builder.image_alt'))
                                        ->placeholder(__('news.form.builder.image_alt_placeholder')),
                                    Select::make('alignment')
                                        ->label(__('news.form.builder.image_alignment'))
                                        ->options([
                                            'left' => __('news.form.builder.alignment_left'),
                                            'center' => __('news.form.builder.alignment_center'),
                                            'right' => __('news.form.builder.alignment_right'),
                                        ])
                                        ->default('center')
                                        ->required(),
                                ]),

                            // === Video Block ===
                            Block::make('video')
                                ->icon('heroicon-o-video-camera')
                                ->label('Video')
                                ->schema([
                                    Tabs::make('VideoSource')
                                        ->tabs([
                                            Tabs\Tab::make('Upload File')
                                                ->icon('heroicon-o-arrow-up-tray')
                                                ->schema([
                                                    FileUpload::make('url')
                                                        ->label(__('news.form.builder.video_upload'))
                                                        ->disk('public')
                                                        ->directory('news/videos')
                                                        ->acceptedFileTypes(['video/mp4', 'video/webm', 'video/ogg'])
                                                        ->helperText(__('news.form.builder.video_upload_helper'))
                                                        ->maxSize(24576) // 12 MB = 12 * 1024 KB
                                                        ->reactive()
                                                        ->afterStateUpdated(function (callable $set, $state) {
                                                            if ($state) {
                                                                $set('url_link', null);
                                                            }
                                                        })
                                                        ->columnSpanFull(),
                                                ]),

                                            Tabs\Tab::make('Upload Via Link')
                                                ->icon('heroicon-o-link')
                                                ->schema([
                                                    TextInput::make('url_link')
                                                        ->label(__('news.form.builder.video_url_label'))
                                                        ->placeholder(__('news.form.builder.video_url_placeholder'))
                                                        ->reactive()
                                                        ->afterStateUpdated(function (callable $set, $state) {
                                                            if ($state) {
                                                                $set('url', null);

                                                                // Konversi otomatis berdasarkan domain
                                                                $embedUrl = $state;

                                                                // YouTube
                                                                if (preg_match('/youtube\.com\/watch\?v=([a-zA-Z0-9_-]+)/', $state, $matches)) {
                                                                    $embedUrl = "https://www.youtube.com/embed/{$matches[1]}";
                                                                }

                                                                // Google Drive
                                                                elseif (preg_match('/drive\.google\.com\/file\/d\/([^\/]+)/', $state, $matches)) {
                                                                    $embedUrl = "https://drive.google.com/uc?export=download&id={$matches[1]}";
                                                                }

                                                                // OneDrive (ubah ke embed link)
                                                                elseif (preg_match('/1drv\.ms/', $state)) {
                                                                    // OneDrive biasanya redirect, kita ubah ke format embed umum
                                                                    $embedUrl = str_replace('1drv.ms', 'onedrive.live.com/embed', $state);
                                                                    $embedUrl = preg_replace('/\?.*/', '', $embedUrl);
                                                                }

                                                                $set('url_link', $embedUrl);
                                                            }
                                                        })
                                                        ->helperText(__('news.form.builder.video_url_helper'))
                                                        ->columnSpanFull(),
                                                ]),

                                        ])
                                        ->columnSpanFull(),

                                    View::make('filament.forms.components.video-preview')
                                        ->label(__('news.form.builder.video_preview'))
                                        ->reactive()
                                        ->visible(fn($get) => $get('url') || $get('url_link'))
                                        ->viewData(function ($get) {
                                            return [
                                                'fileUrl' => $get('url'),
                                                'linkUrl' => $get('url_link'),
                                            ];
                                        })
                                        ->columnSpanFull(),

                                    TextInput::make('caption')
                                        ->label(__('news.form.builder.video_caption'))
                                        ->placeholder(__('news.form.builder.video_caption_placeholder')),

                                    Select::make('alignment')
                                        ->label(__('news.form.builder.video_alignment'))
                                        ->options([
                                            'left' => __('news.form.builder.alignment_left'),
                                            'center' => __('news.form.builder.alignment_center'),
                                            'right' => __('news.form.builder.alignment_right'),
                                        ])
                                        ->default('center')
                                        ->required(),
                                    Toggle::make('featuredvideo')
                                        ->label(__('news.form.builder.video_featured'))
                                        ->helperText(__('news.form.builder.video_featured_helper'))
                                        ->default(false)
                                        ->inline(false)
                                        ->columnSpanFull(),
                                ]),

                            // === Document Block ===

                            Block::make('document')
                                ->icon('heroicon-o-document-arrow-down')
                                ->schema([
                                    Tabs::make('Document Source')
                                        ->tabs([
                                            Tabs\Tab::make('Upload File')
                                                ->icon('heroicon-o-arrow-up-tray')
                                                ->schema([
                                                    FileUpload::make('url')
                                                        ->label(__('news.form.builder.doc_upload'))
                                                        ->acceptedFileTypes([
                                                            'application/pdf',
                                                            'application/msword',
                                                            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                                                            'application/vnd.ms-excel',
                                                            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                                                        ])
                                                        ->disk('public')
                                                        ->directory('news/documents')
                                                        ->maxSize(12288)
                                                        ->helperText('Maximum limit: 12 MB file size.')
                                                        ->reactive()
                                                        ->afterStateUpdated(function (callable $set, $state) {
                                                            if ($state) {
                                                                // Reset tab link jika upload file baru
                                                                $set('url_link', null);
                                                                $set('converted_url', null);
                                                                $set('document_type', 'upload');
                                                            }
                                                        })
                                                        ->columnSpanFull(),
                                                ]),

                                            Tabs\Tab::make('Upload Via Link')
                                                ->icon('heroicon-o-link')
                                                ->schema([
                                                    TextInput::make('url_link')
                                                        ->label(__('news.form.builder.doc_url_label'))
                                                        ->placeholder(__('news.form.builder.doc_url_placeholder'))
                                                        ->helperText(__('news.form.builder.doc_url_helper'))
                                                        ->url()
                                                        ->suffixIcon('heroicon-o-link')
                                                        ->reactive()
                                                        ->afterStateUpdated(function (callable $set, $state) {
                                                            if ($state) {
                                                                // Reset upload jika link baru diisi
                                                                $set('url', null);
                                                                $set('document_type', 'link');

                                                                // Convert link untuk preview (Google Drive, OneDrive, dsb)
                                                                $convertedUrl = \App\Services\CloudDocumentService::convertToPreviewUrl($state);
                                                                $provider = \App\Services\CloudDocumentService::getProvider($state);

                                                                $set('converted_url', $convertedUrl);
                                                                $set('provider', $provider);
                                                            }
                                                        })
                                                        ->columnSpanFull(),

                                                    Forms\Components\Placeholder::make('link_info')
                                                        ->label('')
                                                        ->content(
                                                            fn($get) => $get('converted_url')
                                                                ? new \Illuminate\Support\HtmlString(
                                                                    '<div class="text-sm text-success-600 dark:text-success-400">
                                                                        ✓ Link converted successfully for preview
                                                                    </div>'
                                                                )
                                                                : null
                                                        )
                                                        ->visible(fn($get) => filled($get('converted_url')))
                                                        ->columnSpanFull(),
                                                ]),
                                        ])
                                        ->columnSpanFull(),

                                    Forms\Components\Hidden::make('document_type'),
                                    Forms\Components\Hidden::make('converted_url'),

                                    TextInput::make('name')
                                        ->label(__('news.form.builder.doc_name'))
                                        ->helperText(__('news.form.builder.doc_name_helper'))
                                        ->required(),

                                    Forms\Components\ViewField::make('preview')
                                        ->label(__('news.form.builder.doc_preview'))
                                        ->view('filament.forms.components.document-preview')
                                        ->viewData(function ($get) {
                                            $rawUrl = $get('converted_url') ?? $get('url_link') ?? $get('url');
                                            $finalUrl = null;

                                            // Jika array (misalnya hasil dari FileUpload multiple / UUID keyed)
                                            if (is_array($rawUrl)) {
                                                $firstItem = reset($rawUrl);

                                                // Jika masih temporary file
                                                if ($firstItem instanceof \Livewire\Features\SupportFileUploads\TemporaryUploadedFile) {
                                                    try {
                                                        $finalUrl = $firstItem->temporaryUrl();
                                                    } catch (\Throwable $e) {
                                                        $finalUrl = null;
                                                    }
                                                }
                                                // Jika sudah tersimpan (path)
                                                elseif (is_string($firstItem)) {
                                                    $finalUrl = \Illuminate\Support\Facades\Storage::url($firstItem);
                                                }
                                            }

                                            // Jika string langsung
                                            elseif (is_string($rawUrl) && !empty($rawUrl)) {
                                                if (filter_var($rawUrl, FILTER_VALIDATE_URL)) {
                                                    $finalUrl = $rawUrl;
                                                } elseif (\Illuminate\Support\Facades\Storage::exists($rawUrl)) {
                                                    $finalUrl = \Illuminate\Support\Facades\Storage::url($rawUrl);
                                                }
                                            }

                                            // Jika instance TemporaryUploadedFile langsung
                                            elseif ($rawUrl instanceof \Livewire\Features\SupportFileUploads\TemporaryUploadedFile) {
                                                try {
                                                    $finalUrl = $rawUrl->temporaryUrl();
                                                } catch (\Throwable $e) {
                                                    $finalUrl = null;
                                                }
                                            }

                                            return [
                                                'url' => $finalUrl,
                                                'original_link' => $get('url_link'),
                                                'document_type' => $get('document_type'),
                                                'name' => $get('name'),
                                            ];
                                        })
                                        ->visible(function ($get) {
                                            $rawUrl = $get('converted_url') ?? $get('url_link') ?? $get('url');
                                            return filled($rawUrl);
                                        })
                                        ->columnSpanFull(),

                                ])
                                ->extraAttributes([
                                    'class' => 'document-block',
                                ]),


                            // === Table Block ===
                            // Di dalam form() method Anda, cari Builder\Block::make('table')...
                            // Di dalam form() method Anda, cari Builder\Block::make('table')...
                            Block::make('table')
                                ->label(label: 'Table')
                                ->label(__('news.form.builder.table_label'))
                                ->icon('heroicon-o-table-cells')
                                ->schema([
                                    TextInput::make('heading')->label(__('news.form.builder.table_heading')),
                                    Grid::make(2)
                                        ->schema([
                                            TextInput::make('column_count')
                                                ->label(__('news.form.builder.table_column_count'))
                                                ->numeric()
                                                ->default(3)
                                                ->minValue(1)
                                                ->live(onBlur: true) // Gunakan onBlur untuk stabilitas
                                                // --- PERBAIKAN DI SINI ---
                                                ->afterStateUpdated(function (callable $set, callable $get) {
                                                    // 1. Kosongkan data 'rows' terlebih dahulu
                                                    $set('rows', []);
                                                    // 2. Buat ulang data dengan struktur yang benar
                                                    self::syncTableCells($set, $get);
                                                }),

                                            TextInput::make('row_count')
                                                ->label(__('news.form.builder.table_row_count'))
                                                ->numeric()
                                                ->default(3)
                                                ->minValue(1)
                                                ->live(onBlur: true) // Gunakan onBlur untuk stabilitas
                                                // --- PERBAIKAN DI SINI ---
                                                ->afterStateUpdated(function (callable $set, callable $get) {
                                                    // 1. Kosongkan data 'rows' terlebih dahulu
                                                    $set('rows', []);
                                                    // 2. Buat ulang data dengan struktur yang benar
                                                    self::syncTableCells($set, $get);
                                                }),
                                        ]),

                                    Repeater::make('rows')
                                        ->schema(fn(callable $get) => self::getTableColumns((int) $get('column_count')))
                                        ->columns(1)
                                        ->minItems(1)
                                        ->defaultItems(3)
                                        ->hiddenLabel(),
                                ]),
                        ]),
                ])
                ->collapsed(),


            /***************************************************************************************************
             * SECTION: PUBLICATION SETTINGS
             * -----------------------------------------------------------------------------------------------
             * This section contains all fields related to how a news post is published and optimized for SEO.
             * It includes publication status, scheduling, and meta information that affects search visibility.
             **************************************************************************************************/

            Section::make(__('news.form.section_publication'))
                ->schema([

                    // -------------------- GRID LAYOUT (2 Columns) --------------------
                    Grid::make(2)
                        ->schema([
                            Select::make('status')
                                ->label(__('news.form.status'))
                                ->options([
                                    'draft' => __('news.form.status_options.draft'),
                                    'scheduled' => __('news.form.status_options.scheduled'),
                                    'published' => __('news.form.status_options.published'),
                                ])
                                ->required()
                                ->reactive()
                                ->afterStateUpdated(function ($state, callable $set) {
                                    if ($state === 'published') {
                                        // Gunakan format datetime yang dikenali DateTimePicker
                                        $set('published_at', Carbon::now()->format('Y-m-d H:i:s'));
                                    } elseif ($state === 'scheduled') {
                                        // Kosongkan agar user bisa isi manual untuk jadwal
                                        $set('published_at', null);
                                    } else {
                                        // Draft — kosongkan
                                        $set('published_at', null);
                                    }
                                }),

                            DateTimePicker::make('published_at')
                                ->label(__('news.form.published_at'))
                                ->helperText(__('news.form.published_at_helper'))
                                ->nullable()
                                ->requiredIf('status', 'scheduled')
                                ->visible(fn($get) => in_array($get('status'), ['scheduled']))
                                ->disabled(fn($get) => $get('status') === 'published'),
                            TextInput::make('meta_title')
                                ->label(__('news.form.meta_title'))
                                ->maxLength(60)
                                ->placeholder(__('news.form.meta_title_placeholder')),
                            TextInput::make('meta_keywords')
                                ->label(__('news.form.meta_keywords'))
                                ->placeholder(__('news.form.meta_keywords_placeholder')),
                        ]),
                    Textarea::make('meta_description')
                        ->label(__('news.form.meta_description'))
                        ->maxLength(160)
                        ->placeholder(__('news.form.meta_description_placeholder')),
                ])
                ->collapsed(),
            Toggle::make('featured')
                ->label(__('news.form.featured'))
                ->helperText(__('news.form.featured_helper'))
                ->default(false)
                ->inline(false)
                ->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('no')
                    ->label(__('news.table.no'))
                    ->rowIndex()
                    ->toggleable(),
                ImageColumn::make('image_url')
                    ->label(__('news.table.thumbnail'))
                    ->size(40)
                    ->square()
                    ->getStateUsing(function ($record) {
                        // 1️⃣ Jika ada thumbnail tersimpan di storage
                        if (!empty($record->thumbnail)) {
                            // Pastikan path lengkap
                            $thumbnailPath = $record->thumbnail;

                            // Jika thumbnail sudah berisi path lengkap seperti 'news/thumbnails/xxx.jpg'
                            if (Storage::exists($thumbnailPath)) {
                                return Storage::url($thumbnailPath);
                            }

                            // Jika thumbnail hanya nama file, coba dengan prefix path
                            if (Storage::exists('news/thumbnails/' . $thumbnailPath)) {
                                return Storage::url('news/thumbnails/' . $thumbnailPath);
                            }

                            // Jika ada di public/storage
                            if (file_exists(public_path('storage/' . $thumbnailPath))) {
                                return asset('storage/' . $thumbnailPath);
                            }
                        }

                        // 2️⃣ Coba ambil gambar pertama dari konten
                        $content = $record->content;

                        // Decode jika JSON string
                        if (is_string($content)) {
                            $decoded = json_decode($content, true);
                            if (json_last_error() === JSON_ERROR_NONE) {
                                $content = $decoded;
                            }
                        }

                        // Jika konten berupa array (dari Editor.js atau JSON)
                        if (is_array($content)) {
                            foreach ($content as $block) {
                                if (isset($block['type']) && $block['type'] === 'image') {
                                    $imageUrl = $block['data']['url_link'] ?? $block['data']['url'] ?? null;

                                    if ($imageUrl) {
                                        // Jika URL absolut
                                        if (str_starts_with($imageUrl, 'http')) {
                                            return $imageUrl;
                                        }
                                        // Jika URL relatif
                                        return asset('storage/' . ltrim($imageUrl, '/'));
                                    }
                                }
                            }
                        }

                        // Jika konten HTML string, cari tag <img>
                        if (is_string($content) && preg_match('/<img[^>]+src="([^">]+)"/i', $content, $matches)) {
                            return $matches[1];
                        }

                        // 3️⃣ Fallback ke logo
                        return asset('images/logo.png');
                    })
                    ->extraAttributes(['class' => 'cursor-pointer'])
                    ->url(fn($record) => $record->getStateUsing ? null : asset('images/logo.png'), shouldOpenInNewTab: false)
                    ->tooltip(__('news.table.thumbnail_tooltip')),
                TextColumn::make('title')
                    ->label(__('news.table.title'))
                    ->searchable()
                    ->wrap(),
                TextColumn::make('slug')
                    ->label(__('news.table.slug'))
                    ->copyable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('author')
                    ->label(__('news.table.author'))
                    ->sortable()
                    ->searchable()
                    ->wrap()
                    ->toggleable(),
                TextColumn::make('status')->label(__('news.table.status'))
                    ->badge()
                    ->colors([
                        'warning' => 'draft',
                        'success' => 'published',
                        'gray' => 'archived',
                    ]),
                TextColumn::make('published_at')
                    ->label(__('news.table.published_at'))
                    ->dateTime('d M Y H:i:s')
                    ->sortable(),
                TextColumn::make('views')->label(__('news.table.views'))->sortable()->default(0),
                TextColumn::make('created_at')
                    ->label(__('news.table.created_at'))
                    ->label('Created At')
                    ->dateTime()
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_by')
                    ->label(__('news.table.updated_by'))
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->label(__('news.table.updated_at'))
                    ->dateTime()
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('keterangan')
                    ->label(__('news.table.information'))
                    ->formatStateUsing(function ($state) {
                        $decoded = json_decode($state, true);

                        if (empty($decoded)) {
                            return '-';
                        }

                        // Contoh ambil elemen pertama
                        $type = $decoded[0]['type'] ?? 'unknown';
                        $data = $decoded[0]['data'] ?? [];

                        // Ubah jadi teks yang mudah dibaca
                        if ($type === 'heading') {
                            return "Heading: " . ($data['text'] ?? '');
                        } elseif ($type === 'video') {
                            return "Video (" . ($data['alignment'] ?? '-') . ")";
                        } else {
                            return ucfirst($type);
                        }
                    })
                    ->wrap()
                    ->limit(255)
                    ->sortable()
                    ->searchable()
                    ->formatStateUsing(fn($state) => strip_tags($state))
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('content')
                    ->label(__('news.table.content'))
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->hidden(fn() => true)
                    ->searchable(false)
                    ->sortable(false)
                    ->wrap(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('author')
                    ->label(__('news.filter.author'))
                    ->options(User::query()->select('name')->distinct()->pluck('name', 'name')->filter()->toArray()),
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'draft' => __('news.filter.status_options.draft'),
                        'published' => __('news.filter.status_options.published'),
                        'scheduled' => __('news.filter.status_options.scheduled')
                    ]),
                Tables\Filters\SelectFilter::make('category_id')->relationship('category', 'name'),
                Tables\Filters\Filter::make('published_at')
                    ->form([
                        Forms\Components\DatePicker::make('from')->label(__('news.filter.published_from')),
                        Forms\Components\DatePicker::make('to')->label(__('news.filter.published_to')),
                    ])
                    ->query(
                        fn($query, array $data) =>
                        $query
                            ->when($data['from'], fn($q) => $q->whereDate('published_at', '>=', $data['from']))
                            ->when($data['to'], fn($q) => $q->whereDate('published_at', '<=', $data['to']))
                    ),
            ])
            ->actions([
                Tables\Actions\Action::make('publish')
                    ->label(__('news.action.publish'))
                    ->icon('heroicon-o-paper-airplane')
                    ->color('success')
                    ->requiresConfirmation()
                    ->visible(fn(News $record) => $record->status === 'draft')
                    ->action(function (News $record) {
                        $record->update([
                            'status' => 'published',
                            'published_at' => now(),
                        ]);

                        // Kirim notifikasi
                        self::sendNewsNotification($record);

                        Notification::make()
                            ->title(__('news.notification.publish_success_title'))
                            ->body(__('news.notification.publish_success_body'))
                            ->success()
                            ->send();
                    }),
                Tables\Actions\ViewAction::make()
                    ->icon('heroicon-o-eye')
                    ->url(fn($record) => url('/news/' . $record->slug))
                    ->openUrlInNewTab(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->visible(fn() => in_array(Filament::auth()->user()->role, ['admin', 'superadmin'])),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
            
                    ExportBulkAction::make()
                        ->label('Export yang Dipilih')
                        ->exports([
                            ExcelExport::make()
                                ->fromTable()
                                ->withFilename('berita-terpilih-' . date('Y-m-d'))
                                ->withColumns([
                                    Column::make('no')
                                        ->heading('No')
                                        ->getStateUsing(function () {
                                            static $no = 0;
                                            return ++$no;
                                        }),
                            
                                    Column::make('title')
                                        ->heading('Judul'),
                            
                                    Column::make('author.name')
                                        ->heading('Penulis'),
                            
                                    Column::make('status')
                                        ->heading('Status')
                                        ->formatStateUsing(fn ($state) => match ($state) {
                                            'published' => 'Published',
                                            'draft' => 'Draft',
                                            'scheduled' => 'Scheduled',
                                            default => $state,
                                        }),
                            
                                    Column::make('views')
                                        ->heading('Dilihat'),
                            
                                    Column::make('created_at')
                                        ->heading('Dibuat')
                                        ->formatStateUsing(fn ($state) =>
                                            $state ? \Carbon\Carbon::parse($state)->format('d M Y H:i') : '-'
                                        ),
                            
                                    Column::make('updated_at')
                                        ->heading('Diperbarui')
                                        ->formatStateUsing(fn ($state) =>
                                            $state ? \Carbon\Carbon::parse($state)->format('d M Y H:i') : '-'
                                        ),
                                ])
                            ])
                    ->visible(fn () =>
                        auth()->user()?->role === 'admin' ||
                        auth()->user()?->role === 'superadmin'
                    ),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListNews::route('/'),
            'create' => Pages\CreateNews::route('/create'),
            'edit' => Pages\EditNews::route('/{record}/edit'),
        ];
    }

    // THE FIX: This entire method is corrected to solve the TypeError.
    protected static function getTableColumns(?int $columnCount): array
    {
        // First, ensure the value is a valid integer, defaulting to 3 if not.
        $actualColumnCount = (int) $columnCount;
        if ($actualColumnCount <= 0) {
            $actualColumnCount = 3;
        }

        // Create the text input fields
        $textInputs = [];
        for ($i = 0; $i < $actualColumnCount; $i++) {
            $textInputs[] = TextInput::make("col{$i}")
                ->label("Column " . ($i + 1));
        }

        // Return a single, pre-configured Grid component.
        // The columns() method now receives a valid integer, not a function.
        return [
            Grid::make()
                ->schema($textInputs)
                ->columns($actualColumnCount),
        ];
    }

    protected static function syncTableCells(callable $set, callable $get): void
    {
        $columnCount = (int)$get('column_count'); // Cast to be safe
        $rowCount = (int)$get('row_count');     // Cast to be safe
        $currentRows = $get('rows');

        $newRows = [];

        if (count($currentRows ?? []) > $rowCount) {
            $currentRows = array_slice($currentRows, 0, $rowCount);
        }

        for ($i = 0; $i < $rowCount; $i++) {
            $row = $currentRows[$i] ?? [];
            for ($j = 0; $j < $columnCount; $j++) {
                if (!array_key_exists("col{$j}", $row)) {
                    $row["col{$j}"] = null;
                }
            }

            foreach ($row as $key => $value) {
                if (Str::startsWith($key, 'col') && (int) Str::after($key, 'col') >= $columnCount) {
                    unset($row[$key]);
                }
            }
            $newRows[$i] = $row;
        }

        $set('rows', $newRows);
    }

    protected static function convertCloudLink(?string $url): ?string
    {
        if (empty($url)) {
            return null;
        }

        // Google Drive conversion
        if (preg_match('/drive\.google\.com\/file\/d\/([a-zA-Z0-9_-]+)/', $url, $matches)) {
            return "https://drive.google.com/file/d/{$matches[1]}/preview";
        }

        if (preg_match('/drive\.google\.com\/open\?id=([a-zA-Z0-9_-]+)/', $url, $matches)) {
            return "https://drive.google.com/file/d/{$matches[1]}/preview";
        }

        // OneDrive conversion
        if (str_contains($url, 'onedrive.live.com') || str_contains($url, '1drv.ms')) {
            // Convert OneDrive share link to embed link
            if (preg_match('/onedrive\.live\.com.*resid=([A-Z0-9]+).*/', $url, $matches)) {
                return str_replace('view.aspx', 'embed', $url);
            }
            // For shortened links, keep original (will need backend processing)
            return $url . (str_contains($url, '?') ? '&' : '?') . 'embed=1';
        }

        // Dropbox conversion
        if (str_contains($url, 'dropbox.com')) {
            return str_replace('www.dropbox.com', 'dl.dropboxusercontent.com', $url);
        }

        // Direct link - no conversion needed
        return $url;
    }

    public static function mutateFormDataBeforeSave(array $data): array
    {
        if (empty($data['thumbnail']) && isset($data['content'])) {
            $firstImage = collect($data['content'])
                ->firstWhere('type', 'image')['data']['url'] ?? null;
            if ($firstImage) $data['thumbnail'] = $firstImage;
        }

        $data['updated_by'] ??= Filament::auth()->user()?->name ?? 'System';

        return $data;
    }


    protected function beforeSave(): void
    {
        $content = $this->data['content'] ?? '';
        $contentString = is_array($content) ? json_encode($content) : (string) $content;

        if (strlen($contentString) > 64 * 1024 * 1024) {
            Notification::make()
                ->title('Content size is too large')
                ->body('The maximum content size is 64 MB. Some of the content has been automatically truncated.')
                ->danger()
                ->send();

            $this->halt(); // stop simpan data
        }
    }

    // Method untuk kirim notifikasi
    protected static function sendNewsNotification(News $news, array $roles = null): void
    {
        // Default roles jika tidak ditentukan
        if ($roles === null) {
            $roles = ['superadmin', 'admin', 'author', 'user'];
        }

        // Ambil users berdasarkan role dan yang aktif notifikasi
        $users = User::receivingNotifications()
            ->withAnyRole($roles)
            ->get();

        if ($users->isEmpty()) {
            return;
        }

        // Kirim notifikasi
        Notification::make()
            ->title('Berita Baru: ' . $news->title)
            ->body($news->keterangan ?? 'Ada berita baru yang menarik untuk Anda!')
            ->icon('heroicon-o-newspaper')
            ->iconColor('info')
            ->actions([
                \Filament\Notifications\Actions\Action::make('read')
                    ->label('Baca Sekarang')
                    ->url(route('news.show', $news->slug))
                    ->markAsRead(),
            ])
            ->sendToDatabase($users);
    }

    // Hook after create
    public static function afterCreate(News $record): void
    {
        // Set author
        $record->update([
            'author' => Filament::auth()->user()->name ?? 'System',
        ]);

        // Kirim notifikasi jika status published dan checkbox aktif
        if (
            $record->status === 'published' &&
            request()->input('send_notification', false)
        ) {
            $roles = request()->input('notification_roles', ['superadmin', 'admin', 'author']);
            self::sendNewsNotification($record, $roles);
        }
    }

    // Hook after update
    public static function afterUpdate(News $record): void
    {
        // Set updated by
        $record->update([
            'updated_by' => Filament::auth()->user()->name ?? 'System',
        ]);

        // Kirim notifikasi jika baru saja dipublikasikan
        if (
            $record->wasChanged('status') &&
            $record->status === 'published' &&
            request()->input('send_notification', false)
        ) {
            $roles = request()->input('notification_roles', ['superadmin', 'admin', 'author']);
            self::sendNewsNotification($record, $roles);
        }
    }
}