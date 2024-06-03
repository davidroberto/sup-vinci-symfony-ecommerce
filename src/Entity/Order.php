<?php

namespace App\Entity;

use App\Dto\CreateOrderDto;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Exception;

/**
 * @ORM\Entity(repositoryClass=OrderRepository::class)
 * @ORM\Table(name="`order`")
 */
class Order
{
    const OrderStatus = [
        'CREATED' => 'Créée',
        'PAID' => 'Payée',
        'CANCELED' => 'Annulée'
    ];
    
    public function __construct(CreateOrderDto $createOrderData ) {

        $this->items = new ArrayCollection();
        
        if ($createOrderData) {
          if (count($createOrderData->products) > 3) {
            throw new Exception("trop d'items");
          }
    
          $this->items = $this->createOrderItems($createOrderData);
          $this->createAt = new DateTime();
          $this->updatedAt = new DateTime();
          $this->customer = 'tetetete';
          $this->paidAt = null;
          $this->status =  self::OrderStatus['CREATED'];
          $this->total = 10 * count($createOrderData->products);
        }
      }
    
      private function createOrderItems(CreateOrderDto $createOrderData): ArrayCollection {
        $orderItemsToCreate = new ArrayCollection();
    
        foreach ($createOrderData->products as $product) {
            $orderItem = $this->getOrderItemWithProduct($product);
        
            if ($orderItem) {
                $orderItem->quantity += 1;
            } else {
                $orderItem = new OrderItem($product, $this);
            }
        
            $orderItemsToCreate[] = $orderItem;
        }

    
        return $orderItemsToCreate;
      }
    
      private function getOrderItemWithProduct(string $product) {

        foreach ($this->items as $item) {
            if ($item->product === $product) {
                return $item;
            }
        }
    
        return null;
      }

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createAt;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updatedAt;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $customer;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $paidAt;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $status;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $total;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $shippingAddress;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $shippingMethod;

    /**
     * @ORM\OneToMany(targetEntity=OrderItem::class, mappedBy="_order", orphanRemoval=true, cascade={"persist"})
     */
    private $items;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCreateAt()
    {
        return $this->createAt;
    }

    public function setCreateAt($createAt): self
    {
        $this->createAt = $createAt;

        return $this;
    }

    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt($updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getCustomer(): ?string
    {
        return $this->customer;
    }

    public function setCustomer(string $customer): self
    {
        $this->customer = $customer;

        return $this;
    }

    public function getPaidAt()
    {
        return $this->paidAt;
    }

    public function setPaidAt($paidAt): self
    {
        $this->paidAt = $paidAt;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getTotal(): ?float
    {
        return $this->total;
    }

    public function setTotal(?float $total): self
    {
        $this->total = $total;

        return $this;
    }

    public function getShippingAddress(): ?string
    {
        return $this->shippingAddress;
    }

    public function setShippingAddress(?string $shippingAddress): self
    {
        $this->shippingAddress = $shippingAddress;

        return $this;
    }

    public function getShippingMethod(): ?string
    {
        return $this->shippingMethod;
    }

    public function setShippingMethod(?string $shippingMethod): self
    {
        $this->shippingMethod = $shippingMethod;

        return $this;
    }

    /**
     * @return Collection<int, OrderItem>
     */
    public function getItems(): Collection
    {
        return $this->items;
    }

    public function addItem(OrderItem $item): self
    {
        if (!$this->items->contains($item)) {
            $this->items[] = $item;
            $item->setOrder($this);
        }

        return $this;
    }

    public function removeItem(OrderItem $item): self
    {
        if ($this->items->removeElement($item)) {
            // set the owning side to null (unless already changed)
            if ($item->getOrder() === $this) {
                $item->setOrder(null);
            }
        }

        return $this;
    }
}
