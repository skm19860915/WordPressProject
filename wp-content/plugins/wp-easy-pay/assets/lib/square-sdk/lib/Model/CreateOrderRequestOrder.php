<?php
/**
 * CreateOrderRequestOrder
 *
 * PHP version 5
 *
 * @category Class
 * @package  SquareConnect
 * @author   http://github.com/swagger-api/swagger-codegen
 * @license  http://www.apache.org/licenses/LICENSE-2.0 Apache Licene v2
 * @link     https://github.com/swagger-api/swagger-codegen
 */
/**
 *  Copyright 2016 Square, Inc.
 *
 *  Licensed under the Apache License, Version 2.0 (the "License");
 *  you may not use this file except in compliance with the License.
 *  You may obtain a copy of the License at
 *
 *      http://www.apache.org/licenses/LICENSE-2.0
 *
 *  Unless required by applicable law or agreed to in writing, software
 *  distributed under the License is distributed on an "AS IS" BASIS,
 *  WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 *  See the License for the specific language governing permissions and
 *  limitations under the License.
 */
/**
 * NOTE: This class is auto generated by the swagger code generator program.
 * https://github.com/swagger-api/swagger-codegen
 * Do not edit the class manually.
 */

namespace SquareConnect\Model;

use \ArrayAccess;
/**
 * CreateOrderRequestOrder Class Doc Comment
 *
 * @category    Class
 * @description The object describes the order.
 * @package     SquareConnect
 * @author      http://github.com/swagger-api/swagger-codegen
 * @license     http://www.apache.org/licenses/LICENSE-2.0 Apache Licene v2
 * @link        https://github.com/swagger-api/swagger-codegen
 */
class CreateOrderRequestOrder implements ArrayAccess {

	/**
	 * Array of property to type mappings. Used for (de)serialization
	 *
	 * @var string[]
	 */
	static $swaggerTypes = array(
		'reference_id' => 'string',
		'line_items'   => '\SquareConnect\Model\CreateOrderRequestLineItem[]',
	);

	/**
	 * Array of attributes where the key is the local name, and the value is the original name
	 *
	 * @var string[]
	 */
	static $attributeMap = array(
		'reference_id' => 'reference_id',
		'line_items'   => 'line_items',
	);

	/**
	 * Array of attributes to setter functions (for deserialization of responses)
	 *
	 * @var string[]
	 */
	static $setters = array(
		'reference_id' => 'setReferenceId',
		'line_items'   => 'setLineItems',
	);

	/**
	 * Array of attributes to getter functions (for serialization of requests)
	 *
	 * @var string[]
	 */
	static $getters = array(
		'reference_id' => 'getReferenceId',
		'line_items'   => 'getLineItems',
	);

	/**
	 * $reference_id An optional ID you can associate with the order for your own purposes (such as to associate the order with an entity ID in your own database).  This value cannot exceed 40 characters.
	 *
	 * @var string
	 */
	protected $reference_id;
	/**
	 * $line_items The line items to associate with this order.  Each line item represents a different product (or a custom monetary amount) to include in a purchase.
	 *
	 * @var \SquareConnect\Model\CreateOrderRequestLineItem[]
	 */
	protected $line_items;

	/**
	 * Constructor
	 *
	 * @param mixed[] $data Associated array of property value initalizing the model
	 */
	public function __construct( array $data = null ) {
		if ( $data != null ) {
			$this->reference_id = $data['reference_id'];
			$this->line_items   = $data['line_items'];
		}
	}
	/**
	 * Gets reference_id
	 *
	 * @return string
	 */
	public function getReferenceId() {
		return $this->reference_id;
	}

	/**
	 * Sets reference_id
	 *
	 * @param string $reference_id An optional ID you can associate with the order for your own purposes (such as to associate the order with an entity ID in your own database).  This value cannot exceed 40 characters.
	 * @return $this
	 */
	public function setReferenceId( $reference_id ) {
		$this->reference_id = $reference_id;
		return $this;
	}
	/**
	 * Gets line_items
	 *
	 * @return \SquareConnect\Model\CreateOrderRequestLineItem[]
	 */
	public function getLineItems() {
		return $this->line_items;
	}

	/**
	 * Sets line_items
	 *
	 * @param \SquareConnect\Model\CreateOrderRequestLineItem[] $line_items The line items to associate with this order.  Each line item represents a different product (or a custom monetary amount) to include in a purchase.
	 * @return $this
	 */
	public function setLineItems( $line_items ) {
		$this->line_items = $line_items;
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
