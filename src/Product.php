<?php

namespace App;

class Product
{
    public string $title;
    public float $price;
    public string $imageUrl;
    public int $capacityMB;
    public string $colour;
    public string $availabilityText;
    public bool $isAvailable;
    public ?string $shippingText;
    public ?string $shippingDate;


    public function getTitle(): string
    {
        return $this->title;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function getImageUrl(): string
    {
        return $this->imageUrl;
    }

    public function getCapacityMB(): int
    {
        return $this->capacityMB;
    }

    public function getColour(): string
    {
        return $this->colour;
    }

    public function getAvailabilityText(): string
    {
        return $this->availabilityText;
    }

    public function isAvailable(): bool
    {
        return $this->isAvailable;
    }

    public function getShippingText(): ?string
    {
        return $this->shippingText;
    }

    public function getShippingDate(): ?string
    {
        return $this->shippingDate;
    }


    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function setPrice(string $price): void
    {
        // Remove the pound symbol and any non-numeric characters
        $cleanedPrice = (float) preg_replace('/[^\d.]/', '', $price);

        $this->price = $cleanedPrice;
    }

    public function setImageUrl(string $imageUrl): void
    {
        $this->imageUrl = $imageUrl;
    }

    public function setCapacityMB(int $capacityMB): void
    {
        $this->capacityMB = $capacityMB;
    }

    public function setColour(string $colour): void
    {
        $this->colour = $colour;
    }

    public function setAvailabilityText(string $availabilityText): void
    {
        $this->availabilityText = $availabilityText;
    }

    public function setIsAvailable(bool $isAvailable): void
    {
        $this->isAvailable = $isAvailable;
    }

    public function setShippingText(?string $shippingText): void
    {
        $this->shippingText = $shippingText;
    }

    public function setShippingDate(?string $shippingDate): void
    {
        $this->shippingDate = $shippingDate;
    }

    public function generateProductIdentifier(): string
    {
        return sprintf(
            '%s-%s-%s-%s',
            $this->getTitle(),
            $this->getColour(),
            $this->getCapacityMB(),
            $this->getPrice()
        );
    }
}
