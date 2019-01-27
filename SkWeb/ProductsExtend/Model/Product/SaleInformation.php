<?php
declare(strict_types=1);
/**
 * Copyright © 2019 SkWeb (sergey.kamnev@gmail.com). All rights reserved.
 * See COPYING.txt for license details.
 */

namespace SkWeb\ProductsExtend\Model\Product;

use Magento\Sales\Model\ResourceModel\Order\CollectionFactory as OrderFactory;
use SkWeb\ProductsExtend\Api\Data\ProductSaleInformationInterface;

/**
 * Product Extra Information Model
 * @codeCoverageIgnore
 */
class SaleInformation implements ProductSaleInformationInterface
{
    protected $orderCollection;
    protected $orderStatus;
    private $productId;

    /**
     * SaleInformation constructor.
     * @param OrderFactory $orderCollection
     * @param string $orderStatus
     */
    public function __construct(
        OrderFactory $orderCollection,
        string $orderStatus
    ) {
        $this->orderStatus = $orderStatus;
        $this->orderCollection = $orderCollection;
    }
    /**
     * @param string $status
     * @return int
     */
    public function getQty(string $status) : int
    {
        $orderCollection = $this->getProductOrders($this->orderStatus);

        return $orderCollection->getSize();
    }

    /**
     * @return string
     */
    public function getLastOrderDate() : string
    {
        $orderCollection = $this->getProductOrders(null, 'DESC');

        $createdAt = '';
        if ($orderCollection->getSize() > 0) {
            $createdAt = $orderCollection->getFirstItem()->getCreatedAt();
        }
        return $createdAt;
    }

    /**
     * @param $productId
     * @return $this
     */
    public function setProductId($productId)
    {
        $this->productId = $productId;

        return $this;
    }

    /**
     * @param null $orderStatus
     * @param string $order
     * @return $this
     */
    private function getProductOrders($orderStatus = null, string $order = 'ASC')
    {
        $orderCollection = $this->orderCollection->create()->addFieldToSelect(['*']);

        if ($orderStatus != '') {
            $orderCollection->addFieldToFilter(
                'status',
                ['eq' => $orderStatus]
            );
        }

        $orderCollection->getSelect()
            ->join(
                'sales_order_item',
                'main_table.entity_id = sales_order_item.order_id'
            )->where('sales_order_item.product_id = ' . $this->productId);

        $orderCollection->setOrder('main_table.created_at', $order);

        return $orderCollection;
    }
}
