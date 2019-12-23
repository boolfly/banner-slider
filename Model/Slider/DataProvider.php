<?php
/************************************************************
 * *
 *  * Copyright © Boolfly. All rights reserved.
 *  * See COPYING.txt for license details.
 *  *
 *  * @author    info@boolfly.com
 * *  @project   Banner Slider
 */
namespace Boolfly\BannerSlider\Model\Slider;

use Boolfly\BannerSlider\Helper\Data;
use Boolfly\BannerSlider\Model\Source\Status;
use Magento\Framework\Api\Filter;
use Magento\Framework\UrlInterface;
use Magento\Ui\DataProvider\AbstractDataProvider;
use Magento\Framework\Registry;
use Boolfly\BannerSlider\Api\Data\BannerInterface;
use Boolfly\BannerSlider\Model\ResourceModel\Banner\CollectionFactory as BannerCollectionFactory;
use Magento\Cms\Model\ResourceModel\Page\CollectionFactory as PageCollectionFactory;

/**
 * Class DataProvider
 */
class DataProvider extends AbstractDataProvider
{

    /**
     * @var array
     */
    protected $loadedData;

    /**
     * Core registry
     *
     * @var \Magento\Framework\Registry
     */
    protected $coreRegistry;

    /**
     * @var BannerCollectionFactory
     */
    private $bannerCollectionFactory;

    /**
     * @var Data
     */
    private $helperData;

    /**
     * @var UrlInterface
     */
    private $urlBuilder;
    /**
     * @var Status
     */
    private $status;
    /**
     * @var PageCollectionFactory
     */
    private $pageCollectionFactory;

    /**
     * DataProvider constructor.
     *
     * @param $name
     * @param $primaryFieldName
     * @param $requestFieldName
     * @param Registry                $registry
     * @param Data                    $helperData
     * @param UrlInterface            $urlBuilder
     * @param Status                  $status
     * @param BannerCollectionFactory $bannerCollectionFactory
     * @param PageCollectionFactory   $pageCollectionFactory
     * @param array                   $meta
     * @param array                   $data
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        Registry $registry,
        Data $helperData,
        UrlInterface $urlBuilder,
        Status $status,
        BannerCollectionFactory $bannerCollectionFactory,
        PageCollectionFactory $pageCollectionFactory,
        array $meta = [],
        array $data = []
    ) {
        $this->coreRegistry            = $registry;
        $this->bannerCollectionFactory = $bannerCollectionFactory;
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
        $this->helperData            = $helperData;
        $this->urlBuilder            = $urlBuilder;
        $this->status                = $status;
        $this->pageCollectionFactory = $pageCollectionFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function getData()
    {
        if (isset($this->loadedData)) {
            return $this->loadedData;
        }
        $this->loadedData = [];
        /** @var BannerInterface | \Boolfly\BannerSlider\Model\Slider $slider */
        $slider = $this->coreRegistry->registry('current_slider');
        if ($slider->getId()) {
            $this->prepareBannerData($slider);
            $this->prepareCmsPageData($slider);

            $sliderData                         = $slider->getData();
            $this->loadedData[$slider->getId()] = $sliderData;
        }

        return $this->loadedData;
    }

    /**
     * Prepare Banner Data
     *
     * @param \Boolfly\BannerSlider\Model\Slider $slider
     * @throws \Exception
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    private function prepareBannerData($slider)
    {
        $assignedBanners = $slider->getData('assigned_banners');
        if (is_array($assignedBanners) && !empty($assignedBanners)) {
            $bannerIds = array_keys($assignedBanners);
            if (!empty($bannerIds)) {
                $bannerCollection = $this->bannerCollectionFactory->create();
                $bannerCollection->addFieldToFilter('banner_id', ['in' => $bannerIds]);
                $newBannerData = [];
                /** @var \Boolfly\BannerSlider\Model\Banner $banner */
                foreach ($bannerCollection as $banner) {
                    $newBannerData[] = [
                        'banner_id' => $banner->getId(),
                        'title' => $banner->getTitle(),
                        'image_desktop' => $this->helperData->getResizeImage($banner->getData('image_desktop'), null, 50),
                        'image_tablet' => $this->helperData->getResizeImage($banner->getData('image_tablet'), null, 50),
                        'image_mobile' => $this->helperData->getResizeImage($banner->getData('image_mobile'), null, 50),
                        'position' => $assignedBanners[$banner->getId()],
                        'status' => (string) $this->status->getOptionText((int)$banner->getStatus())
                    ];
                }
                usort($newBannerData, function ($a, $b) {
                    return $a['position'] <=> $b['position'];
                });
                $slider->setData('assigned_banners', $newBannerData);
            }
        }
    }

    /**
     * Prepare CMS Page Data
     *
     * @param \Boolfly\BannerSlider\Model\Slider $slider
     */
    private function prepareCmsPageData($slider)
    {
        $cmsPageIds = $slider->getData('cms_pages');
        if (is_array($cmsPageIds) && !empty($cmsPageIds)) {
            $pageCollection = $this->pageCollectionFactory->create();
            $pageCollection->addFieldToFilter('page_id', $cmsPageIds);
            $newCmsPageData = [];
            /** @var \Magento\Cms\Model\Page $page */
            foreach ($pageCollection as $page) {
                $status           = $page->getAvailableStatuses();
                $isActiveText     = (string)$status[(int)$page->getData('is_active')];
                $newCmsPageData[] = [
                    'page_id' => $page->getId(),
                    'title' => $page->getTitle(),
                    'identifier' => $page->getIdentifier(),
                    'is_active' => $isActiveText
                ];
            }
            $slider->setData('cms_pages', $newCmsPageData);
        }
    }

    /**
     * @inheritdoc
     */
    public function addFilter(Filter $filter)
    {
        return [];
    }
}
