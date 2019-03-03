<?php

declare(strict_types=1);

namespace App\Domain\Shared\Identity;

abstract class Identity
{
    /** @var array */
    private static $instances = [];

    /** @var string */
    public $value;

    public function __construct(string $value)
    {
        $this->value = trim($value);

        if ('' === $this->value) {
            $this->throwInvalidArgumentException();
        }
    }

    private function throwInvalidArgumentException()
    {
        $classNameParts = explode('\\', static::class);

        throw new \InvalidArgumentException(array_pop($classNameParts).' is mandatory');
    }

    public function __toString(): string
    {
        return $this->value();
    }

    public function value(): string
    {
        return $this->value;
    }

    public function equals(Identity $identity): bool
    {
        return $this->isSameValue($identity) && $this->isSameClass($identity);
    }

    private function isSameClass(Identity $identity): bool
    {
        return static::class === get_class($identity);
    }

    private function isSameValue(Identity $identity): bool
    {
        return $this->value === $identity->value;
    }

    /**
     * @param string $value
     *
     * @return static
     */
    public static function instance(string $value)
    {
        $instanceKey = static::class.'|'.$value;

        if (!isset(self::$instances[$instanceKey])) {
            self::$instances[$instanceKey] = new static($value);
        }

        return self::$instances[$instanceKey];
    }
}
