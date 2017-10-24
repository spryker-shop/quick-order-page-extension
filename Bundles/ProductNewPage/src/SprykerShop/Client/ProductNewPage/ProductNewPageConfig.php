<?php

/**
 * This file is part of the Spryker Demoshop.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerShop\Client\ProductNewPage;

use Spryker\Client\Kernel\AbstractBundleConfig;
use Spryker\Shared\ProductNew\ProductNewConfig as SprykerSharedProductNewConfig;

class ProductNewPageConfig extends AbstractBundleConfig
{

    /**
     * @return string
     */
    public function getLabelNewName()
    {
        return SprykerSharedProductNewConfig::DEFAULT_LABEL_NAME;
    }

}
