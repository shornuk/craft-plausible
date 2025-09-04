<?php
/**
 * Plausible plugin for Craft CMS 3.x
 *
 * @link      https://shorn.co.uk
 * @copyright Copyright (c) 2021 Sean Hill
 */

namespace shornuk\plausible\widgets;

use Locale;
use shornuk\plausible\helpers\StringHelper;
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
 * @since     2.1.0
 */
class TopCountries extends Widget
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
        return Craft::t('plausible', 'Top Countries');
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
        return Craft::t('plausible', 'Top Countries - {timePeriod}', [
            'timePeriod' => Craft::t('plausible', StringHelper::timeLabelize($this->timePeriod)),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function getSettingsHtml(): ?string
    {
        return Craft::$app->getView()->renderTemplate(
            'plausible/_components/widgets/TopCountries/settings',
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

        $cacheKey = 'plausibleV5:topCountries'.$this->timePeriod.$this->limit;
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
                        "visit:country_name",
                        [""]
                    ]
                ],
                "dimensions" => [
                    "visit:country_name"
                ]
            ]);

//            foreach ($results as &$result) {
//                if (!empty($result->country)) {
//                    $result->country = Locale::getDisplayRegion(
//                        '-' . $result->country,
//                        Craft::$app->getUser()->getIdentity()->getPreferredLanguage(),
//                    );
//                }
//            }

            Craft::$app->getCache()->set($cacheKey, $results, 300);
        }

        return Craft::$app->getView()->renderTemplate(
            'plausible/_components/widgets/TopCountries/body',
            [
                'results' => $results
            ]
        );
    }
}
