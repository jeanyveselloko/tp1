<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\BookingRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=BookingRepository::class)
 */
class Booking
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $bookingDate;

    /**
     * @ORM\Column(type="datetime")
     */
    private $checkInDate;

    /**
     * @ORM\Column(type="datetime")
     */
    private $checkOutDate;

    /**
     * @ORM\Column(type="integer")
     */
    private $numberOfNight;

    /**
     * @ORM\Column(type="integer")
     */
    private $TotalBooking;

    /**
     * @ORM\Column(type="integer")
     */
    private $priceByNight;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $status;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="bookings")
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity=Announce::class, inversedBy="bookings")
     */
    private $announce;

    /**
     * @ORM\OneToMany(targetEntity=Rating::class, mappedBy="booking")
     */
    private $ratings;

    public function __construct()
    {
        $this->ratings = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBookingDate(): ?\DateTimeInterface
    {
        return $this->bookingDate;
    }

    public function setBookingDate(\DateTimeInterface $bookingDate): self
    {
        $this->bookingDate = $bookingDate;

        return $this;
    }

    public function getCheckInDate(): ?\DateTimeInterface
    {
        return $this->checkInDate;
    }

    public function setCheckInDate(\DateTimeInterface $checkInDate): self
    {
        $this->checkInDate = $checkInDate;

        return $this;
    }

    public function getCheckOutDate(): ?\DateTimeInterface
    {
        return $this->checkOutDate;
    }

    public function setCheckOutDate(\DateTimeInterface $checkOutDate): self
    {
        $this->checkOutDate = $checkOutDate;

        return $this;
    }

    public function getNumberOfNight(): ?int
    {
        return $this->numberOfNight;
    }

    public function setNumberOfNight(int $numberOfNight): self
    {
        $this->numberOfNight = $numberOfNight;

        return $this;
    }

    public function getTotalBooking(): ?int
    {
        return $this->TotalBooking;
    }

    public function setTotalBooking(int $TotalBooking): self
    {
        $this->TotalBooking = $TotalBooking;

        return $this;
    }

    public function getPriceByNight(): ?int
    {
        return $this->priceByNight;
    }

    public function setPriceByNight(int $priceByNight): self
    {
        $this->priceByNight = $priceByNight;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

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

    public function getAnnounce(): ?Announce
    {
        return $this->announce;
    }

    public function setAnnounce(?Announce $announce): self
    {
        $this->announce = $announce;

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
            $this->ratings[] = $rating;
            $rating->setBooking($this);
        }

        return $this;
    }

    public function removeRating(Rating $rating): self
    {
        if ($this->ratings->removeElement($rating)) {
            // set the owning side to null (unless already changed)
            if ($rating->getBooking() === $this) {
                $rating->setBooking(null);
            }
        }

        return $this;
    }
}
