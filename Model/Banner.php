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

use Boolfly\BannerSlider\Api\Data\BannerInterface;
use Boolfly\BannerSlider\Model\ResourceModel\Banner as BannerResourceModel;
use Magento\Framework\Data\Collection\AbstractDb;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Model\AbstractModel;
use Magento\Framework\DataObject\IdentityInterface;
use Magento\Framework\Model\Context;
use Magento\Framework\Registry;
use Magento\Framework\Model\ResourceModel\AbstractResource;
use Magento\Framework\UrlInterface;
use Magento\Store\Model\StoreManagerInterface;

/**
 * Class Banner
 *
 * @package Boolfly\BannerSlider\Model
 */
class Banner extends AbstractModel implements BannerInterface, IdentityInterface
{

    /**
     * Sub directory image
     *
     * @const
     */
    const ENTITY_MEDIA_PATH = '/boolfly/banner';

    /**#@-*/
    protected $_eventPrefix = 'boolfly_banner';

    /**
     * Event Object
     *
     * @var string
     */
    protected $_eventObject = 'banner';

    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * @var ImageUploader
     */
    private $imageUploader;

    /**
     * Banner constructor.
     *
     * @param Context               $context
     * @param Registry              $registry
     * @param StoreManagerInterface $storeManager
     * @param ImageUploader         $imageUploader
     * @param AbstractResource|null $resource
     * @param AbstractDb|null       $resourceCollection
     * @param array                 $data
     */
    public function __construct(
        Context $context,
        Registry $registry,
        StoreManagerInterface $storeManager,
        ImageUploader $imageUploader,
        AbstractResource $resource = null,
        AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
        $this->storeManager  = $storeManager;
        $this->imageUploader = $imageUploader;
    }

    /**
     * Initialize resource model
     *
     * @return void
     */
    public function _construct()
    {
        $this->_init(BannerResourceModel::class);
    }

    /**
     * Get Image Url
     *
     * @param $image
     * @return boolean|string
     * @throws LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getImageUrl($image)
    {
        $url = false;
        if ($image) {
            if (is_string($image)) {
                /** @var \Magento\Store\Model\Store $store */
                $store        = $this->storeManager->getStore();
                $mediaBaseUrl = $store->getBaseUrl(
                    UrlInterface::URL_TYPE_MEDIA
                );
                $url          = $mediaBaseUrl
                    . ltrim(self::ENTITY_MEDIA_PATH, '/')
                    . '/'
                    . $image;
            } else {
                throw new LocalizedException(
                    __('Something went wrong while getting the image url.')
                );
            }
        }

        return $url;
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
        return $this->setData(self::TITLE);
    }

    /**
     * Get Status
     *
     * @return boolean
     * @since 1.0.0
     */
    public function getStatus()
    {
        return (boolean)$this->_getData(self::STATUS);
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
        return $this->setData(self::STATUS, $status);
    }

    /**
     * Get Banner Url
     *
     * @return string|null
     * @since 1.0.0
     */
    public function getBannerUrl()
    {
        return $this->_getData(self::BANNER_URL);
    }

    /**
     * Set Banner Url
     *
     * @param string $url
     *
     * @return $this
     * @since 1.0.0
     */
    public function setBannerUrl($url)
    {
        return $this->setData(self::BANNER_URL, $url);
    }

    /**
     * Get Banner Description
     *
     * @return string|null
     * @since 1.0.0
     */
    public function getDescription()
    {
        return $this->_getData(self::DESCRIPTION);
    }

    /**
     * Set Description
     *
     * @param string $description
     *
     * @return $this
     * @since 1.0.0
     */
    public function setDescription($description)
    {
        return $this->setData(self::DESCRIPTION, $description);
    }

    /**
     * Get Button Text
     *
     * @return string|null
     * @since 1.0.0
     */
    public function getButtonText()
    {
        return $this->_getData(self::BUTTON_TEXT);
    }

    /**
     * Set Button Text
     *
     * @param string $text
     *
     * @return $this
     * @since 1.0.0
     */
    public function setButtonText($text)
    {
        return $this->setData(self::BUTTON_TEXT);
    }

    /**
     * Get Button Url
     *
     * @return string|null
     * @since 1.0.0
     */
    public function getButtonUrl()
    {
        return $this->_getData(self::BUTTON_URL);
    }

    /**
     * Set Button Url
     *
     * @param string $url
     *
     * @return $this
     * @since 1.0.0
     */
    public function setButtonUrl($url)
    {
        return $this->setData(self::BUTTON_URL, $url);
    }

    /**
     * Get Image Desktop Url
     *
     * @return string|boolean
     * @since 1.0.0
     * @throws LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getImageDesktopUrl()
    {
        return $this->getImageUrl($this->_getData(self::IMAGE_DESKTOP));
    }

    /**
     * Set Image Desktop
     *
     * @param string $imageName
     *
     * @return $this
     * @since 1.0.0
     */
    public function setImageDesktop($imageName)
    {
        return $this->setData(self::IMAGE_DESKTOP, $imageName);
    }

    /**
     * Get Image Tablet Url
     *
     * @return string|boolean
     * @since 1.0.0
     * @throws LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getImageTabletUrl()
    {
        return $this->getImageUrl($this->_getData(self::IMAGE_TABLET));
    }

    /**
     * Set Image Tablet
     *
     * @param string $imageName
     *
     * @return $this
     * @since 1.0.0
     */
    public function setImageTablet($imageName)
    {
        return $this->setData(self::IMAGE_TABLET);
    }

    /**
     * Get Image Mobile Url
     *
     * @return string|boolean
     * @since 1.0.0
     * @throws LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getImageMobileUrl()
    {
        return $this->getImageUrl($this->_getData(self::IMAGE_MOBILE));
    }

    /**
     * Set Image Mobile
     *
     * @param string $imageName
     *
     * @return $this
     * @since 1.0.0
     */
    public function setImageMobile($imageName)
    {
        return $this->setData(self::IMAGE_MOBILE, $imageName);
    }

    /**
     * Get Target Link
     *
     * @return string|null
     * @since 1.0.0
     */
    public function getTargetLink()
    {
        return $this->_getData(self::TARGET_LINK);
    }

    /**
     * Set Target Link
     *
     * @param string $targetLink
     *
     * @return $this
     * @since 1.0.0
     */
    public function setTargetLink($targetLink)
    {
        return $this->setData(self::TARGET_LINK, $targetLink);
    }

    /**
     * Get Align Text
     *
     * @return string|null
     * @since 1.0.0
     */
    public function getAlignText()
    {
        return $this->_getData(self::ALIGN_TEXT);
    }

    /**
     * Check Button
     *
     * @return boolean
     */
    public function hasButton()
    {
        return $this->getButtonUrl() || $this->getButtonText();
    }

    /**
     * Set Align Text
     *
     * @param string $alignText
     *
     * @return $this
     * @since 1.0.0
     */
    public function setAlignText($alignText)
    {
        return $this->setData(self::ALIGN_TEXT, $alignText);
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
}
