document.addEventListener('DOMContentLoaded', function () {
    document.getElementById('searchInput')?.addEventListener('keyup', filterIncidents);
    document.getElementById('typeFilter')?.addEventListener('change', filterIncidents);
    document.getElementById('statusFilter')?.addEventListener('change', filterIncidents);
});

function filterIncidents() {
    const searchValue = document.getElementById('searchInput').value.toLowerCase();
    const typeValue   = document.getElementById('typeFilter').value.toLowerCase();
    const statusValue = document.getElementById('statusFilter').value.toLowerCase();

    const cards = document.querySelectorAll('.incident-card');

    cards.forEach(card => {
        let show = true;

        const baseType   = (card.dataset.type || '').toLowerCase();        // e.g. "other"
        const customType = (card.dataset.customType || '').toLowerCase();  // e.g. "bullying"
        const status     = (card.dataset.status || '').toLowerCase();
        const fullText   = card.textContent.toLowerCase();

        // 🔍 Search
        if (searchValue && !fullText.includes(searchValue)) {
            show = false;
        }

        // 📂 Type filter
        if (show && typeValue) {
            if (typeValue === 'other') {
                show = baseType === 'other';
            } else {
                show = baseType === typeValue || customType === typeValue;
            }
        }

        // 🏷 Status filter
        if (show && statusValue) {
            show = status === statusValue;
        }

        card.style.display = show ? 'block' : 'none';
    });

    updateEmptyState();
}

function resetFilters() {
    document.getElementById('searchInput').value = '';
    document.getElementById('typeFilter').value = '';
    document.getElementById('statusFilter').value = '';

    document.querySelectorAll('.incident-card').forEach(card => {
        card.style.display = 'block';
    });

    updateEmptyState();
}

function updateEmptyState() {
    const cards = document.querySelectorAll('.incident-card');
    const empty = document.querySelector('.empty-state');

    if (!empty) return;

    const visible = Array.from(cards).some(card => card.style.display !== 'none');
    empty.style.display = visible ? 'none' : 'block';
}
