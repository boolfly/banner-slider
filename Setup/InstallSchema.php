<?php
/************************************************************
 * *
 *  * Copyright © Boolfly. All rights reserved.
 *  * See COPYING.txt for license details.
 *  *
 *  * @author    info@boolfly.com
 * *  @project   Banner Slider
 */
namespace Boolfly\BannerSlider\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\DB\Ddl\Table;

/**
 * Class InstallSchema
 *
 * @package Boolfly\BannerSlider\Setup
 */
class InstallSchema implements InstallSchemaInterface
{

    /**@#%
     * Prefix of Table
     *
     * @const
     */
    const PREFIX_TABLE_NAME = 'boolfly_';

    /**@#%
     * Table Name
     *
     * @const
     */
    const BANNER_TABLE_NAME          = self::PREFIX_TABLE_NAME . 'banner';
    const SLIDER_TABLE_NAME          = self::PREFIX_TABLE_NAME . 'slider';
    const BANNER_SLIDER_TABLE_NAME   = self::PREFIX_TABLE_NAME . 'banner_slider';
    const SLIDER_CMS_PAGE_TABLE_NAME = self::PREFIX_TABLE_NAME . 'slider_cms_page';

    /**
     * {@inheritdoc}
     *
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;
        $installer->startSetup();

        $this->createBannerTable($installer);
        $this->createSliderTable($installer);
        $this->createBannerSliderTable($installer);
        $this->createSliderCmsPageTable($installer);

        $installer->endSetup();
    }

    /**
     * Create Banner Table
     *
     * @param SchemaSetupInterface $installer
     * @throws \Zend_Db_Exception
     */
    private function createBannerTable(SchemaSetupInterface $installer)
    {
        // Add prefix table
        $tableName = $installer->getTable(self::BANNER_TABLE_NAME);
        if (!$installer->tableExists($tableName)) {
            $table = $installer->getConnection()
                ->newTable(
                    $tableName
                )->addColumn(
                    'banner_id',
                    Table::TYPE_INTEGER,
                    null,
                    [
                        'unsigned' => true,
                        'identity' => true,
                        'nullable' => false,
                        'primary'  => true,
                    ],
                    'Banner ID'
                )->addColumn(
                    'title',
                    Table::TYPE_TEXT,
                    255,
                    ['nullable' => false],
                    'Title'
                )->addColumn(
                    'status',
                    Table::TYPE_SMALLINT,
                    6,
                    ['nullable' => false],
                    'Status'
                )->addColumn(
                    'banner_url',
                    Table::TYPE_TEXT,
                    255,
                    ['nullable' => true],
                    'Banner URL'
                )->addColumn(
                    'description',
                    Table::TYPE_TEXT,
                    255,
                    ['nullable' => true],
                    'Description'
                )->addColumn(
                    'button_text',
                    Table::TYPE_TEXT,
                    50,
                    ['nullable' => true],
                    'Button Text'
                )->addColumn(
                    'button_url',
                    Table::TYPE_TEXT,
                    255,
                    ['nullable' => true],
                    'Button URL'
                )->addColumn(
                    'image_alt',
                    Table::TYPE_TEXT,
                    255,
                    ['nullable' => true],
                    'Image Alt'
                )->addColumn(
                    'image_desktop',
                    Table::TYPE_TEXT,
                    255,
                    ['nullable' => true],
                    'Image in Desktop'
                )->addColumn(
                    'image_tablet',
                    Table::TYPE_TEXT,
                    255,
                    ['nullable' => true],
                    'Image in Tablet'
                )->addColumn(
                    'image_mobile',
                    Table::TYPE_TEXT,
                    255,
                    ['nullable' => true],
                    'Image in Mobile'
                )->addColumn(
                    'target_link',
                    Table::TYPE_TEXT,
                    30,
                    ['nullable' => true],
                    'Target when click to banner'
                )->addColumn(
                    'align_text',
                    Table::TYPE_TEXT,
                    10,
                    ['nullable' => true],
                    'Placement Text in Banner'
                )->addColumn(
                    'created_at',
                    Table::TYPE_TIMESTAMP,
                    null,
                    ['nullable' => true],
                    'Created At'
                )->addColumn(
                    'updated_at',
                    Table::TYPE_TIMESTAMP,
                    null,
                    ['nullable' => true],
                    'Updated At'
                )->addIndex(
                    $installer->getIdxName($tableName, ['banner_id']),
                    ['banner_id']
                )->setComment(
                    'Banner Table'
                );
            $installer->getConnection()->createTable($table);
        }
    }

    /**
     * Create Slider Table
     *
     * @param SchemaSetupInterface $installer
     * @throws \Zend_Db_Exception
     */
    private function createSliderTable(SchemaSetupInterface $installer)
    {
        // Add prefix table
        $tableName = $installer->getTable(self::SLIDER_TABLE_NAME);
        if (!$installer->tableExists($tableName)) {
            $table = $installer->getConnection()
                ->newTable(
                    $tableName
                )->addColumn(
                    'slider_id',
                    Table::TYPE_INTEGER,
                    null,
                    [
                        'unsigned' => true,
                        'identity' => true,
                        'nullable' => false,
                        'primary'  => true,
                    ],
                    'Slider ID'
                )->addColumn(
                    'title',
                    Table::TYPE_TEXT,
                    255,
                    ['nullable' => false],
                    'Title'
                )->addColumn(
                    'display_title',
                    Table::TYPE_SMALLINT,
                    6,
                    ['nullable' => false],
                    'Display Title'
                )->addColumn(
                    'status',
                    Table::TYPE_SMALLINT,
                    6,
                    ['nullable' => false],
                    'Status'
                )->addColumn(
                    'mode',
                    Table::TYPE_SMALLINT,
                    6,
                    ['nullable' => true],
                    'Mode'
                )->addColumn(
                    'animation_effect',
                    Table::TYPE_TEXT,
                    25,
                    ['nullable' => true],
                    'Animation Effect'
                )->addColumn(
                    'auto_play',
                    Table::TYPE_SMALLINT,
                    6,
                    ['nullable' => false],
                    'Auto Play'
                )->addColumn(
                    'speed',
                    Table::TYPE_INTEGER,
                    null,
                    ['nullable' => true],
                    'Speed to change image'
                )->addColumn(
                    'position',
                    Table::TYPE_TEXT,
                    50,
                    ['nullable' => true],
                    'Position of Slider'
                )->addColumn(
                    'category_id',
                    Table::TYPE_TEXT,
                    255,
                    ['nullable' => true],
                    'Category Id'
                )->addColumn(
                    'created_at',
                    Table::TYPE_TIMESTAMP,
                    null,
                    ['nullable' => true],
                    'Created At'
                )->addColumn(
                    'updated_at',
                    Table::TYPE_TIMESTAMP,
                    null,
                    ['nullable' => true],
                    'Updated At'
                )->addIndex(
                    $installer->getIdxName($tableName, ['slider_id']),
                    ['slider_id']
                )->setComment(
                    'Slider Table'
                );

            $installer->getConnection()->createTable($table);
        }
    }

    /**
     * Create Banner Slider Table
     *
     * @param SchemaSetupInterface $installer
     * @throws \Zend_Db_Exception
     */
    private function createBannerSliderTable(SchemaSetupInterface $installer)
    {
        $tableName       = $installer->getTable(self::BANNER_SLIDER_TABLE_NAME);
        $sliderTableName = $installer->getTable(self::SLIDER_TABLE_NAME);
        $bannerTableName = $installer->getTable(self::BANNER_TABLE_NAME);
        if (!$installer->tableExists($tableName)) {
            $table = $installer->getConnection()
                ->newTable($installer->getTable($tableName))
                ->addColumn(
                    'banner_id',
                    Table::TYPE_INTEGER,
                    null,
                    [
                        'unsigned' => true,
                        'nullable' => false,
                        'primary'  => true,
                    ],
                    'Banner Id'
                )->addColumn(
                    'slider_id',
                    Table::TYPE_INTEGER,
                    null,
                    [
                        'unsigned' => true,
                        'nullable' => false,
                        'primary'  => true,
                    ],
                    'Slider Id'
                )->addColumn(
                    'position',
                    Table::TYPE_SMALLINT,
                    6,
                    [
                        'nullable' => true,
                    ],
                    'Position'
                )->addIndex(
                    $installer->getIdxName($tableName, ['slider_id']),
                    ['slider_id']
                )->addIndex(
                    $installer->getIdxName($tableName, ['banner_id']),
                    ['banner_id']
                )->addForeignKey(
                    $installer->getFkName(
                        $tableName,
                        'slider_id',
                        $sliderTableName,
                        'slider_id'
                    ),
                    'slider_id',
                    $sliderTableName,
                    'slider_id',
                    Table::ACTION_CASCADE
                )->addForeignKey(
                    $installer->getFkName(
                        $tableName,
                        'banner_id',
                        $bannerTableName,
                        'banner_id'
                    ),
                    'banner_id',
                    $bannerTableName,
                    'banner_id',
                    Table::ACTION_CASCADE
                )->setComment('Slider and Banner Relations');

            $installer->getConnection()->createTable($table);
        }
    }

    /**
     * Create Slider Cms Page Table
     *
     * @param SchemaSetupInterface $installer
     * @throws \Zend_Db_Exception
     */
    private function createSliderCmsPageTable(SchemaSetupInterface $installer)
    {
        $tableName        = $installer->getTable(self::SLIDER_CMS_PAGE_TABLE_NAME);
        $sliderTableName  = $installer->getTable(self::SLIDER_TABLE_NAME);
        $cmsPageTableName = $installer->getTable('cms_page');
        if (!$installer->tableExists($tableName)) {
            $table = $installer->getConnection()
                ->newTable($installer->getTable($tableName))
                ->addColumn(
                    'slider_id',
                    Table::TYPE_INTEGER,
                    null,
                    [
                        'unsigned' => true,
                        'nullable' => false,
                        'primary'  => true,
                    ],
                    'Slider Id'
                )->addColumn(
                    'page_id',
                    Table::TYPE_SMALLINT,
                    6,
                    [
                        'unsigned' => false,
                        'nullable' => false,
                        'primary'  => true,
                    ],
                    'Page Id'
                )->addIndex(
                    $installer->getIdxName($tableName, ['slider_id']),
                    ['slider_id']
                )->addIndex(
                    $installer->getIdxName($tableName, ['page_id']),
                    ['page_id']
                )->addForeignKey(
                    $installer->getFkName(
                        $tableName,
                        'slider_id',
                        $sliderTableName,
                        'slider_id'
                    ),
                    'slider_id',
                    $sliderTableName,
                    'slider_id',
                    Table::ACTION_CASCADE
                )->addForeignKey(
                    $installer->getFkName(
                        $tableName,
                        'page_id',
                        $cmsPageTableName,
                        'page_id'
                    ),
                    'page_id',
                    $cmsPageTableName,
                    'page_id',
                    Table::ACTION_CASCADE
                )->setComment('Slider and CMS Page Relations');

            $installer->getConnection()->createTable($table);
        }
    }

    /**
     * Create Slider Category Table
     *
     * @param SchemaSetupInterface $installer
     * @throws \Zend_Db_Exception
     */
    private function createSliderCategoryTable(SchemaSetupInterface $installer)
    {
        $tableName        = $installer->getTable(self::SLIDER_CMS_PAGE_TABLE_NAME);
        $sliderTableName  = $installer->getTable(self::SLIDER_TABLE_NAME);
        $cmsPageTableName = $installer->getTable('cms_page');
        if (!$installer->tableExists($tableName)) {
            $table = $installer->getConnection()
                ->newTable($installer->getTable($tableName))
                ->addColumn(
                    'slider_id',
                    Table::TYPE_INTEGER,
                    null,
                    [
                        'unsigned' => true,
                        'nullable' => false,
                        'primary'  => true,
                    ],
                    'Slider Id'
                )->addColumn(
                    'page_id',
                    Table::TYPE_SMALLINT,
                    6,
                    [
                        'unsigned' => false,
                        'nullable' => false,
                        'primary'  => true,
                    ],
                    'Page Id'
                )->addIndex(
                    $installer->getIdxName($tableName, ['slider_id']),
                    ['slider_id']
                )->addIndex(
                    $installer->getIdxName($tableName, ['page_id']),
                    ['page_id']
                )->addForeignKey(
                    $installer->getFkName(
                        $tableName,
                        'slider_id',
                        $sliderTableName,
                        'slider_id'
                    ),
                    'slider_id',
                    $sliderTableName,
                    'slider_id',
                    Table::ACTION_CASCADE
                )->addForeignKey(
                    $installer->getFkName(
                        $tableName,
                        'page_id',
                        $cmsPageTableName,
                        'page_id'
                    ),
                    'page_id',
                    $cmsPageTableName,
                    'page_id',
                    Table::ACTION_CASCADE
                )->setComment('Slider and CMS Page Relations');

            $installer->getConnection()->createTable($table);
        }
    }
}
