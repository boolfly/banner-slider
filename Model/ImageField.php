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

/**
 * Class ImageField
 *
 * @package Boolfly\BannerSlider\Model
 */
class ImageField
{

    /**
     * Return Image Field
     *
     * @return array
     */
    public static function getField()
    {
        return [
            'image_desktop',
            'image_tablet',
            'image_mobile'
        ];
    }
}
