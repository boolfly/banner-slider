<?php
/***********************************************************************
 * *
 *  *
 *  * @copyright Copyright © Boolfly. All rights reserved.
 *  * See COPYING.txt for license details.
 *  * @author    info@boolfly.com
 * *
 */
namespace Boolfly\BannerSlider\Block\Cms;

use Boolfly\BannerSlider\Model\ResourceModel\Slider\Collection as SliderCollection;
use Magento\Cms\Model\PageFactory;
use Magento\Framework\Serialize\Serializer\Json;
use Magento\Framework\View\Element\Template as Template;
use Boolfly\BannerSlider\Model\ResourceModel\Banner\CollectionFactory as BannerCollectionFactory;
use Boolfly\BannerSlider\Model\ResourceModel\Slider\CollectionFactory as SliderCollectionFactory;
use Magento\Cms\Helper\Page;
use Boolfly\BannerSlider\Block\AbstractSlider;

/**
 * Class Slider
 *
 * @package Boolfly\BannerSlider\Block\Cms
 */
class Slider extends AbstractSlider
{
    /**
     * @var PageFactory
     */
    private $pageFactory;

    /**
     * Slider constructor.
     *
     * @param Template\Context        $context
     * @param BannerCollectionFactory $bannerCollectionFactory
     * @param SliderCollectionFactory $sliderCollectionFactory
     * @param PageFactory             $pageFactory
     * @param Json                    $serializer
     * @param array                   $data
     */
    public function __construct(
        Template\Context $context,
        BannerCollectionFactory $bannerCollectionFactory,
        SliderCollectionFactory $sliderCollectionFactory,
        PageFactory $pageFactory,
        Json $serializer,
        array $data = []
    ) {
        parent::__construct(
            $context,
            $bannerCollectionFactory,
            $sliderCollectionFactory,
            $serializer,
            $data
        );
        $this->pageFactory = $pageFactory;
    }

    /**
     * Get Slider
     *
     * @return boolean|\Boolfly\BannerSlider\Model\Slider
     */
    public function getSlider()
    {
        if ($this->slider === null) {
            $this->slider = false;
            $pageId       = $this->getPageId();
            if ($pageId) {
                /** @var SliderCollection $sliderCollection */
                $sliderCollection = $this->sliderCollectionFactory->create();
                $sliderCollection->addCmsPageToFilter($pageId);
                $sliderCollection->addActiveStatusFilter();
                if ($sliderCollection->getSize() > 0) {
                    $this->slider = $sliderCollection->getFirstItem();
                }
            }
        }

        return $this->slider;
    }

    /**
     * @return $this|mixed
     */
    protected function getPageId()
    {
        if ($this->isHomepage()) {
            $pageIdentifier = $this->_scopeConfig->getValue(Page::XML_PATH_HOME_PAGE);
            $page           = $this->pageFactory->create()->load($pageIdentifier);
            return $page->getId();
        } else {
            $pageId = $this->getRequest()->getParam('page_id', false);
        }

        return $pageId;
    }

    /**
     * Check is homepage
     *
     * @return boolean
     */
    public function isHomepage()
    {
        return $this->getRequest()->getFullActionName() === 'cms_index_index';
    }
}
