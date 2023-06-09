<?php
/**
 * CustomerCardApi
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

namespace SquareConnect\Api;

use \SquareConnect\Configuration;
use \SquareConnect\ApiClient;
use \SquareConnect\ApiException;
use \SquareConnect\ObjectSerializer;

/**
 * CustomerCardApi Class Doc Comment
 *
 * @category Class
 * @package  SquareConnect
 * @author   http://github.com/swagger-api/swagger-codegen
 * @license  http://www.apache.org/licenses/LICENSE-2.0 Apache Licene v2
 * @link     https://github.com/swagger-api/swagger-codegen
 */
class CustomerCardApi {


	/**
	 * API Client
	 *
	 * @var \SquareConnect\ApiClient instance of the ApiClient
	 */
	protected $apiClient;

	/**
	 * Constructor
	 *
	 * @param \SquareConnect\ApiClient|null $apiClient The api client to use
	 */
	function __construct( $apiClient = null ) {
		if ( $apiClient == null ) {
			$apiClient = new ApiClient();
			$apiClient->getConfig()->setHost( 'https://connect.squareup.com' );
		}

		$this->apiClient = $apiClient;
	}

	/**
	 * Get API client
	 *
	 * @return \SquareConnect\ApiClient get the API client
	 */
	public function getApiClient() {
		return $this->apiClient;
	}

	/**
	 * Set the API client
	 *
	 * @param \SquareConnect\ApiClient $apiClient set the API client
	 * @return CustomerCardApi
	 */
	public function setApiClient( ApiClient $apiClient ) {
		$this->apiClient = $apiClient;
		return $this;
	}

	/**
	 * createCustomerCard
	 *
	 * CreateCustomerCard
	 *
	 * @param string                                         $authorization The value to provide in the Authorization header of your request. This value should follow the format &#x60;Bearer YOUR_ACCESS_TOKEN_HERE&#x60;. (required)
	 * @param string                                         $customer_id The ID of the customer to link the card on file to. (required)
	 * @param \SquareConnect\Model\CreateCustomerCardRequest $body An object containing the fields to POST for the request.  See the corresponding object definition for field details. (required)
	 * @return \SquareConnect\Model\CreateCustomerCardResponse
	 * @throws \SquareConnect\ApiException on non-2xx response
	 */
	public function createCustomerCard( $authorization, $customer_id, $body ) {
		list($response, $statusCode, $httpHeader) = $this->createCustomerCardWithHttpInfo( $authorization, $customer_id, $body );
		return $response;
	}


	/**
	 * createCustomerCardWithHttpInfo
	 *
	 * CreateCustomerCard
	 *
	 * @param string                                         $authorization The value to provide in the Authorization header of your request. This value should follow the format &#x60;Bearer YOUR_ACCESS_TOKEN_HERE&#x60;. (required)
	 * @param string                                         $customer_id The ID of the customer to link the card on file to. (required)
	 * @param \SquareConnect\Model\CreateCustomerCardRequest $body An object containing the fields to POST for the request.  See the corresponding object definition for field details. (required)
	 * @return Array of \SquareConnect\Model\CreateCustomerCardResponse, HTTP status code, HTTP response headers (array of strings)
	 * @throws \SquareConnect\ApiException on non-2xx response
	 */
	public function createCustomerCardWithHttpInfo( $authorization, $customer_id, $body ) {

		// verify the required parameter 'authorization' is set
		if ( $authorization === null ) {
			throw new \InvalidArgumentException( 'Missing the required parameter $authorization when calling createCustomerCard' );
		}
		// verify the required parameter 'customer_id' is set
		if ( $customer_id === null ) {
			throw new \InvalidArgumentException( 'Missing the required parameter $customer_id when calling createCustomerCard' );
		}
		// verify the required parameter 'body' is set
		if ( $body === null ) {
			throw new \InvalidArgumentException( 'Missing the required parameter $body when calling createCustomerCard' );
		}

		// parse inputs
		$resourcePath   = '/v2/customers/{customer_id}/cards';
		$httpBody       = '';
		$queryParams    = array();
		$headerParams   = array();
		$formParams     = array();
		$_header_accept = ApiClient::selectHeaderAccept( array( 'application/json' ) );
		if ( ! is_null( $_header_accept ) ) {
			$headerParams['Accept'] = $_header_accept;
		}
		$headerParams['Content-Type'] = ApiClient::selectHeaderContentType( array( 'application/json' ) );

		// header params
		if ( $authorization !== null ) {
			$headerParams['Authorization'] = $this->apiClient->getSerializer()->toHeaderValue( $authorization );
		}
		// path params
		if ( $customer_id !== null ) {
			$resourcePath = str_replace(
				'{' . 'customer_id' . '}',
				$this->apiClient->getSerializer()->toPathValue( $customer_id ),
				$resourcePath
			);
		}
		// default format to json
		$resourcePath = str_replace( '{format}', 'json', $resourcePath );

		// body params
		$_tempBody = null;
		if ( isset( $body ) ) {
			$_tempBody = $body;
		}

		// for model (json/xml)
		if ( isset( $_tempBody ) ) {
			$httpBody = $_tempBody; // $_tempBody is the method argument, if present
		} elseif ( count( $formParams ) > 0 ) {
			$httpBody = $formParams; // for HTTP post (form)
		}
				// make the API Call
		try {
			list($response, $statusCode, $httpHeader) = $this->apiClient->callApi(
				$resourcePath,
				'POST',
				$queryParams,
				$httpBody,
				$headerParams,
				'\SquareConnect\Model\CreateCustomerCardResponse'
			);
			if ( ! $response ) {
				return array( null, $statusCode, $httpHeader );
			}

			return array( \SquareConnect\ObjectSerializer::deserialize( $response, '\SquareConnect\Model\CreateCustomerCardResponse', $httpHeader ), $statusCode, $httpHeader );
		} catch ( ApiException $e ) {
			switch ( $e->getCode() ) {
				case 200:
					$data = \SquareConnect\ObjectSerializer::deserialize( $e->getResponseBody(), '\SquareConnect\Model\CreateCustomerCardResponse', $e->getResponseHeaders() );
					$e->setResponseObject( $data );
					break;
			}

			throw $e;
		}
	}
	/**
	 * deleteCustomerCard
	 *
	 * DeleteCustomerCard
	 *
	 * @param string $authorization The value to provide in the Authorization header of your request. This value should follow the format &#x60;Bearer YOUR_ACCESS_TOKEN_HERE&#x60;. (required)
	 * @param string $customer_id The ID of the customer that the card on file belongs to. (required)
	 * @param string $card_id The ID of the card on file to delete. (required)
	 * @return \SquareConnect\Model\DeleteCustomerCardResponse
	 * @throws \SquareConnect\ApiException on non-2xx response
	 */
	public function deleteCustomerCard( $authorization, $customer_id, $card_id ) {
		list($response, $statusCode, $httpHeader) = $this->deleteCustomerCardWithHttpInfo( $authorization, $customer_id, $card_id );
		return $response;
	}


	/**
	 * deleteCustomerCardWithHttpInfo
	 *
	 * DeleteCustomerCard
	 *
	 * @param string $authorization The value to provide in the Authorization header of your request. This value should follow the format &#x60;Bearer YOUR_ACCESS_TOKEN_HERE&#x60;. (required)
	 * @param string $customer_id The ID of the customer that the card on file belongs to. (required)
	 * @param string $card_id The ID of the card on file to delete. (required)
	 * @return Array of \SquareConnect\Model\DeleteCustomerCardResponse, HTTP status code, HTTP response headers (array of strings)
	 * @throws \SquareConnect\ApiException on non-2xx response
	 */
	public function deleteCustomerCardWithHttpInfo( $authorization, $customer_id, $card_id ) {

		// verify the required parameter 'authorization' is set
		if ( $authorization === null ) {
			throw new \InvalidArgumentException( 'Missing the required parameter $authorization when calling deleteCustomerCard' );
		}
		// verify the required parameter 'customer_id' is set
		if ( $customer_id === null ) {
			throw new \InvalidArgumentException( 'Missing the required parameter $customer_id when calling deleteCustomerCard' );
		}
		// verify the required parameter 'card_id' is set
		if ( $card_id === null ) {
			throw new \InvalidArgumentException( 'Missing the required parameter $card_id when calling deleteCustomerCard' );
		}

		// parse inputs
		$resourcePath   = '/v2/customers/{customer_id}/cards/{card_id}';
		$httpBody       = '';
		$queryParams    = array();
		$headerParams   = array();
		$formParams     = array();
		$_header_accept = ApiClient::selectHeaderAccept( array( 'application/json' ) );
		if ( ! is_null( $_header_accept ) ) {
			$headerParams['Accept'] = $_header_accept;
		}
		$headerParams['Content-Type'] = ApiClient::selectHeaderContentType( array( 'application/json' ) );

		// header params
		if ( $authorization !== null ) {
			$headerParams['Authorization'] = $this->apiClient->getSerializer()->toHeaderValue( $authorization );
		}
		// path params
		if ( $customer_id !== null ) {
			$resourcePath = str_replace(
				'{' . 'customer_id' . '}',
				$this->apiClient->getSerializer()->toPathValue( $customer_id ),
				$resourcePath
			);
		}// path params
		if ( $card_id !== null ) {
			$resourcePath = str_replace(
				'{' . 'card_id' . '}',
				$this->apiClient->getSerializer()->toPathValue( $card_id ),
				$resourcePath
			);
		}
		// default format to json
		$resourcePath = str_replace( '{format}', 'json', $resourcePath );

		// for model (json/xml)
		if ( isset( $_tempBody ) ) {
			$httpBody = $_tempBody; // $_tempBody is the method argument, if present
		} elseif ( count( $formParams ) > 0 ) {
			$httpBody = $formParams; // for HTTP post (form)
		}
				// make the API Call
		try {
			list($response, $statusCode, $httpHeader) = $this->apiClient->callApi(
				$resourcePath,
				'DELETE',
				$queryParams,
				$httpBody,
				$headerParams,
				'\SquareConnect\Model\DeleteCustomerCardResponse'
			);
			if ( ! $response ) {
				return array( null, $statusCode, $httpHeader );
			}

			return array( \SquareConnect\ObjectSerializer::deserialize( $response, '\SquareConnect\Model\DeleteCustomerCardResponse', $httpHeader ), $statusCode, $httpHeader );
		} catch ( ApiException $e ) {
			switch ( $e->getCode() ) {
				case 200:
					$data = \SquareConnect\ObjectSerializer::deserialize( $e->getResponseBody(), '\SquareConnect\Model\DeleteCustomerCardResponse', $e->getResponseHeaders() );
					$e->setResponseObject( $data );
					break;
			}

			throw $e;
		}
	}
}
