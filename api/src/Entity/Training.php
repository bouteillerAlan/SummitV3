<?php

namespace App\Entity;

use App\Repository\TrainingRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: TrainingRepository::class)]
class Training
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['getAllTrainings', 'getOneTraining', 'getAllUsers', 'getOneUser'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['getAllTrainings', 'getOneTraining', 'getAllUsers', 'getOneUser'])]
    #[Assert\Type(type: 'string', message: 'Name must be string')]
    #[Assert\NotBlank(message: 'Name is mandatory')]
    #[Assert\Length(min: 1, max: 255, minMessage: 'Name must be between 1 and 255 characters', maxMessage: 'Name must be between 1 and 255 characters')]
    private ?string $name = null;

    #[ORM\ManyToOne(inversedBy: 'training')]
    #[Groups(['getAllTrainings', 'getOneTraining'])]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotBlank(message: 'User is mandatory')]
    private ?User $user = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Groups(['getAllTrainings', 'getOneTraining', 'getAllUsers', 'getOneUser'])]
    // fixme: this assert throw string error for some reason
    //#[Assert\DateTime(message: 'Date must be a DateTime')]
    private ?\DateTimeInterface $date = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): static
    {
        $this->date = $date;

        return $this;
    }
}
