<x-filament-panels::page>
   <style>
        /* === PRIMARY COLOR OVERRIDE === */

        /* Tabs active */
        [data-active="true"] .fi-tabs-item-label,
        .fi-tabs-item[aria-selected="true"] .fi-tabs-item-label {
            color: rgb(var(--color-primary-600)) !important;
        }
        .fi-tabs-item[aria-selected="true"] {
            border-color: rgb(var(--color-primary-600)) !important;
        }

        /* Section headers */
        .fi-section-header-heading {
            color: rgb(var(--color-primary-600)) !important;
        }

        /* Code blocks */
        code {
            color: rgb(var(--color-primary-700)) !important;
            background-color: rgb(var(--color-primary-50)) !important;
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 0.85em;
            border: 1px solid rgb(var(--color-primary-200));
        }

        .dark code {
            color: rgb(var(--color-primary-300)) !important;
            background-color: rgb(var(--color-primary-950)) !important;
            border-color: rgb(var(--color-primary-800));
        }

        /* Section border left accent */
        .fi-section {
            border-left: 3px solid rgb(var(--color-primary-400)) !important;
        }

        /* Section collapse button */
        .fi-section-header button svg {
            color: rgb(var(--color-primary-500)) !important;
        }

        /* Page title */
        .fi-header-heading {
            color: rgb(var(--color-primary-700)) !important;
        }

        .dark .fi-header-heading {
            color: rgb(var(--color-primary-400)) !important;
        }

        /* Edit action button */
        .fi-btn-color-primary,
        [data-btn-color="primary"] {
            background-color: rgb(var(--color-primary-600)) !important;
        }

        /* Tab bar underline */
        .fi-tabs {
            border-bottom: 2px solid rgb(var(--color-primary-200)) !important;
        }

        .dark .fi-tabs {
            border-bottom: 2px solid rgb(var(--color-primary-800)) !important;
        }
    </style>
    <x-filament-panels::form wire:submit="save">
        {{ $this->form }}

        <x-filament-panels::form.actions
            :actions="$this->getCachedFormActions()"
            :full-width="$this->hasFullWidthFormActions()"
        />
    </x-filament-panels::form>

    <x-filament-panels::page.unsaved-data-changes-alert />
</x-filament-panels::page>
