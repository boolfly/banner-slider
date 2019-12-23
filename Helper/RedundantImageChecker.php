<?php
/************************************************************
 * *
 *  * Copyright © Boolfly. All rights reserved.
 *  * See COPYING.txt for license details.
 *  *
 *  * @author    info@boolfly.com
 * *  @project   Banner Slider
 */
namespace Boolfly\BannerSlider\Helper;

use Magento\Framework\App\ResourceConnection;
use Magento\Framework\DB\Adapter\AdapterInterface;
use Boolfly\BannerSlider\Model\ImageField;
use Boolfly\BannerSlider\Model\ImageUploader;

/**
 * Class RedundantImageChecker
 *
 * @package Boolfly\BannerSlider\Helper
 */
class RedundantImageChecker
{
    /**
     * @var ResourceConnection
     */
    private $resourceConnection;

    /**
     * @var AdapterInterface
     */
    private $connection;

    /**
     * @var ImageUploader
     */
    private $imageUploader;

    /**
     * RedundantImageChecker constructor.
     *
     * @param ResourceConnection $resourceConnection
     * @param ImageUploader      $imageUploader
     */
    public function __construct(
        ResourceConnection $resourceConnection,
        ImageUploader $imageUploader
    ) {
        $this->resourceConnection = $resourceConnection;
        $this->connection         = $resourceConnection->getConnection();
        $this->imageUploader      = $imageUploader;
    }

    /**
     * Delete the image un-used
     *
     * @param $image
     * @throws \Magento\Framework\Exception\FileSystemException
     */
    public function process($image)
    {
        if ($image && is_string($image) && $this->isRedundant($image)) {
            $this->imageUploader->deleteImageFile($image);
        }
    }

    /**
     * Checking Image Unused
     *
     * @param string $image
     * @return boolean
     */
    private function isRedundant($image)
    {
        $connection     = $this->connection;
        $conditionArray = [];
        foreach (ImageField::getField() as $field) {
            $conditionArray[] = $connection->quoteInto($field . ' = ?', $image);
        }
        $conditions = join(' OR ', $conditionArray);
        $select     = $connection->select()
            ->from($connection->getTableName('boolfly_banner'), 'banner_id')
            ->where($conditions)
            ->limit(1);

        return ((int)$connection->fetchOne($select)) < 1;
    }
}
