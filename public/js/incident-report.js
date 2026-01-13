document.addEventListener('DOMContentLoaded', () => {

    const uploadBox = document.getElementById('uploadBox');
    const evidenceInput = document.getElementById('evidenceInput');
    const filesList = document.getElementById('filesList');
    const incidentTypeSelect = document.getElementById('incidentType');
    const customIncidentTypeGroup = document.getElementById('customIncidentTypeGroup');
    const customIncidentTypeInput = document.getElementById('customIncidentType');
    const form = document.getElementById('incidentForm');
    const cancelBtn = document.getElementById('cancelBtn');
    const locationBtn = document.querySelector('.btn-secondary');

    let selectedFiles = new DataTransfer();

    /* ============================
        Toast Notifications
    ============================ */
    function showToast(message, type = 'success') {
        const toast = document.createElement('div');
        toast.className = `toast toast-${type}`;
        const icon = type === 'success' ? 'check-circle' : 'exclamation-circle';
        toast.innerHTML = `<i class="fas fa-${icon}"></i><span>${message}</span>`;
        document.body.appendChild(toast);

        setTimeout(() => toast.classList.add('show'), 10);
        setTimeout(() => {
            toast.classList.remove('show');
            setTimeout(() => toast.remove(), 300);
        }, 3000);
    }

    /* ============================
        Other → Custom Type Toggle
    ============================ */
    function toggleCustomIncidentType() {
        if (incidentTypeSelect.value === 'Other') {
            customIncidentTypeGroup.style.display = 'flex';
            customIncidentTypeInput.required = true;
        } else {
            customIncidentTypeGroup.style.display = 'none';
            customIncidentTypeInput.required = false;
            customIncidentTypeInput.value = '';
        }
    }

    incidentTypeSelect?.addEventListener('change', toggleCustomIncidentType);
    toggleCustomIncidentType();

    /* ============================
        Upload Box
    ============================ */
    uploadBox?.addEventListener('click', () => evidenceInput.click());

    evidenceInput?.addEventListener('change', e => addFiles(e.target.files));

    uploadBox?.addEventListener('dragover', e => {
        e.preventDefault();
        uploadBox.classList.add('dragover');
    });

    uploadBox?.addEventListener('dragleave', () => uploadBox.classList.remove('dragover'));

    uploadBox?.addEventListener('drop', e => {
        e.preventDefault();
        uploadBox.classList.remove('dragover');
        addFiles(e.dataTransfer.files);
    });

    function addFiles(files) {
        for (let file of files) {
            if (Array.from(selectedFiles.items).some(f => f.getAsFile().name === file.name)) continue;
            selectedFiles.items.add(file);
        }
        evidenceInput.files = selectedFiles.files;
        displayFiles();
    }

    function displayFiles() {
        filesList.innerHTML = '';

        Array.from(selectedFiles.files).forEach((file, index) => {
            const fileItem = document.createElement('div');
            fileItem.className = 'file-item';
            fileItem.innerHTML = `
                <div class="file-info">
                    <i class="fas fa-file"></i>
                    <div>
                        <strong>${file.name}</strong>
                        <small>${(file.size / 1024 / 1024).toFixed(2)} MB</small>
                    </div>
                </div>
                <button type="button" class="file-remove" data-index="${index}">
                    <i class="fas fa-times"></i>
                </button>
            `;
            filesList.appendChild(fileItem);
        });

        document.querySelectorAll('.file-remove').forEach(btn => {
            btn.addEventListener('click', () => {
                const index = parseInt(btn.dataset.index);
                const newTransfer = new DataTransfer();
                Array.from(selectedFiles.files).forEach((file, i) => {
                    if (i !== index) newTransfer.items.add(file);
                });
                selectedFiles = newTransfer;
                evidenceInput.files = selectedFiles.files;
                displayFiles();
            });
        });

        uploadBox.classList.toggle('has-files', selectedFiles.files.length > 0);
    }

    /* ============================
        GPS Location Detection
    ============================ */
    function detectLocation() {
        if (!navigator.geolocation) {
            showToast('Geolocation is not supported by your browser', 'error');
            return;
        }

        locationBtn.classList.add('loading');
        locationBtn.disabled = true;
        locationBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Detecting...';

        navigator.geolocation.getCurrentPosition(
            (position) => {
                const lat = position.coords.latitude;
                const lng = position.coords.longitude;
                const accuracy = position.coords.accuracy;

                // Store raw GPS coordinates
                document.getElementById('lat').value = lat;
                document.getElementById('lng').value = lng;

                // Convert to readable address using reverse geocoding
                fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}`)
                    .then(r => r.json())
                    .then(data => {
                        const locationInput = document.querySelector('[name="location"]');
                        locationInput.value = data.display_name || `${lat.toFixed(6)}, ${lng.toFixed(6)}`;
                        showToast(`Location detected (±${Math.round(accuracy)}m)`, 'success');
                        resetLocationButton();
                    })
                    .catch(error => {
                        console.error('Geocoding error:', error);
                        const locationInput = document.querySelector('[name="location"]');
                        locationInput.value = `${lat.toFixed(6)}, ${lng.toFixed(6)}`;
                        showToast('Location detected (coordinates only)', 'success');
                        resetLocationButton();
                    });
            },
            (error) => {
                let errorMessage = 'Failed to get location';
                
                switch(error.code) {
                    case error.PERMISSION_DENIED:
                        errorMessage = 'Location permission denied. Please enable it in settings.';
                        break;
                    case error.POSITION_UNAVAILABLE:
                        errorMessage = 'Location information is unavailable.';
                        break;
                    case error.TIMEOUT:
                        errorMessage = 'Location request timed out.';
                        break;
                }

                showToast(errorMessage, 'error');
                resetLocationButton();
            },
            {
                enableHighAccuracy: true,
                timeout: 10000,
                maximumAge: 0
            }
        );
    }

    function resetLocationButton() {
        locationBtn.classList.remove('loading');
        locationBtn.disabled = false;
        locationBtn.innerHTML = '<i class="fas fa-map-marker-alt"></i> Use My Location';
    }

    // Attach detectLocation to button
    if (locationBtn) {
        locationBtn.addEventListener('click', (e) => {
            e.preventDefault();
            detectLocation();
        });
    }

    /* ============================
        Form Submit Toast
    ============================ */
    form?.addEventListener('submit', e => {
        if (!form.checkValidity()) {
            e.preventDefault();
            showToast('Please fill in all required fields', 'error');
        } else {
            showToast('Submitting your report...', 'success');
        }
    });

    /* ============================
        Cancel Button – Clear Form
    ============================ */
    cancelBtn?.addEventListener('click', () => {

        const hasData =
            incidentTypeSelect.value ||
            customIncidentTypeInput.value ||
            form.location.value ||
            form.incident_date.value ||
            form.incident_time.value ||
            form.description.value ||
            selectedFiles.files.length > 0;

        if (hasData && !confirm('Clear all entered data?')) return;

        // Reset form
        form.reset();

        // Reset custom type
        toggleCustomIncidentType();

        // Clear files
        selectedFiles = new DataTransfer();
        evidenceInput.value = '';
        filesList.innerHTML = '';
        uploadBox.classList.remove('has-files');
    });
});