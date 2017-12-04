<?php

/**
 * This file is part of the Spryker Demoshop.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerShop\Yves\NewsletterPage\Controller;

use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\NewsletterSubscriberTransfer;
use Generated\Shared\Transfer\NewsletterSubscriptionRequestTransfer;
use Generated\Shared\Transfer\NewsletterTypeTransfer;
use Spryker\Shared\Newsletter\NewsletterConstants;
use SprykerShop\Yves\NewsletterPage\Form\NewsletterSubscriptionForm;
use SprykerShop\Yves\NewsletterPage\Plugin\Provider\NewsletterPageControllerProvider;
use SprykerShop\Yves\ShopApplication\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \SprykerShop\Yves\NewsletterPage\NewsletterPageFactory getFactory()
 */
class NewsletterController extends AbstractController
{
    const MESSAGE_UNSUBSCRIPTION_SUCCESS = 'newsletter.unsubscription.success';
    const MESSAGE_SUBSCRIPTION_SUCCESS = 'newsletter.subscription.success';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Spryker\Yves\Kernel\View\View|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function indexAction(Request $request)
    {
        $customerTransfer = $this->getFactory()
            ->getCustomerClient()
            ->getCustomer();

        $newsletterSubscriptionRequestTransfer = $this->createNewsletterSubscriptionRequest($customerTransfer);

        $dataProvider = $this->getFactory()->createNewsletterSubscriptionFormDataProvider();
        $newsletterSubscriptionForm = $this->getFactory()
            ->createNewsletterSubscriptionForm(
                $dataProvider->getData($newsletterSubscriptionRequestTransfer),
                $dataProvider->getOptions()
            )
            ->handleRequest($request);

        if ($newsletterSubscriptionForm->isValid()) {
            $this->processForm($newsletterSubscriptionForm, $newsletterSubscriptionRequestTransfer);

            return $this->redirectResponseInternal(NewsletterPageControllerProvider::ROUTE_CUSTOMER_NEWSLETTER);
        }

        return $this->view([
            'customer' => $customerTransfer,
            'form' => $newsletterSubscriptionForm->createView(),
        ]);
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     * @param string|null $subscriberKey
     *
     * @return \Generated\Shared\Transfer\NewsletterSubscriptionRequestTransfer
     */
    protected function createNewsletterSubscriptionRequest(CustomerTransfer $customerTransfer, $subscriberKey = null)
    {
        $subscriptionRequest = new NewsletterSubscriptionRequestTransfer();

        $subscriber = new NewsletterSubscriberTransfer();
        $subscriber->setFkCustomer($customerTransfer->getIdCustomer());
        $subscriber->setEmail($customerTransfer->getEmail());
        $subscriber->setSubscriberKey($subscriberKey);

        $subscriptionRequest->setNewsletterSubscriber($subscriber);
        $subscriptionRequest->addSubscriptionType((new NewsletterTypeTransfer())
            ->setName(NewsletterConstants::DEFAULT_NEWSLETTER));

        return $subscriptionRequest;
    }

    /**
     * @param \Symfony\Component\Form\FormInterface $newsletterSubscriptionForm
     * @param \Generated\Shared\Transfer\NewsletterSubscriptionRequestTransfer $newsletterSubscriptionRequestTransfer
     *
     * @return void
     */
    public function processForm(FormInterface $newsletterSubscriptionForm, NewsletterSubscriptionRequestTransfer $newsletterSubscriptionRequestTransfer)
    {
        $subscribe = (bool)$newsletterSubscriptionForm->get(NewsletterSubscriptionForm::FIELD_SUBSCRIBE)->getData();

        if ($subscribe === true) {
            $this->processSubscription($newsletterSubscriptionRequestTransfer);

            return;
        }

        $this->processUnsubscription($newsletterSubscriptionRequestTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\NewsletterSubscriptionRequestTransfer $newsletterSubscriptionRequestTransfer
     *
     * @return void
     */
    protected function processSubscription(NewsletterSubscriptionRequestTransfer $newsletterSubscriptionRequestTransfer)
    {
        $subscriptionResponse = $this->getFactory()
            ->getNewsletterClient()
            ->subscribeWithDoubleOptIn($newsletterSubscriptionRequestTransfer);

        $subscriptionResult = current($subscriptionResponse->getSubscriptionResults());
        if ($subscriptionResult->getIsSuccess()) {
            $this->addSuccessMessage(static::MESSAGE_SUBSCRIPTION_SUCCESS);

            return;
        }

        $this->addErrorMessage($subscriptionResult->getErrorMessage());
    }

    /**
     * @param \Generated\Shared\Transfer\NewsletterSubscriptionRequestTransfer $newsletterSubscriptionRequestTransfer
     *
     * @return void
     */
    protected function processUnsubscription(NewsletterSubscriptionRequestTransfer $newsletterSubscriptionRequestTransfer)
    {
        $this->getFactory()
            ->getNewsletterClient()
            ->unsubscribe($newsletterSubscriptionRequestTransfer);

        $this->addSuccessMessage(static::MESSAGE_UNSUBSCRIPTION_SUCCESS);
    }
}