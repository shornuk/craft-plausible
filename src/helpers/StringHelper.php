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
        return match (true) {
    		$interval === '12mo' || $interval === '6mo' => 'time:month',
            $interval === 'day' => 'time:hour',
	        default => 'time:day',
	    };
    }
    
    public static function getPreviousPeriodForInterval(string $interval, ?string $today = null): array
    {
        // Optionally allow overriding "today" for testing/predictability
        $now = $today ? new \DateTimeImmutable($today) : new \DateTimeImmutable('today');

        switch ($interval) {
            case 'day':
                $end = $now->modify('-1 day');
                $start = $end;
                break;

            case '7d':
                $end = $now->modify('-8 day'); // 7 days before yesterday
                $start = $now->modify('-14 day');
                break;

            case '28d':
                $end = $now->modify('-29 day');
                $start = $now->modify('-56 day');
                break;

            case '30d':
                $end = $now->modify('-29 day');
                $start = $now->modify('-58 day');
                break;

            case 'month':
                // Start of this month
                $startOfThisMonth = $now->modify('first day of this month');
                // Start of previous month
                $start = $startOfThisMonth->modify('-1 month');
                // End of previous month
                $end = $startOfThisMonth->modify('-1 day');
                break;

            case '6mo':
                $startOfThisMonth = $now->modify('first day of this month');
                $start = $startOfThisMonth->modify('-12 month');
                $end = $startOfThisMonth->modify('-7 month')->modify('-1 day');
                break;

            case '12mo':
                $startOfThisMonth = $now->modify('first day of this month');
                $start = $startOfThisMonth->modify('-24 month');
                $end = $startOfThisMonth->modify('-12 month')->modify('-1 day');
                break;

            default:
                // Fallback to previous '30d'
                $end = $now->modify('-29 day');
                $start = $now->modify('-58 day');
                break;
        }

        return [
            $start->format('Y-m-d'),
            $end->format('Y-m-d'),
        ];
    }
}