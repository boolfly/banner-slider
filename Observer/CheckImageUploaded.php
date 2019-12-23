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
use Boolfly\BannerSlider\Model\ImageField;
use Boolfly\BannerSlider\Helper\RedundantImageChecker;

/**
 * Class CheckImageUploaded
 *
 * @package Boolfly\BannerSlider\Observer
 *
 * @event boolfly_banner_delete_commit_after
 * @event boolfly_banner_save_commit_after
 */
class CheckImageUploaded implements ObserverInterface
{
    /**
     * @var RedundantImageChecker
     */
    private $redundantImageChecker;

    /**
     * CheckingImageUploaded constructor.
     *
     * @param RedundantImageChecker $redundantImageChecker
     */
    public function __construct(
        RedundantImageChecker $redundantImageChecker
    ) {
        $this->redundantImageChecker = $redundantImageChecker;
    }

    /**
     * Dispatch event
     *
     * @param Observer $observer
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function execute(Observer $observer)
    {
        $banner = $observer->getEvent()->getData('banner');
        if ($banner && $banner instanceof BannerInterface) {
            foreach (ImageField::getField() as $field) {
                $this->redundantImageChecker->process($banner->getOrigData($field));
            }
        }
    }
}
