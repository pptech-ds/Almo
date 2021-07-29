<?php

namespace App\Entity;

use App\Repository\ReportRepository;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass=ReportRepository::class)
 */
class Report
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="reportCreatedBy")
     * @ORM\JoinColumn(nullable=false)
     */
    private $createdBy;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="reportPatient")
     * @ORM\JoinColumn(nullable=false)
     */
    private $patient;

    /**
     * @gedmo\Slug(fields={"name"})
     * @ORM\Column(type="string", length=255)
     */
    private $slug;

    /**
     * @ORM\OneToOne(targetEntity=Disponibility::class, inversedBy="report", cascade={"persist", "remove"})
     */
    private $disponibility;

    /**
     * @ORM\Column(type="text")
     */
    private $reportPatient;

    /**
     * @ORM\Column(type="text")
     */
    private $reportAlmo;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getCreatedBy(): ?User
    {
        return $this->createdBy;
    }

    public function setCreatedBy(?User $createdBy): self
    {
        $this->createdBy = $createdBy;

        return $this;
    }

    public function getPatient(): ?User
    {
        return $this->patient;
    }

    public function setPatient(?User $patient): self
    {
        $this->patient = $patient;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getDisponibility(): ?Disponibility
    {
        return $this->disponibility;
    }

    public function setDisponibility(?Disponibility $disponibility): self
    {
        $this->disponibility = $disponibility;

        return $this;
    }

    public function getReportPatient(): ?string
    {
        return $this->reportPatient;
    }

    public function setReportPatient(string $reportPatient): self
    {
        $this->reportPatient = $reportPatient;

        return $this;
    }

    public function getReportAlmo(): ?string
    {
        return $this->reportAlmo;
    }

    public function setReportAlmo(string $reportAlmo): self
    {
        $this->reportAlmo = $reportAlmo;

        return $this;
    }
}
