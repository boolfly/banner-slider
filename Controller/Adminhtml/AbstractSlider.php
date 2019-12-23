<?php
/************************************************************
 * *
 *  * Copyright © Boolfly. All rights reserved.
 *  * See COPYING.txt for license details.
 *  *
 *  * @author    info@boolfly.com
 * *  @project   Banner Slider
 */
namespace Boolfly\BannerSlider\Controller\Adminhtml;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Registry;
use Boolfly\BannerSlider\Api\Data\SliderInterfaceFactory;

/**
 * Class AbstractSlider
 *
 * @package Boolfly\BannerSlider\Controller\Adminhtml
 */
abstract class AbstractSlider extends Action
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = 'Boolfly_BannerSlider::slider';

    /**
     * Core registry
     *
     * @var Registry
     */
    protected $coreRegistry = null;


    /**
     * @var SliderInterfaceFactory
     */
    protected $sliderFactory;

    /**
     * AbstractSlider constructor.
     *
     * @param Context                $context
     * @param Registry               $coreRegistry
     * @param SliderInterfaceFactory $sliderFactory
     */
    public function __construct(
        Context $context,
        Registry $coreRegistry,
        SliderInterfaceFactory $sliderFactory
    ) {
        parent::__construct($context);
        $this->coreRegistry  = $coreRegistry;
        $this->sliderFactory = $sliderFactory;
    }

    /**
     * Init action
     *
     * @return $this
     */
    protected function _initAction()
    {
        $this->_view->loadLayout();
        $this->_setActiveMenu(
            'Boolfly_BannerSlider::manager'
        )->_addBreadcrumb(
            __('Manage Slider'),
            __('Manage Slider')
        );
        return $this;
    }
}
