<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;

use App\Services\Reports\Config;
use App\Services\Reports\Types;
use App\Services\Reports\Parameters;
use App\Services\Reports\Request;
use App\Services\Reports\ReportsCreator;
use App\Services\Reports\Reports as Rpt;

class Reports extends Controller
{	

    /*
    |--------------------------------------------------------------------------
    | Reports Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling meals and meta
    | includes a Class Services for assisting the application events and actions
    |
    */

    use Config, Rpt, Types, Parameters, ReportsCreator;

    const generateUrl = 'reports/generate';
    const timingsUrl = 'reports/timings';
    const view = 'pages.reports.';
    const exportTo = 'reports/';

    public function __construct()
    {
        $this->request = new Request;
    }

    public function index(): string
    {
        return view(self::view.'index')->with($this->getConfig());
    }

    protected function getRequestTitle()
    {
        return str_replace('/','-',$this->request->getParameter());
    }

    protected function getExportTo()
    {
        return self::exportTo;
    }
}
