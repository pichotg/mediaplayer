<?php


namespace App\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\GenreRepository")
 */
class Genre
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private $name;

    public function __construct()
    {
        $this->medias = new ArrayCollection();
    }

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Media", mappedBy="genre")
     */
    private $medias;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\TypeMedia", inversedBy="genres")
     * @ORM\JoinColumn(name="TypeMedia", referencedColumnName="id")
     */
    private $typemedia;

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

    /**
     * @return mixed
     */
    public function getMedias()
    {
        return $this->medias;
    }

    /**
     * @param mixed $medias
     */
    public function setMedias($medias): void
    {
        $this->$medias = $medias;
    }
}