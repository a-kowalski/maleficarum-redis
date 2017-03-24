<?php
/**
 * This trait provides functionality common to all classes dependant on the \Maleficarum\Redis namespace
 */
declare (strict_types=1);

namespace Maleficarum\Redis;

trait Dependant {

	/* ------------------------------------ Class Property START --------------------------------------- */

	/**
	 * Internal storage for the redis connection object.
	 *
	 * @var \Maleficarum\Redis\Connection\Connection
	 */
	protected $redisStorage = null;

	/* ------------------------------------ Class Property END ----------------------------------------- */

	/* ------------------------------------ Class Methods START ---------------------------------------- */

	/**
	 * Inject a new redis connection object.
	 *
	 * @param \Maleficarum\Redis\Connection\Connection $connection
	 * @return \Maleficarum\Redis\Dependant
	 */
	public function setRedis(\Maleficarum\Redis\Connection\Connection $connection) {
		$this->redisStorage = $connection;

		return $this;
	}

	/**
	 * Fetch the currently assigned redis connection object.
	 *
	 * @return \Maleficarum\Redis\Connection\Connection
	 */
	public function getRedis() {
		return $this->redisStorage;
	}

	/**
	 * Detach the currently assigned redis connection object.
	 *
	 * @return \Maleficarum\Redis\Dependant
	 */
	public function detachRedis() {
		$this->redisStorage = null;

		return $this;
	}

	/* ------------------------------------ Class Methods END ------------------------------------------ */

}
