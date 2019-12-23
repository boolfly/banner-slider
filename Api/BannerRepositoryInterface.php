<?php
/************************************************************
 * *
 *  * Copyright © Boolfly. All rights reserved.
 *  * See COPYING.txt for license details.
 *  *
 *  * @author    info@boolfly.com
 * *  @project   Banner Slider
 */
namespace Boolfly\BannerSlider\Api;

use Boolfly\BannerSlider\Api\Data\BannerInterface;

/**
 * Interface BannerRepositoryInterface
 *
 * @package Boolfly\BannerSlider\Api
 */
interface BannerRepositoryInterface
{
    /**
     * Get Banner by Id
     *
     * @param string  $bannerId
     * @param boolean $force
     * @return null|BannerInterface
     */
    public function get($bannerId, $force = false);

    /**
     * Delete Banner
     *
     * @param BannerInterface $banner
     * @return boolean
     */
    public function delete(BannerInterface $banner);

    /**
     * Save Banner
     *
     * @param BannerInterface $banner
     * @return BannerInterface
     */
    public function save(BannerInterface $banner);
}
