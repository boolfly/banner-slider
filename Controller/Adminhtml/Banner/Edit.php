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
use Boolfly\BannerSlider\Model\ImageField;

/**
 * Class Edit
 *
 * @package Boolfly\BannerSlider\Controller\Adminhtml\Banner
 */
class Edit extends AbstractBanner
{

    /**
     * @return void
     */
    public function execute()
    {
        /** @var \Boolfly\BannerSlider\Model\Banner $model */
        $model = $this->bannerFactory->create();
        $this->_coreRegistry->register('current_banner', $model);

        $id = $this->getRequest()->getParam('id');
        if ($id) {
            $model->load($id);
            if (!$model->getId()) {
                $this->messageManager->addErrorMessage(__('This banner no longer exists.'));
                $this->_redirect('bannerslider/banner/*');
                return;
            }
        }
        $data = $this->_objectManager->get('Magento\Backend\Model\Session')->getPageData(true);
        if (!empty($data)) {
            foreach (ImageField::getField() as $field) {
                unset($data[$field]);
            }
            $model->addData($data);
        }

        $this->_initAction();
        $this->_addBreadcrumb(
            $id ? __('Edit Banner') : __('New Banner'),
            $id ? __('Edit Banner') : __('New Banner')
        );

        $this->_view->getPage()->getConfig()->getTitle()->prepend(
            $model->getId() ? $model->getTitle() : __('New Banner')
        );
        $this->_view->renderLayout();
    }
}
