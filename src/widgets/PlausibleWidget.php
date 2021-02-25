<?php
/**
 * Plausible plugin for Craft CMS 3.x
 *
 * A wrapper around the Plausible API that fetches the analytics into your dashboard in a pretty way.
 *
 * @link      https://shorn.co.uk
 * @copyright Copyright (c) 2021 Sean Hill
 */

namespace shornuk\plausible\widgets;

use shornuk\plausible\Plausible;
use shornuk\plausible\services\PlausibleService;
use shornuk\plausible\assetbundles\plausiblewidget\PlausibleWidgetAsset;

use Craft;
use craft\base\Widget;

/**
 * Plausible Widget
 *
 * @author    Sean Hill
 * @package   Plausible
 * @since     1.0.0
 */
class PlausibleWidget extends Widget
{

    // Public Properties
    // =========================================================================

    public $limit = 5;
    public $timePeriod = '6mo';

    // Static Methods
    // =========================================================================

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
        return Craft::getAlias("@shornuk/plausible/assetbundles/plausiblewidget/dist/img/Plausible-icon.svg");
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



    /**
     * @inheritdoc
     */
    public function getSettingsHtml()
    {
        return Craft::$app->getView()->renderTemplate(
            'plausible/_components/widgets/Plausible_settings',
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
        Craft::$app->getView()->registerAssetBundle(PlausibleWidgetAsset::class);

        return Craft::$app->getView()->renderTemplate(
            'plausible/_components/widgets/Plausible_body',
            [
                'results' => Plausible::$plugin->plausible->getTopPages($this->limit, $this->timePeriod)
            ]
        );
    }
}
