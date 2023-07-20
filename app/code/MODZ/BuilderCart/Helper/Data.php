<?php

namespace MODZ\BuilderCart\Helper;

class Data
{
    const BUILDERCART_ENABLE = 'modz_buildercart/general/enable';
    const BUILDERCART_APPURL = 'modz_buildercart/general/app_url';

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $scopeConfig;

    public function __construct(\Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig)
    {
        $this->scopeConfig = $scopeConfig;
    }

    public function isEnabled()
    {
        return $this->scopeConfig->getValue(self::BUILDERCART_ENABLE);
    }

    public function getBuilderAppUrl()
    {
        return $this->scopeConfig->getValue(self::BUILDERCART_APPURL);
    }
}
