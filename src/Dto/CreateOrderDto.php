<?php

namespace App\Dto;


class CreateOrderDto
{

	public array $products;

	public function __construct(array $products)
	{
		$this->products = $products;
	}

}