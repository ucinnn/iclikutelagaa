@php
    use Filament\Support\Facades\FilamentAsset;
    $statePath = $getStatePath();
@endphp

<x-dynamic-component
    :component="$getFieldWrapperView()"
    :field="$field"
>
    <div
        x-data="{
            fullscreen: false,
            async toggleFullscreen() {
                const editorContainer = this.$refs.fullscreenContainer

                if (!this.fullscreen) {
                    if (editorContainer.requestFullscreen) {
                        await editorContainer.requestFullscreen()
                    } else if (editorContainer.webkitRequestFullscreen) { // Safari
                        await editorContainer.webkitRequestFullscreen()
                    } else if (editorContainer.msRequestFullscreen) { // IE11
                        await editorContainer.msRequestFullscreen()
                    }
                    this.fullscreen = true
                } else {
                    if (document.exitFullscreen) {
                        await document.exitFullscreen()
                    } else if (document.webkitExitFullscreen) { // Safari
                        await document.webkitExitFullscreen()
                    } else if (document.msExitFullscreen) { // IE11
                        await document.msExitFullscreen()
                    }
                    this.fullscreen = false
                }
            },
            handleFullscreenChange() {
                this.fullscreen = !!document.fullscreenElement
            }
        }"
        x-init="document.addEventListener('fullscreenchange', handleFullscreenChange)"
        x-ref="fullscreenContainer"
        @class([
            'lexical-editor rounded-md relative text-gray-950 bg-white shadow-sm ring-1 dark:bg-white/5 dark:text-white transition-all duration-300',
            'ring-gray-950/10 dark:ring-white/20' => ! $errors->has($statePath),
            'ring-danger-600 dark:ring-danger-600' => $errors->has($statePath),
        ])
        style="width: 100%;"
    >

        <div
            ax-load="visible"
            ax-load-src="{{ FilamentAsset::getAlpineComponentSrc('lexical-component', 'malzariey/filament-lexical-editor') }}"
            x-data="lexicalComponent({
                state: $wire.{{ $applyStateBindingModifiers("\$entangle('{$statePath}')") }},
                enabledToolbars: @js($getEnabledToolbars())
            })"
            x-ignore
            wire:ignore
            class="editor-shell w-full h-full flex flex-col"
        >
            {{-- Toolbar --}}
            <div class="toolbar flex-wrap flex items-center justify-between gap-2 p-2 border-b dark:border-white/10 bg-gray-50 dark:bg-gray-800">
                <div class="flex flex-wrap gap-1">
                    @foreach($getEnabledToolbars() as $toolbar)
                        <x-filament-lexical-editor::toolbar :toolbar="$toolbar" />
                    @endforeach
                </div>

                {{-- Tombol Fullscreen --}}
                <button
                    type="button"
                    @click="toggleFullscreen"
                    class="ml-auto px-3 py-1 text-xs rounded-md border bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 flex items-center gap-1"
                >
                    <svg x-show="!fullscreen" xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 8V4h4M16 4h4v4M20 16v4h-4M8 20H4v-4" />
                    </svg>
                    <svg x-show="fullscreen" xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 8h-4v4M16 8h4v4M4 16h4v4M20 16h-4v4" />
                    </svg>
                    <span x-text="fullscreen ? 'Exit Fullscreen' : 'Fullscreen'"></span>
                </button>
            </div>

            {{-- Editor --}}
            <div class="editor-container tree-view p-2 flex-1 overflow-auto bg-white dark:bg-gray-900">
                <div class="editor-scroller h-full">
                    <div x-ref="editor"
                         @link-clicked="showLinkEditorDialog($event.detail.target,$event.detail.url,false)"
                         @link-created="showLinkEditorDialog($event.detail.target, $event.detail.url)"
                         @close-link-editor-dialog="closeLinkEditorDialog()"
                         class="editor h-full"
                         style="max-width: unset"
                         contenteditable="true"
                         role="textbox"
                         spellcheck="true"
                         aria-placeholder="Enter some rich text..."
                         data-lexical-editor="true">
                    </div>
                </div>
            </div>

            <x-filament-lexical-editor::dialogs/>
        </div>
    </div>
</x-dynamic-component>
