<?php

namespace Arrilot\Tests\Sessions;

use Arrilot\Sessions\Session;
use Arrilot\Sessions\SessionProvider;
use PHPUnit_Framework_TestCase;

class SessionTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        @session_start();
    }

    public function tearDown()
    {
        session_destroy();
    }

    public function testAll()
    {
        Session::set('user', 'John');
        Session::set('foo', 'bar');
        $this->assertSame(['user' => 'John', 'foo' => 'bar'], Session::all());
    }

    public function testFlash()
    {
        // 1st request
        Session::flash('user', 'John');

        // 2nd
        $this->imitateNextRequest();
        $this->assertTrue(Session::has('user'));
        $this->assertSame('John', Session::get('user'));

        // 3rd
        $this->imitateNextRequest();
        $this->assertFalse(Session::has('user'));

        // 4th
        $this->imitateNextRequest();
        $this->assertFalse(Session::has('user'));
    }

    public function testFlush()
    {
        Session::set('user', 'John');
        Session::set('foo', 'bar');
        Session::flush();
        $this->assertEmpty(Session::all());
    }

    public function testForget()
    {
        Session::set('user', 'John');
        Session::forget('user');
        $this->assertFalse(Session::has('user'));
    }

    public function testGet()
    {
        Session::put('key', 'value');
        $this->assertSame('value', Session::get('key'));
    }

    public function testGetWithDefault()
    {
        $this->assertSame('default', Session::get('key', 'default'));

        $this->assertSame('bar', Session::get('key', function () {
            return 'bar';
        }));
    }

    public function testHas()
    {
        Session::set('user', 'John');
        $this->assertTrue(Session::has('user'));
        $this->assertFalse(Session::has('foo'));
    }

    public function testKeep()
    {
        // 1st request
        Session::flash('user', 'John');
        Session::flash('foo', 'bar');

        // 2nd
        $this->imitateNextRequest();
        Session::flash('before', 'Before');
        Session::keep('user');
        Session::flash('after', 'After');

        // 3rd
        $this->imitateNextRequest();
        $this->assertSame('John', Session::get('user'));
        $this->assertFalse(Session::has('foo'));
        $this->assertSame('Before', Session::get('before'));
        $this->assertSame('After', Session::get('after'));

        // 4th
        $this->imitateNextRequest();
        $this->assertFalse(Session::has('user'));
        $this->assertFalse(Session::has('foo'));
    }

    public function testPull()
    {
        Session::set('user', 'John');
        $this->assertSame('John', Session::pull('user'));
        $this->assertSame([], $_SESSION);
    }

    public function testPush()
    {
        Session::push('user.groups', 'manager');
        $this->assertSame(['groups' => ['manager']], $_SESSION['user']);

        Session::push('user.groups', 'buyer');
        $this->assertSame(['groups' => ['manager', 'buyer']], $_SESSION['user']);
    }

    public function testPut()
    {
        Session::put('key', 'value');
        $this->assertSame('value', $_SESSION['key']);

        Session::put(['foo' => 'bar']);
        $this->assertSame('bar', $_SESSION['foo']);
    }

    public function testReflash()
    {
        // 1st request
        Session::flash('user', 'John');
        Session::flash('foo', 'bar');

        // 2nd
        $this->imitateNextRequest();
        Session::flash('before', 'Before');
        Session::reflash();
        Session::flash('after', 'After');

        // 3rd
        $this->imitateNextRequest();
        $this->assertSame('John', Session::get('user'));
        $this->assertSame('bar', Session::get('foo'));
        $this->assertSame('Before', Session::get('before'));
        $this->assertSame('After', Session::get('after'));

        // 4th
        $this->imitateNextRequest();
        $this->assertFalse(Session::has('user'));
    }

    public function testSet()
    {
        Session::set('key', 'value');
        $this->assertSame('value', $_SESSION['key']);
    }

    /**
     * Emulate next request to test flashing.
     */
    private function imitateNextRequest()
    {
        SessionProvider::register();
    }
}
