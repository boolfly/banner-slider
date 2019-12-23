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
 * Class Edit
 *
 * @package Boolfly\BannerSlider\Controller\Adminhtml\Banner
 */
class Edit extends AbstractSlider
{

    /**
     * @return void
     */
    public function execute()
    {
        /** @var \Boolfly\BannerSlider\Model\Slider $model */
        $model = $this->sliderFactory->create();
        $this->coreRegistry->register('current_slider', $model);

        $id = $this->getRequest()->getParam('id');
        if ($id) {
            $model->load($id);
            if (!$model->getId()) {
                $this->messageManager->addErrorMessage(__('This slider no longer exists.'));
                $this->_redirect('bannerslider/slider/*');
                return;
            }
        }
        $data = $this->_objectManager->get('Magento\Backend\Model\Session')->getPageData(true);
        if (!empty($data)) {
            $model->addData($data);
        }

        $this->_initAction();
        $this->_addBreadcrumb(
            $id ? __('Edit Slider') : __('New Slider'),
            $id ? __('Edit Slider') : __('New Slider')
        );

        $this->_view->getPage()->getConfig()->getTitle()->prepend(
            $model->getId() ? $model->getTitle() : __('New Slider')
        );
        $this->_view->renderLayout();
    }
}
