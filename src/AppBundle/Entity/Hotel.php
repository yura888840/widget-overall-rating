<?php
/**
 * Created by PhpStorm.
 * User: yuri
 * Date: 08.11.17
 * Time: 11:52
 */

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Hotel
 * @ORM\Entity(repositoryClass="AppBundle\Repository\HotelRepository")
 * @package AppBundle\Entity
 */
class Hotel
{
    /**
     * @var string
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\Column(type="string")
     */
    private $UUID;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $name;

    /**
     * @var Review
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Review", mappedBy="hotel", cascade={"persist"})
     */
    protected $reviews;

    /**
     * Hotel constructor.
     */
    public function __construct()
    {
        $this->reviews = new ArrayCollection();
    }

    /**
     * @return string
     */
    public function getUUID()
    {
        return $this->UUID;
    }

    /**
     * @param string $UUID
     */
    public function setUUID($UUID)
    {
        $this->UUID = $UUID;
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
}
