<!-- Logout Confirmation Modal -->
 <!-- Logout Confirmation Modal -->
 <div id="logout-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white dark:bg-gray-800 rounded-lg p-8 max-w-sm w-full mx-4 transform transition-all duration-300 scale-95 opacity-0" id="modal-content">
            <div class="text-center">
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100 dark:bg-red-900 mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-red-600 dark:text-red-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">Konfirmasi Keluar</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400 mb-6">
                    Apakah Anda yakin ingin keluar dari sistem? Anda perlu login kembali untuk mengakses sistem.
                </p>
                <div class="flex justify-center space-x-4">
                    <button onclick="hideLogoutConfirmation()"
                        class="px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors duration-200">
                        Batal
                    </button>
                    <button onclick="confirmLogout()"
                        class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors duration-200">
                        Ya, Keluar
                    </button>
                </div>
            </div>
        </div>
    </div>


@push('scripts')
<script>
// Logout Modal Functions
function showLogoutConfirmation(event) {
    event.preventDefault();
    const modal = document.getElementById('logout-modal');
    const modalContent = document.getElementById('modal-content');

    if (modal && modalContent) {
        modal.classList.remove('hidden');
        modal.classList.add('flex');

        // Trigger animation
        setTimeout(() => {
            modalContent.classList.remove('scale-95', 'opacity-0');
            modalContent.classList.add('scale-100', 'opacity-100');
        }, 10);
    }
}

function hideLogoutConfirmation() {
    const modal = document.getElementById('logout-modal');
    const modalContent = document.getElementById('modal-content');

    if (modal && modalContent) {
        modalContent.classList.remove('scale-100', 'opacity-100');
        modalContent.classList.add('scale-95', 'opacity-0');

        // Wait for animation to finish before hiding
        setTimeout(() => {
            modal.classList.remove('flex');
            modal.classList.add('hidden');
        }, 300);
    }
}

// Close modal when clicking outside
window.addEventListener('click', function(event) {
    const modal = document.getElementById('logout-modal');
    const modalContent = document.getElementById('modal-content');
    
    if (event.target === modal) {
        hideLogoutConfirmation();
    }
});
</script>
@endpush
