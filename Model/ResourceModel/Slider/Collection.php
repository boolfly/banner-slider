<?php
/************************************************************
 * *
 *  * Copyright © Boolfly. All rights reserved.
 *  * See COPYING.txt for license details.
 *  *
 *  * @author    info@boolfly.com
 * *  @project   Banner Slider
 */
namespace Boolfly\BannerSlider\Model\ResourceModel\Slider;

use Boolfly\BannerSlider\Model\ResourceModel\Slider as SliderResourceModel;
use Boolfly\BannerSlider\Model\Slider;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Boolfly\BannerSlider\Model\Source\Status;

/**
 * Class Collection
 *
 * @package Boolfly\BannerSlider\Model\ResourceModel\Slider
 */
class Collection extends AbstractCollection
{

    /**
     * Primary column
     *
     * @var string
     */
    protected $_idFieldName = 'slider_id';

    /**
     * Initialize resource collection
     *
     * @return void
     */
    public function _construct()
    {
        $this->_init(Slider::class, SliderResourceModel::class);
    }

    /**
     * Get Resource
     *
     * @return SliderResourceModel|mixed
     */
    public function getResource()
    {
        return parent::getResource();
    }

    /**
     * Only get enable banner
     *
     * @return Collection
     */
    public function addActiveStatusFilter()
    {
        return $this->addFieldToFilter('status', Status::STATUS_ENABLED);
    }

    /**
     * @param $pageId
     * @return $this
     */
    public function addCmsPageToFilter($pageId)
    {
        if ($pageId && is_numeric($pageId)) {
            $conditions = $this->getConnection()->quoteInto(
                'main_table.slider_id = cms_page.slider_id AND cms_page.page_id = ?',
                $pageId
            );
            $this->getSelect()->joinInner(
                ['cms_page' => $this->getResource()->getSliderCmsPageTable()],
                $conditions,
                'page_id'
            );
        }

        return $this;
    }

    /**
     * Add Category To Filter
     *
     * @param $catId
     * @return $this
     */
    public function addCategoryToFilter($catId)
    {
        if ($catId && is_numeric($catId)) {
            $this->addFieldToFilter('category_id', ['finset' => $catId]);
        }

        return $this;
    }
}
