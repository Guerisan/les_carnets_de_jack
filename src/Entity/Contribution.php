<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ContributionRepository")
 */
class Contribution
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
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="text")
     */
    private $content;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $date;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Commentary", mappedBy="contributionRelation")
     */
    private $commentaries;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $status;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Images", mappedBy="contribution")
     */
    private $images;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $main_image;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $image_gallery_1;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $image_gallery_2;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $image_gallery_3;


    public function __construct()
    {
        $this->commentaries = new ArrayCollection();
        $this->images = new ArrayCollection();
    }

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

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
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

    public function getDate(): ?string
    {
        return $this->date;
    }

    public function setDate(): self
    {
        $this->date = date('Y-m-d H:i:s');

        return $this;
    }

    /**
     * @return Collection|Commentary[]
     */
    public function getCommentaries(): Collection
    {
        return $this->commentaries;
    }

    public function addCommentary(Commentary $commentary): self
    {
        if (!$this->commentaries->contains($commentary)) {
            $this->commentaries[] = $commentary;
            $commentary->setContributionRelation($this);
        }

        return $this;
    }

    public function removeCommentary(Commentary $commentary): self
    {
        if ($this->commentaries->contains($commentary)) {
            $this->commentaries->removeElement($commentary);
            // set the owning side to null (unless already changed)
            if ($commentary->getContributionRelation() === $this) {
                $commentary->setContributionRelation(null);
            }
        }

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(?string $status): self
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return Collection|Images[]
     */
    public function getImages(): Collection
    {
        return $this->images;
    }

    public function addImage(Images $image): self
    {
        if (!$this->images->contains($image)) {
            $this->images[] = $image;
            $image->setContribution($this);
        }

        return $this;
    }

    public function removeImage(Images $image): self
    {
        if ($this->images->contains($image)) {
            $this->images->removeElement($image);
            // set the owning side to null (unless already changed)
            if ($image->getContribution() === $this) {
                $image->setContribution(null);
            }
        }

        return $this;
    }

    public function getMainImage()
    {
        return $this->main_image;
    }

    public function setMainImage($main_image): self
    {
        $this->main_image = $main_image;

        return $this;
    }

    public function getImageGallery1()
    {
        return $this->image_gallery_1;
    }

    public function setImageGallery1( $image_gallery_1): self
    {
        $this->image_gallery_1 = $image_gallery_1;

        return $this;
    }

    public function getImageGallery2()
    {
        return $this->image_gallery_2;
    }

    public function setImageGallery2($image_gallery_2): self
    {
        $this->image_gallery_2 = $image_gallery_2;

        return $this;
    }

    public function getImageGallery3()
    {
        return $this->image_gallery_3;
    }

    public function setImageGallery3( $image_gallery_3): self
    {
        $this->image_gallery_3 = $image_gallery_3;

        return $this;
    }

}
