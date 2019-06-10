<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\LogRepository")
 */
class Log
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $city;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $first_amount;

    /**
     * @ORM\Column(type="string", length=10)
     */
    private $exchange_currency;

    /**
     * @ORM\Column(type="integer")
     */
    private $after_exchange;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getFirstAmount(): ?int
    {
        return $this->first_amount;
    }

    public function setFirstAmount(?int $first_amount): self
    {
        $this->first_amount = $first_amount;

        return $this;
    }

    public function getExchangeCurrency(): ?string
    {
        return $this->exchange_currency;
    }

    public function setExchangeCurrency(string $exchange_currency): self
    {
        $this->exchange_currency = $exchange_currency;

        return $this;
    }

    public function getAfterExchange(): ?int
    {
        return $this->after_exchange;
    }

    public function setAfterExchange(int $after_exchange): self
    {
        $this->after_exchange = $after_exchange;

        return $this;
    }
    
    public function save($data) {
        
        if (isset($data['departure_capital'])) {
            $this->setCity($data['departure_capital']);
        }
        if (isset($data[2])) {
            $this->setFirstAmount($data[2]);
        }
        if (isset($data[1])) {
            $this->setExchangeCurrency($data[1]);
        }
        if (isset($data['cash'])) {
            $this->setAfterExchange($data['cash']);
        }
        $this->setDate(\DateTime::createFromFormat('d-m-Y H:i:s',date('d-m-Y H:i:s')));
        return $this;
    }
       /**
     * @return string
     */
    public function __toString()
    {
        return $this->getId() ? (string)$this->getName() : '-';
    }
}
