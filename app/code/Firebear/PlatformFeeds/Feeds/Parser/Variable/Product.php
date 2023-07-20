<?php
/**
 * @copyright: Copyright © 2017 Firebear Studio. All rights reserved.
 * @author   : Firebear Studio <fbeardev@gmail.com>
 */

namespace Firebear\PlatformFeeds\Feeds\Parser\Variable;

class Product extends AbstractVariable
{
    /**
     * @inheritdoc
     */
    protected function getVariableNamespace()
    {
        return 'product';
    }
}
