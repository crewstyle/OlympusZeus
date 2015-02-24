<?php

namespace Takeatea\TeaThemeOptions\Networks\Googleplus;

use Takeatea\TeaThemeOptions\TeaNetworks;

/**
 * TEA GOOGLEPLUS NETWORK
 */

if (!defined('TTO_CONTEXT')) {
    die('You are not authorized to directly access to this page');
}

//----------------------------------------------------------------------------//

/**
 * Tea Networks Googleplus
 *
 * To get its own Network
 *
 * @since 1.5.2.14
 *
 */
class Googleplus extends TeaNetworks
{
    /**
     * Build HTML component to display in all the Tea T.O. defined pages.
     *
     * @since 1.4.0
     */
    public function templatePages()
    {
        //Default variables
        $keys = $this->getTokens('googleplus');

        //Get template
        include(TTO_PATH.'/Networks/Googleplus/in_pages.tpl.php');
    }
}
