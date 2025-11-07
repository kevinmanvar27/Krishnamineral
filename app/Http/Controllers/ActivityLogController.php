<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;
use Illuminate\Pagination\LengthAwarePaginator;

class ActivityLogController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view-activity-log');
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
        
        return view('activity-log.show', compact('activity'));
    }
}