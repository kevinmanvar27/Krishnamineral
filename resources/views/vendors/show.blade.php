@extends('layouts.app')

@section('content')
    <div class="wrapper">
        <div class="page-wrapper">
            <div class="page-content">
                <div class="tab-content">
                    <div class="tab-pane fade show active" id="profileTab" role="tabpanel">
                        <div class="card card-body lead-info">
                            <div class="mb-4 d-flex align-items-center justify-content-between">
                                <h5 class="card-title mb-0">Vendor Details</h5>
                                <div class="card-header-action">
                                    <div class="card-header-btn">
                                        <a href="{{ route('vendors.index') }}" class="btn btn-sm btn-primary">
                                            <i class="bx bx-arrow-to-left"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <hr class="mt-0">
                            <div class="row mb-4">
                                <div class="col-lg-4 fw-medium">Vendor Code</div>
                                <div class="col-lg-8"><a href="javascript:void(0);">{{ $vendor->vendor_code ?? '-' }}</a></div>
                            </div>
                            <div class="row mb-4">
                                <div class="col-lg-4 fw-medium">Vendor Name</div>
                                <div class="col-lg-8"><a href="javascript:void(0);">{{ $vendor->vendor_name ?? '-' }}</a></div>
                            </div>
                            <div class="row mb-4">
                                <div class="col-lg-4 fw-medium">Contact Person</div>
                                <div class="col-lg-8"><a href="javascript:void(0);">{{ $vendor->contact_person ?? '-' }}</a></div>
                            </div>
                            <div class="row mb-4">
                                <div class="col-lg-4 fw-medium">Mobile</div>
                                <div class="col-lg-8"><a href="javascript:void(0);">{{ $vendor->mobile ?? '-' }}</a></div>
                            </div>
                            <div class="row mb-4">
                                <div class="col-lg-4 fw-medium">Telephone</div>
                                <div class="col-lg-8"><a href="javascript:void(0);">{{ $vendor->telephone ?? '-' }}</a></div>
                            </div>
                            <div class="row mb-4">
                                <div class="col-lg-4 fw-medium">Email ID</div>
                                <div class="col-lg-8"><a href="javascript:void(0);">{{ $vendor->email_id ?? '-' }}</a></div>
                            </div>
                            <div class="row mb-4">
                                <div class="col-lg-4 fw-medium">Website</div>
                                <div class="col-lg-8"><a href="javascript:void(0);">{{ $vendor->website ?? '-' }}</a></div>
                            </div>
                            <div class="row mb-4">
                                <div class="col-lg-4 fw-medium">Country</div>
                                <div class="col-lg-8"><a href="javascript:void(0);">{{ $vendor->country ?? '-' }}</a></div>
                            </div>
                            <div class="row mb-4">
                                <div class="col-lg-4 fw-medium">State</div>
                                <div class="col-lg-8"><a href="javascript:void(0);">{{ $vendor->state ?? '-' }}</a></div>
                            </div>
                            <div class="row mb-4">
                                <div class="col-lg-4 fw-medium">City</div>
                                <div class="col-lg-8"><a href="javascript:void(0);">{{ $vendor->city ?? '-' }}</a></div>
                            </div>
                            <div class="row mb-4">
                                <div class="col-lg-4 fw-medium">Pincode</div>
                                <div class="col-lg-8"><a href="javascript:void(0);">{{ $vendor->pincode ?? '-' }}</a></div>
                            </div>
                            <div class="row mb-4">
                                <div class="col-lg-4 fw-medium">Address</div>
                                <div class="col-lg-8"><a href="javascript:void(0);">{{ $vendor->address ?? '-' }}</a></div>
                            </div>
                            <div class="row mb-4">
                                <div class="col-lg-4 fw-medium">Bank Proof</div>
                                <div class="col-lg-8">
                                    @if($vendor->bank_proof)
                                        @php
                                            function renderFile($filePath) { 
                                                if (!$filePath) { 
                                                    return '<label class="badge bg-danger text-white">No File</label>'; 
                                                } 
                                                
                                                $extension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION)); 
                                                $assetPath = asset($filePath);
                                                
                                                if (in_array($extension, ['jpg','jpeg','png'])) { 
                                                    return 
                                                    '<a href="javascript:void(0);" 
                                                        class="view-file" 
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#imageDownload" 
                                                        data-file="' . $assetPath . '"> 
                                                        <img src="' . $assetPath . '" alt="Image" class="img-fluid" width="100">
                                                    </a>'; 
                                                } 
                                                elseif ($extension === 'pdf') { 
                                                    return 
                                                    '<a href="javascript:void(0);" 
                                                        class="view-file" 
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#imageDownload" 
                                                        data-file="' . $assetPath . '"> 
                                                        <i class="bx bx-file bx-md text-danger"></i>
                                                    </a>';
                                                } 
                                                else {
                                                    return '<a href="' . $assetPath . '" target="_blank" class="badge bg-secondary text-white text-decoration-none">View File</a>';
                                                }
                                            }
                                            echo renderFile('storage/vendors/' . $vendor->bank_proof);
                                        @endphp
                                    @else
                                        <a href="javascript:void(0);">-</a>
                                    @endif
                                </div>
                            </div>
                            <div class="row mb-4">
                                <div class="col-lg-4 fw-medium">Payment Conditions</div>
                                <div class="col-lg-8"><a href="javascript:void(0);">{{ $vendor->payment_conditions ?? '-' }}</a></div>
                            </div>
                            <div class="row mb-4">
                                <div class="col-lg-4 fw-medium">Visiting Card</div>
                                <div class="col-lg-8"><a href="javascript:void(0);">{{ $vendor->visiting_card ?? '-' }}</a></div>
                            </div>
                            <div class="row mb-4">
                                <div class="col-lg-4 fw-medium">Note</div>
                                <div class="col-lg-8"><a href="javascript:void(0);">{{ $vendor->note ?? '-' }}</a></div>
                            </div>
                        </div>
                        <hr>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Download Modal -->
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
        // Handle file preview in modal
        $(document).on('click', '.view-file', function() {
            const fileUrl = $(this).data('file');
            const fileExtension = fileUrl.split('.').pop().toLowerCase();
            
            if (['jpg', 'jpeg', 'png'].includes(fileExtension)) {
                $('#file-preview').html(`<img src="${fileUrl}" class="img-fluid" alt="File">`);
            } else {
                $('#file-preview').html(`<iframe src="${fileUrl}" class="w-100" style="height: 500px;"></iframe>`);
            }
            
            $('#downloadFile').attr('href', fileUrl);
            $('#downloadFile').attr('download', fileUrl.split('/').pop());
        });
    </script>
@endpush