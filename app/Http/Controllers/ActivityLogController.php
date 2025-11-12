<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;
use App\Models\Sales;
use App\Models\Purchase;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Log;

class ActivityLogController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view-activity-log')->except('relatedLogs');
    }
    
    /**
     * Display a listing of the activity logs.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = Activity::with('causer')->orderBy('created_at', 'desc');
        
        // Filter by causer (user)
        if ($request->has('causer') && $request->causer != '') {
            $query->where('causer_id', $request->causer);
        }
        
        // Filter by date range
        if ($request->has('from_date') && $request->from_date != '') {
            $query->whereDate('created_at', '>=', $request->from_date);
        }
        
        if ($request->has('to_date') && $request->to_date != '') {
            $query->whereDate('created_at', '<=', $request->to_date);
        }
        
        $activities = $query->paginate(15);
        
        return view('activity-log.index', compact('activities'));
    }

    /**
     * Display the specified activity log.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $activity = Activity::with('causer')->findOrFail($id);
        $sales = Sales::where('id', $activity->subject_id)->first();
        $purchase = Purchase::where('id', $activity->subject_id)->first();
        
        return view('activity-log.show', compact('activity', 'sales', 'purchase'));
    }

    /**
     * Fetch all activity logs related to a specific subject.
     *
     * @param  Request  $request
     * @param  string  $subjectType
     * @param  int  $subjectId
     * @return \Illuminate\Http\Response
     */
    public function relatedLogs(Request $request, $subjectType, $subjectId)
    {
        $oldSubjectType = $subjectType;
        $newSubjectType = preg_replace('/(?<!^)([A-Z])/', '\\\\$1', $oldSubjectType);
        $logs = Activity::where('subject_type', $newSubjectType)
                       ->where('subject_id', $subjectId)
                       ->with('causer')
                       ->orderBy('created_at', 'desc')
                       ->get();
        
        // Enhance logs with model data for proper date formatting
        $logs->each(function ($log) {
            if ($log->subject_type === 'App\Models\Sales') {
                $model = Sales::find($log->subject_id);
                if ($model) {
                    $log->model_created_at = $model->created_at;
                    $log->model_updated_at = $model->updated_at;
                }
            } elseif ($log->subject_type === 'App\Models\Purchase') {
                $model = Purchase::find($log->subject_id);
                if ($model) {
                    $log->model_created_at = $model->created_at;
                    $log->model_updated_at = $model->updated_at;
                }
            }
        });
        
        return response()->json($logs);
    }
}