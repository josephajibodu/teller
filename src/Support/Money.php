<?php

namespace JosephAjibodu\Teller\Support;

class Money
{
    public function __construct(
        protected int $amount,
        protected string $currency = 'NGN'
    ) {}

    public static function fromKobo(int $kobo, string $currency = 'NGN'): self
    {
        return new self($kobo, $currency);
    }

    public static function fromNaira(float $naira, string $currency = 'NGN'): self
    {
        return new self((int) ($naira * 100), $currency);
    }

    public function toKobo(): int
    {
        return $this->amount;
    }

    public function toNaira(): float
    {
        return $this->amount / 100;
    }

    public function format(): string
    {
        return number_format($this->toNaira(), 2) . ' ' . $this->currency;
    }

    public function add(Money $other): self
    {
        if ($this->currency !== $other->currency) {
            throw new \InvalidArgumentException('Cannot add different currencies');
        }

        return new self($this->amount + $other->amount, $this->currency);
    }

    public function subtract(Money $other): self
    {
        if ($this->currency !== $other->currency) {
            throw new \InvalidArgumentException('Cannot subtract different currencies');
        }

        return new self($this->amount - $other->amount, $this->currency);
    }

    public function multiply(float $factor): self
    {
        return new self((int) round($this->amount * $factor), $this->currency);
    }

    public function divide(float $divisor): self
    {
        if ($divisor === 0) {
            throw new \InvalidArgumentException('Cannot divide by zero');
        }

        return new self((int) round($this->amount / $divisor), $this->currency);
    }

    public function equals(Money $other): bool
    {
        return $this->amount === $other->amount && $this->currency === $other->currency;
    }

    public function isGreaterThan(Money $other): bool
    {
        if ($this->currency !== $other->currency) {
            throw new \InvalidArgumentException('Cannot compare different currencies');
        }

        return $this->amount > $other->amount;
    }

    public function isLessThan(Money $other): bool
    {
        if ($this->currency !== $other->currency) {
            throw new \InvalidArgumentException('Cannot compare different currencies');
        }

        return $this->amount < $other->amount;
    }
}
