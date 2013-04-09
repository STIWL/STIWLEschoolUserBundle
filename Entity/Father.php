<?php

namespace Esolving\Eschool\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Esolving\Eschool\UserBundle\Entity\User as BaseUser;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Esolving\Eschool\UserBundle\Entity\Father
 *
 * @ORM\Table(name="fathers")
 * @ORM\Entity(repositoryClass="Esolving\Eschool\UserBundle\Repository\FatherRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Father {

    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var \DateTime $createdAt
     *
     * @ORM\Column(name="createdAt", type="datetime")
     */
    private $createdAt;

    /**
     * @var \DateTime $updatedAt
     *
     * @ORM\Column(name="updatedAt", type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * @var \DateTime $disabledAt
     *
     * @ORM\Column(name="disabledAt", type="datetime", nullable=true)
     */
    private $disabledAt;

    /**
     *
     * @var type 
     * @ORM\Column(name="status", type="boolean")
     */
    private $status;

    /**
     *
     * @var type 
     * @ORM\ManyToMany(targetEntity="Student", mappedBy="fathers")
     * */
    private $students;

    /**
     *
     * @var type 
     * @ORM\ManyToOne(targetEntity="User", inversedBy="fathers", cascade={"persist"})
     */
    private $user;

    public function __construct() {
        $this->status = true;
        $this->createdAt = new \DateTime();
        $this->students = new \Doctrine\Common\Collections\ArrayCollection();
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
       return $this->getUser()->getName().' '.$this->getUser()->getLastname();
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
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return Father
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
     * @return Father
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
     * @return Father
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
     * Set status
     *
     * @param boolean $status
     * @return Father
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
     * Add students
     *
     * @param Esolving\Eschool\UserBundle\Entity\Student $students
     * @return Father
     */
    public function addStudent(\Esolving\Eschool\UserBundle\Entity\Student $students)
    {
        $this->students[] = $students;
    
        return $this;
    }

    /**
     * Remove students
     *
     * @param Esolving\Eschool\UserBundle\Entity\Student $students
     */
    public function removeStudent(\Esolving\Eschool\UserBundle\Entity\Student $students)
    {
        $this->students->removeElement($students);
    }

    /**
     * Get students
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getStudents()
    {
        return $this->students;
    }

    /**
     * Set user
     *
     * @param Esolving\Eschool\UserBundle\Entity\User $user
     * @return Father
     */
    public function setUser(\Esolving\Eschool\UserBundle\Entity\User $user = null)
    {
        $this->user = $user;
    
        return $this;
    }

    /**
     * Get user
     *
     * @return Esolving\Eschool\UserBundle\Entity\User 
     */
    public function getUser()
    {
        return $this->user;
    }
}