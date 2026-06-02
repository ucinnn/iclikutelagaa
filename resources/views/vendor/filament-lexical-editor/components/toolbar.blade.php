@props(['toolbar' => '' ])
@php
    use Malzariey\FilamentLexicalEditor\Enums\ToolbarItem;
    $item = $toolbar instanceof ToolbarItem ? $toolbar : ToolbarItem::from($toolbar);
@endphp
@switch($item)
    @case(ToolbarItem::UNDO)
        <x-filament-lexical-editor::toolbar-item ref="undo" disable-option="cannotUndo"
                                                 title="{{ __('filament-lexical-editor::lexical-editor.undo') }}" shortcut="Ctrl+Z" icon="undo"/>
        @break
    @case(ToolbarItem::REDO)
        <x-filament-lexical-editor::toolbar-item ref="redo" disable-option="cannotRedo"
                                                 title="{{ __('filament-lexical-editor::lexical-editor.redo') }}" shortcut="Ctrl+Y" icon="redo"/>
        @break
    @case(ToolbarItem::FONT_FAMILY)
        <div class="relative w-64 h-11 py-1">
            <style>
                /* Style umum untuk select font */
                select.font-family {
                    appearance: none;
                    -webkit-appearance: none;
                    -moz-appearance: none;
                    padding: 8px 36px 8px 40px;
                    border-radius: 0.5rem;
                    border: 1px solid #d1d5db; /* gray-300 */
                    width: 100%;
                    height: 42px;
                    background-color: var(--select-bg, #fff);
                    color: var(--select-text, #111827); /* gray-900 */
                    font-size: 15px;
                    line-height: 1.4;
                    transition: all 0.2s ease;
                }

                /* Dark mode */
                @media (prefers-color-scheme: dark) {
                    select.font-family {
                        --select-bg: #1f2937; /* gray-800 */
                        --select-text: #f9fafb; /* gray-50 */
                        border-color: #374151; /* gray-700 */
                    }

                    select.font-family option {
                        background-color: #1f2937;
                        color: #f9fafb;
                    }

                    select.font-family option:hover {
                        background-color: #374151;
                    }
                }

                select.font-family:focus {
                    border-color: #3b82f6; /* blue-500 */
                    box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.3);
                    outline: none;
                }

                select.font-family option {
                    font-size: 15px;
                    color: #111827;
                    background-color: #fff;
                    padding: 4px 8px;
                }

                select.font-family option:hover {
                    background-color: #f3f4f6; /* gray-100 */
                }

                /* Tambah ikon "T" di kiri */
                .font-select-wrapper {
                    position: relative;
                }

                .font-select-wrapper::before {
                    content: "T";
                    position: absolute;
                    left: 12px;
                    top: 50%;
                    transform: translateY(-50%);
                    font-size: 16px;
                    color: var(--icon-color, #6b7280); /* gray-500 */
                    font-weight: 500;
                }

                @media (prefers-color-scheme: dark) {
                    .font-select-wrapper::before {
                        --icon-color: #9ca3af; /* gray-400 */
                    }
                }
            </style>

            <div class="font-select-wrapper">
                <select x-ref="fontFamily"
                    class="toolbar-item spaced font-family"
                    x-tooltip="'{{ __('filament-lexical-editor::lexical-editor.font_family') }}'">

                        <!-- Sans-Serif Fonts -->
                        <optgroup label="Sans-Serif Fonts">
                            <option value="Arial" style="font-family: Arial, sans-serif;">Arial</option>
                            <option value="Arial Black" style="font-family: 'Arial Black', sans-serif;">Arial Black</option>
                            <option value="Calibri" style="font-family: Calibri, sans-serif;">Calibri</option>
                            <option value="Candara" style="font-family: Candara, sans-serif;">Candara</option>
                            <option value="Century Gothic" style="font-family: 'Century Gothic', sans-serif;">Century Gothic</option>
                            <option value="Franklin Gothic Medium" style="font-family: 'Franklin Gothic Medium', sans-serif;">Franklin Gothic Medium</option>
                            <option value="Futura" style="font-family: Futura, sans-serif;">Futura</option>
                            <option value="Geneva" style="font-family: Geneva, sans-serif;">Geneva</option>
                            <option value="Gill Sans" style="font-family: 'Gill Sans', sans-serif;">Gill Sans</option>
                            <option value="Helvetica" style="font-family: Helvetica, sans-serif;">Helvetica</option>
                            <option value="Impact" style="font-family: Impact, sans-serif;">Impact</option>
                            <option value="Inter" style="font-family: Inter, sans-serif;">Inter</option>
                            <option value="Lato" style="font-family: Lato, sans-serif;">Lato</option>
                            <option value="Lucida Grande" style="font-family: 'Lucida Grande', sans-serif;">Lucida Grande</option>
                            <option value="Montserrat" style="font-family: Montserrat, sans-serif;">Montserrat</option>
                            <option value="Nunito" style="font-family: Nunito, sans-serif;">Nunito</option>
                            <option value="Open Sans" style="font-family: 'Open Sans', sans-serif;">Open Sans</option>
                            <option value="Optima" style="font-family: Optima, sans-serif;">Optima</option>
                            <option value="Poppins" style="font-family: Poppins, sans-serif;">Poppins</option>
                            <option value="Roboto" style="font-family: Roboto, sans-serif;">Roboto</option>
                            <option value="Segoe UI" style="font-family: 'Segoe UI', sans-serif;">Segoe UI</option>
                            <option value="Source Sans Pro" style="font-family: 'Source Sans Pro', sans-serif;">Source Sans Pro</option>
                            <option value="Tahoma" style="font-family: Tahoma, sans-serif;">Tahoma</option>
                            <option value="Trebuchet MS" style="font-family: 'Trebuchet MS', sans-serif;">Trebuchet MS</option>
                            <option value="Ubuntu" style="font-family: Ubuntu, sans-serif;">Ubuntu</option>
                            <option value="Verdana" style="font-family: Verdana, sans-serif;">Verdana</option>
                        </optgroup>

                        <!-- Serif Fonts -->
                        <optgroup label="Serif Fonts">
                            <option value="American Typewriter" style="font-family: 'American Typewriter', serif;">American Typewriter</option>
                            <option value="Baskerville" style="font-family: Baskerville, serif;">Baskerville</option>
                            <option value="Big Caslon" style="font-family: 'Big Caslon', serif;">Big Caslon</option>
                            <option value="Bodoni MT" style="font-family: 'Bodoni MT', serif;">Bodoni MT</option>
                            <option value="Bookman Old Style" style="font-family: 'Bookman Old Style', serif;">Bookman Old Style</option>
                            <option value="Cambria" style="font-family: Cambria, serif;">Cambria</option>
                            <option value="Cochin" style="font-family: Cochin, serif;">Cochin</option>
                            <option value="Constantia" style="font-family: Constantia, serif;">Constantia</option>
                            <option value="Copperplate" style="font-family: Copperplate, serif;">Copperplate</option>
                            <option value="Didot" style="font-family: Didot, serif;">Didot</option>
                            <option value="Garamond" style="font-family: Garamond, serif;">Garamond</option>
                            <option value="Georgia" style="font-family: Georgia, serif;">Georgia</option>
                            <option value="Goudy Old Style" style="font-family: 'Goudy Old Style', serif;">Goudy Old Style</option>
                            <option value="Hoefler Text" style="font-family: 'Hoefler Text', serif;">Hoefler Text</option>
                            <option value="Libre Baskerville" style="font-family: 'Libre Baskerville', serif;">Libre Baskerville</option>
                            <option value="Lora" style="font-family: Lora, serif;">Lora</option>
                            <option value="Merriweather" style="font-family: Merriweather, serif;">Merriweather</option>
                            <option value="Noto Serif" style="font-family: 'Noto Serif', serif;">Noto Serif</option>
                            <option value="Palatino" style="font-family: Palatino, serif;">Palatino</option>
                            <option value="Playfair Display" style="font-family: 'Playfair Display', serif;">Playfair Display</option>
                            <option value="Rockwell" style="font-family: Rockwell, serif;">Rockwell</option>
                            <option value="Times New Roman" style="font-family: 'Times New Roman', serif;">Times New Roman</option>
                        </optgroup>

                        <!-- Monospace Fonts -->
                        <optgroup label="Monospace Fonts">
                            <option value="Andale Mono" style="font-family: 'Andale Mono', monospace;">Andale Mono</option>
                            <option value="Consolas" style="font-family: Consolas, monospace;">Consolas</option>
                            <option value="Courier New" style="font-family: 'Courier New', monospace;">Courier New</option>
                            <option value="Fira Code" style="font-family: 'Fira Code', monospace;">Fira Code</option>
                            <option value="JetBrains Mono" style="font-family: 'JetBrains Mono', monospace;">JetBrains Mono</option>
                            <option value="Lucida Console" style="font-family: 'Lucida Console', monospace;">Lucida Console</option>
                            <option value="Monaco" style="font-family: Monaco, monospace;">Monaco</option>
                            <option value="Roboto Mono" style="font-family: 'Roboto Mono', monospace;">Roboto Mono</option>
                            <option value="Source Code Pro" style="font-family: 'Source Code Pro', monospace;">Source Code Pro</option>
                            <option value="Space Mono" style="font-family: 'Space Mono', monospace;">Space Mono</option>
                        </optgroup>

                        <!-- Display & Handwritten Fonts -->
                        <optgroup label="Display & Handwritten Fonts">
                            <option value="Abril Fatface" style="font-family: 'Abril Fatface', cursive;">Abril Fatface</option>
                            <option value="Anton" style="font-family: Anton, sans-serif;">Anton</option>
                            <option value="Bebas Neue" style="font-family: 'Bebas Neue', cursive;">Bebas Neue</option>
                            <option value="Caveat" style="font-family: Caveat, cursive;">Caveat</option>
                            <option value="Comfortaa" style="font-family: Comfortaa, cursive;">Comfortaa</option>
                            <option value="Comic Sans MS" style="font-family: 'Comic Sans MS', cursive;">Comic Sans MS</option>
                            <option value="Courgette" style="font-family: Courgette, cursive;">Courgette</option>
                            <option value="Dancing Script" style="font-family: 'Dancing Script', cursive;">Dancing Script</option>
                            <option value="Great Vibes" style="font-family: 'Great Vibes', cursive;">Great Vibes</option>
                            <option value="Indie Flower" style="font-family: 'Indie Flower', cursive;">Indie Flower</option>
                            <option value="Lobster" style="font-family: Lobster, cursive;">Lobster</option>
                            <option value="Oswald" style="font-family: Oswald, sans-serif;">Oswald</option>
                            <option value="Pacifico" style="font-family: Pacifico, cursive;">Pacifico</option>
                            <option value="Permanent Marker" style="font-family: 'Permanent Marker', cursive;">Permanent Marker</option>
                            <option value="Raleway" style="font-family: Raleway, sans-serif;">Raleway</option>
                            <option value="Shadows Into Light" style="font-family: 'Shadows Into Light', cursive;">Shadows Into Light</option>
                        </optgroup>

                    </select>
                </div>
            </div>
    @break


    @case(ToolbarItem::NORMAL)
        <x-filament-lexical-editor::toolbar-item active-option="blockType == 'paragraph'" ref="normal"
                                                 title="{{ __('filament-lexical-editor::lexical-editor.normal') }}" shortcut="Ctrl+Alt+0"
                                                 icon="paragraph"/>
        @break
    @case(ToolbarItem::H1)
        <x-filament-lexical-editor::toolbar-item active-option="blockType == 'h1'" ref="h1"
                                                 title="{{ __('filament-lexical-editor::lexical-editor.heading_1') }}" shortcut="Ctrl+Alt+1"
                                                 icon="h1"/>
        @break
    @case(ToolbarItem::H2)
        <x-filament-lexical-editor::toolbar-item ref="h2" active-option="blockType == 'h2'"
                                                 title="{{ __('filament-lexical-editor::lexical-editor.heading_2') }}" shortcut="Ctrl+Alt+2"
                                                 icon="h2"/>
        @break
    @case(ToolbarItem::H3)
        <x-filament-lexical-editor::toolbar-item ref="h3" active-option="blockType == 'h3'"
                                                 title="{{ __('filament-lexical-editor::lexical-editor.heading_3') }}" shortcut="Ctrl+Alt+3"
                                                 icon="h3"/>
        @break
    @case(ToolbarItem::H4)
        <x-filament-lexical-editor::toolbar-item ref="h4" active-option="blockType == 'h4'"
                                                 title="{{ __('filament-lexical-editor::lexical-editor.heading_4') }}" shortcut="Ctrl+Alt+4"
                                                 icon="h4"/>
        @break
    @case(ToolbarItem::H5)
        <x-filament-lexical-editor::toolbar-item ref="h5" active-option="blockType == 'h5'"
                                                 title="{{ __('filament-lexical-editor::lexical-editor.heading_5') }}" shortcut="Ctrl+Alt+5"
                                                 icon="h5"/>
        @break
    @case(ToolbarItem::H6)
        <x-filament-lexical-editor::toolbar-item ref="h6" active-option="blockType == 'h6'"
                                                 title="{{ __('filament-lexical-editor::lexical-editor.heading_6') }}" shortcut="Ctrl+Alt+6"
                                                 icon="h6"/>
        @break
    @case(ToolbarItem::BULLET)
        <x-filament-lexical-editor::toolbar-item ref="bullet" active-option="blockType == 'bullet'"
                                                 title="{{ __('filament-lexical-editor::lexical-editor.bullet_list') }}" shortcut="Ctrl+Alt+7"
                                                 icon="bullet-list"/>
        @break
    @case(ToolbarItem::NUMBERED)
        <x-filament-lexical-editor::toolbar-item ref="numbered" active-option="blockType == 'number'"
                                                 title="{{ __('filament-lexical-editor::lexical-editor.numbered_list') }}" shortcut="Ctrl+Alt+8"
                                                 icon="numbered-list"/>
        @break
    @case(ToolbarItem::QUOTE)
        <x-filament-lexical-editor::toolbar-item ref="quote" active-option="blockType == 'quote'"
                                                 title="{{ __('filament-lexical-editor::lexical-editor.quote') }}" shortcut="Ctrl+Alt+Q" icon="quote"/>
        @break
    @case(ToolbarItem::CODE)
        <x-filament-lexical-editor::toolbar-item ref="code" active-option="blockType == 'code'"
                                                 title="{{ __('filament-lexical-editor::lexical-editor.code_block') }}" shortcut="Ctrl+Alt+C"
                                                 icon="code"/>
        @break
    @case(ToolbarItem::FONT_SIZE)
        <x-filament-lexical-editor::toolbar-item ref="decrement" class="font-decrement"
                                                 title="{{ __('filament-lexical-editor::lexical-editor.decrease_font_size') }}" shortcut="Ctrl+Shift+,"
                                                 icon-class="format" icon="minus-icon"/>
        <input type="number" title="Font size" x-ref="fontSize" class="toolbar-item font-size-input w-16 " min="8"
               max="420" value="15">
        <x-filament-lexical-editor::toolbar-item ref="increment" class="font-increment"
                                                 title="{{ __('filament-lexical-editor::lexical-editor.increase_font_size') }}" shortcut="Ctrl+Shift+."
                                                 icon-class="format" icon="add-icon"/>
        @break
    @case(ToolbarItem::BOLD)
        <x-filament-lexical-editor::toolbar-item active-option="isBold" ref="bold" title="{{ __('filament-lexical-editor::lexical-editor.bold') }}"
                                                 shortcut="Ctrl+B" icon="bold"/>
        @break
    @case(ToolbarItem::ITALIC)
        <x-filament-lexical-editor::toolbar-item active-option="isItalic" ref="italic"
                                                 title="{{ __('filament-lexical-editor::lexical-editor.italic') }}" shortcut="Ctrl+I" icon="italic"/>
        @break
    @case(ToolbarItem::UNDERLINE)
        <x-filament-lexical-editor::toolbar-item active-option="isUnderline" ref="underline"
                                                 title="{{ __('filament-lexical-editor::lexical-editor.underline') }}" shortcut="Ctrl+U"
                                                 icon="underline"/>
        @break
    @case(ToolbarItem::ICODE)
        <x-filament-lexical-editor::toolbar-item active-option="isCode" ref="icode"
                                                 title="{{ __('filament-lexical-editor::lexical-editor.insert_code_block') }}" shortcut="Ctrl+Shift+C"
                                                 icon="code"/>
        @break
    @case(ToolbarItem::LINK)
        <x-filament-lexical-editor::toolbar-item active-option="isLink" ref="link"
                                                 title="{{ __('filament-lexical-editor::lexical-editor.insert_link') }}" shortcut="Ctrl+K"
                                                 icon="link"/>
        @break
    @case(ToolbarItem::TEXT_COLOR)
        <x-filament-lexical-editor::text-color-dialog ref="text_color" icon="font-color"
                                                      :title="__('filament-lexical-editor::lexical-editor.formatting_text_color')"/>
        @break
    @case(ToolbarItem::BACKGROUND_COLOR)
        <x-filament-lexical-editor::text-color-dialog ref="background_color" icon="bg-color"
                                                      :title="__('filament-lexical-editor::lexical-editor.formatting_background_color')"/>
        @break
    @case(ToolbarItem::LOWERCASE)
        <x-filament-lexical-editor::toolbar-item active-option="isLowercase" ref="lowercase"
                                                 title="{{ __('filament-lexical-editor::lexical-editor.lowercase') }}" shortcut="Ctrl+Shift+1"
                                                 icon="lowercase"/>
        @break
    @case(ToolbarItem::UPPERCASE)
        <x-filament-lexical-editor::toolbar-item active-option="isUppercase" ref="uppercase"
                                                 title="{{ __('filament-lexical-editor::lexical-editor.uppercase') }}" shortcut="Ctrl+Shift+2"
                                                 icon="uppercase"/>
        @break
    @case(ToolbarItem::CAPITALIZE)
        <x-filament-lexical-editor::toolbar-item active-option="isCapitalize" ref="capitalize"
                                                 title="{{ __('filament-lexical-editor::lexical-editor.capitalize') }}" shortcut="Ctrl+Shift+3"
                                                 icon="capitalize"/>
        @break
    @case(ToolbarItem::STRIKETHROUGH)
        <x-filament-lexical-editor::toolbar-item active-option="isStrikethrough" ref="strikethrough"
                                                 title="{{ __('filament-lexical-editor::lexical-editor.strikethrough') }}" shortcut="Ctrl+Shift+S"
                                                 icon="strikethrough"/>
        @break
    @case(ToolbarItem::SUBSCRIPT)
        <x-filament-lexical-editor::toolbar-item active-option="isSubscript" ref="subscript"
                                                 title="{{ __('filament-lexical-editor::lexical-editor.subscript') }}" shortcut="Ctrl+,"
                                                 icon="subscript"/>
        @break
    @case(ToolbarItem::SUPERSCRIPT)
        <x-filament-lexical-editor::toolbar-item active-option="isSuperscript" ref="superscript"
                                                 title="{{ __('filament-lexical-editor::lexical-editor.superscript') }}" shortcut="Ctrl+."
                                                 icon="superscript"/>
        @break
    @case(ToolbarItem::CLEAR)
        <x-filament-lexical-editor::toolbar-item ref="clear" title="{{ __('filament-lexical-editor::lexical-editor.clear_text_formatting') }}"
                                                 shortcut="Ctrl+/" icon="clear"/>
        @break
    @case(ToolbarItem::LEFT)
        <x-filament-lexical-editor::toolbar-item active-option="elementFormat == 'left'" ref="left" rtl-ref="right"
                                                 title="{{ __('filament-lexical-editor::lexical-editor.left_align') }}"
                                                 rtl-title="{{ __('filament-lexical-editor::lexical-editor.right_align') }}" shortcut="Ctrl+Shift+L"
                                                 rtl-shortcut="Ctrl+Shift+R" icon="left-align" rtl-icon="right-align"/>
        @break
    @case(ToolbarItem::CENTER)
        <x-filament-lexical-editor::toolbar-item active-option="elementFormat == 'center'" ref="center"
                                                 title="{{ __('filament-lexical-editor::lexical-editor.center_align') }}" shortcut="Ctrl+Shift+E"
                                                 icon="center-align"/>
        @break
    @case(ToolbarItem::RIGHT)
        <x-filament-lexical-editor::toolbar-item active-option="elementFormat == 'right'" ref="right" rtl-ref="left"
                                                 title="{{ __('filament-lexical-editor::lexical-editor.right_align') }}"
                                                 rtl-title="{{ __('filament-lexical-editor::lexical-editor.left_align') }}" shortcut="Ctrl+Shift+R"
                                                 rtl-shortcut="Ctrl+Shift+L" icon="right-align" rtl-icon="left-align"/>
        @break
    @case(ToolbarItem::JUSTIFY)
        <x-filament-lexical-editor::toolbar-item active-option="elementFormat == 'justify'" ref="justify"
                                                 title="{{ __('filament-lexical-editor::lexical-editor.justify_align') }}" shortcut="Ctrl+Shift+J"
                                                 icon="justify-align"/>
        @break
    @case(ToolbarItem::START)
        <x-filament-lexical-editor::toolbar-item active-option="elementFormat == 'start'" ref="start" rtl-ref="end"
                                                 title="{{ __('filament-lexical-editor::lexical-editor.start_align') }}"
                                                 rtl-title="{{ __('filament-lexical-editor::lexical-editor.end_align') }}" shortcut="Ctrl+Shift+["
                                                 rtl-shortcut="Ctrl+Shift+]" icon="left-align" rtl-icon="right-align"/>
        @break
    @case(ToolbarItem::END)
        <x-filament-lexical-editor::toolbar-item active-option="elementFormat == 'end'" ref="end" rtl-ref="start"
                                                 title="{{ __('filament-lexical-editor::lexical-editor.end_align') }}"
                                                 rtl-title="{{ __('filament-lexical-editor::lexical-editor.start_align') }}" shortcut="Ctrl+Shift+]"
                                                 rtl-shortcut="Ctrl+Shift+[" icon="right-align" rtl-icon="left-align"/>
        @break
    @case(ToolbarItem::INDENT)
        <x-filament-lexical-editor::toolbar-item ref="indent" title="{{ __('filament-lexical-editor::lexical-editor.indent') }}" shortcut="Ctrl+]"
                                                 icon="indent"/>
        @break
    @case(ToolbarItem::OUTDENT)
        <x-filament-lexical-editor::toolbar-item ref="outdent" title="{{ __('filament-lexical-editor::lexical-editor.outdent') }}" shortcut="Ctrl+["
                                                 icon="outdent"/>
        @break
    @case(ToolbarItem::HR)
        <x-filament-lexical-editor::toolbar-item ref="hr" title="{{ __('filament-lexical-editor::lexical-editor.hr') }}" shortcut=""
                                                 icon="horizontal-rule"/>
        @break
    @case(ToolbarItem::IMAGE)
        <x-filament-lexical-editor::toolbar-item ref="image" title="{{ __('filament-lexical-editor::lexical-editor.image') }}" shortcut=""
                                                 icon="image"/>
        @break
    @case(ToolbarItem::DIVIDER)
        <div class="divider"></div>
        @break
@endswitch
