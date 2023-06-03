<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\DashboardRepository;
use Illuminate\Http\Request;

class DashboardController extends Controller
{

    public DashboardRepository $repository;
    public function __construct(DashboardRepository $repository)
    {
        $this->repository = $repository;
    }

    public function index(){
//        $this->permissionsAllowed("view-dashboard");
        $data["counter"] = $this->repository->getAllCounters();
        return view("admin.dashboard.index", $data);
    }
}

