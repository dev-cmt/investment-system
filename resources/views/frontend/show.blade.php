@extends('frontend.layouts.master')

@push('styles')
    <style>
        .thumb-item {
            border: 2px solid transparent;
            transition: all 0.2s ease;
            cursor: pointer;
            background: #fff;
        }

        .thumb-item.active,
        .thumb-item:hover {
            border-color: #5E46E8 !important;
        }

        .opt-card {
            transition: all 0.2s ease;
            background: #fff;
            border: 1px solid #e5e7eb;
            cursor: pointer;
            padding: 1rem 1rem 0.9rem;
            border-radius: 1rem;
        }

        .opt-card.active-opt {
            border-color: #5E46E8 !important;
            background: linear-gradient(180deg, #f7f4ff 0%, #f3f8ff 100%);
            box-shadow: 0 10px 24px rgba(94, 70, 232, 0.1);
        }

        .opt-card:hover {
            border-color: rgba(94, 70, 232, 0.5);
            transform: translateY(-1px);
        }

        .opt-icon {
            width: 42px;
            height: 42px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(94, 70, 232, 0.08);
            color: #5E46E8;
            font-size: 1rem;
            flex-shrink: 0;
        }

        .opt-amount {
            font-weight: 800;
            color: #1f2937;
            white-space: nowrap;
        }

        .invest-guide {
            border: 1px dashed rgba(94, 70, 232, 0.32);
            background: rgba(94, 70, 232, 0.03);
        }

        .btn-purple {
            background-color: #5E46E8;
            color: #ffffff;
            border: none;
            transition: all 0.2s ease;
        }

        .btn-purple:hover {
            background-color: #4B36C8;
            color: #ffffff;
        }

        .extra-small {
            font-size: 0.75rem;
        }
    </style>
@endpush

@section('content')
    <!-- Breadcrumb -->
    <div class="bg-light py-3 border-bottom">
        <div class="container">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 small">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none text-muted"><i
                                class="fas fa-home me-1"></i>Home</a></li>
                    <li class="breadcrumb-item active fw-semibold text-dark" aria-current="page">Opportunity Details</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="py-5 bg-light">
        <div class="container">
            <div class="row g-4">
                <!-- Left Column: Product Image & Gallery -->
                <div class="col-lg-4">
                    @php
                        $galleryUrls = $post->gallery_image_urls;
                        $totalGallery = count($galleryUrls);
                    @endphp
                    <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-3 position-relative">
                        <div class="p-3 text-center bg-white">
                            @foreach ($galleryUrls as $gIdx => $gUrl)
                                <a href="{{ $gUrl }}" class="glightbox {{ $gIdx > 0 ? 'd-none' : '' }}"
                                    data-gallery="product-details-{{ $post->id }}" @if($gIdx === 0) id="mainProductAnchor" @endif>
                                    @if ($gIdx === 0)
                                        <img src="{{ $gUrl }}" alt="{{ $post->title }}"
                                            id="mainProductImage" class="img-fluid rounded-3"
                                            style="max-height: 320px; width: 100%; object-fit: contain;" />
                                    @endif
                                </a>
                            @endforeach
                        </div>

                        <!-- Circular Active Counter Badge -->
                        <div class="position-absolute bottom-0 end-0 mb-3 me-3 d-flex align-items-center justify-content-center bg-dark bg-opacity-75 text-white rounded-circle shadow-lg border border-white border-opacity-25"
                             id="imageCounter"
                             style="width: 46px; height: 46px; backdrop-filter: blur(4px);">
                            <span class="extra-small fw-bold lh-1 text-center" id="counterText">
                                1/{{ $totalGallery }}
                            </span>
                        </div>
                    </div>

                    <!-- Thumbnail Gallery Strip -->
                    <div class="d-flex gap-2 flex-wrap">
                        @foreach ($galleryUrls as $idx => $gUrl)
                            <div class="border rounded-3 p-1 bg-white text-center thumb-item {{ $idx === 0 ? 'active' : '' }}"
                                style="width: 65px; height: 60px;" onclick="changeMainImage('{{ $gUrl }}', this, {{ $idx + 1 }}, {{ $totalGallery }})">
                                <img src="{{ $gUrl }}" alt="Thumb {{ $idx + 1 }}" class="img-fluid rounded"
                                    style="max-height: 50px; width: 100%; object-fit: contain;" />
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Middle Column: Product Details & Description -->
                <div class="col-lg-5">
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <h3 class="fw-bold text-dark mb-0">{{ $post->title }}</h3>
                        <span class="badge {{ $post->type === 'Local' ? 'bg-warning-subtle text-dark border border-warning' : ($post->type === 'Manufacture' ? 'bg-info-subtle text-dark border border-info' : 'bg-success-subtle text-success border border-success') }} rounded-pill px-3 py-1.5 small fw-semibold">
                            <i class="fas {{ $post->type === 'Local' ? 'fa-location-dot' : ($post->type === 'Manufacture' ? 'fa-industry' : 'fa-ship') }} me-1"></i>{{ $post->type ?? 'Import' }}
                        </span>
                    </div>

                    <div class="text-muted small mb-4 lh-lg">
                        {!! $post->description ?? '' !!}
                    </div>
                </div>

                <!-- Right Column: Investment Summary Card -->
                <div class="col-lg-3">
                    <div
                        class="card border-0 shadow-sm rounded-4 p-4 bg-white h-100 d-flex flex-column justify-content-between">
                        <div>
                            <h5 class="fw-bold text-dark mb-3 pb-2 border-bottom">Investment Summary</h5>

                            <ul class="list-unstyled mb-4 d-flex flex-column gap-3 small">
                                <li class="d-flex justify-content-between">
                                    <span class="text-muted">Total Investment Required</span>
                                    <strong
                                        class="text-dark fw-bold">&#2547;{{ number_format($post->target_amount) }}</strong>
                                </li>
                                <li class="d-flex justify-content-between">
                                    <span class="text-muted">Required Quantity</span>
                                    <strong class="text-dark">{{ number_format($post->total_quantity) }} pcs</strong>
                                </li>
                                <li class="d-flex justify-content-between">
                                    <span class="text-muted">Cost per Piece</span>
                                    <strong class="text-dark">&#2547;{{ number_format($post->unit_cost) }}</strong>
                                </li>
                                <li class="d-flex justify-content-between">
                                    <span class="text-muted">{{ $post->time_label }}</span>
                                    <strong class="text-dark">{{ $post->expected_import_days }} Days</strong>
                                </li>
                                <li class="d-flex justify-content-between">
                                    <span class="text-muted">Expected Profit</span>
                                    <strong
                                        class="text-success fw-bold">&#2547;{{ number_format($post->profit_per_unit * $post->total_quantity) }}</strong>
                                </li>
                                <li class="d-flex justify-content-between">
                                    <span class="text-muted">Profit Distribution</span>
                                    <strong class="text-dark">{{ $post->msg_profit_payment ?? 'Weekly' }}</strong>
                                </li>
                                <li class="d-flex justify-content-between">
                                    <span class="text-muted">Investor</span>
                                    <strong class="text-primary">Multiple Investors</strong>
                                </li>
                                <li class="d-flex justify-content-between">
                                    <span class="text-muted">Total Members</span>
                                    @auth
                                        <a href="{{ route('investments.index', ['investment_post_id' => $post->id]) }}"
                                           class="fw-bold text-decoration-none" style="color:#1a9e4f;"
                                           title="View all investors">
                                            <i class="fas fa-users me-1" style="font-size:0.75rem;"></i>
                                            {{ $post->member_count ?? $post->investments->whereIn('status', ['pending','active','sold','completed'])->count() }} Member{{ (($post->member_count ?? $post->investments->whereIn('status', ['pending','active','sold','completed'])->count()) != 1) ? 's' : '' }}
                                        </a>
                                    @else
                                        <strong class="text-dark">
                                            <i class="fas fa-users me-1" style="font-size:0.75rem;"></i>
                                            {{ $post->member_count ?? $post->investments->whereIn('status', ['pending','active','sold','completed'])->count() }} Member{{ (($post->member_count ?? $post->investments->whereIn('status', ['pending','active','sold','completed'])->count()) != 1) ? 's' : '' }}
                                        </strong>
                                    @endauth
                                </li>
                            </ul>
                        </div>

                        <div>
                            @php
                                $userBid = auth()->check() ? $post->investments->whereIn('status', ['pending', 'active', 'sold', 'completed'])->where('user_id', auth()->id())->first() : null;
                                $isSoldOut = $post->status === 'sold_out';
                            @endphp

                            @if($userBid)
                                <button type="button" class="btn btn-secondary opacity-75 w-100 py-2.5 fw-bold rounded-3 mb-2" disabled title="You have already placed a bid for this opportunity">
                                    <i class="fas fa-check-circle me-1.5 text-success"></i> Bid Placed ({{ ucfirst($userBid->status) }})
                                </button>
                            @elseif($isSoldOut)
                                <button type="button" class="btn btn-secondary opacity-75 w-100 py-2.5 fw-bold rounded-3 mb-2" disabled title="This opportunity is sold out">
                                    <i class="fas fa-ban me-1.5"></i> Sold Out
                                </button>
                            @else
                                <button type="button" class="btn btn-purple w-100 py-2.5 fw-bold rounded-3 mb-2"
                                    data-bs-toggle="modal" data-bs-target="#investBidModal">
                                    Place Investment Bid
                                </button>
                            @endif

                            <a href="{{ route('home') }}#opportunities"
                                class="btn btn-outline-secondary w-100 py-2 fw-semibold rounded-3 small">
                                Back to Opportunities
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bottom Features Strip -->
            <div class="row g-3 mt-4">
                <div class="col-md-3">
                    <div class="p-3 bg-white rounded-3 shadow-sm d-flex align-items-center gap-3">
                        <div class="rounded-3 gap-2 bg-primary-subtle text-primary"><i
                                class="fas fa-calendar-alt fa-lg"></i></div>
                        <div>
                            <small class="text-muted d-block">{{ $post->time_label }}</small>
                            <strong class="text-dark">{{ $post->expected_import_days }} Days</strong>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="p-3 bg-white rounded-3 shadow-sm d-flex align-items-center gap-3">
                        <div class="rounded-3 gap-2 bg-success-subtle text-success"><i class="fas fa-wallet fa-lg"></i>
                        </div>
                        <div>
                            <small class="text-muted d-block">Profit Payment</small>
                            <strong class="text-dark">{{ $post->msg_profit_payment ?? 'Weekly' }}</strong>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="p-3 bg-white rounded-3 shadow-sm d-flex align-items-center gap-3">
                        <div class="rounded-3 gap-2 bg-info-subtle text-info"><i class="fas fa-arrows-rotate fa-lg"></i>
                        </div>
                        <div>
                            <small class="text-muted d-block">Return Type</small>
                            <strong class="text-dark">Profit + Capital</strong>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="p-3 bg-white rounded-3 shadow-sm d-flex align-items-center gap-3">
                        <div class="rounded-3 gap-2 bg-warning-subtle text-warning"><i class="fas fa-user-shield fa-lg"></i>
                        </div>
                        <div>
                            <small class="text-muted d-block">Investment Type</small>
                            <strong class="text-dark">{{ $post->type ?? 'Import' }}</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- PLACE INVESTMENT BID MODAL -->
    <div class="modal fade" id="investBidModal" tabindex="-1" aria-labelledby="investBidModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
                <div class="modal-header bg-white border-bottom py-3">
                    <h5 class="modal-title fw-bold text-dark" id="investBidModalLabel">
                        Place Investment Bid
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="row g-4">
                        <!-- Left side: Investment options -->
                        <div class="col-md-7">
                            <div class="p-3 bg-light rounded-3 mb-3 border">
                                <span class="text-muted small d-block mb-1">Total Investment Required</span>
                                <h4 class="fw-bold text-dark mb-0">&#2547;{{ number_format($post->target_amount) }}</h4>
                            </div>

                            <label class="fw-semibold text-dark small mb-2 d-block">Choose your investment option</label>

                            <div class="d-flex flex-column gap-2 mb-3">
                                <div class="card opt-card active-opt" id="optCardFull" onclick="selectOpt('full')">
                                    <div class="d-flex justify-content-between align-items-center gap-3">
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="opt-icon"><i class="fas fa-wallet"></i></div>
                                            <div>
                                                <div class="fw-bold text-dark">Full Amount</div>
                                                <small class="text-muted">Become the sole investor for this
                                                    opportunity.</small>
                                            </div>
                                        </div>
                                        <div class="opt-amount">&#2547;{{ number_format($post->target_amount) }}</div>
                                    </div>
                                    <input class="form-check-input d-none" type="radio" name="investOption"
                                        id="optFull" value="full" checked>
                                </div>

                                <div class="card opt-card" id="optCardCustom" onclick="selectOpt('custom')">
                                    <div class="d-flex justify-content-between align-items-center gap-3">
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="opt-icon"><i class="fas fa-sliders-h"></i></div>
                                            <div>
                                                <div class="fw-bold text-dark">Custom Investment</div>
                                                <small class="text-muted">Choose your own amount based on your comfort level.</small>
                                            </div>
                                        </div>
                                        <div class="opt-amount">Min &#2547;{{ number_format($post->min_investment_amount ?? 100) }}</div>
                                    </div>
                                    <input class="form-check-input d-none" type="radio" name="investOption"
                                        id="optCustom" value="custom">
                                </div>
                            </div>

                            <form action="{{ route('investments.store') }}" method="POST">
                                @csrf
                                <input type="hidden" name="investment_post_id" value="{{ $post->id }}">
                                <input type="hidden" name="amount" id="selectedInvestmentAmount"
                                    value="{{ $post->target_amount }}">

                                <div class="mb-3 d-none" id="customAmountWrap">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <label class="form-label fw-semibold text-dark small mb-0">Your investment amount (&#2547;)</label>
                                        <button type="button" class="btn btn-link btn-sm p-0 text-decoration-none extra-small text-primary fw-semibold" id="btnResetMinAmount" title="Reset to minimum investment">
                                            <i class="fas fa-rotate-left me-1"></i> Reset Min
                                        </button>
                                    </div>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light fw-bold">&#2547;</span>
                                        <input type="number" id="customAmountInput" class="form-control"
                                            min="{{ $post->min_investment_amount ?? 100 }}" step="100"
                                            value="{{ $post->min_investment_amount ?? 100 }}" placeholder="Enter amount">
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center mt-1 flex-wrap gap-1">
                                        <small class="text-muted" id="minAmountLabel">
                                            Minimum investment: <strong role="button" class="text-primary text-decoration-underline" id="minAmountClickable" title="Click to apply minimum investment">&#2547;{{ number_format($post->min_investment_amount ?? 100) }}</strong>
                                        </small>
                                        <small id="customAmountValidationMsg" class="text-danger extra-small d-none"></small>
                                    </div>
                                </div>

                                <div class="row g-3 mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold text-dark small">Profit per Piece (&#2547;)</label>
                                        <input type="number" name="per_piece_profit" id="bidProfitPerUnit" class="form-control"
                                            value="{{ number_format($post->profit_per_unit, 2, '.', '') }}" step="0.01" min="0">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold text-dark small">Expected Total Profit (&#2547;)</label>
                                        <input type="number" name="custom_profit" id="bidCustomProfit" class="form-control bg-light"
                                            value="{{ (float) (($post->target_amount / ($post->unit_cost ?: 1)) * $post->profit_per_unit) }}" step="0.01" readonly>
                                        <small class="text-muted extra-small d-block mt-1">Auto-calculated (Cost/pc: &#2547;{{ number_format($post->unit_cost) }}).</small>
                                    </div>
                                </div>

                                @if($userBid)
                                    <div class="alert alert-warning rounded-3 small mb-3">
                                        <i class="fas fa-exclamation-circle me-1"></i> You have already submitted a bid of <strong>&#2547;{{ number_format($userBid->amount) }}</strong> on this opportunity.
                                    </div>
                                    <button type="button" class="btn btn-secondary w-100 py-2.5 fw-bold rounded-3 mb-2" disabled>
                                        Bid Already Placed
                                    </button>
                                @elseif($isSoldOut)
                                    <div class="alert alert-secondary rounded-3 small mb-3">
                                        <i class="fas fa-ban me-1"></i> This opportunity is marked as sold out.
                                    </div>
                                    <button type="button" class="btn btn-secondary w-100 py-2.5 fw-bold rounded-3 mb-2" disabled>
                                        Opportunity Sold Out
                                    </button>
                                @else
                                    <button type="submit" class="btn btn-purple w-100 py-2.5 fw-bold rounded-3 mb-2">
                                        Confirm Investment
                                    </button>
                                @endif
                                <p class="text-muted text-center extra-small mb-0">Multiple investors can place bids for this opportunity.</p>
                            </form>
                        </div>

                        <!-- Right side: Summary box -->
                        <div class="col-md-5">
                            <div class="p-3 bg-light rounded-3 border h-100 d-flex flex-column justify-content-between">
                                <div>
                                    <h6 class="fw-bold text-dark mb-3">Summary</h6>
                                    <ul class="list-unstyled mb-0 d-flex flex-column gap-1 small">
                                        <li class="d-flex justify-content-between">
                                            <span class="text-muted">Product</span>
                                            <strong class="text-dark">{{ $post->title }}</strong>
                                        </li>
                                        <li class="d-flex justify-content-between">
                                            <span class="text-muted">Quantity</span>
                                            <strong class="text-dark">{{ number_format($post->total_quantity) }}
                                                pcs</strong>
                                        </li>
                                        <li class="d-flex justify-content-between">
                                            <span class="text-muted">{{ $post->time_label }}</span>
                                            <strong class="text-dark">{{ $post->expected_import_days }} Days</strong>
                                        </li>
                                        <li class="d-flex justify-content-between">
                                            <span class="text-muted">Profit Distribution</span>
                                            <strong class="text-dark">{{ $post->msg_profit_payment ?? 'Weekly' }}</strong>
                                        </li>
                                        <li class="d-flex justify-content-between border-top pt-2 mt-1">
                                            <span class="text-muted">Total Investment</span>
                                            <strong
                                                class="text-dark fw-bold">&#2547;{{ number_format($post->target_amount) }}</strong>
                                        </li>
                                        <li class="d-flex justify-content-between">
                                            <span class="text-muted">Expected Profit</span>
                                            <strong
                                                class="text-success fw-bold">&#2547;{{ number_format($post->profit_per_unit * $post->total_quantity) }}</strong>
                                        </li>
                                    </ul>
                                </div>

                                <div class="mt-4 p-2 bg-white rounded border text-center extra-small text-muted">
                                    <i class="fas fa-lock text-success me-1"></i> Your Investment is safe and fully
                                    secured.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        const unitCost = {{ (float) ($post->unit_cost ?: 1) }};
        const profitPerUnit = {{ (float) ($post->profit_per_unit ?: 0) }};

        function changeMainImage(src, element, index, total) {
            document.getElementById('mainProductImage').src = src;
            var anchor = document.getElementById('mainProductAnchor');
            if (anchor) anchor.href = src;

            $('.thumb-item').removeClass('active');
            $(element).addClass('active');

            // Update circular active counter badge dynamically
            $('#counterText').text(index + '/' + total);
        }

        const minAmount = {{ (float) ($post->min_investment_amount ?? 100) }};
        const fullAmount = {{ (float) ($post->target_amount ?? 0) }};

        function calcExpectedProfit(amount) {
            if (unitCost <= 0) return 0;
            const currentProfitPerUnit = parseFloat($('#bidProfitPerUnit').val()) || 0;
            const profit = (amount / unitCost) * currentProfitPerUnit;
            return Math.round(profit * 100) / 100;
        }

        function updateCustomAmount(val) {
            const num = Number(val) || 0;
            $('#selectedInvestmentAmount').val(num);
            $('#bidCustomProfit').val(calcExpectedProfit(num));

            if (num > 0 && num < minAmount) {
                $('#customAmountValidationMsg')
                    .removeClass('d-none')
                    .text('Min required: ৳' + minAmount.toLocaleString());
            } else {
                $('#customAmountValidationMsg').addClass('d-none');
            }
        }

        function resetToMin() {
            $('#customAmountInput').val(minAmount);
            updateCustomAmount(minAmount);
        }

        function selectOpt(type) {
            if (type === 'full') {
                $('#optFull').prop('checked', true);
                $('#optCardFull').addClass('active-opt');
                $('#optCardCustom').removeClass('active-opt');
                $('#selectedInvestmentAmount').val(fullAmount);
                $('#customAmountWrap').addClass('d-none');
                $('#bidCustomProfit').val(calcExpectedProfit(fullAmount));
            } else {
                $('#optCustom').prop('checked', true);
                $('#optCardCustom').addClass('active-opt');
                $('#optCardFull').removeClass('active-opt');
                $('#customAmountWrap').removeClass('d-none');

                let val = Number($('#customAmountInput').val());
                if (!val || val <= 0) {
                    val = minAmount;
                    $('#customAmountInput').val(val);
                }
                updateCustomAmount(val);
            }
        }

        $('#customAmountInput').on('input change keyup', function() {
            const customValue = $(this).val();
            updateCustomAmount(customValue);
        });

        $('#btnResetMinAmount, #minAmountClickable').on('click', function(e) {
            e.preventDefault();
            resetToMin();
        });

        $('#bidProfitPerUnit').on('input change keyup', function() {
            const currentAmount = Number($('#selectedInvestmentAmount').val()) || 0;
            $('#bidCustomProfit').val(calcExpectedProfit(currentAmount));
        });
    </script>
@endpush
