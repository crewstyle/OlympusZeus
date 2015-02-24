<?php

namespace Takeatea\TeaThemeOptions\Networks\Facebook;

use Takeatea\TeaThemeOptions\TeaNetworks;

/**
 * TEA FACEBOOK NETWORK
 */

if (!defined('TTO_CONTEXT')) {
    die('You are not authorized to directly access to this page');
}

//----------------------------------------------------------------------------//

/**
 * Tea Networks Facebook
 *
 * To get its own Network
 *
 * @since 1.5.2.14
 *
 */
class Facebook extends TeaNetworks
{
    /**
     * Build HTML component to display in all the Tea T.O. defined pages.
     *
     * @since 1.4.0
     */
    public function templatePages()
    {
        //Default variables
        $keys = $this->getTokens('facebook');

        //Get template
        include(TTO_PATH.'/Networks/Facebook/in_pages.tpl.php');
    }
}
