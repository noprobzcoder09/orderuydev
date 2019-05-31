<?php
namespace App\Services\Cutover\Data;

use App\Models\DeliveryTimings as Model;

Class Timings
{   
    public function __construct()
    {
        $this->timing = new Model;
    }

    public function getSchedule()
    {
        return $this->timing->where('disabled',0)->get();
    }

    public function getByDay(string $day)
    {
        return $this->timing->where('cutoff_day',$day);
    }

    public function getByDayTime(string $day, $time)
    {
        return $this->timing
        ->where('cutoff_day',$day)
        ->where('cutoff_time',$time);
    }
    
}
