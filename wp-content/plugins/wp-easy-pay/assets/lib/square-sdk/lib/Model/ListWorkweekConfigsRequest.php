<?php
/**
 * NOTE: This class is auto generated by the swagger code generator program.
 * https://github.com/swagger-api/swagger-codegen
 * Do not edit the class manually.
 */

namespace SquareConnect\Model;

use \ArrayAccess;
/**
 * ListWorkweekConfigsRequest Class Doc Comment
 *
 * @category Class
 * @package  SquareConnect
 * @author   Square Inc.
 * @license  http://www.apache.org/licenses/LICENSE-2.0 Apache License v2
 * @link     https://squareup.com/developers
 */
class ListWorkweekConfigsRequest implements ArrayAccess {

	/**
	 * Array of property to type mappings. Used for (de)serialization
	 *
	 * @var string[]
	 */
	static $swaggerTypes = array(
		'limit'  => 'int',
		'cursor' => 'string',
	);

	/**
	 * Array of attributes where the key is the local name, and the value is the original name
	 *
	 * @var string[]
	 */
	static $attributeMap = array(
		'limit'  => 'limit',
		'cursor' => 'cursor',
	);

	/**
	 * Array of attributes to setter functions (for deserialization of responses)
	 *
	 * @var string[]
	 */
	static $setters = array(
		'limit'  => 'setLimit',
		'cursor' => 'setCursor',
	);

	/**
	 * Array of attributes to getter functions (for serialization of requests)
	 *
	 * @var string[]
	 */
	static $getters = array(
		'limit'  => 'getLimit',
		'cursor' => 'getCursor',
	);

	/**
	 * $limit Maximum number of Workweek Configs to return per page.
	 *
	 * @var int
	 */
	protected $limit;
	/**
	 * $cursor Pointer to the next page of Workweek Config results to fetch.
	 *
	 * @var string
	 */
	protected $cursor;

	/**
	 * Constructor
	 *
	 * @param mixed[] $data Associated array of property value initializing the model
	 */
	public function __construct( array $data = null ) {
		if ( $data != null ) {
			if ( isset( $data['limit'] ) ) {
				$this->limit = $data['limit'];
			} else {
				$this->limit = null;
			}
			if ( isset( $data['cursor'] ) ) {
				$this->cursor = $data['cursor'];
			} else {
				$this->cursor = null;
			}
		}
	}
	/**
	 * Gets limit
	 *
	 * @return int
	 */
	public function getLimit() {
		return $this->limit;
	}

	/**
	 * Sets limit
	 *
	 * @param int $limit Maximum number of Workweek Configs to return per page.
	 * @return $this
	 */
	public function setLimit( $limit ) {
		$this->limit = $limit;
		return $this;
	}
	/**
	 * Gets cursor
	 *
	 * @return string
	 */
	public function getCursor() {
		return $this->cursor;
	}

	/**
	 * Sets cursor
	 *
	 * @param string $cursor Pointer to the next page of Workweek Config results to fetch.
	 * @return $this
	 */
	public function setCursor( $cursor ) {
		$this->cursor = $cursor;
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
