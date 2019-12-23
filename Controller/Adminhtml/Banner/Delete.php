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
 * Class Delete
 *
 * @package Boolfly\BannerSlider\Controller\Adminhtml\Banner
 */
class Delete extends AbstractBanner
{

    /**
     * @return $this|\Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $id = (int)$this->getRequest()->getParam('id');
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($id) {
            /** @var \Boolfly\BannerSlider\Model\Banner $model */
            $model = $this->bannerFactory->create();
            try {
                $model->load($id);
                $model->delete();
                $this->messageManager->addSuccessMessage(__('The banner has been deleted.'));
                return $resultRedirect->setPath('*/*/');
            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage(__('Something went wrong while deleted the banner.'));
                $this->_objectManager->get('Psr\Log\LoggerInterface')->critical($e->getMessage());
            }
        }

        return $resultRedirect->setPath('*/*/');
    }
}
