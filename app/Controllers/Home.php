<?php

namespace App\Controllers;
use App\Models\CommonModel;

class Home extends BaseController
{
    public function __construct()
    {
        $this->commonModel = new CommonModel();
    }

    public function index(): string
    {
        $dashboardData = $this->commonModel->getDashboardData();

        return view('Homepage', $dashboardData);
    }
}
