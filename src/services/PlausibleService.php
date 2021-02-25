<?php
/**
 * Plausible plugin for Craft CMS 3.x
 *
 * A wrapper around the Plausible API that fetches the analytics into your dashboard in a pretty way.
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

    public function init()
    {
        parent::init();
        $this->settings = Plausible::$plugin->getSettings();
    }

    public function getTopPages($limit = 5, $timePeriod = '6mo')
    {
        $url = 'https://plausible.io/api/v1/stats/breakdown?site_id='.Craft::parseEnv($this->settings->siteId).'&period='.$timePeriod.'&property=event:page&limit='.$limit;

        $headers = [
            'Authorization' => 'Bearer '.Craft::parseEnv($this->settings->apiKey),
            'Accept'        => 'application/json',
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

        return $result;

    }
}
