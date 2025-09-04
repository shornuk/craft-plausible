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
use craft\helpers\App;
use craft\base\Component;

use GuzzleHttp\Exception\RequestException;
use Psr\Http\Message\ResponseInterface;


/**
 * @author    Sean Hill
 * @package   Plausible
 * @since     1.0.0
 */
class PlausibleService extends Component
{
    // Public Properties
    // =========================================================================

    public const DEFAULT_DATE_RANGE = '30d';
    public const DEFAULT_METRICS = [
        'visitors',
        'visits',
        'pageviews',
    ];

    public $client;
    public $settings;

    // Private Properties
    // =========================================================================


    // Public Methods
    // =========================================================================

    public function init(): void
    {
        parent::init();
        $this->settings = Plausible::$plugin->getSettings();
        if ($this->client === null) {
            $this->client = Craft::createGuzzleClient([
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Bearer ' . App::parseEnv($this->settings->apiKey),
                ],
                'base_uri' => App::parseEnv($this->settings->baseUrl).'/api/v2/',
            ]);
        }
    }

    public function siteId(): bool|string|null
    {
        return App::parseEnv($this->settings->siteId);
    }

    /**
     * @param array $data
//     * @return object|null|string
     */
    public function query(array $data = [])
    {
        $data['site_id'] = $data['site_id'] ?? $this->siteId();
        $data['metrics'] = $data['metrics'] ?? self::DEFAULT_METRICS;
        $data['date_range'] = $data['date_range'] ?? self::DEFAULT_DATE_RANGE;
        $data['include'] = array_merge($data['include'] ?? [], [ 'total_rows' => true ]);

        try {

            $response = $this->client->request('POST', 'query', [ 'json' => $data ]);
            return $this->_handleResponse($response);

        } catch (RequestException $e) {

            Craft::warning('Plausible API (v2) Error: ' . $e->getMessage());
            return [
                'error' => $e->getMessage()
            ];
        }

    }

    public function getTimeIntervals(): array
    {
        return ['12mo','6mo','month','30d','7d','day'];
    }

    private function _handleResponse($response, mixed $default = null)
    {
        if ($response instanceof ResponseInterface) {
            return json_decode($response->getBody()->getContents(), false);
        }
        return $default;
    }

}