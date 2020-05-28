<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;


/**
 * @ORM\Entity(repositoryClass="App\Repository\CommentaryRepository")
 */
class Commentary
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $author;

    /**
     * @ORM\Column(type="text")
     */
    private $text;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Recit", inversedBy="commentaries")
     * @ORM\JoinColumn(nullable=true)
     */
    private $relation;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $date;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Contribution", inversedBy="commentaries")
     */
    private $contributionRelation;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\JournalEntry", inversedBy="commentaries")
     */
    private $journalEntry;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAuthor(): ?string
    {
        return $this->author;
    }

    public function setAuthor(string $author): self
    {
        $this->author = $author;

        return $this;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(string $text): self
    {
        $this->text = $text;

        return $this;
    }

    public function getRelation(): ?Recit
    {
        return $this->relation;
    }

    public function setRelation(?Recit $relation): self
    {
        $this->relation = $relation;

        return $this;
    }

    public function getDate(): ?string
    {
        return $this->date;
    }

    public function setDate(): self
    {
        $this->date = date('Y-m-d H:i:s');
        return $this;
    }

    public function getContributionRelation(): ?Contribution
    {
        return $this->contributionRelation;
    }

    public function setContributionRelation(?Contribution $contributionRelation): self
    {
        $this->contributionRelation = $contributionRelation;

        return $this;
    }

    public function getJournalEntry(): ?JournalEntry
    {
        return $this->journalEntry;
    }

    public function setJournalEntry(?JournalEntry $journalEntry): self
    {
        $this->journalEntry = $journalEntry;

        return $this;
    }
}
