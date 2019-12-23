<?php
/************************************************************
 * *
 *  * Copyright © Boolfly. All rights reserved.
 *  * See COPYING.txt for license details.
 *  *
 *  * @author    info@boolfly.com
 * *  @project   Banner Slider
 */
namespace Boolfly\BannerSlider\Model\Source;

use Magento\Eav\Model\Entity\Attribute\Source\AbstractSource;
use Magento\Eav\Model\Entity\Attribute\Source\SourceInterface;
use Magento\Framework\Data\OptionSourceInterface;

/**
 * Class AlignText
 *
 * @package Boolfly\BannerSlider\Model\Source
 */
class AlignText extends AbstractSource implements SourceInterface, OptionSourceInterface
{
    /**#@+
     * Align Text Values
     */
    const CENTER_ALIGN_TEXT = 'center';

    const LEFT_ALIGN_TEXT = 'left';

    const RIGHT_ALIGN_TEXT = 'right';

    /**
     * Retrieve option array
     *
     * @return string[]
     */
    public static function getOptionArray()
    {
        return [
            self::CENTER_ALIGN_TEXT => __('Center'),
            self::LEFT_ALIGN_TEXT => __('Left'),
            self::RIGHT_ALIGN_TEXT => __('Right')
        ];
    }

    /**
     * Retrieve option array with empty value
     *
     * @return string[]
     */
    public function getAllOptions()
    {
        $result = [];

        foreach (self::getOptionArray() as $index => $value) {
            $result[] = ['value' => $index, 'label' => $value];
        }

        return $result;
    }

    /**
     * Retrieve option text by option value
     *
     * @param string $optionId
     * @return string
     */
    public function getOptionText($optionId)
    {
        $options = self::getOptionArray();

        return isset($options[$optionId]) ? $options[$optionId] : null;
    }
}
