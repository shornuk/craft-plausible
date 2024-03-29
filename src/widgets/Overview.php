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
 * Overview Widget
 *
 * @author    Sean Hill
 * @package   Plausible
 * @since     1.0.0
 */
class Overview extends Widget
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
        return Craft::t('plausible', 'Overview');
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
        $title = Craft::t('plausible', 'Overview');
        $timePeriod = $this->timePeriod;

        if ($timePeriod) {
            $title = Craft::t('plausible', 'Overview - {timePeriod}', [
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
            'plausible/_components/widgets/Overview/settings',
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

        $cacheKey = 'plausible:overview'.$this->timePeriod;
        $results = Craft::$app->getCache()->get($cacheKey);

        if (!$results)
        {
            $results = Plausible::$plugin->plausible->getOverview($this->timePeriod);
            Craft::$app->getCache()->set($cacheKey, $results, 300);
        }

        $timeCacheKey = 'plausible:timeseries'.$this->timePeriod;
        $timeResults = Craft::$app->getCache()->get($timeCacheKey);

        if (!$timeResults)
        {
            $timeResults = Plausible::$plugin->plausible->getTimeSeries($this->timePeriod);
            Craft::$app->getCache()->set($timeCacheKey, $timeResults, 300);
        }

        return Craft::$app->getView()->renderTemplate(
            'plausible/_components/widgets/Overview/body',
            [
                'period' => $this->timePeriod,
                'results' => $results,
                'timeResults' => $timeResults
            ]
        );
    }
}
