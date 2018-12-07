<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuickOrderPageExtension\Dependency\Plugin;

interface QuickOrderFormColumnPluginInterface
{
    /**
     * Specification:
     * - Returns glossary key for column title to be displayed.
     *
     * @api
     *
     * @return string
     */
    public function getColumnTitle(): string;

    /**
     * Specification:
     * - Returns string separated by dots to be used as a property path.
     *
     * @api
     *
     * @return string
     */
    public function getDataPath(): string;
}
