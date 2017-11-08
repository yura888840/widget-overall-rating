<?php
/**
 * Created by PhpStorm.
 * User: yuri
 * Date: 08.11.17
 * Time: 11:53
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class Review
 * @ORM\Entity
 * @package AppBundle\Entity
 */
class Review
{
    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @var int
     */
    private $id;

    /**
     * @var int
     * @ORM\Column(type="integer")
     */
    private $rating;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetime")
     */
    private $createdDate;

    /**
     * @var Hotel
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Hotel", inversedBy="reviews", cascade={"persist"})
     * @ORM\JoinColumn(name="hotel_uuid", referencedColumnName="UUID")
     */
    private $hotel;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return int
     */
    public function getRating()
    {
        return $this->rating;
    }

    /**
     * @param int $rating
     */
    public function setRating($rating)
    {
        $this->rating = $rating;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedDate()
    {
        return $this->createdDate;
    }

    /**
     * @param \DateTime $createdDate
     */
    public function setCreatedDate($createdDate)
    {
        $this->createdDate = $createdDate;
    }

    /**
     * @return Hotel
     */
    public function getHotel()
    {
        return $this->hotel;
    }

    /**
     * @param Hotel $hotel
     */
    public function setHotel($hotel)
    {
        $this->hotel = $hotel;
    }
}
