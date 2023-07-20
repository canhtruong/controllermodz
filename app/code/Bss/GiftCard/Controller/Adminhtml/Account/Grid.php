<?php
/**
 * BSS Commerce Co.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://bsscommerce.com/Bss-Commerce-License.txt
 *
 * @category   BSS
 * @package    Bss_GiftCard
 * @author     Extension Team
 * @copyright  Copyright (c) 2017-2018 BSS Commerce Co. ( http://bsscommerce.com )
 * @license    http://bsscommerce.com/Bss-Commerce-License.txt
 */

namespace Bss\GiftCard\Controller\Adminhtml\Account;

use Bss\GiftCard\Controller\Adminhtml\AbstractGiftCard;
use Bss\GiftCard\Block\Adminhtml\Pattern\Code\Tab\History;

/**
 * Class Grid
 *
 * @package Bss\GiftCard\Controller\Adminhtml\Account
 */
class Grid extends AbstractGiftCard
{
    /**
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\Result\Raw|\Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $grid = $this->_view->getLayout()->createBlock(
            History::class
        )->toHtml();

        $response = $this->resultRawFactory->create();
        $response->setContents($grid);
        return $response;
    }
}
