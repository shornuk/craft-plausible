<?php
/**
 * Plausible plugin for Craft CMS 3.x
 *
 * A wrapper around the Plausible API that fetches the analytics into your dashboard in a pretty way.
 *
 * @link      https://shorn.co.uk
 * @copyright Copyright (c) 2021 Sean Hill
 */

namespace shornuk\plausible\models;

use shornuk\plausible\Plausible;

use Craft;
use craft\base\Model;
use craft\behaviors\EnvAttributeParserBehavior;

/**
 * @author    Sean Hill
 * @package   Plausible
 * @since     1.0.0
 */
class Settings extends Model
{
    // Public Properties
    // =========================================================================

    /**
    * @var string An API key to used for accessing the Send in Blue API
    */
    public $apiKey;
    /**
    * @var string An API key to used for accessing the Send in Blue API
    */
    public $siteId;

    // Public Methods
    // =========================================================================



    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['apiKey', 'required'],
            ['siteId', 'required']
        ];
    }
}
