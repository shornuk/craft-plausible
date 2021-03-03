<?php
/**
 * Plausible plugin for Craft CMS 3.x
 *
 * @link      https://shorn.co.uk
 * @copyright Copyright (c) 2021 Sean Hill
 */

namespace shornuk\plausible\twigextensions;

use shornuk\plausible\Plausible;
use shornuk\plausible\services\PlausibleService;

use Twig_Extension;
use Twig_SimpleFilter;

/**
 * @author    Sean Hill
 * @package   Plausible
 * @since     1.0.0
 */

class PlausibleTwigExtension extends Twig_Extension
{
    public function getFilters(): array
    {
        return [
            new Twig_SimpleFilter('timeLabelize', [$this, 'timeLabelize']),
            new Twig_SimpleFilter('prettyTime', [$this, 'prettyTime']),
            new Twig_SimpleFilter('prettyCount', [$this, 'prettyCount']),
            new Twig_SimpleFilter('asPercentageOf', [$this, 'asPercentageOf']),
        ];
    }

    public function timeLabelize($value)
    {
        return Plausible::$plugin->plausible->timeLabelize($value);
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
        return sprintf("%dh %2dm %02ds", $h, $m, $s);
    }

    public function prettyCount($count)
    {
        $count = number_format($count);
        $input_count = substr_count($count, ',');

        if($input_count != '0')
        {
            if($input_count == '1')
            {
                return substr($count, 0, -4).'k';
            }
            elseif($input_count == '2')
            {
                return substr($count, 0, -8).'mil';
            }
            elseif($input_count == '3')
            {
                return substr($count, 0,  -12).'bil';
            }
            else
            {
                return;
            }
        }
        else
        {
            return $count;
        }
    }
}
