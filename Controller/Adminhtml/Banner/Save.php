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
use Magento\Framework\Exception\LocalizedException;

/**
 * Class Save
 *
 * @package Boolfly\BannerSlider\Controller\Adminhtml\Banner
 */
class Save extends AbstractBanner
{
    /**
     * @return $this|\Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\ResultInterface
     * @throws LocalizedException
     */
    public function execute()
    {
        $data = $this->getRequest()->getPostValue();
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($data) {
            /** @var \Boolfly\BannerSlider\Model\Banner $model */
            $model = $this->bannerFactory->create();
            if (!empty($data['banner_id'])) {
                $model->load($data['banner_id']);
                if (!$model->getId()) {
                    throw new LocalizedException(__('Wrong banner Id: %1.', $data['banner_id']));
                }
            }
            $model->addData($data);
            $this->_objectManager->get('Magento\Backend\Model\Session')->setPageData($model->getData());
            try {
                $this->_eventManager->dispatch('controller_action_boolfly_banner_save_entity_before',
                    ['controller' => $this, 'banner' => $model]
                );
                $model->save();
                $this->messageManager->addSuccessMessage(__('The banner has been saved.'));
                $this->_objectManager->get('Magento\Backend\Model\Session')->setPageData(false);
                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath('*/*/edit', ['id' => $model->getId(), '_current' => true]);
                }

                return $resultRedirect->setPath('*/*/');
            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage(__('Something went wrong while saving the banner.'));
                $this->_objectManager->get('Psr\Log\LoggerInterface')->critical($e->getMessage());
                return $resultRedirect->setPath('*/*/edit', ['id' => $this->getRequest()->getParam('id')]);
            }
        }

        return $resultRedirect->setPath('*/*/');
    }
}
