<?php

namespace App\Entity;

use App\Repository\RequestHistoryRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RequestHistoryRepository::class)]
#[ORM\Table(name: '`requests_history`')]
#[ORM\Index(name: 'request_date_index', columns: ['request_date'])]
class RequestHistory
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $request_date = null;

    #[ORM\Column]
    private array $request_data = [];

    #[ORM\Column]
    private ?int $user_id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $ip_address = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRequestDate(): ?\DateTimeImmutable
    {
        return $this->request_date;
    }

    public function setRequestDate(\DateTimeImmutable $request_date): static
    {
        $this->request_date = $request_date;

        return $this;
    }

    public function getRequestData(): array
    {
        return $this->request_data;
    }

    public function setRequestData(array $request_data): static
    {
        $this->request_data = $request_data;

        return $this;
    }

    public function getUserId(): ?int
    {
        return $this->user_id;
    }

    public function setUserId(int $user_id): static
    {
        $this->user_id = $user_id;

        return $this;
    }

    public function getIpAddress(): ?string
    {
        return $this->ip_address;
    }

    public function setIpAddress(?string $ip_address): static
    {
        $this->ip_address = $ip_address;

        return $this;
    }
}
