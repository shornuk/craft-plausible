<?php
/**
 * Plausible plugin for Craft CMS 3.x
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
    * @var string The base URL of the API, defaulting to plausible.io
    */
    public $baseUrl = 'https://plausible.io';
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
    public function rules(): array
    {
        return [
            [['baseUrl', 'apiKey', 'siteId'], 'required'],
        ];
    }

    public function attributeLabels()
    {
        $labels = parent::attributeLabels();
        $labels['baseUrl'] = Craft::t('plausible','Base URL');
        $labels['apiKey'] = Craft::t('plausible','API Key');
        $labels['siteId'] = Craft::t('plausible','Site ID');

        return $labels;
    }
}
