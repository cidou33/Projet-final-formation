<?php

namespace App\Entity;

use App\Repository\TranscriptionsRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TranscriptionsRepository::class)]
class Transcriptions
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $bandName;

    #[ORM\Column(type: 'string', length: 255)]
    private $songName;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $pdfFile;

    #[ORM\Column(type: 'string', length: 255)]
    private $mediaLink;

    #[ORM\Column(type: 'boolean')]
    private $active;

    #[ORM\Column(type: 'date')]
    private $createDate;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private $updateDate;

    #[ORM\Column(type: 'integer', nullable: true)]
    private $numbDownload;

    #[ORM\ManyToOne(targetEntity: Difficulty::class)]
    #[ORM\JoinColumn(nullable: false)]
    private $difficultyLevel;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBandName(): ?string
    {
        return $this->bandName;
    }

    public function setBandName(string $bandName): self
    {
        $this->bandName = $bandName;

        return $this;
    }

    public function getSongName(): ?string
    {
        return $this->songName;
    }

    public function setSongName(string $songName): self
    {
        $this->songName = $songName;

        return $this;
    }

    public function getPdfFile(): ?string
    {
        return $this->pdfFile;
    }

    public function setPdfFile(?string $pdfFile): self
    {
        $this->pdfFile = $pdfFile;

        return $this;
    }

    public function getMediaLink(): ?string
    {
        return $this->mediaLink;
    }

    public function setMediaLink(string $mediaLink): self
    {
        $this->mediaLink = $mediaLink;

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

    public function getNumbDownload(): ?int
    {
        return $this->numbDownload;
    }

    public function setNumbDownload(?int $numbDownload): self
    {
        $this->numbDownload = $numbDownload;

        return $this;
    }

    public function getDifficultyLevel(): ?Difficulty
    {
        return $this->difficultyLevel;
    }

    public function setDifficultyLevel(?Difficulty $difficultyLevel): self
    {
        $this->difficultyLevel = $difficultyLevel;

        return $this;
    }
}
