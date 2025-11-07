@extends('layouts.app')

@section('content')
<div class="wrapper">
    <div class="page-wrapper">
        <div class="page-content">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between">
                            <h4 class="card-title">Activity Log Details</h4>   
                            <a class="btn btn-sm btn-primary" href="{{ route('activity-log.index') }}">
                                <i class="bx bx-arrow-to-left"></i>
                            </a>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <table class="table table-borderless">
                                        <tr>
                                            <th>User:</th>
                                            <td>{{ $activity->causer ? $activity->causer->name : 'System' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Event:</th>
                                            <td>{{ $activity->event }}</td>
                                        </tr>
                                        <tr>
                                            <th>Subject Type:</th>
                                            <td>{{ $activity->subject_type }}</td>
                                        </tr>
                                        <tr>
                                            <th>Subject ID:</th>
                                            <td>{{ $activity->subject_id }}</td>
                                        </tr>
                                        <tr>
                                            <th>Created At:</th>
                                            <td>{{ $activity->created_at->format('Y-m-d H:i:s') }}</td>
                                        </tr>
                                        <tr>
                                            <th>Updated At:</th>
                                            <td>{{ $activity->updated_at->format('Y-m-d H:i:s') }}</td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="col-md-6">
                                    <h5>Changes</h5>
                                    @if(isset($activity->properties['attributes']) || isset($activity->properties['old']))
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th class="bg-primary text-white">Attribute</th>
                                                    <th class="bg-primary text-white">Old Value</th>
                                                    <th class="bg-primary text-white">New Value</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @if(isset($activity->properties['attributes']))
                                                    @foreach($activity->properties['attributes'] as $key => $newValue)
                                                    <tr>
                                                        <td>{{ ucfirst(str_replace('_', ' ', $key)) }}</td>
                                                        <td>
                                                            @if(isset($activity->properties['old'][$key]))
                                                                @if(is_array($activity->properties['old'][$key]))
                                                                    <pre>{{ json_encode($activity->properties['old'][$key], JSON_PRETTY_PRINT) }}</pre>
                                                                @else
                                                                    {{ $activity->properties['old'][$key] }}
                                                                @endif
                                                            @else
                                                                -
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if(is_array($newValue))
                                                                <pre>{{ json_encode($newValue, JSON_PRETTY_PRINT) }}</pre>
                                                            @else
                                                                {{ $newValue }}
                                                            @endif
                                                        </td>
                                                    </tr>
                                                    @endforeach
                                                @elseif(isset($activity->properties['old']))
                                                    @foreach($activity->properties['old'] as $key => $oldValue)
                                                    <tr>
                                                        <td>{{ ucfirst(str_replace('_', ' ', $key)) }}</td>
                                                        <td>
                                                            @if(is_array($oldValue))
                                                                <pre>{{ json_encode($oldValue, JSON_PRETTY_PRINT) }}</pre>
                                                            @else
                                                                {{ $oldValue }}
                                                            @endif
                                                        </td>
                                                        <td>-</td>
                                                    </tr>
                                                    @endforeach
                                                @endif
                                            </tbody>
                                        </table>
                                    @else
                                        <p>No changes recorded.</p>
                                    @endif
                                </div>
                            </div>
                            
                            @if($activity->description)
                            <div class="row mt-4">
                                <div class="col-md-12">
                                    <h5>Description</h5>
                                    <p>{{ $activity->description }}</p>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection