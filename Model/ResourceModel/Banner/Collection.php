<?php
/************************************************************
 * *
 *  * Copyright © Boolfly. All rights reserved.
 *  * See COPYING.txt for license details.
 *  *
 *  * @author    info@boolfly.com
 * *  @project   Banner Slider
 */
namespace Boolfly\BannerSlider\Model\ResourceModel\Banner;

use Boolfly\BannerSlider\Model\ResourceModel\Banner as BannerResourceModel;
use Boolfly\BannerSlider\Model\Banner;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Boolfly\BannerSlider\Model\Source\Status;
use Boolfly\BannerSlider\Setup\InstallSchema;

/**
 * Class Collection
 *
 * @package Boolfly\BannerSlider\Model\ResourceModel\Banner
 */
class Collection extends AbstractCollection
{
    /**
     * Primary column
     *
     * @var string
     */
    protected $_idFieldName = 'banner_id';

    /**
     * Initialize resource collection
     *
     * @return void
     */
    public function _construct()
    {
        $this->_init(Banner::class, BannerResourceModel::class);
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
     * @param $sliderId
     * @return $this
     */
    public function addSliderToFilter($sliderId)
    {
        if ($sliderId && is_numeric($sliderId)) {
            $conditions = $this->getConnection()->quoteInto(
                'main_table.banner_id = banner_slider.banner_id AND banner_slider.slider_id = ?',
                $sliderId
            );
            $this->getSelect()->joinInner(
                ['banner_slider' => $this->getTable(InstallSchema::BANNER_SLIDER_TABLE_NAME)],
                $conditions
            )->order('banner_slider.position ASC');
        }

        return $this;
    }
}
