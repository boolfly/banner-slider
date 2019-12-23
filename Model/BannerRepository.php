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

use Boolfly\BannerSlider\Api\BannerRepositoryInterface;
use Boolfly\BannerSlider\Api\Data\BannerInterfaceFactory;
use Boolfly\BannerSlider\Api\Data\BannerInterface;
use Boolfly\BannerSlider\Model\ResourceModel\Banner as ResourceBanner;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\StateException;

/**
 * Class BannerRepository
 *
 * @package Boolfly\BannerSlider\Model
 */
class BannerRepository implements BannerRepositoryInterface
{
    /**
     * @var Banner[]
     */
    protected $instances = [];

        /**
         * @var ResourceBanner
         */
    private $resource;

        /**
         * @var BannerInterfaceFactory
         */
    private $bannerFactory;

    /**
     * BannerRepository constructor.
     *
     * @param ResourceBanner         $resource
     * @param BannerInterfaceFactory $bannerFactory
     */
    public function __construct(
        ResourceBanner $resource,
        BannerInterfaceFactory $bannerFactory
    ) {

        $this->resource      = $resource;
        $this->bannerFactory = $bannerFactory;
    }

    /**
     * Get Banner by Id
     *
     * @param string  $bannerId
     * @param boolean $force
     * @return null|BannerInterface
     * @throws NoSuchEntityException
     */
    public function get($bannerId, $force = false)
    {
        $cacheKey = 'all';
        if (!isset($this->instances[$bannerId][$cacheKey]) || $force) {
            /** @var Banner $banner */
            $banner = $this->bannerFactory->create();
            $banner->load($bannerId);
            if (!$banner->getId()) {
                throw NoSuchEntityException::singleField('id', $bannerId);
            }
            $this->instances[$bannerId][$cacheKey] = $banner;
        }
        return $this->instances[$bannerId][$cacheKey];
    }

    /**
     * Delete Banner
     *
     * @param Banner|BannerInterface $banner
     * @return boolean
     * @throws StateException
     */
    public function delete(BannerInterface $banner)
    {
        try {
            $bannerId = $banner->getId();
            $this->resource->delete($banner);
        } catch (\Exception $e) {
            throw new StateException(
                __(
                    'Cannot delete category with id %1',
                    $banner->getId()
                ),
                $e
            );
        }
        unset($this->instances[$bannerId]);

                return true;
    }

    /**
     * Save Banner
     *
     * @param Banner|BannerInterface $banner
     * @return BannerInterface|null
     * @throws CouldNotSaveException
     * @throws NoSuchEntityException
     */
    public function save(BannerInterface $banner)
    {
        $existingData = $banner->getData();

        if ($banner->getId()) {
            $banner = $this->get($banner->getId());
        }
        $banner->addData($existingData);
        try {
            $this->resource->save($banner);
        } catch (\Exception $e) {
            throw new CouldNotSaveException(
                __(
                    'Could not save banner: %1',
                    $e->getMessage()
                ),
                $e
            );
        }
        unset($this->instances[$banner->getId()]);
        return $this->get($banner->getId());
    }
}
