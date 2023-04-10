<?php
/**
 * NOTE: This class is auto generated by the swagger code generator program.
 * https://github.com/swagger-api/swagger-codegen
 * Do not edit the class manually.
 */

namespace SquareConnect\Model;

use \ArrayAccess;
/**
 * OrderLineItemAppliedTax Class Doc Comment
 *
 * @category Class
 * @package  SquareConnect
 * @author   Square Inc.
 * @license  http://www.apache.org/licenses/LICENSE-2.0 Apache License v2
 * @link     https://squareup.com/developers
 */
class OrderLineItemAppliedTax implements ArrayAccess {

	/**
	 * Array of property to type mappings. Used for (de)serialization
	 *
	 * @var string[]
	 */
	static $swaggerTypes = array(
		'uid'           => 'string',
		'tax_uid'       => 'string',
		'applied_money' => '\SquareConnect\Model\Money',
	);

	/**
	 * Array of attributes where the key is the local name, and the value is the original name
	 *
	 * @var string[]
	 */
	static $attributeMap = array(
		'uid'           => 'uid',
		'tax_uid'       => 'tax_uid',
		'applied_money' => 'applied_money',
	);

	/**
	 * Array of attributes to setter functions (for deserialization of responses)
	 *
	 * @var string[]
	 */
	static $setters = array(
		'uid'           => 'setUid',
		'tax_uid'       => 'setTaxUid',
		'applied_money' => 'setAppliedMoney',
	);

	/**
	 * Array of attributes to getter functions (for serialization of requests)
	 *
	 * @var string[]
	 */
	static $getters = array(
		'uid'           => 'getUid',
		'tax_uid'       => 'getTaxUid',
		'applied_money' => 'getAppliedMoney',
	);

	/**
	 * $uid Unique ID that identifies the applied tax only within this order.
	 *
	 * @var string
	 */
	protected $uid;
	/**
	 * $tax_uid The `uid` of the tax for which this applied tax represents.  Must reference a tax present in the `order.taxes` field.  This field is immutable. To change which taxes apply to a line item, delete and add new `OrderLineItemAppliedTax`s.
	 *
	 * @var string
	 */
	protected $tax_uid;
	/**
	 * $applied_money The amount of money applied by the tax to the line item.
	 *
	 * @var \SquareConnect\Model\Money
	 */
	protected $applied_money;

	/**
	 * Constructor
	 *
	 * @param mixed[] $data Associated array of property value initializing the model
	 */
	public function __construct( array $data = null ) {
		if ( $data != null ) {
			if ( isset( $data['uid'] ) ) {
				$this->uid = $data['uid'];
			} else {
				$this->uid = null;
			}
			if ( isset( $data['tax_uid'] ) ) {
				$this->tax_uid = $data['tax_uid'];
			} else {
				$this->tax_uid = null;
			}
			if ( isset( $data['applied_money'] ) ) {
				$this->applied_money = $data['applied_money'];
			} else {
				$this->applied_money = null;
			}
		}
	}
	/**
	 * Gets uid
	 *
	 * @return string
	 */
	public function getUid() {
		return $this->uid;
	}

	/**
	 * Sets uid
	 *
	 * @param string $uid Unique ID that identifies the applied tax only within this order.
	 * @return $this
	 */
	public function setUid( $uid ) {
		$this->uid = $uid;
		return $this;
	}
	/**
	 * Gets tax_uid
	 *
	 * @return string
	 */
	public function getTaxUid() {
		return $this->tax_uid;
	}

	/**
	 * Sets tax_uid
	 *
	 * @param string $tax_uid The `uid` of the tax for which this applied tax represents.  Must reference a tax present in the `order.taxes` field.  This field is immutable. To change which taxes apply to a line item, delete and add new `OrderLineItemAppliedTax`s.
	 * @return $this
	 */
	public function setTaxUid( $tax_uid ) {
		$this->tax_uid = $tax_uid;
		return $this;
	}
	/**
	 * Gets applied_money
	 *
	 * @return \SquareConnect\Model\Money
	 */
	public function getAppliedMoney() {
		return $this->applied_money;
	}

	/**
	 * Sets applied_money
	 *
	 * @param \SquareConnect\Model\Money $applied_money The amount of money applied by the tax to the line item.
	 * @return $this
	 */
	public function setAppliedMoney( $applied_money ) {
		$this->applied_money = $applied_money;
		return $this;
	}
	/**
	 * Returns true if offset exists. False otherwise.
	 *
	 * @param  integer $offset Offset
	 * @return boolean
	 */
	public function offsetExists( $offset ) {
		return isset( $this->$offset );
	}

	/**
	 * Gets offset.
	 *
	 * @param  integer $offset Offset
	 * @return mixed
	 */
	public function offsetGet( $offset ) {
		return $this->$offset;
	}

	/**
	 * Sets value based on offset.
	 *
	 * @param  integer $offset Offset
	 * @param  mixed   $value  Value to be set
	 * @return void
	 */
	public function offsetSet( $offset, $value ) {
		$this->$offset = $value;
	}

	/**
	 * Unsets offset.
	 *
	 * @param  integer $offset Offset
	 * @return void
	 */
	public function offsetUnset( $offset ) {
		unset( $this->$offset );
	}

	/**
	 * Gets the string presentation of the object
	 *
	 * @return string
	 */
	public function __toString() {
		if ( defined( 'JSON_PRETTY_PRINT' ) ) {
			return json_encode( \SquareConnect\ObjectSerializer::sanitizeForSerialization( $this ), JSON_PRETTY_PRINT );
		} else {
			return json_encode( \SquareConnect\ObjectSerializer::sanitizeForSerialization( $this ) );
		}
	}
}