<?php
/**
 * UltraPlugin
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the ultraplugin.com license that is
 * available through the world-wide-web at this URL:
 * https://ultraplugin.com/end-user-license-agreement
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * version in the future.
 *
 * @category    UltraPlugin
 * @package     Ultraplugin_NewsletterPopup
 * @copyright   Copyright (c) UltraPlugin (https://ultraplugin.com/)
 * @license     https://ultraplugin.com/end-user-license-agreement
 */

namespace Ultraplugin\NewsletterPopup\Block;

use Magento\Customer\Model\Session;
use Magento\Framework\View\Element\Template;
use Magento\Store\Model\ScopeInterface;

class Popup extends Template
{
    public const XML_PATH_NEWSLETTER_POPUP_TITLE = 'newsletter_popup/general/title';
    public const XML_PATH_NEWSLETTER_POPUP_DELAY_SECONDS = 'newsletter_popup/general/delay_seconds';
    public const XML_PATH_NEWSLETTER_POPUP_CMS_BLOCK = 'newsletter_popup/general/cms_block';

    protected $customerSession;

    /**
     * Popup constructor.
     *
     * @param Template\Context $context
     * @param Session $customerSession
     * @param array $data
     */
    public function __construct(
        Template\Context $context,
        Session $customerSession,
        array $data = []
    ) {
        $this->customerSession = $customerSession;
        parent::__construct($context, $data);
    }

    /**
     * Get title of popup
     *
     * @return string
     */
    public function getPopupTitle()
    {
        return $this->getConfig(self::XML_PATH_NEWSLETTER_POPUP_TITLE);
    }

    /**
     * Get delay seconds
     *
     * @return int
     */
    public function getPopupDelay()
    {
        return $this->getConfig(self::XML_PATH_NEWSLETTER_POPUP_DELAY_SECONDS);
    }

    /**
     * Get cms block content for popup
     *
     * @return string
     */
    public function getCmsBlockContent()
    {
        $content = '';
        $blockId = $this->getConfig(self::XML_PATH_NEWSLETTER_POPUP_CMS_BLOCK);
        try {
            $content = $this->getLayout()
                ->createBlock(\Magento\Cms\Block\Block::class)
                ->setBlockId($blockId)
                ->toHtml();
        } catch (\Exception $e) {
        }

        return $content;
    }

    /**
     * Get config value
     *
     * @param string $path
     * @return mixed
     */
    public function getConfig($path)
    {
        return $this->_scopeConfig->getValue(
            $path,
            ScopeInterface::SCOPE_STORES
        );
    }
}
