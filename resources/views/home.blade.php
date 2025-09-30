@extends('layouts.app')

@section('content')
<div class="wrapper">
    <div class="page-wrapper">
        <div class="page-content">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">{{ __('Dashboard') }}</div>
        
                        <div class="card-body">
                            {{ __('You are logged in! '.Auth::user()->name) }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
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
                    @if (session('success'))
                        <div class="alert alert-success" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif
                    <div class="card">
                        <div class="card-header">Change Password</div>
                        <div class="card-body">
                            <form action="{{ route('user.changePassword') }}" method="POST">
                            @csrf
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="old_password" class="form-label">Current Password</label>
                                        <div class="input-group" id="show_hide_old_password">
                                            <input type="password" class="form-control border-end-0" id="old_password" name="old_password" required placeholder="Enter Current Password"> <a href="javascript:;" class="input-group-text bg-transparent"><i class='bx bx-hide'></i></a>
                                        </div>
                                        @if ($errors->has('old_password'))
                                            <span class="text-danger">{{ $errors->first('old_password') }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="password" class="form-label">New Password</label>
                                        <div class="input-group" id="show_hide_new_password">
                                            <input type="password" class="form-control border-end-0" id="password" name="password" required placeholder="Enter New Password"> <a href="javascript:;" class="input-group-text bg-transparent"><i class='bx bx-hide'></i></a>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="password_confirmation" class="form-label">Confirm New Password</label>
                                        <div class="input-group" id="show_hide_confirm_password">
                                            <input type="password" class="form-control border-end-0" id="password_confirmation" name="password_confirmation" required placeholder="Confirm New Password"> <a href="javascript:;" class="input-group-text bg-transparent"><i class='bx bx-hide'></i></a>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <button type="submit" class="btn btn-primary">Change Password</button>
                                    </div>
                                </div>
                            </form>
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
		$(document).ready(function () {
			$("#show_hide_old_password a").on('click', function (event) {
				event.preventDefault();
				if ($('#show_hide_old_password input').attr("type") == "text") {
					$('#show_hide_old_password input').attr('type', 'password');
					$('#show_hide_old_password i').addClass("bx-hide");
					$('#show_hide_old_password i').removeClass("bx-show");
				} else if ($('#show_hide_old_password input').attr("type") == "password") {
					$('#show_hide_old_password input').attr('type', 'text');
					$('#show_hide_old_password i').removeClass("bx-hide");
					$('#show_hide_old_password i').addClass("bx-show");
				}
			});
            
            $("#show_hide_new_password a").on('click', function (event) {
				event.preventDefault();
				if ($('#show_hide_new_password input').attr("type") == "text") {
					$('#show_hide_new_password input').attr('type', 'password');
					$('#show_hide_new_password i').addClass("bx-hide");
					$('#show_hide_new_password i').removeClass("bx-show");
				} else if ($('#show_hide_new_password input').attr("type") == "password") {
					$('#show_hide_new_password input').attr('type', 'text');
					$('#show_hide_new_password i').removeClass("bx-hide");
					$('#show_hide_new_password i').addClass("bx-show");
				}
			});
            
            $("#show_hide_confirm_password a").on('click', function (event) {
				event.preventDefault();
				if ($('#show_hide_confirm_password input').attr("type") == "text") {
					$('#show_hide_confirm_password input').attr('type', 'password');
					$('#show_hide_confirm_password i').addClass("bx-hide");
					$('#show_hide_confirm_password i').removeClass("bx-show");
				} else if ($('#show_hide_confirm_password input').attr("type") == "password") {
					$('#show_hide_confirm_password input').attr('type', 'text');
					$('#show_hide_confirm_password i').removeClass("bx-hide");
					$('#show_hide_confirm_password i').addClass("bx-show");
				}
			});
		});
	</script>
@endpush