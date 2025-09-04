<?php
/**
 * Plausible plugin for Craft CMS 3.x
 *
 * @link      https://shorn.co.uk
 * @copyright Copyright (c) 2021 Sean Hill
 */

namespace shornuk\plausible\twigextensions;

use shornuk\plausible\Plausible;
use shornuk\plausible\helpers\StringHelper;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig_Extension;
use Twig_SimpleFilter;

/**
 * @author    Sean Hill
 * @package   Plausible
 * @since     1.0.0
 */

class PlausibleTwigExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            new TwigFilter('timeLabelize', [$this, 'timeLabelize']),
            new TwigFilter('prettyTime', [$this, 'prettyTime']),
            new TwigFilter('prettyCount', [$this, 'prettyCount']),
            new TwigFilter('asPercentageOf', [$this, 'asPercentageOf']),
            new TwigFilter('prepUri', [$this, 'prepUri']),
        ];
    }

    public function prepUri($string)
    {

        return trim($string, '/');
    }

    public function timeLabelize($value)
    {
        return StringHelper::timeLabelize($value);
    }

    public function asPercentageOf($value, $topValue)
    {
        return $percentage = number_format(($value/$topValue) * 100, 2);
    }

    public function prettyTime($seconds)
    {
        $h = floor($seconds / 3600);
        $m = floor(($seconds % 3600) / 60);
        $s = $seconds % 60;

        $hours = sprintf("%dh", $h);
        $minsAndSecs = sprintf("%2dm %02ds",$m,$s);
        return ($hours != '0h' ? $hours.' ' : null).$minsAndSecs;
    }

    public function prettyCount($value)
    {
        if ($value >= 1000 && $value < 1000000)
        {
            $thousands = number_format($value/100,0,'','') / 10;
            if ($thousands == number_format($thousands) || $value >= 100000)
            {
                return number_format($thousands).'k';
            }
            else
            {
                return $thousands.'k';
            }
        }

        if ($value >= 1000000 && $value < 1000000000)
        {
            $millions = number_format($value/100000,0,'','') / 10;
            if ($millions == number_format($millions))
            {
                return number_format($millions).'m';
            }
            else
            {
                return $millions.'m';
            }
        }
        return number_format($value);
    }
}
