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

        $this->middleware('permission:dashboard', ['only' => ['index']]);
        $this->middleware('permission:studentDashboard', ['only' => ['studentDashboard']]);
        $this->middleware('permission:peerDashboard', ['only' => ['peerDashboard']]);
        $this->middleware('permission:supervisorDashboard', ['only' => ['supervisorDashboard']]);
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
}
