<?php

namespace App\Entity;

use App\Repository\FeedbackRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: FeedbackRepository::class)]
class Feedback
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;

    #[ORM\Column(type: Types::TEXT, nullable: false)]
    #[Assert\NotBlank]
    #[Assert\NotNull]
    private string $text;

    #[ORM\Column]
    #[Assert\NotNull]
    private int $overallRating;

    #[ORM\ManyToOne(inversedBy: 'feedbacks')]
    #[ORM\JoinColumn(nullable: false)]
    private Vico $vico;

    #[ORM\OneToMany(mappedBy: 'feedback', targetEntity: Rating::class, orphanRemoval: true)]
    private Collection $ratings;

    #[ORM\OneToOne(inversedBy: 'feedback', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private Project $project;

    #[ORM\ManyToOne(inversedBy: 'feedbacks')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Client $client = null;

    public function __construct()
    {
        $this->ratings = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getText(): string
    {
        return $this->text;
    }

    public function setText(string $text): self
    {
        $this->text = $text;

        return $this;
    }

    public function getOverallRating(): int
    {
        return $this->overallRating;
    }

    public function setOverallRating(int $overallRating): self
    {
        $this->overallRating = $overallRating;

        return $this;
    }

    public function getVico(): Vico
    {
        return $this->vico;
    }

    public function setVico(Vico $vico): self
    {
        $this->vico = $vico;

        return $this;
    }

    /**
     * @return Collection<int, Rating>
     */
    public function getRatings(): Collection
    {
        return $this->ratings;
    }

    public function addRating(Rating $rating): self
    {
        if (!$this->ratings->contains($rating)) {
            $this->ratings->add($rating);
            $rating->setFeedback($this);
        }

        return $this;
    }

    public function removeRating(Rating $rating): self
    {
        if ($this->ratings->removeElement($rating)) {
            // set the owning side to null (unless already changed)
            if ($rating->getFeedback() === $this) {
                $rating->setFeedback(null);
            }
        }

        return $this;
    }

    public function removeAllRating(): self
    {
        foreach ($this->ratings as $rating) {
            if ($this->ratings->removeElement($rating)) {
                // set the owning side to null (unless already changed)
                if ($rating->getFeedback() === $this) {
                    $rating->setFeedback(null);
                }
            }
        }

        return $this;
    }

    public function getProject(): ?Project
    {
        return $this->project;
    }

    public function setProject(Project $project): self
    {
        $this->project = $project;

        return $this;
    }

    public function getClient(): ?Client
    {
        return $this->client;
    }

    public function setClient(?Client $client): self
    {
        $this->client = $client;

        return $this;
    }
}
