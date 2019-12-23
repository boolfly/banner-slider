<?php
/************************************************************
 * *
 *  * Copyright © Boolfly. All rights reserved.
 *  * See COPYING.txt for license details.
 *  *
 *  * @author    info@boolfly.com
 * *  @project   Banner Slider
 */
namespace Boolfly\BannerSlider\Controller\Adminhtml\Slider;

use Boolfly\BannerSlider\Controller\Adminhtml\AbstractSlider;

/**
 * Class NewAction
 *
 * @package Boolfly\BannerSlider\Controller\Adminhtml\Slider
 */
class NewAction extends AbstractSlider
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
