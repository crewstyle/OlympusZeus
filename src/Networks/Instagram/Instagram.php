<?php

namespace Takeatea\TeaThemeOptions\Networks\Instagram;

use Takeatea\TeaThemeOptions\TeaNetworks;

/**
 * TEA INSTAGRAM NETWORK
 */

if (!defined('TTO_CONTEXT')) {
    die('You are not authorized to directly access to this page');
}

//----------------------------------------------------------------------------//

/**
 * Tea Networks Instagram
 *
 * To get its own Network
 *
 * @since 1.5.2.14
 *
 */
class Instagram extends TeaNetworks
{
    /**
     * Build HTML component to display in all the Tea T.O. defined pages.
     *
     * @since 1.4.0
     */
    public function templatePages()
    {
        //Default variables
        $keys = $this->getTokens('instagram');

        //Get template
        include(TTO_PATH.'/Networks/Instagram/in_pages.tpl.php');
    }
}
