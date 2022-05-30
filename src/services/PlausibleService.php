<?php
/**
 * Plausible plugin for Craft CMS 3.x
 *
 * @link      https://shorn.co.uk
 * @copyright Copyright (c) 2021 Sean Hill
 */

namespace shornuk\plausible\services;

use shornuk\plausible\Plausible;

use Craft;
use craft\base\Component;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;


/**
 * @author    Sean Hill
 * @package   Plausible
 * @since     1.0.0
 */
class PlausibleService extends Component
{
     // Public Properties
     // =========================================================================

    public $settings;

    // Private Properties
    // =========================================================================


    // Public Methods
    // =========================================================================

    public function init(): void
    {
        parent::init();
        $this->settings = Plausible::$plugin->getSettings();
    }

    public function getCurrentVisitors()
    {
        $format = 'https://plausible.io/api/v1/stats/realtime/visitors?site_id=%1$s';
        $url = sprintf($format, Craft::parseEnv($this->settings->siteId));
        return $this->queryApi($url);
    }


    public function getTopPages($limit = 5, $timePeriod = '30d')
    {

        $format = 'https://plausible.io/api/v1/stats/breakdown?site_id=%1$s&period=%2$s&property=event:page&limit=%3$s';
        $url = sprintf($format, Craft::parseEnv($this->settings->siteId), $timePeriod, $limit);

        return $this->queryApi($url);

    }

    public function getTopSources($limit = 5, $timePeriod = '30d')
    {

        $format = 'https://plausible.io/api/v1/stats/breakdown?site_id=%1$s&period=%2$s&property=visit:source&limit=%3$s';
        $url = sprintf($format, Craft::parseEnv($this->settings->siteId), $timePeriod, $limit);

        return $this->queryApi($url);

    }

    public function getTopBrowsers($limit = 5, $timePeriod = '30d')
    {

        $format = 'https://plausible.io/api/v1/stats/breakdown?site_id=%1$s&period=%2$s&property=visit:browser&limit=%3$s';
        $url = sprintf($format, Craft::parseEnv($this->settings->siteId), $timePeriod, $limit);
        return $this->queryApi($url);
    }

    public function getTopDevices($timePeriod = '30d')
    {

        $format = 'https://plausible.io/api/v1/stats/breakdown?site_id=%1$s&period=%2$s&property=visit:device';
        $url = sprintf($format, Craft::parseEnv($this->settings->siteId), $timePeriod);

        return $this->queryApi($url);

    }

    public function getOverview($timePeriod = '30d')
    {

        // $format = 'https://plausible.io/api/v1/stats/aggregate?site_id=%1$s&period=%2$s&metrics=visitors,pageviews,bounce_rate,visit_duration';
        $format = 'https://plausible.io/api/v1/stats/aggregate?site_id=%1$s&period=%2$s&compare=previous_period&metrics=visitors,pageviews,bounce_rate,visit_duration';
        $url = sprintf($format, Craft::parseEnv($this->settings->siteId), $timePeriod);

        return $this->queryApi($url);

    }

    public function getVisitors($timePeriod = '30d')
    {

        $format = 'https://plausible.io/api/v1/stats/aggregate?site_id=%1$s&period=%2$s&metrics=visitors';
        $url = sprintf($format, Craft::parseEnv($this->settings->siteId), $timePeriod);

        return $this->queryApi($url);

    }

    public function getTimeSeries($timePeriod = '30d')
    {

        $format = 'https://plausible.io/api/v1/stats/timeseries?site_id=%1$s&period=%2$s';
        $url = sprintf($format, Craft::parseEnv($this->settings->siteId), $timePeriod);

        return $this->queryApi($url);

    }

    public function queryApi($url)
    {
        if (!$url) return false;

        $headers = [
            'Authorization' => 'Bearer '.Craft::parseEnv($this->settings->apiKey),
            'Accept' => 'application/json',
        ];

        try {
            $guzzleClient = new Client;
            $response = $guzzleClient->request('GET', $url, [
                'headers' => $headers
            ]);
            $responseBody = $response->getBody();
            $result = json_decode($responseBody->getContents());
        } catch (RequestException $e){
            $result = json_decode($e->getResponse()->getBody()->getContents());
        }

        if (is_object($result)) {
            if (property_exists($result, 'results')) {
                return $result->results;
            }
        }
        return $result;
    }


    public function timeLabelize($value = null)
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

}
