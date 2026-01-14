document.addEventListener('DOMContentLoaded', () => {

    const searchInput = document.getElementById('searchInput');
    const tabBtns = document.querySelectorAll('.tab-btn');
    const reportCards = document.querySelectorAll('.report-card');

    let currentFilter = 'all';

    /* ======================
       FILTER TABS
    ====================== */
    tabBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            tabBtns.forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            currentFilter = btn.dataset.filter;
            filterReports();
        });
    });

    /* ======================
       SEARCH
    ====================== */
    searchInput.addEventListener('input', filterReports);

    function filterReports() {
        const term = searchInput.value.toLowerCase();
        let visible = 0;

        reportCards.forEach(card => {
            const type = card.dataset.type;
            const title = card.querySelector('h3').textContent.toLowerCase();
            const desc = card.querySelector('.report-description').textContent.toLowerCase();

            const matchType = currentFilter === 'all' || type === currentFilter;
            const matchSearch = title.includes(term) || desc.includes(term);

            if (matchType && matchSearch) {
                card.style.display = 'block';
                visible++;
            } else {
                card.style.display = 'none';
            }
        });

        toggleEmptyState(visible);
    }

    function toggleEmptyState(count) {
        const grid = document.querySelector('.reports-grid');
        let empty = grid.querySelector('.empty-state');

        if (count === 0 && !empty) {
            const div = document.createElement('div');
            div.className = 'empty-state';
            div.innerHTML = `
                <i class="fas fa-search"></i>
                <h3>No Reports Found</h3>
                <p>Try adjusting your search or filters.</p>
            `;
            grid.appendChild(div);
        }

        if (count > 0 && empty) {
            empty.remove();
        }
    }

}); 
