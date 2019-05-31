<?php

namespace App\Services\Cutover\Cycle;

use App\Services\Cutover\Data\Cycle;
use App\Services\Cutover\Data\Timings;

Class Timing
{   
    private $day;
    public function __construct(string $day, $time)
    {
        $this->cycle = new Cycle;
        $this->timing = new Timings;
        $this->day = $day;
        $this->time = $time;
    }

    public function get()
    {
        $ids = [];
        foreach($this->timing->getByDay($this->day)->get() as $row)
        {
            $ids[] = $row->id;
        }
        return $ids;
    }
}
