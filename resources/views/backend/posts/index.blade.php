<x-backend-layout>
    @push('styles')
    <link href="{{ asset('css/summernote-lite.min.css') }}" rel="stylesheet">
    @endpush

    <div class="inv-page-container">

        <!-- ── 1. Hero Header ───────────────────────────────────────────── -->
        <div class="inv-hero-header">
            <div class="d-flex align-items-center gap-3">
                <div class="inv-title-badge">
                    <i class="fas fa-boxes-stacked"></i>
                </div>
                <div>
                    <div class="d-flex align-items-center gap-2">
                        <h4 class="fw-bold mb-0 text-dark">Investment Opportunities &amp; Posts</h4>
                        <span class="badge bg-light text-dark border rounded-pill px-2.5 py-1 extra-small fw-semibold">
                            {{ $posts->total() }} Total
                        </span>
                    </div>
                    <p class="text-muted small mb-0 mt-0.5">Manage import product investment opportunities, product gallery images, and postings.</p>
                </div>
            </div>

            <div class="d-flex align-items-center gap-2">
                <button type="button" class="btn btn-primary btn-sm rounded-3 px-3.5 py-2 fw-semibold d-inline-flex align-items-center gap-2 shadow-sm" data-bs-toggle="modal" data-bs-target="#createPostModal">
                    <i class="fas fa-plus"></i>
                    <span>Add New Post</span>
                </button>
            </div>
        </div>

        <!-- ── Flash Notifications ──────────────────────────────────────── -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show rounded-4 border-0 shadow-sm mb-4 d-flex align-items-center gap-2" role="alert">
                <i class="fas fa-check-circle fs-5 text-success"></i>
                <div>{{ session('success') }}</div>
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show rounded-4 border-0 shadow-sm mb-4 d-flex align-items-center gap-2" role="alert">
                <i class="fas fa-exclamation-triangle fs-5 text-danger"></i>
                <div>Please check the form for errors below.</div>
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- ── 2. Top Summary / KPI Cards ──────────────────────────────── -->
        @php
            $totalTargetAmount = $posts->sum('target_amount');
            $totalCurrentInvested = $posts->sum('current_invested_amount');
            $activePostsCount = $posts->where('status', 'active')->count();
            $upcomingPostsCount = $posts->where('status', 'upcoming')->count();
        @endphp
        <div class="inv-stats-grid">
            <div class="inv-stat-card stat-teal">
                <div>
                    <div class="inv-stat-label">Total Opportunities</div>
                    <div class="inv-stat-val text-dark">{{ $posts->total() }}</div>
                    <div class="inv-stat-sub">{{ $activePostsCount }} active &bull; {{ $upcomingPostsCount }} upcoming</div>
                </div>
                <div class="inv-stat-icon text-teal-600" style="background: #ccfbf1; color: #0d9488;">
                    <i class="fas fa-cubes"></i>
                </div>
            </div>

            <div class="inv-stat-card stat-emerald">
                <div>
                    <div class="inv-stat-label">Total Target Value</div>
                    <div class="inv-stat-val text-success">&#2547;{{ number_format($totalTargetAmount) }}</div>
                    <div class="inv-stat-sub">Across all listed product posts</div>
                </div>
                <div class="inv-stat-icon text-success" style="background: #ecfdf5; color: #10b981;">
                    <i class="fas fa-bullseye"></i>
                </div>
            </div>

            <div class="inv-stat-card stat-blue">
                <div>
                    <div class="inv-stat-label">Total Funded Amount</div>
                    <div class="inv-stat-val text-primary">&#2547;{{ number_format($totalCurrentInvested) }}</div>
                    <div class="inv-stat-sub">
                        @if($totalTargetAmount > 0)
                            {{ number_format(($totalCurrentInvested / $totalTargetAmount) * 100, 1) }}% total funded rate
                        @else
                            0% funded rate
                        @endif
                    </div>
                </div>
                <div class="inv-stat-icon text-primary" style="background: #eff6ff; color: #3b82f6;">
                    <i class="fas fa-chart-pie"></i>
                </div>
            </div>

            <div class="inv-stat-card stat-amber">
                <div>
                    <div class="inv-stat-label">Active For Bidding</div>
                    <div class="inv-stat-val text-warning">{{ $activePostsCount }}</div>
                    <div class="inv-stat-sub">Accepting new investor bids</div>
                </div>
                <div class="inv-stat-icon text-warning" style="background: #fffbeb; color: #f59e0b;">
                    <i class="fas fa-circle-play"></i>
                </div>
            </div>
        </div>

        <!-- ── 3. Filters & Search Bar ──────────────────────────────────── -->
        <div class="inv-filter-card">
            <form method="GET" action="{{ route('posts.index') }}" class="row g-2 align-items-center">
                <div class="col-lg-5 col-md-6 col-12">
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0 text-muted ps-3"><i class="fas fa-search"></i></span>
                        <input type="text" name="search" class="form-control border-start-0 ps-1" placeholder="Search product title, code, or keyword..." value="{{ request('search') }}">
                    </div>
                </div>

                <div class="col-lg-4 col-md-6 col-12">
                    <select name="status" class="form-select">
                        <option value="">All Statuses</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>🟢 Active (Open for Bids)</option>
                        <option value="upcoming" {{ request('status') == 'upcoming' ? 'selected' : '' }}>🔵 Upcoming</option>
                        <option value="imported" {{ request('status') == 'imported' ? 'selected' : '' }}>📦 Imported</option>
                        <option value="sold_out" {{ request('status') == 'sold_out' ? 'selected' : '' }}>🟠 Sold Out</option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>🏆 Completed</option>
                    </select>
                </div>

                <div class="col-lg-3 col-md-12 col-12 d-flex gap-2">
                    <button type="submit" class="btn btn-dark btn-sm rounded-3 w-100 fw-semibold py-2 d-inline-flex align-items-center justify-content-center gap-1">
                        <i class="fas fa-filter"></i>
                        <span>Filter</span>
                    </button>
                    @if(request()->anyFilled(['search', 'status']))
                        <a href="{{ route('posts.index') }}" class="btn btn-outline-secondary btn-sm rounded-3 py-2 px-2.5" title="Clear Filters">
                            <i class="fas fa-times"></i>
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <!-- ── 4. Mobile View (d-md-none) ───────────────────────────────── -->
        <div class="d-md-none">
            @forelse($posts as $post)
                @php
                    $badgeConfig = match($post->status) {
                        'active'    => ['class' => 'bg-success-subtle text-success border border-success-subtle', 'icon' => 'fas fa-check-circle'],
                        'upcoming'  => ['class' => 'bg-info-subtle text-info border border-info-subtle', 'icon' => 'fas fa-clock'],
                        'imported'  => ['class' => 'bg-primary-subtle text-primary border border-primary-subtle', 'icon' => 'fas fa-box'],
                        'sold_out'  => ['class' => 'bg-warning-subtle text-warning-emphasis border border-warning-subtle', 'icon' => 'fas fa-tag'],
                        'completed' => ['class' => 'bg-secondary-subtle text-secondary border border-secondary-subtle', 'icon' => 'fas fa-award'],
                        default     => ['class' => 'bg-light text-dark border', 'icon' => 'fas fa-circle'],
                    };
                    $galleryCount = is_array($post->gallery_images) ? count($post->gallery_images) : 0;
                    $coverImage = $post->image ? asset($post->image) : asset('asset/images/earbuds.jpg');
                @endphp
                <div class="inv-mobile-card">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="d-flex align-items-center gap-2">
                            <img src="{{ $coverImage }}" alt="{{ $post->title }}" class="inv-prod-thumb" style="width: 44px; height: 44px;">
                            <div>
                                <strong class="d-block text-dark small fw-bold">{{ $post->title }}</strong>
                                <span class="text-muted extra-small">
                                    <i class="fas fa-truck-fast text-primary me-1"></i>{{ $post->expected_import_days }} Days
                                    @if($galleryCount > 0)
                                        &bull; <i class="fas fa-images text-muted ms-1"></i> {{ $galleryCount }}
                                    @endif
                                </span>
                            </div>
                        </div>
                        <span class="inv-badge-pill {{ $badgeConfig['class'] }}">
                            <i class="{{ $badgeConfig['icon'] }}"></i> {{ str_replace('_', ' ', $post->status) }}
                        </span>
                    </div>

                    <div class="inv-mobile-card-grid">
                        <div>
                            <span class="text-muted d-block extra-small">Total Qty</span>
                            <strong class="text-dark fs-6">{{ number_format($post->total_quantity) }} pcs</strong>
                        </div>
                        <div>
                            <span class="text-muted d-block extra-small">Unit Cost</span>
                            <strong class="text-dark fs-6">&#2547;{{ number_format($post->unit_cost) }}</strong>
                        </div>
                        <div>
                            <span class="text-muted d-block extra-small">Target Amount</span>
                            <strong class="text-dark fs-6">&#2547;{{ number_format($post->target_amount) }}</strong>
                        </div>
                        <div>
                            <span class="text-muted d-block extra-small">Profit / Piece</span>
                            <strong class="text-success fs-6">+&#2547;{{ number_format($post->profit_per_unit) }}</strong>
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="d-flex justify-content-between extra-small text-muted mb-1">
                            <span>Invested: <strong class="text-success">&#2547;{{ number_format($post->current_invested_amount) }}</strong></span>
                            <span class="fw-bold text-success">{{ $post->funded_percentage }}% Funded</span>
                        </div>
                        <div class="inv-pay-progress">
                            <div class="inv-pay-progress-bar bg-success" style="width: {{ min(100, $post->funded_percentage) }}%;"></div>
                        </div>
                    </div>

                    <div class="d-flex flex-wrap gap-2 border-top pt-2">
                        <a href="{{ route('opportunity.show', $post->id) }}" target="_blank" class="btn btn-sm btn-light border text-dark fw-semibold px-3 py-1.5 rounded-3">
                            <i class="fas fa-external-link-alt me-1 text-muted"></i> View
                        </a>
                        <a href="{{ route('investments.index', ['investment_post_id' => $post->id]) }}" class="btn btn-sm btn-light border text-dark fw-semibold px-3 py-1.5 rounded-3">
                            <i class="fas fa-users me-1 text-primary"></i> {{ $post->investments->count() }} Bids
                        </a>
                        <button type="button" class="btn btn-sm btn-outline-primary flex-grow-1 rounded-3 edit-post-btn fw-semibold py-1.5"
                            data-bs-toggle="modal"
                            data-bs-target="#editPostModal"
                            data-id="{{ $post->id }}"
                            data-title="{{ $post->title }}"
                            data-description="{{ $post->description }}"
                            data-total_quantity="{{ $post->total_quantity }}"
                            data-unit_cost="{{ $post->unit_cost }}"
                            data-profit_per_unit="{{ $post->profit_per_unit }}"
                            data-expected_import_days="{{ $post->expected_import_days }}"
                            data-target_amount="{{ $post->target_amount }}"
                            data-min_investment_amount="{{ $post->min_investment_amount }}"
                            data-status="{{ $post->status }}"
                            data-image="{{ $coverImage }}"
                            data-gallery_images='@json($post->gallery_images ?? [])'>
                            <i class="fas fa-pen me-1"></i> Edit
                        </button>
                        <form action="{{ route('posts.destroy', $post->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this post?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger rounded-3 px-3 py-1.5 fw-semibold" title="Delete">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="text-center py-5 bg-white rounded-4 shadow-sm text-muted">
                    <i class="fas fa-inbox fa-3x mb-3 text-muted opacity-25"></i>
                    <p class="mb-0 fw-semibold">No investment posts found.</p>
                </div>
            @endforelse
        </div>

        <!-- ── 5. Desktop Table View (d-none d-md-block) ────────────────── -->
        <div class="inv-table-card d-none d-md-block">
            <div class="table-responsive">
                <table class="table inv-table align-middle">
                    <thead>
                        <tr>
                            <th class="ps-4" style="min-width: 220px;">Product Opportunity</th>
                            <th style="min-width: 140px;">Gallery</th>
                            <th style="min-width: 160px;">Quantity &amp; Cost</th>
                            <th style="min-width: 180px;">Invested &amp; Funding</th>
                            <th style="min-width: 130px;">Unit Profit</th>
                            <th style="min-width: 110px;">Bids</th>
                            <th style="min-width: 120px;">Status</th>
                            <th class="text-end pe-4" style="min-width: 130px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($posts as $post)
                            @php
                                $galleryList = is_array($post->gallery_images) ? $post->gallery_images : [];
                                $coverImage = $post->image ? asset($post->image) : asset('asset/images/earbuds.jpg');
                                $badgeConfig = match($post->status) {
                                    'active'    => ['class' => 'bg-success-subtle text-success border border-success-subtle', 'icon' => 'fas fa-check-circle'],
                                    'upcoming'  => ['class' => 'bg-info-subtle text-info border border-info-subtle', 'icon' => 'fas fa-clock'],
                                    'imported'  => ['class' => 'bg-primary-subtle text-primary border border-primary-subtle', 'icon' => 'fas fa-box'],
                                    'sold_out'  => ['class' => 'bg-warning-subtle text-warning-emphasis border border-warning-subtle', 'icon' => 'fas fa-tag'],
                                    'completed' => ['class' => 'bg-secondary-subtle text-secondary border border-secondary-subtle', 'icon' => 'fas fa-award'],
                                    default     => ['class' => 'bg-light text-dark border', 'icon' => 'fas fa-circle'],
                                };
                            @endphp
                            <tr>
                                {{-- 1. Product --}}
                                <td class="ps-4">
                                    <div class="d-flex align-items-center gap-3">
                                        <img src="{{ $coverImage }}" alt="{{ $post->title }}" class="inv-prod-thumb" />
                                        <div style="min-width: 0;">
                                            <a href="{{ route('opportunity.show', $post->id) }}" target="_blank" class="fw-bold text-dark text-truncate d-block mb-0.5 hover-primary" style="font-size: 0.88rem; max-width: 190px;" title="{{ $post->title }}">
                                                {{ $post->title }} <i class="fas fa-arrow-up-right-from-square extra-small text-muted ms-1"></i>
                                            </a>
                                            <div class="text-muted extra-small">
                                                <i class="fas fa-truck-fast me-1 text-primary"></i>{{ $post->expected_import_days }} Days Import
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                {{-- 2. Gallery --}}
                                <td>
                                    <div class="d-flex align-items-center gap-1">
                                        @if(count($galleryList) > 0)
                                            @foreach(array_slice($galleryList, 0, 3) as $gImg)
                                                <img src="{{ asset($gImg) }}" alt="Gallery" class="rounded-2 border" style="width: 30px; height: 30px; object-fit: cover;" />
                                            @endforeach
                                            @if(count($galleryList) > 3)
                                                <span class="badge bg-light text-dark border rounded-circle" style="width: 28px; height: 28px; display: inline-flex; align-items: center; justify-content: center; font-size: 0.7rem;">+{{ count($galleryList) - 3 }}</span>
                                            @endif
                                        @else
                                            <span class="text-muted extra-small"><i class="fas fa-image text-muted opacity-50 me-1"></i>None</span>
                                        @endif
                                    </div>
                                </td>

                                {{-- 3. Quantity / Cost --}}
                                <td>
                                    <div class="inv-metrics-box">
                                        <div class="inv-metric-row">
                                            <span class="inv-metric-label">Total Qty:</span>
                                            <span class="badge bg-light text-dark border inv-badge-sm">{{ number_format($post->total_quantity) }} pcs</span>
                                        </div>
                                        <div class="inv-metric-row">
                                            <span class="inv-metric-label">Unit Cost:</span>
                                            <span class="inv-metric-val">&#2547;{{ number_format($post->unit_cost) }}/pc</span>
                                        </div>
                                        <div class="inv-metric-row">
                                            <span class="inv-metric-label">Target:</span>
                                            <span class="inv-metric-val fw-bold text-dark">&#2547;{{ number_format($post->target_amount) }}</span>
                                        </div>
                                    </div>
                                </td>

                                {{-- 4. Invested & Progress --}}
                                <td>
                                    <div class="inv-metrics-box">
                                        <div class="inv-metric-row">
                                            <span class="inv-metric-label">Invested:</span>
                                            <span class="inv-metric-val fw-bold text-success">&#2547;{{ number_format($post->current_invested_amount) }}</span>
                                        </div>
                                        <div class="inv-pay-progress">
                                            <div class="inv-pay-progress-bar bg-success" style="width: {{ min(100, $post->funded_percentage) }}%;"></div>
                                        </div>
                                        <div class="inv-metric-row mt-1">
                                            <span class="inv-metric-label">Funded:</span>
                                            <span class="badge bg-success-subtle text-success border border-success-subtle inv-badge-sm fw-bold">{{ $post->funded_percentage }}%</span>
                                        </div>
                                    </div>
                                </td>

                                {{-- 5. Profit / Unit --}}
                                <td>
                                    <span class="badge bg-success-subtle text-success border border-success-subtle px-2.5 py-1 inv-badge-sm fw-bold">
                                        +&#2547;{{ number_format($post->profit_per_unit) }}
                                    </span>
                                </td>

                                {{-- 6. Investment Bids --}}
                                <td>
                                    <a href="{{ route('investments.index', ['investment_post_id' => $post->id]) }}" class="badge bg-light text-dark border rounded-pill px-2.5 py-1 extra-small fw-semibold hover-bg-light" title="View all bids for this post">
                                        <i class="fas fa-users me-1 text-primary"></i>{{ $post->investments->count() }}
                                    </a>
                                </td>

                                {{-- 7. Status --}}
                                <td>
                                    <span class="inv-badge-pill {{ $badgeConfig['class'] }}">
                                        <i class="{{ $badgeConfig['icon'] }}"></i> {{ str_replace('_', ' ', $post->status) }}
                                    </span>
                                </td>

                                {{-- 8. Actions --}}
                                <td class="text-end pe-4">
                                    <div class="d-inline-flex align-items-center gap-1 justify-content-end">
                                        <button type="button" class="btn btn-sm btn-outline-primary inv-action-btn edit-post-btn"
                                            data-bs-toggle="modal"
                                            data-bs-target="#editPostModal"
                                            data-id="{{ $post->id }}"
                                            data-title="{{ $post->title }}"
                                            data-description="{{ $post->description }}"
                                            data-total_quantity="{{ $post->total_quantity }}"
                                            data-unit_cost="{{ $post->unit_cost }}"
                                            data-profit_per_unit="{{ $post->profit_per_unit }}"
                                            data-expected_import_days="{{ $post->expected_import_days }}"
                                            data-target_amount="{{ $post->target_amount }}"
                                            data-min_investment_amount="{{ $post->min_investment_amount }}"
                                            data-status="{{ $post->status }}"
                                            data-image="{{ $coverImage }}"
                                            data-gallery_images='@json($post->gallery_images ?? [])'
                                            title="Edit Post">
                                            <i class="fas fa-pen"></i>
                                        </button>
                                        <form action="{{ route('posts.destroy', $post->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this post?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger inv-action-btn" title="Delete Post">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-5 text-muted">
                                    <div class="py-4">
                                        <div class="mb-3">
                                            <i class="fas fa-boxes-stacked fa-3x text-muted opacity-25"></i>
                                        </div>
                                        <h6 class="fw-bold text-dark mb-1">No investment posts found</h6>
                                        <p class="text-muted small mb-0">Try changing your filters or add a new opportunity to get started.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- ── 6. Pagination ───────────────────────────────────────────── -->
        @if($posts->hasPages())
            <div class="mt-4 d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div class="text-muted small">
                    Showing {{ $posts->firstItem() ?? 0 }} to {{ $posts->lastItem() ?? 0 }} of {{ $posts->total() }} posts
                </div>
                <div>
                    {{ $posts->links() }}
                </div>
            </div>
        @endif

    </div>

    <!-- ── 7. Create Post Modal ────────────────────────────────────────── -->
    <div class="modal fade" id="createPostModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
                <div class="modal-header inv-modal-header text-white">
                    <div class="d-flex align-items-center gap-2">
                        <i class="fas fa-plus-circle fs-5"></i>
                        <h5 class="modal-title fw-bold mb-0">Create New Investment Opportunity</h5>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body p-4">
                        <div class="row g-3">
                            <div class="col-md-8 col-12">
                                <label class="form-label fw-semibold small text-dark">Product Title <span class="text-danger">*</span></label>
                                <input type="text" name="title" class="form-control" placeholder="e.g. Wireless ANC Earbuds Pro" required>
                            </div>
                            <div class="col-md-4 col-12">
                                <label class="form-label fw-semibold small text-dark">Status <span class="text-danger">*</span></label>
                                <select name="status" class="form-select" required>
                                    <option value="active" selected>🟢 Active (Open for Bids)</option>
                                    <option value="upcoming">🔵 Upcoming</option>
                                    <option value="imported">📦 Imported</option>
                                    <option value="sold_out">🟠 Sold Out</option>
                                    <option value="completed">🏆 Completed</option>
                                </select>
                            </div>

                            <div class="col-md-4 col-12">
                                <label class="form-label fw-semibold small text-dark">Total Quantity (pcs) <span class="text-danger">*</span></label>
                                <input type="number" name="total_quantity" id="create_total_quantity" class="form-control" value="1000" min="1" required>
                            </div>
                            <div class="col-md-4 col-12">
                                <label class="form-label fw-semibold small text-dark">Cost per Piece (&#2547;) <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0 fw-bold">&#2547;</span>
                                    <input type="number" name="unit_cost" id="create_unit_cost" class="form-control" value="500" step="0.01" min="0.01" required>
                                </div>
                            </div>
                            <div class="col-md-4 col-12">
                                <label class="form-label fw-semibold small text-dark">Profit per Piece (&#2547;) <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0 fw-bold text-success">+&#2547;</span>
                                    <input type="number" name="profit_per_unit" id="create_profit_per_unit" class="form-control text-success fw-semibold" value="50" step="0.01" min="0" required>
                                </div>
                            </div>

                            <div class="col-md-4 col-12">
                                <label class="form-label fw-semibold small text-dark">Import Duration (Days) <span class="text-danger">*</span></label>
                                <input type="number" name="expected_import_days" class="form-control" value="25" min="1" required>
                            </div>
                            <div class="col-md-4 col-12">
                                <label class="form-label fw-semibold small text-dark">Target Amount (&#2547;)</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0 fw-bold">&#2547;</span>
                                    <input type="number" name="target_amount" id="create_target_amount" class="form-control" value="500000" placeholder="Auto: Qty * Cost">
                                </div>
                            </div>
                            <div class="col-md-4 col-12">
                                <label class="form-label fw-semibold small text-dark">Min Investment (&#2547;) <span class="extra-small text-muted">(80%)</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0 fw-bold">&#2547;</span>
                                    <input type="number" name="min_investment_amount" id="create_min_investment_amount" class="form-control" value="400000" min="1" required>
                                </div>
                            </div>

                            <div class="col-md-6 col-12">
                                <label class="form-label fw-semibold small text-dark">Main Cover Image</label>
                                <input type="file" name="image" class="form-control" accept="image/*">
                            </div>
                            <div class="col-md-6 col-12">
                                <label class="form-label fw-semibold small text-dark">Gallery Images (Multiple)</label>
                                <input type="file" name="gallery_images[]" class="form-control" accept="image/*" multiple>
                                <small class="text-muted extra-small">Upload multiple thumbnails for product gallery.</small>
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-semibold small text-dark">Detailed Description &amp; Highlights</label>
                                <textarea name="description" id="create_description" class="form-control"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer bg-light py-3 px-4 border-top">
                        <button type="button" class="btn btn-secondary btn-sm rounded-3 px-3" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary btn-sm rounded-3 px-4 fw-semibold shadow-sm">Save Post</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- ── 8. Edit Post Modal ──────────────────────────────────────────── -->
    <div class="modal fade" id="editPostModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
                <div class="modal-header inv-modal-header text-white">
                    <div class="d-flex align-items-center gap-2">
                        <i class="fas fa-pen-to-square fs-5"></i>
                        <h5 class="modal-title fw-bold mb-0">Edit Investment Post</h5>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="editPostForm" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="modal-body p-4">
                        <div class="row g-3">
                            <div class="col-md-8 col-12">
                                <label class="form-label fw-semibold small text-dark">Product Title <span class="text-danger">*</span></label>
                                <input type="text" name="title" id="edit_title" class="form-control" required>
                            </div>
                            <div class="col-md-4 col-12">
                                <label class="form-label fw-semibold small text-dark">Status <span class="text-danger">*</span></label>
                                <select name="status" id="edit_status" class="form-select" required>
                                    <option value="active">🟢 Active</option>
                                    <option value="upcoming">🔵 Upcoming</option>
                                    <option value="imported">📦 Imported</option>
                                    <option value="sold_out">🟠 Sold Out</option>
                                    <option value="completed">🏆 Completed</option>
                                </select>
                            </div>

                            <div class="col-md-4 col-12">
                                <label class="form-label fw-semibold small text-dark">Total Quantity (pcs) <span class="text-danger">*</span></label>
                                <input type="number" name="total_quantity" id="edit_total_quantity" class="form-control" min="1" required>
                            </div>
                            <div class="col-md-4 col-12">
                                <label class="form-label fw-semibold small text-dark">Cost per Piece (&#2547;) <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0 fw-bold">&#2547;</span>
                                    <input type="number" name="unit_cost" id="edit_unit_cost" class="form-control" step="0.01" min="0.01" required>
                                </div>
                            </div>
                            <div class="col-md-4 col-12">
                                <label class="form-label fw-semibold small text-dark">Profit per Piece (&#2547;) <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0 fw-bold text-success">+&#2547;</span>
                                    <input type="number" name="profit_per_unit" id="edit_profit_per_unit" class="form-control text-success fw-semibold" step="0.01" min="0" required>
                                </div>
                            </div>

                            <div class="col-md-4 col-12">
                                <label class="form-label fw-semibold small text-dark">Import Duration (Days) <span class="text-danger">*</span></label>
                                <input type="number" name="expected_import_days" id="edit_expected_import_days" class="form-control" min="1" required>
                            </div>
                            <div class="col-md-4 col-12">
                                <label class="form-label fw-semibold small text-dark">Target Amount (&#2547;)</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0 fw-bold">&#2547;</span>
                                    <input type="number" name="target_amount" id="edit_target_amount" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-4 col-12">
                                <label class="form-label fw-semibold small text-dark">Min Investment (&#2547;)</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0 fw-bold">&#2547;</span>
                                    <input type="number" name="min_investment_amount" id="edit_min_investment_amount" class="form-control" min="1" required>
                                </div>
                            </div>

                            <div class="col-md-6 col-12">
                                <label class="form-label fw-semibold small text-dark">Change Cover Image</label>
                                <input type="file" name="image" class="form-control" accept="image/*">
                                <div class="mt-2" id="edit_cover_preview_wrap">
                                    <span class="extra-small text-muted d-block mb-1">Current Cover Image:</span>
                                    <img id="edit_cover_preview" src="" alt="Cover Image" class="rounded-3 border shadow-sm" style="max-height: 75px; width: auto; object-fit: cover;" />
                                </div>
                            </div>
                            <div class="col-md-6 col-12">
                                <label class="form-label fw-semibold small text-dark">Add Additional Gallery Images</label>
                                <input type="file" name="gallery_images[]" class="form-control" accept="image/*" multiple>
                            </div>

                            <div class="col-12" id="edit_gallery_wrap">
                                <label class="form-label fw-semibold small text-dark d-block">Current Gallery Images</label>
                                <div id="edit_gallery_container" class="gallery-thumb-wrap"></div>
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-semibold small text-dark">Detailed Description &amp; Highlights</label>
                                <textarea name="description" id="edit_description" class="form-control"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer bg-light py-3 px-4 border-top">
                        <button type="button" class="btn btn-secondary btn-sm rounded-3 px-3" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary btn-sm rounded-3 px-4 fw-semibold shadow-sm">Update Post</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="{{ asset('js/summernote-lite.min.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var baseUrl = '{{ asset("") }}';

            // Summernote Options
            var summernoteOptions = {
                placeholder: 'Enter detailed product description and investment highlights...',
                tabsize: 2,
                height: 160,
                toolbar: [
                    ['style', ['style', 'bold', 'italic', 'underline', 'clear']],
                    ['font', ['strikethrough', 'superscript', 'subscript']],
                    ['color', ['color']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['insert', ['link', 'table', 'hr']],
                    ['view', ['codeview', 'fullscreen']]
                ]
            };

            // Initialize Summernote
            if (typeof $ !== 'undefined' && $.fn.summernote) {
                $('#create_description').summernote(summernoteOptions);
                $('#edit_description').summernote(summernoteOptions);
            }

            // Auto calculate Target Amount & Min Investment (80%) for Create Modal
            function updateCreateCalc() {
                var qty = parseFloat(document.getElementById('create_total_quantity').value) || 0;
                var cost = parseFloat(document.getElementById('create_unit_cost').value) || 0;
                var target = qty * cost;
                var minInvest = target * 0.8;
                document.getElementById('create_target_amount').value = target > 0 ? Math.round(target * 100) / 100 : '';
                document.getElementById('create_min_investment_amount').value = minInvest > 0 ? Math.round(minInvest * 100) / 100 : '';
            }

            var createQtyEl = document.getElementById('create_total_quantity');
            var createCostEl = document.getElementById('create_unit_cost');
            if (createQtyEl && createCostEl) {
                createQtyEl.addEventListener('input', updateCreateCalc);
                createCostEl.addEventListener('input', updateCreateCalc);
            }

            // Auto calculate Target Amount & Min Investment (80%) for Edit Modal
            function updateEditCalc() {
                var qty = parseFloat(document.getElementById('edit_total_quantity').value) || 0;
                var cost = parseFloat(document.getElementById('edit_unit_cost').value) || 0;
                var target = qty * cost;
                var minInvest = target * 0.8;
                document.getElementById('edit_target_amount').value = target > 0 ? Math.round(target * 100) / 100 : '';
                document.getElementById('edit_min_investment_amount').value = minInvest > 0 ? Math.round(minInvest * 100) / 100 : '';
            }

            var editQtyEl = document.getElementById('edit_total_quantity');
            var editCostEl = document.getElementById('edit_unit_cost');
            if (editQtyEl && editCostEl) {
                editQtyEl.addEventListener('input', updateEditCalc);
                editCostEl.addEventListener('input', updateEditCalc);
            }

            var editButtons = document.querySelectorAll('.edit-post-btn');
            editButtons.forEach(function(btn) {
                btn.addEventListener('click', function() {
                    var id = this.getAttribute('data-id');
                    var form = document.getElementById('editPostForm');
                    form.action = '{{ url("posts") }}/' + id;

                    document.getElementById('edit_title').value = this.getAttribute('data-title') || '';
                    document.getElementById('edit_total_quantity').value = this.getAttribute('data-total_quantity') || '';
                    document.getElementById('edit_unit_cost').value = this.getAttribute('data-unit_cost') || '';
                    document.getElementById('edit_profit_per_unit').value = this.getAttribute('data-profit_per_unit') || '';
                    document.getElementById('edit_expected_import_days').value = this.getAttribute('data-expected_import_days') || '';
                    document.getElementById('edit_target_amount').value = this.getAttribute('data-target_amount') || '';
                    document.getElementById('edit_min_investment_amount').value = this.getAttribute('data-min_investment_amount') || '';
                    document.getElementById('edit_status').value = this.getAttribute('data-status') || 'active';

                    // Set description in Summernote
                    var desc = this.getAttribute('data-description') || '';
                    if (typeof $ !== 'undefined' && $.fn.summernote) {
                        $('#edit_description').summernote('code', desc);
                    } else {
                        document.getElementById('edit_description').value = desc;
                    }

                    // Cover Image preview
                    var coverUrl = this.getAttribute('data-image');
                    var coverImg = document.getElementById('edit_cover_preview');
                    var coverWrap = document.getElementById('edit_cover_preview_wrap');
                    if (coverUrl) {
                        coverImg.src = coverUrl;
                        coverWrap.style.display = 'block';
                    } else {
                        coverWrap.style.display = 'none';
                    }

                    // Gallery images
                    var galleryContainer = document.getElementById('edit_gallery_container');
                    galleryContainer.innerHTML = '';
                    var rawGallery = this.getAttribute('data-gallery_images');
                    try {
                        var galleryArr = JSON.parse(rawGallery) || [];
                        if (galleryArr.length > 0) {
                            galleryArr.forEach(function(path) {
                                var imgUrl = (path.startsWith('http://') || path.startsWith('https://')) ? path : (baseUrl + path.replace(/^\//, ''));
                                var div = document.createElement('div');
                                div.className = 'gallery-thumb-item';
                                div.innerHTML = '<img src="' + imgUrl + '" alt="Gallery Image"/>' +
                                    '<label class="gallery-thumb-remove" title="Remove Image">' +
                                    '<input type="checkbox" name="remove_gallery_images[]" value="' + path + '" style="display:none;" onchange="this.parentNode.parentNode.style.opacity=this.checked?0.3:1"><i class="fas fa-times"></i></label>';
                                galleryContainer.appendChild(div);
                            });
                        } else {
                            galleryContainer.innerHTML = '<span class="text-muted extra-small">No gallery images uploaded yet.</span>';
                        }
                    } catch(e) {
                        galleryContainer.innerHTML = '<span class="text-muted extra-small">No gallery images uploaded yet.</span>';
                    }
                });
            });
        });
    </script>
    @endpush
</x-backend-layout>
