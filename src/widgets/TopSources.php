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
class TopSources extends Widget
{

    // Public Properties
    // =========================================================================

    public $limit = 4;
    public $timePeriod = '30d';

    // Static Methods
    // =========================================================================

    protected function defineRules(): array
    {
        $rules = parent::defineRules();
        $rules[] = [['limit'], 'integer', 'max' => 20];
        return $rules;
    }

    /**
     * @inheritdoc
     */
    public static function displayName(): string
    {
        return Craft::t('plausible', 'Top Sources');
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
        $title = Craft::t('plausible', 'Top Sources');
        $timePeriod = $this->timePeriod;

        if ($timePeriod) {
            $title = Craft::t('plausible', 'Top Sources - {timePeriod}', [
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
            'plausible/_components/widgets/TopSources/settings',
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

        $cacheKey = 'plausible:topSources'.$this->timePeriod.$this->limit;
        $results = Craft::$app->getCache()->get($cacheKey);
        if (!$results)
        {
            $results = Plausible::$plugin->plausible->getTopSources($this->limit, $this->timePeriod);
            Craft::$app->getCache()->set($cacheKey, $results, 300);
        }

        return Craft::$app->getView()->renderTemplate(
            'plausible/_components/widgets/TopSources/body',
            [
                'results' => $results
            ]
        );
    }
}
