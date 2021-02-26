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
use shornuk\plausible\assetbundles\widgets\toppages\TopPagesAsset;

use Craft;
use craft\base\Widget;

/**
 * Top Pages Widget
 *
 * @author    Sean Hill
 * @package   Plausible
 * @since     1.0.0
 */
class TopPages extends Widget
{

    // Public Properties
    // =========================================================================

    public $limit = 5;
    public $timePeriod = '6mo';

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
        return Craft::t('plausible', 'Top Pages');
    }

    /**
     * @inheritdoc
     */
    public static function icon()
    {
        return Craft::getAlias("@shornuk/plausible/assetbundles/widgets/toppages/dist/img/Plausible-icon.svg");
    }

    /**
     * @inheritdoc
     */
    public static function maxColspan()
    {
        return null;
    }

    // Public Methods
    // =========================================================================

    public function getTitle(): string
    {
        if (!isset($title)) {
            $title = Craft::t('plausible', 'Top Pages');
        }
        $timePeriod = $this->timePeriod;

        if ($timePeriod) {
            $title = Craft::t('app', 'Top Pages - {timePeriod}', [
                'timePeriod' => Craft::t('plausible', Plausible::$plugin->plausible->timeLabelize($timePeriod)),
            ]);
        }
        return $title;
    }

    /**
     * @inheritdoc
     */
    public function getSettingsHtml()
    {
        return Craft::$app->getView()->renderTemplate(
            'plausible/_components/widgets/TopPages/settings',
            [
                'widget' => $this
            ]
        );
    }

    /**
     * @inheritdoc
     */
    public function getBodyHtml()
    {
        Craft::$app->getView()->registerAssetBundle(TopPagesAsset::class);

        $results = Plausible::$plugin->plausible->getTopPages($this->limit, $this->timePeriod);

        return Craft::$app->getView()->renderTemplate(
            'plausible/_components/widgets/TopPages/body',
            [
                'results' => $results
            ]
        );
    }
}
