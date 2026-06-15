<?php
/**
 * Lightweight service container.
 *
 * @package AlternatePro\Elements
 */

namespace AlternatePro\Elements;

defined( 'ABSPATH' ) || exit;

/**
 * Stores shared plugin services.
 */
final class Container {
	/**
	 * Registered services.
	 *
	 * @var array<string,mixed>
	 */
	private $services = array();

	/**
	 * Register a shared service.
	 *
	 * @param string $id Service ID.
	 * @param mixed  $service Service instance.
	 * @return void
	 */
	public function set( $id, $service ) {
		$this->services[ sanitize_key( $id ) ] = $service;
	}

	/**
	 * Check whether a service exists.
	 *
	 * @param string $id Service ID.
	 * @return bool
	 */
	public function has( $id ) {
		return array_key_exists( sanitize_key( $id ), $this->services );
	}

	/**
	 * Retrieve a service.
	 *
	 * @param string $id Service ID.
	 * @return mixed|null
	 */
	public function get( $id ) {
		$id = sanitize_key( $id );

		return $this->has( $id ) ? $this->services[ $id ] : null;
	}

	/**
	 * Return all services.
	 *
	 * @return array<string,mixed>
	 */
	public function all() {
		return $this->services;
	}
}
