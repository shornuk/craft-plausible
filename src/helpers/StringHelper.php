<?php

namespace shornuk\plausible\helpers;

use craft\helpers\StringHelper as CraftStringHelper;

class StringHelper extends CraftStringHelper
{
    public static function timeLabelize($value = '30d'): string
    {
        $periods = array(
            "12mo" => "Last 12 months",
            "6mo" => "Last 6 months",
            "month" => "This month",
            "30d" => "Last 30 Days",
            "7d" => "Last 7 Days",
            "day" => "Today"
        );
        return $periods[$value];
    }

    public static function getTimeDimensionFromInterval($interval = '30d'): string
    {
        dump($interval);
        return match (true) {
    		$interval === '12mo' || $interval === '6mo' => 'time:month',
            $interval === 'day' => 'time:hour',
	        default => 'time:day',
	    };
    }
}
