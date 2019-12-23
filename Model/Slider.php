<?php
/************************************************************
 * *
 *  * Copyright © Boolfly. All rights reserved.
 *  * See COPYING.txt for license details.
 *  *
 *  * @author    info@boolfly.com
 * *  @project   Banner Slider
 */
namespace Boolfly\BannerSlider\Model;

use Boolfly\BannerSlider\Api\Data\SliderInterface;
use Boolfly\BannerSlider\Model\ResourceModel\Slider as SliderResourceModel;
use Magento\Framework\Model\AbstractModel;
use Magento\Framework\DataObject\IdentityInterface;

/**
 * Class Slider
 *
 * @package Boolfly\BannerSlider\Model
 */
class Slider extends AbstractModel implements SliderInterface, IdentityInterface
{

    /**
     * Initialize resource model
     * @return void
     */
    public function _construct()
    {
        $this->_init(SliderResourceModel::class);
    }

    /**
     * Get Title
     *
     * @return string|null
     * @since 1.0.0
     */
    public function getTitle()
    {
        return $this->_getData(self::TITLE);
    }

    /**
     * Set Title
     *
     * @param string $title
     *
     * @return $this
     * @since 1.0.0
     */
    public function setTitle($title)
    {
        return $this->setData(self::TITLE, $title);
    }

    /**
     * Get Status
     *
     * @return boolean
     * @since 1.0.0
     */
    public function getStatus()
    {
        return $this->_getData(self::STATUS);
    }

    /**
     * Set Status
     *
     * @param integer|boolean $status
     *
     * @return $this
     * @since 1.0.0
     */
    public function setStatus($status)
    {
        return $this->setData(self::STATUS, (bool)$status);
    }

    /**
     * Get Display Title
     *
     * @return boolean
     * @since 1.0.0
     */
    public function getDisplayTitle()
    {
        return (boolean)$this->_getData(self::DISPLAY_TITLE);
    }

    /**
     * Set Display Title
     *
     * @param boolean|integer $displayTitle
     *
     * @return $this
     * @since 1.0.0
     */
    public function setDisplayTitle($displayTitle)
    {
        return $this->setData(self::DISPLAY_TITLE, (boolean) $displayTitle);
    }

    /**
     * Get Mode
     *
     * @return integer
     * @since 1.0.0
     */
    public function getMode()
    {
        return (int)$this->_getData(self::MODE);
    }

    /**
     * Set Mode
     *
     * @param string|integer $mode
     *
     * @return $this
     * @since 1.0.0
     */
    public function setMode($mode)
    {
        return $this->setData(self::MODE, (int)$mode);
    }

    /**
     * Get Animation Effect
     *
     * @return string|null
     * @since 1.0.0
     */
    public function getAnimationEffect()
    {
        return $this->_getData(self::ANIMATION_EFFECT);
    }

    /**
     * Set Animation Effect
     *
     * @param string $effect
     *
     * @return $this
     * @since 1.0.0
     */
    public function setAnimationEffect($effect)
    {
        return $this->setData(self::ANIMATION_EFFECT, $effect);
    }

    /**
     * Check Auto Play
     *
     * @return boolean
     * @since 1.0.0
     */
    public function isAutoPlay()
    {
        return (boolean)$this->_getData(self::AUTO_PLAY);
    }

    /**
     * Set Auto Play
     *
     * @param integer|boolean $autoPlay
     *
     * @return $this
     * @since 1.0.0
     */
    public function setAutoPlay($autoPlay)
    {
        return $this->setData(self::AUTO_PLAY, (int)$autoPlay);
    }

    /**
     * Get Speed
     *
     * @return integer|null
     * @since 1.0.0
     */
    public function getSpeed()
    {
        return $this->_getData(self::SPEED);
    }

    /**
     * Set Speed
     *
     * @param string $speed
     *
     * @return $this
     * @since 1.0.0
     */
    public function setSpeed($speed)
    {
        return $this->setData(self::SPEED, (int)$speed);
    }

    /**
     * Get Position
     *
     * @return string|null
     * @since 1.0.0
     */
    public function getPosition()
    {
        return $this->_getData(self::POSITION);
    }

    /**
     * Set Position
     *
     * @param string $position
     *
     * @return $this
     * @since 1.0.0
     */
    public function setPosition($position)
    {
        return $this->setData(self::POSITION, $position);
    }

    /**
     * Get Banner Id
     *
     * @return array
     */
    public function getBannerIds()
    {
        $assignedBanner = $this->getData('assigned_banners');
        if (is_array($assignedBanner)) {
            return array_keys($assignedBanner);
        }
        return [];
    }

    /**
     * Return unique ID(s) for each object in system
     *
     * @return string[]
     */
    public function getIdentities()
    {
        $identities = [
            self::CACHE_TAG . '_' . $this->getId(),
        ];

        if (!$this->getId() || $this->isDeleted()) {
            $identities[] = self::CACHE_TAG;
        }

        return array_unique($identities);
    }

    /**
     * Get Category Id
     *
     * @return array
     * @since 1.0.0
     */
    public function getCategoryIds()
    {
        return $this->_getData(self::CATEGORY_ID);
    }

    /**
     * After Load Slider
     *
     * @return mixed
     */
    public function _afterLoad()
    {
        $categoryIds = $this->_getData(self::CATEGORY_ID);
        if ($categoryIds && is_string($categoryIds)) {
            $this->setData(self::CATEGORY_ID, explode(',', $categoryIds));
        }
        return parent::_afterLoad();
    }

    /**
     * @return mixed
     */
    public function beforeSave()
    {
        $this->setCategoryIds($this->_getData(self::CATEGORY_ID));
        return parent::beforeSave();
    }

    /**
     * Set Category Id
     *
     * @param array $categoryIds
     *
     * @return $this
     * @since 1.0.0
     */
    public function setCategoryIds($categoryIds)
    {
        if (is_array($categoryIds)) {
            return $this->setData(self::CATEGORY_ID, implode(',', $categoryIds));
        } else {
            return $this->setData(self::CATEGORY_ID, null);
        }
    }
}
