<?php
declare(strict_types=1);
/**
 * Copyright © 2019 SkWeb (sergey.kamnev@gmail.com). All rights reserved.
 * See COPYING.txt for license details.
 */

namespace SkWeb\ProductsExtend\Model\Plugin\Product;

use Magento\Framework\Api\SearchResults;
use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\Api\Data\ProductExtensionFactory;
use SkWeb\ProductsExtend\Api\Data\ProductSaleInformationInterface;

/**
 * Class SaleInformation
 * @package SkWeb\ProductsExtend\Model\Plugin\Product
 */
class SaleInformation
{
    /** @var ProductExtensionFactory */
    private $productExtensionFactory;

    /** @var ProductSaleInformationInterface */
    private $saleInformationProvider;

    /**
     * SaleInformation constructor.
     * @param ProductExtensionFactory $productExtensionFactory
     * @param ProductSaleInformationInterface $saleInformationProvider
     */
    public function __construct(
        ProductExtensionFactory $productExtensionFactory,
        ProductSaleInformationInterface $saleInformationProvider
    ) {
        $this->productExtensionFactory = $productExtensionFactory;
        $this->saleInformationProvider = $saleInformationProvider;
    }

    /**
     * @param ProductRepositoryInterface $subject
     * @param SearchResults $searchResult
     * @return SearchResults
     */
    public function afterGetList(
        ProductRepositoryInterface $subject,
        SearchResults $searchResult
    ) {
        /** @var \Magento\Catalog\Api\Data\ProductInterface $product */
        foreach ($searchResult->getItems() as $product) {
            $this->addSaleInformationToProduct($product);
        }

        return $searchResult;
    }

    /**
     * @param ProductRepositoryInterface $subject
     * @param ProductInterface $product
     * @return ProductInterface
     */
    public function afterGetById(
        ProductRepositoryInterface $subject,
        ProductInterface $product
    ) {
        $this->addSaleInformationToProduct($product);
        return $product;
    }


    /**
     * @param ProductInterface $product
     * @return $this
     */
    private function addSaleInformationToProduct(ProductInterface $product)
    {
        $extensionAttributes = $product->getExtensionAttributes();

        if (empty($extensionAttributes)) {
            $extensionAttributes = $this->productExtensionFactory->create();
        }
        $saleInformation = $this->saleInformationProvider;
        $extensionAttributes->setSaleInformation($saleInformation->setProductId($product->getId()));
        $product->setExtensionAttributes($extensionAttributes);

        return $this;
    }
}
