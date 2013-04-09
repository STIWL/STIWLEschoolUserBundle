<?php

namespace Esolving\Eschool\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Esolving\Eschool\UserBundle\Entity\Teacher
 *
 * @ORM\Table(name="teachers")
 * @ORM\Entity(repositoryClass="Esolving\Eschool\UserBundle\Repository\TeacherRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Teacher {

    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var boolean $status
     *
     * @ORM\Column(name="status", type="boolean")
     */
    private $status;

    /**
     *
     * @var $user 
     * @ORM\ManyToOne(targetEntity="User", inversedBy="teachers", cascade={"persist"})
     */
    private $user;
    
    /**
     *
     * @var date
     * @ORM\Column(name="createdAt", type="datetime")
     */
    protected $createdAt;
    
    /**
     *
     * @var date
     * @ORM\Column(name="updatedAt", type="datetime", nullable=true)
     */
    protected $updatedAt;
    
    /**
     *
     * @var date
     * @ORM\Column(name="disabledAt", type="datetime", nullable=true)
     */
    protected $disabledAt;
    
    /**
     *
     * @var type 
     * @ORM\OneToMany(targetEntity="Esolving\Eschool\RoomBundle\Entity\Schedule", mappedBy="teacher")
     */
    private $schedules;

    public function __construct() {
        $this->status = true;
        $this->createdAt = new \DateTime();
        $this->schedules = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * @ORM\PreUpdate()
     */
    public function preUpdatedAt() {
        $this->updatedAt = new \DateTime();
        if (!$this->getStatus()) {
            $this->disabledAt = new \DateTime();
        } else {
            $this->disabledAt = null;
        }
    }
    
    public function __toString() {
        return $this->getUser()->__toString();
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set status
     *
     * @param boolean $status
     * @return Teacher
     */
    public function setStatus($status)
    {
        $this->status = $status;
    
        return $this;
    }

    /**
     * Get status
     *
     * @return boolean 
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return Teacher
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    
        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime 
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     * @return Teacher
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;
    
        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime 
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Set disabledAt
     *
     * @param \DateTime $disabledAt
     * @return Teacher
     */
    public function setDisabledAt($disabledAt)
    {
        $this->disabledAt = $disabledAt;
    
        return $this;
    }

    /**
     * Get disabledAt
     *
     * @return \DateTime 
     */
    public function getDisabledAt()
    {
        return $this->disabledAt;
    }

    /**
     * Set user
     *
     * @param \Esolving\Eschool\UserBundle\Entity\User $user
     * @return Teacher
     */
    public function setUser(\Esolving\Eschool\UserBundle\Entity\User $user = null)
    {
        $this->user = $user;
    
        return $this;
    }

    /**
     * Get user
     *
     * @return \Esolving\Eschool\UserBundle\Entity\User 
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Add schedules
     *
     * @param \Esolving\Eschool\RoomBundle\Entity\Schedule $schedules
     * @return Teacher
     */
    public function addSchedule(\Esolving\Eschool\RoomBundle\Entity\Schedule $schedules)
    {
        $this->schedules[] = $schedules;
    
        return $this;
    }

    /**
     * Remove schedules
     *
     * @param \Esolving\Eschool\RoomBundle\Entity\Schedule $schedules
     */
    public function removeSchedule(\Esolving\Eschool\RoomBundle\Entity\Schedule $schedules)
    {
        $this->schedules->removeElement($schedules);
    }

    /**
     * Get schedules
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getSchedules()
    {
        return $this->schedules;
    }
}