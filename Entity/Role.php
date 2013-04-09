<?php

namespace Esolving\Eschool\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Esolving\Eschool\UserBundle\Entity\Role
 *
 * @ORM\Table(name="roles")
 * @ORM\Entity(repositoryClass="Esolving\Eschool\UserBundle\Repository\RoleRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Role {

    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    
    /**
     * @var integer $role
     *
     * @ORM\OneToOne(targetEntity="Esolving\Eschool\CoreBundle\Entity\Type")
     * @Assert\NotBlank()
     */
    private $roleType;
    
    /**
     * This is the status
     * @var type 
     * @ORM\Column(name="status", type="boolean")
     */
    protected $status;

    /**
     * @var integer $users
     *
     * @ORM\ManyToMany(targetEntity="User", mappedBy="rolesAccess")
     */
    private $users;
    
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
    
    public function __toString() {
        return $this->getRoleType()->getName();
    }
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->status = true;
        $this->users = new \Doctrine\Common\Collections\ArrayCollection();
        $this->createdAt = new \DateTime();
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
     * @return Role
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
     * @return Role
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
     * @return Role
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
     * @return Role
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
     * Set roleType
     *
     * @param Esolving\Eschool\CoreBundle\Entity\Type $roleType
     * @return Role
     */
    public function setRoleType(\Esolving\Eschool\CoreBundle\Entity\Type $roleType = null)
    {
        $this->roleType = $roleType;
    
        return $this;
    }

    /**
     * Get roleType
     *
     * @return Esolving\Eschool\CoreBundle\Entity\Type 
     */
    public function getRoleType()
    {
        return $this->roleType;
    }

    /**
     * Add users
     *
     * @param Esolving\Eschool\UserBundle\Entity\User $users
     * @return Role
     */
    public function addUser(\Esolving\Eschool\UserBundle\Entity\User $users)
    {
        $this->users[] = $users;
    
        return $this;
    }

    /**
     * Remove users
     *
     * @param Esolving\Eschool\UserBundle\Entity\User $users
     */
    public function removeUser(\Esolving\Eschool\UserBundle\Entity\User $users)
    {
        $this->users->removeElement($users);
    }

    /**
     * Get users
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getUsers()
    {
        return $this->users;
    }
}