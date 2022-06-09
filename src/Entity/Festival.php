<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\FestivalRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ApiResource(attributes={
 *   "normalization_context"={"groups"={"festival"}},
 *   "denormalization_context"={"groups"={"festival"}},
 * })
 * @ORM\Entity(repositoryClass=FestivalRepository::class)
 */
class Festival
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"festival"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotNull( message = "Veuillez renseigner un nom")
     * @Groups({"festival"})
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"festival"})
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     * @Groups({"festival"})
     */
    private $type;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\NotNull( message = "Veuillez renseigner une date de début")
     * @Groups({"festival"})
     */
    private $dateStart;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\NotNull( message = "Veuillez renseigner une date de fin")
     * @Groups({"festival"})
     */
    private $dateEnd;

    /**
     * @ORM\Column(type="boolean")
     * @Groups({"festival"})
     */
    private $cancelled;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"festival"})
     */
    private $color;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="festivals")
     * @Groups({"festival"})
     */
    private $user;

    /**
     * @ORM\ManyToMany(targetEntity=Tag::class, inversedBy="festival")
     * @Groups({"festival"})
     */
    private $tag;

    /**
     * @ORM\ManyToOne(targetEntity=Place::class, inversedBy="festival")
     * @Groups({"festival"})
     */
    private $place;

    public function __construct()
    {
        $this->tag = new ArrayCollection();
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

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getDateStart(): ?\DateTimeInterface
    {
        return $this->dateStart;
    }

    public function setDateStart(\DateTimeInterface $dateStart): self
    {
        $this->dateStart = $dateStart;

        return $this;
    }

    public function getDateEnd(): ?\DateTimeInterface
    {
        return $this->dateEnd;
    }

    public function setDateEnd(\DateTimeInterface $dateEnd): self
    {
        $this->dateEnd = $dateEnd;

        return $this;
    }

    public function isCancelled(): ?bool
    {
        return $this->cancelled;
    }

    public function setCancelled(bool $cancelled): self
    {
        $this->cancelled = $cancelled;

        return $this;
    }

    public function getColor(): ?string
    {
        return $this->color;
    }

    public function setColor(?string $color): self
    {
        $this->color = $color;

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

    /**
     * @return Collection<int, Tag>
     */
    public function getTag(): Collection
    {
        return $this->tag;
    }

    public function addTag(Tag $tag): self
    {
        if (!$this->tag->contains($tag)) {
            $this->tag[] = $tag;
        }

        return $this;
    }

    public function removeTag(Tag $tag): self
    {
        $this->tag->removeElement($tag);

        return $this;
    }

    public function getPlace(): ?Place
    {
        return $this->place;
    }

    public function setPlace(?Place $place): self
    {
        $this->place = $place;

        return $this;
    }

}
