<?php

namespace App\UseCase;

use App\Dto\CreateOrderDto;
use App\Entity\Order;
use App\Repository\OrderItemRepository;
use Doctrine\ORM\EntityManagerInterface;

class CreateOrderService
{

	private EntityManagerInterface $entityManager;

	private OrderItemRepository $orderItemRepository;

	public function __construct(
		EntityManagerInterface $entityManager
	)
	{
		$this->entityManager = $entityManager;
	}
	

	public function createOrder(CreateOrderDto $createOrderData): Order
	{
		$order = new Order($createOrderData);

		$this->entityManager->persist($order);
		$this->entityManager->flush();

		return $order;
	}
}