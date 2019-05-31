<?php

namespace App\Services\Cutover\Traits;

use App\Repository\MealsRepository;
use App\Repository\ProductPlanRepository;
use App\Repository\CustomerRepository;
use App\Services\Customer;
use App\Services\Log;

Trait ReportsMailingProvider
{   
    public function sendReportToMailProvider(int $deliveryTimingsId)
    {   
        if($this->isSendReportLog($deliveryTimingsId)) {
            if (count($this->successBillingStorage) > 0) {

                \App\Jobs\AutoEmailReport::dispatch('Last Cycle', $deliveryTimingsId)
                ->delay(now()->addMinutes(1));
                echo "LAST CYCLE REPORT EMAIL SENT!</br>";
                $this->logReport($deliveryTimingsId);
            }
        }
    }

    private function isSendReportLog($deliveryTimingsId)
    {
        $log = new Log('report_log','logs/report.log');

        if (!file_exists(storage_path()."/logs/report.log")) {
            $log->info('Report Log Created.');
        }

        $file = file(storage_path()."/logs/report.log");
        
        foreach(array_reverse($file) as $record) {
            $record = explode('|', $record);
            if (count($record) < 2) continue;
            $deliveryTimingsIdLine = explode(' ', $record[0])[3];
            $date = explode(' ', $record[1])[0];

            $now = new \DateTime($this->cutoverDate);
            $date = new \DateTime($date);
            if ($deliveryTimingsId == $deliveryTimingsIdLine) {
                if ($date == $now) {
                    return false;
                }
            }
        }

        return true;
    }

    private function logReport($deliveryTimingsId)
    {
        $log = new Log('report_log','logs/report.log');
        $log->info($deliveryTimingsId.'|'.$this->cutoverDate);
    }
}
