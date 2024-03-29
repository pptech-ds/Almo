<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @UniqueEntity(fields={"email"}, message="There is already an account with this email")
 */
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isVerified = false;

    /**
     * @ORM\OneToMany(targetEntity=Ressource::class, mappedBy="user", orphanRemoval=true)
     */
    private $ressources;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $firstname;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $lastname;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $address;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $city;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $zipcode;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $phone;

    /**
     * @ORM\ManyToOne(targetEntity=Hospital::class, inversedBy="doctor")
     */
    private $hospital;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="patients")
     */
    private $doctor;

    /**
     * @ORM\OneToMany(targetEntity=User::class, mappedBy="doctor", orphanRemoval=true)
     */
    private $patients;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $civility;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $details;

    /**
     * @ORM\OneToMany(targetEntity=Appointment::class, mappedBy="createdBy", orphanRemoval=true)
     */
    private $appointments;

    /**
     * @ORM\ManyToOne(targetEntity=Speciality::class, inversedBy="users")
     */
    private $speciality;

    /**
     * @ORM\OneToMany(targetEntity=Appointment::class, mappedBy="reservedBy", orphanRemoval=true)
     */
    private $reservations;

    /**
     * @ORM\OneToMany(targetEntity=Report::class, mappedBy="createdBy", orphanRemoval=true)
     */
    private $reportCreatedBy;

    /**
     * @ORM\OneToMany(targetEntity=Report::class, mappedBy="patient", orphanRemoval=true)
     */
    private $reportPatient;

    /**
     * @ORM\ManyToMany(targetEntity=Webinar::class, mappedBy="reservedBy", orphanRemoval=true)
     */
    private $webinarReservations;

    /**
     * @ORM\OneToMany(targetEntity=Information::class, mappedBy="user", orphanRemoval=true)
     */
    private $information;

    /**
     * @ORM\OneToMany(targetEntity=Webinar::class, mappedBy="host", orphanRemoval=true)
     */
    private $webinars;

    /**
     * @ORM\OneToMany(targetEntity=WebinarQuestions::class, mappedBy="user", orphanRemoval=true)
     */
    private $webinarQuestions;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $visioLink;

    /**
     * @ORM\OneToMany(targetEntity=Message::class, mappedBy="sender", orphanRemoval=true, orphanRemoval=true)
     */
    private $messages;

    /**
     * @ORM\OneToMany(targetEntity=Feedback::class, mappedBy="sender", orphanRemoval=true, orphanRemoval=true)
     */
    private $feedback;

    

        


    public function __construct()
    {
        $this->ressources = new ArrayCollection();
        $this->patients = new ArrayCollection();
        $this->appointments = new ArrayCollection();
        $this->reservations = new ArrayCollection();
        $this->reportCreatedBy = new ArrayCollection();
        $this->reportPatient = new ArrayCollection();
        $this->webinarReservations = new ArrayCollection();
        $this->information = new ArrayCollection();
        $this->webinars = new ArrayCollection();
        $this->webinarQuestions = new ArrayCollection();
        $this->messages = new ArrayCollection();
        $this->feedback = new ArrayCollection();
    }

    public function __toString()
    {
        // return $this->email;
        // return $this->product . ' ' . $this->product_order . ' ' . $this->quantity_order . ' ' . $this->price_order;
        return $this->civility.' '.$this->firstname.' '.$this->lastname;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): self
    {
        $this->isVerified = $isVerified;

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
            $ressource->setUser($this);
        }

        return $this;
    }

    public function removeRessource(Ressource $ressource): self
    {
        if ($this->ressources->removeElement($ressource)) {
            // set the owning side to null (unless already changed)
            if ($ressource->getUser() === $this) {
                $ressource->setUser(null);
            }
        }

        return $this;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(?string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(?string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(?string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(?string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getZipcode(): ?string
    {
        return $this->zipcode;
    }

    public function setZipcode(?string $zipcode): self
    {
        $this->zipcode = $zipcode;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getHospital(): ?Hospital
    {
        return $this->hospital;
    }

    public function setHospital(?Hospital $hospital): self
    {
        $this->hospital = $hospital;

        return $this;
    }

    public function getDoctor(): ?self
    {
        return $this->doctor;
    }

    public function setDoctor(?self $doctor): self
    {
        $this->doctor = $doctor;

        return $this;
    }

    /**
     * @return Collection|self[]
     */
    public function getPatients(): Collection
    {
        return $this->patients;
    }

    public function addPatient(self $patient): self
    {
        if (!$this->patients->contains($patient)) {
            $this->patients[] = $patient;
            $patient->setDoctor($this);
        }

        return $this;
    }

    public function removePatient(self $patient): self
    {
        if ($this->patients->removeElement($patient)) {
            // set the owning side to null (unless already changed)
            if ($patient->getDoctor() === $this) {
                $patient->setDoctor(null);
            }
        }

        return $this;
    }

    public function getCivility(): ?string
    {
        return $this->civility;
    }

    public function setCivility(string $civility): self
    {
        $this->civility = $civility;

        return $this;
    }

    public function getDetails(): ?string
    {
        return $this->details;
    }

    public function setDetails(?string $details): self
    {
        $this->details = $details;

        return $this;
    }

    /**
     * @return Collection|Appointment[]
     */
    public function getAppointments(): Collection
    {
        return $this->appointments;
    }

    public function addAppointment(Appointment $appointment): self
    {
        if (!$this->appointments->contains($appointment)) {
            $this->appointments[] = $appointment;
            $appointment->setCreatedBy($this);
        }

        return $this;
    }

    public function removeAppointment(Appointment $appointment): self
    {
        if ($this->appointments->removeElement($appointment)) {
            // set the owning side to null (unless already changed)
            if ($appointment->getCreatedBy() === $this) {
                $appointment->setCreatedBy(null);
            }
        }

        return $this;
    }

    

    public function getSpeciality(): ?Speciality
    {
        return $this->speciality;
    }

    public function setSpeciality(?Speciality $speciality): self
    {
        $this->speciality = $speciality;

        return $this;
    }

    /**
     * @return Collection|Appointment[]
     */
    public function getReservations(): Collection
    {
        return $this->reservations;
    }

    public function addReservation(Appointment $reservation): self
    {
        if (!$this->reservations->contains($reservation)) {
            $this->reservations[] = $reservation;
            $reservation->setReservedBy($this);
        }

        return $this;
    }

    public function removeReservation(Appointment $reservation): self
    {
        if ($this->reservations->removeElement($reservation)) {
            // set the owning side to null (unless already changed)
            if ($reservation->getReservedBy() === $this) {
                $reservation->setReservedBy(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Report[]
     */
    public function getReportCreatedBy(): Collection
    {
        return $this->reportCreatedBy;
    }

    public function addReportCreatedBy(Report $reportCreatedBy): self
    {
        if (!$this->reportCreatedBy->contains($reportCreatedBy)) {
            $this->reportCreatedBy[] = $reportCreatedBy;
            $reportCreatedBy->setCreatedBy($this);
        }

        return $this;
    }

    public function removeReportCreatedBy(Report $reportCreatedBy): self
    {
        if ($this->reportCreatedBy->removeElement($reportCreatedBy)) {
            // set the owning side to null (unless already changed)
            if ($reportCreatedBy->getCreatedBy() === $this) {
                $reportCreatedBy->setCreatedBy(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Report[]
     */
    public function getReportPatient(): Collection
    {
        return $this->reportPatient;
    }

    public function addReportPatient(Report $reportPatient): self
    {
        if (!$this->reportPatient->contains($reportPatient)) {
            $this->reportPatient[] = $reportPatient;
            $reportPatient->setPatient($this);
        }

        return $this;
    }

    public function removeReportPatient(Report $reportPatient): self
    {
        if ($this->reportPatient->removeElement($reportPatient)) {
            // set the owning side to null (unless already changed)
            if ($reportPatient->getPatient() === $this) {
                $reportPatient->setPatient(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Webinar[]
     */
    public function getWebinarReservations(): Collection
    {
        return $this->webinarReservations;
    }

    public function addWebinarReservation(Webinar $webinarReservation): self
    {
        if (!$this->webinarReservations->contains($webinarReservation)) {
            $this->webinarReservations[] = $webinarReservation;
            $webinarReservation->addReservedBy($this);
        }

        return $this;
    }

    public function removeWebinarReservation(Webinar $webinarReservation): self
    {
        if ($this->webinarReservations->removeElement($webinarReservation)) {
            $webinarReservation->removeReservedBy($this);
        }

        return $this;
    }

    /**
     * @return Collection|Information[]
     */
    public function getInformation(): Collection
    {
        return $this->information;
    }

    public function addInformation(Information $information): self
    {
        if (!$this->information->contains($information)) {
            $this->information[] = $information;
            $information->setUser($this);
        }

        return $this;
    }

    public function removeInformation(Information $information): self
    {
        if ($this->information->removeElement($information)) {
            // set the owning side to null (unless already changed)
            if ($information->getUser() === $this) {
                $information->setUser(null);
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
            $webinar->setHost($this);
        }

        return $this;
    }

    public function removeWebinar(Webinar $webinar): self
    {
        if ($this->webinars->removeElement($webinar)) {
            // set the owning side to null (unless already changed)
            if ($webinar->getHost() === $this) {
                $webinar->setHost(null);
            }
        }

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
            $webinarQuestion->setUser($this);
        }

        return $this;
    }

    public function removeWebinarQuestion(WebinarQuestions $webinarQuestion): self
    {
        if ($this->webinarQuestions->removeElement($webinarQuestion)) {
            // set the owning side to null (unless already changed)
            if ($webinarQuestion->getUser() === $this) {
                $webinarQuestion->setUser(null);
            }
        }

        return $this;
    }

    public function getVisioLink(): ?string
    {
        return $this->visioLink;
    }

    public function setVisioLink(?string $visioLink): self
    {
        $this->visioLink = $visioLink;

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
            $message->setSender($this);
        }

        return $this;
    }

    public function removeMessage(Message $message): self
    {
        if ($this->messages->removeElement($message)) {
            // set the owning side to null (unless already changed)
            if ($message->getSender() === $this) {
                $message->setSender(null);
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
            $feedback->setSender($this);
        }

        return $this;
    }

    public function removeFeedback(Feedback $feedback): self
    {
        if ($this->feedback->removeElement($feedback)) {
            // set the owning side to null (unless already changed)
            if ($feedback->getSender() === $this) {
                $feedback->setSender(null);
            }
        }

        return $this;
    }

    

}
