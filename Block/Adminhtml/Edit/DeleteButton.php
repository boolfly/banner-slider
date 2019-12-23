<?php
/************************************************************
 * *
 *  * Copyright © Boolfly. All rights reserved.
 *  * See COPYING.txt for license details.
 *  *
 *  * @author    info@boolfly.com
 * *  @project   Banner Slider
 */
namespace Boolfly\BannerSlider\Block\Adminhtml\Edit;

use Magento\Backend\Block\Template;
use Magento\Framework\Registry;
use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

/**
 * Class DeleteButton
 *
 * @package Boolfly\BannerSlider\Block\Adminhtml\Edit
 */
class DeleteButton extends Template implements ButtonProviderInterface
{
    /**
     * Delete button
     *
     * @return array
     */
    public function getButtonData()
    {
        if ($id = $this->getRequest()->getParam('id', false)) {
            return [
                'id' => 'delete',
                'label' => __('Delete'),
                'on_click' => "deleteConfirm('" .__('Are you sure you want to delete this?') ."', '"
                    . $this->getDeleteUrl(['id' => $id]) . "', {data: {}})",
                'class' => 'delete',
                'sort_order' => 30
            ];
        }

        return [];
    }

    /**
     * @param array $args
     * @return string
     */
    public function getDeleteUrl(array $args = [])
    {
        $params = array_merge($this->getDefaultUrlParams(), $args);
        return $this->getUrl('*/*/delete', $params);
    }

    /**
     * @return array
     */
    protected function getDefaultUrlParams()
    {
        return ['_current' => true, '_query' => ['isAjax' => null]];
    }
}
