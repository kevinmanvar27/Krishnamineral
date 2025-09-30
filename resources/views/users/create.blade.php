@extends('layouts.app')

@section('content')

    <div class="wrapper">
        <div class="page-wrapper">
            <!-- [ Main Content ] start -->
            <div class="page-content">
                <div class="row">
                    <div class="col-lg-12">
                        @if (count($errors) > 0)
                            <div class="alert alert-danger">
                            <strong>Whoops!</strong> There were some problems with your input.<br><br>
                            <ul>
                                @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            </div>
                        @endif
                        <div class="card stretch stretch-full">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h5 class="card-title">New Employee</h5>
                                <div class="card-header-action">
                                    <div class="card-header-btn">         
                                        <a class="btn btn-sm btn-primary" href="{{ route('users.index') }}">
                                            <i class="bx bx-arrow-to-left"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <hr class="mt-0">
                            <form method="POST" action="{{ route('users.store') }}" enctype="multipart/form-data">
                            @csrf
                                <div class="card-body general-info">
                                    <div class="row align-items-center">
                                        <div class="col-lg-4 mb-3">
                                            <label for="challan_number" class="form-label mb-2">Employee Number </label>
                                            <div class="input-group">
                                                <div class="input-group-text">KME_</div>
                                                <input type="text" name="id" placeholder="EMPLOYEE NUMBER" class="form-control"  id="id"  value="{{ old('id', $user->id ?? $nextEmployeeNumber) }}">
                                                @error('id')<div class="text-danger">{{ $message }}</div>@enderror
                                            </div>
                                        </div>  
                                    </div>
                                    <div class="row align-items-center">
                                        <div class="col-lg-6 mb-3 ">
                                            <label for="username" class="form-label">Employee Name</label>
                                            <input type="text" name="username" placeholder="Employee Name" class="form-control"  id="username"  value="{{ old('username') }}">
                                            @error('username')<div class="text-danger">{{ $message }}</div>@enderror
                                        </div>
                                        <div class="col-lg-6 mb-3 ">
                                            <label for="birthdate" class="form-label">Employee DOB</label>
                                            <input type="date" name="birthdate" placeholder="Birthdate" class="form-control" id="birthdate" value="{{ old('birthdate') }}" max="{{ \Carbon\Carbon::now()->subYears(18)->toDateString() }}">
                                            @error('birthdate')<div class="text-danger">{{ $message }}</div>@enderror
                                        </div>
                                    </div>
                                    <div class="row align-items-center">
                                        <div class="col-lg-4 mb-3 ">
                                            <label for="contact_number_1" class="form-label">Employee Contact Number</label><i class="bx bx-plus btn btn-primary btn-sm ms-2" id="showMobile2"></i>
                                            <input type="tel" maxlength="10" minlength="10" name="contact_number_1" placeholder="Employee Contact Number" class="form-control"  id="contact_number_1"  value="{{ old('contact_number_1') }}" oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 10);">
                                            @error('contact_number_1')<div class="text-danger">{{ $message }}</div>@enderror
                                        </div>  
                                        <div class="col-lg-4 d-none mb-3 " id="Mobile2">
                                            <label for="contact_number_2" class="form-label">Employee Alternate Contact Number</label>
                                            <input type="tel" maxlength="10" minlength="10" name="contact_number_2" placeholder="Employee Alternate Contact Number" class="form-control"  id="contact_number_2"  value="{{ old('contact_number_2') }}"oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 10);">
                                            @error('contact_number_2')<div class="text-danger">{{ $message }}</div>@enderror
                                        </div>  
                                        <div class="col-lg-4 mb-3 ">
                                            <label for="joining_date" class="form-label">Employee Joining Date</label>
                                            <input type="date" name="joining_date" class="form-control date" id="date" value="{{ old('joining_date') }}" readonly>
                                            @error('joining_date')<div class="text-danger">{{ $message }}</div>@enderror
                                        </div>  
                                    </div>
                                    <div class="row align-items-center">  
                                        <div class="col-md-4 mb-3 ">
                                            <label for="user_photo" class="form-label">Employee Image</label>
                                            @if(isset($settings->user_photo))
                                            <a href="{{ asset('uploads/users/'.$settings->user_photo) }}" target="_blank">
                                                <img src="{{ asset('uploads/users/'.$settings->user_photo) }}" alt="{{$settings->user_photo}}" width="50">
                                            </a>                    
                                            @endif
                                            <input type="file" name="user_photo" id="user_photo" class="form-control" accept=".jpg, .jpeg, .png, .pdf">
                                            @error('user_photo')<div class="text-danger">{{ $message }}</div>@enderror
                                        </div>
                                        <div class="col-md-4 mb-3 ">
                                            <label for="user_photo_id" class="form-label">Employee Photo ID</label>
                                            @if(isset($settings->user_photo_id))
                                            <a href="{{ asset('uploads/users/'.$settings->user_photo_id) }}" target="_blank">
                                                <img src="{{ asset('uploads/users/'.$settings->user_photo_id) }}" alt="{{$settings->user_photo_id}}" width="50">
                                            </a>                    
                                            @endif
                                            <input type="file" name="user_photo_id" id="user_photo_id" class="form-control" accept=".jpg, .jpeg, .png, .pdf">
                                            @error('user_photo_id')<div class="text-danger">{{ $message }}</div>@enderror
                                        </div>
                                        <div class="col-md-4 mb-3 ">
                                            <label for="user_address_proof" class="form-label">Employee Address Proof</label>
                                            @if(isset($settings->user_address_proof))
                                            <a href="{{ asset('uploads/users/'.$settings->user_address_proof) }}" target="_blank">
                                                <img src="{{ asset('uploads/users/'.$settings->user_address_proof) }}" alt="{{$settings->user_address_proof}}" width="50">
                                            </a>                    
                                            @endif
                                            <input type="file" name="user_address_proof" id="user_address_proof" class="form-control" accept=".jpg, .jpeg, .png, .pdf">
                                            @error('user_address_proof')<div class="text-danger">{{ $message }}</div>@enderror
                                        </div>
                                    </div>
                                    
                                    <div class="row align-items-center">  
                                        <div class="col-md-4 mb-3 ">
                                            <label for="gender" class="form-label mb-2">Employee Gender</label><br>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="employee_gender" id="male" value="1" checked>
                                                <label class="form-check-label" for="male">MALE</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="employee_gender" id="female" value="2">
                                                <label class="form-check-label" for="female">FEMALE</label>
                                            </div>
                                        </div>
                                    </div>
                                    <hr class="mt-0">
                                    <h5>Employee Insurance Details</h5>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="insurance" id="insurance_no" value="1" checked>
                                        <label class="form-check-label" for="insurance_no">NO</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="insurance" id="insurance_yes" value="2">
                                        <label class="form-check-label" for="insurance_yes">YES</label>
                                    </div>
                                    <div id="showInsurance" class="d-none mt-3">
                                        <div class="row align-items-center">
                                            <div class="col-lg-6 mb-3 ">
                                                <label for="insurance_name" class="form-label">Employee Insurance Name</label>
                                                <input type="text" name="insurance_name" placeholder="Employee Insurance Name" class="form-control" id="insurance_name" value="{{ old('insurance_name') }}">
                                                @error('insurance_name')<div class="text-danger">{{ $message }}</div>@enderror
                                            </div>  
                                            <div class="col-lg-6 mb-3 ">
                                                <label for="insurance_policy_copy" class="form-label">Employee Insurance Policy Copy</label>
                                                <input type="file" name="insurance_policy_copy" class="form-control" id="insurance_policy_copy" accept=".jpg, .jpeg, .png, .pdf">
                                                @error('insurance_policy_copy')<div class="text-danger">{{ $message }}</div>@enderror
                                            </div>  
                                        </div>
                                        <div class="row align-items-center">
                                            <div class="col-lg-6 mb-3 ">
                                                <label for="insurance_issue_date" class="form-label">Insurance Issue Date</label>
                                                <input type="date" name="insurance_issue_date" placeholder="Insurance Issue Date" class="form-control" id="insurance_issue_date" value="{{ old('insurance_issue_date') }}">
                                                @error('insurance_issue_date')<div class="text-danger">{{ $message }}</div>@enderror
                                            </div>  
                                            <div class="col-lg-6 mb-3 ">
                                                <label for="insurance_valid_date" class="form-label">Insurance Valid Date</label>
                                                <input type="date" name="insurance_valid_date" placeholder="Insurance Valid Date" class="form-control" id="insurance_valid_date" value="{{ old('insurance_valid_date') }}">
                                                @error('insurance_valid_date')<div class="text-danger">{{ $message }}</div>@enderror
                                            </div>  
                                        </div>
                                        <hr class="mt-0">
                                        <h5 class="mb-3">Nominee Details</h5>
                                        <div class="row align-items-center">
                                            <div class="col-lg-6 mb-3 ">
                                                <label for="nominee_name" class="form-label">Nominee Name</label>
                                                <input type="text" name="nominee_name" placeholder="Nominee Name" class="form-control" id="nominee_name" value="{{ old('nominee_name') }}">
                                                @error('nominee_name')<div class="text-danger">{{ $message }}</div>@enderror
                                            </div>  
                                            <div class="col-lg-6 mb-3 ">
                                                <label for="nominee_mobile_number" class="form-label">Nominee Mobile Number</label>
                                                <input type="tel" maxlength="10" name="nominee_mobile_number" placeholder="Nominee Mobile Number" class="form-control" id="nominee_mobile_number" value="{{ old('nominee_mobile_number') }}"  oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 10);">
                                                @error('nominee_mobile_number')<div class="text-danger">{{ $message }}</div>@enderror
                                            </div>  
                                        </div>
                                        <div class="row align-items-center">
                                            <div class="col-lg-6 mb-3 ">
                                                <label for="nominee_photo_id" class="form-label">Nominee Photo ID</label>
                                                <input type="file" name="nominee_photo_id" class="form-control" id="nominee_photo_id" value="{{ old('nominee_photo_id') }}" accept=".jpg, .jpeg, .png, .pdf">
                                                @error('nominee_photo_id')<div class="text-danger">{{ $message }}</div>@enderror
                                            </div>  
                                            <div class="col-lg-6 mb-3 ">
                                                <label for="nominee_address_proof" class="form-label">Nominee Address Proof</label>
                                                <input type="file" name="nominee_address_proof" class="form-control" id="nominee_address_proof" value="{{ old('nominee_address_proof') }}" accept=".jpg, .jpeg, .png, .pdf">
                                                @error('nominee_address_proof')<div class="text-danger">{{ $message }}</div>@enderror
                                            </div>  
                                        </div>
                                        <div class="row align-items-center">
                                            <div class="col-lg-6 mb-3 ">
                                                <label for="gender" class="form-label mb-2">Gender</label><br>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="nominee_gender" id="nominee_male" value="1" checked>
                                                    <label class="form-check-label" for="nominee_male">MALE</label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="nominee_gender" id="nominee_female" value="2">
                                                    <label class="form-check-label" for="nominee_female">FEMALE</label>
                                                </div>
                                            </div>  
                                            <div class="col-lg-6 mb-3 ">
                                                <label for="nominee_birthdate" class="form-label">Nominee DOB</label>
                                                <input type="date" name="nominee_birthdate" class="form-control" id="nominee_birthdate" value="{{ old('nominee_birthdate') }}" max="{{ \Carbon\Carbon::now()->subYears(18)->toDateString() }}">
                                                @error('nominee_birthdate')<div class="text-danger">{{ $message }}</div>@enderror
                                            </div>  
                                        </div>
                                        <div class="row align-items-center">
                                            <div class="col-lg-12 mb-3 ">
                                                <label for="insurance_note" class="form-label">Insurance Note</label>
                                                <input type="text" name="insurance_note" placeholder="Insurance Note" class="form-control" id="insurance_note" value="{{ old('insurance_note') }}">
                                                @error('insurance_note')<div class="text-danger">{{ $message }}</div>@enderror
                                            </div>  
                                        </div>
                                    </div>
                                    <hr class="mt-3">
                                    <h5>Employee Work Profile</h5>
                                    <div class="border border-2 shadow-md rounded p-3">
                                        <div class="row align-items-center justify-content-between d-flex">
                                            <div class="col-lg-6 mb-3 ">
                                                <select name="user_type" id="user_type" class="form-select">
                                                    <option value="">Select Designation</option>
                                                    @foreach ($roles as $value => $label)
                                                        <option value="{{ $value }}" {{ old('user_type') == $value ? 'selected' : '' }}>
                                                            {{ $label }}
                                                        </option>
                                                    @endforeach
                                                    <option value="add-user">
                                                        + Add Designation
                                                    </option>
                                                </select>
                                                @error('user_type')<div class="text-danger">{{ $message }}</div>@enderror
                                            </div>
                                            <div class="col-lg-6 mb-3 ">
                                                <input type="text" name="department" placeholder="Department" class="form-control" id="department" value="{{ old('department') }}">
                                                @error('department')<div class="text-danger">{{ $message }}</div>@enderror
                                            </div>  
                                        </div>
                                        <div class="row align-items-center">
                                            <div class="col-lg-6 align-items-center mb-3 ">
                                                <label for="salary" class="form-label">Salary</label>
                                                <input type="number" name="salary" class="form-control" placeholder="Salary ( MONTHLY )" value="{{ old('salary') }}">
                                                @error('salary')<div class="text-danger">{{ $message }}</div>@enderror
                                            </div>
                                            <div class="col-lg-6 licence d-none mb-3 " id="showDriverLicence">
                                                <label for="licence" class="form-label">Licence</label>
                                                <input type="file" name="licence" placeholder="Licence" class="form-control" id="licence" accept=".jpg, .jpeg, .png, .pdf">
                                                @error('licence')<div class="text-danger">{{ $message }}</div>@enderror
                                            </div>
                                        </div>
                                    </div>
                                    <hr class="mt-3">
                                    <h5>Employee Bank Account</h5>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="bank" id="bank_no" value="1" checked>
                                        <label class="form-check-label" for="bank_no">NO</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="bank" id="bank_yes" value="2">
                                        <label class="form-check-label" for="bank_yes">YES</label>
                                    </div>
                                    <div id="showBank" class="d-none mt-3">
                                        <div class="row align-items-center">
                                            <div class="col-lg-6">
                                                <label for="bank_proof" class="form-label">Employee Bank Proof</label>
                                                <input type="file" name="bank_proof" class="form-control" id="bank_proof" value="{{ old('bank_proof') }}" accept=".jpg, .jpeg, .png, .pdf">
                                                @error('bank_proof')<div class="text-danger">{{ $message }}</div>@enderror
                                            </div>  
                                        </div>
                                    </div>
                                    <hr class="mt-3">
                                    <h5>Employee Court Case</h5>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="court" id="court_no" value="1" checked>
                                        <label class="form-check-label" for="court_no">NO</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="court" id="court_yes" value="2">
                                        <label class="form-check-label" for="court_yes">YES</label>
                                    </div>
                                    <div id="showCourt" class="d-none mt-3">
                                        <div id="case-files-wrapper">
                                            <div class="row align-items-center">
                                                <div class="col-lg-5 mb-3 ">
                                                    <label for="court_case_file" class="form-label">Case Details File</label>
                                                    <input type="file" name="court_case_files[]" class="form-control" accept=".jpg, .jpeg, .png, .pdf">
                                                    @error('court_case_files')<div class="text-danger">{{ $message }}</div>@enderror
                                                </div>  
                                                <div class="col-lg-5 mb-3 ">
                                                    <label for="court_case_close_file" class="form-label">Case Close File</label>
                                                    <input type="file" name="court_case_close_file[]" class="form-control" accept=".jpg, .jpeg, .png, .pdf">
                                                </div>  
                                            </div>
                                        </div>
                                        <button type="button" class="btn btn-primary mt-3" id="add-fields">Add More Files</button>
                                    </div>
                                    <hr class="mt-3">
                                    <div class="row align-items-center">
                                        <div class="col-lg-12 mb-3 ">
                                            <label for="note" class="form-label">Note</label>
                                            <input type="text" name="note" id="note" placeholder="Note" class="form-control">
                                        </div>
                                    </div>
                                    <div class="row align-items-center">
                                        <div class="col-lg-12 mb-3 ">
                                            <label for="note" class="form-label mb-2">Permissions</label>

                                            <div class="accordion" id="mainAccordion">
                                                @foreach($groupedPermissions as $groupName => $permissions)
                                                    @php 
                                                        $collapseId = 'collapse-' . $loop->index;
                                                        $headingId = 'heading-' . $loop->index;
                                                    @endphp

                                                    <div class="accordion-item">
                                                        <h2 class="accordion-header" id="{{ $headingId }}">
                                                            <button class="accordion-button {{ $loop->first ? '' : 'collapsed' }}" 
                                                                    type="button" 
                                                                    data-bs-toggle="collapse" 
                                                                    data-bs-target="#{{ $collapseId }}" 
                                                                    aria-controls="{{ $collapseId }}">
                                                                {{ $groupName }} Permissions
                                                            </button>
                                                        </h2>
                                                        <div id="{{ $collapseId }}" 
                                                            class="accordion-collapse collapse" 
                                                            aria-labelledby="{{ $headingId }}" 
                                                            data-bs-parent="#mainAccordion">
                                                            <div class="accordion-body">
                                                                <div class="form-group">
                                                                    <div class="row">
                                                                        @foreach($permissions as $permission)
                                                                            <div class="col-md-4 mb-2">
                                                                                <div class="form-check">
                                                                                    <input class="form-check-input" 
                                                                                        id="perm_{{ $permission->id }}" 
                                                                                        type="checkbox" 
                                                                                        name="permissions[]" 
                                                                                        value="{{ $permission->id }}">
                                                                                    <label class="form-check-label" for="perm_{{ $permission->id }}">
                                                                                        {{ ucwords(str_replace('-', ' ', $permission->name)) }}
                                                                                    </label>
                                                                                </div>
                                                                            </div>
                                                                        @endforeach
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>        
                                    <div class="row align-items-center">
                                        <div class="col-lg-8">
                                            <div class="input-group">
                                                <button type="submit" class="btn btn-primary mt-2 mb-3">
                                                    <i class="bx bx-save me-2"></i>
                                                    Submit
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- [ Main Content ] end -->
        </div>
    </div>
    <div class="modal fade" id="addUserTypeModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="addUserTypeForm" action="{{ route('roles.store') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Add Employee Designation</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="user_type_name" class="col-form-label">Employee Designation:</label>
                            <input type="text" class="form-control" name="name" id="user_type_name" placeholder="Enter user type">
                            <div class="invalid-feedback" id="name-error"></div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        $(document).ready(function() {

            // -------------------- Set today's date for all date fields --------------------
            const today = new Date().toISOString().split('T')[0];
            $('.date').each(function () {
                if (!$(this).val()) {
                    $(this).val(today);
                }
            });
            
            // -------------------- Show Add User Type Modal --------------------
            $('#user_type').on('change', function() {
                if ($(this).val() === 'add-user') {
                    new bootstrap.Modal(document.getElementById('addUserTypeModal')).show();
                    $(this).val(''); // Reset the select
                }
            });
            
            // -------------------- Toggle Sections --------------------
            function toggleSection(triggerId, sectionId, extraClasses = 'border border-2 shadow-md rounded p-3') {
                if ($(triggerId).is(':checked')) {
                    $(sectionId).removeClass('d-none').addClass('d-block ' + extraClasses);
                } else {
                    $(sectionId).removeClass('d-block').addClass('d-none');
                }
            }

            // Insurance
            $('input[name="insurance"]').on('change', function () {
                toggleSection('#insurance_yes', '#showInsurance');
            });
            toggleSection('#insurance_yes', '#showInsurance');

            // Bank
            $('input[name="bank"]').on('change', function () {
                toggleSection('#bank_yes', '#showBank');
            });
            toggleSection('#bank_yes', '#showBank');

            // Court
            $('input[name="court"]').on('change', function () {
                toggleSection('#court_yes', '#showCourt');
            });
            toggleSection('#court_yes', '#showCourt');

            // -------------------- Driver Licence based on User Type --------------------
            const roleMap = @json($roles);
            function toggleDriverLicence() {
                const val = $('#user_type').val();
                let label = roleMap[val] || '';
                label = String(label).trim().toLowerCase();
                if (label === 'driver' || label === 'operator') {
                    $('#showDriverLicence').removeClass('d-none').addClass('d-block');
                } else {
                    $('#showDriverLicence').removeClass('d-block').addClass('d-none');
                }
            }
            $('#user_type').on('change', toggleDriverLicence);
            toggleDriverLicence();

            // -------------------- Add/Remove Court Case Fields --------------------
            $('#add-fields').click(function () {
                const newFields = `
                    <div class="row mb-3 align-items-center">
                        <hr class="mt-0">
                        <div class="col-lg-5 mb-3 ">
                            <input type="file" name="court_case_files[]" class="form-control">
                        </div>
                        <div class="col-lg-5 mb-3 ">
                            <input type="file" name="court_case_close_file[]" class="form-control">
                        </div>
                        <div class="col-lg-2 text-end mt-2">
                            <button type="button" class="btn btn-danger btn-sm remove-fields"><i class="bx bx-trash"></i></button>
                        </div>
                    </div>`;
                $('#case-files-wrapper').append(newFields);
            });

            $(document).on('click', '.remove-fields', function () {
                $(this).closest('.row').remove();
            });

            // -------------------- Mobile Number Toggle --------------------
            $('#showMobile2').on('click', function () {
                $('#Mobile2').toggleClass('d-none d-block');
                $(this).toggleClass('bx-plus bx-minus');
            });

            // -------------------- Handle Add User Type Form Submission --------------------
            $(document).on('submit', '#addUserTypeForm', function(e) {
                e.preventDefault();

                const form = $(this);
                const url = form.attr('action');
                const formData = new FormData(this);
                
                $('.invalid-feedback').text('');
                $('.form-control').removeClass('is-invalid');

                $.ajax({
                    url: url,
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        if(response.id && response.name){
                            // Remove the "Add User Type" option temporarily
                            const addUserOption = $('#user_type option[value="add-user"]').detach();
                            // Add new option
                            $('#user_type').append(`<option value="${response.id}">${response.name}</option>`);
                            // Select the newly added option
                            $('#user_type').val(response.id);
                            // Re-add the "Add User Type" option at the end
                            $('#user_type').append(addUserOption);
                            
                            // Hide the modal
                            const modalEl = document.getElementById('addUserTypeModal');
                            const modal = bootstrap.Modal.getInstance(modalEl);
                            if (modal) {
                                modal.hide();
                            }
                            
                            // Reset form
                            form[0].reset();
                            
                            // Clear modal backdrop
                            $('body').removeClass('modal-open');
                            $('.modal-backdrop').remove();
                            
                            // Trigger change event to update driver license visibility
                            toggleDriverLicence();
                        } else {
                            alert('Role added but unexpected response format');
                        }
                    },
                    error: function(xhr){
                        if (xhr.status === 422) {
                            let errors = xhr.responseJSON.errors;
                            $.each(errors, function(key, value) {
                                $(`#${key}`).addClass('is-invalid');
                                $(`#${key}-error`).text(value[0]);
                            });
                        } else {
                            alert('Something went wrong!');
                        }
                    }
                });
            });

            $('#addUserTypeModal').on('hidden.bs.modal', function () {
                $('body').removeClass('modal-open');
                $('body').css('overflow', 'auto');
                $('.modal-backdrop').remove();
                $('#addUserTypeForm')[0].reset();
                $('.invalid-feedback').text('');
                $('.form-control').removeClass('is-invalid');
            });
        });

    </script>
@endpush
