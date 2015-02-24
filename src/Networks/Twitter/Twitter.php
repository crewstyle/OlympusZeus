<?php

namespace Takeatea\TeaThemeOptions\Networks\Twitter;

use Takeatea\TeaThemeOptions\TeaNetworks;

/**
 * TEA TWITTER NETWORK
 */

if (!defined('TTO_CONTEXT')) {
    die('You are not authorized to directly access to this page');
}

//----------------------------------------------------------------------------//

/**
 * Tea Networks Twitter
 *
 * To get its own Network
 *
 * @since 1.5.2.14
 *
 */
class Twitter extends TeaNetworks
{
    /**
     * Build HTML component to display in all the Tea T.O. defined pages.
     *
     * @since 1.4.0
     */
    public function templatePages()
    {
        //Default variables
        $keys = $this->getTokens('twitter');

        //Get template
        include(TTO_PATH.'/Networks/Twitter/in_pages.tpl.php');
    }
}
