<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CompanyPage\Controller;

use Generated\Shared\Transfer\CompanyUserCriteriaFilterTransfer;
use Generated\Shared\Transfer\CompanyUserResponseTransfer;
use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use SprykerShop\Yves\CompanyPage\Plugin\Provider\CompanyPageControllerProvider;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \SprykerShop\Yves\CompanyPage\CompanyPageFactory getFactory()
 */
class UserController extends AbstractCompanyController
{
    public const COMPANY_USER_LIST_SORT_FIELD = 'id_company_user';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Spryker\Yves\Kernel\View\View
     */
    public function indexAction(Request $request)
    {
        $criteriaFilterTransfer = $this->createCompanyUserCriteriaFilterTransfer($request);

        $companyUserCollectionTransfer = $this->getFactory()
            ->getCompanyUserClient()
            ->getCompanyUserCollection($criteriaFilterTransfer);

        $data = [
            'pagination' => $companyUserCollectionTransfer->getPagination(),
            'companyUserCollection' => $companyUserCollectionTransfer->getCompanyUsers(),
        ];

        return $this->view($data);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Spryker\Yves\Kernel\View\View|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function createAction(Request $request)
    {
        $dataProvider = $this->getFactory()
            ->createCompanyFormFactory()
            ->createCompanyUserFormDataProvider();

        $companyUserForm = $this->getFactory()
            ->createCompanyFormFactory()
            ->getCompanyUserForm(
                $dataProvider->getOptions(
                    $this->getCompanyUser()->getFkCompany()
                )
            )
            ->handleRequest($request);

        if ($companyUserForm->isSubmitted() === false) {
            $companyUserForm->setData($dataProvider->getData($this->getCompanyUser()->getFkCompany()));
        } elseif ($companyUserForm->isSubmitted() && $companyUserForm->isValid()) {
            $companyUserResponseTransfer = $this->createCompanyUser($companyUserForm->getData());

            if ($companyUserResponseTransfer->getIsSuccessful()) {
                return $this->redirectResponseInternal(CompanyPageControllerProvider::ROUTE_COMPANY_USER);
            }
        }

        return $this->view([
            'companyUserForm' => $companyUserForm->createView(),
        ]);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Spryker\Yves\Kernel\View\View|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function updateAction(Request $request)
    {
        $dataProvider = $this->getFactory()
            ->createCompanyFormFactory()
            ->createCompanyUserFormDataProvider();

        $companyUserForm = $this->getFactory()
            ->createCompanyFormFactory()
            ->getCompanyUserForm(
                $dataProvider->getOptions(
                    $this->getCompanyUser()->getFkCompany()
                )
            )
            ->handleRequest($request);

        if ($companyUserForm->isSubmitted() === false) {
            $idCompanyUser = $request->query->getInt('id');
            $companyUserForm->setData(
                $dataProvider->getData(
                    $this->getCompanyUser()->getFkCompany(),
                    $idCompanyUser
                )
            );
        } elseif ($companyUserForm->isSubmitted() && $companyUserForm->isValid()) {
            $companyUserResponseTransfer = $this->updateCompanyUser($companyUserForm->getData());

            if ($companyUserResponseTransfer->getIsSuccessful()) {
                return $this->redirectResponseInternal(CompanyPageControllerProvider::ROUTE_COMPANY_USER);
            }
        }

        return $this->view([
            'companyUserForm' => $companyUserForm->createView(),
        ]);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(Request $request)
    {
        $idCompanyUser = $request->query->getInt('id');
        $companyUserTransfer = new CompanyUserTransfer();
        $companyUserTransfer->setIdCompanyUser($idCompanyUser);
        $this->getFactory()->getCompanyUserClient()->deleteCompanyUser($companyUserTransfer);

        return $this->redirectResponseInternal(CompanyPageControllerProvider::ROUTE_COMPANY_USER);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Generated\Shared\Transfer\CompanyUserCriteriaFilterTransfer
     */
    protected function createCompanyUserCriteriaFilterTransfer(Request $request): CompanyUserCriteriaFilterTransfer
    {
        $criteriaFilterTransfer = new CompanyUserCriteriaFilterTransfer();
        $criteriaFilterTransfer->setIdCompany($this->getCompanyUser()->getFkCompany());

        $filterTransfer = $this->createFilterTransfer(self::COMPANY_USER_LIST_SORT_FIELD);
        $criteriaFilterTransfer->setFilter($filterTransfer);

        $paginationTransfer = $this->createPaginationTransfer($request);
        $criteriaFilterTransfer->setPagination($paginationTransfer);

        return $criteriaFilterTransfer;
    }

    /**
     * @param array $data
     *
     * @return \Generated\Shared\Transfer\CompanyUserResponseTransfer
     */
    protected function createCompanyUser(array $data): CompanyUserResponseTransfer
    {
        $companyUserTransfer = new CompanyUserTransfer();
        $companyUserTransfer->fromArray($data, true);

        $customerTransfer = new CustomerTransfer();
        $customerTransfer->fromArray($data, true);
        $customerTransfer->setSendPasswordToken(true);

        $companyUserTransfer->setCustomer($customerTransfer);

        return $this->getFactory()->getCompanyUserClient()->createCompanyUser($companyUserTransfer);
    }

    /**
     * @param array $data
     *
     * @return \Generated\Shared\Transfer\CompanyUserResponseTransfer
     */
    protected function updateCompanyUser(array $data): CompanyUserResponseTransfer
    {
        $companyUserTransfer = new CompanyUserTransfer();
        $companyUserTransfer->fromArray($data, true);

        $customerTransfer = new CustomerTransfer();
        $customerTransfer->fromArray($data, true);

        $companyUserTransfer->setCustomer($customerTransfer);

        return $this->getFactory()->getCompanyUserClient()->updateCompanyUser($companyUserTransfer);
    }
}