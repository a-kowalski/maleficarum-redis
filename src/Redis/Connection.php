<?php
/**
 * This class provides functionality of handling redis server connection
 */

namespace Maleficarum\Redis;

class Connection
{
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
        $this->connection->close();
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
        if (!$this->connection->isConnected()) {
            throw new \LogicException(sprintf('Cannot call method before connection initialization. \%s::__call()', $method, static::class));
        }

        if (!method_exists($this->connection, $method)) {
            throw new \LogicException(sprintf('Method "%s" does not exist. \%s::__call()', $method, static::class));
        }

        return call_user_func_array([$this->connection, $method], $arguments);
    }
    /* ------------------------------------ Magic methods END ------------------------------------------ */

    /* ------------------------------------ Connection methods START ----------------------------------- */
    /**
     * Connect to the redis server
     *
     * @return \Maleficarum\Redis\Connection
     */
    public function connect() : \Maleficarum\Redis\Connection {
        if ($this->connection->isConnected()) {
            return $this;
        }

        $this
            ->connection
            ->connect($this->host, $this->port);

        if (!empty($this->password)) {
            $this
                ->connection
                ->auth($this->password);
        }

        return $this;
    }
    /* ------------------------------------ Connection methods END ------------------------------------- */
}
