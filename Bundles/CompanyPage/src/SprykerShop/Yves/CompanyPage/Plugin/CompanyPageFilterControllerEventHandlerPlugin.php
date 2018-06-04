<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CompanyPage\Plugin;

use Spryker\Yves\Kernel\AbstractPlugin;
use SprykerShop\Yves\CompanyPage\Controller\AbstractCompanyController;
use SprykerShop\Yves\ShopApplication\Dependency\Plugin\FilterControllerEventHandlerPluginInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;

/**
 * @method \SprykerShop\Yves\CompanyPage\CompanyPageFactory getFactory()
 */
class CompanyPageFilterControllerEventHandlerPlugin extends AbstractPlugin implements FilterControllerEventHandlerPluginInterface
{
    /**
     * @const string
     */
    protected const COMPANY_REDIRECT_URL = '/company/user/select';

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Symfony\Component\HttpKernel\Event\FilterControllerEvent $event
     *
     * @return void
     */
    public function handle(FilterControllerEvent $event): void
    {
        list($controllerInstance, $actionName) = $event->getController();
        $customerTransfer = $this->getFactory()->getCustomerClient()->getCustomer();

        if ($controllerInstance instanceof AbstractCompanyController
            && $customerTransfer
            && $customerTransfer->getIsOnBehalf()
            && !$customerTransfer->getCompanyUserTransfer()
            && $event->getRequest()->getRequestUri() !== static::COMPANY_REDIRECT_URL
        ) {
            $event->setController(function () {
                return new RedirectResponse(static::COMPANY_REDIRECT_URL);
            });
        }
    }
}
