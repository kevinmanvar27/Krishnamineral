@extends('layouts.app')

@section('content')

    <div class="wrapper">
        <div class="page-wrapper">   
            <div class="page-content">
                <!--breadcrumb-->
                <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
                    <div class="breadcrumb-title pe-3">Notifications</div>
                    <div class="ps-3">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb mb-0 p-0">
                                <li class="breadcrumb-item"><a href="{{ url('/home') }}"><i class="bx bx-home-alt"></i></a>
                                </li>
                                <li class="breadcrumb-item active" aria-current="page">All Notifications</li>
                            </ol>
                        </nav>
                    </div>
                </div>
                <!--end breadcrumb-->
                
                <div class="row">
                    <div class="row mb-3">
                        <!-- [Notifications] start -->
                        <div class="col-xxl-12">
                            <div class="card stretch stretch-full">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h5 class="card-title mb-0">Driver Inactive Notifications</h5>
                                    <div>
                                        @if(auth()->user()->unreadNotifications->count() > 0)
                                            <form method="POST" action="{{ route('notifications.markAllAsRead') }}" style="display: inline;">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-primary">
                                                    Mark All as Read
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>  
                                <div class="card-body">
                                    @if($notifications->count() > 0)
                                        <div class="table-responsive">
                                            <table class="table table-striped table-bordered" style="width:100%" id="notificationsTable">
                                                <thead>
                                                    <tr class="border-b">
                                                        <th>Message</th>
                                                        <th>Date</th>
                                                        <th>Status</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="notificationsTableBody">
                                                    @foreach($notifications as $notification)
                                                        <tr class="{{ $notification->read_at ? '' : 'table-warning' }}" id="notification-{{ $notification->id }}">
                                                            <td>
                                                                {{ $notification->data['message'] ?? 'Notification' }}
                                                            </td>
                                                            <td>{{ $notification->created_at->format('d M Y, H:i') }}</td>
                                                            <td>
                                                                @if(!$notification->read_at)
                                                                    <span class="badge bg-danger">Unread</span>
                                                                @else
                                                                    <span class="badge bg-success">Read</span>
                                                                @endif
                                                            </td>
                                                            <td class="d-flex">
                                                                @if(isset($notification->data['purchase_id']))
                                                                    <a href="{{ route('purchase.show', $notification->data['purchase_id']) }}" class="btn btn-sm btn-info me-2">
                                                                        <i class="lni lni-eye text-white"></i>
                                                                    </a>
                                                                @elseif(isset($notification->data['minutes_threshold']))
                                                                    <a href="{{ route('users.edit', $notification->data['user_id']) }}" class="btn btn-sm btn-info me-2">
                                                                        <i class="lni lni-eye text-white"></i>
                                                                    </a>
                                                                @else
                                                                    <span class="btn btn-sm btn-secondary disabled me-2">
                                                                        <i class="lni lni-eye text-white"></i>
                                                                    </span>
                                                                @endif
                                                                @if(!$notification->read_at)
                                                                    <button class="btn btn-sm btn-success mark-as-read" data-id="{{ $notification->id }}">
                                                                        Mark as Read
                                                                    </button>
                                                                @endif
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    @else
                                        <div class="alert alert-info">
                                            No notifications found.
                                        </div>
                                    @endif
                                </div>
                                <div class="card-footer">
                                    <div class="d-flex justify-content-start" id="paginationLinks">
                                        {{ $notifications->links() }}
                                    </div>
                                </div>
                            </div>
                            <!-- [Notifications] end -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Handle mark as read button clicks
    $(document).on('click', '.mark-as-read', function() {
        var notificationId = $(this).data('id');
        var button = $(this);
        
        $.ajax({
            url: '/notifications/mark-as-read/' + notificationId,
            type: 'POST',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                // Update the row to show as read
                $('#notification-' + notificationId).removeClass('table-warning');
                $('#notification-' + notificationId + ' .badge').removeClass('bg-danger').addClass('bg-success').text('Read');
                button.remove(); // Remove the button
                
                // Update the notification count in the header
                var countElement = $('.alert-count');
                if (countElement.length > 0) {
                    var count = parseInt(countElement.text());
                    if (count > 1) {
                        countElement.text(count - 1);
                    } else {
                        countElement.hide();
                    }
                }
            },
            error: function(xhr) {
                console.log('Error marking notification as read');
                console.log(xhr.responseText);
            }
        });
    });
    
    // Handle pagination clicks
    $(document).on('click', '#paginationLinks .pagination a', function(e) {
        e.preventDefault();
        var page = $(this).attr('href').split('page=')[1];
        fetchNotifications(page);
    });
    
    function fetchNotifications(page = 1) {
        $.ajax({
            url: '/notifications?page=' + page,
            type: 'GET',
            success: function(response) {
                // Parse the response as HTML
                var responseHtml = $(response);
                
                // Update the table body
                var newBody = responseHtml.find('#notificationsTableBody').html();
                if (newBody) {
                    $('#notificationsTableBody').html(newBody);
                }
                
                // Update pagination
                var newPagination = responseHtml.find('#paginationLinks').html();
                if (newPagination) {
                    $('#paginationLinks').html(newPagination);
                }
            },
            error: function(xhr) {
                console.log('Error fetching notifications');
                console.log(xhr.responseText);
            }
        });
    }
});
</script>
@endpush