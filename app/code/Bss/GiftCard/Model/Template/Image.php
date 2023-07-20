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

namespace Bss\GiftCard\Model\Template;

use Magento\Framework\Model\AbstractModel;
use Bss\GiftCard\Model\ResourceModel\Template\Image as ImageResourceModel;

/**
 * Class Image
 *
 * @package Bss\GiftCard\Model\Template
 */
class Image extends AbstractModel
{
    /**
     * @return void
     */
    public function _construct()
    {
        $this->_init(ImageResourceModel::class);
    }

    /**
     * @param array $data
     * @param int $templateId
     */
    public function insertData($data, $templateId)
    {
        $this->getResource()->insertImage($data, $templateId);
    }

    /**
     * @param int $templateId
     * @return mixed
     */
    public function loadByTemplate($templateId)
    {
        return $this->getResource()->loadByTemplate($templateId);
    }

    /**
     * Get image data by id
     *
     * @param int $imageId
     * @return \Magento\Framework\DataObject|bool
     */
    public function getById($imageId)
    {
        return $this->getResource()->getById($imageId);
    }

    /**
     * @return mixed
     */
    public function getThumbnail()
    {
        return $this->getResource()->resize(
            $this->getValue(),
            ImageResourceModel::THUMBNAIL_IMG_WIDTH,
            ImageResourceModel::THUMBNAIL_IMG_HEIGHT
        );
    }
}