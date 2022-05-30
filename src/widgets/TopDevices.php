<?php
/**
 * Plausible plugin for Craft CMS 3.x
 *
 * @link      https://shorn.co.uk
 * @copyright Copyright (c) 2021 Sean Hill
 */

namespace shornuk\plausible\widgets;

use shornuk\plausible\Plausible;
use shornuk\plausible\services\PlausibleService;
use shornuk\plausible\assetbundles\plausible\PlausibleAsset;

use Craft;
use craft\base\Widget;

/**
 * Top Sources Widget
 *
 * @author    Sean Hill
 * @package   Plausible
 * @since     1.0.0
 */
class TopDevices extends Widget
{

    // Public Properties
    // =========================================================================

    public $timePeriod = '30d';

    // Static Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public static function displayName(): string
    {
        return Craft::t('plausible', 'Top Devices');
    }

    public static function icon(): ?string
    {
        return Craft::getAlias("@shornuk/plausible/assetbundles/plausible/dist/img/Plausible-icon.svg");
    }

    /**
     * @inheritdoc
     */
    public static function maxColspan(): ?int
    {
        return null;
    }

    // Public Methods
    // =========================================================================

    public function getTitle(): ?string
    {
        if (!isset($title)) {
            $title = Craft::t('plausible', 'Top Devices');
        }
        $timePeriod = $this->timePeriod;

        if ($timePeriod) {
            $title = Craft::t('app', 'Top Devices - {timePeriod}', [
                'timePeriod' => Craft::t('plausible', Plausible::$plugin->plausible->timeLabelize($timePeriod)),
            ]);
        }
        return $title;
    }

    /**
     * @inheritdoc
     */
    public function getSettingsHtml(): ?string
    {
        return Craft::$app->getView()->renderTemplate(
            'plausible/_components/widgets/TopDevices/settings',
            [
                'widget' => $this
            ]
        );
    }

    /**
     * @inheritdoc
     */
    public function getBodyHtml(): ?string
    {
        Craft::$app->getView()->registerAssetBundle(PlausibleAsset::class);

        $cacheKey = 'plausible:topDevices'.$this->timePeriod;
        $results = Craft::$app->getCache()->get($cacheKey);
        if (!$results)
        {
            $results = Plausible::$plugin->plausible->getTopDevices($this->timePeriod);
            Craft::$app->getCache()->set($cacheKey, $results, 300);
        }

        $visitorCacheKey = 'plausible:totalVisitors'.$this->timePeriod;
        $visitors = Craft::$app->getCache()->get($visitorCacheKey);
        if (!$visitors)
        {
            $visitors = Plausible::$plugin->plausible->getVisitors($this->timePeriod);
            Craft::$app->getCache()->set($visitorCacheKey, $visitors, 300);
        }

        return Craft::$app->getView()->renderTemplate(
            'plausible/_components/widgets/TopDevices/body',
            [
                'visitors' => $visitors,
                'results' => $results
            ]
        );
    }
}
