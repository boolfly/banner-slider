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
use Magento\Framework\DataObject;
use Magento\Framework\Controller\ResultFactory;

/**
 * Class Validate
 *
 * @package Boolfly\BannerSlider\Controller\Adminhtml\Slider
 */
class Validate extends AbstractSlider
{

    /**
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\Result\Json|\Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        /** @var \Magento\Framework\Controller\Result\Json $resultJson */
        $resultJson = $this->resultFactory->create(ResultFactory::TYPE_JSON);
        $resultJson->setData($this->validateData());

        return $resultJson;
    }

    /**
     * Validate Data
     *
     * @return DataObject
     */
    private function validateData()
    {
        $error    = false;
        $messages = [];
        $response = new DataObject();
        try {
            $banners = $this->getRequest()->getParam('assigned_banners');
            if (!($banners && !empty($banners))) {
                throw new LocalizedException(__('You should assign least a banner.'));
            }
        } catch (LocalizedException $e) {
            $error      = true;
            $messages[] = $e->getMessage();
        }
        $result = [
            'error' => $error,
            'messages' => $messages
        ];
        $response->setData($result);

        return $response;
    }
}
