<?php
/************************************************************
 * *
 *  * Copyright © Boolfly. All rights reserved.
 *  * See COPYING.txt for license details.
 *  *
 *  * @author    info@boolfly.com
 * *  @project   Banner Slider
 */
namespace Boolfly\BannerSlider\Model\Banner;

use Boolfly\BannerSlider\Model\ImageUploader;
use Boolfly\BannerSlider\Model\ResourceModel\Banner\CollectionFactory;
use Boolfly\BannerSlider\Model\ResourceModel\Banner\Collection;
use Magento\Ui\DataProvider\AbstractDataProvider;
use Magento\Framework\Registry;
use Boolfly\BannerSlider\Api\Data\BannerInterface;
use Boolfly\BannerSlider\Model\ImageField;

/**
 * Class DataProvider
 */
class DataProvider extends AbstractDataProvider
{

    /**
     * @var Collection
     */
    protected $collection;

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
     * @var ImageUploader
     */
    private $imageUploader;

    /**
     * DataProvider constructor.
     *
     * @param $name
     * @param $primaryFieldName
     * @param $requestFieldName
     * @param CollectionFactory $collectionFactory
     * @param ImageUploader     $imageUploader
     * @param Registry          $registry
     * @param array             $meta
     * @param array             $data
     */
    public function __construct(
        string $name,
        string $primaryFieldName,
        string $requestFieldName,
        CollectionFactory $collectionFactory,
        ImageUploader $imageUploader,
        Registry $registry,
        array $meta = [],
        array $data = []
    ) {
        $this->collection   = $collectionFactory->create();
        $this->coreRegistry = $registry;
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
        $this->imageUploader = $imageUploader;
    }

    /**
     * {@inheritdoc}
     */
    public function getData()
    {
        if (isset($this->loadedData)) {
            return $this->loadedData;
        }
        /** @var BannerInterface | \Boolfly\BannerSlider\Model\Banner $banner */
        $banner = $this->coreRegistry->registry('current_banner');
        if ($banner && $banner->getId()) {
            $bannerData = $banner->getData();
            foreach (ImageField::getField() as $field) {
                unset($bannerData[$field]);
                $imageName = $banner->getData($field);
                if ($imageSrc = $banner->getImageUrl($imageName)) {
                    try {
                        $size = $this->imageUploader->getSize($imageName);
                    } catch (\Exception $e) {
                        $size = 'undefined';
                    }
                    $bannerData[$field][] = [
                        'name' => $imageName,
                        'url' => $imageSrc,
                        'size' => $size
                    ];
                }
            }
            if ($banner->getButtonText() || $banner->getButtonUrl()) {
                $bannerData['enable_button'] = '1';
            } else {
                $bannerData['enable_button'] = '0';
            }
            $this->loadedData[$banner->getId()] = $bannerData;
        } else {
            $this->loadedData = [];
        }

        return $this->loadedData;
    }
}
