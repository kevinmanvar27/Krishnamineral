<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Sales Details</h5>
                </div>
                <div class="card-body">
                    <!-- Form Start -->
                    <form>
                        <div class="row">
                            <div class="col-lg-4 mb-3">
                                <label class="form-label">Challan Number</label>
                                <div class="input-group">
                                    <div class="input-group-text">S_</div>
                                    <input type="text" class="form-control" value="{{ $sales->id }}" readonly>
                                </div>
                            </div>
                            <div class="col-lg-4 mb-3">
                                <label class="form-label">Date & Time</label>
                                <input type="text" class="form-control" value="{{ $sales->created_at->timezone('Asia/Kolkata')->format('d-m-Y h:i A') }}" readonly>
                            </div>
                            <div class="col-lg-4 mb-3">
                                <label class="form-label">Status</label>
                                <input type="text" class="form-control" value="{{ $sales->status == 0 ? 'Pending' : ($sales->status == 1 ? 'Completed' : 'Audit Required') }}" readonly>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-6 mb-3">
                                <label class="form-label">Vehicle Number</label>
                                <input type="text" class="form-control" value="{{ $sales->vehicle->name ?? '-' }}" readonly>
                            </div>
                            <div class="col-lg-6 mb-3">
                                <label class="form-label">Transporter Name</label>
                                <input type="text" class="form-control" value="{{ $sales->transporter ?? '-' }}" readonly>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-6 mb-3">
                                <label class="form-label">Owner Contact Number</label>
                                <input type="text" class="form-control" value="{{ $sales->contact_number ?? '-' }}" readonly>
                            </div>
                            <div class="col-lg-6 mb-3">
                                <label class="form-label">Driver Contact Number</label>
                                <input type="text" class="form-control" value="{{ $sales->driver_contact_number ?? '-' }}" readonly>
                            </div>
                        </div>
                        <hr class="mt-0 border border-2 border-primary">
                        <h5>Rate Details :-</h5>
                        <div class="row">
                            <div class="col-lg-6 mb-3">
                                <label class="form-label">Gross Weight</label>
                                <input type="text" class="form-control" value="{{ $sales->gross_weight ?? '-' }}" readonly>
                            </div>
                            <div class="col-lg-6 mb-3">
                                <label class="form-label">Tare Weight</label>
                                <input type="text" class="form-control" value="{{ $sales->tare_weight ?? '-' }}" readonly>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-6 mb-3">
                                <label class="form-label">Net Weight</label>
                                <input type="text" class="form-control" value="{{ $sales->net_weight ?? '-' }}" readonly>
                            </div>
                            <div class="col-lg-6 mb-3">
                                <label class="form-label">Party Weight</label>
                                <input type="text" class="form-control" value="{{ $sales->party_weight ?? '-' }}" readonly>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-4 mb-3">
                                <label class="form-label">Rate</label>
                                <input type="text" class="form-control" value="{{ $sales->rate ?? '-' }}" readonly>
                            </div>
                            <div class="col-lg-4 mb-3">
                                <label class="form-label">GST</label>
                                <input type="text" class="form-control" value="{{ $sales->gst ?? '-' }}%" readonly>
                            </div>
                            <div class="col-lg-4 mb-3">
                                <label class="form-label">Amount</label>
                                <input type="text" class="form-control" value="{{ $sales->amount ?? '-' }}" readonly>
                            </div>
                        </div>
                        <hr class="mt-0 border border-2 border-primary">
                        <h5>Carting Details :-</h5>

                        <div class="row">
                            <div class="col-lg-6 mb-3">
                                <label class="form-label">Net Weight</label>
                                <input type="text" class="form-control" value="{{ $sales->net_weight ?? '-' }}" readonly>
                            </div>
                            <div class="col-lg-6 mb-3">
                                <label class="form-label">Party Weight</label>
                                <input type="text" class="form-control" value="{{ $sales->party_weight ?? '-' }}" readonly>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6 mb-3">
                                <label class="form-label">Carting Type</label>
                                <input type="text" class="form-control" value="{{ $sales->carting_type == '0' ? 'No' : 'Yes' }}" readonly>
                            </div>
                            <div class="col-lg-6 mb-3">
                                <label class="form-label">Carting Rate</label>
                                <input type="text" class="form-control" value="{{ $sales->carting_rate ?? '-' }}" readonly>
                            </div>
                        </div>
                        <hr class="mt-0 border border-2 border-primary">

                        <div class="row">
                            <div class="col-lg-4 mb-3">
                                <label class="form-label">Material Name</label>
                                <input type="text" class="form-control" value="{{ $sales->material->name ?? '-' }}" readonly>
                            </div>
                            <div class="col-lg-4 mb-3">
                                <label class="form-label">Loading Name</label>
                                <input type="text" class="form-control" value="{{ $sales->loading->name ?? '-' }}" readonly>
                            </div>
                            <div class="col-lg-4 mb-3">
                                <label class="form-label">Place Name</label>
                                <input type="text" class="form-control" value="{{ $sales->place->name ?? '-' }}" readonly>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-4 mb-3">
                                <label class="form-label">Party Name</label>
                                <input type="text" class="form-control" value="{{ $sales->party->name ?? '-' }}" readonly>
                            </div>
                            <div class="col-lg-4 mb-3">
                                <label class="form-label">Royalty Name</label>
                                <input type="text" class="form-control" value="{{ $sales->royalty->name ?? 'NO' }}" readonly>
                            </div>
                            <div class="col-lg-4 mb-3">
                                <label class="form-label">Royalty Number</label>
                                <input type="text" class="form-control" value="{{ $sales->royalty_number ?? '-' }}" readonly>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-4 mb-3">
                                <label class="form-label">Royalty Tone</label>
                                <input type="text" class="form-control" value="{{ $sales->royalty_tone ?? '-' }}" readonly>
                            </div>
                            <div class="col-lg-4 mb-3">
                                <label class="form-label">Driver Name</label>
                                <input type="text" class="form-control" value="{{ $sales->driver->name ?? '-' }}" readonly>
                            </div>
                            <div class="col-lg-4 mb-3">
                                <label class="form-label">Carting</label>
                                <input type="text" class="form-control" value="{{ $sales->carting_id == 0 ? 'Carting' : 'Self' }}" readonly>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-12 mb-3">
                                <label class="form-label">Note</label>
                                <textarea class="form-control" rows="3" readonly>{{ $sales->note ?? '' }}</textarea>
                            </div>
                        </div>

                        <div class="row">
                        </div>
                    </form>
                    <!-- Form End -->
                </div>
            </div>
        </div>
    </div>
</div>