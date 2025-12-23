<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <title>{{ !empty($settings) ? $settings->name : 'Krishna Minerals' }} {{ !empty($settings) ? ' - '.$settings->tagline : 'Krishna Minerals' }}</title>
	<!-- Required meta tags -->
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="csrf-token" content="{{ csrf_token() }}">
    
	<!--favicon-->
	<link rel="icon" href="{{(!empty($settings) && !empty($settings->favicon)) ? asset('storage/uploads/' . $settings->favicon) : '' }}" type="image/png"/>
	<!--plugins-->
	<link href="{{ asset('build/assets/plugins/vectormap/jquery-jvectormap-2.0.2.css') }}" rel="stylesheet"/>
	<link href="{{ asset('build/assets/plugins/simplebar/css/simplebar.css') }}" rel="stylesheet" />
	<link href="{{ asset('build/assets/plugins/perfect-scrollbar/css/perfect-scrollbar.css') }}" rel="stylesheet" />
	<link href="{{ asset('build/assets/plugins/metismenu/css/metisMenu.min.css') }}" rel="stylesheet"/>
	<!-- loader-->
	<link href="{{ asset('build/assets/css/pace.min.css') }}" rel="stylesheet"/>
	<script src="{{ asset('build/assets/js/pace.min.js')}}"></script>
	<!-- Bootstrap CSS -->
	<link href="{{ asset('build/assets/css/bootstrap.min.css') }}" rel="stylesheet">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" />
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
	<link href="{{ asset('build/assets/css/bootstrap-extended.css') }}" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
	<link href="{{ asset('build/assets/css/app.css') }}" rel="stylesheet">
	<link href="{{ asset('build/assets/css/icons.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
	<!-- Theme Style CSS -->
	<link rel="stylesheet" href="{{ asset('build/assets/css/dark-theme.css') }}"/>
	<link rel="stylesheet" href="{{ asset('build/assets/css/semi-dark.css') }}"/>
	<link rel="stylesheet" href="{{ asset('build/assets/css/header-colors.css') }}"/>

</head>
<body>
    @auth
        @include('partials.sidebar')
    @endauth
    <div id="app">
        <main class="py-4">
            <div class="row justify-content-center">
                <div class="col-md-12">
                    @yield('content')
                </div>
            </div>
        </main>        
    </div>

    
        
	<!--start switcher-->
	<div class="switcher-wrapper">
		<div class="switcher-btn"> <i class='bx bx-cog bx-spin'></i>
		</div>
		<div class="switcher-body">
			<div class="d-flex align-items-center">
				<h5 class="mb-0 text-uppercase">Theme Customizer</h5>
				<button type="button" class="btn-close ms-auto close-switcher" aria-label="Close"></button>
			</div>
			<hr/>
			<h6 class="mb-0">Theme Styles</h6>
			<hr/>
			<div class="d-flex align-items-center justify-content-between">
				<div class="form-check">
					<input class="form-check-input" type="radio" name="flexRadioDefault" id="lightmode" checked>
					<label class="form-check-label" for="lightmode">Light</label>
				</div>
				<div class="form-check">
					<input class="form-check-input" type="radio" name="flexRadioDefault" id="darkmode">
					<label class="form-check-label" for="darkmode">Dark</label>
				</div>
				<div class="form-check">
					<input class="form-check-input" type="radio" name="flexRadioDefault" id="semidark">
					<label class="form-check-label" for="semidark">Semi Dark</label>
				</div>
			</div>
			<hr/>
			<div class="form-check">
				<input class="form-check-input" type="radio" id="minimaltheme" name="flexRadioDefault">
				<label class="form-check-label" for="minimaltheme">Minimal Theme</label>
			</div>
			<hr/>
			<h6 class="mb-0">Header Colors</h6>
			<hr/>
			<div class="header-colors-indigators">
				<div class="row row-cols-auto g-3">
					<div class="col">
						<div class="indigator headercolor1" id="headercolor1"></div>
					</div>
					<div class="col">
						<div class="indigator headercolor2" id="headercolor2"></div>
					</div>
					<div class="col">
						<div class="indigator headercolor3" id="headercolor3"></div>
					</div>
					<div class="col">
						<div class="indigator headercolor4" id="headercolor4"></div>
					</div>
					<div class="col">
						<div class="indigator headercolor5" id="headercolor5"></div>
					</div>
					<div class="col">
						<div class="indigator headercolor6" id="headercolor6"></div>
					</div>
					<div class="col">
						<div class="indigator headercolor7" id="headercolor7"></div>
					</div>
					<div class="col">
						<div class="indigator headercolor8" id="headercolor8"></div>
					</div>
				</div>
			</div>
			<hr/>
			<h6 class="mb-0">Sidebar Colors</h6>
			<hr/>
			<div class="header-colors-indigators">
				<div class="row row-cols-auto g-3">
					<div class="col">
						<div class="indigator sidebarcolor1" id="sidebarcolor1"></div>
					</div>
					<div class="col">
						<div class="indigator sidebarcolor2" id="sidebarcolor2"></div>
					</div>
					<div class="col">
						<div class="indigator sidebarcolor3" id="sidebarcolor3"></div>
					</div>
					<div class="col">
						<div class="indigator sidebarcolor4" id="sidebarcolor4"></div>
					</div>
					<div class="col">
						<div class="indigator sidebarcolor5" id="sidebarcolor5"></div>
					</div>
					<div class="col">
						<div class="indigator sidebarcolor6" id="sidebarcolor6"></div>
					</div>
					<div class="col">
						<div class="indigator sidebarcolor7" id="sidebarcolor7"></div>
					</div>
					<div class="col">
						<div class="indigator sidebarcolor8" id="sidebarcolor8"></div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!--end switcher-->
	<!-- Bootstrap JS -->
	<script src="{{ asset('build/assets/js/jquery.min.js')}}"></script>
	<script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
	<script src="{{ asset('build/assets/js/bootstrap.bundle.min.js')}}"></script>
	<!--plugins-->
	<script src="{{ asset('build/assets/plugins/simplebar/js/simplebar.min.js')}}"></script>
	<script src="{{ asset('build/assets/plugins/metismenu/js/metisMenu.min.js')}}"></script>
	<script src="{{ asset('build/assets/plugins/perfect-scrollbar/js/perfect-scrollbar.js')}}"></script>
	<script src="{{ asset('build/assets/plugins/vectormap/jquery-jvectormap-2.0.2.min.js')}}"></script>
    <script src="{{ asset('build/assets/plugins/vectormap/jquery-jvectormap-world-mill-en.js')}}"></script>
	<script src="{{ asset('build/assets/plugins/chartjs/js/chart.js')}}"></script>
	<!--app JS-->
	<script src="{{ asset('build/assets/js/app.js')}}"></script>
	<script>
		new PerfectScrollbar(".app-container")
	</script>

    <!-- Allow pages to push custom scripts -->
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
		
		$(document).ready(function () {
			$('.js-select2').select2({
				theme: 'bootstrap-5',
				width: '100%'
			});
		});
    </script>

    @stack('scripts')
    
    <!-- Notification Trigger Script -->
    <script>
        // Trigger notification check every minute
        @auth
        setInterval(function() {
            $.ajax({
                url: '{{ route('notifications.trigger') }}',
                method: 'GET',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    console.log('Notification check completed:', response);
                },
                error: function(xhr) {
                    console.log('Error triggering notifications:', xhr.responseText);
                }
            });
        }, 60000); // Every 60 seconds
        @endauth
    </script>

    <!-- Search Modal -->
    <div class="modal fade" id="SearchModal" tabindex="-1" aria-labelledby="SearchModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="SearchModalLabel">Search Challans</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group mb-3">
                                <label for="searchModule">Select Module:</label>
                                <select class="form-select" id="searchModule">
                                    <option value="sales">Sales</option>
                                    <option value="purchase">Purchase</option>
                                    <option value="blasting">Blasting</option>
                                    <option value="drilling">Drilling</option>
                                </select>
                            </div>
                            
                            <div class="form-group mb-3">
                                <label for="searchType">Search By:</label>
                                <select class="form-select" id="searchType">
                                    <option value="challan">Challan Number</option>
                                    <option value="transporter">Transporter</option>
                                    <option value="vehicle">Vehicle Number</option>
                                    <option value="date">Date</option>
                                    <option value="date_range">Date Range</option>
                                </select>
                            </div>
                            
                            <div class="form-group mb-3" id="challanSearchGroup">
                                <label for="challanNumber">Challan Number:</label>
                                <input type="text" class="form-control" id="challanNumber" placeholder="Enter challan number">
                            </div>
                            
                            <div class="form-group mb-3" id="transporterSearchGroup" style="display: none;">
                                <label for="transporterName">Transporter Name:</label>
                                <input type="text" class="form-control" id="transporterName" placeholder="Enter transporter name">
                            </div>
                            
                            <div class="form-group mb-3" id="vehicleSearchGroup" style="display: none;">
                                <label for="vehicleNumber">Vehicle Number:</label>
                                <input type="text" class="form-control" id="vehicleNumber" placeholder="Enter vehicle number">
                            </div>
                            
                            <div class="form-group mb-3" id="dateSearchGroup" style="display: none;">
                                <label for="searchDate">Date:</label>
                                <input type="date" class="form-control" id="searchDate">
                            </div>
                            
                            <div class="form-group mb-3" id="dateRangeSearchGroup" style="display: none;">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label for="dateFrom">From Date:</label>
                                        <input type="date" class="form-control" id="dateFrom">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="dateTo">To Date:</label>
                                        <input type="date" class="form-control" id="dateTo">
                                    </div>
                                </div>
                            </div>
                            
                            <button type="button" class="btn btn-primary" id="searchBtn">Search</button>
                        </div>
                    </div>
                    
                    <div class="row mt-4">
                        <div class="col-md-12">
                            <div id="searchResults"></div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        $(document).ready(function() {
            // Handle search type change
            $('#searchType').change(function() {
                var searchType = $(this).val();
                
                // Hide all search groups
                $('#challanSearchGroup, #transporterSearchGroup, #vehicleSearchGroup, #dateSearchGroup, #dateRangeSearchGroup').hide();
                
                // Show the relevant search group
                if (searchType === 'challan') {
                    $('#challanSearchGroup').show();
                } else if (searchType === 'transporter') {
                    $('#transporterSearchGroup').show();
                } else if (searchType === 'vehicle') {
                    $('#vehicleSearchGroup').show();
                } else if (searchType === 'date') {
                    $('#dateSearchGroup').show();
                } else if (searchType === 'date_range') {
                    $('#dateRangeSearchGroup').show();
                }
            });
            
            // Handle search button click
            $('#searchBtn').click(function() {
                var module = $('#searchModule').val();
                var searchType = $('#searchType').val();
                var searchData = {};
                
                // Get search data based on search type
                if (searchType === 'challan') {
                    searchData.challan = $('#challanNumber').val();
                } else if (searchType === 'transporter') {
                    searchData.transporter = $('#transporterName').val();
                } else if (searchType === 'vehicle') {
                    searchData.vehicle = $('#vehicleNumber').val();
                } else if (searchType === 'date') {
                    searchData.date = $('#searchDate').val();
                } else if (searchType === 'date_range') {
                    searchData.date_from = $('#dateFrom').val();
                    searchData.date_to = $('#dateTo').val();
                }
                
                // Perform AJAX search
                $.ajax({
                    url: '/search-challans',
                    method: 'POST',
                    data: {
                        module: module,
                        searchType: searchType,
                        searchData: searchData
                    },
                    success: function(response) {
                        $('#searchResults').html(response);
                    },
                    error: function(xhr) {
                        var errorMessage = 'Error occurred while searching.';
                        if (xhr.responseJSON && xhr.responseJSON.error) {
                            errorMessage = xhr.responseJSON.error;
                        } else if (xhr.responseText) {
                            errorMessage = xhr.responseText;
                        }
                        $('#searchResults').html('<div class="alert alert-danger">' + errorMessage + '</div>');
                    }
                });
            });
        });
    </script>
</body>
</html>