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
    const useLocationBtn = document.getElementById('useLocation');
    const locationStatus = document.getElementById('locationStatus');

    /* =========================
    LOST / FOUND TOGGLE LOGIC
    ========================= */
    status.addEventListener('change', () => {
        badge.classList.remove('lost', 'found');

        if (status.value === 'lost') {
            badge.innerHTML = '<i class="fas fa-exclamation-circle"></i> LOST ITEM';
            badge.classList.add('lost');

            nameLabel.innerHTML = '<i class="fas fa-box"></i> Lost Item Name <span>*</span>';
            locationLabel.innerHTML = '<i class="fas fa-map-marker-alt"></i> Last Seen Location <span>*</span>';

            itemNameInput.placeholder = 'e.g. Black Backpack, iPhone';
            locationInput.placeholder = 'Where the item was last seen';
        }

        if (status.value === 'found') {
            badge.innerHTML = '<i class="fas fa-check-circle"></i> FOUND ITEM';
            badge.classList.add('found');

            nameLabel.innerHTML = '<i class="fas fa-box"></i> Found Item Name <span>*</span>';
            locationLabel.innerHTML = '<i class="fas fa-map-marker-alt"></i> Found Location <span>*</span>';

            itemNameInput.placeholder = 'e.g. Black Backpack, iPhone';
            locationInput.placeholder = 'Where the item was found';
        }

        if (!status.value) {
            badge.innerHTML = '<i class="fas fa-tag"></i> Select Status';
            locationInput.placeholder = '';
        }
    });

    /* =========================
    IMAGE UPLOAD & PREVIEW
    ========================= */
    if (uploadBox && imageInput && preview) {

        // Click to upload
        uploadBox.addEventListener('click', () => {
            imageInput.click();
        });

        // Prevent default drag behaviors
        document.addEventListener('dragover', (e) => {
            e.preventDefault();
            e.stopPropagation();
        });

        document.addEventListener('drop', (e) => {
            e.preventDefault();
            e.stopPropagation();
        });

        // Drag over upload box
        uploadBox.addEventListener('dragover', (e) => {
            e.preventDefault();
            e.stopPropagation();
            uploadBox.classList.add('dragover');
        });

        uploadBox.addEventListener('dragenter', (e) => {
            e.preventDefault();
            e.stopPropagation();
            uploadBox.classList.add('dragover');
        });

        uploadBox.addEventListener('dragleave', (e) => {
            e.preventDefault();
            e.stopPropagation();
            
            // Only remove if leaving upload box itself
            if (e.target === uploadBox) {
                uploadBox.classList.remove('dragover');
            }
        });

        // Drop files
        uploadBox.addEventListener('drop', (e) => {
            e.preventDefault();
            e.stopPropagation();
            uploadBox.classList.remove('dragover');
            
            const files = e.dataTransfer.files;
            console.log('Files dropped:', files.length);
            
            if (files && files.length > 0) {
                const file = files[0];
                console.log('File type:', file.type);
                
                if (file.type.startsWith('image/')) {
                    // Create a new DataTransfer object and add the file
                    const dataTransfer = new DataTransfer();
                    dataTransfer.items.add(file);
                    
                    // Set the files to input
                    imageInput.files = dataTransfer.files;
                    
                    console.log('File assigned, files count:', imageInput.files.length);
                    
                    // Trigger change event manually
                    imageInput.dispatchEvent(new Event('change', { bubbles: true }));
                    
                    showToast('Image selected successfully', 'success');
                } else {
                    showToast('Please upload a valid image file', 'error');
                }
            }
        });

        // File input change
        imageInput.addEventListener('change', displayImage);

        function displayImage() {
            const file = imageInput.files[0];
            console.log('Displaying image, file:', file);
            
            if (!file) {
                console.log('No file selected');
                return;
            }

            if (!file.type.startsWith('image/')) {
                showToast('Please upload a valid image file', 'error');
                return;
            }

            const reader = new FileReader();
            reader.onload = (e) => {
                console.log('File read successfully');
                preview.src = e.target.result;
                preview.style.display = 'block';
                uploadBox.classList.add('has-image');
                uploadBox.style.cursor = 'pointer';
            };
            reader.onerror = (e) => {
                console.error('File read error:', e);
                showToast('Failed to read file', 'error');
            };
            reader.readAsDataURL(file);
        }
    }

    /* =========================
    FORM VALIDATION & SUBMISSION
    ========================= */
    form.addEventListener('submit', (e) => {
        let valid = true;

        form.querySelectorAll('[required]').forEach(input => {
            const error = input.nextElementSibling;
            if (!input.value.trim()) {
                input.classList.add('error');
                if (error && error.classList.contains('error-text')) {
                    error.style.display = 'flex';
                }
                valid = false;
            } else {
                input.classList.remove('error');
                if (error && error.classList.contains('error-text')) {
                    error.style.display = 'none';
                }
            }
        });

        const desc = document.querySelector('textarea[name="description"]');
        if (desc && desc.value.length < 20) {
            desc.classList.add('error');
            showToast('Description must be at least 20 characters', 'error');
            valid = false;
        }

        if (!valid) {
            e.preventDefault();
            showToast('Please fill in all required fields', 'error');
        }
        // If valid, allow form to submit naturally to the server
        // The server will handle the redirect and flash the success message
    });

    /* =========================
    LOCATION FETCHING
    ========================= */
    useLocationBtn.addEventListener('click', () => {
        if (!navigator.geolocation) {
            locationStatus.innerHTML = '<i class="fas fa-times-circle"></i> Geolocation not supported';
            showToast('Geolocation is not supported on your device', 'error');
            return;
        }

        locationStatus.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Getting location...';

        navigator.geolocation.getCurrentPosition(
            (pos) => {
                const lat = pos.coords.latitude.toFixed(5);
                const lng = pos.coords.longitude.toFixed(5);

                const locationText = `Latitude ${lat}, Longitude ${lng}`;

                locationInput.value = locationText;
                locationInput.placeholder = locationText;

                locationStatus.innerHTML = '<i class="fas fa-check-circle" style="color: #16a34a;"></i> Location added successfully';
                showToast('Location added successfully', 'success');
            },
            () => {
                locationStatus.innerHTML = '<i class="fas fa-times-circle" style="color: #dc2626;"></i> Location permission denied';
                showToast('Location permission denied', 'error');
            },
            {
                enableHighAccuracy: true,
                timeout: 10000,
            }
        );
    });

    /* =========================
    TOAST NOTIFICATION FUNCTION
    ========================= */
    function showToast(message, type = 'success') {
        const toast = document.createElement('div');
        toast.className = `toast toast-${type}`;
        toast.innerHTML = `
            <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'}"></i>
            <span>${message}</span>
        `;
        document.body.appendChild(toast);

        setTimeout(() => toast.classList.add('show'), 10);
        setTimeout(() => {
            toast.classList.remove('show');
            setTimeout(() => toast.remove(), 300);
        }, 3000);
    }

    // Show success toast from server redirect
    const successMessage = document.querySelector('[data-success-message]');
    if (successMessage) {
        showToast(successMessage.dataset.successMessage, 'success');
    }
});