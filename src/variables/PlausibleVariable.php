<?php
/**
 * Plausible plugin for Craft CMS 3.x
 *
 * A wrapper around the Plausible API that fetches the analytics into your dashboard in a pretty way.
 *
 * @link      https://shorn.co.uk
 * @copyright Copyright (c) 2021 Sean Hill
 */

namespace shornuk\plausible\variables;

use shornuk\plausible\Plausible;

use Craft;

/**
 * @author    Sean Hill
 * @package   Plausible
 * @since     1.0.0
 */
class PlausibleVariable
{
    // Public Methods
    // =========================================================================

    /**
     * @param null $optional
     * @return string
     */
    public function test()
    {
        return Plausible::$plugin->plausible->test();
    }
}
