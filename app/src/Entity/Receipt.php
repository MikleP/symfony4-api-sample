<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\MaxDepth;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ReceiptRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Receipt
{
	const STATUS_ACTIVE = 'ACTIVE';
	const STATUS_CLOSED = 'CLOSED';

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $status = self::STATUS_ACTIVE;

    /**
     * @ORM\Column(type="datetime")
	 * @MaxDepth(1)
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime")
	 * @MaxDepth(1)
     */
    private $modifiedAt;

    /**
     * @ORM\Column(type="integer")
     */
    private $userId;

    /**
	 * @var ProductEntry[]
     * @ORM\Column(type="object", nullable=true)
     */
    private $productEntries = [];

    public function getId(): ?int
    {
        return $this->id;
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

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getModifiedAt(): ?\DateTimeInterface
    {
        return $this->modifiedAt;
    }

    public function setModifiedAt(\DateTimeInterface $modifiedAt): self
    {
        $this->modifiedAt = $modifiedAt;

        return $this;
    }

    public function getUserId(): ?int
    {
        return $this->userId;
    }

    public function setUserId(int $userId): self
    {
        $this->userId = $userId;

        return $this;
    }

    public function getProductEntries(): ?array
    {
        return $this->productEntries;
    }

    public function setProductEntries(?array $productEntries): self
    {
        $this->productEntries = $productEntries;

        return $this;
    }

	public function addProductEntry(?ProductEntry $productEntry): self
	{
		//TODO: Check if Product already exist in Receipt
		array_push($this->productEntries, $productEntry);

		return $this;
    }

	/**
	 * @ORM\PrePersist
	 * @ORM\PreUpdate
	 */
	public function updatedTimestamps()
	{
		$this->setModifiedAt(new \DateTime('now'));

		if ($this->getCreatedAt() == null) {
			$this->setCreatedAt(new \DateTime('now'));
		}
	}
}
