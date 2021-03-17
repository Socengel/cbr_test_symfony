<?php

namespace App\Entity;

use App\Repository\CurrencyRatesRepository;
use DateTime;
use Doctrine\ORM\Mapping as ORM;


/**
 * @ORM\Entity(repositoryClass=CurrencyRatesRepository::class)
 */
class CurrencyRates
{
    /**
     * Integer Set numbers after comma to save, cannot be zero
     */
    const PRECISION = 4;
    /**
     * @var DateTime $updatedAt
     *
     * @ORM\Column(type="datetime", nullable = true)
     */
    protected $updatedAt;
    /**
     * @ORM\Id
     * @ORM\Column(type="string", length=3)
     */
    private $name;
    /**
     * @ORM\Column(type="integer")
     */
    private $rate;

    /**
     * @return DateTime
     */
    public function getUpdatedAt(): DateTime
    {
        return $this->updatedAt;
    }

    /**
     * @param DateTime $updatedAt
     */
    public function setUpdatedAt(DateTime $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }

    /**
     * @return array
     */
    public function getArray(): array
    {
        return [
            'name' => $this->getName(),
            'rate' => $this->getRate()
        ];
    }

    /**
     * Return name of
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Set name of currency
     * @param string $name ISO 4217 Code
     * @return $this
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Return rate of currency
     * @return float
     */
    public function getRate(): float
    {
        return $this->rate / pow(10, CurrencyRates::PRECISION);
    }

    /**
     * Set rate for currency
     * @param float $rate
     * @return $this
     */
    public function setRate(float $rate): self
    {
        $this->rate = ceil($rate * pow(10, CurrencyRates::PRECISION));
        return $this;
    }

}
