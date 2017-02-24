<?php
declare(strict_types = 1);

/**
 * Tests for the \Maleficarum\Redis\Connection class.
 */

namespace Maleficarum\Command\Tests;

class ConnectionTest extends \PHPUnit\Framework\TestCase
{
    /* ------------------------------------ Method: connect START -------------------------------------- */
    public function testNotEstablishedConnectionWithoutPassword() {
        $redis = $this
            ->getMockBuilder('Redis')
            ->setMethods(['isConnected', 'connect', 'auth'])
            ->getMock();

        $redis
            ->expects($this->exactly(2))
            ->method('isConnected')
            ->willReturn(false);

        $redis
            ->expects($this->once())
            ->method('connect')
            ->with(
                $this->equalTo('foo'),
                $this->equalTo(1)
            );

        $redis
            ->expects($this->never())
            ->method('auth');

        $connection = new \Maleficarum\Redis\Connection($redis, 'foo', 1);
        $connection->connect();
    }

    public function testNotEstablishedConnectionWithPassword() {
        $redis = $this
            ->getMockBuilder('Redis')
            ->setMethods(['isConnected', 'connect', 'auth'])
            ->getMock();

        $redis
            ->expects($this->exactly(2))
            ->method('isConnected')
            ->willReturn(false);

        $redis
            ->expects($this->once())
            ->method('connect')
            ->with(
                $this->equalTo('foo'),
                $this->equalTo(1)
            );

        $redis
            ->expects($this->once())
            ->method('auth')
            ->with(
                $this->equalTo('bar')
            );

        $connection = new \Maleficarum\Redis\Connection($redis, 'foo', 1, 'bar');
        $connection->connect();
    }

    public function testEstablishedConnection() {
        $redis = $this
            ->getMockBuilder('Redis')
            ->setMethods(['isConnected', 'connect'])
            ->getMock();

        $redis
            ->expects($this->exactly(2))
            ->method('isConnected')
            ->willReturn(true);

        $redis
            ->expects($this->never())
            ->method('connect');

        $connection = new \Maleficarum\Redis\Connection($redis, 'foo', 1, 'bar');
        $connection->connect();
    }
    /* ------------------------------------ Method: connect END ---------------------------------------- */

    /* ------------------------------------ Method: __call START --------------------------------------- */
    public function testCall() {
        $redis = $this
            ->getMockBuilder('Redis')
            ->setMethods(['isConnected', 'select'])
            ->getMock();

        $redis
            ->expects($this->exactly(2))
            ->method('isConnected')
            ->willReturn(true);
        $redis
            ->expects($this->exactly(1))
            ->method('select')
            ->with(
                $this->equalTo(1)
            )
            ->willReturn(true);

        $connection = new \Maleficarum\Redis\Connection($redis, 'foo', 1, 'bar');
        $result = $connection->select(1);
        
        $this->assertSame(true, $result);
    }

    /**
     * @expectedException \LogicException
     */
    public function testCallWithoutConnection() {
        $redis = $this
            ->getMockBuilder('Redis')
            ->setMethods(['isConnected'])
            ->getMock();

        $redis
            ->expects($this->exactly(2))
            ->method('isConnected')
            ->willReturn(false);

        $connection = new \Maleficarum\Redis\Connection($redis, 'foo', 1, 'bar');
        $connection->select(0);
    }

    /**
     * @expectedException \LogicException
     */
    public function testNonExistentMethodCall() {
        $redis = $this
            ->getMockBuilder('Redis')
            ->setMethods(['isConnected'])
            ->getMock();

        $redis
            ->expects($this->exactly(2))
            ->method('isConnected')
            ->willReturn(true);

        $connection = new \Maleficarum\Redis\Connection($redis, 'foo', 1, 'bar');
        $connection->foo();
    }
    /* ------------------------------------ Method: __call END ----------------------------------------- */
}
