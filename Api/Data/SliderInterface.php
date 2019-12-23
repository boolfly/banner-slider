<?php
/************************************************************
 * *
 *  * Copyright © Boolfly. All rights reserved.
 *  * See COPYING.txt for license details.
 *  *
 *  * @author    info@boolfly.com
 * *  @project   Banner Slider
 */
namespace Boolfly\BannerSlider\Api\Data;

/**
 * Interface SliderInterface
 *
 * @package Boolfly\BannerSlider\Api\Data
 */
interface SliderInterface
{

    /**#@+
     * Constants Cache Tag
     */
    const CACHE_TAG = 'boolfly_slider';

    /**#@+
     * Constants defined for keys of data array
     */
    const SLIDER_ID = 'slider_id';

    const TITLE = 'title';

    const STATUS = 'status';

    const DISPLAY_TITLE = 'display_title';

    const MODE = 'mode';

    const CATEGORY_ID = 'category_id';

    const ANIMATION_EFFECT = 'animation_effect';

    const AUTO_PLAY = 'auto_play';

    const SPEED = 'speed';

    const POSITION = 'position';

    /**
     * Get Slider Id
     *
     * @return string|null
     * @since 1.0.0
     */
    public function getId();

    /**
     * Get Title
     *
     * @return string|null
     * @since 1.0.0
     */
    public function getTitle();

    /**
     * Set Title
     *
     * @param string $title
     *
     * @return $this
     * @since 1.0.0
     */
    public function setTitle($title);

    /**
     * Get Status
     *
     * @return boolean
     * @since 1.0.0
     */
    public function getStatus();

    /**
     * Set Status
     *
     * @param integer|boolean $status
     *
     * @return $this
     * @since 1.0.0
     */
    public function setStatus($status);

    /**
     * Get Display Title
     *
     * @return boolean
     * @since 1.0.0
     */
    public function getDisplayTitle();

    /**
     * Set Display Title
     *
     * @param boolean|integer $displayTitle
     *
     * @return $this
     * @since 1.0.0
     */
    public function setDisplayTitle($displayTitle);

    /**
     * Get Mode
     *
     * @return integer
     * @since 1.0.0
     */
    public function getMode();

    /**
     * Set Mode
     *
     * @param string|integer $mode
     *
     * @return $this
     * @since 1.0.0
     */
    public function setMode($mode);

    /**
     * Get Category Ids
     *
     * @return array
     * @since 1.0.0
     */
    public function getCategoryIds();

    /**
     * Set Category Ids
     *
     * @param array $categoryIds
     *
     * @return $this
     * @since 1.0.0
     */
    public function setCategoryIds($categoryIds);

    /**
     * Get Animation Effect
     *
     * @return string|null
     * @since 1.0.0
     */
    public function getAnimationEffect();

    /**
     * Set Animation Effect
     *
     * @param string $effect
     *
     * @return $this
     * @since 1.0.0
     */
    public function setAnimationEffect($effect);

    /**
     * Check Auto Play
     *
     * @return boolean
     * @since 1.0.0
     */
    public function isAutoPlay();

    /**
     * Set Auto Play
     *
     * @param integer|boolean $autoPlay
     *
     * @return $this
     * @since 1.0.0
     */
    public function setAutoPlay($autoPlay);

    /**
     * Get Speed
     *
     * @return integer|null
     * @since 1.0.0
     */
    public function getSpeed();

    /**
     * Set Speed
     *
     * @param string $speed
     *
     * @return $this
     * @since 1.0.0
     */
    public function setSpeed($speed);

    /**
     * Get Position
     *
     * @return string|null
     * @since 1.0.0
     */
    public function getPosition();

    /**
     * Set Position
     *
     * @param string $position
     *
     * @return $this
     * @since 1.0.0
     */
    public function setPosition($position);
}
