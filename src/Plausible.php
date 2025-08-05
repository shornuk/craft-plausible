<?php
/**
 * Plausible plugin for Craft CMS 3.x
 *
 * @link      https://shorn.co.uk
 * @copyright Copyright (c) 2021 Sean Hill
 */

namespace shornuk\plausible;

use shornuk\plausible\services\PlausibleService;
use shornuk\plausible\variables\PlausibleVariable;
use shornuk\plausible\models\Settings;
use shornuk\plausible\widgets\CurrentVisitors;
use shornuk\plausible\widgets\Test;
use shornuk\plausible\widgets\TopPages;
use shornuk\plausible\widgets\TopSources;
use shornuk\plausible\widgets\TopDevices;
use shornuk\plausible\widgets\TopBrowsers;
use shornuk\plausible\widgets\TopCountries;
use shornuk\plausible\widgets\Overview;
use shornuk\plausible\twigextensions\PlausibleTwigExtension;

use Craft;
use craft\base\Plugin;
use craft\services\Plugins;
use craft\events\PluginEvent;
use craft\web\twig\variables\CraftVariable;
use craft\services\Dashboard;
use craft\events\RegisterComponentTypesEvent;

use yii\base\Event;

/**
 * Class Plausible
 *
 * @author    Sean Hill
 * @package   Plausible
 * @since     1.0.0
 *
 * @property  PlausibleService $plausible
 */
class Plausible extends Plugin
{
    // Static Properties
    // =========================================================================

    /**
     * @var Plausible
     */
    public static $plugin;

    // Public Properties
    // =========================================================================

    /**
     * @var string
     */
    public string $schemaVersion = '2.0.0';

    /**
     * @var bool
     */
    public bool $hasCpSettings = true;

    /**
     * @var bool
     */
    public bool $hasCpSection = false;

    // Public Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        self::$plugin = $this;

        $this->setComponents([
            'plausible' => PlausibleService::class,
        ]);

        $this->_registerTwigExtensions();

        Event::on(
            Dashboard::class,
            Dashboard::EVENT_REGISTER_WIDGET_TYPES,
            function (RegisterComponentTypesEvent $event) {
                $event->types[] = CurrentVisitors::class;
                $event->types[] = TopPages::class;
                $event->types[] = Test::class;
                $event->types[] = Overview::class;
                $event->types[] = TopSources::class;
                $event->types[] = TopDevices::class;
                $event->types[] = TopBrowsers::class;
                $event->types[] = TopCountries::class;
            }
        );

        Event::on(
            CraftVariable::class,
            CraftVariable::EVENT_INIT,
            function (Event $event) {
                /** @var CraftVariable $variable */
                $variable = $event->sender;
                $variable->set('plausible', PlausibleVariable::class);
            }
        );

        Event::on(
            Plugins::class,
            Plugins::EVENT_AFTER_INSTALL_PLUGIN,
            function (PluginEvent $event) {
                if ($event->plugin === $this) {
                }
            }
        );

        Craft::info(
            Craft::t(
                'plausible',
                '{name} plugin loaded',
                ['name' => $this->name]
            ),
            __METHOD__
        );
    }

    // Protected Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    private function _registerTwigExtensions(): void
    {
        Craft::$app->view->registerTwigExtension(new PlausibleTwigExtension);
    }

    protected function createSettingsModel(): ?\craft\base\Model
    {
        return new Settings();
    }

    /**
     * @inheritdoc
     */
    protected function settingsHtml(): ?string
    {
        return Craft::$app->view->renderTemplate(
            'plausible/settings',
            [
                'settings' => $this->getSettings()
            ]
        );
    }
}
