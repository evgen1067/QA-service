<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\QuestionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ApiResource(
    collectionOperations: ['get'],
    itemOperations: ['get'],
)]
#[ORM\Entity(repositoryClass: QuestionRepository::class)]
class Question
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\ManyToOne(targetEntity: QuestionCategory::class, inversedBy: 'questions')]
    #[ORM\JoinColumn(nullable: false)]
    private $category;

    #[Assert\NotBlank(message: 'Вы не ввели заголовок вопроса')]
    #[ORM\Column(type: 'string', length: 255)]
    private $title;

    #[Assert\NotBlank(message: 'Вы не ввели текст вопроса')]
    #[ORM\Column(type: 'string', length: 510)]
    private $question_text;

    #[ORM\Column(type: 'datetime')]
    private $question_date;

    #[ORM\Column(type: 'boolean')]
    private $moderation_status = false;

    #[ORM\OneToMany(mappedBy: 'question', targetEntity: Answer::class)]
    private $answers;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'questions')]
    private $user;

    public function __construct()
    {
        $this->answers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCategory(): ?QuestionCategory
    {
        return $this->category;
    }

    public function setCategory(?QuestionCategory $category): self
    {
        $this->category = $category;

        return $this;
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

    public function getQuestionText(): ?string
    {
        return $this->question_text;
    }

    public function setQuestionText(string $question_text): self
    {
        $this->question_text = $question_text;

        return $this;
    }

    public function getQuestionDate(): ?\DateTimeInterface
    {
        return $this->question_date;
    }

    public function setQuestionDate(\DateTimeInterface $question_date): self
    {
        $this->question_date = $question_date;

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

    /**
     * @return Collection<int, Answer>
     */
    public function getAnswers(): Collection
    {
        return $this->answers;
    }

    public function addAnswer(Answer $answer): self
    {
        if (!$this->answers->contains($answer)) {
            $this->answers[] = $answer;
            $answer->setQuestion($this);
        }

        return $this;
    }

    public function removeAnswer(Answer $answer): self
    {
        if ($this->answers->removeElement($answer)) {
            // set the owning side to null (unless already changed)
            if ($answer->getQuestion() === $this) {
                $answer->setQuestion(null);
            }
        }

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

    public function __toString()
    {
        return $this->title;
    }
}
