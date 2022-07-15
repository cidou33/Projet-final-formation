<?php

namespace App\Entity;

use App\Repository\NewsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: NewsRepository::class)]
class News
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $title;

    #[ORM\Column(type: 'string', length: 500)]
    private $Content;

    #[ORM\Column(type: 'date')]
    private $createDate;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private $updateDate;

    #[ORM\Column(type: 'boolean')]
    private $active;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $imgNews;

    #[ORM\Column(type: 'string', length: 255)]
    private $imgAlt;

    #[ORM\ManyToMany(targetEntity: Users::class)]
    private $liked;

    public function __construct()
    {
        $this->liked = new ArrayCollection();
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

    public function getContent(): ?string
    {
        return $this->Content;
    }

    public function setContent(string $Content): self
    {
        $this->Content = $Content;

        return $this;
    }

    public function getCreateDate(): ?\DateTimeInterface
    {
        return $this->createDate;
    }

    public function setCreateDate(\DateTimeInterface $createDate): self
    {
        $this->createDate = $createDate;

        return $this;
    }

    public function getUpdateDate(): ?\DateTimeInterface
    {
        return $this->updateDate;
    }

    public function setUpdateDate(?\DateTimeInterface $updateDate): self
    {
        $this->updateDate = $updateDate;

        return $this;
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

    public function getImgNews(): ?string
    {
        return $this->imgNews;
    }

    public function setImgNews(?string $imgNews): self
    {
        $this->imgNews = $imgNews;

        return $this;
    }

    public function getImgAlt(): ?string
    {
        return $this->imgAlt;
    }

    public function setImgAlt(string $imgAlt): self
    {
        $this->imgAlt = $imgAlt;

        return $this;
    }

    /**
     * @return Collection<int, Users>
     */
    public function getLiked(): Collection
    {
        return $this->liked;
    }

    public function addLiked(Users $liked): self
    {
        if (!$this->liked->contains($liked)) {
            $this->liked[] = $liked;
        }

        return $this;
    }

    public function removeLiked(Users $liked): self
    {
        $this->liked->removeElement($liked);

        return $this;
    }
}
