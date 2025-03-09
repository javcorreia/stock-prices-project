<?php

namespace App\Entity;

use App\Repository\JobLogRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: JobLogRepository::class)]
#[ORM\Table(name: '`jobs_logs`')]
#[ORM\Index(name: 'jobs_logs_job_name_idx', columns: ['job_name'])]
class JobLog
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $job_name = null;

    #[ORM\Column]
    private array $job_payload = [];

    #[ORM\Column]
    private ?bool $job_success = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $executed_at = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getJobName(): ?string
    {
        return $this->job_name;
    }

    public function setJobName(string $job_name): static
    {
        $this->job_name = $job_name;

        return $this;
    }

    public function getJobPayload(): array
    {
        return $this->job_payload;
    }

    public function setJobPayload(array $job_payload): static
    {
        $this->job_payload = $job_payload;

        return $this;
    }

    public function isJobSuccess(): ?bool
    {
        return $this->job_success;
    }

    public function setJobSuccess(bool $job_success): static
    {
        $this->job_success = $job_success;

        return $this;
    }

    public function getExecutedAt(): ?\DateTimeImmutable
    {
        return $this->executed_at;
    }

    public function setExecutedAt(\DateTimeImmutable $executed_at): static
    {
        $this->executed_at = $executed_at;

        return $this;
    }
}
