<?php
/************************************************************
 * *
 *  * Copyright © Boolfly. All rights reserved.
 *  * See COPYING.txt for license details.
 *  *
 *  * @author    info@boolfly.com
 * *  @project   Banner Slider
 */
namespace Boolfly\BannerSlider\Controller\Adminhtml\Banner;

use Boolfly\BannerSlider\Controller\Adminhtml\AbstractBanner;

/**
 * Class NewAction
 *
 * @package Boolfly\BannerSlider\Controller\Adminhtml\Banner
 */
class NewAction extends AbstractBanner
{

    /**
     * New Banner action
     *
     * @return void
     */
    public function execute()
    {
        $this->_forward('edit');
    }
}
