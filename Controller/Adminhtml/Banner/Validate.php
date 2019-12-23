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
use Magento\Framework\DataObject;
use Magento\Framework\Controller\ResultFactory;
use Boolfly\BannerSlider\Api\Data\BannerInterface;

/**
 * Class Validate
 *
 * @package Boolfly\BannerSlider\Controller\Adminhtml\Banner
 */
class Validate extends AbstractBanner
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
            $images = $this->getRequest()->getParam(BannerInterface::IMAGE_DESKTOP);
            if (!($images && is_array($images))) {
                throw new LocalizedException(__('You should upload image desktop.'));
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
