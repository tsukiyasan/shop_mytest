<?php
/**
 * PtsV2PaymentsPost201ResponseRiskInformationInfoCodes
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
 * PtsV2PaymentsPost201ResponseRiskInformationInfoCodes Class Doc Comment
 *
 * @category    Class
 * @package     CyberSource
 * @author      Swagger Codegen team
 * @link        https://github.com/swagger-api/swagger-codegen
 */
class PtsV2PaymentsPost201ResponseRiskInformationInfoCodes implements ArrayAccess
{
    const DISCRIMINATOR = null;

    /**
      * The original name of the model.
      * @var string
      */
    protected static $swaggerModelName = 'ptsV2PaymentsPost201Response_riskInformation_infoCodes';

    /**
      * Array of property to type mappings. Used for (de)serialization
      * @var string[]
      */
    protected static $swaggerTypes = [
        'velocity' => 'string[]',
        'address' => 'string[]',
        'customerList' => 'string[]',
        'deviceBehavior' => 'string[]',
        'identityChange' => 'string[]',
        'internet' => 'string[]',
        'phone' => 'string[]',
        'suspicious' => 'string[]',
        'globalVelocity' => 'string[]'
    ];

    /**
      * Array of property to format mappings. Used for (de)serialization
      * @var string[]
      */
    protected static $swaggerFormats = [
        'velocity' => null,
        'address' => null,
        'customerList' => null,
        'deviceBehavior' => null,
        'identityChange' => null,
        'internet' => null,
        'phone' => null,
        'suspicious' => null,
        'globalVelocity' => null
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
        'velocity' => 'velocity',
        'address' => 'address',
        'customerList' => 'customerList',
        'deviceBehavior' => 'deviceBehavior',
        'identityChange' => 'identityChange',
        'internet' => 'internet',
        'phone' => 'phone',
        'suspicious' => 'suspicious',
        'globalVelocity' => 'globalVelocity'
    ];


    /**
     * Array of attributes to setter functions (for deserialization of responses)
     * @var string[]
     */
    protected static $setters = [
        'velocity' => 'setVelocity',
        'address' => 'setAddress',
        'customerList' => 'setCustomerList',
        'deviceBehavior' => 'setDeviceBehavior',
        'identityChange' => 'setIdentityChange',
        'internet' => 'setInternet',
        'phone' => 'setPhone',
        'suspicious' => 'setSuspicious',
        'globalVelocity' => 'setGlobalVelocity'
    ];


    /**
     * Array of attributes to getter functions (for serialization of requests)
     * @var string[]
     */
    protected static $getters = [
        'velocity' => 'getVelocity',
        'address' => 'getAddress',
        'customerList' => 'getCustomerList',
        'deviceBehavior' => 'getDeviceBehavior',
        'identityChange' => 'getIdentityChange',
        'internet' => 'getInternet',
        'phone' => 'getPhone',
        'suspicious' => 'getSuspicious',
        'globalVelocity' => 'getGlobalVelocity'
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
        $this->container['velocity'] = isset($data['velocity']) ? $data['velocity'] : null;
        $this->container['address'] = isset($data['address']) ? $data['address'] : null;
        $this->container['customerList'] = isset($data['customerList']) ? $data['customerList'] : null;
        $this->container['deviceBehavior'] = isset($data['deviceBehavior']) ? $data['deviceBehavior'] : null;
        $this->container['identityChange'] = isset($data['identityChange']) ? $data['identityChange'] : null;
        $this->container['internet'] = isset($data['internet']) ? $data['internet'] : null;
        $this->container['phone'] = isset($data['phone']) ? $data['phone'] : null;
        $this->container['suspicious'] = isset($data['suspicious']) ? $data['suspicious'] : null;
        $this->container['globalVelocity'] = isset($data['globalVelocity']) ? $data['globalVelocity'] : null;
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
     * Gets velocity
     * @return string[]
     */
    public function getVelocity()
    {
        return $this->container['velocity'];
    }

    /**
     * Sets velocity
     * @param string[] $velocity List of information codes triggered by the order. These information codes were generated when you created the order and product velocity rules and are returned so that you can associate them with the rules.
     * @return $this
     */
    public function setVelocity($velocity)
    {
        $this->container['velocity'] = $velocity;

        return $this;
    }

    /**
     * Gets address
     * @return string[]
     */
    public function getAddress()
    {
        return $this->container['address'];
    }

    /**
     * Sets address
     * @param string[] $address Indicates a mismatch between the customer’s billing and shipping addresses.
     * @return $this
     */
    public function setAddress($address)
    {
        $this->container['address'] = $address;

        return $this;
    }

    /**
     * Gets customerList
     * @return string[]
     */
    public function getCustomerList()
    {
        return $this->container['customerList'];
    }

    /**
     * Sets customerList
     * @param string[] $customerList Indicates that customer information is associated with transactions that are either on the negative or the positive list.
     * @return $this
     */
    public function setCustomerList($customerList)
    {
        $this->container['customerList'] = $customerList;

        return $this;
    }

    /**
     * Gets deviceBehavior
     * @return string[]
     */
    public function getDeviceBehavior()
    {
        return $this->container['deviceBehavior'];
    }

    /**
     * Sets deviceBehavior
     * @param string[] $deviceBehavior Indicates the device behavior information code(s) returned from device fingerprinting.
     * @return $this
     */
    public function setDeviceBehavior($deviceBehavior)
    {
        $this->container['deviceBehavior'] = $deviceBehavior;

        return $this;
    }

    /**
     * Gets identityChange
     * @return string[]
     */
    public function getIdentityChange()
    {
        return $this->container['identityChange'];
    }

    /**
     * Sets identityChange
     * @param string[] $identityChange Indicates excessive identity changes. The threshold is variable depending on the identity elements being compared.
     * @return $this
     */
    public function setIdentityChange($identityChange)
    {
        $this->container['identityChange'] = $identityChange;

        return $this;
    }

    /**
     * Gets internet
     * @return string[]
     */
    public function getInternet()
    {
        return $this->container['internet'];
    }

    /**
     * Sets internet
     * @param string[] $internet Indicates a problem with the customer’s email address, IP address, or billing address.
     * @return $this
     */
    public function setInternet($internet)
    {
        $this->container['internet'] = $internet;

        return $this;
    }

    /**
     * Gets phone
     * @return string[]
     */
    public function getPhone()
    {
        return $this->container['phone'];
    }

    /**
     * Sets phone
     * @param string[] $phone Indicates a problem with the customer’s phone number.
     * @return $this
     */
    public function setPhone($phone)
    {
        $this->container['phone'] = $phone;

        return $this;
    }

    /**
     * Gets suspicious
     * @return string[]
     */
    public function getSuspicious()
    {
        return $this->container['suspicious'];
    }

    /**
     * Sets suspicious
     * @param string[] $suspicious Indicates that the customer provided potentially suspicious information.
     * @return $this
     */
    public function setSuspicious($suspicious)
    {
        $this->container['suspicious'] = $suspicious;

        return $this;
    }

    /**
     * Gets globalVelocity
     * @return string[]
     */
    public function getGlobalVelocity()
    {
        return $this->container['globalVelocity'];
    }

    /**
     * Sets globalVelocity
     * @param string[] $globalVelocity Indicates that the customer has a high purchase frequency.
     * @return $this
     */
    public function setGlobalVelocity($globalVelocity)
    {
        $this->container['globalVelocity'] = $globalVelocity;

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


