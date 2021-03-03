<?php
/**
 * Plausible plugin for Craft CMS 3.x
 *
 * @link      https://shorn.co.uk
 * @copyright Copyright (c) 2021 Sean Hill
 */

namespace shornuk\plausible\assetbundles\plausible;

use Craft;
use craft\web\AssetBundle;
use craft\web\assets\cp\CpAsset;
use craft\web\assets\chartjs\ChartJsAsset;

/**
 * @author    Sean Hill
 * @package   Plausible
 * @since     1.0.0
 */
class PlausibleAsset extends AssetBundle
{
    // Public Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->sourcePath = "@shornuk/plausible/assetbundles/plausible/dist";

        $this->depends = [
            CpAsset::class,
        ];

        $this->js = [
            'js/frappe-charts.min.iife.js',
            'js/Plausible.js'
        ];

        $this->css = [
            'css/Plausible.css',
            'css/frappe-charts.min.css'
        ];

        parent::init();
    }
}
