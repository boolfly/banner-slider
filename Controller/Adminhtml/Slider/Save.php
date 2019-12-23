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
use Magento\Framework\Exception\LocalizedException;

/**
 * Class Save
 *
 * @package Boolfly\BannerSlider\Controller\Adminhtml\Slider
 */
class Save extends AbstractSlider
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
            /** @var \Boolfly\BannerSlider\Model\Slider $model */
            $model = $this->sliderFactory->create();
            if (!empty($data['slider_id'])) {
                $model->load($data['slider_id']);
                if (!$model->getId()) {
                    throw new LocalizedException(__('Wrong Slider ID: %1.', $data['slider_id']));
                }
            }
            if (empty($data['category_id'])) {
                $model->setCategoryIds([]);
            }

            if (empty($data['cms_pages'])) {
                $model->setData('cms_pages', []);
            }
            $model->addData($data);
            $this->_objectManager->get('Magento\Backend\Model\Session')->setPageData($model->getData());
            try {
                $model->save();
                $this->messageManager->addSuccessMessage(__('The slider has been saved.'));
                $this->_objectManager->get('Magento\Backend\Model\Session')->setPageData(false);
                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath('*/*/edit', ['id' => $model->getId(), '_current' => true]);
                }

                return $resultRedirect->setPath('*/*/');
            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage(__($e->getMessage()));
                $this->messageManager->addErrorMessage(__('Something went wrong while saving the slider.'));
                $this->_objectManager->get('Psr\Log\LoggerInterface')->critical($e->getMessage());
                return $resultRedirect->setPath('*/*/edit', ['id' => $this->getRequest()->getParam('id')]);
            }
        }

        return $resultRedirect->setPath('*/*/');
    }
}
