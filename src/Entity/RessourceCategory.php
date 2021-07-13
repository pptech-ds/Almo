<?php

namespace App\Entity;

use App\Repository\RessourceCategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass=RessourceCategoryRepository::class)
 */
class RessourceCategory
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
     * @ORM\ManyToOne(targetEntity=RessourceCategory::class, inversedBy="ressourceCategories")
     */
    private $parent;

    /**
     * @ORM\OneToMany(targetEntity=RessourceCategory::class, mappedBy="parent")
     */
    private $ressourceCategories;

    /**
     * @ORM\OneToMany(targetEntity=Ressource::class, mappedBy="ressourceCategory")
     */
    private $ressources;

    public function __construct()
    {
        $this->ressourceCategories = new ArrayCollection();
        $this->ressources = new ArrayCollection();
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
    public function getRessourceCategories(): Collection
    {
        return $this->ressourceCategories;
    }

    public function addRessourceCategory(self $ressourceCategory): self
    {
        if (!$this->ressourceCategories->contains($ressourceCategory)) {
            $this->ressourceCategories[] = $ressourceCategory;
            $ressourceCategory->setParent($this);
        }

        return $this;
    }

    public function removeRessourceCategory(self $ressourceCategory): self
    {
        if ($this->ressourceCategories->removeElement($ressourceCategory)) {
            // set the owning side to null (unless already changed)
            if ($ressourceCategory->getParent() === $this) {
                $ressourceCategory->setParent(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Ressource[]
     */
    public function getRessources(): Collection
    {
        return $this->ressources;
    }

    public function addRessource(Ressource $ressource): self
    {
        if (!$this->ressources->contains($ressource)) {
            $this->ressources[] = $ressource;
            $ressource->setRessourceCategory($this);
        }

        return $this;
    }

    public function removeRessource(Ressource $ressource): self
    {
        if ($this->ressources->removeElement($ressource)) {
            // set the owning side to null (unless already changed)
            if ($ressource->getRessourceCategory() === $this) {
                $ressource->setRessourceCategory(null);
            }
        }

        return $this;
    }
}
