<?php
/**
 * Plausible plugin for Craft CMS 3.x
 *
 * @link      https://shorn.co.uk
 * @copyright Copyright (c) 2021 Sean Hill
 */

namespace shornuk\plausible\widgets;

use shornuk\plausible\helpers\StringHelper;
use shornuk\plausible\Plausible;
use shornuk\plausible\services\PlausibleService;
use shornuk\plausible\assetbundles\plausible\PlausibleAsset;

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

    public int $limit = 4;
    public string $timePeriod = '30d';

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
        return Craft::t('plausible', 'Top Pages - {timePeriod}', [
            'timePeriod' => Craft::t('plausible', StringHelper::timeLabelize($this->timePeriod)),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function getSettingsHtml(): ?string
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
    public function getBodyHtml(): ?string
    {
        Craft::$app->getView()->registerAssetBundle(PlausibleAsset::class);

        $cacheKey = 'plausibleV5:topPages'.$this->timePeriod.$this->limit;
        $results = Craft::$app->getCache()->get($cacheKey);
        if (!$results)
        {
            $results = Plausible::$plugin->plausible->query([
                'metrics' => ['visitors'],
                'date_range' => $this->timePeriod,
                'pagination' => [
                    'limit' => $this->limit,
                    'offset' => 0,
                ],
                "filters" => [
                    [
                        "is_not",
                        "event:page",
                        [""]
                    ]
                ],
                "dimensions" => [
                    "event:page"
                ]
            ]);

            Craft::$app->getCache()->set($cacheKey, $results, 300);

        }

        return Craft::$app->getView()->renderTemplate(
            'plausible/_components/widgets/TopPages/body',
            [
                'results' => $results
            ]
        );
    }
}
