<?php
declare(strict_types=1);
/**
 * Copyright © 2019 SkWeb. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace SkWeb\ProductsExtend\Api\Data;

use Magento\Framework\Api\ExtensibleDataInterface;

/**
 * Interface ProductSaleInformationInterface
 * @package SkWeb\ProductsExtend\Api\Data
 */
interface ProductSaleInformationInterface extends ExtensibleDataInterface
{
    /**
     * @param string $status
     * @return mixed
     */
    public function getQty(string $status);

    /**
     * @return mixed
     */
    public function getLastOrderDate();
}
