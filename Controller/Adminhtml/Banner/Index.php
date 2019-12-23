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
 * Class Index
 *
 * @package Boolfly\BannerSlider\Controller\Adminhtml\Banner
 */
class Index extends AbstractBanner
{

    /**
     * @return void
     */
    public function execute()
    {
        $this->_initAction()->_addBreadcrumb(__('Content'), __('Content'));
        $this->_view->getPage()->getConfig()->getTitle()->prepend(__('Manage Banner'));
        $this->_view->renderLayout();
    }
}
