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
     * @ORM\Column(type="string", length=255, nullable=true)
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

    /**
     * @ORM\Column(type="datetime")
     */
    private $startTime;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="webinars")
     * @ORM\JoinColumn(nullable=false)
     */
    private $host;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $visioLink;

    /**
     * @ORM\OneToMany(targetEntity=WebinarQuestions::class, mappedBy="webinar")
     */
    private $webinarQuestions;

    /**
     * @ORM\OneToMany(targetEntity=Message::class, mappedBy="webinar")
     */
    private $messages;

    /**
     * @ORM\OneToMany(targetEntity=Feedback::class, mappedBy="webinar")
     */
    private $feedback;

    public function __construct()
    {
        $this->reservedBy = new ArrayCollection();
        $this->webinarQuestions = new ArrayCollection();
        $this->messages = new ArrayCollection();
        $this->feedback = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->title;
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

    public function getStartTime(): ?\DateTimeInterface
    {
        return $this->startTime;
    }

    public function setStartTime(\DateTimeInterface $startTime): self
    {
        $this->startTime = $startTime;

        return $this;
    }

    public function getHost(): ?User
    {
        return $this->host;
    }

    public function setHost(?User $host): self
    {
        $this->host = $host;

        return $this;
    }

    public function getVisioLink(): ?string
    {
        return $this->visioLink;
    }

    public function setVisioLink(string $visioLink): self
    {
        $this->visioLink = $visioLink;

        return $this;
    }

    /**
     * @return Collection|WebinarQuestions[]
     */
    public function getWebinarQuestions(): Collection
    {
        return $this->webinarQuestions;
    }

    public function addWebinarQuestion(WebinarQuestions $webinarQuestion): self
    {
        if (!$this->webinarQuestions->contains($webinarQuestion)) {
            $this->webinarQuestions[] = $webinarQuestion;
            $webinarQuestion->setWebinar($this);
        }

        return $this;
    }

    public function removeWebinarQuestion(WebinarQuestions $webinarQuestion): self
    {
        if ($this->webinarQuestions->removeElement($webinarQuestion)) {
            // set the owning side to null (unless already changed)
            if ($webinarQuestion->getWebinar() === $this) {
                $webinarQuestion->setWebinar(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Message[]
     */
    public function getMessages(): Collection
    {
        return $this->messages;
    }

    public function addMessage(Message $message): self
    {
        if (!$this->messages->contains($message)) {
            $this->messages[] = $message;
            $message->setWebinar($this);
        }

        return $this;
    }

    public function removeMessage(Message $message): self
    {
        if ($this->messages->removeElement($message)) {
            // set the owning side to null (unless already changed)
            if ($message->getWebinar() === $this) {
                $message->setWebinar(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Feedback[]
     */
    public function getFeedback(): Collection
    {
        return $this->feedback;
    }

    public function addFeedback(Feedback $feedback): self
    {
        if (!$this->feedback->contains($feedback)) {
            $this->feedback[] = $feedback;
            $feedback->setWebinar($this);
        }

        return $this;
    }

    public function removeFeedback(Feedback $feedback): self
    {
        if ($this->feedback->removeElement($feedback)) {
            // set the owning side to null (unless already changed)
            if ($feedback->getWebinar() === $this) {
                $feedback->setWebinar(null);
            }
        }

        return $this;
    }
}
