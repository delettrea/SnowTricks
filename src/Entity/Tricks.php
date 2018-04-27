<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TricksRepository")
 */
class Tricks
{

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, unique=true)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text")
     */
    private $description;

    /**
     * @ORM\Column(name="date_creation", type="datetime")
     */
    private $dateCreation;

    /**
     * @ORM\Column(name="date_edit", type="datetime", nullable=true)
     */
    private $dateEdit;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Groups", inversedBy="tricks")
     * @ORM\JoinColumn(nullable=false)
     */
    private $group;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Comments", mappedBy="trick", orphanRemoval=true)
     */
    private $comments;

    /**
     */
    private $illustrations;


    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Videos", mappedBy="trick", orphanRemoval=true)
     */
    private $video;

    private $videos;

    private $date;

    public function __construct()
    {
        $this->videos = new ArrayCollection();
        $this->illustrations = new ArrayCollection();
        $this->date = new \DateTime();
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return mixed
     */
    public function getGroup()
    {
        return $this->group;
    }

    /**
     * @param mixed $group
     */
    public function setGroup($group)
    {
        $this->group = $group;
    }

    public function getVideos()
    {
        return $this->videos;
    }


    /**
     * @return mixed
     */
    public function getVideo()
    {
        return $this->video;
    }

    /**
     * @param mixed $video
     */
    public function setVideo($video)
    {
        $this->video = $video;
    }

    /**
     * @return mixed
     */
    public function getDateCreation()
    {
        return $this->dateCreation;
    }

    /**
     */
    public function setDateCreation()
    {
        $this->dateCreation = $this->date;
    }

    /**
     * @return mixed
     */
    public function getDateEdit()
    {
        return $this->dateEdit;
    }

    /**
     */
    public function setDateEdit()
    {
        $this->dateEdit = $this->date;
    }

    /**
     * @return mixed
     */
    public function getIllustrations()
    {
        return $this->illustrations;
    }

    /**
     * @param mixed $illustrations
     */
    public function setIllustrations($illustrations)
    {
        $this->illustrations = $illustrations;
    }




}
