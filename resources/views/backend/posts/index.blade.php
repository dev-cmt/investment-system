<x-backend-layout>
    @push('styles')
    <link href="{{ asset('css/summernote-lite.min.css') }}" rel="stylesheet">
    <style>
        .note-editor.note-frame {
            border-radius: 8px !important;
            border-color: #dee2e6 !important;
            box-shadow: none !important;
        }
        .note-editor .note-toolbar {
            background: #f8f9fa !important;
            border-bottom: 1px solid #dee2e6 !important;
            padding: 6px 10px !important;
        }
        .note-btn {
            background: #ffffff !important;
            border: 1px solid #ced4da !important;
            color: #495057 !important;
            padding: 4px 8px !important;
            font-size: 0.78rem !important;
        }
        .note-btn:hover {
            background: #e9ecef !important;
        }
        .note-modal .modal-header, .note-modal .modal-footer {
            padding: 10px 15px;
        }
        .note-dropdown-menu {
            z-index: 1060 !important;
        }
    </style>
    @endpush
    <div class="container-fluid py-4">
        <!-- Header -->
        <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
            <div>
                <h4 class="fw-bold mb-1"><i class="fas fa-boxes-stacked text-primary me-2"></i>Investment Posts</h4>
                <p class="text-muted small mb-0">Manage import product investment opportunities, product gallery images, and postings.</p>
            </div>
            <button type="button" class="btn btn-primary btn-sm rounded-3 px-3 fw-semibold" data-bs-toggle="modal" data-bs-target="#createPostModal">
                <i class="fas fa-plus me-1.5"></i> Add New Post
            </button>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show rounded-3 border-0 shadow-sm" role="alert">
                <i class="fas fa-check-circle me-1.5"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show rounded-3 border-0 shadow-sm" role="alert">
                <i class="fas fa-exclamation-triangle me-1.5"></i> Please check the form for errors.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Filter Card -->
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
            <div class="card-body py-3">
                <form method="GET" action="{{ route('posts.index') }}" class="row g-2">
                    <div class="col-md-5 col-sm-6">
                        <div class="input-group input-group-sm">
                            <span class="input-group-text bg-light border-end-0"><i class="fas fa-search text-muted"></i></span>
                            <input type="text" name="search" class="form-control form-control-sm border-start-0 ps-0" placeholder="Search posts..." value="{{ request('search') }}">
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-6">
                        <select name="status" class="form-select form-select-sm">
                            <option value="">All Statuses</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="upcoming" {{ request('status') == 'upcoming' ? 'selected' : '' }}>Upcoming</option>
                            <option value="imported" {{ request('status') == 'imported' ? 'selected' : '' }}>Imported</option>
                            <option value="sold_out" {{ request('status') == 'sold_out' ? 'selected' : '' }}>Sold Out</option>
                            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                        </select>
                    </div>
                    <div class="col-md-3 col-12">
                        <button type="submit" class="btn btn-secondary btn-sm w-100 fw-semibold"><i class="fas fa-filter me-1"></i> Filter</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- MOBILE RESPONSIVE CARD VIEW (d-md-none) -->
        <div class="d-md-none">
            <div class="row g-3">
                @forelse($posts as $post)
                    @php
                        $badgeClass = match($post->status) {
                            'active' => 'bg-success-subtle text-success border-success',
                            'upcoming' => 'bg-info-subtle text-info border-info',
                            'imported' => 'bg-primary-subtle text-primary border-primary',
                            'sold_out' => 'bg-warning-subtle text-warning border-warning',
                            'completed' => 'bg-secondary-subtle text-secondary border-secondary',
                            default => 'bg-light text-dark',
                        };
                        $galleryCount = is_array($post->gallery_images) ? count($post->gallery_images) : 0;
                    @endphp
                    <div class="col-12">
                        <div class="admin-data-card">
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <div class="d-flex align-items-center gap-2">
                                    <img src="{{ asset($post->image ?? 'asset/images/earbuds.jpg') }}" alt="{{ $post->title }}" class="rounded-3 shadow-sm border" style="width: 44px; height: 44px; object-fit: cover;" />
                                    <div>
                                        <strong class="d-block text-dark small fw-bold">{{ $post->title }}</strong>
                                        <span class="text-muted extra-small">Import: {{ $post->expected_import_days }} Days @if($galleryCount > 0) &bull; <span class="text-primary"><i class="fas fa-images"></i> {{ $galleryCount }} photos</span> @endif</span>
                                    </div>
                                </div>
                                <span class="badge {{ $badgeClass }} border rounded-pill px-2.5 py-1 extra-small fw-semibold text-uppercase">
                                    {{ str_replace('_', ' ', $post->status) }}
                                </span>
                            </div>

                            <div class="data-card-grid">
                                <div><span class="text-muted d-block extra-small">Quantity</span><strong>{{ number_format($post->total_quantity) }} pcs</strong></div>
                                <div><span class="text-muted d-block extra-small">Unit Cost</span><strong>&#2547;{{ number_format($post->unit_cost) }}</strong></div>
                                <div><span class="text-muted d-block extra-small">Target</span><strong class="text-dark">&#2547;{{ number_format($post->target_amount) }}</strong></div>
                                <div><span class="text-muted d-block extra-small">Profit/pc</span><strong class="text-primary">&#2547;{{ number_format($post->profit_per_unit) }}</strong></div>
                            </div>

                            <div class="mb-3">
                                <div class="d-flex justify-content-between extra-small mb-1">
                                    <span class="text-muted">Invested: <strong class="text-success">&#2547;{{ number_format($post->current_invested_amount) }}</strong></span>
                                    <span class="fw-bold text-success">{{ $post->funded_percentage }}%</span>
                                </div>
                                <div class="progress rounded-pill" style="height: 6px;">
                                    <div class="progress-bar bg-success rounded-pill" style="width: {{ $post->funded_percentage }}%"></div>
                                </div>
                            </div>

                            <div class="d-flex gap-2 border-top pt-2">
                                <button type="button" class="btn btn-sm btn-outline-primary w-100 rounded-3 edit-post-btn fw-semibold"
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
                                    data-image="{{ asset($post->image ?? 'asset/images/earbuds.jpg') }}"
                                    data-gallery_images='@json($post->gallery_images ?? [])'>
                                    <i class="fas fa-pen me-1"></i> Edit
                                </button>
                                <form action="{{ route('posts.destroy', $post->id) }}" method="POST" class="w-100" onsubmit="return confirm('Are you sure you want to delete this post?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger w-100 rounded-3 fw-semibold">
                                        <i class="fas fa-trash me-1"></i> Delete
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center py-4 bg-white rounded-4 shadow-sm text-muted">No investment posts found.</div>
                @endforelse
            </div>
        </div>

        <!-- DESKTOP TABLE VIEW (d-none d-md-block) -->
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden d-none d-md-block">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light text-muted small text-uppercase">
                            <tr>
                                <th class="ps-4">Product</th>
                                <th>Gallery</th>
                                <th>Quantity / Cost</th>
                                <th>Target Amount</th>
                                <th>Invested</th>
                                <th>Profit / Unit</th>
                                <th>Status</th>
                                <th class="text-end pe-4">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($posts as $post)
                                @php
                                    $galleryList = is_array($post->gallery_images) ? $post->gallery_images : [];
                                @endphp
                                <tr>
                                    <td class="ps-4">
                                        <div class="d-flex align-items-center gap-3">
                                            <img src="{{ asset($post->image ?? 'asset/images/earbuds.jpg') }}" alt="{{ $post->title }}" class="rounded-3 shadow-sm border" style="width: 44px; height: 44px; object-fit: cover;" />
                                            <div>
                                                <a href="{{ route('opportunity.show', $post->id) }}" target="_blank" class="fw-bold text-dark text-decoration-none hover-primary mb-0 d-block">{{ $post->title }} <i class="fas fa-arrow-up-right-from-square extra-small text-muted ms-1"></i></a>
                                                <small class="text-muted">Import: {{ $post->expected_import_days }} Days</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center gap-1">
                                            @if(count($galleryList) > 0)
                                                @foreach(array_slice($galleryList, 0, 3) as $gImg)
                                                    <img src="{{ asset($gImg) }}" alt="Gallery" class="rounded border" style="width: 28px; height: 28px; object-fit: cover;" />
                                                @endforeach
                                                @if(count($galleryList) > 3)
                                                    <span class="badge bg-light text-muted border rounded-circle" style="width:28px;height:28px;display:inline-flex;align-items:center;justify-content:center;">+{{ count($galleryList) - 3 }}</span>
                                                @endif
                                            @else
                                                <span class="text-muted extra-small">No gallery</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <div class="fw-semibold text-dark">{{ number_format($post->total_quantity) }} pcs</div>
                                        <small class="text-muted">&#2547;{{ number_format($post->unit_cost) }}/pc</small>
                                    </td>
                                    <td class="fw-bold text-dark">&#2547;{{ number_format($post->target_amount) }}</td>
                                    <td>
                                        <div class="fw-bold text-success">&#2547;{{ number_format($post->current_invested_amount) }}</div>
                                        <div class="progress mt-1 rounded-pill" style="height: 6px; width: 110px;">
                                            <div class="progress-bar bg-success rounded-pill" style="width: {{ $post->funded_percentage }}%"></div>
                                        </div>
                                    </td>
                                    <td class="fw-bold text-primary">&#2547;{{ number_format($post->profit_per_unit) }}</td>
                                    <td>
                                        @php
                                            $badgeClass = match($post->status) {
                                                'active' => 'bg-success-subtle text-success border-success',
                                                'upcoming' => 'bg-info-subtle text-info border-info',
                                                'imported' => 'bg-primary-subtle text-primary border-primary',
                                                'sold_out' => 'bg-warning-subtle text-warning border-warning',
                                                'completed' => 'bg-secondary-subtle text-secondary border-secondary',
                                                default => 'bg-light text-dark',
                                            };
                                        @endphp
                                        <span class="badge {{ $badgeClass }} border rounded-pill px-2.5 py-1 small fw-semibold text-uppercase">
                                            {{ str_replace('_', ' ', $post->status) }}
                                        </span>
                                    </td>
                                    <td class="text-end pe-4">
                                        <button type="button" class="btn btn-sm btn-outline-primary rounded-2 me-1 edit-post-btn"
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
                                            data-image="{{ asset($post->image ?? 'asset/images/earbuds.jpg') }}"
                                            data-gallery_images='@json($post->gallery_images ?? [])'
                                            title="Edit Post">
                                            <i class="fas fa-pen"></i>
                                        </button>
                                        <form action="{{ route('posts.destroy', $post->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this post?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger rounded-2" title="Delete Post">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-5 text-muted">No investment posts found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        @if($posts->hasPages())
            <div class="mt-4">
                {{ $posts->links() }}
            </div>
        @endif
    </div>

    <!-- CREATE POST MODAL -->
    <div class="modal fade" id="createPostModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
                <div class="modal-header bg-primary text-white py-3">
                    <h5 class="modal-title fw-bold fs-6"><i class="fas fa-plus-circle me-1.5"></i> Create New Investment Post</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body p-4">
                        <div class="row g-3">
                            <div class="col-md-8">
                                <label class="form-label fw-semibold small">Product Title <span class="text-danger">*</span></label>
                                <input type="text" name="title" class="form-control form-control-sm" placeholder="e.g. Wireless Earbuds" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold small">Status <span class="text-danger">*</span></label>
                                <select name="status" class="form-select form-select-sm" required>
                                    <option value="active" selected>Active</option>
                                    <option value="upcoming">Upcoming</option>
                                    <option value="imported">Imported</option>
                                    <option value="sold_out">Sold Out</option>
                                    <option value="completed">Completed</option>
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-semibold small">Total Quantity (pcs) <span class="text-danger">*</span></label>
                                <input type="number" name="total_quantity" id="create_total_quantity" class="form-control form-control-sm" value="1000" min="1" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold small">Cost per Piece (&#2547;) <span class="text-danger">*</span></label>
                                <input type="number" name="unit_cost" id="create_unit_cost" class="form-control form-control-sm" value="500" step="0.01" min="0.01" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold small">Profit per Piece (&#2547;) <span class="text-danger">*</span></label>
                                <input type="number" name="profit_per_unit" id="create_profit_per_unit" class="form-control form-control-sm" value="50" step="0.01" min="0" required>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-semibold small">Import Time (Days) <span class="text-danger">*</span></label>
                                <input type="number" name="expected_import_days" class="form-control form-control-sm" value="25" min="1" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold small">Target Amount (&#2547;)</label>
                                <input type="number" name="target_amount" id="create_target_amount" class="form-control form-control-sm" value="500000" placeholder="Auto-calculated (Qty * Cost)">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold small">Min Investment (&#2547;) <span class="extra-small text-muted">(80%)</span></label>
                                <input type="number" name="min_investment_amount" id="create_min_investment_amount" class="form-control form-control-sm" value="400000" min="1" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold small">Main Product Cover Image</label>
                                <input type="file" name="image" class="form-control form-control-sm" accept="image/*">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold small">Product Gallery Images (Multiple)</label>
                                <input type="file" name="gallery_images[]" class="form-control form-control-sm" accept="image/*" multiple>
                                <small class="text-muted extra-small">Upload multiple thumbnails for product gallery.</small>
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-semibold small">Description</label>
                                <textarea name="description" id="create_description" class="form-control form-control-sm"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer bg-light py-2.5 px-4 border-top">
                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary btn-sm px-4 fw-semibold">Save Post</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- EDIT POST MODAL -->
    <div class="modal fade" id="editPostModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
                <div class="modal-header bg-primary text-white py-3">
                    <h5 class="modal-title fw-bold fs-6"><i class="fas fa-pen-to-square me-1.5"></i> Edit Investment Post</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="editPostForm" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="modal-body p-4">
                        <div class="row g-3">
                            <div class="col-md-8">
                                <label class="form-label fw-semibold small">Product Title <span class="text-danger">*</span></label>
                                <input type="text" name="title" id="edit_title" class="form-control form-control-sm" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold small">Status <span class="text-danger">*</span></label>
                                <select name="status" id="edit_status" class="form-select form-select-sm" required>
                                    <option value="active">Active</option>
                                    <option value="upcoming">Upcoming</option>
                                    <option value="imported">Imported</option>
                                    <option value="sold_out">Sold Out</option>
                                    <option value="completed">Completed</option>
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-semibold small">Total Quantity (pcs) <span class="text-danger">*</span></label>
                                <input type="number" name="total_quantity" id="edit_total_quantity" class="form-control form-control-sm" min="1" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold small">Cost per Piece (&#2547;) <span class="text-danger">*</span></label>
                                <input type="number" name="unit_cost" id="edit_unit_cost" class="form-control form-control-sm" step="0.01" min="0.01" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold small">Profit per Piece (&#2547;) <span class="text-danger">*</span></label>
                                <input type="number" name="profit_per_unit" id="edit_profit_per_unit" class="form-control form-control-sm" step="0.01" min="0" required>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-semibold small">Import Time (Days) <span class="text-danger">*</span></label>
                                <input type="number" name="expected_import_days" id="edit_expected_import_days" class="form-control form-control-sm" min="1" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold small">Target Amount (&#2547;)</label>
                                <input type="number" name="target_amount" id="edit_target_amount" class="form-control form-control-sm">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold small">Min Investment (&#2547;)</label>
                                <input type="number" name="min_investment_amount" id="edit_min_investment_amount" class="form-control form-control-sm" min="1" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold small">Change Cover Image</label>
                                <input type="file" name="image" class="form-control form-control-sm" accept="image/*">
                                <div class="mt-2" id="edit_cover_preview_wrap">
                                    <span class="extra-small text-muted d-block mb-1">Current Cover Image:</span>
                                    <img id="edit_cover_preview" src="" alt="Cover Image" class="rounded-3 border shadow-sm" style="max-height: 75px; width: auto; object-fit: cover;" />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold small">Add Additional Gallery Images</label>
                                <input type="file" name="gallery_images[]" class="form-control form-control-sm" accept="image/*" multiple>
                            </div>

                            <div class="col-12" id="edit_gallery_wrap">
                                <label class="form-label fw-semibold small d-block">Current Gallery Images</label>
                                <div id="edit_gallery_container" class="gallery-thumb-wrap"></div>
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-semibold small">Description</label>
                                <textarea name="description" id="edit_description" class="form-control form-control-sm"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer bg-light py-2.5 px-4 border-top">
                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary btn-sm px-4 fw-semibold">Update Post</button>
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
                    form.action = '{{ route("posts.index") }}/' + id;

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
