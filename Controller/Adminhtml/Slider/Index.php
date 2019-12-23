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
 * Class Index
 *
 * @package Boolfly\BannerSlider\Controller\Adminhtml\Slider
 */
class Index extends AbstractSlider
{

    /**
     * @return void
     */
    public function execute()
    {
        $this->_initAction()->_addBreadcrumb(__('Content'), __('Content'));
        $this->_view->getPage()->getConfig()->getTitle()->prepend(__('Manage Slider'));
        $this->_view->renderLayout();
    }
}
