<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Visitor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $allVisitors = DB::table('visitors')
            ->join('visitors_employers', 'visitors.id', '=', 'visitors_employers.visitor_id')
            ->select('visitors.*', 'visitors_employers.purpose')
            ->get();

        // Get all employees
        $allEmployees = Employee::all();

        // Get visitors who are currently checked in (check_out_time is NULL)
        $checkedInVisitors = Visitor::whereNull('check_out_time')->get();

        // Get visitors who have checked out (check_out_time is NOT NULL)
        $checkedOutVisitors = Visitor::whereNotNull('check_out_time')->get();

        // Get total check-ins for today
        $totalCheckInsToday = Visitor::whereDate('check_in_time', today())->count();

        // Get check-in times of visitors
        $visitorCheckInTimes = Visitor::pluck('check_in_time');

        $totalCheckedInVisitors = $checkedInVisitors->count();

        // Get total number of visitors checked out
        $totalCheckedOutVisitors = $checkedOutVisitors->count();

        $totalVisitors = $totalCheckedInVisitors + $totalCheckedOutVisitors;

        return view('home', compact(
            'allVisitors',
            'allEmployees',
            'checkedInVisitors',
            'checkedOutVisitors',
            'totalCheckInsToday',
            'visitorCheckInTimes',
            'totalCheckedInVisitors',
            'totalCheckedOutVisitors',
            'totalVisitors'
        ));
    }
}
