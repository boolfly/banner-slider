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

use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Filesystem;
use Magento\Framework\Filesystem\Directory\WriteInterface;
use Magento\Framework\UrlInterface;
use Magento\Framework\View\Asset\Repository;
use Magento\MediaStorage\Helper\File\Storage\Database;
use Magento\MediaStorage\Model\File\UploaderFactory;
use Magento\Store\Model\StoreManagerInterface;
use Psr\Log\LoggerInterface;

/**
 * Class ImageUploader
 *
 * @package Boolfly\BannerSlider\Model
 */
class ImageUploader
{

    /**@#%
     * Cache Image Directory
     *
     * @const
     */
    const BANNER_CACHE_IMAGE_DIRECTORY = 'bannerslider/banner/cache/';

    /**
     * Core file storage database
     *
     * @var Database
     */
    protected $coreFileStorageDatabase;

    /**
     * Media directory object (writable).
     *
     * @var WriteInterface
     */
    protected $mediaDirectory;

    /**
     * Uploader factory
     *
     * @var UploaderFactory
     */
    private $uploaderFactory;

    /**
     * Store manager
     *
     * @var StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * Base tmp path
     *
     * @var string
     */
    protected $baseTmpPath;

    /**
     * Base path
     *
     * @var string
     */
    protected $basePath;

    /**
     * Allowed extensions
     *
     * @var string
     */
    protected $allowedExtensions;

    /**
     * @var Repository
     */
    private $assetRepository;

    /**
     * ImageUploader constructor.
     *
     * @param Database              $coreFileStorageDatabase
     * @param Filesystem            $filesystem
     * @param UploaderFactory       $uploaderFactory
     * @param StoreManagerInterface $storeManager
     * @param LoggerInterface       $logger
     * @param Repository            $assetRepository
     * @param string                $baseTmpPath
     * @param string                $basePath
     * @param array                 $allowedExtensions
     * @throws \Magento\Framework\Exception\FileSystemException
     */
    public function __construct(
        Database $coreFileStorageDatabase,
        Filesystem $filesystem,
        UploaderFactory $uploaderFactory,
        StoreManagerInterface $storeManager,
        LoggerInterface $logger,
        Repository $assetRepository,
        $baseTmpPath = 'tmp/boolfly/banner',
        $basePath = 'boolfly/banner',
        $allowedExtensions = [
            'jpg',
            'jpeg',
            'png',
            'gif'
        ]
    ) {
        $this->coreFileStorageDatabase = $coreFileStorageDatabase;
        $this->mediaDirectory          = $filesystem->getDirectoryWrite(DirectoryList::MEDIA);
        $this->uploaderFactory         = $uploaderFactory;
        $this->storeManager            = $storeManager;
        $this->logger                  = $logger;
        $this->baseTmpPath             = $baseTmpPath;
        $this->basePath                = $basePath;
        $this->allowedExtensions       = $allowedExtensions;
        $this->assetRepository         = $assetRepository;
    }

    /**
     * Set base tmp path
     *
     * @param string $baseTmpPath
     *
     * @return void
     */
    public function setBaseTmpPath($baseTmpPath)
    {
        $this->baseTmpPath = $baseTmpPath;
    }

    /**
     * Set base path
     *
     * @param string $basePath
     *
     * @return void
     */
    public function setBasePath($basePath)
    {
        $this->basePath = $basePath;
    }

    /**
     * Set allowed extensions
     *
     * @param string[] $allowedExtensions
     *
     * @return void
     */
    public function setAllowedExtensions($allowedExtensions)
    {
        $this->allowedExtensions = $allowedExtensions;
    }

    /**
     * Retrieve base tmp path
     *
     * @return string
     */
    public function getBaseTmpPath()
    {
        return $this->baseTmpPath;
    }

    /**
     * Retrieve base path
     *
     * @return string
     */
    public function getBasePath()
    {
        return $this->basePath;
    }

    /**
     * Retrieve base path
     *
     * @return string[]
     */
    public function getAllowedExtensions()
    {
        return $this->allowedExtensions;
    }

    /**
     * Retrieve path
     *
     * @param string $path
     * @param string $imageName
     *
     * @return string
     */
    public function getFilePath($path, $imageName)
    {
        return rtrim($path, '/') . '/' . ltrim($imageName, '/');
    }

    /**
     * Checking file for moving and move it
     *
     * @param string $imageName
     *
     * @return string
     *
     * @throws LocalizedException
     */
    public function moveFileFromTmp($imageName)
    {
        $baseTmpPath = $this->getBaseTmpPath();
        $basePath    = $this->getBasePath();

        $baseImagePath    = $this->getFilePath($basePath, $imageName);
        $baseTmpImagePath = $this->getFilePath($baseTmpPath, $imageName);

        try {
            $this->coreFileStorageDatabase->copyFile(
                $baseTmpImagePath,
                $baseImagePath
            );
            $this->mediaDirectory->renameFile(
                $baseTmpImagePath,
                $baseImagePath
            );
        } catch (\Exception $e) {
            throw new LocalizedException(
                __('Something went wrong while saving the file(s).')
            );
        }

        return $imageName;
    }

    /**
     * @param $image
     * @return boolean
     * @throws \Magento\Framework\Exception\FileSystemException
     */
    public function deleteImageFile($image)
    {
        return $this->mediaDirectory->delete($this->getFilePath($this->getBasePath(), $image));
    }

    /**
     * Get Image Url
     *
     * @param $name
     * @return string|boolean
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getImageUrl($name)
    {
        if ($this->isFile($name)) {
            return $this->storeManager
                    ->getStore()
                    ->getBaseUrl(
                        UrlInterface::URL_TYPE_MEDIA
                    ) . $this->getFilePath($this->getBasePath(), $name);
        }
        return false;
    }

    /**
     * @param $name
     * @return mixed
     */
    public function getSize($name)
    {
        $stat = $this->mediaDirectory->stat($this->getFilePath($this->getBasePath(), $name));

        return $stat[7];
    }

    /**
     * @param $name
     * @return boolean
     */
    public function isFile($name)
    {
        return $this->mediaDirectory->isFile($this->getFilePath($this->getBasePath(), $name));
    }

    /**
     * Checking file for save and save it to tmp dir
     *
     * @param $fileId
     * @return array
     * @throws \Exception
     * @throws LocalizedException
     */
    public function saveFileToTmpDir($fileId)
    {
        $baseTmpPath = $this->getBaseTmpPath();

        $uploader = $this->uploaderFactory->create(['fileId' => $fileId]);
        $uploader->setAllowedExtensions($this->getAllowedExtensions());
        $uploader->setAllowRenameFiles(true);

        $result = $uploader->save($this->mediaDirectory->getAbsolutePath($baseTmpPath));

        if (!$result) {
            throw new LocalizedException(
                __('File can not be saved to the destination folder.')
            );
        }

        /**
         * Workaround for prototype 1.7 methods "isJSON", "evalJSON" on Windows OS
         */
        $result['tmp_name'] = str_replace('\\', '/', $result['tmp_name']);
        $result['path']     = str_replace('\\', '/', $result['path']);
        $result['url']      = $this->storeManager
                ->getStore()
                ->getBaseUrl(
                    UrlInterface::URL_TYPE_MEDIA
                ) . $this->getFilePath($baseTmpPath, $result['file']);
        $result['name']     = $result['file'];

        if (isset($result['file'])) {
            try {
                $relativePath = rtrim($baseTmpPath, '/') . '/' . ltrim($result['file'], '/');
                $this->coreFileStorageDatabase->saveFile($relativePath);
            } catch (\Exception $e) {
                $this->logger->critical($e);
                throw new LocalizedException(
                    __('Something went wrong while saving the file(s).')
                );
            }
        }

        return $result;
    }
}
