@extends('layouts.app')

@section('content')

    <div class="wrapper">
        <div class="page-wrapper">
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
                                <h5 class="card-title">Edit Employee</h5>
                                <div class="card-header-action">
                                    <div class="card-header-btn">         
                                        <a class="btn btn-sm btn-primary" href="{{ route('users.editIndex') }}">
                                            <i class="bx bx-arrow-to-left"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <hr class="mt-0">

                            <form method="POST" action="{{ route('users.update', $user->id) }}" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')

                                <div class="card-body general-info">

                                    {{-- Employee Info --}}
                                    <div class="row align-items-center">
                                        <div class="col-lg-4 mb-3">
                                            <label for="id" class="form-label mb-2">Employee Number</label>
                                            <div class="input-group">
                                                <div class="input-group-text">KME_</div>
                                                <input type="text" name="id" placeholder="EMPLOYEE NUMBER" class="form-control"  id="id"  
                                                    value="{{ old('id', $user->id ?? $nextEmployeeNumber) }}" readonly>
                                                @error('id')<div class="text-danger">{{ $message }}</div>@enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row align-items-center">
                                        <div class="col-lg-6 mb-3">
                                            <label for="username" class="form-label mb-2">Employee Name</label>
                                            <input type="text" name="username" placeholder="Employee Name" class="form-control"  id="username"  
                                                value="{{ old('username', $user->username) }}">
                                            @error('username')<div class="text-danger">{{ $message }}</div>@enderror
                                        </div>
                                        <div class="col-lg-6 mb-3">
                                            <label for="birthdate" class="form-label mb-2">Employee DOB</label>
                                            <input type="date" name="birthdate" placeholder="Employee DOB" class="form-control" id="birthdate" 
                                                value="{{ old('birthdate', $user->birthdate) }}" max="{{ \Carbon\Carbon::now()->subYears(18)->toDateString() }}">
                                            @error('birthdate')<div class="text-danger">{{ $message }}</div>@enderror
                                        </div>
                                    </div>

                                    <div class="row align-items-center">
                                        <div class="col-lg-4 mb-3">
                                            <label for="contact_number_1" class="form-label mb-2">Employee Contact Number</label><i class="bx {{ empty(old('contact_number_2', $user->contact_number_2)) ? 'bx-plus' : 'bx-minus' }} btn btn-primary btn-sm ms-2" id="showMobile2"></i>
                                            <input type="tel" maxlength="10" minlength="10" name="contact_number_1" placeholder="Employee Contact Number" class="form-control" id="contact_number_1"  
                                                value="{{ old('contact_number_1', $user->contact_number_1) }}" oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 10);">
                                            @error('contact_number_1')<div class="text-danger">{{ $message }}</div>@enderror
                                        </div>  
                                        <div class="col-lg-4 mb-3 {{ empty(old('contact_number_2', $user->contact_number_2)) ? 'd-none' : '' }}" id="Mobile2">
                                            <label for="contact_number_2" class="form-label mb-2">Employee Alternate Contact Number</label>
                                            <input type="tel" maxlength="10" minlength="10" name="contact_number_2" placeholder="Employee Alternate Contact Number" class="form-control" id="contact_number_2"  
                                                value="{{ old('contact_number_2', $user->contact_number_2) }}" oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 10);">
                                            @error('contact_number_2')<div class="text-danger">{{ $message }}</div>@enderror
                                        </div>  
                                        <div class="col-lg-4 mb-3">
                                            <label for="joining_date" class="form-label mb-2">Employee Joining Date</label>
                                            <input type="date" name="joining_date" class="form-control date" id="date" 
                                                value="{{ old('joining_date', $user->joining_date) }}" readonly>
                                            @error('joining_date')<div class="text-danger">{{ $message }}</div>@enderror
                                        </div>  
                                    </div>

                                    {{-- Uploads --}}
                                    <div class="row align-items-center">  
                                        <div class="col-md-4 mb-3">
                                            <label for="user_photo" class="form-label mb-2">Employee Image</label>
                                            @if($user->user_photo)
                                            <a href="{{ asset('storage/users/user_' . $user->id . '/' . $user->user_photo) }}" target="_blank" class="badge bg-secondary text-white text-decoration-none view-file" data-bs-toggle="modal" data-bs-target="#imageDownload" data-file="{{ asset('storage/users/user_' . $user->id . '/' . $user->user_photo) }}">View File</a>
                                            </a>                    
                                            @endif
                                            <input type="file" name="user_photo" id="user_photo" class="form-control" accept=".jpg, .jpeg, .png, .pdf">
                                            @error('user_photo')<div class="text-danger">{{ $message }}</div>@enderror
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label for="user_photo_id" class="form-label mb-2">Employee Photo ID</label>
                                            @if($user->user_photo_id)
                                            <a href="{{ asset('storage/users/user_' . $user->id . '/' . $user->user_photo_id) }}" target="_blank" class="badge bg-secondary text-white text-decoration-none view-file" data-bs-toggle="modal" data-bs-target="#imageDownload" data-file="{{ asset('storage/users/user_' . $user->id . '/' . $user->user_photo_id) }}">View File</a>
                                            </a>                    
                                            @endif
                                            <input type="file" name="user_photo_id" id="user_photo_id" class="form-control" accept=".jpg, .jpeg, .png, .pdf">
                                            @error('user_photo_id')<div class="text-danger">{{ $message }}</div>@enderror
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label for="user_address_proof" class="form-label mb-2">Employee Address Proof</label>  
                                            @if($user->user_address_proof)
                                            <a href="{{ asset('storage/users/user_' . $user->id . '/' . $user->user_address_proof) }}" target="_blank" class="badge bg-secondary text-white text-decoration-none view-file" data-bs-toggle="modal" data-bs-target="#imageDownload" data-file="{{ asset('storage/users/user_' . $user->id . '/' . $user->user_address_proof) }}">View File</a>
                                            </a>                    
                                            @endif
                                            <input type="file" name="user_address_proof" id="user_address_proof" class="form-control" accept=".jpg, .jpeg, .png, .pdf">
                                            @error('user_address_proof')<div class="text-danger">{{ $message }}</div>@enderror
                                        </div>
                                    </div>

                                    {{-- Gender --}}
                                    <div class="row align-items-center">  
                                        <div class="col-md-4 mb-3">
                                            <label for="gender" class="form-label mb-2 mb-2">Employee Gender</label><br>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="employee_gender" id="male" value="1" 
                                                    {{ old('employee_gender', $user->employee_gender) == 1 ? 'checked' : '' }}>
                                                <label class="form-check-label" for="male">MALE</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="employee_gender" id="female" value="2" 
                                                    {{ old('employee_gender', $user->employee_gender) == 2 ? 'checked' : '' }}>
                                                <label class="form-check-label" for="female">FEMALE</label>
                                            </div>
                                        </div>
                                    </div>

                                    <hr class="mt-0">
                                    {{-- Insurance Section --}}
                                    <h5>Employee Insurance Details</h5>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="insurance" id="insurance_no" value="1" 
                                            {{ old('insurance', $user->insurance) == 1 ? 'checked' : '' }}>
                                        <label class="form-check-label" for="insurance_no">NO</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="insurance" id="insurance_yes" value="2" 
                                            {{ old('insurance', $user->insurance) == 2 ? 'checked' : '' }}>
                                        <label class="form-check-label" for="insurance_yes">YES</label>
                                    </div>
                                    <div id="showInsurance" class="{{ old('insurance', $user->insurance) == 2 ? '' : 'd-none' }} mt-3">
                                        <div class="row align-items-center">
                                            <div class="col-lg-6 mb-3">
                                                <label for="insurance_name" class="form-label mb-2">Employee Insurance Name</label>
                                                <input type="text" name="insurance_name" class="form-control" 
                                                    value="{{ old('insurance_name', $user->insurance_name) }}" placeholder="Employee Insurance Name">
                                                @error('insurance_name')<div class="text-danger">{{ $message }}</div>@enderror
                                            </div>
                                            <div class="col-lg-6 mb-3">
                                                <label for="insurance_policy_copy" class="form-label mb-2">Employee Insurance Policy Copy</label>
                                                @if($user->insurance_policy_copy)
                                                    <a href="{{ asset('storage/users/user_' . $user->id . '/insurance/' . $user->insurance_policy_copy) }}" target="_blank" class="badge bg-secondary text-white text-decoration-none view-file" data-bs-toggle="modal" data-bs-target="#imageDownload" data-file="{{ asset('storage/users/user_' . $user->id . '/insurance/' . $user->insurance_policy_copy) }}">View File</a>
                                                @endif
                                                <input type="file" name="insurance_policy_copy" class="form-control" accept=".jpg, .jpeg, .png, .pdf">
                                                @error('insurance_policy_copy')<div class="text-danger">{{ $message }}</div>@enderror
                                            </div>
                                        </div>
                                        <div class="row mb-4 align-items-center">
                                            <div class="col-lg-6 mb-3">
                                                <label for="insurance_issue_date" class="form-label mb-2">Insurance Issue Date</label>
                                                <input type="date" name="insurance_issue_date" class="form-control" 
                                                    value="{{ old('insurance_issue_date', $user->insurance_issue_date) }}">
                                                @error('insurance_issue_date')<div class="text-danger">{{ $message }}</div>@enderror
                                            </div>
                                            <div class="col-lg-6 mb-3">
                                                <label for="insurance_valid_date" class="form-label mb-2">Insurance Valid Date</label>
                                                <input type="date" name="insurance_valid_date" class="form-control" 
                                                    value="{{ old('insurance_valid_date', $user->insurance_valid_date) }}">
                                                @error('insurance_valid_date')<div class="text-danger">{{ $message }}</div>@enderror
                                            </div>
                                        </div>
                                        <hr class="mt-0">

                                        {{-- Nominee Section --}}
                                        <h5 class="mb-3">Nominee Details</h5>
                                        <div class="row align-items-center">
                                            <div class="col-lg-6 mb-3">
                                                <label for="nominee_name" class="form-label mb-2">Nominee Name</label>
                                                <input type="text" name="nominee_name" class="form-control" 
                                                    value="{{ old('nominee_name', $user->nominee_name) }}" placeholder="Nominee Name">
                                                @error('nominee_name')<div class="text-danger">{{ $message }}</div>@enderror
                                            </div>
                                            <div class="col-lg-6 mb-3">
                                                <label for="nominee_mobile_number" class="form-label mb-2">Nominee Mobile Number</label>
                                                <input type="tel" maxlength="10" name="nominee_mobile_number" class="form-control" 
                                                    value="{{ old('nominee_mobile_number', $user->nominee_mobile_number) }}" placeholder="Nominee Mobile Number" oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 10);">
                                                @error('nominee_mobile_number')<div class="text-danger">{{ $message }}</div>@enderror
                                            </div>
                                        </div>

                                        <div class="row align-items-center">
                                            <div class="col-lg-6 mb-3">
                                                <label for="nominee_photo_id" class="form-label mb-2">Nominee Photo ID</label>
                                                @if($user->nominee_photo_id)
                                                    <a href="{{ asset('storage/users/user_' . $user->id . '/nominee/' . $user->nominee_photo_id) }}" target="_blank" class="badge bg-secondary text-white text-decoration-none view-file" data-bs-toggle="modal" data-bs-target="#imageDownload" data-file="{{ asset('storage/users/user_' . $user->id . '/nominee/' . $user->nominee_photo_id) }}">View File</a>
                                                @endif
                                                <input type="file" name="nominee_photo_id" class="form-control" accept=".jpg, .jpeg, .png, .pdf">
                                                @error('nominee_photo_id')<div class="text-danger">{{ $message }}</div>@enderror
                                            </div>
                                            <div class="col-lg-6 mb-3">
                                                <label for="nominee_address_proof" class="form-label mb-2">Nominee Address Proof</label>
                                                @if($user->nominee_address_proof)
                                                    <a href="{{ asset('storage/users/user_' . $user->id . '/nominee/' . $user->nominee_address_proof) }}" target="_blank" class="badge bg-secondary text-white text-decoration-none view-file" data-bs-toggle="modal" data-bs-target="#imageDownload" data-file="{{ asset('storage/users/user_' . $user->id . '/nominee/' . $user->nominee_address_proof) }}">View File</a>
                                                @endif
                                                <input type="file" name="nominee_address_proof" class="form-control" accept=".jpg, .jpeg, .png, .pdf">
                                                @error('nominee_address_proof')<div class="text-danger">{{ $message }}</div>@enderror
                                            </div>
                                        </div>

                                        <div class="row mb-4 align-items-center">
                                            <div class="col-lg-6 mb-3">
                                                <label for="nominee_gender" class="form-label mb-2 mb-2">Gender</label><br>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="nominee_gender" id="nominee_male" value="1" 
                                                        {{ old('nominee_gender', $user->nominee_gender) == 1 ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="nominee_male">MALE</label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="nominee_gender" id="nominee_female" value="2" 
                                                        {{ old('nominee_gender', $user->nominee_gender) == 2 ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="nominee_female">FEMALE</label>
                                                </div>
                                            </div>
                                            <div class="col-lg-6 mb-3">
                                                <label for="nominee_birthdate" class="form-label mb-2">Nominee DOB</label>
                                                <input type="date" name="nominee_birthdate" class="form-control" 
                                                    value="{{ old('nominee_birthdate', $user->nominee_birthdate) }}" max="{{ \Carbon\Carbon::now()->subYears(18)->toDateString() }}">
                                                @error('nominee_birthdate')<div class="text-danger">{{ $message }}</div>@enderror
                                            </div>
                                        </div>

                                        <div class="row align-items-center">
                                            <div class="col-lg-12 mb-3">
                                                <label for="insurance_note" class="form-label mb-2">Insurance Note</label>
                                                <input type="text" name="insurance_note" class="form-control" 
                                                    value="{{ old('insurance_note', $user->insurance_note) }}" placeholder="Insurance Note">
                                                @error('insurance_note')<div class="text-danger">{{ $message }}</div>@enderror
                                            </div>
                                        </div>

                                    </div> {{-- End Insurance --}}

                                    <hr class="mt-3">

                                    {{-- User Type --}}
                                    <h5>Employee Work Profile</h5>
                                    <div class="border border-2 shadow-md rounded p-3">
                                        <div class="row align-items-center justify-content-between">
                                            <div class="col-lg-6 mb-3">
                                                <select name="user_type" id="user_type" class="form-select">
                                                    <option value="">Select Designation</option>
                                                    @foreach ($roles as $value => $label)
                                                        <option value="{{ $value }}" {{ old('user_type', $user->user_type) == $value ? 'selected' : '' }}>
                                                            {{ $label }}
                                                        </option>
                                                    @endforeach
                                                    <option value="add-user">
                                                        + Add Designation
                                                    </option>
                                                </select>
                                            </div>
                                            <div class="col-lg-6 mb-3">
                                                <input type="text" name="department" placeholder="Department"  class="form-control" id="department" value="{{ old('department', $user->department) }}">
                                                @error('department')<div class="text-danger">{{ $message }}</div>@enderror
                                            </div>  
                                        </div>
                                        {{-- Salary & Licence --}}
                                        <div class="row align-items-center">
                                            <div class="col-lg-6 mb-3 align-items-center">
                                                <label for="salary">Salary</label>
                                                <input type="number" name="salary" class="form-control" placeholder="Salary ( MONTHLY )" 
                                                    value="{{ old('salary', $user->salary) }}">
                                            </div>
                                            <div class="col-lg-6 mb-3 licence {{ $user->user_type == 'driver' ? '' : 'd-none' }}" id="showDriverLicence">
                                                <label for="licence" class="form-label mb-2">Licence</label>
                                                @if($user->licence)
                                                    <a href="{{ asset('storage/users/user_' . $user->id . '/' . $user->licence) }}" target="_blank" class="badge bg-secondary text-white text-decoration-none view-file" data-bs-toggle="modal" data-bs-target="#imageDownload" data-file="{{ asset('storage/users/user_' . $user->id . '/' . $user->licence) }}">View File</a>
                                                @endif
                                                <input type="file" name="licence" class="form-control" accept=".jpg, .jpeg, .png, .pdf">
                                                @error('licence')<div class="text-danger">{{ $message }}</div>@enderror
                                            </div>
                                        </div>
                                    </div>


                                    {{-- Bank Section --}}
                                    <hr class="mt-3">
                                    <h5>Employee Bank Account</h5>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="bank" id="bank_no" value="1" 
                                            {{ old('bank', $user->bank) == 1 ? 'checked' : '' }}>
                                        <label class="form-check-label" for="bank_no">NO</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="bank" id="bank_yes" value="2" 
                                            {{ old('bank', $user->bank) == 2 ? 'checked' : '' }}>
                                        <label class="form-check-label" for="bank_yes">YES</label>
                                    </div>
                                    <div id="showBank" class="{{ old('bank', $user->bank) == 2 ? '' : 'd-none' }} mt-3">
                                        <div class="row align-items-center">
                                            <div class="col-lg-6 mb-3">
                                                <label for="bank_proof" class="form-label mb-2">Employee Bank Proof</label>
                                                @if($user->bank_proof)
                                                    <a href="{{ asset('storage/users/user_' . $user->id . '/bank/' . $user->bank_proof) }}" target="_blank" class="badge bg-secondary text-white text-decoration-none view-file" data-bs-toggle="modal" data-bs-target="#imageDownload" data-file="{{ asset('storage/users/user_' . $user->id . '/bank/' . $user->bank_proof) }}">View File</a>
                                                @endif
                                                <input type="file" name="bank_proof" class="form-control" accept=".jpg, .jpeg, .png, .pdf">
                                                @error('bank_proof')<div class="text-danger">{{ $message }}</div>@enderror
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Court Section --}}
                                    <hr class="mt-3">
                                    <h5>Employee Court Case</h5>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="court" id="court_no" value="1" 
                                            {{ old('court', $user->court) == 1 ? 'checked' : '' }}>
                                        <label class="form-check-label" for="court_no">NO</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="court" id="court_yes" value="2" 
                                            {{ old('court', $user->court) == 2 ? 'checked' : '' }}>
                                        <label class="form-check-label" for="court_yes">YES</label>
                                    </div>

                                    <div id="showCourt" class="{{ old('court', $user->court) == 2 ? '' : 'd-none' }} mt-3">
                                        @php
                                            $courtCases = json_decode($user->court_case_files ?? '[]', true) ?? [];
                                        @endphp

                                        <div id="case-files-wrapper">
                                            @if(count($courtCases))
                                                @foreach($courtCases as $caseIndex => $courtCase)
                                                    <div class="row align-items-center mb-3 existing-case" data-index="{{ $caseIndex }}">
                                                        <div class="col-lg-5">
                                                            <label>Case Details File</label><br>
                                                            @if(!empty($courtCase['case_files']))
                                                                @foreach($courtCase['case_files'] as $file)
                                                                    <a href="{{ asset('storage/users/user_' . $user->id . '/court_case/' . $file) }}" target="_blank" class="badge bg-secondary text-white view-file" data-bs-toggle="modal" data-bs-target="#imageDownload" data-file="{{ asset('storage/users/user_' . $user->id . '/court_case/' . $file) }}">View File</a>
                                                                @endforeach
                                                            @endif
                                                            <input type="file" name="court_case_files[{{ $caseIndex }}][]" class="form-control mt-2" {{ !empty($courtCase['case_files']) ? 'disabled' : '' }}>
                                                        </div>
                                                        <div class="col-lg-5">
                                                            <label>Case Close File</label><br>
                                                            @if(!empty($courtCase['case_close_files']))
                                                                @foreach($courtCase['case_close_files'] as $closeFile)
                                                                    <a href="{{ asset('storage/users/user_' . $user->id . '/court_case_close/' . $closeFile) }}" target="_blank" class="badge bg-secondary text-white view-file" data-bs-toggle="modal" data-bs-target="#imageDownload" data-file="{{ asset('storage/users/user_' . $user->id . '/court_case_close/' . $closeFile) }}">View File</a>
                                                                @endforeach
                                                            @endif
                                                            <input type="file" name="court_case_close_file[{{ $caseIndex }}][]" class="form-control mt-2" {{ !empty($courtCase['case_close_files']) ? 'disabled' : '' }}>
                                                        </div>
                                                    </div>
                                                @endforeach

                                            @else
                                                <div class="row align-items-center mb-3">
                                                    <div class="col-lg-5">
                                                        <label>Case Details File</label>
                                                        <input type="file" name="court_case_files[0][]" class="form-control" accept=".jpg,.jpeg,.png,.pdf" multiple>
                                                    </div>
                                                    <div class="col-lg-5">
                                                        <label>Case Close File</label>
                                                        <input type="file" name="court_case_close_file[0][]" class="form-control" accept=".jpg,.jpeg,.png,.pdf" multiple>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>



                                        <button type="button" class="btn btn-primary mt-3" id="add-fields">Add More Case</button>
                                    </div>


                                    {{-- Notes --}}
                                    <hr class="mt-3">
                                    <div class="row align-items-center">
                                        <div class="col-lg-12 mb-3">
                                            <label for="note" class="form-label mb-2">Note</label>
                                            <input type="text" name="note" id="note" placeholder="Note" class="form-control" 
                                                value="{{ old('note', $user->note) }}">
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
                                                                                        value="{{ $permission->id }}"
                                                                                        {{ in_array($permission->id, $userPermissions) ? 'checked' : '' }}>
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

                                    {{-- Submit --}}
                                    <div class="row align-items-center">
                                        <div class="col-lg-8 mb-3">
                                            <div class="input-group">
                                                <button type="submit" class="btn btn-primary mt-2 mb-3">
                                                    <i class="bx bx-save me-2"></i>
                                                    Submit
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                </div> {{-- End Card Body --}}
                            </form>

                        </div>
                    </div>
                </div>
            </div>
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


    <div class="modal fade" id="imageDownload" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="file-preview">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <a href="#" download id="downloadFile" class="btn btn-success">Download</a>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function () {

            // -------------------- Set today's date for all date fields --------------------
            const today = new Date().toISOString().split('T')[0];
            $('.date').each(function () {
                if (!$(this).val()) { // Only set if empty
                    $(this).val(today);
                }
            });
            
            // -------------------- Show Add User Type Modal --------------------
            $('#user_type').on('change', function() {
                if ($(this).val() === 'add-user') {
                    new bootstrap.Modal(document.getElementById('addUserTypeModal')).show();
                    $(this).val('');
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

            // Nominee
            $('input[name="nominee"]').on('change', function () {
                toggleSection('#nominee_yes', '#showNominee');
            });
            toggleSection('#nominee_yes', '#showNominee');

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
                const newIndex = $('#case-files-wrapper .new-case').length;

                const newFields = `
                    <div class="row mb-3 align-items-center new-case">
                        <div class="col-lg-5">
                            <input type="file" name="court_case_files[new_${newIndex}][]" class="form-control">
                        </div>
                        <div class="col-lg-5">
                            <input type="file" name="court_case_close_file[new_${newIndex}][]" class="form-control">
                        </div>
                        <div class="col-lg-2 text-end mt-2">
                            <button type="button" class="btn btn-danger btn-sm remove-fields">
                                <i class="bx bx-trash"></i>
                            </button>
                        </div>
                    </div>`;
                $('#case-files-wrapper').append(newFields);
            });

            $(document).on('click', '.remove-fields', function () {
                $(this).closest('.row').remove();

                // Reindex only new rows
                $('#case-files-wrapper .new-case').each(function (i, row) {
                    $(row).find('input[name^="court_case_files"]').attr('name', `court_case_files[new_${i}][]`);
                    $(row).find('input[name^="court_case_close_file"]').attr('name', `court_case_close_file[new_${i}][]`);
                });
            });



            // -------------------- Mobile Number Toggle --------------------
            $('#showMobile2').on('click', function () {
                $('#Mobile2').toggleClass('d-none d-block');
                $(this).toggleClass('bx-plus bx-minus');
            });

            // Initialize mobile number 2 visibility based on whether it has data
            if ($('#contact_number_2').val()) {
                $('#Mobile2').removeClass('d-none');
                $('#showMobile2').removeClass('bx-plus').addClass('bx-minus');
            }

        });

        $(document).ready(function() {
            $(document).on('submit', '#addUserTypeForm', function(e) {
                e.preventDefault();

                const form = $(this);
                const url = form.attr('action');
                const formData = new FormData(this);

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
                            // Add new option before the "Add User Type" option
                            $('#user_type').append(`<option value="${response.id}">${response.name}</option>`);
                            // Select the newly added option
                            $('#user_type').val(response.id);
                            // Re-add the "Add User Type" option at the end
                            $('#user_type').append(addUserOption);

                            const modalEl = document.getElementById('addUserTypeModal');
                            const modal = bootstrap.Modal.getInstance(modalEl);
                            if(modal){
                                modal.hide();
                            }

                            form[0].reset();
                            
                            // Clear previous errors
                            $('.invalid-feedback').text('');
                            $('.form-control').removeClass('is-invalid');

                            $('body').removeClass('modal-open');
                            $('.modal-backdrop').remove();
                        }
                    },
                    error: function(xhr){
                        // Handle validation errors
                        if (xhr.status === 422) {
                            let errors = xhr.responseJSON.errors;
                            // Clear previous errors
                            $('.invalid-feedback').text('');
                            $('.form-control').removeClass('is-invalid');
                            
                            $.each(errors, function(key, value) {
                                $(`#${key}`).addClass('is-invalid');
                                $(`#${key}-error`).text(value[0]);
                            });
                        } else {
                            alert('Something went wrong!');
                        }
                    }
                });
                $('#addUserTypeModal').on('hidden.bs.modal', function () {
                    $('body').removeClass('modal-open');
                    $('body').css('overflow', 'auto');
                    $('.modal-backdrop').remove();
                    // Reset form and clear errors when modal is closed
                    $('#addUserTypeForm')[0].reset();
                    $('.invalid-feedback').text('');
                    $('.form-control').removeClass('is-invalid');
                });
            });
        });

    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.view-file').forEach(function (el) {
                el.addEventListener('click', function () {
                    let fileUrl = this.dataset.file;
                    let filePreview = document.getElementById('file-preview');
                    let downloadBtn = document.getElementById('downloadFile');

                    // Clear previous content
                    filePreview.innerHTML = '';

                    // Set download link
                    downloadBtn.href = fileUrl;

                    // Detect file extension
                    let ext = fileUrl.split('.').pop().toLowerCase();

                    if (ext === 'pdf') {
                        filePreview.innerHTML = `<iframe src="${fileUrl}" class="w-100" style="height: 100vh;" frameborder="0"></iframe>`;
                    } else {
                        filePreview.innerHTML = `<img src="${fileUrl}" alt="File" class="img-fluid w-100 h-100 object-fit-contain">`;
                    }
                });
            });
        });
    </script>

@endpush
