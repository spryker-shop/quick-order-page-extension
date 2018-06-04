<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CompanyPage\Form;

use Spryker\Shared\Application\ApplicationConstants;
use Spryker\Yves\Kernel\AbstractFactory;
use SprykerShop\Yves\CompanyPage\CompanyPageDependencyProvider;
use SprykerShop\Yves\CompanyPage\Dependency\Client\CompanyPageToBusinessOnBehalfClientInterface;
use SprykerShop\Yves\CompanyPage\Dependency\Client\CompanyPageToCompanyBusinessUnitClientInterface;
use SprykerShop\Yves\CompanyPage\Dependency\Client\CompanyPageToCompanyRoleClientInterface;
use SprykerShop\Yves\CompanyPage\Dependency\Client\CompanyPageToCompanyUnitAddressClientInterface;
use SprykerShop\Yves\CompanyPage\Dependency\Client\CompanyPageToCompanyUserClientInterface;
use SprykerShop\Yves\CompanyPage\Dependency\Store\CompanyPageToKernelStoreInterface;
use SprykerShop\Yves\CompanyPage\Form\DataProvider\CompanyBusinessUnitFormDataProvider;
use SprykerShop\Yves\CompanyPage\Form\DataProvider\CompanyRoleDataProvider;
use SprykerShop\Yves\CompanyPage\Form\DataProvider\CompanyRolePermissionDataProvider;
use SprykerShop\Yves\CompanyPage\Form\DataProvider\CompanyUnitAddressFormDataProvider;
use SprykerShop\Yves\CompanyPage\Form\DataProvider\CompanyUserAccountDataProvider;
use SprykerShop\Yves\CompanyPage\Form\DataProvider\CompanyUserFormDataProvider;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;

class FormFactory extends AbstractFactory
{
    /**
     * @return \Symfony\Component\Form\FormFactoryInterface
     */
    protected function getFormFactory(): FormFactoryInterface
    {
        return $this->getProvidedDependency(ApplicationConstants::FORM_FACTORY);
    }

    /**
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getCompanyRegisterForm(): FormInterface
    {
        return $this->getFormFactory()->create(CompanyRegisterForm::class);
    }

    /**
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getBusinessUnitForm(): FormInterface
    {
        return $this->getFormFactory()->create(CompanyBusinessUnitForm::class);
    }

    /**
     * @param array $formOptions
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getCompanyUnitAddressForm(array $formOptions): FormInterface
    {
        return $this->getFormFactory()->create(CompanyUnitAddressForm::class, null, $formOptions);
    }

    /**
     * @param array $formOptions
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getCompanyBusinessUnitAddressForm(array $formOptions): FormInterface
    {
        return $this->getFormFactory()->create(CompanyBusinessUnitAddressForm::class, null, $formOptions);
    }

    /**
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getCompanyRoleForm(): FormInterface
    {
        return $this->getFormFactory()->create(CompanyRoleForm::class);
    }

    /**
     * @param array $formOptions
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getCompanyUserForm(array $formOptions): FormInterface
    {
        return $this->getFormFactory()->create(CompanyUserForm::class, null, $formOptions);
    }

    /**
     * @SuppressWarnings(PHPMD)
     *
     * @return \SprykerShop\Yves\CompanyPage\Form\DataProvider\CompanyUserFormDataProvider
     */
    public function createCompanyUserFormDataProvider(): CompanyUserFormDataProvider
    {
        return new CompanyUserFormDataProvider(
            $this->getCompanyUserClient(),
            $this->getCompanyBusinessUnitClient()
        );
    }

    /**
     * @param int $idCompanyRole
     * @param int $idPermission
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getCompanyRolePermissionType(int $idCompanyRole, int $idPermission)
    {
        $dataProvider = $this->createCompanyRolePermissionDataProvider();

        return $this->getFormFactory()->create(
            CompanyRolePermissionConfigurationType::class,
            $dataProvider->getData($idCompanyRole, $idPermission),
            $dataProvider->getOptions()
        );
    }

    /**
     * @return \SprykerShop\Yves\CompanyPage\Form\DataProvider\CompanyRolePermissionDataProvider
     */
    public function createCompanyRolePermissionDataProvider()
    {
        return new CompanyRolePermissionDataProvider(
            $this->getCompanyRoleClient()
        );
    }

    /**
     * @SuppressWarnings(PHPMD)
     *
     * @return \SprykerShop\Yves\CompanyPage\Form\DataProvider\CompanyBusinessUnitFormDataProvider
     */
    public function createBusinessUnitFormDataProvider(): CompanyBusinessUnitFormDataProvider
    {
        return new CompanyBusinessUnitFormDataProvider($this->getCompanyBusinessUnitClient());
    }

    /**
     * @return \SprykerShop\Yves\CompanyPage\Dependency\Client\CompanyPageToCompanyBusinessUnitClientInterface
     */
    protected function getCompanyBusinessUnitClient(): CompanyPageToCompanyBusinessUnitClientInterface
    {
        return $this->getProvidedDependency(CompanyPageDependencyProvider::CLIENT_COMPANY_BUSINESS_UNIT);
    }

    /**
     * @SuppressWarnings(PHPMD)
     *
     * @return \SprykerShop\Yves\CompanyPage\Form\DataProvider\CompanyUnitAddressFormDataProvider
     */
    public function createCompanyUnitAddressFormDataProvider(): CompanyUnitAddressFormDataProvider
    {
        return new CompanyUnitAddressFormDataProvider(
            $this->getCompanyUnitAddressClient(),
            $this->getStore()
        );
    }

    /**
     * @SuppressWarnings(PHPMD)
     *
     * @return \SprykerShop\Yves\CompanyPage\Form\DataProvider\CompanyRoleDataProvider
     */
    public function createCompanyRoleDataProvider(): CompanyRoleDataProvider
    {
        return new CompanyRoleDataProvider($this->getCompanyRoleClient());
    }

    /**
     * @param array $data
     * @param array $formOptions
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getCompanyUserAccountForm(array $data = [], array $formOptions = []): FormInterface
    {
        return $this->getFormFactory()->create(CompanyUserAccountForm::class, $data, $formOptions);
    }

    /**
     * @return \SprykerShop\Yves\CompanyPage\Form\DataProvider\CompanyUserAccountDataProvider
     */
    public function createCompanyUserAccountDataProvider(): CompanyUserAccountDataProvider
    {
        return new CompanyUserAccountDataProvider(
            $this->getBusinessOnBehalfClient()
        );
    }

    /**
     * @return \SprykerShop\Yves\CompanyPage\Dependency\Client\CompanyPageToBusinessOnBehalfClientInterface
     */
    protected function getBusinessOnBehalfClient(): CompanyPageToBusinessOnBehalfClientInterface
    {
        return $this->getProvidedDependency(CompanyPageDependencyProvider::CLIENT_BUSINESS_ON_BEHALF);
    }

    /**
     * @return \SprykerShop\Yves\CompanyPage\Dependency\Client\CompanyPageToCompanyUnitAddressClientInterface
     */
    protected function getCompanyUnitAddressClient(): CompanyPageToCompanyUnitAddressClientInterface
    {
        return $this->getProvidedDependency(CompanyPageDependencyProvider::CLIENT_COMPANY_UNIT_ADDRESS);
    }

    /**
     * @return \SprykerShop\Yves\CompanyPage\Dependency\Store\CompanyPageToKernelStoreInterface
     */
    protected function getStore(): CompanyPageToKernelStoreInterface
    {
        return $this->getProvidedDependency(CompanyPageDependencyProvider::STORE);
    }

    /**
     * @return \SprykerShop\Yves\CompanyPage\Dependency\Client\CompanyPageToCompanyRoleClientInterface
     */
    protected function getCompanyRoleClient(): CompanyPageToCompanyRoleClientInterface
    {
        return $this->getProvidedDependency(CompanyPageDependencyProvider::CLIENT_COMPANY_ROLE);
    }

    /**
     * @return \SprykerShop\Yves\CompanyPage\Dependency\Client\CompanyPageToCompanyUserClientInterface
     */
    protected function getCompanyUserClient(): CompanyPageToCompanyUserClientInterface
    {
        return $this->getProvidedDependency(CompanyPageDependencyProvider::CLIENT_COMPANY_USER);
    }
}
