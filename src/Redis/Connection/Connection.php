<?php
/**
 * This class provides functionality of handling redis server connection
 */
declare (strict_types=1);

namespace Maleficarum\Redis\Connection;

class Connection {

    /* ------------------------------------ Class Property START --------------------------------------- */

    /**
     * Internal storage for redis connection
     *
     * @var \Redis
     */
    private $connection;

    /**
     * Internal storage for host
     *
     * @var string
     */
    private $host;

    /**
     * Internal storage for port
     *
     * @var string
     */
    private $port;

    /**
     * Internal storage for password
     *
     * @var string
     */
    private $password;

    /* ------------------------------------ Class Property END ----------------------------------------- */

    /* ------------------------------------ Magic methods START ---------------------------------------- */

    /**
     * Connection constructor.
     *
     * @param \Redis $connection
     * @param string $host
     * @param int $port
     * @param string $password
     */
    public function __construct(\Redis $connection, string $host, int $port, string $password = '') {
        $this->connection = $connection;
        $this->host = $host;
        $this->port = $port;
        $this->password = $password;
    }

    /**
     * Connection destructor.
     */
    public function __destruct() {
        if ($this->connection->isConnected()) {
            $this->connection->close();
        }
    }

    /**
     * Forward method call to the redis object
     *
     * @param string $method
     * @param array $arguments
     *
     * @return mixed
     * @throws \LogicException
     */
    public function __call(string $method, array $arguments) {
        $connection = $this->connection;

        if (!$connection->isConnected()) {
            throw new \LogicException(sprintf('Cannot call method before connection initialization. \%s::__call()', $method, static::class));
        }

        if (!method_exists($connection, $method)) {
            throw new \LogicException(sprintf('Method "%s" does not exist. \%s::__call()', $method, static::class));
        }

        return call_user_func_array([$connection, $method], $arguments);
    }

    /* ------------------------------------ Magic methods END ------------------------------------------ */

    /* ------------------------------------ Connection methods START ----------------------------------- */

    /**
     * Connect to the redis server
     *
     * @return \Maleficarum\Redis\Connection\Connection
     */
    public function connect(): \Maleficarum\Redis\Connection\Connection {
        $connection = $this->connection;

        if ($connection->isConnected()) {
            return $this;
        }

        $connection->connect($this->host, $this->port);

        if (!empty($this->password)) {
            $connection->auth($this->password);
        }

        return $this;
    }

    /* ------------------------------------ Connection methods END ------------------------------------- */
}
