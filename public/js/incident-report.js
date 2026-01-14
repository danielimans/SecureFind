document.addEventListener('DOMContentLoaded', () => {

    /* ===============================
       Element References
    =============================== */
    const uploadBox = document.getElementById('uploadBox');
    const evidenceInput = document.getElementById('evidenceInput');
    const filesList = document.getElementById('filesList');

    const incidentTypeSelect = document.getElementById('incidentType');
    const customIncidentTypeGroup = document.getElementById('customIncidentTypeGroup');
    const customIncidentTypeInput = document.getElementById('customIncidentType');

    const form = document.getElementById('incidentForm');
    const cancelBtn = document.getElementById('cancelBtn');

    const dateInput = document.getElementById('incident_date');
    const timeInput = document.getElementById('incident_time');

    const incidentLocationInput = document.getElementById('incidentLocation');
    const incidentLat = document.getElementById('incident_lat');
    const incidentLng = document.getElementById('incident_lng');

    let selectedFiles = new DataTransfer();


    /* ===============================
       Toast System
    =============================== */
    function showToast(message, type = 'success') {
        const toast = document.createElement('div');
        toast.className = `toast toast-${type}`;
        toast.innerHTML = `<i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'}"></i><span>${message}</span>`;
        document.body.appendChild(toast);

        setTimeout(() => toast.classList.add('show'), 10);
        setTimeout(() => {
            toast.classList.remove('show');
            toast.remove();
        }, 3000);
    }


    /* ===============================
       Other → Custom Incident Type
    =============================== */
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
    incidentTypeSelect.addEventListener('change', toggleCustomIncidentType);
    toggleCustomIncidentType();


    /* ===============================
       File Upload
    =============================== */
    uploadBox.addEventListener('click', () => evidenceInput.click());

    evidenceInput.addEventListener('change', e => {
        for (let file of e.target.files) {
            selectedFiles.items.add(file);
        }
        evidenceInput.files = selectedFiles.files;
        renderFiles();
    });

    function renderFiles() {
        filesList.innerHTML = '';
        Array.from(selectedFiles.files).forEach((file, index) => {
            const div = document.createElement('div');
            div.className = 'file-item';
            div.innerHTML = `<strong>${file.name}</strong><button type="button">×</button>`;
            div.querySelector('button').onclick = () => removeFile(index);
            filesList.appendChild(div);
        });
    }

    function removeFile(index) {
        const dt = new DataTransfer();
        Array.from(selectedFiles.files).forEach((file, i) => {
            if (i !== index) dt.items.add(file);
        });
        selectedFiles = dt;
        evidenceInput.files = dt.files;
        renderFiles();
    }


    /* ===============================
       Malaysia Timezone-Correct Date Lock
    =============================== */
    function getMalaysiaToday() {
        const now = new Date();
        const malaysiaOffset = 8 * 60; // UTC+8 in minutes
        const localOffset = now.getTimezoneOffset(); // browser offset
        const malaysiaTime = new Date(now.getTime() + (malaysiaOffset + localOffset) * 60000);
        return malaysiaTime.toISOString().split('T')[0];
    }

    dateInput.setAttribute("max", getMalaysiaToday());


    /* ===============================
       Prevent Future Time (Today Only)
    =============================== */
    timeInput.addEventListener('change', () => {
        if (!dateInput.value) return;

        const selected = new Date(dateInput.value + " " + timeInput.value);
        const now = new Date();

        if (selected > now) {
            showToast("Incident time cannot be in the future", "error");
            timeInput.value = '';
        }
    });


    /* ===============================
       UTHM Campus Map (Locked)
    =============================== */
    const CAMPUS_BOUNDS = [
        [1.8450, 103.0710], // SW
        [1.8700, 103.0930]  // NE
    ];

    const bounds = L.latLngBounds(CAMPUS_BOUNDS);
    let map, marker;

    if (document.getElementById('incidentMap')) {
        map = L.map('incidentMap', {
            maxBounds: bounds,
            maxBoundsViscosity: 1,
            minZoom: 15,
            maxZoom: 19
        }).fitBounds(bounds);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap'
        }).addTo(map);

        map.on('click', e => {
            if (!bounds.contains(e.latlng)) {
                showToast("Incident must be inside UTHM campus", "error");
                return;
            }

            const lat = e.latlng.lat;
            const lng = e.latlng.lng;

            if (marker) map.removeLayer(marker);
            marker = L.marker([lat, lng]).addTo(map);

            incidentLat.value = lat;
            incidentLng.value = lng;

            fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}`)
                .then(r => r.json())
                .then(data => {
                    incidentLocationInput.value =
                        data.display_name || `${lat.toFixed(6)}, ${lng.toFixed(6)}`;
                });
        });
    }


    /* ===============================
       Final Form Validation
    =============================== */
    form.addEventListener('submit', e => {

        if (!incidentLocationInput.value || !incidentLat.value || !incidentLng.value) {
            e.preventDefault();
            showToast("Please select the incident location on the map", "error");
            return;
        }

        if (form.description.value.trim().length < 30) {
            e.preventDefault();
            showToast("Description must be at least 30 characters", "error");
            return;
        }

        if (selectedFiles.files.length === 0) {
            if (!confirm("No evidence uploaded. Submit anyway?")) {
                e.preventDefault();
                return;
            }
        }

        showToast("Submitting incident report…");
    });


    /* ===============================
       Cancel Button
    =============================== */
    cancelBtn.addEventListener('click', () => {
        if (!confirm("Clear all entered data?")) return;

        form.reset();
        filesList.innerHTML = '';
        selectedFiles = new DataTransfer();

        if (marker) {
            map.removeLayer(marker);
            marker = null;
        }
    });

});
