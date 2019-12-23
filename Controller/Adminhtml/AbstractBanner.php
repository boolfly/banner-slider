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
use Magento\Framework\Stdlib\DateTime\Filter\Date;
use Boolfly\BannerSlider\Api\Data\BannerInterfaceFactory;

/**
 * Class AbstractBanner
 *
 * @package Boolfly\BannerSlider\Controller\Adminhtml
 */
abstract class AbstractBanner extends Action
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = 'Boolfly_BannerSlider::banner';

    /**
     * Core registry
     *
     * @var Registry
     */
    protected $_coreRegistry = null;

    /**
     * @var BannerInterfaceFactory
     */
    protected $bannerFactory;

    /**
     * AbstractBanner constructor.
     *
     * @param Context                $context
     * @param Registry               $coreRegistry
     * @param BannerInterfaceFactory $bannerFactory
     */
    public function __construct(
        Context $context,
        Registry $coreRegistry,
        BannerInterfaceFactory $bannerFactory
    ) {
        parent::__construct($context);
        $this->_coreRegistry = $coreRegistry;
        $this->bannerFactory = $bannerFactory;
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
            __('Manage Banner'),
            __('Manage Banner')
        );
        return $this;
    }
}
