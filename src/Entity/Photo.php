<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\PhotoRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=PhotoRepository::class)
 */
class Photo
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $storageName;

    /**
     * @ORM\Column(type="date")
     */
    private $createAt;

    /**
     * @ORM\Column(type="text")
     */
    private $fileType;

    /**
     * @ORM\ManyToOne(targetEntity=Announce::class, inversedBy="photos")
     */
    private $announce;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStorageName(): ?string
    {
        return $this->storageName;
    }

    public function setStorageName(string $storageName): self
    {
        $this->storageName = $storageName;

        return $this;
    }

    public function getCreateAt(): ?\DateTimeInterface
    {
        return $this->createAt;
    }

    public function setCreateAt(\DateTimeInterface $createAt): self
    {
        $this->createAt = $createAt;

        return $this;
    }

    public function getFileType(): ?string
    {
        return $this->fileType;
    }

    public function setFileType(string $fileType): self
    {
        $this->fileType = $fileType;

        return $this;
    }

    public function getAnnounce(): ?Announce
    {
        return $this->announce;
    }

    public function setAnnounce(?Announce $announce): self
    {
        $this->announce = $announce;

        return $this;
    }
}
