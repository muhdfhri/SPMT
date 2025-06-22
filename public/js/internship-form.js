// Simple form validation and submission
console.log('Internship form script loaded');

document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM fully loaded');
    
    const form = document.getElementById('internshipForm');
    if (!form) {
        console.error('Form not found!');
        return;
    }
    
    console.log('Form found, adding event listeners');
    
    // Set min date for start date
    const today = new Date().toISOString().split('T')[0];
    const startDateInput = document.getElementById('start_date');
    const endDateInput = document.getElementById('end_date');
    
    if (startDateInput) {
        startDateInput.min = today;
        
        startDateInput.addEventListener('change', function() {
            if (endDateInput) {
                endDateInput.min = this.value;
                if (endDateInput.value && new Date(endDateInput.value) < new Date(this.value)) {
                    endDateInput.value = this.value;
                }
            }
        });
    }
    
    // Form submission
    form.addEventListener('submit', function(e) {
        console.log('Form submission started');
        e.preventDefault();
        
        // Basic validation
        const requiredFields = ['title', 'company', 'description', 'requirements', 'location', 'start_date', 'end_date', 'quota'];
        let isValid = true;
        
        requiredFields.forEach(fieldId => {
            const field = document.getElementById(fieldId);
            if (field && !field.value.trim()) {
                alert(`Mohon isi ${fieldId}`);
                field.focus();
                isValid = false;
                return false;
            }
        });
        
        if (!isValid) return;
        
        // Disable submit button
        const submitButton = form.querySelector('button[type="submit"]');
        if (submitButton) {
            submitButton.disabled = true;
            submitButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menyimpan...';
        }
        
        // Submit form
        const formData = new FormData(form);
        
        console.log('Mengirim data ke:', form.action);
        
        fetch(form.action, {
            method: 'POST',
            body: formData,
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
            },
            credentials: 'same-origin'
        })
        .then(async response => {
            console.log('Response status:', response.status);
            const contentType = response.headers.get('content-type');
            
            // Cek jika response adalah redirect
            if (response.redirected) {
                console.log('Redirecting to:', response.url);
                window.location.href = response.url;
                return;
            }
            
            // Cek jika response adalah JSON
            if (contentType && contentType.includes('application/json')) {
                const data = await response.json();
                console.log('Response data:', data);
                
                if (data.redirect) {
                    console.log('Redirecting to (from JSON):', data.redirect);
                    window.location.href = data.redirect;
                } else if (response.ok) {
                    // Jika tidak ada redirect tapi status OK, redirect ke halaman index
                    console.log('No redirect URL, going to index');
                    window.location.href = '/admin/internships';
                }
                return data;
            }
            
            // Jika bukan JSON, coba parsing sebagai text
            const text = await response.text();
            console.log('Response text:', text);
            
            // Jika response adalah HTML, mungkin ada error validasi
            if (contentType && contentType.includes('text/html')) {
                // Coba cari pesan error di response
                const parser = new DOMParser();
                const doc = parser.parseFromString(text, 'text/html');
                const errorElements = doc.querySelectorAll('.error-message');
                
                if (errorElements.length > 0) {
                    const errors = Array.from(errorElements).map(el => el.textContent.trim());
                    console.error('Validation errors:', errors);
                    alert('Terjadi kesalahan validasi: ' + errors.join('\n'));
                } else {
                    // Jika tidak ada pesan error yang ditemukan
                    console.log('No validation errors found in response');
                    window.location.href = '/admin/internships';
                }
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan. Silakan coba lagi. Error: ' + error.message);
        })
        .finally(() => {
            // Selalu aktifkan kembali tombol submit
            if (submitButton) {
                submitButton.disabled = false;
                const buttonText = submitButton.querySelector('#submitButtonText');
                if (buttonText) {
                    buttonText.textContent = 'Simpan Lowongan';
                }
            }
        });
    });
});
