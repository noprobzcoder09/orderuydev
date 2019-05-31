<?php

namespace App\Services\Reports\AutoEmail;

use App\Services\Reports\Config;
use App\Services\Reports\Types;
use App\Services\Reports\Parameters;
use App\Services\Reports\AutoEmail\Request;
use App\Services\Reports\ReportsCreator;
use App\Services\Reports\Reports as Rpt;
use Configurations as Configuration;
use App\Services\Session\Session as SessionStorage;
use App\Repository\TimingRepository;

Class AutoEmail
{     
    use Config, Rpt, Types, Parameters, ReportsCreator;

    const generateUrl = 'reports/generate';
    const timingsUrl = 'reports/timings';
    const view = 'pages.reports.';
    const exportTo = 'reports/';

    private $setTimings;
    private $setParameters;

    public function __construct($setParameters, $setTimings)
    {
        $this->request = new Request;
        $this->setParameters = $setParameters;
        $this->setTimings = $setTimings;
    }

    protected function getRequestTitle()
    {
        return str_replace('/','-',$this->request->getParameter());
    }

    protected function getExportTo()
    {
        return self::exportTo;
    }

    public function handle() {

       
        $this->request->setParameter($this->setParameters); 
        $this->request->setTiming($this->setTimings); 
        
        $this->generate();
        
        //send email
        $configuration = new Configuration;
        $sessionStorage = new SessionStorage('report-file');
        $timingRepo = new TimingRepository;
        $file = $sessionStorage->get();
        
        if (empty($file)) return;

		if (!empty($configuration->getAdminEmails())) {
			
			$emails = explode(',', $configuration->getAdminEmails());
			$timing = $timingRepo->get($this->request->getTiming());

            $deliveryTimingDesc = ($timing->delivery_day ?? '').
            ' Delivery, Order By Previous '. 
            ($timing->cutoff_day ?? '').' '.
            date('h:iA', strtotime(($timing->cutoff_time ?? 0)));

			\Mail::to($emails)
				->queue(
                    new \App\Mail\AdminReportEmail(
                        $file[0],
                        $deliveryTimingDesc
                    )
                );
			
		}		

        $sessionStorage->destroy();
    }

  
}

