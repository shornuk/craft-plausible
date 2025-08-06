<?php
/**
 * Plausible plugin for Craft CMS 3.x
 *
 * @link      https://shorn.co.uk
 * @copyright Copyright (c) 2021 Sean Hill
 */

namespace shornuk\plausible\widgets;

use DateTimeImmutable;
use shornuk\plausible\Plausible;
use shornuk\plausible\assetbundles\plausible\PlausibleAsset;

use Craft;
use craft\base\Widget;
use DateTime;
use DateTimeZone;
use DateInterval;

/**
 * Top Sources Widget
 *
 * @author    Sean Hill
 * @package   Plausible
 * @since     1.0.0
 */
class CurrentVisitors extends Widget
{

    // Public Properties
    // =========================================================================


    // Static Methods
    // =========================================================================


    /**
     * @inheritdoc
     */
    public static function displayName(): string
    {
        return Craft::t('plausible', 'Current Visitors');
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
        $title = Craft::t('plausible', 'Current Visitors');
        return $title;
    }

    /**
     * @inheritdoc
     */
    public function getBodyHtml(): ?string
    {
        Craft::$app->getView()->registerAssetBundle(PlausibleAsset::class);

        $cacheKey = 'plausible:currentVisitors';
        $results = Craft::$app->getCache()->get($cacheKey);
        if (!$results)
        {
            $now = new DateTimeImmutable('now', new DateTimeZone(date('P')));
            $fiveMinutesAgo = $now->sub(new DateInterval('PT5M'));

            $dateRange = [
                $fiveMinutesAgo->format(DateTime::ATOM),
                $now->format(DateTime::ATOM)
            ];

            $results = Plausible::$plugin->plausible->query([
                'metrics' => ['visitors'],
                'date_range' => $dateRange,
            ]);
            Craft::$app->getCache()->set($cacheKey, $results, 60);
        }

        return Craft::$app->getView()->renderTemplate(
            'plausible/_components/widgets/CurrentVisitors/body',
            [
                'results' => $results
            ]
        );
    }
}
