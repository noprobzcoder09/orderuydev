<?php

namespace App\Services\Reports;

Class Joins
{     
	public static function isJoined($query, $table)
	{
	    $joins = collect($query->getQuery()->joins);
	    return $joins->pluck('table')->contains($table);
	}
}

