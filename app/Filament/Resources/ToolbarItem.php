<?php

namespace App\Filament\Resources;

use Filament\Forms\Components\Field;


/**
 * This is a placeholder for the custom ToolbarItem form component.
 * The original error "Class not found" occurred because this file was missing.
 *
 * This class likely needs a corresponding view file to render correctly.
 * For now, its existence will resolve the immediate error.
 */
class ToolbarItem extends Field
{
    /**
     * The view that will be used to render this component.
     * The path might need to be adjusted based on your project structure.
     * e.g., 'filament.forms.components.toolbar-item'
     *
     * @var string
     */
    protected string $view = ''; // You might need to create and specify a view here.

    public static function make(string $name): static
    {
        return parent::make($name);
    }

    // --- Menambahkan konstanta yang hilang agar Malzariey\FilamentLexicalEditor bisa berfungsi ---
    public const UNDO = 'undo';
    public const REDO = 'redo';
    public const FONT_FAMILY = 'fontFamily';
    public const NORMAL = 'normal';
    public const H1 = 'h1';
    public const H2 = 'h2';
    public const H3 = 'h3';
    public const H4 = 'h4';
    public const H5 = 'h5';
    public const H6 = 'h6';
    public const BULLET = 'bullet';
    public const NUMBERED = 'numbered';
    public const QUOTE = 'quote';
    public const CODE = 'code';
    public const FONT_SIZE = 'fontSize';
    public const BOLD = 'bold';
    public const ITALIC = 'italic';
    public const UNDERLINE = 'underline';
    public const ICODE = 'icode';
    public const LINK = 'link';
    public const TEXT_COLOR = 'textColor';
    public const BACKGROUND_COLOR = 'backgroundColor';
    public const LOWERCASE = 'lowercase';
    public const UPPERCASE = 'uppercase';
    public const CAPITALIZE = 'capitalize';
    public const STRIKETHROUGH = 'strikethrough';
    public const SUBSCRIPT = 'subscript';
    public const SUPERSCRIPT = 'superscript';
    public const CLEAR = 'clear';
    public const LEFT = 'left';
    public const CENTER = 'center';
    public const RIGHT = 'right';
    public const JUSTIFY = 'justify';
    public const START = 'start'; // Added for completeness, if used in LexicalEditor
    public const END = 'end';     // Added for completeness, if used in LexicalEditor
    public const INDENT = 'indent';
    public const OUTDENT = 'outdent';
    public const HR = 'hr';     // Horizontal Rule
    public const IMAGE = 'image';
    public const DIVIDER = 'divider';
    // --- Akhir penambahan konstanta ---
}
