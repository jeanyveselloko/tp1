<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\AnnounceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=AnnounceRepository::class)
 */
class Announce
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
    private $name;

    /**
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @ORM\Column(type="integer")
     */
    private $bedroomNumber;

    /**
     * @ORM\Column(type="integer")
     */
    private $priceByNight;

    /**
     * @ORM\Column(type="date")
     */
    private $disponibility;

    /**
     * @ORM\Column(type="text")
     */
    private $address;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="announces")
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity=City::class, inversedBy="announces")
     */
    private $city;

    /**
     * @ORM\ManyToOne(targetEntity=AccomodationType::class, inversedBy="announces")
     */
    private $accomodationType;

    /**
     * @ORM\ManyToOne(targetEntity=AnnounceType::class, inversedBy="announces")
     */
    private $announceType;

    /**
     * @ORM\ManyToMany(targetEntity=Facilities::class, inversedBy="announces")
     */
    private $facilities;

    /**
     * @ORM\OneToMany(targetEntity=Photo::class, mappedBy="announce")
     */
    private $photos;

    /**
     * @ORM\OneToMany(targetEntity=Booking::class, mappedBy="announce")
     */
    private $bookings;

    public function __construct()
    {
        $this->facilities = new ArrayCollection();
        $this->photos = new ArrayCollection();
        $this->bookings = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getBedroomNumber(): ?int
    {
        return $this->bedroomNumber;
    }

    public function setBedroomNumber(int $bedroomNumber): self
    {
        $this->bedroomNumber = $bedroomNumber;

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

    public function getDisponibility(): ?\DateTimeInterface
    {
        return $this->disponibility;
    }

    public function setDisponibility(\DateTimeInterface $disponibility): self
    {
        $this->disponibility = $disponibility;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): self
    {
        $this->address = $address;

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

    public function getCity(): ?City
    {
        return $this->city;
    }

    public function setCity(?City $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getAccomodationType(): ?AccomodationType
    {
        return $this->accomodationType;
    }

    public function setAccomodationType(?AccomodationType $accomodationType): self
    {
        $this->accomodationType = $accomodationType;

        return $this;
    }

    public function getAnnounceType(): ?AnnounceType
    {
        return $this->announceType;
    }

    public function setAnnounceType(?AnnounceType $announceType): self
    {
        $this->announceType = $announceType;

        return $this;
    }

    /**
     * @return Collection<int, Facilities>
     */
    public function getFacilities(): Collection
    {
        return $this->facilities;
    }

    public function addFacility(Facilities $facility): self
    {
        if (!$this->facilities->contains($facility)) {
            $this->facilities[] = $facility;
        }

        return $this;
    }

    public function removeFacility(Facilities $facility): self
    {
        $this->facilities->removeElement($facility);

        return $this;
    }

    /**
     * @return Collection<int, Photo>
     */
    public function getPhotos(): Collection
    {
        return $this->photos;
    }

    public function addPhoto(Photo $photo): self
    {
        if (!$this->photos->contains($photo)) {
            $this->photos[] = $photo;
            $photo->setAnnounce($this);
        }

        return $this;
    }

    public function removePhoto(Photo $photo): self
    {
        if ($this->photos->removeElement($photo)) {
            // set the owning side to null (unless already changed)
            if ($photo->getAnnounce() === $this) {
                $photo->setAnnounce(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Booking>
     */
    public function getBookings(): Collection
    {
        return $this->bookings;
    }

    public function addBooking(Booking $booking): self
    {
        if (!$this->bookings->contains($booking)) {
            $this->bookings[] = $booking;
            $booking->setAnnounce($this);
        }

        return $this;
    }

    public function removeBooking(Booking $booking): self
    {
        if ($this->bookings->removeElement($booking)) {
            // set the owning side to null (unless already changed)
            if ($booking->getAnnounce() === $this) {
                $booking->setAnnounce(null);
            }
        }

        return $this;
    }
}
