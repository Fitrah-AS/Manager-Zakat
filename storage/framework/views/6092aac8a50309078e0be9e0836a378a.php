<div id="global-confirm-modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/50 p-4">
    <div class="bg-white rounded-2xl max-w-md w-full shadow-2xl border border-slate-200 overflow-hidden">
        <div class="p-6">
            <div class="flex items-start gap-3">
                <div class="w-10 h-10 rounded-full bg-red-100 text-red-600 flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                    </svg>
                </div>
                <div>
                    <h3 id="global-confirm-title" class="text-base font-bold text-slate-900">Konfirmasi Hapus Data</h3>
                    <p id="global-confirm-message" class="mt-1.5 text-sm text-slate-600">
                        Apakah Anda yakin ingin menghapus data ini? Tindakan ini tidak dapat dibatalkan.
                    </p>
                </div>
            </div>
        </div>
        <div class="bg-slate-50 px-6 py-3.5 flex items-center justify-end gap-2.5 border-t border-slate-200">
            <button type="button" data-close-confirm class="rounded-lg border border-slate-300 bg-white hover:bg-slate-100 px-4 py-2 text-xs font-semibold text-slate-700 cursor-pointer">
                Batal
            </button>
            <form id="global-confirm-form" method="POST" action="#">
                <?php echo csrf_field(); ?>
                <?php echo method_field('DELETE'); ?>
                <button type="submit" class="rounded-lg bg-red-600 hover:bg-red-700 px-4 py-2 text-xs font-semibold text-white cursor-pointer">
                    Ya, Hapus
                </button>
            </form>
        </div>
    </div>
</div>
<?php /**PATH C:\laragon\www\zakat-app\resources\views/components/modal-confirm.blade.php ENDPATH**/ ?>