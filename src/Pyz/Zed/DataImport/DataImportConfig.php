<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\DataImport;

use Generated\Shared\Transfer\DataImporterQueueWriterConfigurationTransfer;
use Spryker\Zed\DataImport\DataImportConfig as SprykerDataImportConfig;

/**
 * @SuppressWarnings(PHPMD.ExcessiveClassComplexity)
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
 */
class DataImportConfig extends SprykerDataImportConfig
{
    public const IMPORT_TYPE_CATEGORY_TEMPLATE = 'category-template';
    public const IMPORT_TYPE_CUSTOMER = 'customer';
    public const IMPORT_TYPE_GLOSSARY = 'glossary';
    public const IMPORT_TYPE_NAVIGATION = 'navigation';
    public const IMPORT_TYPE_NAVIGATION_NODE = 'navigation-node';
    public const IMPORT_TYPE_PRODUCT_PRICE = 'product-price';
    public const IMPORT_TYPE_PRODUCT_STOCK = 'product-stock';
    public const IMPORT_TYPE_PRODUCT_ABSTRACT = 'product-abstract';
    public const IMPORT_TYPE_PRODUCT_ABSTRACT_STORE = 'product-abstract-store';
    public const IMPORT_TYPE_PRODUCT_CONCRETE = 'product-concrete';
    public const IMPORT_TYPE_PRODUCT_ATTRIBUTE_KEY = 'product-attribute-key';
    public const IMPORT_TYPE_PRODUCT_MANAGEMENT_ATTRIBUTE = 'product-management-attribute';
    public const IMPORT_TYPE_PRODUCT_REVIEW = 'product-review';
    public const IMPORT_TYPE_PRODUCT_SET = 'product-set';
    public const IMPORT_TYPE_PRODUCT_GROUP = 'product-group';
    public const IMPORT_TYPE_PRODUCT_OPTION = 'product-option';
    public const IMPORT_TYPE_PRODUCT_OPTION_PRICE = 'product-option-price';
    public const IMPORT_TYPE_PRODUCT_IMAGE = 'product-image';
    public const IMPORT_TYPE_PRODUCT_SEARCH_ATTRIBUTE_MAP = 'product-search-attribute-map';
    public const IMPORT_TYPE_PRODUCT_SEARCH_ATTRIBUTE = 'product-search-attribute';
    public const IMPORT_TYPE_CMS_TEMPLATE = 'cms-template';
    public const IMPORT_TYPE_CMS_BLOCK = 'cms-block';
    public const IMPORT_TYPE_CMS_BLOCK_STORE = 'cms-block-store';
    public const IMPORT_TYPE_CMS_BLOCK_CATEGORY_POSITION = 'cms-block-category-position';
    public const IMPORT_TYPE_CMS_BLOCK_CATEGORY = 'cms-block-category';
    public const IMPORT_TYPE_DISCOUNT = 'discount';
    public const IMPORT_TYPE_DISCOUNT_STORE = 'discount-store';
    public const IMPORT_TYPE_DISCOUNT_AMOUNT = 'discount-amount';
    public const IMPORT_TYPE_DISCOUNT_VOUCHER = 'discount-voucher';
    public const IMPORT_TYPE_TAX = 'tax';
    public const IMPORT_TYPE_CURRENCY = 'currency';
    public const IMPORT_TYPE_STORE = 'store';
    public const IMPORT_TYPE_ORDER_SOURCE = 'order-source';
    public const IMPORT_TYPE_ABSTRACT_GIFT_CARD_CONFIGURATION = 'gift-card-abstract-configuration';
    public const IMPORT_TYPE_CONCRETE_GIFT_CARD_CONFIGURATION = 'gift-card-concrete-configuration';
    public const IMPORT_TYPE_COMBINED_PRODUCT_ABSTRACT = 'combined-product-abstract';
    public const IMPORT_TYPE_COMBINED_PRODUCT_ABSTRACT_STORE = 'combined-product-abstract-store';
    public const IMPORT_TYPE_COMBINED_PRODUCT_CONCRETE = 'combined-product-concrete';
    public const IMPORT_TYPE_COMBINED_PRODUCT_IMAGE = 'combined-product-image';
    public const IMPORT_TYPE_COMBINED_PRODUCT_PRICE = 'combined-product-price';
    public const IMPORT_TYPE_COMBINED_PRODUCT_STOCK = 'combined-product-stock';
    public const IMPORT_TYPE_COMBINED_PRODUCT_GROUP = 'combined-product-group';

    public const PRODUCT_ABSTRACT_QUEUE = 'import.product_abstract';
    public const PRODUCT_ABSTRACT_QUEUE_ERROR = 'import.product_abstract.error';
    public const PRODUCT_CONCRETE_QUEUE = 'import.product_concrete';
    public const PRODUCT_CONCRETE_QUEUE_ERROR = 'import.product_concrete.error';
    public const PRODUCT_IMAGE_QUEUE = 'import.product_image';
    public const PRODUCT_IMAGE_QUEUE_ERROR = 'import.product_image.error';
    public const PRODUCT_PRICE_QUEUE = 'import.product_price';
    public const PRODUCT_PRICE_QUEUE_ERROR = 'import.product_price.error';
    public const IMPORT_TYPE_MERCHANT_USER = 'merchant-user';

    /**
     * @return string|null
     */
    public function getDefaultYamlConfigPath(): ?string
    {
        return APPLICATION_ROOT_DIR . DIRECTORY_SEPARATOR . 'data/import/local/full_EU.yml';
    }

    /**
     * @return \Generated\Shared\Transfer\DataImporterQueueWriterConfigurationTransfer
     */
    public function getProductAbstractQueueWriterConfiguration(): DataImporterQueueWriterConfigurationTransfer
    {
        return (new DataImporterQueueWriterConfigurationTransfer())
            ->setQueueName(static::PRODUCT_ABSTRACT_QUEUE)
            ->setChunkSize($this->getQueueWriterChunkSize());
    }

    /**
     * @return \Generated\Shared\Transfer\DataImporterQueueWriterConfigurationTransfer
     */
    public function getProductConcreteQueueWriterConfiguration(): DataImporterQueueWriterConfigurationTransfer
    {
        return (new DataImporterQueueWriterConfigurationTransfer())
            ->setQueueName(static::PRODUCT_CONCRETE_QUEUE)
            ->setChunkSize($this->getQueueWriterChunkSize());
    }

    /**
     * @return \Generated\Shared\Transfer\DataImporterQueueWriterConfigurationTransfer
     */
    public function getProductImageQueueWriterConfiguration(): DataImporterQueueWriterConfigurationTransfer
    {
        return (new DataImporterQueueWriterConfigurationTransfer())
            ->setQueueName(static::PRODUCT_IMAGE_QUEUE)
            ->setChunkSize($this->getQueueWriterChunkSize());
    }

    /**
     * @return \Generated\Shared\Transfer\DataImporterQueueWriterConfigurationTransfer
     */
    public function getProductPriceQueueWriterConfiguration(): DataImporterQueueWriterConfigurationTransfer
    {
        return (new DataImporterQueueWriterConfigurationTransfer())
            ->setQueueName(static::PRODUCT_PRICE_QUEUE)
            ->setChunkSize($this->getQueueWriterChunkSize());
    }

    /**
     * @return string[]
     */
    public function getFullImportTypes(): array
    {
        return [
            static::IMPORT_TYPE_CATEGORY_TEMPLATE,
            static::IMPORT_TYPE_CUSTOMER,
            static::IMPORT_TYPE_GLOSSARY,
            static::IMPORT_TYPE_NAVIGATION,
            static::IMPORT_TYPE_NAVIGATION_NODE,
            static::IMPORT_TYPE_PRODUCT_PRICE,
            static::IMPORT_TYPE_PRODUCT_ABSTRACT,
            static::IMPORT_TYPE_PRODUCT_ABSTRACT_STORE,
            static::IMPORT_TYPE_PRODUCT_CONCRETE,
            static::IMPORT_TYPE_PRODUCT_ATTRIBUTE_KEY,
            static::IMPORT_TYPE_PRODUCT_MANAGEMENT_ATTRIBUTE,
            static::IMPORT_TYPE_PRODUCT_REVIEW,
            static::IMPORT_TYPE_PRODUCT_SET,
            static::IMPORT_TYPE_PRODUCT_GROUP,
            static::IMPORT_TYPE_PRODUCT_OPTION,
            static::IMPORT_TYPE_PRODUCT_OPTION_PRICE,
            static::IMPORT_TYPE_PRODUCT_IMAGE,
            static::IMPORT_TYPE_PRODUCT_SEARCH_ATTRIBUTE_MAP,
            static::IMPORT_TYPE_PRODUCT_SEARCH_ATTRIBUTE,
            static::IMPORT_TYPE_CMS_TEMPLATE,
            static::IMPORT_TYPE_CMS_BLOCK,
            static::IMPORT_TYPE_CMS_BLOCK_STORE,
            static::IMPORT_TYPE_CMS_BLOCK_CATEGORY_POSITION,
            static::IMPORT_TYPE_CMS_BLOCK_CATEGORY,
            static::IMPORT_TYPE_DISCOUNT,
            static::IMPORT_TYPE_DISCOUNT_STORE,
            static::IMPORT_TYPE_DISCOUNT_AMOUNT,
            static::IMPORT_TYPE_DISCOUNT_VOUCHER,
            static::IMPORT_TYPE_TAX,
            static::IMPORT_TYPE_CURRENCY,
            static::IMPORT_TYPE_STORE,
            static::IMPORT_TYPE_PRODUCT_STOCK,
            static::IMPORT_TYPE_ORDER_SOURCE,
            static::IMPORT_TYPE_ABSTRACT_GIFT_CARD_CONFIGURATION,
            static::IMPORT_TYPE_CONCRETE_GIFT_CARD_CONFIGURATION,

        ];
    }
}
