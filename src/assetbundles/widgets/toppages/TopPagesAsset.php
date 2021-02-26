<?php
/**
 * Plausible plugin for Craft CMS 3.x
 *
 * @link      https://shorn.co.uk
 * @copyright Copyright (c) 2021 Sean Hill
 */

namespace shornuk\plausible\assetbundles\widgets\toppages;

use Craft;
use craft\web\AssetBundle;
use craft\web\assets\cp\CpAsset;

/**
 * @author    Sean Hill
 * @package   Plausible
 * @since     1.0.0
 */
class TopPagesAsset extends AssetBundle
{
    // Public Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->sourcePath = "@shornuk/plausible/assetbundles/widgets/toppages/dist";

        $this->depends = [
            CpAsset::class,
        ];

        $this->js = [
            'js/Plausible.js',
        ];

        $this->css = [
            'css/Plausible.css',
        ];

        parent::init();
    }
}
