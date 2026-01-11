document.addEventListener('DOMContentLoaded', () => {
    const uploadBox = document.getElementById('uploadBox');
    const evidenceInput = document.getElementById('evidenceInput');
    const filesList = document.getElementById('filesList');
    let selectedFiles = new DataTransfer();

    // Toast notification function
    function showToast(message, type = 'success') {
        const toast = document.createElement('div');
        toast.className = `toast toast-${type}`;
        const icon = type === 'success' ? 'check-circle' : 'exclamation-circle';
        toast.innerHTML = `
            <i class="fas fa-${icon}"></i>
            <span>${message}</span>
        `;
        document.body.appendChild(toast);

        setTimeout(() => toast.classList.add('show'), 10);
        setTimeout(() => {
            toast.classList.remove('show');
            setTimeout(() => toast.remove(), 300);
        }, 3000);
    }

    // Click to upload
    uploadBox?.addEventListener('click', () => {
        evidenceInput.click();
    });

    // Handle file input change
    evidenceInput?.addEventListener('change', (e) => {
        addFiles(e.target.files);
    });

    // Drag & drop
    uploadBox?.addEventListener('dragover', (e) => {
        e.preventDefault();
        uploadBox.classList.add('dragover');
    });

    uploadBox?.addEventListener('dragleave', () => {
        uploadBox.classList.remove('dragover');
    });

    uploadBox?.addEventListener('drop', (e) => {
        e.preventDefault();
        uploadBox.classList.remove('dragover');
        addFiles(e.dataTransfer.files);
    });

    // Add files function
    function addFiles(files) {
        for (let file of files) {
            // Check if file already exists
            if (Array.from(selectedFiles.items).some(f => f.name === file.name)) {
                continue;
            }
            selectedFiles.items.add(file);
        }
        evidenceInput.files = selectedFiles.files;
        displayFiles();
    }

    // Display files
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

        // Remove file listeners
        document.querySelectorAll('.file-remove').forEach(btn => {
            btn.addEventListener('click', (e) => {
                e.preventDefault();
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

    // Form submission with toast
    const form = document.querySelector('form');
    form?.addEventListener('submit', (e) => {
        if (form.checkValidity() === false) {
            e.preventDefault();
            e.stopPropagation();
            showToast('Please fill in all required fields', 'error');
        } else {
            // Show loading toast
            showToast('Submitting your report...', 'success');
        }
        form.classList.add('was-validated');
    });

    // Check for success message after form submission
    const successAlert = document.querySelector('[role="alert"]');
    if (successAlert && successAlert.textContent.includes('successfully')) {
        showToast('Incident reported successfully!', 'success');
    }

    // Alternative: Check URL for success parameter
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get('success') === '1') {
        showToast('Incident reported successfully!', 'success');
    }
});