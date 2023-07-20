<?php

namespace MODZ\ProductSortOption\Plugin\Product\ProductList;

use Magento\Catalog\Block\Product\ProductList\Toolbar as Productdata;

class Toolbar extends Productdata
{
    /**
     * Set collection to pager
     *
     * @param \Magento\Framework\Data\Collection $collection
     * @return $this
     */
    public function setCollection($collection) {
        $this->_collection = $collection;
        $this->_collection->setCurPage($this->getCurrentPage());

        // we need to set pagination only if passed value integer and more that 0
        $limit = (int)$this->getLimit();
        if ($limit) {
            $this->_collection->setPageSize($limit);
        }

        // switch between sort order options
        if ($this->getCurrentOrder()) {
            // create custom query for created_at option
            switch ($this->getCurrentOrder()) {
                case 'created_at':
                    if ($this->getCurrentDirection() == 'desc') {
                        $this->_collection
                            ->getSelect()
                            ->order('e.created_at DESC');
                    } elseif ($this->getCurrentDirection() == 'asc') {
                        $this->_collection
                            ->getSelect()
                            ->order('e.created_at ASC');
                    }
                    break;
                default:
                    $this->_collection->setOrder($this->getCurrentOrder(), $this->getCurrentDirection());
                    break;
            }
        }

        // echo '<pre>';
        // var_dump($this->getCurrentOrder());
        // var_dump((string) $this->_collection->getSelect());
        // die;

        return $this;
    }
    /**
     * Plugin
     *
     * @param \Magento\Catalog\Block\Product\ProductList\Toolbar $subject
     * @param \Closure $proceed
     * @param \Magento\Framework\Data\Collection $collection
     * @return \Magento\Catalog\Block\Product\ProductList\Toolbar
     */
    public function aroundSetCollection(Productdata $subject, \Closure $proceed, $collection)
    {
        $currentOrder = $subject->getCurrentOrder();
        if ($currentOrder) {
            if ($currentOrder == "created_at") {
                $direction = $subject->getCurrentDirection();
                $collection->getSelect()->order('e.created_at '.$direction);
//                print_r('=====');
//                print_r($collection->getSelect()->assemble());
//                die('===See===');
            }
            return $proceed($collection);
        }
    }
//    public function afterSetCollection(
//        \Magento\Catalog\Block\Product\ProductList\Toolbar $subject,
//                                                           $result
//    )
//    {
//        $currentOrder = $subject->getCurrentOrder();
//        $dir = $subject->getCurrentDirection();
//        $currentDirection = $subject->getCurrentDirection();
//        if ($currentOrder == "newest_product") {
//            if ($currentDirection == 'asc') {
//                $subject->getCollection()->setOrder('created_at', 'desc');
//            }
//            else {
//                $subject->getCollection()->setOrder('created_at', 'asc');
//            }
//        }
//        return $result;
//    }
}
