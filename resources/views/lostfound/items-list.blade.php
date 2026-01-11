@extends('layouts.app')

@section('page-title', 'All Lost & Found Items')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/incidents-lists.css') }}">
@endsection

@section('content')

<a href="{{ route('dashboard') }}" style="display: inline-flex; align-items: center; gap: 6px; color: #2563eb; text-decoration: none; font-size: 16px; font-weight: 600; margin-bottom: 24px;">
    <i class="fas fa-arrow-left"></i> Back to Dashboard
</a>

<div class="incidents-container">

    <!-- Page Header -->
    <div class="page-header">
        <h2><i class="fas fa-search"></i> All Lost & Found Items</h2>
        <p>View all lost and found items reported</p>
    </div>

    <!-- Filters & Search -->
    <div class="card filters-card">
        <div class="filters-row">
            <div class="filter-group">
                <label><i class="fas fa-search"></i> Search</label>
                <input type="text" id="searchInput" placeholder="Search by item name, description...">
            </div>

            <div class="filter-group">
                <label><i class="fas fa-list"></i> Item Status</label>
                <select id="statusFilter">
                    <option value="">All Items</option>
                    <option value="lost">Lost Items</option>
                    <option value="found">Found Items</option>
                </select>
            </div>

            <div class="filter-group">
                <label><i class="fas fa-tag"></i> Category</label>
                <select id="categoryFilter">
                    <option value="">All Categories</option>
                    <option value="Electronics">Electronics</option>
                    <option value="Wallet">Wallet</option>
                    <option value="Bag">Bag</option>
                    <option value="Documents">Documents</option>
                    <option value="Others">Others</option>
                </select>
            </div>

            <button class="btn-filter-reset" onclick="resetFilters()">
                <i class="fas fa-redo"></i> Reset
            </button>
        </div>
    </div>

    <!-- Stats -->
    <div class="incidents-stats">
        <div class="stat-box">
            <span class="stat-label">Total Items</span>
            <span class="stat-value">{{ $totalItems }}</span>
        </div>
        <div class="stat-box">
            <span class="stat-label">Lost Items</span>
            <span class="stat-value">{{ $lostCount }}</span>
        </div>
        <div class="stat-box">
            <span class="stat-label">Found Items</span>
            <span class="stat-value">{{ $foundCount }}</span>
        </div>
    </div>

    <!-- Items List -->
    <div class="card incidents-list-card">
        @forelse($items as $item)
            <div class="incident-card" data-status="{{ $item->item_status }}" data-category="{{ $item->category ?? '' }}">
                <div class="incident-header">
                    <div class="incident-title-section">
                        <h3>{{ $item->item_name }}</h3>
                        <span class="incident-status status-{{ $item->item_status }}">
                            @if($item->item_status === 'found')
                                <i class="fas fa-check-circle"></i> FOUND
                            @else
                                <i class="fas fa-search"></i> LOST
                            @endif
                        </span>
                    </div>
                    <span class="incident-date">
                        <i class="fas fa-calendar-alt"></i> 
                        {{ \Carbon\Carbon::parse($item->event_datetime)->format('M d, Y') }}
                    </span>
                </div>

                <p class="incident-description">
                    {{ $item->description }}
                </p>

                <div class="incident-details">
                    <div class="detail-item">
                        <i class="fas fa-map-marker-alt"></i>
                        <span><strong>Location:</strong> {{ $item->location ?? 'N/A' }}</span>
                    </div>
                    <div class="detail-item">
                        <i class="fas fa-tag"></i>
                        <span><strong>Category:</strong> {{ $item->item_category ?? 'Uncategorized' }}</span>
                    </div>
                    <div class="detail-item">
                        <i class="fas fa-clock"></i>
                        <span><strong>Reported:</strong> {{ $item->created_at->diffForHumans() }}</span>
                    </div>
                </div>

                @if($item->image)
                    <div style="margin-bottom: 16px; text-align: center;">
                        <img src="{{ asset('storage/' . $item->image) }}" 
                             alt="{{ $item->item_name }}" 
                             style="max-width: 200px; max-height: 200px; border-radius: 8px; object-fit: cover;"
                             onerror="this.style.display='none';">
                    </div>
                @endif

                <div class="incident-footer">
                    <a href="{{ route('lostfound.show', $item->id) }}" class="btn-view-details">
                        View Details <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
            </div>
        @empty
            <div class="empty-state">
                <i class="fas fa-inbox"></i>
                <h3>No Items Found</h3>
                <p>No lost or found items matching your filters.</p>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($items->hasPages())
        <div class="pagination-section">
            {{ $items->links() }}
        </div>
    @endif

</div>

@endsection

@section('scripts')
<script>
    // Search functionality
    document.getElementById('searchInput').addEventListener('keyup', filterItems);
    document.getElementById('statusFilter').addEventListener('change', filterItems);
    document.getElementById('categoryFilter').addEventListener('change', filterItems);

    function filterItems() {
        const searchValue = document.getElementById('searchInput').value.toLowerCase();
        const statusValue = document.getElementById('statusFilter').value.toLowerCase();
        const categoryValue = document.getElementById('categoryFilter').value.toLowerCase();

        document.querySelectorAll('.incident-card').forEach(card => {
            let show = true;

            // Search filter
            if (searchValue) {
                const text = card.textContent.toLowerCase();
                show = text.includes(searchValue);
            }

            // Status filter
            if (show && statusValue) {
                const status = card.getAttribute('data-status').toLowerCase();
                show = status.includes(statusValue);
            }

            // Category filter
            if (show && categoryValue) {
                const category = card.getAttribute('data-category').toLowerCase();
                show = category.includes(categoryValue);
            }

            card.style.display = show ? 'block' : 'none';
        });
    }

    function resetFilters() {
        document.getElementById('searchInput').value = '';
        document.getElementById('statusFilter').value = '';
        document.getElementById('categoryFilter').value = '';
        document.querySelectorAll('.incident-card').forEach(card => {
            card.style.display = 'block';
        });
    }
</script>
@endsection