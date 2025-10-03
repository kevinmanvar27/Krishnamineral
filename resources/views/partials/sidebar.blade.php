
	<!--wrapper-->
	<div class="wrapper">
		<!--sidebar wrapper -->
		<div class="sidebar-wrapper" data-simplebar="true">
			<div class="sidebar-header">
				<a href="{{ route('home') }}" class="b-brand">
					<!-- Full logo (expanded sidebar) -->
					<div class="logo-full">
						@if(!empty($settings) && !empty($settings->logo))
							<img src="{{ asset('storage/uploads/' . $settings->logo) }}" alt="Logo" class="logo-icon" style="max-height:40px; width: 150px;">
						@else
							<h4 class="logo-text">{{ $settings->name ?? '' }}</h4>
						@endif
					</div>

					<!-- Small logo (collapsed sidebar) -->
					<div class="logo-collapsed">
						@if(!empty($settings) && !empty($settings->favicon))
							<img src="{{ asset('storage/uploads/' . $settings->favicon) }}" alt="Logo" class="logo-icon" style="max-height:40px; width: 40px;">
						@else
							<h4 class="logo-text">L</h4>
						@endif
					</div>
				</a>
				<div class="toggle-icon ms-auto"><i class='bx bx-arrow-back'></i></div>
			</div>

			<ul class="metismenu" id="menu">
				@php
					$canEmployees = auth()->user()->can('view-employees') ||
									auth()->user()->can('add-employee') ||
									auth()->user()->can('edit-employees');
				@endphp
                @if($canEmployees)
                    <li>
                        <a href="javascript:;" class="has-arrow">
                            <div class="parent-icon"><i class='bx bx-user'></i>
                            </div>
                            <div class="menu-title">Employees</div>
                        </a>
                        <ul>
                            @can('view-employees')
                                <li> 
                                    <a href="{{ route('users.index') }}"><i class='bx bx-radio-circle'></i>View Employees</a>
                                </li>
                            @endcan
                            @can('add-employee')
                                <li>
                                    <a href="{{ route('users.create') }}"><i class='bx bx-radio-circle'></i>Add Employee</a>
                                </li>
                            @endcan
							@can('edit-employees')
							<li>
								<a href="{{ route('users.editIndex') }}"><i class='bx bx-radio-circle'></i>Edit Employees</a>
							</li>
							@endcan
                        </ul>
                    </li>
				@endif

				@php
					$canSales = auth()->user()->can('view-sales') ||
									auth()->user()->can('add-sale') ||
									auth()->user()->can('edit-sales')||
                                    auth()->user()->can('pending-load-sales');
				@endphp
				@if($canSales)
                    <li>
                        <a href="javascript:;" class="has-arrow">
                            <div class="parent-icon"><i class='bx bx-purchase-tag-alt'></i>
                            </div>
                            <div class="menu-title">Sales</div>
                        </a>
                        <ul>
                            @can('view-sales')
                                <li> 
                                    <a href="{{ route('sales.index') }}"><i class='bx bx-radio-circle'></i>View Sales</a>
                                </li>
                            @endcan
                            @can('add-sale')
                                <li>
                                    <a href="{{ route('sales.create') }}"><i class='bx bx-radio-circle'></i>Add Sale</a>
                                </li>
                            @endcan
                            @can('edit-sales')
                                <li>
                                    <a href="{{ route('sales.editIndex') }}"><i class='bx bx-radio-circle'></i>Edit Sales</a>
                                </li>
                            @endcan
                            @can('pending-load-sales')
                                <li>
                                    <a href="{{ route('sales.pendingLoad') }}"><i class='bx bx-radio-circle'></i>Pending Loads</a>
                                </li>
                            @endcan
                        </ul>
                    </li>
				@endif

				@php
					$canMaterials = auth()->user()->can('view-materials') ||
									auth()->user()->can('add-material') ||
									auth()->user()->can('edit-materials');
				@endphp
				@if($canMaterials)
                    <li>
                        <a href="javascript:;" class="has-arrow">
                            <div class="parent-icon"><i class='bx bx-circle'></i>
                            </div>
                            <div class="menu-title">Materials</div>
                        </a>
                        <ul>
                            @can('view-materials')
                                <li> 
                                    <a href="{{ route('materials.index') }}"><i class='bx bx-radio-circle'></i>View Materials</a>
                                </li>
                            @endcan
                            @can('add-material')
                                <li>
                                    <a href="{{ route('materials.create') }}"><i class='bx bx-radio-circle'></i>Add Material</a>
                                </li>
                            @endcan
                            @can('edit-materials')
                                <li>
                                    <a href="{{ route('materials.editIndex') }}"><i class='bx bx-radio-circle'></i>Edit Materials</a>
                                </li>
                            @endcan
                        </ul>
                    </li>
				@endif

				@php
					$canPlaces = auth()->user()->can('view-places') ||
									auth()->user()->can('add-place') ||
									auth()->user()->can('edit-places');
				@endphp
				@if($canPlaces)
                    <li>
                        <a href="javascript:;" class="has-arrow">
                            <div class="parent-icon"><i class='bx bx-map'></i>
                            </div>
                            <div class="menu-title">Places</div>
                        </a>
                        <ul>
                            @can('view-places')
                                <li> 
                                    <a href="{{ route('places.index') }}"><i class='bx bx-radio-circle'></i>View Places</a>
                                </li>
                            @endcan
                            @can('add-place')
                                <li>
                                    <a href="{{ route('places.create') }}"><i class='bx bx-radio-circle'></i>Add Place</a>
                                </li>
                            @endcan
                            @can('edit-places')
                                <li>
                                    <a href="{{ route('places.editIndex') }}"><i class='bx bx-radio-circle'></i>Edit Places</a>
                                </li>
                            @endcan
                        </ul>
                    </li>
				@endif

				@php
					$canVehicles = auth()->user()->can('view-vehicles') ||
									auth()->user()->can('add-vehicle') ||
									auth()->user()->can('edit-vehicles');
				@endphp
				@if($canVehicles)
                    <li>
                        <a href="javascript:;" class="has-arrow">
                            <div class="parent-icon"><i class='bx bxs-truck'></i>
                            </div>
                            <div class="menu-title">Vehicles</div>
                        </a>
                        <ul>
                            @can('view-vehicles')
                                <li> 
                                    <a href="{{ route('vehicle.index') }}"><i class='bx bx-radio-circle'></i>View Vehicles</a>
                                </li>
                            @endcan
                            @can('add-vehicle')
                                <li>
                                    <a href="{{ route('vehicle.create') }}"><i class='bx bx-radio-circle'></i>Add Vehicle</a>
                                </li>
                            @endcan
                            @can('edit-vehicles')
                                <li>
                                    <a href="{{ route('vehicles.editIndex') }}"><i class='bx bx-radio-circle'></i>Edit Vehicles</a>
                                </li>
                            @endcan
                        </ul>
                    </li>
				@endif

				@php
					$canRoyalty = auth()->user()->can('view-royalty') ||
									auth()->user()->can('add-royalty') ||
									auth()->user()->can('edit-royalty');
				@endphp
				@if($canRoyalty)
                    <li>
                        <a href="javascript:;" class="has-arrow">
                            <div class="parent-icon"><i class='bx bx-crown'></i>
                            </div>
                            <div class="menu-title">Royalty</div>
                        </a>
                        <ul>
                            @can('view-royalty')
                                <li> 
                                    <a href="{{ route('royalty.index') }}"><i class='bx bx-radio-circle'></i>View Royalty</a>
                                </li>
                            @endcan
                            @can('add-royalty')
                                <li>
                                    <a href="{{ route('royalty.create') }}"><i class='bx bx-radio-circle'></i>Add Royalty</a>
                                </li>
                            @endcan
                            @can('edit-royalty')
                                <li>
                                    <a href="{{ route('royalty.editIndex') }}"><i class='bx bx-radio-circle'></i>Edit Royalty</a>
                                </li>
                            @endcan
                        </ul>
                    </li>
				@endif

				@php
					$canLoading = auth()->user()->can('view-loading') ||
									auth()->user()->can('add-loading') ||
									auth()->user()->can('edit-loading');
				@endphp
				@if($canLoading)
                    <li>
                        <a href="javascript:;" class="has-arrow">
                            <div class="parent-icon"><i class='bx bx-package'></i>
                            </div>
                            <div class="menu-title">Loading</div>
                        </a>
                        <ul>
                            @can('view-loading')
                                <li> 
                                    <a href="{{ route('loading.index') }}"><i class='bx bx-radio-circle'></i>View Loading</a>
                                </li>
                            @endcan
                            @can('add-loading')
                                <li>
                                    <a href="{{ route('loading.create') }}"><i class='bx bx-radio-circle'></i>Add Loading</a>
                                </li>
                            @endcan
                            @can('edit-loading')
                                <li>
                                    <a href="{{ route('loading.editIndex') }}"><i class='bx bx-radio-circle'></i>Edit Loading</a>
                                </li>
                            @endcan
                        </ul>
                    </li>
				@endif

				@php
					$canDriver = auth()->user()->can('view-driver') ||
									auth()->user()->can('add-driver') ||
									auth()->user()->can('edit-driver');
				@endphp
				@if($canDriver)
                    <li>
                        <a href="javascript:;" class="has-arrow">
                            <div class="parent-icon"><i class='bx bxs-truck'></i>
                            </div>
                            <div class="menu-title">Driver</div>
                        </a>
                        <ul>
                            @can('view-driver')
                                <li> 
                                    <a href="{{ route('driver.index') }}"><i class='bx bx-radio-circle'></i>View Driver</a>
                                </li>
                            @endcan
                            @can('add-driver')
                                <li>
                                    <a href="{{ route('driver.create') }}"><i class='bx bx-radio-circle'></i>Add Driver</a>
                                </li>
                            @endcan
                            @can('edit-driver')
                                <li>
                                    <a href="{{ route('driver.editIndex') }}"><i class='bx bx-radio-circle'></i>Edit Driver</a>
                                </li>
                            @endcan
                        </ul>
                    </li>
				@endif

				@php
					$canParty = auth()->user()->can('view-party') ||
									auth()->user()->can('add-party') ||
									auth()->user()->can('edit-party');
				@endphp
				@if($canParty)
                    <li>
                        <a href="javascript:;" class="has-arrow">
                            <div class="parent-icon"><i class='bx bx-group'></i>
                            </div>
                            <div class="menu-title">Party</div>
                        </a>
                        <ul>
                            @can('view-party')
                                <li> 
                                    <a href="{{ route('party.index') }}"><i class='bx bx-radio-circle'></i>View Party</a>
                                </li>
                            @endcan
                            @can('add-party')
                                <li>
                                    <a href="{{ route('party.create') }}"><i class='bx bx-radio-circle'></i>Add Party</a>
                                </li>
                            @endcan
                            @can('edit-party')
                                <li>
                                    <a href="{{ route('party.editIndex') }}"><i class='bx bx-radio-circle'></i>Edit Party</a>
                                </li>
                            @endcan
                        </ul>
                    </li>
				@endif
			</ul>
			<!--end navigation-->
		</div>
		<!--end sidebar wrapper -->
		<!--start header -->
		<header>
			<div class="topbar d-flex align-items-center">
				<nav class="navbar navbar-expand gap-3">
					<div class="mobile-toggle-menu"><i class='bx bx-menu'></i>
					</div>

					  <div class="search-bar d-lg-block d-none" data-bs-toggle="modal" data-bs-target="#SearchModal">
					     <a href="avascript:;" class="btn d-flex align-items-center"><i class='bx bx-search'></i>Search</a>
					  </div>

					  <div class="top-menu ms-auto">
						<ul class="navbar-nav align-items-center gap-1">
							<li class="nav-item mobile-search-icon d-flex d-lg-none" data-bs-toggle="modal" data-bs-target="#SearchModal">
								<a class="nav-link" href="avascript:;"><i class='bx bx-search'></i>
								</a>
							</li>

							<li class="nav-item dark-mode d-none d-sm-flex app-container">
								<a class="nav-link dark-mode-icon" href="javascript:;"><i class='bx bx-moon'></i>
								</a>
							</li>

                            <div class="header-notifications-list"></div>
                            <div class="header-message-list"></div>
						</ul>
					</div>
					<div class="user-box dropdown px-3">
						<a class="d-flex align-items-center nav-link dropdown-toggle gap-3 dropdown-toggle-nocaret" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
							<img src="{{ Auth::user()->user_photo ? asset('storage/users/user_' . Auth::user()->id . '/' . Auth::user()->user_photo) : asset('uploads/user.png') }}" class="user-img" alt="user avatar">
							<div class="user-info">
								<p class="user-name mb-0">{{ Auth::user()->name ?? ''}}</p>
								<p class="designattion mb-0">{{ Auth::user()->email ?? ''}}</p>
							</div>
						</a>
						<ul class="dropdown-menu dropdown-menu-end">
							<li>
                                <a class="dropdown-item d-flex align-items-center" href="{{ route('settings.edit') }}"><i class="bx bx-cog"></i><span>Settings</span></a>
							</li>
							<li>
                                <a class="dropdown-item d-flex align-items-center" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i class="bx bx-log-out-circle"></i><span>Logout</span></a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
							</li>
						</ul>
					</div>
				</nav>
			</div>
		</header>
		<!--end header -->
		<!--start overlay-->
		 <div class="overlay toggle-icon"></div>
		<!--end overlay-->
		<!--Start Back To Top Button-->
		  <a href="javaScript:;" class="back-to-top"><i class='bx bxs-up-arrow-alt'></i></a>
		<!--End Back To Top Button-->
		<footer class="page-footer">
			<p class="mb-0">{{ isset($settings) && $settings->copyright ? $settings->copyright : '' }}</p>
		</footer>
	</div>
	<!--end wrapper-->