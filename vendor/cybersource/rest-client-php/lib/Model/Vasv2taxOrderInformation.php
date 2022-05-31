<?php
/**
 * Vasv2taxOrderInformation
 *
 * PHP version 5
 *
 * @category Class
 * @package  CyberSource
 * @author   Swaagger Codegen team
 * @link     https://github.com/swagger-api/swagger-codegen
 */

/**
 * CyberSource Merged Spec
 *
 * All CyberSource API specs merged together. These are available at https://developer.cybersource.com/api/reference/api-reference.html
 *
 * OpenAPI spec version: 0.0.1
 * 
 * Generated by: https://github.com/swagger-api/swagger-codegen.git
 *
 */

/**
 * NOTE: This class is auto generated by the swagger code generator program.
 * https://github.com/swagger-api/swagger-codegen
 * Do not edit the class manually.
 */

namespace CyberSource\Model;

use \ArrayAccess;

/**
 * Vasv2taxOrderInformation Class Doc Comment
 *
 * @category    Class
 * @package     CyberSource
 * @author      Swagger Codegen team
 * @link        https://github.com/swagger-api/swagger-codegen
 */
class Vasv2taxOrderInformation implements ArrayAccess
{
    const DISCRIMINATOR = null;

    /**
      * The original name of the model.
      * @var string
      */
    protected static $swaggerModelName = 'vasv2tax_orderInformation';

    /**
      * Array of property to type mappings. Used for (de)serialization
      * @var string[]
      */
    protected static $swaggerTypes = [
        'amountDetails' => '\CyberSource\Model\RiskV1DecisionsPost201ResponseOrderInformationAmountDetails',
        'billTo' => '\CyberSource\Model\Vasv2taxOrderInformationBillTo',
        'shippingDetails' => '\CyberSource\Model\Vasv2taxOrderInformationShippingDetails',
        'shipTo' => '\CyberSource\Model\Vasv2taxOrderInformationShipTo',
        'lineItems' => '\CyberSource\Model\Vasv2taxOrderInformationLineItems[]',
        'invoiceDetails' => '\CyberSource\Model\Vasv2taxOrderInformationInvoiceDetails',
        'orderAcceptance' => '\CyberSource\Model\Vasv2taxOrderInformationOrderAcceptance',
        'orderOrigin' => '\CyberSource\Model\Vasv2taxOrderInformationOrderOrigin'
    ];

    /**
      * Array of property to format mappings. Used for (de)serialization
      * @var string[]
      */
    protected static $swaggerFormats = [
        'amountDetails' => null,
        'billTo' => null,
        'shippingDetails' => null,
        'shipTo' => null,
        'lineItems' => null,
        'invoiceDetails' => null,
        'orderAcceptance' => null,
        'orderOrigin' => null
    ];

    public static function swaggerTypes()
    {
        return self::$swaggerTypes;
    }

    public static function swaggerFormats()
    {
        return self::$swaggerFormats;
    }

    /**
     * Array of attributes where the key is the local name, and the value is the original name
     * @var string[]
     */
    protected static $attributeMap = [
        'amountDetails' => 'amountDetails',
        'billTo' => 'billTo',
        'shippingDetails' => 'shippingDetails',
        'shipTo' => 'shipTo',
        'lineItems' => 'lineItems',
        'invoiceDetails' => 'invoiceDetails',
        'orderAcceptance' => 'orderAcceptance',
        'orderOrigin' => 'orderOrigin'
    ];


    /**
     * Array of attributes to setter functions (for deserialization of responses)
     * @var string[]
     */
    protected static $setters = [
        'amountDetails' => 'setAmountDetails',
        'billTo' => 'setBillTo',
        'shippingDetails' => 'setShippingDetails',
        'shipTo' => 'setShipTo',
        'lineItems' => 'setLineItems',
        'invoiceDetails' => 'setInvoiceDetails',
        'orderAcceptance' => 'setOrderAcceptance',
        'orderOrigin' => 'setOrderOrigin'
    ];


    /**
     * Array of attributes to getter functions (for serialization of requests)
     * @var string[]
     */
    protected static $getters = [
        'amountDetails' => 'getAmountDetails',
        'billTo' => 'getBillTo',
        'shippingDetails' => 'getShippingDetails',
        'shipTo' => 'getShipTo',
        'lineItems' => 'getLineItems',
        'invoiceDetails' => 'getInvoiceDetails',
        'orderAcceptance' => 'getOrderAcceptance',
        'orderOrigin' => 'getOrderOrigin'
    ];

    public static function attributeMap()
    {
        return self::$attributeMap;
    }

    public static function setters()
    {
        return self::$setters;
    }

    public static function getters()
    {
        return self::$getters;
    }

    

    

    /**
     * Associative array for storing property values
     * @var mixed[]
     */
    protected $container = [];

    /**
     * Constructor
     * @param mixed[] $data Associated array of property values initializing the model
     */
    public function __construct(array $data = null)
    {
        $this->container['amountDetails'] = isset($data['amountDetails']) ? $data['amountDetails'] : null;
        $this->container['billTo'] = isset($data['billTo']) ? $data['billTo'] : null;
        $this->container['shippingDetails'] = isset($data['shippingDetails']) ? $data['shippingDetails'] : null;
        $this->container['shipTo'] = isset($data['shipTo']) ? $data['shipTo'] : null;
        $this->container['lineItems'] = isset($data['lineItems']) ? $data['lineItems'] : null;
        $this->container['invoiceDetails'] = isset($data['invoiceDetails']) ? $data['invoiceDetails'] : null;
        $this->container['orderAcceptance'] = isset($data['orderAcceptance']) ? $data['orderAcceptance'] : null;
        $this->container['orderOrigin'] = isset($data['orderOrigin']) ? $data['orderOrigin'] : null;
    }

    /**
     * show all the invalid properties with reasons.
     *
     * @return array invalid properties with reasons
     */
    public function listInvalidProperties()
    {
        $invalid_properties = [];

        return $invalid_properties;
    }

    /**
     * validate all the properties in the model
     * return true if all passed
     *
     * @return bool True if all properties are valid
     */
    public function valid()
    {

        return true;
    }


    /**
     * Gets amountDetails
     * @return \CyberSource\Model\RiskV1DecisionsPost201ResponseOrderInformationAmountDetails
     */
    public function getAmountDetails()
    {
        return $this->container['amountDetails'];
    }

    /**
     * Sets amountDetails
     * @param \CyberSource\Model\RiskV1DecisionsPost201ResponseOrderInformationAmountDetails $amountDetails
     * @return $this
     */
    public function setAmountDetails($amountDetails)
    {
        $this->container['amountDetails'] = $amountDetails;

        return $this;
    }

    /**
     * Gets billTo
     * @return \CyberSource\Model\Vasv2taxOrderInformationBillTo
     */
    public function getBillTo()
    {
        return $this->container['billTo'];
    }

    /**
     * Sets billTo
     * @param \CyberSource\Model\Vasv2taxOrderInformationBillTo $billTo
     * @return $this
     */
    public function setBillTo($billTo)
    {
        $this->container['billTo'] = $billTo;

        return $this;
    }

    /**
     * Gets shippingDetails
     * @return \CyberSource\Model\Vasv2taxOrderInformationShippingDetails
     */
    public function getShippingDetails()
    {
        return $this->container['shippingDetails'];
    }

    /**
     * Sets shippingDetails
     * @param \CyberSource\Model\Vasv2taxOrderInformationShippingDetails $shippingDetails
     * @return $this
     */
    public function setShippingDetails($shippingDetails)
    {
        $this->container['shippingDetails'] = $shippingDetails;

        return $this;
    }

    /**
     * Gets shipTo
     * @return \CyberSource\Model\Vasv2taxOrderInformationShipTo
     */
    public function getShipTo()
    {
        return $this->container['shipTo'];
    }

    /**
     * Sets shipTo
     * @param \CyberSource\Model\Vasv2taxOrderInformationShipTo $shipTo
     * @return $this
     */
    public function setShipTo($shipTo)
    {
        $this->container['shipTo'] = $shipTo;

        return $this;
    }

    /**
     * Gets lineItems
     * @return \CyberSource\Model\Vasv2taxOrderInformationLineItems[]
     */
    public function getLineItems()
    {
        return $this->container['lineItems'];
    }

    /**
     * Sets lineItems
     * @param \CyberSource\Model\Vasv2taxOrderInformationLineItems[] $lineItems
     * @return $this
     */
    public function setLineItems($lineItems)
    {
        $this->container['lineItems'] = $lineItems;

        return $this;
    }

    /**
     * Gets invoiceDetails
     * @return \CyberSource\Model\Vasv2taxOrderInformationInvoiceDetails
     */
    public function getInvoiceDetails()
    {
        return $this->container['invoiceDetails'];
    }

    /**
     * Sets invoiceDetails
     * @param \CyberSource\Model\Vasv2taxOrderInformationInvoiceDetails $invoiceDetails
     * @return $this
     */
    public function setInvoiceDetails($invoiceDetails)
    {
        $this->container['invoiceDetails'] = $invoiceDetails;

        return $this;
    }

    /**
     * Gets orderAcceptance
     * @return \CyberSource\Model\Vasv2taxOrderInformationOrderAcceptance
     */
    public function getOrderAcceptance()
    {
        return $this->container['orderAcceptance'];
    }

    /**
     * Sets orderAcceptance
     * @param \CyberSource\Model\Vasv2taxOrderInformationOrderAcceptance $orderAcceptance
     * @return $this
     */
    public function setOrderAcceptance($orderAcceptance)
    {
        $this->container['orderAcceptance'] = $orderAcceptance;

        return $this;
    }

    /**
     * Gets orderOrigin
     * @return \CyberSource\Model\Vasv2taxOrderInformationOrderOrigin
     */
    public function getOrderOrigin()
    {
        return $this->container['orderOrigin'];
    }

    /**
     * Sets orderOrigin
     * @param \CyberSource\Model\Vasv2taxOrderInformationOrderOrigin $orderOrigin
     * @return $this
     */
    public function setOrderOrigin($orderOrigin)
    {
        $this->container['orderOrigin'] = $orderOrigin;

        return $this;
    }
    /**
     * Returns true if offset exists. False otherwise.
     * @param  integer $offset Offset
     * @return boolean
     */
    public function offsetExists($offset)
    {
        return isset($this->container[$offset]);
    }

    /**
     * Gets offset.
     * @param  integer $offset Offset
     * @return mixed
     */
    public function offsetGet($offset)
    {
        return isset($this->container[$offset]) ? $this->container[$offset] : null;
    }

    /**
     * Sets value based on offset.
     * @param  integer $offset Offset
     * @param  mixed   $value  Value to be set
     * @return void
     */
    public function offsetSet($offset, $value)
    {
        if (is_null($offset)) {
            $this->container[] = $value;
        } else {
            $this->container[$offset] = $value;
        }
    }

    /**
     * Unsets offset.
     * @param  integer $offset Offset
     * @return void
     */
    public function offsetUnset($offset)
    {
        unset($this->container[$offset]);
    }

    /**
     * Gets the string presentation of the object
     * @return string
     */
    public function __toString()
    {
        if (defined('JSON_PRETTY_PRINT')) { // use JSON pretty print
            return json_encode(\CyberSource\ObjectSerializer::sanitizeForSerialization($this), JSON_PRETTY_PRINT);
        }

        return json_encode(\CyberSource\ObjectSerializer::sanitizeForSerialization($this));
    }
}


