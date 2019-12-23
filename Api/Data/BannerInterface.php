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
 * Interface BannerInterface
 *
 * @package Boolfly\BannerSlider\Api\Data
 */
interface BannerInterface
{

    /**#@+
     * Constants Cache Tag
     */
    const CACHE_TAG = 'boolfly_banner';

    /**#@+
     * Constants defined for keys of data array
     */
    const BANNER_ID = 'banner_id';

    const TITLE = 'title';

    const STATUS = 'status';

    const BANNER_URL = 'banner_url';

    const DESCRIPTION = 'description';

    const BUTTON_TEXT = 'button_text';

    const BUTTON_URL = 'button_url';

    const IMAGE_ALT = 'image_alt';

    const IMAGE_DESKTOP = 'image_desktop';

    const IMAGE_TABLET = 'image_tablet';

    const IMAGE_MOBILE = 'image_mobile';

    const TARGET_LINK = 'target_link';

    const ALIGN_TEXT = 'align_text';

    /**
     * Get Banner Id
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
     * Get Banner Url
     *
     * @return string|null
     * @since 1.0.0
     */
    public function getBannerUrl();

    /**
     * Set Banner Url
     *
     * @param string $url
     *
     * @return $this
     * @since 1.0.0
     */
    public function setBannerUrl($url);

    /**
     * Get Banner Description
     *
     * @return string|null
     * @since 1.0.0
     */
    public function getDescription();

    /**
     * Set Description
     *
     * @param string $description
     *
     * @return $this
     * @since 1.0.0
     */
    public function setDescription($description);

    /**
     * Get Button Text
     *
     * @return string|null
     * @since 1.0.0
     */
    public function getButtonText();

    /**
     * Set Button Text
     *
     * @param string $text
     *
     * @return $this
     * @since 1.0.0
     */
    public function setButtonText($text);

    /**
     * Get Button Url
     *
     * @return string|null
     * @since 1.0.0
     */
    public function getButtonUrl();

    /**
     * Set Button Url
     *
     * @param string $url
     *
     * @return $this
     * @since 1.0.0
     */
    public function setButtonUrl($url);

    /**
     * Get Image Desktop Url
     *
     * @return string|boolean
     * @since 1.0.0
     */
    public function getImageDesktopUrl();

    /**
     * Set Image Desktop
     *
     * @param string $imageName
     *
     * @return $this
     * @since 1.0.0
     */
    public function setImageDesktop($imageName);

    /**
     * Get Image Tablet Url
     *
     * @return string|boolean
     * @since 1.0.0
     */
    public function getImageTabletUrl();

    /**
     * Set Image Tablet
     *
     * @param string $imageName
     *
     * @return $this
     * @since 1.0.0
     */
    public function setImageTablet($imageName);

    /**
     * Get Image Mobile Url
     *
     * @return string|boolean
     * @since 1.0.0
     */
    public function getImageMobileUrl();

    /**
     * Set Image Mobile
     *
     * @param string $imageName
     *
     * @return $this
     * @since 1.0.0
     */
    public function setImageMobile($imageName);

    /**
     * Get Target Link
     *
     * @return string|null
     * @since 1.0.0
     */
    public function getTargetLink();

    /**
     * Set Target Link
     *
     * @param string $targetLink
     *
     * @return $this
     * @since 1.0.0
     */
    public function setTargetLink($targetLink);

    /**
     * Get Align Text
     *
     * @return string|null
     * @since 1.0.0
     */
    public function getAlignText();

    /**
     * Set Align Text
     *
     * @param string $alignText
     *
     * @return $this
     * @since 1.0.0
     */
    public function setAlignText($alignText);
}
