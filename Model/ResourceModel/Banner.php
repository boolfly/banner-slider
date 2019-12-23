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
 * Class Banner
 *
 * @package Boolfly\BannerSlider\Model\ResourceModel
 */
class Banner extends AbstractDb
{

    /**
     * @var DateTime
     */
    private $dateTime;

    /**
     * Banner constructor.
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
        $this->_init(InstallSchema::BANNER_TABLE_NAME, 'banner_id');
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
        if (!$object->getData('enable_button')) {
            $object->setData('button_text', null);
            $object->setData('button_url', null);
        }

        return parent::_beforeSave($object);
    }
}
