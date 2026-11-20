import './dashboard-charts.js';
import './pembayaran-form.js';
import './penyaluran-form.js';
import './kalkulator-zakat.js';

document.addEventListener('DOMContentLoaded', () => {
    // 1. Toggle tampilkan / sembunyikan password
    document.querySelectorAll('[data-toggle-password]').forEach((btn) => {
        btn.addEventListener('click', () => {
            const targetId = btn.getAttribute('data-toggle-password');
            const input = document.getElementById(targetId);
            if (!input) return;

            const isPassword = input.type === 'password';
            input.type = isPassword ? 'text' : 'password';

            const showIcon = btn.querySelector('[data-icon-show]');
            const hideIcon = btn.querySelector('[data-icon-hide]');
            if (showIcon && hideIcon) {
                showIcon.classList.toggle('hidden', isPassword);
                hideIcon.classList.toggle('hidden', !isPassword);
            }
        });
    });

    // 2. Loading state otomatis pada tombol submit form
    document.querySelectorAll('form[data-loading-form]').forEach((form) => {
        form.addEventListener('submit', () => {
            const submitBtn = form.querySelector('button[type="submit"]');
            if (!submitBtn || submitBtn.disabled) return;

            submitBtn.disabled = true;
            submitBtn.classList.add('opacity-75', 'cursor-not-allowed');
            const label = submitBtn.querySelector('[data-submit-label]');
            const spinner = submitBtn.querySelector('[data-submit-spinner]');
            if (label && submitBtn.dataset.loadingText) {
                label.textContent = submitBtn.dataset.loadingText;
            }
            if (spinner) {
                spinner.classList.remove('hidden');
            }
        });
    });

    // 3. Sidebar Mobile Drawer Toggle
    const sidebar = document.getElementById('app-sidebar');
    const backdrop = document.getElementById('sidebar-backdrop');
    const openBtn = document.getElementById('sidebar-open-btn');
    const closeBtn = document.getElementById('sidebar-close-btn');

    const toggleSidebar = (open) => {
        if (!sidebar || !backdrop) return;
        sidebar.classList.toggle('-translate-x-full', !open);
        backdrop.classList.toggle('hidden', !open);
        document.body.classList.toggle('overflow-hidden', open);
    };

    if (openBtn) openBtn.addEventListener('click', () => toggleSidebar(true));
    if (closeBtn) closeBtn.addEventListener('click', () => toggleSidebar(false));
    if (backdrop) backdrop.addEventListener('click', () => toggleSidebar(false));

    // 4. Auto-dismiss alert notifikasi
    document.querySelectorAll('[data-dismiss-alert]').forEach((btn) => {
        btn.addEventListener('click', () => {
            const alertBox = btn.closest('[data-alert-box]');
            if (alertBox) alertBox.remove();
        });
    });

    // 5. Modal Konfirmasi Hapus Global
    const confirmModal = document.getElementById('global-confirm-modal');
    const confirmForm = document.getElementById('global-confirm-form');
    const confirmTitle = document.getElementById('global-confirm-title');
    const confirmMessage = document.getElementById('global-confirm-message');

    const closeConfirmModal = () => {
        if (!confirmModal) return;
        confirmModal.classList.add('hidden');
        confirmModal.classList.remove('flex');
    };

    document.querySelectorAll('[data-confirm-delete]').forEach((trigger) => {
        trigger.addEventListener('click', (e) => {
            e.preventDefault();
            if (!confirmModal || !confirmForm) return;

            confirmForm.action = trigger.getAttribute('data-action') || '#';
            if (confirmTitle) {
                confirmTitle.textContent = trigger.getAttribute('data-title') || 'Konfirmasi Hapus Data';
            }
            if (confirmMessage) {
                confirmMessage.textContent = trigger.getAttribute('data-message') || 'Apakah Anda yakin ingin menghapus data ini? Tindakan ini tidak dapat dibatalkan.';
            }

            confirmModal.classList.remove('hidden');
            confirmModal.classList.add('flex');
        });
    });

    document.querySelectorAll('[data-close-confirm]').forEach((btn) => {
        btn.addEventListener('click', closeConfirmModal);
    });

    // 6. Modal Umum (data-modal-open / data-modal-close)
    document.querySelectorAll('[data-modal-open]').forEach((btn) => {
        btn.addEventListener('click', () => {
            const targetId = btn.getAttribute('data-modal-open');
            const modal = document.getElementById(targetId);
            if (!modal) return;
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        });
    });

    document.querySelectorAll('[data-modal-close]').forEach((btn) => {
        btn.addEventListener('click', () => {
            const modal = btn.closest('[data-modal-root]');
            if (!modal) return;
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        });
    });
});
