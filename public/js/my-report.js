document.addEventListener('DOMContentLoaded', () => {

    const searchInput = document.getElementById('searchInput');
    const tabBtns = document.querySelectorAll('.tab-btn');
    const reportCards = document.querySelectorAll('.report-card');

    let currentFilter = 'all';

    /* ==================
    FILTER BY TYPE
    ================== */
    tabBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            // Update active tab
            tabBtns.forEach(b => b.classList.remove('active'));
            btn.classList.add('active');

            // Set current filter
            currentFilter = btn.dataset.filter;

            // Filter cards
            filterReports();
        });
    });

    /* ==================
    SEARCH REPORTS
    ================== */
    searchInput.addEventListener('input', () => {
        filterReports();
    });

    /* ==================
    FILTER LOGIC
    ================== */
    function filterReports() {
        const searchTerm = searchInput.value.toLowerCase();
        let visibleCount = 0;

        reportCards.forEach(card => {
            const type = card.dataset.type;
            const title = card.querySelector('.report-title h3').textContent.toLowerCase();
            const description = card.querySelector('.report-content p').textContent.toLowerCase();

            // Type filter
            const typeMatch = currentFilter === 'all' || type === currentFilter;

            // Search filter
            const searchMatch = title.includes(searchTerm) || description.includes(searchTerm);

            // Show/hide card
            if (typeMatch && searchMatch) {
                card.style.display = 'block';
                visibleCount++;
            } else {
                card.style.display = 'none';
            }
        });

        // Show empty state if no results
        showEmptyStateIfNeeded(visibleCount);
    }

    /* ==================
    EMPTY STATE HANDLING
    ================== */
    function showEmptyStateIfNeeded(visibleCount) {
        const grid = document.querySelector('.reports-grid');
        let emptyState = grid.querySelector('.empty-state');

        if (visibleCount === 0 && !emptyState) {
            // Create empty state
            const empty = document.createElement('div');
            empty.className = 'empty-state';
            empty.innerHTML = `
                <i class="fas fa-search"></i>
                <h3>No Reports Found</h3>
                <p>Try adjusting your filters or search terms.</p>
            `;
            grid.appendChild(empty);
        } else if (visibleCount > 0 && emptyState) {
            // Remove empty state
            emptyState.remove();
        }
    }

    /* ==================
    VIEW REPORT
    ================== */
    document.addEventListener('click', (e) => {
        if (e.target.closest('.view-btn')) {
            e.preventDefault();
            const reportId = e.target.closest('.view-btn').dataset.id;
            showViewModal(reportId);
        }
    });

    function showViewModal(reportId) {
        const card = document.querySelector(`.view-btn[data-id="${reportId}"]`).closest('.report-card');
        const title = card.querySelector('.report-title h3').textContent;
        const description = card.querySelector('.report-content p').textContent;
        const status = card.querySelector('.status-badge').textContent;
        const locationText = card.querySelector('.meta-item:nth-child(1)').textContent.trim();
        const dateText = card.querySelector('.meta-item:nth-child(2)').textContent.trim();

        const modal = document.createElement('div');
        modal.className = 'modal-overlay';
        modal.innerHTML = `
            <div class="modal-content">
                <div class="modal-header">
                    <h2>${title}</h2>
                </div>
                <div class="modal-body">
                    <div class="modal-field">
                        <label>Report ID:</label>
                        <p>${reportId}</p>
                    </div>
                    <div class="modal-field">
                        <label>Status:</label>
                        <p>${status}</p>
                    </div>
                    <div class="modal-field">
                        <label>Location:</label>
                        <p>${locationText}</p>
                    </div>
                    <div class="modal-field">
                        <label>Date:</label>
                        <p>${dateText}</p>
                    </div>
                    <div class="modal-field">
                        <label>Description:</label>
                        <p>${description}</p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn-secondary close-modal-btn">Close</button>
                </div>
            </div>
        `;

        document.body.appendChild(modal);

        setTimeout(() => {
            modal.style.display = 'flex';
            
            const closeBtn = modal.querySelector('.close-modal-btn');
            closeBtn.addEventListener('click', (e) => {
                e.preventDefault();
                modal.style.display = 'none';
                setTimeout(() => modal.remove(), 100);
            });

            modal.addEventListener('click', (e) => {
                if (e.target === modal) {
                    modal.style.display = 'none';
                    setTimeout(() => modal.remove(), 100);
                }
            });
        }, 0);
    }

    /* ==================
    EDIT REPORT
    ================== */
    document.querySelectorAll('.edit-btn').forEach(btn => {
        btn.addEventListener('click', (e) => {
            e.preventDefault();
            const reportId = btn.dataset.id;
            showEditMessage(reportId);
        });
    });

    function showEditMessage(reportId) {
        const modal = document.createElement('div');
        modal.className = 'modal-overlay';
        modal.innerHTML = `
            <div class="modal-content" style="max-width: 400px;">
                <div class="modal-header">
                    <h2>Edit Report</h2>
                </div>
                <div class="modal-body">
                    <p style="color: #6b7280; text-align: center;">Edit functionality for report <strong>${reportId}</strong> will be implemented soon.</p>
                </div>
                <div class="modal-footer">
                    <button class="btn-secondary close-edit-modal">OK</button>
                </div>
            </div>
        `;

        document.body.appendChild(modal);
        modal.style.display = 'flex';

        const modalContent = modal.querySelector('.modal-content');

        // Close modal on button click
        const closeBtn = modal.querySelector('.close-edit-modal');
        closeBtn.addEventListener('click', () => {
            modal.remove();
        });

        // Close on outside click
        modal.addEventListener('click', (e) => {
            if (e.target === modal) {
                modal.remove();
            }
        });

        // Prevent clicks on modal content from triggering overlay close
        modalContent.addEventListener('click', (e) => {
            e.stopPropagation();
        });
    }

    /* ==================
    DELETE REPORT
    ================== */
    document.querySelectorAll('.delete-btn').forEach(btn => {
        btn.addEventListener('click', (e) => {
            e.preventDefault();
            const reportId = btn.dataset.id;
            if (confirm(`Are you sure you want to delete report ${reportId}?`)) {
                deleteReport(reportId);
            }
        });
    });

    function deleteReport(reportId) {
        const card = document.querySelector(`.btn-icon[data-id="${reportId}"]`).closest('.report-card');
        card.style.opacity = '0.5';
        card.style.pointerEvents = 'none';
        
        alert(`Report ${reportId} deleted successfully!`);
        card.remove();
    }

    /* ==================
    VIEW REPORT
    ================== */
    document.querySelectorAll('.view-btn').forEach(btn => {
        btn.addEventListener('click', (e) => {
            e.preventDefault();
            const reportId = btn.dataset.id;
            showViewModal(reportId);
        });
    });

    function showViewModal(reportId) {
        const card = document.querySelector(`[data-id="${reportId}"]`).closest('.report-card');
        const title = card.querySelector('.report-title h3').textContent;
        const description = card.querySelector('.report-content p').textContent;
        const status = card.querySelector('.status-badge').textContent;
        const location = card.querySelector('.meta-item:nth-child(1)').textContent.trim();
        const date = card.querySelector('.meta-item:nth-child(2)').textContent.trim();

        const modal = document.createElement('div');
        modal.className = 'modal-overlay';
        modal.innerHTML = `
            <div class="modal-content">
                <div class="modal-header">
                    <h2>${title}</h2>
                    <button class="close-modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="modal-field">
                        <label>Report ID:</label>
                        <p>${reportId}</p>
                    </div>
                    <div class="modal-field">
                        <label>Status:</label>
                        <p>${status}</p>
                    </div>
                    <div class="modal-field">
                        <label>Location:</label>
                        <p>${location.replace('📍', '').trim()}</p>
                    </div>
                    <div class="modal-field">
                        <label>Date:</label>
                        <p>${date.replace('📅', '').trim()}</p>
                    </div>
                    <div class="modal-field">
                        <label>Description:</label>
                        <p>${description}</p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn-secondary close-modal">Close</button>
                </div>
            </div>
        `;

        document.body.appendChild(modal);
        modal.style.display = 'flex';

        // Close modal
        modal.querySelectorAll('.close-modal').forEach(btn => {
            btn.addEventListener('click', () => {
                modal.remove();
            });
        });

        // Close on outside click
        modal.addEventListener('click', (e) => {
            if (e.target === modal) {
                modal.remove();
            }
        });
    }

    /* ==================
    EDIT REPORT
    ================== */
    document.querySelectorAll('.edit-btn').forEach(btn => {
        btn.addEventListener('click', (e) => {
            e.preventDefault();
            const reportId = btn.dataset.id;
            showEditMessage(reportId);
        });
    });

    function showEditMessage(reportId) {
        alert(`Edit functionality for report ${reportId} will be implemented soon.`);
    }

    /* ==================
    DELETE REPORT
    ================== */
    document.querySelectorAll('.delete-btn').forEach(btn => {
        btn.addEventListener('click', (e) => {
            e.preventDefault();
            const reportId = btn.dataset.id;
            if (confirm(`Are you sure you want to delete report ${reportId}?`)) {
                deleteReport(reportId);
            }
        });
    });

    function deleteReport(reportId) {
        const card = document.querySelector(`[data-id="${reportId}"]`).closest('.report-card');
        card.style.opacity = '0.5';
        card.style.pointerEvents = 'none';
        
        alert(`Report ${reportId} deleted successfully!`);
        card.remove();
    }

});