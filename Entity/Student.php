<?php

namespace Esolving\Eschool\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Esolving\Eschool\UserBundle\Entity\User as BaseUser;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;
/**
 * Esolving\Eschool\UserBundle\Entity\Student
 *
 * @ORM\Table(name="students")
 * @ORM\Entity(repositoryClass="Esolving\Eschool\UserBundle\Repository\StudentRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Student {

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
     * @ORM\ManyToOne(targetEntity="User", inversedBy="students", cascade={"persist"})
     */
    private $user;
    
    /**
     *
     * @var date
     * @ORM\Column(name="createdAt", type="datetime")
     */
    private $createdAt;
    
    /**
     *
     * @var date
     * @ORM\Column(name="updatedAt", type="datetime", nullable=true)
     */
    private $updatedAt;
    
    /**
     *
     * @var date
     * @ORM\Column(name="disabledAt", type="datetime", nullable=true)
     */
    private $disabledAt;
    
    /**
     *
     * @var type 
     * @ORM\ManyToMany(targetEntity="Father", inversedBy="students")
     * @ORM\JoinTable(name="students__fathers")
     * @Assert\Count(
     *      min = "1",
     *      max = "2"
     * )
     */
    private $fathers;
    
    /**
     *
     * @var type 
     * @ORM\OneToMany(targetEntity="Esolving\Eschool\RoomBundle\Entity\StudentInscribe", mappedBy="student")
     * @Assert\NotBlank();
     */
    private $studentInscribes;
    
    public function __toString() {
        return $this->getUser()->getName();
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
    
    public function __construct() {
        $this->status = true;
        $this->createdAt = new \DateTime();
        $this->fathers = new ArrayCollection();
        $this->studentInscribes = new ArrayCollection();
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
     * @return Student
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
     * @return Student
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
     * @return Student
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
     * @return Student
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
     * @return Student
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
     * Add fathers
     *
     * @param \Esolving\Eschool\UserBundle\Entity\Father $fathers
     * @return Student
     */
    public function addFather(\Esolving\Eschool\UserBundle\Entity\Father $fathers)
    {
        $this->fathers[] = $fathers;
    
        return $this;
    }

    /**
     * Remove fathers
     *
     * @param \Esolving\Eschool\UserBundle\Entity\Father $fathers
     */
    public function removeFather(\Esolving\Eschool\UserBundle\Entity\Father $fathers)
    {
        $this->fathers->removeElement($fathers);
    }

    /**
     * Get fathers
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getFathers()
    {
        return $this->fathers;
    }

    /**
     * Add studentInscribes
     *
     * @param \Esolving\Eschool\RoomBundle\Entity\StudentInscribe $studentInscribes
     * @return Student
     */
    public function addStudentInscribe(\Esolving\Eschool\RoomBundle\Entity\StudentInscribe $studentInscribes)
    {
        $this->studentInscribes[] = $studentInscribes;
    
        return $this;
    }

    /**
     * Remove studentInscribes
     *
     * @param \Esolving\Eschool\RoomBundle\Entity\StudentInscribe $studentInscribes
     */
    public function removeStudentInscribe(\Esolving\Eschool\RoomBundle\Entity\StudentInscribe $studentInscribes)
    {
        $this->studentInscribes->removeElement($studentInscribes);
    }

    /**
     * Get studentInscribes
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getStudentInscribes()
    {
        return $this->studentInscribes;
    }
}