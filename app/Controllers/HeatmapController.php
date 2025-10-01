<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class HeatmapController extends BaseController
{
    public function index()
    {
        return view('heatmap_view');
        //return view('heatmap_view2');
    }
}
