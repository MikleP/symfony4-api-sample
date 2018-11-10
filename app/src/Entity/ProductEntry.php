<?php

namespace App\Entity;


class ProductEntry
{
	/**
	 * @var Product
	 */
	private $product;

	/**
	 * @var int
	 */
	private $count;

	public function getProduct(): Product
	{
		return $this->product;
	}

	public function setProduct(Product $product): self
	{
		$this->product = $product;

		return $this;
	}

	public function getCount(): int
	{
		return $this->count;
	}

	public function setCount(int $count): self
	{
		$this->count = $count;

		return $this;
	}

	public function __toArray() {
		return [
			'first' => 'la',
		];
	}
}