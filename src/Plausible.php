<?php
/**
 * Plausible plugin for Craft CMS 3.x
 *
 * A wrapper around the Plausible API that fetches the analytics into your dashboard in a pretty way.
 *
 * @link      https://shorn.co.uk
 * @copyright Copyright (c) 2021 Sean Hill
 */

namespace shornuk\plausible;

use shornuk\plausible\services\PlausibleService;
use shornuk\plausible\variables\PlausibleVariable;
use shornuk\plausible\models\Settings;
use shornuk\plausible\widgets\TopPages;
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
    public $schemaVersion = '1.0.0';

    /**
     * @var bool
     */
    public $hasCpSettings = true;

    /**
     * @var bool
     */
    public $hasCpSection = false;

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
                $event->types[] = TopPages::class;
                $event->types[] = Overview::class;
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
    private function _registerTwigExtensions()
    {
        Craft::$app->view->registerTwigExtension(new PlausibleTwigExtension);
    }

    protected function createSettingsModel()
    {
        return new Settings();
    }

    /**
     * @inheritdoc
     */
    protected function settingsHtml(): string
    {
        return Craft::$app->view->renderTemplate(
            'plausible/settings',
            [
                'settings' => $this->getSettings()
            ]
        );
    }
}
