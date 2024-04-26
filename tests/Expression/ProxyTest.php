<?php

namespace goodben\banking\Core\Test\Expression;

use PHPUnit\Framework\TestCase;
use goodben\banking\Core\Test\Operation;
use goodben\banking\Core\Expression\Proxy;


class ProxyTest extends TestCase {

    public function testSetMethod() {
        $r = new Operation();
        $proxy = new Proxy($r);
        $proxy->operationCode = "SCHM02";

        $this->assertEquals($r->getOperationCode(),'SCHM02');
    }

    public function testGetMethod() {
        $r = new Operation("SCHM02");
        $proxy = new Proxy($r);

        $this->assertEquals($proxy->operationCode,'SCHM02');
    }

    public function testSetMethodException() {
        $r = new Operation();
        $proxy = new Proxy($r);

        $this->expectException(\BadMethodCallException::class);

        $proxy->foo = "foo";
    }

    public function testGetMethodException() {
        $r = new Operation();
        $r->setOperationCode('foo');
        $proxy = new Proxy($r);

        $this->expectException(\BadMethodCallException::class);

        $proxy->foo;
    }
}