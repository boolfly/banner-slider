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

use Magento\Catalog\Model\ResourceModel\Category\CollectionFactory;
use Magento\Framework\Data\OptionSourceInterface;
use Magento\Catalog\Model\Category as CategoryModel;

/**
 * Class CategoryTree
 *
 * @package Boolfly\BannerSlider\Model\Source
 */
class CategoryTree implements OptionSourceInterface
{
    /**
     * @var array
     */
    private $options;

    /**
     * @var CollectionFactory
     */
    private $collectionFactory;

    /**
     * CategoryTree constructor.
     *
     * @param CollectionFactory $collectionFactory
     */
    public function __construct(
        CollectionFactory $collectionFactory
    ) {
        $this->collectionFactory = $collectionFactory;
    }

    /**
     * @return array|mixed
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function toOptionArray()
    {
        return $this->getAllOptions();
    }

    /**
     * @return string[]
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getAllOptions()
    {
        if ($this->options === null) {
            $collection = $this->collectionFactory->create();
            $collection->addAttributeToSelect(['name', 'is_active', 'parent_id']);

            $categoryById = [
                CategoryModel::TREE_ROOT_ID => [
                    'value' => CategoryModel::TREE_ROOT_ID,
                    'optgroup' => [],
                ],
            ];

            foreach ($collection as $category) {
                foreach ([$category->getId(), $category->getParentId()] as $categoryId) {
                    if (!isset($categoryById[$categoryId])) {
                        $categoryById[$categoryId] = ['value' => $categoryId];
                    }
                }

                $categoryById[$category->getId()]['is_active']        = $category->getIsActive();
                $categoryById[$category->getId()]['label']            = $category->getName();
                $categoryById[$category->getId()]['__disableTmpl']    = true;
                $categoryById[$category->getParentId()]['optgroup'][] = &$categoryById[$category->getId()];
            }

            $this->options = $categoryById[CategoryModel::TREE_ROOT_ID]['optgroup'];
        }

        return $this->options;
    }
}
