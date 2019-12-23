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
use Boolfly\BannerSlider\Model\ResourceModel\Slider\CollectionFactory;
use Boolfly\BannerSlider\Api\Data\SliderInterfaceFactory;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Registry;
use Magento\Ui\Component\MassAction\Filter;
use Magento\Framework\Controller\ResultFactory;

/**
 * Class MassStatus
 *
 * @package Boolfly\BannerSlider\Controller\Adminhtml\Slider
 */
class MassStatus extends AbstractSlider
{

    /**
     * @var CollectionFactory
     */
    private $collectionFactory;

    /**
     * @var Filter
     */
    private $filter;

    /**
     * MassStatus constructor.
     *
     * @param Context                $context
     * @param Registry               $coreRegistry
     * @param SliderInterfaceFactory $sliderFactory
     * @param CollectionFactory      $collectionFactory
     * @param Filter                 $filter
     */
    public function __construct(
        Context $context,
        Registry $coreRegistry,
        SliderInterfaceFactory $sliderFactory,
        CollectionFactory $collectionFactory,
        Filter $filter
    ) {
        parent::__construct($context, $coreRegistry, $sliderFactory);
        $this->collectionFactory = $collectionFactory;
        $this->filter            = $filter;
    }

    /**
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\ResultInterface
     * @throws LocalizedException
     */
    public function execute()
    {
        $collections = $this->filter->getCollection($this->collectionFactory->create());
        $status      = $this->getRequest()->getParam('status');
        $totals      = 0;
        try {
            /** @var \Boolfly\BannerSlider\Model\Slider $item */
            foreach ($collections as $item) {
                $item->setStatus($status);
                $item->save();
                $totals++;
            }
            $this->messageManager->addSuccessMessage(__('A total of %1 record(s) have been updated.', $totals));
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage(__('Something went wrong while update the slider(s).'));
        }
        /** @var \Magento\Framework\Controller\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        return $resultRedirect->setPath('*/*/');
    }
}
