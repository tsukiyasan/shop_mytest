<?php
/**
 * Ptsv2paymentsRiskInformationBuyerHistory
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
 * Ptsv2paymentsRiskInformationBuyerHistory Class Doc Comment
 *
 * @category    Class
 * @package     CyberSource
 * @author      Swagger Codegen team
 * @link        https://github.com/swagger-api/swagger-codegen
 */
class Ptsv2paymentsRiskInformationBuyerHistory implements ArrayAccess
{
    const DISCRIMINATOR = null;

    /**
      * The original name of the model.
      * @var string
      */
    protected static $swaggerModelName = 'ptsv2payments_riskInformation_buyerHistory';

    /**
      * Array of property to type mappings. Used for (de)serialization
      * @var string[]
      */
    protected static $swaggerTypes = [
        'customerAccount' => '\CyberSource\Model\Ptsv2paymentsRiskInformationBuyerHistoryCustomerAccount',
        'accountHistory' => '\CyberSource\Model\Ptsv2paymentsRiskInformationBuyerHistoryAccountHistory',
        'accountPurchases' => 'int',
        'addCardAttempts' => 'int',
        'priorSuspiciousActivity' => 'bool',
        'paymentAccountHistory' => 'string',
        'paymentAccountDate' => 'int',
        'transactionCountDay' => 'int',
        'transactionCountYear' => 'int'
    ];

    /**
      * Array of property to format mappings. Used for (de)serialization
      * @var string[]
      */
    protected static $swaggerFormats = [
        'customerAccount' => null,
        'accountHistory' => null,
        'accountPurchases' => null,
        'addCardAttempts' => null,
        'priorSuspiciousActivity' => null,
        'paymentAccountHistory' => null,
        'paymentAccountDate' => null,
        'transactionCountDay' => null,
        'transactionCountYear' => null
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
        'customerAccount' => 'customerAccount',
        'accountHistory' => 'accountHistory',
        'accountPurchases' => 'accountPurchases',
        'addCardAttempts' => 'addCardAttempts',
        'priorSuspiciousActivity' => 'priorSuspiciousActivity',
        'paymentAccountHistory' => 'paymentAccountHistory',
        'paymentAccountDate' => 'paymentAccountDate',
        'transactionCountDay' => 'transactionCountDay',
        'transactionCountYear' => 'transactionCountYear'
    ];


    /**
     * Array of attributes to setter functions (for deserialization of responses)
     * @var string[]
     */
    protected static $setters = [
        'customerAccount' => 'setCustomerAccount',
        'accountHistory' => 'setAccountHistory',
        'accountPurchases' => 'setAccountPurchases',
        'addCardAttempts' => 'setAddCardAttempts',
        'priorSuspiciousActivity' => 'setPriorSuspiciousActivity',
        'paymentAccountHistory' => 'setPaymentAccountHistory',
        'paymentAccountDate' => 'setPaymentAccountDate',
        'transactionCountDay' => 'setTransactionCountDay',
        'transactionCountYear' => 'setTransactionCountYear'
    ];


    /**
     * Array of attributes to getter functions (for serialization of requests)
     * @var string[]
     */
    protected static $getters = [
        'customerAccount' => 'getCustomerAccount',
        'accountHistory' => 'getAccountHistory',
        'accountPurchases' => 'getAccountPurchases',
        'addCardAttempts' => 'getAddCardAttempts',
        'priorSuspiciousActivity' => 'getPriorSuspiciousActivity',
        'paymentAccountHistory' => 'getPaymentAccountHistory',
        'paymentAccountDate' => 'getPaymentAccountDate',
        'transactionCountDay' => 'getTransactionCountDay',
        'transactionCountYear' => 'getTransactionCountYear'
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
        $this->container['customerAccount'] = isset($data['customerAccount']) ? $data['customerAccount'] : null;
        $this->container['accountHistory'] = isset($data['accountHistory']) ? $data['accountHistory'] : null;
        $this->container['accountPurchases'] = isset($data['accountPurchases']) ? $data['accountPurchases'] : null;
        $this->container['addCardAttempts'] = isset($data['addCardAttempts']) ? $data['addCardAttempts'] : null;
        $this->container['priorSuspiciousActivity'] = isset($data['priorSuspiciousActivity']) ? $data['priorSuspiciousActivity'] : null;
        $this->container['paymentAccountHistory'] = isset($data['paymentAccountHistory']) ? $data['paymentAccountHistory'] : null;
        $this->container['paymentAccountDate'] = isset($data['paymentAccountDate']) ? $data['paymentAccountDate'] : null;
        $this->container['transactionCountDay'] = isset($data['transactionCountDay']) ? $data['transactionCountDay'] : null;
        $this->container['transactionCountYear'] = isset($data['transactionCountYear']) ? $data['transactionCountYear'] : null;
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
     * Gets customerAccount
     * @return \CyberSource\Model\Ptsv2paymentsRiskInformationBuyerHistoryCustomerAccount
     */
    public function getCustomerAccount()
    {
        return $this->container['customerAccount'];
    }

    /**
     * Sets customerAccount
     * @param \CyberSource\Model\Ptsv2paymentsRiskInformationBuyerHistoryCustomerAccount $customerAccount
     * @return $this
     */
    public function setCustomerAccount($customerAccount)
    {
        $this->container['customerAccount'] = $customerAccount;

        return $this;
    }

    /**
     * Gets accountHistory
     * @return \CyberSource\Model\Ptsv2paymentsRiskInformationBuyerHistoryAccountHistory
     */
    public function getAccountHistory()
    {
        return $this->container['accountHistory'];
    }

    /**
     * Sets accountHistory
     * @param \CyberSource\Model\Ptsv2paymentsRiskInformationBuyerHistoryAccountHistory $accountHistory
     * @return $this
     */
    public function setAccountHistory($accountHistory)
    {
        $this->container['accountHistory'] = $accountHistory;

        return $this;
    }

    /**
     * Gets accountPurchases
     * @return int
     */
    public function getAccountPurchases()
    {
        return $this->container['accountPurchases'];
    }

    /**
     * Sets accountPurchases
     * @param int $accountPurchases Number of purchases with this cardholder account during the previous six months. Recommended for Discover ProtectBuy.
     * @return $this
     */
    public function setAccountPurchases($accountPurchases)
    {
        $this->container['accountPurchases'] = $accountPurchases;

        return $this;
    }

    /**
     * Gets addCardAttempts
     * @return int
     */
    public function getAddCardAttempts()
    {
        return $this->container['addCardAttempts'];
    }

    /**
     * Sets addCardAttempts
     * @param int $addCardAttempts Number of add card attempts in the last 24 hours. Recommended for Discover ProtectBuy.
     * @return $this
     */
    public function setAddCardAttempts($addCardAttempts)
    {
        $this->container['addCardAttempts'] = $addCardAttempts;

        return $this;
    }

    /**
     * Gets priorSuspiciousActivity
     * @return bool
     */
    public function getPriorSuspiciousActivity()
    {
        return $this->container['priorSuspiciousActivity'];
    }

    /**
     * Sets priorSuspiciousActivity
     * @param bool $priorSuspiciousActivity Indicates whether the merchant experienced suspicious activity (including previous fraud) on the account. Recommended for Discover ProtectBuy.
     * @return $this
     */
    public function setPriorSuspiciousActivity($priorSuspiciousActivity)
    {
        $this->container['priorSuspiciousActivity'] = $priorSuspiciousActivity;

        return $this;
    }

    /**
     * Gets paymentAccountHistory
     * @return string
     */
    public function getPaymentAccountHistory()
    {
        return $this->container['paymentAccountHistory'];
    }

    /**
     * Sets paymentAccountHistory
     * @param string $paymentAccountHistory This only applies for NEW_ACCOUNT and EXISTING_ACCOUNT in creationHistory. Possible values are: - PAYMENT_ACCOUNT_EXISTS - PAYMENT_ACCOUNT_ADDED_NOW
     * @return $this
     */
    public function setPaymentAccountHistory($paymentAccountHistory)
    {
        $this->container['paymentAccountHistory'] = $paymentAccountHistory;

        return $this;
    }

    /**
     * Gets paymentAccountDate
     * @return int
     */
    public function getPaymentAccountDate()
    {
        return $this->container['paymentAccountDate'];
    }

    /**
     * Sets paymentAccountDate
     * @param int $paymentAccountDate Date applicable only for PAYMENT_ACCOUNT_EXISTS in paymentAccountHistory
     * @return $this
     */
    public function setPaymentAccountDate($paymentAccountDate)
    {
        $this->container['paymentAccountDate'] = $paymentAccountDate;

        return $this;
    }

    /**
     * Gets transactionCountDay
     * @return int
     */
    public function getTransactionCountDay()
    {
        return $this->container['transactionCountDay'];
    }

    /**
     * Sets transactionCountDay
     * @param int $transactionCountDay Number of transaction (successful or abandoned) for this cardholder account within the last 24 hours. Recommended for Discover ProtectBuy.
     * @return $this
     */
    public function setTransactionCountDay($transactionCountDay)
    {
        $this->container['transactionCountDay'] = $transactionCountDay;

        return $this;
    }

    /**
     * Gets transactionCountYear
     * @return int
     */
    public function getTransactionCountYear()
    {
        return $this->container['transactionCountYear'];
    }

    /**
     * Sets transactionCountYear
     * @param int $transactionCountYear Number of transaction (successful or abandoned) for this cardholder account within the last year. Recommended for Discover ProtectBuy.
     * @return $this
     */
    public function setTransactionCountYear($transactionCountYear)
    {
        $this->container['transactionCountYear'] = $transactionCountYear;

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


