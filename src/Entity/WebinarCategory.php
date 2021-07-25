<?php

namespace App\Entity;

use App\Entity\Webinar;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\Collection;
use App\Repository\WebinarCategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass=WebinarCategoryRepository::class)
 */
class WebinarCategory
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
     * @gedmo\Slug(fields={"name"})
     * @ORM\Column(type="string", length=255)
     */
    private $slug;

    /**
     * @ORM\ManyToOne(targetEntity=WebinarCategory::class, inversedBy="webinarCategories")
     */
    private $parent;

    /**
     * @ORM\OneToMany(targetEntity=WebinarCategory::class, mappedBy="parent")
     */
    private $webinarCategories;

    /**
     * @ORM\OneToMany(targetEntity=Webinar::class, mappedBy="webinarCategory")
     */
    private $webinars;

    /**
     * @ORM\Column(type="text")
     */
    private $description;

    public function __construct()
    {
        $this->webinarCategories = new ArrayCollection();
        $this->webinars = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->name;
    }

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

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function getParent(): ?self
    {
        return $this->parent;
    }

    public function setParent(?self $parent): self
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * @return Collection|self[]
     */
    public function getWebinarCategories(): Collection
    {
        return $this->webinarCategories;
    }

    public function addWebinarCategory(self $webinarCategory): self
    {
        if (!$this->webinarCategories->contains($webinarCategory)) {
            $this->webinarCategories[] = $webinarCategory;
            $webinarCategory->setParent($this);
        }

        return $this;
    }

    public function removeWebinarCategory(self $webinarCategory): self
    {
        if ($this->webinarCategories->removeElement($webinarCategory)) {
            // set the owning side to null (unless already changed)
            if ($webinarCategory->getParent() === $this) {
                $webinarCategory->setParent(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Webinar[]
     */
    public function getWebinars(): Collection
    {
        return $this->webinars;
    }

    public function addWebinar(Webinar $webinar): self
    {
        if (!$this->webinars->contains($webinar)) {
            $this->webinars[] = $webinar;
            $webinar->setWebinarCategory($this);
        }

        return $this;
    }

    public function removeWebinar(Webinar $webinar): self
    {
        if ($this->webinars->removeElement($webinar)) {
            // set the owning side to null (unless already changed)
            if ($webinar->getWebinarCategory() === $this) {
                $webinar->setWebinarCategory(null);
            }
        }

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }
}
