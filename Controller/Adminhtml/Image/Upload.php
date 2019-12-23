<?php
/************************************************************
 * *
 *  * Copyright © 2019 Forix. All rights reserved.
 *  * See COPYING.txt for license details.
 *  *
 *  * @author    thao@forixwebdesign.com
 * *  @project   Weiman Product
 */
namespace Boolfly\BannerSlider\Controller\Adminhtml\Image;

use Boolfly\BannerSlider\Model\ImageUploader;
use Magento\Framework\Controller\ResultFactory;
use Magento\Backend\App\Action as BackendAction;
use Magento\Backend\App\Action\Context;

/**
 * Class Upload
 *
 * @package Boolfly\BannerSlider\Controller\Adminhtml\Image
 */
class Upload extends BackendAction
{

    /**
     *  Check admin permissions for this controller
     *
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = 'Boolfly_BannerSlider::banner';

    /**
     * @var ImageUploader
     */
    protected $imageUploader;

    /**
     * @var string
     */
    protected $fileId;

    /**
     * Upload constructor.
     *
     * @param Context       $context
     * @param ImageUploader $documentUploader
     */
    public function __construct(
        Context $context,
        ImageUploader $documentUploader
    ) {
        parent::__construct($context);
        $this->imageUploader = $documentUploader;
    }

    /**
     * Upload file controller action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        try {
            $fileId           = $this->getRequest()->getParam('param_name');
            $result           = $this->imageUploader->saveFileToTmpDir($fileId);
            $result['cookie'] = [
                'name'     => $this->_getSession()->getName(),
                'value'    => $this->_getSession()->getSessionId(),
                'lifetime' => $this->_getSession()->getCookieLifetime(),
                'path'     => $this->_getSession()->getCookiePath(),
                'domain'   => $this->_getSession()->getCookieDomain(),
            ];
        } catch (\Exception $e) {
            $result = [
                'error'     => $e->getMessage(),
                'errorcode' => $e->getCode(),
            ];
        }

        /** @var \Magento\Framework\Controller\Result\Json $jsonResult */
        $jsonResult = $this->resultFactory->create(ResultFactory::TYPE_JSON);

        return $jsonResult->setData($result);
    }
}
