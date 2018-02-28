<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CompanyPage;

use Spryker\Shared\Kernel\Store;
use Spryker\Yves\Kernel\AbstractBundleDependencyProvider;
use Spryker\Yves\Kernel\Container;
use SprykerShop\Yves\CompanyPage\Dependency\Client\CompanyPageToCompanyBusinessUnitClientBridge;
use SprykerShop\Yves\CompanyPage\Dependency\Client\CompanyPageToCompanyClientBridge;
use SprykerShop\Yves\CompanyPage\Dependency\Client\CompanyPageToCompanyRoleClientBridge;
use SprykerShop\Yves\CompanyPage\Dependency\Client\CompanyPageToCompanyUnitAddressClientBridge;
use SprykerShop\Yves\CompanyPage\Dependency\Client\CompanyPageToCompanyUserClientBridge;
use SprykerShop\Yves\CompanyPage\Dependency\Client\CompanyPageToCustomerClientBridge;
use SprykerShop\Yves\CompanyPage\Dependency\Client\CompanyPageToKernelStoreBridge;
use SprykerShop\Yves\CompanyPage\Dependency\Client\CompanyPageToPermissionClientBridge;

class CompanyPageDependencyProvider extends AbstractBundleDependencyProvider
{
    public const CLIENT_CUSTOMER = 'CLIENT_CUSTOMER';
    public const CLIENT_COMPANY = 'CLIENT_COMPANY';
    public const CLIENT_COMPANY_BUSINESS_UNIT = 'CLIENT_COMPANY_BUSINESS_UNIT';
    public const CLIENT_COMPANY_UNIT_ADDRESS = 'CLIENT_COMPANY_UNIT_ADDRESS';
    public const CLIENT_COMPANY_USER = 'CLIENT_COMPANY_USER';
    public const CLIENT_COMPANY_ROLE = 'CLIENT_COMPANY_ROLE';
    public const CLIENT_PERMISSION = 'CLIENT_PERMISSION';
    public const STORE = 'STORE';

    public const PLUGIN_COMPANY_OVERVIEW_WIDGETS = 'PLUGIN_COMPANY_OVERVIEW_WIDGETS';

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    public function provideDependencies(Container $container): Container
    {
        $container = parent::provideDependencies($container);

        $container = $this->addCustomerClient($container);
        $container = $this->addCompanyClient($container);
        $container = $this->addCompanyBusinessUnitClient($container);
        $container = $this->addCompanyUserClient($container);
        $container = $this->addCompanyRoleClient($container);
        $container = $this->addCompanyUnitAddressClient($container);
        $container = $this->addCompanyOverviewWidgetPlugins($container);
        $container = $this->addPermissionClient($container);
        $container = $this->addStore($container);

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addStore(Container $container)
    {
        $container[static::STORE] = function () {
            return new CompanyPageToKernelStoreBridge(Store::getInstance());
        };

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addCustomerClient(Container $container): Container
    {
        $container[static::CLIENT_CUSTOMER] = function (Container $container) {
            return new CompanyPageToCustomerClientBridge($container->getLocator()->customer()->client());
        };

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addCompanyUnitAddressClient(Container $container): Container
    {
        $container[static::CLIENT_COMPANY_UNIT_ADDRESS] = function (Container $container) {
            return new CompanyPageToCompanyUnitAddressClientBridge($container->getLocator()->companyUnitAddress()->client());
        };

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addCompanyUserClient(Container $container): Container
    {
        $container[static::CLIENT_COMPANY_USER] = function (Container $container) {
            return new CompanyPageToCompanyUserClientBridge($container->getLocator()->companyUser()->client());
        };

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addCompanyRoleClient(Container $container): Container
    {
        $container[static::CLIENT_COMPANY_ROLE] = function (Container $container) {
            return new CompanyPageToCompanyRoleClientBridge($container->getLocator()->companyRole()->client());
        };

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addCompanyClient(Container $container): Container
    {
        $container[static::CLIENT_COMPANY] = function (Container $container) {
            return new CompanyPageToCompanyClientBridge($container->getLocator()->company()->client());
        };

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addCompanyBusinessUnitClient(Container $container): Container
    {
        $container[static::CLIENT_COMPANY_BUSINESS_UNIT] = function (Container $container) {
            return new CompanyPageToCompanyBusinessUnitClientBridge($container->getLocator()->companyBusinessUnit()->client());
        };

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addCompanyOverviewWidgetPlugins(Container $container): Container
    {
        $container[static::PLUGIN_COMPANY_OVERVIEW_WIDGETS] = function () {
            return $this->getCompanyOverviewWidgetPlugins();
        };

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addPermissionClient(Container $container): Container
    {
        $container[static::CLIENT_PERMISSION] = function (Container $container) {
            return new CompanyPageToPermissionClientBridge($container->getLocator()->permission()->client());
        };

        return $container;
    }

    /**
     * @return array
     */
    protected function getCompanyOverviewWidgetPlugins(): array
    {
        return [];
    }
}
