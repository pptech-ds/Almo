<?php

namespace App\Entity;

use App\Repository\WebinarQuestionsRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=WebinarQuestionsRepository::class)
 */
class WebinarQuestions
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Webinar::class, inversedBy="webinarQuestions")
     */
    private $webinar;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="webinarQuestions")
     */
    private $user;

    /**
     * @ORM\Column(type="text")
     */
    private $question;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getWebinar(): ?Webinar
    {
        return $this->webinar;
    }

    public function setWebinar(?Webinar $webinar): self
    {
        $this->webinar = $webinar;

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

    public function getQuestion(): ?string
    {
        return $this->question;
    }

    public function setQuestion(string $question): self
    {
        $this->question = $question;

        return $this;
    }
}
