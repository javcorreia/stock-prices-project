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
}
