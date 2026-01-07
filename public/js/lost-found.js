document.addEventListener('DOMContentLoaded', () => {

    console.log('lost-found.js loaded');

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
    IMAGE PREVIEW
    ========================= */
    if (uploadBox && imageInput && preview) {

        uploadBox.addEventListener('click', () => imageInput.click());

        imageInput.addEventListener('change', () => {
            const file = imageInput.files[0];
            if (!file) return;

            const reader = new FileReader();
            reader.onload = () => {
                preview.src = reader.result;
                preview.style.display = 'block';
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
            const error = input.nextElementSibling;
            if (!input.value.trim()) {
                input.classList.add('error');
                if (error) error.style.display = 'flex';
                valid = false;
            } else {
                input.classList.remove('error');
                if (error) error.style.display = 'none';
            }
        });

        const desc = document.querySelector('textarea[name="description"]');
        if (desc && desc.value.length < 20) {
            desc.classList.add('error');
            valid = false;
        }
    });

    /* =========================
    LOCATION FETCHING
    ========================= */
    useLocationBtn.addEventListener('click', () => {
        if (!navigator.geolocation) {
            locationStatus.innerHTML = '<i class="fas fa-times-circle"></i> Geolocation not supported';
            return;
        }

        locationStatus.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Getting location...';

        navigator.geolocation.getCurrentPosition(
            pos => {
                const lat = pos.coords.latitude.toFixed(5);
                const lng = pos.coords.longitude.toFixed(5);

                const locationText = `Latitude ${lat}, Longitude ${lng}`;

                // Set actual value
                locationInput.value = locationText;

                // Optional but improves UX
                locationInput.placeholder = locationText;

                locationStatus.innerHTML = '<i class="fas fa-check-circle" style="color: #16a34a;"></i> Location added successfully';
            },
            () => {
                locationStatus.innerHTML = '<i class="fas fa-times-circle" style="color: #dc2626;"></i> Location permission denied';
            },
            {
                enableHighAccuracy: true,
                timeout: 10000,
            }
        );
    });
});