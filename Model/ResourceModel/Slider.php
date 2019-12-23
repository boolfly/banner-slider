<?php
 /************************************************************
  * *
  *  * Copyright © Boolfly. All rights reserved.
  *  * See COPYING.txt for license details.
  *  *
  *  * @author    info@boolfly.com
  * *  @project   Banner Slider
  */
namespace Boolfly\BannerSlider\Model\ResourceModel;

use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Boolfly\BannerSlider\Setup\InstallSchema;
use Magento\Framework\Model\ResourceModel\Db\Context;
use Magento\Framework\Stdlib\DateTime\DateTime;

/**
 * Class Slider
 *
 * @package Boolfly\BannerSlider\Model\ResourceModel
 */
class Slider extends AbstractDb
{

    /**
     * @var DateTime
     */
    private $dateTime;

    /**
     * Slider constructor.
     *
     * @param Context  $context
     * @param DateTime $dateTime
     * @param null     $connectionName
     */
    public function __construct(
        Context $context,
        DateTime $dateTime,
        $connectionName = null
    ) {
        parent::__construct($context, $connectionName);
        $this->dateTime = $dateTime;
    }

    /**
     * Initialize resource
     *
     * @return void
     */
    public function _construct()
    {
        $this->_init(InstallSchema::SLIDER_TABLE_NAME, 'slider_id');
    }

    /**
     * Before save
     *
     * @param AbstractModel $object
     * @return mixed
     */
    protected function _beforeSave(AbstractModel $object)
    {
        $gmtDate = $this->dateTime->gmtDate();
        if ($object->isObjectNew()) {
            $object->setData('created_at', $gmtDate);
        }
        $object->setData('updated_at', $gmtDate);

        return parent::_beforeSave($object);
    }

    /**
     * Banner Slider Table
     *
     * @return string
     */
    public function getBannerSliderTable()
    {
        return $this->getTable(InstallSchema::BANNER_SLIDER_TABLE_NAME);
    }

    /**
     * Get Slider CMS Page Table
     *
     * @return string
     */
    public function getSliderCmsPageTable()
    {
        return $this->getTable(InstallSchema::SLIDER_CMS_PAGE_TABLE_NAME);
    }

    /**
     * @param AbstractModel $object
     * @return mixed
     */
    protected function _afterLoad(AbstractModel $object)
    {
        $this->getLinkData($object);
        return parent::_afterLoad($object);
    }

    /**
     * Get Link Data
     *
     * @param AbstractModel $object
     */
    private function getLinkData(AbstractModel $object)
    {
        $this->getBannerLink($object);
        $this->getCmsPageLink($object);
    }

    /**
     * Get Banner Link
     *
     * @param AbstractModel $object
     */
    private function getBannerLink(AbstractModel $object)
    {
        $connection = $this->getConnection();
        $select     = $connection->select()
            ->from($this->getBannerSliderTable(), ['banner_id', 'position'])
            ->where('slider_id = ?', $object->getId())
            ->order('position ASC');
        $bannerData = $connection->fetchPairs($select);
        $object->setData('assigned_banners', $bannerData);
    }

    /**
     * Get Cms Page Id
     *
     * @param AbstractModel $object
     */
    private function getCmsPageLink(AbstractModel $object)
    {
        $connection = $this->getConnection();
        $select     = $connection->select()
            ->from($this->getSliderCmsPageTable(), ['page_id'])
            ->where('slider_id = ?', $object->getId());
        $data       = $connection->fetchCol($select);
        $object->setData('cms_pages', $data);
    }

    /**
     * Process data to some link tables
     *
     * @param AbstractModel $object
     * @return mixed
     */
    protected function _afterSave(AbstractModel $object)
    {
        $this->processLinkTable($object);
        return parent::_afterSave($object);
    }

    /**
     * Process data to link table
     *
     * @param AbstractModel $object
     * @return $this
     */
    private function processLinkTable(AbstractModel $object)
    {
        $this->processBannerSliderTable($object);
        $this->processSliderCmsPageTable($object);

        return $this;
    }

    /**
     * Save data to boolfly_banner_slider table
     *
     * @param AbstractModel $object
     */
    private function processBannerSliderTable(AbstractModel $object)
    {
        $assignedBanners = $object->getDataByPath('assigned_banners');
        if ($object->getId() && is_array($assignedBanners) && !empty($assignedBanners)) {
            $bannerSliderData  = [];
            $bannerSliderTable = $this->getBannerSliderTable();
            $select            = $this->getConnection()
                ->select()
                ->from($bannerSliderTable, ['banner_id'])
                ->where('slider_id = ?', $object->getId());

            /**
             * Remove Banner not used
             */
            $oldData = $this->getConnection()->fetchCol($select);
            if (!empty($oldData) && !empty($assignedBanners)) {
                $bannerRemoved = array_diff($oldData, array_column($assignedBanners, 'banner_id'));
                if (!empty($bannerRemoved)) {
                    $this->getConnection()->delete(
                        $bannerSliderTable,
                        [
                            'banner_id IN(?)' => $bannerRemoved,
                            'slider_id = ?' => $object->getId()
                        ]
                    );
                }
            }
            foreach ($assignedBanners as $assignedBanner) {
                $bannerSliderData[] = [
                    'banner_id' => $assignedBanner['banner_id'],
                    'slider_id' => $object->getId(),
                    'position' => $assignedBanner['position']
                ];
            }
            if (!empty($bannerSliderData)) {
                $this->getConnection()->insertOnDuplicate(
                    $bannerSliderTable,
                    $bannerSliderData
                );
            }
        };
    }

    /**
     * Save data to boolfly_slider_cms_page
     *
     * @param AbstractModel $model
     */
    private function processSliderCmsPageTable(AbstractModel $model)
    {
        $cmsPageIds         = $model->getData('cms_pages');
        $sliderCmsPageTable = $this->getSliderCmsPageTable();
        if ($model->getId() && is_array($cmsPageIds) && !empty($cmsPageIds)) {
            $cmsPageIds        = array_column($cmsPageIds, 'page_id');
            $sliderCmsPageData = [];
            $select            = $this->getConnection()
                ->select()
                ->from($sliderCmsPageTable, ['page_id'])
                ->where('slider_id = ?', $model->getId());

            /**
             * Remove CMS Page not used
             */
            $oldData = $this->getConnection()->fetchCol($select);
            if (!empty($oldData) && !empty($cmsPageIds)) {
                $pageRemoved = array_diff($oldData, $cmsPageIds);
                if (!empty($pageRemoved)) {
                    $this->getConnection()->delete(
                        $sliderCmsPageTable,
                        [
                            'page_id IN(?)' => $pageRemoved,
                            'slider_id = ?' => $model->getId()
                        ]
                    );
                }
            }
            foreach ($cmsPageIds as $pageId) {
                $sliderCmsPageData[] = [
                    'page_id' => $pageId,
                    'slider_id' => $model->getId()
                ];
            }
            if (!empty($sliderCmsPageData)) {
                $this->getConnection()->insertOnDuplicate(
                    $sliderCmsPageTable,
                    $sliderCmsPageData
                );
            }
        } else {
            $this->getConnection()->delete(
                $sliderCmsPageTable,
                ['slider_id = ?' => $model->getId()]
            );
        }
    }
}
