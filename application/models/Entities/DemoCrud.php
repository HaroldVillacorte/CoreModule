<?php

namespace Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * CrudDemo
 */
class DemoCrud
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var integer
     */
    private $ordernumber;

    /**
     * @var string
     */
    private $productcode;

    /**
     * @var integer
     */
    private $quantityordered;

    /**
     * @var float
     */
    private $priceeach;

    /**
     * @var integer
     */
    private $orderlinenumber;

    /**
     * @var string
     */
    private $text;


    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set ordernumber
     *
     * @param integer $ordernumber
     * @return CrudDemo
     */
    public function setOrdernumber($ordernumber)
    {
        $this->ordernumber = $ordernumber;

        return $this;
    }

    /**
     * Get ordernumber
     *
     * @return integer
     */
    public function getOrdernumber()
    {
        return $this->ordernumber;
    }

    /**
     * Set productcode
     *
     * @param string $productcode
     * @return CrudDemo
     */
    public function setProductcode($productcode)
    {
        $this->productcode = $productcode;

        return $this;
    }

    /**
     * Get productcode
     *
     * @return string
     */
    public function getProductcode()
    {
        return $this->productcode;
    }

    /**
     * Set quantityordered
     *
     * @param integer $quantityordered
     * @return CrudDemo
     */
    public function setQuantityordered($quantityordered)
    {
        $this->quantityordered = $quantityordered;

        return $this;
    }

    /**
     * Get quantityordered
     *
     * @return integer
     */
    public function getQuantityordered()
    {
        return $this->quantityordered;
    }

    /**
     * Set priceeach
     *
     * @param float $priceeach
     * @return CrudDemo
     */
    public function setPriceeach($priceeach)
    {
        $this->priceeach = $priceeach;

        return $this;
    }

    /**
     * Get priceeach
     *
     * @return float
     */
    public function getPriceeach()
    {
        return $this->priceeach;
    }

    /**
     * Set orderlinenumber
     *
     * @param integer $orderlinenumber
     * @return CrudDemo
     */
    public function setOrderlinenumber($orderlinenumber)
    {
        $this->orderlinenumber = $orderlinenumber;

        return $this;
    }

    /**
     * Get orderlinenumber
     *
     * @return integer
     */
    public function getOrderlinenumber()
    {
        return $this->orderlinenumber;
    }

    /**
     * Set text
     *
     * @param string $text
     * @return CrudDemo
     */
    public function setText($text)
    {
        $this->text = $text;

        return $this;
    }

    /**
     * Get text
     *
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }
}