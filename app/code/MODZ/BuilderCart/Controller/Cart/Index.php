<?php

declare(strict_types=1);

namespace MODZ\BuilderCart\Controller\Cart;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Checkout\Model\Session;
use Magento\Quote\Api\CartRepositoryInterface;
use Magento\Quote\Model\MaskedQuoteIdToQuoteIdInterface;
use Magento\Integration\Model\Oauth\Token;
use Magento\Integration\Model\Oauth\TokenFactory;
use Magento\Framework\View\Result\PageFactory;

/**
 * Class index
 */
class Index extends Action
{
    /**
     * @var quoteRepository
     */
    protected $quoteRepository;
    /**
     * @var maskedQuoteIdToQuoteId
     */
    protected $maskedQuoteIdToQuoteId;
    /**
     * @var tokenFactory
     */
    protected $tokenFactory;

    /**
     *
     * @param Context $context
     * @param Session $checkoutSession
     * @param CartRepositoryInterface $quoteRepository
     * @param MaskedQuoteIdToQuoteIdInterface $maskedQuoteIdToQuoteId
     * @param TokenFactory $tokenFactory
     * @param PageFactory $resultPageFactory
     *
     */
    public function __construct(
        Context $context,
        Session $checkoutSession,
        CartRepositoryInterface $quoteRepository,
        MaskedQuoteIdToQuoteIdInterface $maskedQuoteIdToQuoteId,
        TokenFactory $tokenFactory,
        PageFactory $resultPageFactory

    ) {
        parent::__construct($context);
        $this->checkoutSession = $checkoutSession;
        $this->quoteRepository = $quoteRepository;
        $this->maskedQuoteIdToQuoteId = $maskedQuoteIdToQuoteId;
        $this->tokenFactory = $tokenFactory;
        $this->resultPageFactory = $resultPageFactory;
    }

    public function execute()
    {
        $checkoutPath = "checkout/cart";

        // if ($this->hasRequestAllRequiredParams()) {
        //     return $this->resultRedirectFactory->create()->setPath($checkoutPath);
        // }
        $quoteMaskedId = $this->getRequest()->getParam('quote');
        $redirect = $this->getRequest()->getParam('redirect');
        if (!$quoteMaskedId && $redirect)
        {
            $redirectUrl = ($this->checkoutSession->getMaskedId() && $this->checkoutSession->getQuoteId()) ? $redirect.'?q='.$this->checkoutSession->getMaskedId() : $redirect.'?q=new';
            header("Location: ".$redirectUrl);
            exit();
        }

        $customerToken = $this->getRequest()->getParam('token');
        /** @var Token $token */
        $token = $this->tokenFactory->create()->loadByToken($customerToken);
        $quoteId = $this->getQuoteId($quoteMaskedId);

        if ($this->isGuestCart($token)) {
            $this->quoteRepository->get($quoteId);
            $this->checkoutSession->setQuoteId($quoteId);
            $this->checkoutSession->setMaskedId($quoteMaskedId);
        } else {
        }


        return $this->resultRedirectFactory->create()->setPath($checkoutPath);
    }

    /**
     * get Masked id by Quote Id
     *
     * @return string|null
     * @throws LocalizedException
     */
    public function getQuoteId($quoteMaskedId)
    {
        $maskedId = null;
        try {
            $maskedId = $this->maskedQuoteIdToQuoteId->execute($quoteMaskedId);
        } catch (NoSuchEntityException $exception) {
            throw new LocalizedException(__('Current user does not have an active cart.'));
        }

        return $maskedId;
    }

    /**
     * @return bool
     */
    private function hasRequestAllRequiredParams(): bool
    {
        return !empty($this->getRequest()->getParam('quote'));
    }
    /**
     * @param Token $token
     *
     * @return bool
     */
    private function isCustomerCart(Token $token): bool
    {
        return $this->isCustomerToken($token);
    }

    /**
     * @param Token $token
     *
     * @return bool
     */
    private function isGuestCart(Token $token): bool
    {
        return !$this->isCustomerCart($token);
    }

    /**
     * @param Token $token
     *
     * @return bool
     */
    private function isCustomerToken(Token $token): bool
    {
        return $token->getId() && !$token->getRevoked() && $token->getCustomerId();
    }
}
