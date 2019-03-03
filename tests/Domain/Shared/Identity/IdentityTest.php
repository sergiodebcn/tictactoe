<?php

declare(strict_types=1);

namespace App\Domain\Shared\Identity;

use PHPUnit\Framework\TestCase;

class IdentityTest extends TestCase
{
    public function testItShouldReturnSetValue()
    {
        $value = 'identityString';
        $identity = TestableIdentity::instance($value);

        $this->assertSame($value, $identity->value());

        $valueWithWhitespace = ' identityString';
        $identity = TestableIdentity::instance($valueWithWhitespace);

        $this->assertNotSame($valueWithWhitespace, $identity->value());
        $this->assertSame($value, $identity->value());
    }

    public function testItShouldReturnStringRepresentation()
    {
        $identity = TestableIdentity::instance('this is a string representation');

        $this->assertEquals($identity, 'this is a string representation');
    }

    public function testItShouldReturnTrueWhenIdentitiesAreEquals()
    {
        $value = 'identityString';
        $identity = TestableIdentity::instance($value);
        $otherIdentity = TestableIdentity::instance($value);

        $this->assertTrue($identity->equals($otherIdentity));
    }

    public function testItShouldReturnFalseWhenIdentityAreNotTheSameClass()
    {
        $value = 'identityString';
        $identity = TestableIdentity::instance($value);
        $otherIdentity = OtherTestableIdentity::instance($value);

        $this->assertFalse($identity->equals($otherIdentity));
    }

    public function testItShouldReturnFalseWhenIdentitiesHasDifferentValues()
    {
        $identity = TestableIdentity::instance('identityString');
        $otherIdentity = TestableIdentity::instance('otherIdentityString');

        $this->assertFalse($identity->equals($otherIdentity));
    }

    public function testItShouldThrowExceptionWhenAnEmptyValueIsPassed()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('TestableIdentity is mandatory');
        TestableIdentity::instance('');
    }

    public function testItShouldBuildItselfOnce()
    {
        $this->assertSame(
            TestableIdentity::instance('an instance'),
            TestableIdentity::instance('an instance')
        );
    }
}
