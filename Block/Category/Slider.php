<?php
/***********************************************************************
 * *
 *  *
 *  * @copyright Copyright © Boolfly. All rights reserved.
 *  * See COPYING.txt for license details.
 *  * @author    info@boolfly.com
 * *
 */
namespace Boolfly\BannerSlider\Block\Category;

use Boolfly\BannerSlider\Model\ResourceModel\Slider\Collection as SliderCollection;
use Magento\Framework\Registry;
use Magento\Framework\Serialize\Serializer\Json;
use Magento\Framework\View\Element\Template as Template;
use Boolfly\BannerSlider\Model\ResourceModel\Banner\CollectionFactory as BannerCollectionFactory;
use Boolfly\BannerSlider\Model\ResourceModel\Slider\CollectionFactory as SliderCollectionFactory;
use Boolfly\BannerSlider\Block\AbstractSlider;

/**
 * Class Slider
 *
 * @package Boolfly\BannerSlider\Block\Category
 */
class Slider extends AbstractSlider
{
    /**
     * @var Registry
     */
    protected $coreRegistry;

    /**
     * Slider constructor.
     *
     * @param Template\Context        $context
     * @param BannerCollectionFactory $bannerCollectionFactory
     * @param SliderCollectionFactory $sliderCollectionFactory
     * @param Registry                $registry
     * @param Json                    $serializer
     * @param array                   $data
     */
    public function __construct(
        Template\Context $context,
        BannerCollectionFactory $bannerCollectionFactory,
        SliderCollectionFactory $sliderCollectionFactory,
        Registry $registry,
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
        $this->coreRegistry = $registry;
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
            $catId        = $this->getCategoryId();
            if ($catId) {
                /** @var SliderCollection $sliderCollection */
                $sliderCollection = $this->sliderCollectionFactory->create();
                $sliderCollection->addCategoryToFilter($catId);
                $sliderCollection->addActiveStatusFilter();
                if ($sliderCollection->getSize() > 0) {
                    $this->slider = $sliderCollection->getFirstItem();
                }
            }
        }

        return $this->slider;
    }

    /**
     * Get Current Category
     *
     * @return \Magento\Catalog\Model\Category
     */
    public function getCurrentCategory()
    {
        return $this->coreRegistry->registry('current_category');
    }

    /**
     * Get Category
     *
     * @return boolean|mixed
     */
    protected function getCategoryId()
    {
        $category = $this->getCurrentCategory();
        if ($category && $category->getId()) {
            return $category->getId();
        }
        return false;
    }
}
