<?php
/***********************************************************************
 * *
 *  *
 *  * @copyright Copyright © Boolfly. All rights reserved.
 *  * See COPYING.txt for license details.
 *  * @author    info@boolfly.com
 * *
 */
namespace Boolfly\BannerSlider\Block;

use Boolfly\BannerSlider\Model\ResourceModel\Banner\Collection;
use Boolfly\BannerSlider\Model\ResourceModel\Slider\Collection as SliderCollection;
use Magento\Cms\Model\PageFactory;
use Magento\Framework\Serialize\Serializer\Json;
use Magento\Framework\View\Element\Template as Template;
use Boolfly\BannerSlider\Model\ResourceModel\Banner\CollectionFactory as BannerCollectionFactory;
use Boolfly\BannerSlider\Model\ResourceModel\Slider\CollectionFactory as SliderCollectionFactory;
use Boolfly\BannerSlider\Model\Source\Effect;
use Magento\Cms\Helper\Page;

/**
 * Class AbstractSlider
 *
 * @package Boolfly\BannerSlider\Block
 */
abstract class AbstractSlider extends Template
{
    /**
     * @var BannerCollectionFactory
     */
    protected $bannerCollectionFactory;

    /**
     * @var SliderCollectionFactory
     */
    protected $sliderCollectionFactory;

    /**
     * @var \Boolfly\BannerSlider\Model\Slider
     */
    protected $slider;

    /**
     * @var Collection
     */
    protected $bannerCollection;

    /**
     * @var Json
     */
    protected $serializer;

    /**
     * AbstractSlider constructor.
     *
     * @param Template\Context        $context
     * @param BannerCollectionFactory $bannerCollectionFactory
     * @param SliderCollectionFactory $sliderCollectionFactory
     * @param Json                    $serializer
     * @param array                   $data
     */
    public function __construct(
        Template\Context $context,
        BannerCollectionFactory $bannerCollectionFactory,
        SliderCollectionFactory $sliderCollectionFactory,
        Json $serializer,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->bannerCollectionFactory = $bannerCollectionFactory;
        $this->sliderCollectionFactory = $sliderCollectionFactory;
        $this->serializer              = $serializer;
    }

    /**
     * Get banner collection
     *
     * @return boolean|Collection
     */
    public function getBannerCollection()
    {
        if ($this->bannerCollection === null) {
            $this->bannerCollection = false;
            if ($this->getSlider() && ($sliderId = $this->getSlider()->getId())) {
                $bannerCollection = $this->bannerCollectionFactory->create();
                $bannerCollection->addSliderToFilter($sliderId);
                $bannerCollection->addActiveStatusFilter();
                $this->bannerCollection = $bannerCollection;
            }
        }

        return $this->bannerCollection;
    }

    /**
     * @return string
     */
    protected function _toHtml()
    {
        if ($this->getBannerCollection() && $this->getBannerCollection()->getSize() > 0) {
            return parent::_toHtml();
        }

        return '';
    }

    /**
     * Get Slider
     *
     * @return boolean|\Boolfly\BannerSlider\Model\Slider
     */
    abstract public function getSlider();

    /**
     * @return boolean|string
     */
    protected function isFadeEffect()
    {
        $slider = $this->getSlider();
        return $slider && $slider->getAnimationEffect() == Effect::FADE_EFFECT;
    }

    /**
     * Return Json Encoded
     *
     * @return string
     */
    public function getJsonData()
    {
        $config = [
            'fade' => $this->isFadeEffect(),
            'autoplay' => $this->getSlider()->isAutoPlay(),
            'autoplaySpeed' => $this->getSlider()->getSpeed() ?: 5000,
        ];

        return $this->serializer->serialize($config);
    }
}
