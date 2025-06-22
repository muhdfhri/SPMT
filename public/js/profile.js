
    // Function to close modals
    function closeModal(modalId) {
        document.getElementById(modalId).classList.add('hidden');
    }

    // Function to open modals
    function openModal(modalId) {
        document.getElementById(modalId).classList.remove('hidden');
    }

    // Tab functionality
    document.addEventListener('DOMContentLoaded', function() {
        const tabButtons = document.querySelectorAll('.tab-button');
        const tabContents = document.querySelectorAll('.tab-content');

        // Tambah event listener untuk tombol tambah pendidikan
        const addEducationBtn = document.getElementById('add-education-btn');
        if (addEducationBtn) {
            addEducationBtn.addEventListener('click', function() {
                openModal('modal-education');
            });
        }

        // Tambah event listener untuk tombol tambah pengalaman
        const addExperienceBtn = document.getElementById('add-experience-btn');
        if (addExperienceBtn) {
            addExperienceBtn.addEventListener('click', function() {
                openModal('modal-experience');
            });
        }
        // Tambah event listener untuk tombol tambah keahlian
        const addSkillBtn = document.getElementById('add-skill-btn');
        if (addSkillBtn) {
            addSkillBtn.addEventListener('click', function() {
                openModal('modal-skill');
            });
        }

        // Function to show a tab
        function showTab(tabId) {
            // Hide all tab contents
            tabContents.forEach(content => {
                content.classList.add('hidden');
            });

            // Remove active class from all tab buttons
            tabButtons.forEach(button => {
                button.classList.remove('border-blue-500', 'text-blue-600', 'dark:text-blue-400');
                button.classList.add('border-transparent', 'text-gray-500', 'hover:text-gray-700', 'hover:border-gray-300');
            });

            // Show the selected tab content
            const selectedContent = document.getElementById(tabId);
            if (selectedContent) {
                selectedContent.classList.remove('hidden');
            }

            // Add active class to the clicked tab button
            const activeButton = document.querySelector(`[data-target="${tabId}"]`);
            if (activeButton) {
                activeButton.classList.remove('border-transparent', 'text-gray-500', 'hover:text-gray-700', 'hover:border-gray-300');
                activeButton.classList.add('border-blue-500', 'text-blue-600', 'dark:text-blue-400');
            }
        }

        // Add click event to tab buttons
        tabButtons.forEach(button => {
            button.addEventListener('click', function() {
                const tabId = this.getAttribute('data-target');
                showTab(tabId);
            });
        });

        // Show the first tab by default
        const firstTab = tabButtons[0];
        if (firstTab) {
            const tabId = firstTab.getAttribute('data-target');
            showTab(tabId);
        }

        // Tambahan: buka tab dari query string jika ada
        const urlParams = new URLSearchParams(window.location.search);
        const tab = urlParams.get('tab');
        if (tab) {
            const tabButton = document.querySelector(`[data-target='${tab}-content']`);
            if (tabButton) tabButton.click();
        }
    });

    // Handle Education Form and Modal
    const formEducation = document.getElementById('education-form');
    if (formEducation) {
        // Handle education edit button clicks
        document.querySelectorAll('.edit-button[data-type="education"]').forEach(btn => {
            btn.addEventListener('click', function() {
                const educationData = JSON.parse(this.closest('.education-item').dataset.education);
                
                // Update form action for edit
                formEducation.setAttribute('action', `/mahasiswa/profile/education/${educationData.id}`);
                
                // Add method override for PUT
                let methodInput = formEducation.querySelector('input[name="_method"]');
                if (!methodInput) {
                    methodInput = document.createElement('input');
                    methodInput.type = 'hidden';
                    methodInput.name = '_method';
                    formEducation.appendChild(methodInput);
                }
                methodInput.value = 'PUT';

                // Fill form with education data
                document.getElementById('institution_name').value = educationData.institution_name;
                document.getElementById('degree').value = educationData.degree;
                document.getElementById('field_of_study').value = educationData.field_of_study;
                document.getElementById('start_date').value = educationData.start_date.split(' ')[0];
                document.getElementById('end_date').value = educationData.end_date ? educationData.end_date.split(' ')[0] : '';
                document.getElementById('is_current').checked = educationData.is_current;
                document.getElementById('gpa').value = educationData.gpa || '';

                // Handle end date visibility
                const endDateInput = document.getElementById('end_date');
                if (endDateInput) {
                    if (educationData.is_current) {
                        endDateInput.parentElement.style.display = 'none';
                        endDateInput.required = false;
                    } else {
                        endDateInput.parentElement.style.display = 'block';
                        endDateInput.required = true;
                    }
                }

                // Update modal title and show modal
                const modalTitle = document.querySelector('#education-modal h3');
                if (modalTitle) modalTitle.textContent = 'Edit Pendidikan';
                openModal('education-modal');
            });
        });

        // Handle add education button click
        document.getElementById('add-education-btn')?.addEventListener('click', function() {
            // Reset form
            formEducation.reset();
            formEducation.removeAttribute('action');
            
            // Remove any existing method override input
            const methodInput = formEducation.querySelector('input[name="_method"]');
            if (methodInput) methodInput.remove();
            
            // Reset end date visibility
            const endDateInput = document.getElementById('end_date');
            const isCurrentCheckbox = document.getElementById('is_current');
            if (endDateInput && isCurrentCheckbox) {
                isCurrentCheckbox.checked = false;
                endDateInput.parentElement.style.display = 'block';
                endDateInput.required = true;
            }

            // Update modal title and show modal
            const modalTitle = document.querySelector('#education-modal h3');
            if (modalTitle) modalTitle.textContent = 'Tambah Pendidikan';
            openModal('education-modal');
        });

        // Handle is_current checkbox change
        document.getElementById('is_current')?.addEventListener('change', function() {
            const endDateInput = document.getElementById('end_date');
            if (endDateInput) {
                if (this.checked) {
                    endDateInput.value = '';
                    endDateInput.required = false;
                    endDateInput.parentElement.style.display = 'none';
                } else {
                    endDateInput.required = true;
                    endDateInput.parentElement.style.display = 'block';
                }
            }
        });

        // Handle form submission
        formEducation.addEventListener('submit', function(e) {
            e.preventDefault();
            console.log('Form submitted: form-education');

            const fd = new FormData(formEducation);
            const isUpdate = fd.get('_method') === 'PUT';
            const educationId = isUpdate ? formEducation.getAttribute('action').split('/').pop() : null;

            // Log form data for debugging
            for (let pair of fd.entries()) {
                console.log(pair[0] + ': ' + pair[1]);
            }

            // Ensure checkbox value is properly set
            if (!fd.get('is_current')) fd.set('is_current', '0');
            else fd.set('is_current', '1');

            // Show loading indicator
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalBtnText = submitBtn.innerHTML;
            submitBtn.disabled = true;
            submitBtn.innerHTML = `
                <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Menyimpan...
            `;

            // Convert FormData to JSON object
            const jsonData = {};
            for (let pair of fd.entries()) {
                jsonData[pair[0]] = pair[1];
            }

            // Determine the correct URL based on whether it's an update or create
            const url = isUpdate ? `/mahasiswa/profile/education/${educationId}` : '/mahasiswa/profile/education';

            fetch(url, {
                method: 'POST',
                body: JSON.stringify(jsonData),
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json'
                }
            })
            .then(r => {
                console.log('Response status:', r.status);
                return r.json();
            })
            .then(data => {
                // Log response for debugging
                console.log('Response data:', data);

                // Reset button
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalBtnText;

                if (data.success) {
                    closeModal('education-modal');

                    // Show success message
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: data.message || (isUpdate ? 'Pendidikan berhasil diperbarui' : 'Pendidikan berhasil ditambahkan'),
                        confirmButtonColor: '#3085d6'
                    }).then(() => {
                        // Update completion percentage if available
                        if (data.completion_percentage) {
                            document.getElementById('completion-percentage').textContent = data.completion_percentage + '%';
                            document.getElementById('completion-progress-bar').style.width = data.completion_percentage + '%';
                        }

                        // Reload page to show updated data
                        window.location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: data.message || (isUpdate ? 'Gagal memperbarui pendidikan' : 'Gagal menambah pendidikan'),
                        confirmButtonColor: '#3085d6'
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                // Reset button
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalBtnText;

                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: 'Terjadi kesalahan saat menyimpan data pendidikan',
                    confirmButtonColor: '#3085d6'
                });
            });
        });
    }

 

    // Submit Experience Form
    const formExperience = document.getElementById('form-experience');
    if (formExperience) {
        // Handle experience edit button clicks
        document.querySelectorAll('.edit-button[data-type="experience"]').forEach(btn => {
            btn.addEventListener('click', function() {
                // Update modal title for experience
                document.querySelector('.experience-modal-title').textContent = 'Edit Pengalaman';
            });
        });

        // Handle add experience button click
        document.getElementById('add-experience-btn')?.addEventListener('click', function() {
            // Reset form
            formExperience.reset();
            formExperience.removeAttribute('action');
            // Remove any existing method override input
            const methodInput = formExperience.querySelector('input[name="_method"]');
            if (methodInput) methodInput.remove();
            // Update modal title
            document.querySelector('.experience-modal-title').textContent = 'Tambah Pengalaman';
            // Reset checkbox and show end date field
            const isCurrentCheckbox = document.getElementById('exp-current');
            const endDateInput = document.getElementById('exp_end_date');
            if (isCurrentCheckbox && endDateInput) {
                isCurrentCheckbox.checked = false;
                endDateInput.parentElement.style.display = 'block';
                endDateInput.required = true;
            }
        });

        formExperience.addEventListener('submit', function(e) {
            e.preventDefault();
            const fd = new FormData(formExperience);
            const isUpdate = fd.get('_method') === 'PUT';
            const experienceId = isUpdate ? formExperience.getAttribute('action').split('/').pop() : null;

            if (!fd.get('is_current')) fd.set('is_current', '0');
            else fd.set('is_current', '1');

            // Show loading indicator
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalBtnText = submitBtn.innerHTML;
            submitBtn.disabled = true;
            submitBtn.innerHTML = `
                <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Menyimpan...
            `;

            // Determine the correct URL based on whether it's an update or create
            const url = isUpdate ? `/mahasiswa/profile/experience/${experienceId}` : '/mahasiswa/profile/experience';

            fetch(url, {
                method: 'POST',
                body: fd,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(r => r.json())
            .then(data => {
                // Reset button
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalBtnText;

                if (data.success) {
                    closeModal('modal-experience');

                    // Show success message
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: data.message || (isUpdate ? 'Pengalaman berhasil diperbarui' : 'Pengalaman berhasil ditambahkan'),
                        confirmButtonColor: '#3085d6'
                    }).then((result) => {
                        // Update completion percentage
                        if (data.completion_percentage) {
                            document.getElementById('completion-percentage').textContent = data.completion_percentage + '%';
                            document.getElementById('completion-progress-bar').style.width = data.completion_percentage + '%';
                        }

                        // Reload page to show updated data
                        window.location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: data.message || (isUpdate ? 'Gagal memperbarui pengalaman' : 'Gagal menambah pengalaman'),
                        confirmButtonColor: '#3085d6'
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                // Reset button
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalBtnText;

                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: 'Terjadi kesalahan saat menyimpan data pengalaman',
                    confirmButtonColor: '#3085d6'
                });
            });
        });
    }

    // Submit Skill Form
    const formSkill = document.getElementById('form-skill');
    if (formSkill) {
        formSkill.addEventListener('submit', function(e) {
            e.preventDefault();
            const fd = new FormData(formSkill);

            // Show loading indicator
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalBtnText = submitBtn.innerHTML;
            submitBtn.disabled = true;
            submitBtn.innerHTML = `
                <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Menyimpan...
            `;

            fetch('/mahasiswa/profile/skill', {
                    method: 'POST',
                    body: fd,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(r => r.json())
                .then(data => {
                    // Reset button
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalBtnText;

                    if (data.success) {
                        closeModal('modal-skill');

                        // Show success message
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: data.message || 'Keahlian berhasil ditambahkan',
                            confirmButtonColor: '#3085d6'
                        }).then((result) => {
                            // Update completion percentage
                            if (data.completion_percentage) {
                                document.getElementById('completion-percentage').textContent = data.completion_percentage + '%';
                                document.getElementById('completion-progress-bar').style.width = data.completion_percentage + '%';
                            }

                            // Reload page to show updated data
                            window.location.reload();
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal!',
                            text: data.message || 'Gagal menambah keahlian',
                            confirmButtonColor: '#3085d6'
                        });
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    // Reset button
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalBtnText;

                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: 'Terjadi kesalahan saat menyimpan data keahlian',
                        confirmButtonColor: '#3085d6'
                    });
                });
        });
    }

    // Submit Family Info
    const formFamily = document.getElementById('family-info-form');
    if (formFamily) {
        formFamily.addEventListener('submit', function(e) {
            e.preventDefault();
            const fd = new FormData(formFamily);

            // Show loading indicator
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalBtnText = submitBtn.innerHTML;
            submitBtn.disabled = true;
            submitBtn.innerHTML = `
                <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Menyimpan...
            `;

            fetch('/mahasiswa/profile/update-family-info', {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: fd
                })
                .then(r => r.json())
                .then(data => {
                    // Reset button
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalBtnText;

                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: data.message || 'Data keluarga berhasil disimpan',
                            confirmButtonColor: '#3085d6'
                        });

                        // Update completion percentage
                        if (data.completion_percentage) {
                            document.getElementById('completion-percentage').textContent = data.completion_percentage + '%';
                            document.getElementById('completion-progress-bar').style.width = data.completion_percentage + '%';
                        }

                        // Reload page to show updated data
                        setTimeout(() => {
                            window.location.reload();
                        }, 1500);
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal!',
                            text: data.message || 'Terjadi kesalahan saat menyimpan data keluarga',
                            confirmButtonColor: '#3085d6'
                        });
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    // Reset button
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalBtnText;

                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: 'Terjadi kesalahan saat menyimpan data keluarga',
                        confirmButtonColor: '#3085d6'
                    });
                });
        });
    }
 // Submit Guardian Info
 const formGuardian = document.getElementById('guardian-info-form');
    if (formGuardian) {
        formGuardian.addEventListener('submit', function(e) {
            e.preventDefault();
            const fd = new FormData(formGuardian);

            // Show loading indicator
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalBtnText = submitBtn.innerHTML;
            submitBtn.disabled = true;
            submitBtn.innerHTML = `
                <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Menyimpan...
            `;

            fetch('/mahasiswa/profile/update-family-info', {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: fd
            })
                .then(r => r.json())
                .then(data => {
                    // Reset button
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalBtnText;

                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: data.message || 'Data wali berhasil disimpan',
                            confirmButtonColor: '#3085d6'
                        });

                        // Update completion percentage
                        if (data.completion_percentage) {
                            document.getElementById('completion-percentage').textContent = data.completion_percentage + '%';
                            document.getElementById('completion-progress-bar').style.width = data.completion_percentage + '%';
                        }

                        // Reload page to show updated data
                        setTimeout(() => {
                            window.location.reload();
                        }, 1500);
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal!',
                            text: data.message || 'Terjadi kesalahan saat menyimpan data wali',
                            confirmButtonColor: '#3085d6'
                        });
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    // Reset button
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalBtnText;

                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: 'Terjadi kesalahan saat menyimpan data wali',
                        confirmButtonColor: '#3085d6'
                    });
                });
        });
    }

    // Submit Documents Form
    const documentsForm = document.getElementById('documents-form');
    if (documentsForm) {
        documentsForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const fd = new FormData(documentsForm);

            // Show loading indicator
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalBtnText = submitBtn.innerHTML;
            submitBtn.disabled = true;
            submitBtn.innerHTML = `
                <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Mengunggah...
            `;

            fetch(documentsForm.getAttribute('action'), {
                    method: 'POST',
                    body: fd,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(r => r.json())
                .then(data => {
                    // Reset button
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalBtnText;

                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: data.message || 'Dokumen berhasil diunggah',
                            confirmButtonColor: '#3085d6'
                        });

                        // Update completion percentage
                        if (data.completion_percentage) {
                            document.getElementById('completion-percentage').textContent = data.completion_percentage + '%';
                            document.getElementById('completion-progress-bar').style.width = data.completion_percentage + '%';
                        }

                        // Reload page to show updated documents
                        setTimeout(() => {
                            window.location.reload();
                        }, 1500);
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal!',
                            text: data.message || 'Terjadi kesalahan saat mengunggah dokumen',
                            confirmButtonColor: '#3085d6'
                        });
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    // Reset button
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalBtnText;

                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: 'Terjadi kesalahan saat mengunggah dokumen',
                        confirmButtonColor: '#3085d6'
                    });
                });
        });
    }

    // Tab switching with data refresh
    const tabButtons = document.querySelectorAll('.tab-button');
    if (tabButtons.length > 0) {
        tabButtons.forEach(button => {
            button.addEventListener('click', function() {
                const targetId = this.getAttribute('data-target');

                // Hide all tab contents
                document.querySelectorAll('.tab-content').forEach(content => {
                    content.classList.add('hidden');
                });

                // Remove active class from all tabs
                document.querySelectorAll('.tab-button').forEach(btn => {
                    btn.classList.remove('border-blue-500', 'text-blue-600', 'dark:text-blue-400');
                    btn.classList.add('border-transparent', 'text-gray-500', 'hover:text-gray-700', 'hover:border-gray-300', 'dark:text-gray-400', 'dark:hover:text-gray-300');
                });

                // Show the selected tab content
                const targetContent = document.getElementById(targetId);
                if (targetContent) {
                    targetContent.classList.remove('hidden');
                }

                // Add active class to the clicked tab
                this.classList.remove('border-transparent', 'text-gray-500', 'hover:text-gray-700', 'hover:border-gray-300', 'dark:text-gray-400', 'dark:hover:text-gray-300');
                this.classList.add('border-blue-500', 'text-blue-600', 'dark:text-blue-400');

                // If switching to academic, family, or documents tab, refresh data
                if (targetId === 'academic-content' || targetId === 'family-content' || targetId === 'documents-content') {
                    // You could add an AJAX call here to refresh the specific tab data
                    fetch('/mahasiswa/profile/get-profile-completion-status', {
                            method: 'GET',
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            }
                        })
                        .then(r => r.json())
                        .then(data => {
                            if (data.success) {
                                // Update completion percentage
                                const completionPercentage = document.getElementById('completion-percentage');
                                const progressBar = document.getElementById('completion-progress-bar');

                                if (completionPercentage && progressBar) {
                                    completionPercentage.textContent = data.completion_percentage + '%';
                                    progressBar.style.width = data.completion_percentage + '%';
                                }
                            }
                        })
                        .catch(error => {
                            console.error('Error fetching completion status:', error);
                        });
                }
            });
        });
    }

    // Handle document upload buttons
    document.querySelectorAll('.upload-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const type = this.dataset.type;
            const description = this.dataset.description;
            const input = document.getElementById(type);
            
            if (!input || !input.files.length) {
                Swal.fire({
                    title: 'Peringatan!',
                    text: 'Pilih file terlebih dahulu!',
                    icon: 'warning',
                    confirmButtonText: 'OK'
                });
                return;
            }

            // Validate file type
            const file = input.files[0];
            const allowedTypes = ['application/pdf', 'image/jpeg', 'image/png'];
            const maxSize = 5 * 1024 * 1024; // 5MB
            
            if (!allowedTypes.includes(file.type)) {
                Swal.fire({
                    title: 'Format File Tidak Didukung',
                    text: 'Hanya file PDF, JPG, dan PNG yang diizinkan',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
                return;
            }
            
            if (file.size > maxSize) {
                Swal.fire({
                    title: 'Ukuran File Terlalu Besar',
                    text: 'Ukuran file maksimal 5MB',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
                return;
            }

            // Create FormData and append file
            const fd = new FormData();
            fd.append('type', type);
            fd.append('file', input.files[0]);
            fd.append('description', description);

            // Show loading state
            this.disabled = true;
            const originalText = this.innerHTML;
            this.innerHTML = `
                <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Mengunggah...
            `;

            // AJAX upload file
            fetch('/mahasiswa/profile/document', {
                method: 'POST',
                body: fd,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(async response => {
                const data = await response.json();
                if (!response.ok) {
                    // Handle HTTP error status codes
                    let errorMessage = 'Gagal mengunggah dokumen';
                    if (response.status === 422) {
                        // Handle validation errors
                        errorMessage = 'Kesalahan validasi: ';
                        if (data.errors) {
                            // Handle specific validation errors
                            const errorMessages = [];
                            
                            // Check for specific validation errors
                            if (data.errors.file && data.errors.file[0] === 'validation.uploaded') {
                                errorMessages.push('Gagal mengunggah file. Pastikan file tidak rusak dan coba lagi.');
                            } else if (data.errors.file) {
                                errorMessages.push(data.errors.file[0]);
                            }
                            
                            // Add other validation errors if any
                            Object.entries(data.errors).forEach(([field, messages]) => {
                                if (field !== 'file') {
                                    errorMessages.push(...messages);
                                }
                            });
                            
                            errorMessage += errorMessages.join(' ');
                        } else if (data.message) {
                            errorMessage += data.message;
                        }
                    } else if (data.message) {
                        errorMessage = data.message;
                    }
                    return Promise.reject(new Error(errorMessage));
                }
                return data;
            })
            .then(data => {
                // Reset button
                this.disabled = false;
                this.innerHTML = originalText;

                if (data.success) {
                    // Show success message
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: data.message || 'Dokumen berhasil diunggah',
                        confirmButtonColor: '#3085d6'
                    }).then(() => {
                        // Update completion percentage if available
                        if (data.completion_percentage) {
                            document.getElementById('completion-percentage').textContent = data.completion_percentage + '%';
                            document.getElementById('completion-progress-bar').style.width = data.completion_percentage + '%';
                        }

                        // Update the document display section
                        const documentContainer = this.closest('.mb-6');
                        const existingDisplay = documentContainer.querySelector('.mt-2');
                        
                        if (existingDisplay) {
                            existingDisplay.remove();
                        }

                        // Create new document display
                        const newDisplay = document.createElement('div');
                        newDisplay.className = 'mt-2 flex items-center justify-between';
                        newDisplay.innerHTML = `
                            <a href="/mahasiswa/profile/document/${data.document.id}/download" 
                               class="text-sm text-blue-600 hover:text-blue-800">
                                <i class="fas fa-file-alt mr-1"></i>
                                ${data.document.original_filename}
                            </a>
                            <div class="flex space-x-2">
                                <button class="edit-document-btn p-1.5 text-gray-500 hover:text-blue-600 dark:text-gray-400 dark:hover:text-blue-400" 
                                        data-id="${data.document.id}"
                                        data-type="${data.document.type}"
                                        data-description="${data.document.description}">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                </button>
                                <button class="delete-document-btn p-1.5 text-gray-500 hover:text-red-600 dark:text-gray-400 dark:hover:text-red-400"
                                        data-id="${data.document.id}">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                </button>
                            </div>
                        `;

                        documentContainer.appendChild(newDisplay);

                        // Clear the file input
                        input.value = '';

                        // Reattach event listeners to the new buttons
                        attachDocumentButtonListeners();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: data.message || 'Gagal mengunggah dokumen',
                        confirmButtonColor: '#3085d6'
                    });
                }
            })
            .catch(error => {
                console.error('Upload Error:', error);
                // Reset button
                this.disabled = false;
                this.innerHTML = originalText;

                // Show more detailed error message
                let errorMessage = 'Terjadi kesalahan saat mengunggah dokumen';
                if (error.message) {
                    errorMessage = error.message;
                }

                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: errorMessage,
                    confirmButtonColor: '#3085d6'
                });
            });
        });
    });

    // Function to attach event listeners to document buttons
    function attachDocumentButtonListeners() {
        // Attach edit button listeners
        document.querySelectorAll('.edit-document-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const documentId = this.dataset.id;
                const documentType = this.dataset.type;
                const documentDescription = this.dataset.description;

                // Set form values
                document.getElementById('edit_document_id').value = documentId;
                document.getElementById('edit_document_type').value = documentType;
                document.getElementById('edit_document_description').value = documentDescription;

                // Set form action
                const form = document.getElementById('form-edit-document');
                form.setAttribute('action', `/mahasiswa/profile/document/${documentId}`);

                // Open modal
                openModal('modal-edit-document');
            });
        });

        // Attach delete button listeners
        document.querySelectorAll('.delete-document-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const documentId = this.dataset.id;
                
                Swal.fire({
                    title: 'Konfirmasi',
                    text: 'Apakah Anda yakin ingin menghapus dokumen ini?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Send delete request
                        fetch(`/mahasiswa/profile/document/${documentId}`, {
                            method: 'DELETE',
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            }
                        })
                        .then(r => r.json())
                        .then(data => {
                            if (data.success) {
                                // Remove the document display
                                const documentContainer = this.closest('.mb-6');
                                const display = documentContainer.querySelector('.mt-2');
                                if (display) {
                                    display.remove();
                                }

                                // Update completion percentage if available
                                if (data.completion_percentage) {
                                    document.getElementById('completion-percentage').textContent = data.completion_percentage + '%';
                                    document.getElementById('completion-progress-bar').style.width = data.completion_percentage + '%';
                                }

                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil!',
                                    text: data.message || 'Dokumen berhasil dihapus',
                                    confirmButtonColor: '#3085d6'
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Gagal!',
                                    text: data.message || 'Gagal menghapus dokumen',
                                    confirmButtonColor: '#3085d6'
                                });
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal!',
                                text: 'Terjadi kesalahan saat menghapus dokumen',
                                confirmButtonColor: '#3085d6'
                            });
                        });
                    }
                });
            });
        });
    }

    // Initial attachment of event listeners
    attachDocumentButtonListeners();

    // View document button
    const viewButtons = document.querySelectorAll('.view-file-btn');
    if (viewButtons.length > 0) {
        viewButtons.forEach(btn => {
            btn.addEventListener('click', function() {
                const url = this.dataset.url;
                if (url) {
                    // Open file in new tab
                    window.open(url, '_blank');
                } else {
                    Swal.fire({
                        title: 'Error!',
                        text: 'File tidak ditemukan',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                }
            });
        });
    }

    // Delete document button
    const deleteButtons = document.querySelectorAll('.delete-file-btn');
    if (deleteButtons.length > 0) {
        deleteButtons.forEach(btn => {
            btn.addEventListener('click', function() {
                const documentId = this.dataset.id;
                if (!documentId) {
                    Swal.fire({
                        title: 'Error!',
                        text: 'ID dokumen tidak ditemukan',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                    return;
                }

                // Confirm deletion
                Swal.fire({
                    title: 'Konfirmasi',
                    text: 'Apakah Anda yakin ingin menghapus dokumen ini?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Send delete request
                        fetch(`/mahasiswa/profile/delete-document/${documentId}`, {
                                method: 'DELETE',
                                headers: {
                                    'X-Requested-With': 'XMLHttpRequest',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                                }
                            })
                            .then(r => r.json())
                            .then(data => {
                                if (data.success) {
                                    // Show success message
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Berhasil!',
                                        text: data.message || 'Dokumen berhasil dihapus',
                                        confirmButtonColor: '#3085d6'
                                    }).then(() => {
                                        // Update completion percentage
                                        if (data.completion_percentage) {
                                            document.getElementById('completion-percentage').textContent = data.completion_percentage + '%';
                                            document.getElementById('completion-progress-bar').style.width = data.completion_percentage + '%';
                                        }

                                        // Reload page to update UI
                                        window.location.reload();
                                    });
                                } else {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Gagal!',
                                        text: data.message || 'Terjadi kesalahan saat menghapus dokumen',
                                        confirmButtonColor: '#3085d6'
                                    });
                                }
                            })
                            .catch(error => {
                                console.error('Error:', error);
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Gagal!',
                                    text: 'Terjadi kesalahan saat menghapus dokumen',
                                    confirmButtonColor: '#3085d6'
                                });
                            });
                    }
                });
            });
        });
    }

    // Handle checkbox for current education/experience
    document.querySelectorAll('#edu-current, #exp-current').forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const endDateId = this.id === 'edu-current' ? 'end_date' : 'exp_end_date';
            const endDateField = document.getElementById(endDateId);

            if (endDateField) {
                if (this.checked) {
                    endDateField.disabled = true;
                    endDateField.value = '';
                } else {
                    endDateField.disabled = false;
                }
            }
        });
    });

    // Initialize file input change listeners
    document.querySelectorAll('input[type="file"]').forEach(input => {
        input.addEventListener('change', function() {
            const fileNameDisplay = this.parentElement.querySelector('.file-name');
            if (fileNameDisplay && this.files.length > 0) {
                fileNameDisplay.textContent = this.files[0].name;
            }
        });
    });

    // Handle Edit and Delete buttons
    document.addEventListener('click', function(e) {
        // Handle Edit Button Click
        if (e.target.closest('.edit-button')) {
            const editButton = e.target.closest('.edit-button');
            const itemId = editButton.dataset.id;
            const itemType = editButton.dataset.type;
            const itemElement = editButton.closest(`.${itemType}-item`);

            if (itemType === 'education') {
                const educationData = JSON.parse(itemElement.dataset.education);
                const modal = document.getElementById('modal-education');
                if (!modal) {
                    console.error('Education modal not found');
                    return;
                }
                const form = modal.querySelector('form');
                if (!form) {
                    console.error('Education form not found');
                    return;
                }

                // Set form action and method for update
                form.setAttribute('action', `/mahasiswa/profile/education/${itemId}`);
                form.setAttribute('method', 'POST'); // Set method to POST
                const submitBtn = form.querySelector('button[type="submit"]');
                if (submitBtn) {
                    submitBtn.textContent = 'Update Pendidikan';
                }

                // Add PUT method input for Laravel method spoofing
                let methodInput = form.querySelector('input[name="_method"]');
                if (!methodInput) {
                    methodInput = document.createElement('input');
                    methodInput.setAttribute('type', 'hidden');
                    methodInput.setAttribute('name', '_method');
                    form.appendChild(methodInput);
                }
                methodInput.setAttribute('value', 'PUT');

                // Populate form fields with null checks
                const institutionNameInput = form.querySelector('#institution_name');
                const degreeInput = form.querySelector('#degree');
                const fieldOfStudyInput = form.querySelector('#field_of_study');
                const startDateInput = form.querySelector('#start_date');
                const endDateField = form.querySelector('#end_date');
                const isCurrentCheckbox = form.querySelector('#is_current');
                const descriptionInput = form.querySelector('#description');
                const gpaInput = form.querySelector('#gpa');

                if (institutionNameInput) institutionNameInput.value = educationData.institution_name || '';
                if (degreeInput) degreeInput.value = educationData.degree || '';
                if (fieldOfStudyInput) fieldOfStudyInput.value = educationData.field_of_study || '';
                
                // Format date to YYYY-MM-DD
                if (startDateInput && educationData.start_date) {
                    const startDate = new Date(educationData.start_date);
                    startDateInput.value = startDate.toISOString().split('T')[0];
                }

                if (isCurrentCheckbox) {
                    isCurrentCheckbox.checked = educationData.is_current || false;
                    if (endDateField) {
                        endDateField.disabled = educationData.is_current || false;
                        if (!educationData.is_current && educationData.end_date) {
                            const endDate = new Date(educationData.end_date);
                            endDateField.value = endDate.toISOString().split('T')[0];
                        } else {
                            endDateField.value = '';
                        }
                    }
                }

                if (descriptionInput) descriptionInput.value = educationData.description || '';
                if (gpaInput) gpaInput.value = educationData.gpa || '';

                // Open the modal
                openModal('modal-education');

            } else if (itemType === 'experience') {
                const experienceData = JSON.parse(itemElement.dataset.experience);
                const modal = document.getElementById('modal-experience');
                if (!modal) {
                    console.error('Experience modal not found');
                    return;
                }
                const form = modal.querySelector('form');
                if (!form) {
                    console.error('Experience form not found');
                    return;
                }

                // Set form action and method for update
                form.setAttribute('action', `/mahasiswa/profile/experience/${itemId}`);
                form.setAttribute('method', 'POST'); // Set method to POST
                const submitBtn = form.querySelector('button[type="submit"]');
                if (submitBtn) {
                    submitBtn.textContent = 'Update Pengalaman';
                }

                // Add PUT method input for Laravel method spoofing
                let methodInput = form.querySelector('input[name="_method"]');
                if (!methodInput) {
                    methodInput = document.createElement('input');
                    methodInput.setAttribute('type', 'hidden');
                    methodInput.setAttribute('name', '_method');
                    form.appendChild(methodInput);
                }
                methodInput.setAttribute('value', 'PUT');

                // Populate form fields with null checks
                const companyNameInput = form.querySelector('#company_name');
                const positionInput = form.querySelector('#position');
                const expStartDate = form.querySelector('#exp_start_date');
                const expEndDate = form.querySelector('#exp_end_date');
                const expCurrentCheckbox = form.querySelector('#exp-current');
                const expDescriptionInput = form.querySelector('#exp_description');

                if (companyNameInput) companyNameInput.value = experienceData.company_name || '';
                if (positionInput) positionInput.value = experienceData.position || '';

                // Handle month inputs - format YYYY-MM
                if (expStartDate && experienceData.start_date) {
                    const startDate = new Date(experienceData.start_date);
                    expStartDate.value = startDate.toISOString().slice(0, 7); // Format to YYYY-MM
                }

                if (expCurrentCheckbox) {
                    expCurrentCheckbox.checked = experienceData.is_current || false;
                    if (expEndDate) {
                        expEndDate.disabled = experienceData.is_current || false;
                        if (!experienceData.is_current && experienceData.end_date) {
                            const endDate = new Date(experienceData.end_date);
                            expEndDate.value = endDate.toISOString().slice(0, 7); // Format to YYYY-MM
                        } else {
                            expEndDate.value = '';
                        }
                    }
                }

                if (expDescriptionInput) expDescriptionInput.value = experienceData.description || '';

                // Open the modal
                openModal('modal-experience');
            }
        }

        // Handle Delete Button Click
        if (e.target.closest('.delete-button')) {
            const deleteButton = e.target.closest('.delete-button');
            const itemId = deleteButton.dataset.id;
            const itemType = deleteButton.dataset.type;
            const itemElement = deleteButton.closest(`.${itemType}-item`);

            Swal.fire({
                title: 'Konfirmasi',
                text: `Apakah Anda yakin ingin menghapus ${itemType === 'education' ? 'pendidikan' : (itemType === 'experience' ? 'pengalaman' : (itemType === 'skill' ? 'keahlian' : 'dokumen'))} ini?`, // Dynamic text
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Send DELETE request
                    fetch(`/mahasiswa/profile/${itemType}/${itemId}`, {
                            method: 'DELETE',
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            }
                        })
                        .then(r => r.json())
                        .then(data => {
                            if (data.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil!',
                                    text: data.message || `${itemType} berhasil dihapus`,
                                    confirmButtonColor: '#3085d6'
                                });

                                // Remove the item from the DOM
                                itemElement.remove();

                                // Optionally update completion percentage if needed
                                // fetch('/mahasiswa/profile/get-profile-completion-status').then(r => r.json()).then(data => { ... });

                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Gagal!',
                                    text: data.message || `Gagal menghapus ${itemType}`,
                                    confirmButtonColor: '#3085d6'
                                });
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal!',
                                text: `Terjadi kesalahan saat menghapus ${itemType}`,
                                confirmButtonColor: '#3085d6'
                            });
                        });
                }
            });
        }
    });

    // General form submission handler (to handle both Add and Edit)
    document.querySelectorAll('form[data-ajax="true"]').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            const formId = this.id;
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalBtnText = submitBtn.innerHTML;

            // Show loading indicator
            submitBtn.disabled = true;
            submitBtn.innerHTML = `
                <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Menyimpan...
            `;

            const fd = new FormData(this);
            const method = this.getAttribute('method').toUpperCase(); // Get method from form tag
            let fetchMethod = 'POST'; // Default to POST

            // Use PUT/PATCH if specified, but still send as POST for Laravel method spoofing
            if (method === 'PUT' || method === 'PATCH') {
                // Add _method field for Laravel
                if (!fd.has('_method')) {
                     fd.append('_method', method);
                }
            } else {
                 fetchMethod = method;
            }

            fetch(this.getAttribute('action'), {
                method: fetchMethod, // Use the correct fetch method
                body: fd,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(r => {
                 // Check if the response is JSON. If not, log the raw response.
                 const contentType = r.headers.get("content-type");
                 if (contentType && contentType.indexOf("application/json") !== -1) {
                     return r.json();
                 } else {
                     console.error('Received non-JSON response:', r);
                     // If it's an HTML response (e.g., Laravel error page), try to parse it or show generic error.
                     return r.text().then(text => { 
                         console.error('Response text:', text); 
                         throw new Error('Received non-JSON response from server.');
                     });
                 }
            })
            .then(data => {
                // Reset button
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalBtnText;

                // Handle successful response (check data.success or specific structure)
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: data.message || 'Data berhasil disimpan',
                        confirmButtonColor: '#3085d6'
                    }).then(() => {
                        // Update completion percentage
                        if (data.completion_percentage) {
                            document.getElementById('completion-percentage').textContent = data.completion_percentage + '%';
                            document.getElementById('completion-progress-bar').style.width = data.completion_percentage + '%';
                        }
                        // Reload page or update UI dynamically
                         // Check if the form is the personal info form and if we need to stay on the same tab
                         if (formId === 'personal-info-form') {
                              // Stay on the current tab after reload by adding the hash
                               window.location.reload();
                         } else {
                            window.location.reload(); // Default reload for other forms
                         }
                    });
                    // Close the modal if it's an add/edit form (assuming modals have a specific parent class or ID pattern)
                    const modal = form.closest('.fixed.inset-0.z-50'); // Adjust selector if needed
                    if (modal) closeModal(modal.id);

                } else {
                     // Handle validation errors or other backend errors
                     let errorMessage = data.message || 'Terjadi kesalahan saat menyimpan data';
                     if (data.errors) {
                         errorMessage += '<br><ul>';
                         for (const field in data.errors) {
                             errorMessage += `<li>${data.errors[field][0]}</li>`;
                         }
                         errorMessage += '</ul>';
                     }

                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        html: errorMessage,
                        confirmButtonColor: '#d33' // Use red for error button
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                // Reset button
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalBtnText;

                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: error.message || 'Terjadi kesalahan pada request AJAX.', // Use the error message from catch
                    confirmButtonColor: '#d33' // Use red for error button
                });
            });
        });
    });


    // Handle checkbox for current education/experience
    document.querySelectorAll('#is_current, #exp-current').forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            // Find the closest modal to determine which form it is
            const modal = this.closest('.fixed.inset-0.z-50');
            let endDateFieldId = '';
            if (modal && modal.id === 'modal-education') {
                 endDateFieldId = 'end_date';
            } else if (modal && modal.id === 'modal-experience') {
                 endDateFieldId = 'exp_end_date';
            }

            if (endDateFieldId) {
                const endDateField = modal.querySelector(`#${endDateFieldId}`);
                 if (endDateField) {
                     if (this.checked) {
                         endDateField.disabled = true;
                         endDateField.value = '';
                     } else {
                         endDateField.disabled = false;
                     }
                 }
            }
        });
    });


    // Initialize file input change listeners (already present, just ensure they are here)
    document.querySelectorAll('input[type="file"]').forEach(input => {
        input.addEventListener('change', function() {
            const fileNameDisplay = this.parentElement.querySelector('.file-name');
            if (fileNameDisplay && this.files.length > 0) {
                fileNameDisplay.textContent = this.files[0].name;
            }
        });
    });

    // Document Edit Button Click Handler
    document.querySelectorAll('.edit-document-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const documentId = this.dataset.id;
            const documentType = this.dataset.type;
            const documentDescription = this.dataset.description;

            // Set form values
            document.getElementById('edit_document_id').value = documentId;
            document.getElementById('edit_document_type').value = documentType;
            document.getElementById('edit_document_description').value = documentDescription;

            // Set form action
            const form = document.getElementById('form-edit-document');
            form.setAttribute('action', `/mahasiswa/profile/document/${documentId}`);

            // Open modal
            openModal('modal-edit-document');
        });
    });

    // Document Delete Button Click Handler
    document.querySelectorAll('.delete-document-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const documentId = this.dataset.id;

            Swal.fire({
                title: 'Konfirmasi',
                text: 'Apakah Anda yakin ingin menghapus dokumen ini?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Show loading state
                    Swal.fire({
                        title: 'Menghapus...',
                        text: 'Mohon tunggu sebentar',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    // Send delete request
                    fetch(`/mahasiswa/profile/document/${documentId}`, {
                        method: 'DELETE',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    })
                    .then(r => r.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: data.message || 'Dokumen berhasil dihapus',
                                confirmButtonColor: '#3085d6'
                            }).then(() => {
                                // Update completion percentage if available
                                if (data.completion_percentage) {
                                    document.getElementById('completion-percentage').textContent = data.completion_percentage + '%';
                                    document.getElementById('completion-progress-bar').style.width = data.completion_percentage + '%';
                                }
                                // Reload page to update UI
                                window.location.reload();
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal!',
                                text: data.message || 'Gagal menghapus dokumen',
                                confirmButtonColor: '#3085d6'
                            });
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal!',
                            text: 'Terjadi kesalahan saat menghapus dokumen',
                            confirmButtonColor: '#3085d6'
                        });
                    });
                }
            });
        });
    });

    // Document Edit Form Submit Handler
    const formEditDocument = document.getElementById('form-edit-document');
    if (formEditDocument) {
        formEditDocument.addEventListener('submit', function(e) {
            e.preventDefault();
            const fd = new FormData(this);

            // Show loading state
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalBtnText = submitBtn.innerHTML;
            submitBtn.disabled = true;
            submitBtn.innerHTML = `
                <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Menyimpan...
            `;

            fetch(this.getAttribute('action'), {
                method: 'POST',
                body: fd,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(r => r.json())
            .then(data => {
                // Reset button
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalBtnText;

                if (data.success) {
                    closeModal('modal-edit-document');

                    // Show success message
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: data.message || 'Dokumen berhasil diperbarui',
                        confirmButtonColor: '#3085d6'
                    }).then(() => {
                        // Update completion percentage if available
                        if (data.completion_percentage) {
                            document.getElementById('completion-percentage').textContent = data.completion_percentage + '%';
                            document.getElementById('completion-progress-bar').style.width = data.completion_percentage + '%';
                        }
                        // Reload page to update UI
                        window.location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: data.message || 'Gagal memperbarui dokumen',
                        confirmButtonColor: '#3085d6'
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                // Reset button
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalBtnText;

                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: 'Terjadi kesalahan saat memperbarui dokumen',
                    confirmButtonColor: '#3085d6'
                });
            });
        });
    }

    // ========== FOTO PROFIL UPLOAD =============
    const profilePhotoOverlay = document.getElementById('profile-photo-overlay');
    const realProfilePhotoInput = document.getElementById('real-profile-photo-input');
    const profilePhotoPreview = document.getElementById('profile-photo-preview');

    if (profilePhotoOverlay && realProfilePhotoInput) {
        // Trigger real input when overlay clicked
        profilePhotoOverlay.addEventListener('click', function() {
            realProfilePhotoInput.click();
        });
        // Handle file change
        realProfilePhotoInput.addEventListener('change', function() {
            if (!this.files.length) return;
            const file = this.files[0];
            // Validate type & size
            if (!['image/jpeg','image/png','image/jpg'].includes(file.type)) {
                Swal.fire({icon:'error',title:'Format tidak didukung',text:'Hanya file JPG/JPEG/PNG yang diperbolehkan'}); return;
            }
            if (file.size > 2 * 1024 * 1024) {
                Swal.fire({icon:'error',title:'Ukuran terlalu besar',text:'Ukuran maksimal 2MB'}); return;
            }
            // Upload via AJAX
            const formData = new FormData();
            formData.append('photo', file);
            fetch('/mahasiswa/profile/upload-photo', {
            method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'X-Requested-With': 'XMLHttpRequest',
                },
                body: formData
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    // Update preview
                    if (profilePhotoPreview) profilePhotoPreview.src = data.photo_url;
                    // Update avatar di user menu jika ada
                    const userMenuImg = document.querySelector('#user-menu-button img');
                    if (userMenuImg) userMenuImg.src = data.photo_url;
                    Swal.fire({icon:'success',title:'Berhasil!',text:'Foto profil berhasil diperbarui.'});
                } else {
                    Swal.fire({icon:'error',title:'Gagal!',text: data.message || 'Gagal upload foto profil'});
                }
            })
            .catch(() => {
                Swal.fire({icon:'error',title:'Gagal!',text:'Terjadi kesalahan saat upload foto profil'});
            });
        });
    }

    // ========== AJAX Pagination for Reports Table =============
    document.addEventListener('click', function(e) {
        if (e.target.closest('.ajax-pagination-link')) {
            e.preventDefault();
            const url = e.target.closest('.ajax-pagination-link').href;
            fetch(url, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(r => r.text())
            .then(html => {
                // Ambil isi tabel dari response
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');
                const newTable = doc.querySelector('#reports-table-container');
                if (newTable) {
                    document.querySelector('#reports-table-container').innerHTML = newTable.innerHTML;
                }
                // Scroll ke tabel jika perlu
                document.querySelector('#reports-table-container').scrollIntoView({behavior: 'smooth'});
            });
        }
    });

    // ========== DELETE REPORT BUTTON =============
    document.addEventListener('click', function(e) {
        const btn = e.target.closest('.delete-report-btn');
        if (btn) {
            e.preventDefault();
            const reportId = btn.getAttribute('data-id');
            if (!reportId) return;

            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    title: 'Hapus Laporan?',
                    text: 'Laporan yang dihapus tidak dapat dikembalikan.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        deleteReport(reportId, btn);
                    }
                });
            } else {
                if (confirm('Hapus laporan ini?')) {
                    deleteReport(reportId, btn);
                }
            }
        }
    });

    function deleteReport(reportId, btn) {
        fetch(`/mahasiswa/laporan/${reportId}`, {
            method: 'DELETE',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                // Hapus baris dari tabel
                const row = btn.closest('tr');
                if (row) row.remove();
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: data.message || 'Laporan dihapus.',
                        confirmButtonColor: '#3085d6'
                    });
                }
            } else {
                if (typeof Swal !== 'undefined') {
                    Swal.fire('Gagal!', data.message || 'Gagal menghapus laporan.', 'error');
                } else {
                    alert(data.message || 'Gagal menghapus laporan.');
                }
            }
        })
        .catch(() => {
            if (typeof Swal !== 'undefined') {
                Swal.fire('Gagal!', 'Terjadi kesalahan saat menghapus laporan.', 'error');
            } else {
                alert('Terjadi kesalahan saat menghapus laporan.');
            }
        });
    }

    // Custom file input preview for laporan masalah
    document.addEventListener('DOMContentLoaded', function() {
        const attachmentsInput = document.getElementById('attachments');
        const attachmentsList = document.getElementById('attachments-list');

        if (attachmentsInput && attachmentsList) {
            attachmentsInput.addEventListener('change', function() {
                if (this.files && this.files.length > 0) {
                    let names = [];
                    for (let i = 0; i < this.files.length; i++) {
                        names.push(this.files[i].name);
                    }
                    // Clear previous content and add new file names
                    attachmentsList.innerHTML = ''; // Clear previous content
                    names.forEach(name => {
                         const span = document.createElement('span');
                         span.className = 'inline-block bg-blue-100 dark:bg-blue-900 text-blue-700 dark:text-blue-200 rounded px-2 py-1 mr-1 mb-1';
                         span.textContent = name;
                         attachmentsList.appendChild(span);
                    });

                } else {
                    attachmentsList.innerHTML = '<span class="text-gray-400">Belum ada file dipilih</span>';
                }
            });
        }
    });
