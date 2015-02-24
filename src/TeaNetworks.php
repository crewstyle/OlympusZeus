<?php

namespace Takeatea\TeaThemeOptions;

/**
 * TEA NETWORKS
 */

if (!defined('TTO_CONTEXT')) {
    die('You are not authorized to directly access to this page');
}


//----------------------------------------------------------------------------//

/**
 * Tea Networks
 *
 * To get its own Fields
 *
 * @since 1.5.2.14
 *
 */
abstract class TeaNetworks
{
    /**
     * @var array
     */
    protected $includes = array();

    /**
     * Get includes.
     *
     * @param string $network Contains the network name to check
     * @return boolean $tks Answear to the question.
     *
     * @since 1.4.0
     */
    protected function getTokens($network)
    {
        $tks = TeaThemeOptions::getConfigs('networks');

        if (empty($tks)) {
            return false;
        }

        return array_key_exists($network, $tks);
    }
}
