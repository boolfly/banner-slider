<?php
/************************************************************
 * *
 *  * Copyright © Boolfly. All rights reserved.
 *  * See COPYING.txt for license details.
 *  *
 *  * @author    info@boolfly.com
 * *  @project   Banner Slider
 */
namespace Boolfly\BannerSlider\Observer;

use Boolfly\BannerSlider\Api\Data\BannerInterface;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Boolfly\BannerSlider\Model\ImageUploader;
use Boolfly\BannerSlider\Model\ImageField;
use Boolfly\BannerSlider\Helper\RedundantImageChecker;

/**
 * Class ProcessingImageUpload
 *
 * @package Boolfly\BannerSlider\Observer
 */
class ProcessingImageUpload implements ObserverInterface
{
    /**
     * @var ImageUploader
     */
    private $imageUploader;

    /**
     * @var RedundantImageChecker
     */
    private $redundantImageChecker;

    /**
     * ProcessingImageUpload constructor.
     *
     * @param ImageUploader         $imageUploader
     * @param RedundantImageChecker $redundantImageChecker
     */
    public function __construct(
        ImageUploader $imageUploader,
        RedundantImageChecker $redundantImageChecker
    ) {
        $this->imageUploader         = $imageUploader;
        $this->redundantImageChecker = $redundantImageChecker;
    }

    /**
     * @param Observer $observer
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function execute(Observer $observer)
    {
        $banner = $observer->getEvent()->getData('banner');
        if ($banner && $banner instanceof BannerInterface) {
            foreach (ImageField::getField() as $field) {
                $this->processFile($banner, $field);
            }
        }
    }

    /**
     * Process File
     *
     * @param \Boolfly\BannerSlider\Model\Banner|BannerInterface $object
     * @param $key
     * @return $this
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function processFile(BannerInterface $object, $key)
    {
        $files = $object->getData($key);
        $object->setData($key, null);
        if (is_array($files) && !empty($files)) {
            foreach ($files as $file) {
                if (is_array($file) && empty($file['name'])) {
                    continue;
                }
                $name = $file['name'];
                // Upload New File
                if (isset($file['type']) && $file['tmp_name']) {
                    $this->imageUploader->moveFileFromTmp($name);
                }
                $object->setData($key, $name);
            }
        }

        return $this;
    }
}
