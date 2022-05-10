<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\AnswerRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ApiResource(
    collectionOperations: ['get'],
    itemOperations: ['get'],
)]
#[ORM\Entity(repositoryClass: AnswerRepository::class)]
class Answer
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\ManyToOne(targetEntity: Question::class, inversedBy: 'answers')]
    #[ORM\JoinColumn(nullable: false)]
    private $question;

    #[Assert\NotBlank]
    #[Assert\NotBlank(message: 'Вы не ввели текст вопроса')]
    #[ORM\Column(type: 'string', length: 510)]
    private $answer_text;

    #[ORM\Column(type: 'datetime')]
    private $answer_date;

    #[ORM\Column(type: 'integer')]
    private $answer_correctness;

    #[ORM\Column(type: 'boolean')]
    private $moderation_status = false;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'answers')]
    private $user;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuestion(): ?Question
    {
        return $this->question;
    }

    public function setQuestion(?Question $question): self
    {
        $this->question = $question;

        return $this;
    }

    public function getAnswerText(): ?string
    {
        return $this->answer_text;
    }

    public function setAnswerText(string $answer_text): self
    {
        $this->answer_text = $answer_text;

        return $this;
    }

    public function getAnswerDate(): ?\DateTimeInterface
    {
        return $this->answer_date;
    }

    public function setAnswerDate(\DateTimeInterface $answer_date): self
    {
        $this->answer_date = $answer_date;

        return $this;
    }

    public function getAnswerCorrectness(): ?int
    {
        return $this->answer_correctness;
    }

    public function setAnswerCorrectness(int $answer_correctness): self
    {
        $this->answer_correctness = $answer_correctness;

        return $this;
    }

    public function getModerationStatus(): ?bool
    {
        return $this->moderation_status;
    }

    public function setModerationStatus(bool $moderation_status): self
    {
        $this->moderation_status = $moderation_status;

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
}
