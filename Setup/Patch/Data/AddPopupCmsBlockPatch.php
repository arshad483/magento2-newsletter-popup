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

namespace Ultraplugin\NewsletterPopup\Setup\Patch\Data;

use Magento\Cms\Api\BlockRepositoryInterface;
use Magento\Cms\Api\Data\BlockInterfaceFactory;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\App\Config\Storage\WriterInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Ultraplugin\NewsletterPopup\Block\Popup;

class AddPopupCmsBlockPatch implements DataPatchInterface
{
    /**
     * @var ModuleDataSetupInterface
     */
    protected $moduleDataSetup;

    /**
     * @var BlockRepositoryInterface
     */
    protected $blockRepository;

    /**
     * @var BlockInterfaceFactory
     */
    protected $blockFactory;

    /**
     * @var SearchCriteriaBuilder
     */
    protected $criteriaBuilder;

    /**
     * @var WriterInterface
     */
    protected $configWriter;

    /**
     * @param ModuleDataSetupInterface $moduleDataSetup
     * @param BlockRepositoryInterface $blockRepository
     * @param BlockInterfaceFactory $blockFactory
     * @param SearchCriteriaBuilder $criteriaBuilder
     * @param WriterInterface $configWriter
     */
    public function __construct(
        ModuleDataSetupInterface $moduleDataSetup,
        BlockRepositoryInterface $blockRepository,
        BlockInterfaceFactory $blockFactory,
        SearchCriteriaBuilder $criteriaBuilder,
        WriterInterface $configWriter
    ) {
        $this->moduleDataSetup = $moduleDataSetup;
        $this->blockRepository = $blockRepository;
        $this->blockFactory = $blockFactory;
        $this->criteriaBuilder = $criteriaBuilder;
        $this->configWriter = $configWriter;
    }

    /**
     * Create CMS Block
     *
     * @return AddPopupCmsBlockPatch|void
     */
    public function apply()
    {
        try {
            $content = [
                'title'      => 'Newsletter Popup Content',
                'identifier' => 'newsletter_popup_content',
                'content'    => '<p>Subscribe to our newsletter for fresh updates and special savings.</p>',
                'stores'     => [0],
                'is_active'  => 1,
            ];
            $criteria = $this->criteriaBuilder
                ->addFilter(
                    'identifier',
                    'newsletter_popup_content'
                )
                ->addFilter('store_id', 0)
                ->create();
            $blocks = $this->blockRepository->getList($criteria);

            if (!($blocks->getTotalCount())) {
                $block = $this->blockFactory->create();
                $block->setData($content);
                $newBlock = $this->blockRepository->save($block);
                if ($newBlock) {
                    $this->configWriter->save(
                        Popup::XML_PATH_NEWSLETTER_POPUP_CMS_BLOCK,
                        $newBlock->getBlockId()
                    );
                }
            }
        } catch (\Exception $e) {
            //Skip for error
        }
    }

    /**
     * @inheritdoc
     */
    public static function getDependencies()
    {
        return [

        ];
    }

    /**
     * @inheritdoc
     */
    public function getAliases()
    {
        return [];
    }
}
