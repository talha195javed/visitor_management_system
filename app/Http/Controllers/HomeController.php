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
            ->join('visitors_employers', 'visitors.id', '=', 'visitors_employers.visitor_id') // First join the visitors_employers table
            ->join('employees', 'visitors_employers.employee_id', '=', 'employees.id') // Then join the employees table
            ->select('visitors.*', 'visitors_employers.purpose', 'employees.name AS employer_name') // Select the necessary fields
            ->get();

        // Get all employees
        $allEmployees = Employee::all();

        // Get visitors who are currently checked in today (check_out_time is NULL)
        $checkedInVisitors = Visitor::whereNull('check_out_time')
            ->whereDate('check_in_time', today())
            ->get();


        // Get visitors who have checked out today (check_out_time is NOT NULL)
        $checkedOutVisitors = Visitor::whereNotNull('check_out_time')
            ->whereDate('check_out_time', today())
            ->get();


        // Get total check-ins for today
        $totalCheckInsToday = Visitor::whereDate('check_in_time', today())->count();

        // Get total check-ins for last week
        $totalCheckInsLastWeek = Visitor::whereBetween('created_at', [
            now()->subWeek()->startOfWeek(),
            now()->subWeek()->endOfWeek()
        ])->count();

// Get total check-ins for last month
        $totalCheckInsLastMonth = Visitor::whereBetween('created_at', [
            now()->subMonthNoOverflow()->startOfMonth(),
            now()->subMonthNoOverflow()->endOfMonth()
        ])->count();


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
            'totalCheckInsLastWeek',
            'totalCheckInsLastMonth',
            'totalCheckInsToday',
            'visitorCheckInTimes',
            'totalCheckedInVisitors',
            'totalCheckedOutVisitors',
            'totalVisitors'
        ));
    }
}
