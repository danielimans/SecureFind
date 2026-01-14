document.addEventListener('DOMContentLoaded', () => {

    const status = document.getElementById('itemStatus');
    const badge = document.getElementById('statusBadge');
    const nameLabel = document.getElementById('itemNameLabel');
    const locationLabel = document.getElementById('locationLabel');
    const itemNameInput = document.getElementById('itemName');
    const locationInput = document.getElementById('location');
    const uploadBox = document.getElementById('uploadBox');
    const imageInput = document.getElementById('imageInput');
    const preview = document.getElementById('previewImage');
    const form = document.getElementById('lostFoundForm');

    /* =========================
       LOST / FOUND TOGGLE
    ========================= */
    status.addEventListener('change', () => {
        badge.classList.remove('lost', 'found');

        if (status.value === 'lost') {
            badge.innerHTML = '<i class="fas fa-exclamation-circle"></i> LOST ITEM';
            badge.classList.add('lost');

            nameLabel.innerHTML =
                '<i class="fas fa-box"></i> Lost Item Name <span>*</span>';
            locationLabel.innerHTML =
                '<i class="fas fa-map-marker-alt"></i> Last Seen Location <span>*</span>';
        }

        if (status.value === 'found') {
            badge.innerHTML = '<i class="fas fa-check-circle"></i> FOUND ITEM';
            badge.classList.add('found');

            nameLabel.innerHTML =
                '<i class="fas fa-box"></i> Found Item Name <span>*</span>';
            locationLabel.innerHTML =
                '<i class="fas fa-map-marker-alt"></i> Found Location <span>*</span>';
        }

        if (!status.value) {
            badge.innerHTML = '<i class="fas fa-tag"></i> Select Status';
        }
    });

    /* =========================
       IMAGE UPLOAD & PREVIEW
    ========================= */
    if (uploadBox && imageInput && preview) {

        uploadBox.addEventListener('click', () => {
            imageInput.click();
        });

        document.addEventListener('dragover', e => {
            e.preventDefault();
            e.stopPropagation();
        });

        document.addEventListener('drop', e => {
            e.preventDefault();
            e.stopPropagation();
        });

        uploadBox.addEventListener('dragover', e => {
            e.preventDefault();
            uploadBox.classList.add('dragover');
        });

        uploadBox.addEventListener('dragleave', () => {
            uploadBox.classList.remove('dragover');
        });

        uploadBox.addEventListener('drop', e => {
            e.preventDefault();
            uploadBox.classList.remove('dragover');

            const file = e.dataTransfer.files[0];
            if (!file || !file.type.startsWith('image/')) {
                showToast('Please upload a valid image file', 'error');
                return;
            }

            const dataTransfer = new DataTransfer();
            dataTransfer.items.add(file);
            imageInput.files = dataTransfer.files;
            imageInput.dispatchEvent(new Event('change'));
        });

        imageInput.addEventListener('change', () => {
            const file = imageInput.files[0];
            if (!file || !file.type.startsWith('image/')) return;

            const reader = new FileReader();
            reader.onload = e => {
                preview.src = e.target.result;
                preview.style.display = 'block';
                uploadBox.classList.add('has-image');
            };
            reader.readAsDataURL(file);
        });
    }

    /* =========================
       FORM VALIDATION
    ========================= */
    form.addEventListener('submit', e => {
        let valid = true;

        form.querySelectorAll('[required]').forEach(input => {
            if (!input.value.trim()) {
                input.classList.add('error');
                valid = false;
            } else {
                input.classList.remove('error');
            }
        });

        const desc = form.querySelector('textarea[name="description"]');
        if (desc && desc.value.length < 20) {
            desc.classList.add('error');
            showToast('Description must be at least 20 characters', 'error');
            valid = false;
        }

        if (!valid) {
            e.preventDefault();
            showToast('Please complete all required fields', 'error');
        }
    });

    /* =========================
       TOAST NOTIFICATION
    ========================= */
    function showToast(message, type = 'success') {
        const toast = document.createElement('div');
        toast.className = `toast toast-${type}`;
        toast.innerHTML = `
            <i class="fas fa-${type === 'success'
                ? 'check-circle'
                : 'exclamation-circle'}"></i>
            <span>${message}</span>
        `;
        document.body.appendChild(toast);

        setTimeout(() => toast.classList.add('show'), 10);
        setTimeout(() => {
            toast.classList.remove('show');
            setTimeout(() => toast.remove(), 300);
        }, 3000);
    }

    /* =========================
       SERVER SUCCESS MESSAGE
    ========================= */
    const successMessage = document.querySelector('[data-success-message]');
    if (successMessage) {
        showToast(successMessage.dataset.successMessage, 'success');
    }

    /* =========================
    CLEAR FORM (CANCEL BUTTON)
    ========================= */

    const clearBtn = document.getElementById('clearFormBtn');

    if (clearBtn) {
        clearBtn.addEventListener('click', () => {

            // 1. Reset form fields
            const form = document.getElementById('lostFoundForm');
            form.reset();

            // 2. Reset status badge
            const badge = document.getElementById('statusBadge');
            badge.classList.remove('lost', 'found');
            badge.innerHTML = '<i class="fas fa-tag"></i> Select Status';

            // 3. Clear validation errors
            form.querySelectorAll('.error').forEach(el => {
                el.classList.remove('error');
            });

            // 4. Clear image preview
            const preview = document.getElementById('previewImage');
            const uploadBox = document.getElementById('uploadBox');
            const imageInput = document.getElementById('imageInput');

            if (preview) {
                preview.src = '';
                preview.style.display = 'none';
            }

            if (uploadBox) {
                uploadBox.classList.remove('has-image');
            }

            if (imageInput) {
                imageInput.value = '';
            }

            // 5. Clear location input
            const locationInput = document.getElementById('location');
            if (locationInput) {
                locationInput.value = '';
            }

            // 6. Remove map marker (if exists)
            if (typeof marker !== 'undefined' && marker) {
                map.removeLayer(marker);
                marker = null;
            }

            // Optional feedback
            if (typeof showToast === 'function') {
                showToast('Form cleared', 'success');
            }
        });
    }
});
