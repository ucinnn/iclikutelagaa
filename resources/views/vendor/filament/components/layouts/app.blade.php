<script>
    document.addEventListener('livewire:init', () => {
        Livewire.on('toggle-fullscreen', (statePath) => {
            const editor = document.querySelector(`[wire\\:model="${statePath}"]`);
            if (editor) {
                editor.closest('.fi-fo-field').classList.toggle('fixed');
                editor.closest('.fi-fo-field').classList.toggle('inset-0');
                editor.closest('.fi-fo-field').classList.toggle('z-50');
                editor.closest('.fi-fo-field').classList.toggle('bg-white');
                editor.closest('.fi-fo-field').classList.toggle('p-8');
                editor.closest('.fi-fo-field').classList.toggle('overflow-y-auto');
            }
        });
    });
</script>
