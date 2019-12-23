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

use Boolfly\BannerSlider\Model\ImageUploader;
use Magento\Catalog\Api\CategoryRepositoryInterface;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\Filesystem;
use Magento\Framework\Image\AdapterFactory;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\UrlInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Eav\Model\Config as EavConfig;

/**
 * Class Data
 *
 * @package Boolfly\BannerSlider\Helper
 */
class Data extends AbstractHelper
{

    /**
     * @const
     */
    const THUMBNAIL_WIDTH = 75;

    const THUMBNAIL_HEIGHT = 75;

    /**
     * @var Filesystem
     */
    private $filesystem;

    /**
     * @var Filesystem\Directory\WriteInterface
     */
    private $directory;

    /**
     * @var AdapterFactory
     */
    private $imageFactory;

    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * @var CategoryRepositoryInterface
     */
    private $categoryRepository;

    /**
     * @var EavConfig
     */
    private $eavConfig;

    /**
     * @var ImageUploader
     */
    private $imageUploader;


    /**
     * Data constructor.
     *
     * @param Context                     $context
     * @param StoreManagerInterface       $storeManager
     * @param AdapterFactory              $imageFactory
     * @param CategoryRepositoryInterface $categoryRepository
     * @param EavConfig                   $eavConfig
     * @param ImageUploader               $imageUploader
     * @param Filesystem                  $filesystem
     * @throws \Magento\Framework\Exception\FileSystemException
     */
    public function __construct(
        Context $context,
        StoreManagerInterface $storeManager,
        AdapterFactory $imageFactory,
        CategoryRepositoryInterface $categoryRepository,
        EavConfig $eavConfig,
        ImageUploader $imageUploader,
        Filesystem $filesystem
    ) {
        parent::__construct($context);
        $this->filesystem         = $filesystem;
        $this->directory          = $filesystem->getDirectoryWrite(DirectoryList::MEDIA);
        $this->imageFactory       = $imageFactory;
        $this->storeManager       = $storeManager;
        $this->categoryRepository = $categoryRepository;
        $this->eavConfig          = $eavConfig;
        $this->imageUploader      = $imageUploader;
    }

    /**
     * Get Resize Image
     *
     * @param $imageName
     * @param integer   $width
     * @param integer   $height
     * @return boolean|string
     * @throws \Exception
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getResizeImage($imageName, $width = self::THUMBNAIL_WIDTH, $height = self::THUMBNAIL_HEIGHT)
    {
        $directoryRead = $this->filesystem->getDirectoryRead(DirectoryList::MEDIA);
        $imageUploader = $this->imageUploader;
        if (!$imageUploader->isFile($imageName)) {
            return false;
        }
        $cacheDirectory = $imageUploader->getBasePath(). '/cache/' . $width . 'x' . $height. '/';
        $absolutePath   = $directoryRead->getAbsolutePath($imageUploader->getBasePath()) . '/' . $imageName;
        $imageResized   = $directoryRead->getAbsolutePath($cacheDirectory) . $imageName;
        if (!$this->directory->isFile($imageResized)) {
            /** @var \Magento\Framework\Image\Adapter\AbstractAdapter $imageResize */
            $imageResize = $this->imageFactory->create();
            $imageResize->open($absolutePath);
            $imageResize->constrainOnly(false);
            $imageResize->keepTransparency(true);
            $imageResize->keepFrame(false);
            $imageResize->keepAspectRatio(true);
            $imageResize->resize($width, $height);
            $imageResize->save($imageResized);
        }
        $resizeURL = $this->getBaseMediaUrl() . ltrim($cacheDirectory . $imageName, '/');

        return $resizeURL;
    }

    /**
     * Get Image Name
     *
     * @param $imageName
     * @return boolean|string
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getImageUrl($imageName)
    {
        return $this->imageUploader->getImageUrl($imageName);
    }

    /**
     * @return mixed
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getBaseMediaUrl()
    {
        return $this->storeManager->getStore()->getBaseUrl(UrlInterface::URL_TYPE_MEDIA);
    }
}
