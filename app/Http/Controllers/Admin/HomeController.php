<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

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

        $this->middleware('permission:dashboard-read', ['only' => ['index', 'studentDashboard', 'facultyDashboard']]);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $pageTitle = 'Dashboard';
        return view('admin.home', compact('pageTitle'));
    }

    public function studentDashboard()
    {
        $pageTitle = 'Dashboard';
        return view('users.studentDashboard', compact('pageTitle'));
    }

    public function facultyDashboard()
    {
        $pageTitle = 'Dashboard';
        return view('users.facultyDashboard', compact('pageTitle'));
    }
}
