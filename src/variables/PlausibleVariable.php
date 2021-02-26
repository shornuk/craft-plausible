<?php
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
