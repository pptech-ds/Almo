<?php

namespace App\Entity;

use App\Entity\WebinarCategory;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\WebinarRepository;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass=WebinarRepository::class)
 */
class Webinar
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
    private $title;

    /**
     * @gedmo\Slug(fields={"title"})
     * @ORM\Column(type="string", length=255)
     */
    private $slug;

    /**
     * @ORM\Column(type="text")
     */
    private $content;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $image;

    /**
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="boolean")
     */
    private $active;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="webinars")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity=WebinarCategory::class, inversedBy="webinars")
     * @ORM\JoinColumn(nullable=false)
     */
    private $webinarCategory;

    /**
     * @ORM\ManyToMany(targetEntity=User::class, inversedBy="webinarReservations")
     */
    private $reservedBy;

    public function __construct()
    {
        $this->reservedBy = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function getActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(bool $active): self
    {
        $this->active = $active;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getWebinarCategory(): ?WebinarCategory
    {
        return $this->webinarCategory;
    }

    public function setWebinarCategory(?WebinarCategory $webinarCategory): self
    {
        $this->webinarCategory = $webinarCategory;

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getReservedBy(): Collection
    {
        return $this->reservedBy;
    }

    public function addReservedBy(User $reservedBy): self
    {
        if (!$this->reservedBy->contains($reservedBy)) {
            $this->reservedBy[] = $reservedBy;
        }

        return $this;
    }

    public function removeReservedBy(User $reservedBy): self
    {
        $this->reservedBy->removeElement($reservedBy);

        return $this;
    }
}
