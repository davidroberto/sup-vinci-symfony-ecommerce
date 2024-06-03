<?php 

namespace App\Controller;

use App\Dto\CreateOrderDto;
use App\UseCase\CreateOrderService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OrderController extends AbstractController
{
	private CreateOrderService $createOrderService;

	public function __construct(CreateOrderService $createOrderService)
	{
		$this->createOrderService = $createOrderService;
	}

    /**
     * @Route("/orders", name="create_order", methods={"POST"})
     */	
	public function createOrder(Request $request): Response
	{
		$data = json_decode($request->getContent(), true);

		$createOrderDto = new CreateOrderDto($data['products']);

		$order = $this->createOrderService->createOrder($createOrderDto);

		return new JsonResponse(serialize($order));
	}
}