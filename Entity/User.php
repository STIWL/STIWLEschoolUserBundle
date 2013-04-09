<?php

namespace Esolving\Eschool\UserBundle\Entity;

use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\EquatableInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Esolving\Eschool\UserBundle\Validator\Constraints as EsolvingUserBundleAssert;
use Doctrine\ORM\Mapping as ORM;

/**
 * Esolving\Eschool\UserBundle\Entity\User
 *
 * @ORM\Table(name="users")
 * @ORM\Entity(repositoryClass="Esolving\Eschool\UserBundle\Repository\UserRepository")
 * @ORM\HasLifecycleCallbacks
 * @EsolvingUserBundleAssert\AreFathersRequired
 */
class User implements EquatableInterface, UserInterface {

    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string $name
     *
     * @ORM\Column(name="name", type="string", length=45)
     * @Assert\NotBlank()
     */
    private $name;

    /**
     * @var string $lastname
     *
     * @ORM\Column(name="lastname", type="string", length=45)
     * @Assert\NotBlank()
     */
    private $lastName;

    /**
     * @var date $dateborn
     *
     * @ORM\Column(name="dateborn", type="date")
     * @Assert\NotBlank()
     * @Assert\Type("\DateTime")
     */
    private $dateBorn;

    /**
     * @var string $phone
     *
     * @ORM\Column(name="phone", type="string", length=15)
     * @Assert\NotBlank()
     */
    private $phone;

    /**
     * @var string $phonemovil
     *
     * @ORM\Column(name="phonemovil", type="string", length=15, nullable=true)
     */
    private $phoneMovil;

    /**
     * @var string $email
     *
     * @ORM\Column(name="email", type="string", length=100)
     * @Assert\MinLength(limit=10, message="min_length")
     * @Assert\NotBlank()
     * @Assert\Email(message = "invalid_email")
     */
    private $email;

    /**
     * @var string $address
     *
     * @ORM\Column(name="address", type="string", length=250)
     * @Assert\NotBlank()
     */
    private $address;

    /**
     * @var string $code
     *
     * @ORM\Column(name="code", type="string", length=30)
     */
    private $code;

    /**
     * @var string $password
     *
     * @ORM\Column(name="password", type="string", length=300)
     */
    private $password;

    /**
     * @var string $salt
     *
     * @ORM\Column(name="salt", type="string", length=255, nullable=true)
     */
    private $salt;

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
     * @var integer $sexType
     * 
     * @ORM\ManyToOne(targetEntity="Esolving\Eschool\CoreBundle\Entity\Type")
     */
    private $sexType;

    /**
     * @var integer $distritType
     *
     * @ORM\ManyToOne(targetEntity="Esolving\Eschool\CoreBundle\Entity\Type")
     */
    private $distritType;

    /**
     * @var integer $groupBlodType
     *
     * @ORM\ManyToOne(targetEntity="Esolving\Eschool\CoreBundle\Entity\Type")
     */
    private $groupBlodType;

    /**
     * @var integer $sectionType
     *
     * @ORM\ManyToOne(targetEntity="Esolving\Eschool\CoreBundle\Entity\Type")
     */
    private $sectionType;

    /**
     * @var integer $headquarterType
     *
     * @ORM\ManyToOne(targetEntity="Esolving\Eschool\CoreBundle\Entity\Type")
     */
    private $headquarterType;

    /**
     * @var string $status
     *
     * @ORM\Column(name="status", type="boolean", nullable=true)
     */
    private $status;

    /**
     * @var integer $roles
     *
     * @ORM\ManyToMany(targetEntity="Role", inversedBy="users");
     * @ORM\JoinTable(name="users__roles",
     *  joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")},
     *  inverseJoinColumns={@ORM\JoinColumn(name="role_id", referencedColumnName="id")}
     * )
     * @Assert\NotBlank()
     * @Assert\Count(
     *      min = "1",
     *      max = "6"
     * )
     */
    private $rolesAccess;

    /**
     *
     * @var $students  
     * @ORM\OneToMany(targetEntity="Student", mappedBy="user",cascade={"persist","remove"})
     */
    private $students;

    /**
     *
     * @var $students  
     * @ORM\OneToMany(targetEntity="Teacher", mappedBy="user",cascade={"persist","remove"})
     */
    private $teachers;

    /**
     *
     * @var $fathers  
     * @ORM\OneToMany(targetEntity="Father", mappedBy="user", cascade={"persist","remove"})
     */
    private $fathers;

//    /**
//     *
//     * @ORM\OneToMany(targetEntity="Role", mappedBy="user", cascade={"all"}, orphanRemoval=true)
//     */
//    private $roles;
//    /**
//     * @Assert\True(message = "The password and confirmation password do not match")
//     */
//    public function isPasswordEqualToConfirmationPassword() {
//        return ($this->getPassword() == $this->getConfirmationPassword());
//    }

    /**
     * 
     * @param \Symfony\Component\Security\Core\User\UserInterface $user
     * @return type
     */
    function isEqualTo(UserInterface $user) {
        return $this->getCode() == $user->getUsername();
    }

    function eraseCredentials() {
        
    }

    function getRoles() {
//        return array('ROLE_USER','IS_AUTHENTICATED','ROLE_ADMIN');
        $array = array();
        foreach ($this->getRolesAccess() as $role) {
            $array[] = $role->getRoleType()->getName();
        }
        return $array;
    }

    function getUsername() {
        return $this->getCode();
    }

    public function __toString() {
        return $this->getName() . ' ' . $this->getLastname();
    }
    
    public function getFullName(){
        return $this->getName() . ' ' . $this->getLastname();
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
     * @ORM\PrePersist()
     
    public function prePersist() {
        $rolesaccess = $this->getRolesAccess();
        foreach ($rolesaccess as $role) {
            switch ($role->getRoleType()->getName()) {
                case 'ROLE_STUDENT':
                    $student = new \Esolving\Eschool\UserBundle\Entity\Student();
                    $student->setUser($this);
                    $this->addStudent($student);
                    foreach ($this->getFathers() as $father) {
                        $student->getFathers()->add($father);
                    }
                    break;
            }
        }
//        $rolesaccess = $this->getRolesAccess();
//        foreach ($rolesaccess as $role) {
//            switch ($role->getRoleType()) {
//                case 'ROLE_STUDENT':
//                    $student = new \Esolving\Eschool\UserBundle\Entity\Student();
//                    $student->setUser($this);
//                    $this->addStudent($student);
//                    break;
//                case 'ROLE_TEACHER':
//                    $teacher = new \Esolving\Eschool\UserBundle\Entity\Teacher();
//                    $teacher->setUser($this);
//                    $this->addTeacher($teacher);
//                    break;
//                case 'ROLE_FATHER':
//                    $father = new \Esolving\Eschool\UserBundle\Entity\Father();
//                    $father->setUser($this);
//                    $this->addFather($father);
//                    break;
//            }
//        }
    }
     */

    /**
     * Get password
     *
     * @return string 
     */
    public function getPassword() {
        return $this->password;
    }

    /**
     * Get salt
     *
     * @return string 
     */
    public function getSalt() {
        return $this->salt;
    }

    public function __construct() {
        $this->rolesAccess = new \Doctrine\Common\Collections\ArrayCollection();
        $this->fathers = new \Doctrine\Common\Collections\ArrayCollection();
        $this->students = new \Doctrine\Common\Collections\ArrayCollection();
        $this->teachers = new \Doctrine\Common\Collections\ArrayCollection();
        $this->createdAt = new \DateTime();
        $this->salt = md5(uniqid(null, true));
        $this->password = "default";
        $this->code = "default";
        $this->status = true;
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return User
     */
    public function setName($name) {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName() {
        return $this->name;
    }

    /**
     * Set lastName
     *
     * @param string $lastName
     * @return User
     */
    public function setLastName($lastName) {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * Get lastName
     *
     * @return string 
     */
    public function getLastName() {
        return $this->lastName;
    }

    /**
     * Set dateBorn
     *
     * @param \DateTime $dateBorn
     * @return User
     */
    public function setDateBorn($dateBorn) {
        $this->dateBorn = $dateBorn;

        return $this;
    }

    /**
     * Get dateBorn
     *
     * @return \DateTime 
     */
    public function getDateBorn() {
        return $this->dateBorn;
    }

    /**
     * Set phone
     *
     * @param string $phone
     * @return User
     */
    public function setPhone($phone) {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Get phone
     *
     * @return string 
     */
    public function getPhone() {
        return $this->phone;
    }

    /**
     * Set phoneMovil
     *
     * @param string $phoneMovil
     * @return User
     */
    public function setPhoneMovil($phoneMovil) {
        $this->phoneMovil = $phoneMovil;

        return $this;
    }

    /**
     * Get phoneMovil
     *
     * @return string 
     */
    public function getPhoneMovil() {
        return $this->phoneMovil;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return User
     */
    public function setEmail($email) {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail() {
        return $this->email;
    }

    /**
     * Set address
     *
     * @param string $address
     * @return User
     */
    public function setAddress($address) {
        $this->address = $address;

        return $this;
    }

    /**
     * Get address
     *
     * @return string 
     */
    public function getAddress() {
        return $this->address;
    }

    /**
     * Set code
     *
     * @param string $code
     * @return User
     */
    public function setCode($code) {
        $this->code = $code;

        return $this;
    }

    /**
     * Get code
     *
     * @return string 
     */
    public function getCode() {
        return $this->code;
    }

    /**
     * Set password
     *
     * @param string $password
     * @return User
     */
    public function setPassword($password) {
        $this->password = $password;

        return $this;
    }

    /**
     * Set salt
     *
     * @param string $salt
     * @return User
     */
    public function setSalt($salt) {
        $this->salt = $salt;

        return $this;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return User
     */
    public function setCreatedAt($createdAt) {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime 
     */
    public function getCreatedAt() {
        return $this->createdAt;
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     * @return User
     */
    public function setUpdatedAt($updatedAt) {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime 
     */
    public function getUpdatedAt() {
        return $this->updatedAt;
    }

    /**
     * Set disabledAt
     *
     * @param \DateTime $disabledAt
     * @return User
     */
    public function setDisabledAt($disabledAt) {
        $this->disabledAt = $disabledAt;

        return $this;
    }

    /**
     * Get disabledAt
     *
     * @return \DateTime 
     */
    public function getDisabledAt() {
        return $this->disabledAt;
    }

    /**
     * Set status
     *
     * @param boolean $status
     * @return User
     */
    public function setStatus($status) {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return boolean 
     */
    public function getStatus() {
        return $this->status;
    }

    /**
     * Set sexType
     *
     * @param \Esolving\Eschool\CoreBundle\Entity\Type $sexType
     * @return User
     */
    public function setSexType(\Esolving\Eschool\CoreBundle\Entity\Type $sexType = null) {
        $this->sexType = $sexType;

        return $this;
    }

    /**
     * Get sexType
     *
     * @return \Esolving\Eschool\CoreBundle\Entity\Type 
     */
    public function getSexType() {
        return $this->sexType;
    }

    /**
     * Set distritType
     *
     * @param \Esolving\Eschool\CoreBundle\Entity\Type $distritType
     * @return User
     */
    public function setDistritType(\Esolving\Eschool\CoreBundle\Entity\Type $distritType = null) {
        $this->distritType = $distritType;

        return $this;
    }

    /**
     * Get distritType
     *
     * @return \Esolving\Eschool\CoreBundle\Entity\Type 
     */
    public function getDistritType() {
        return $this->distritType;
    }

    /**
     * Set groupBlodType
     *
     * @param \Esolving\Eschool\CoreBundle\Entity\Type $groupBlodType
     * @return User
     */
    public function setGroupBlodType(\Esolving\Eschool\CoreBundle\Entity\Type $groupBlodType = null) {
        $this->groupBlodType = $groupBlodType;

        return $this;
    }

    /**
     * Get groupBlodType
     *
     * @return \Esolving\Eschool\CoreBundle\Entity\Type 
     */
    public function getGroupBlodType() {
        return $this->groupBlodType;
    }

    /**
     * Set sectionType
     *
     * @param \Esolving\Eschool\CoreBundle\Entity\Type $sectionType
     * @return User
     */
    public function setSectionType(\Esolving\Eschool\CoreBundle\Entity\Type $sectionType = null) {
        $this->sectionType = $sectionType;

        return $this;
    }

    /**
     * Get sectionType
     *
     * @return \Esolving\Eschool\CoreBundle\Entity\Type 
     */
    public function getSectionType() {
        return $this->sectionType;
    }

    /**
     * Set headquarterType
     *
     * @param \Esolving\Eschool\CoreBundle\Entity\Type $headquarterType
     * @return User
     */
    public function setHeadquarterType(\Esolving\Eschool\CoreBundle\Entity\Type $headquarterType = null) {
        $this->headquarterType = $headquarterType;

        return $this;
    }

    /**
     * Get headquarterType
     *
     * @return \Esolving\Eschool\CoreBundle\Entity\Type 
     */
    public function getHeadquarterType() {
        return $this->headquarterType;
    }

    /**
     * Add rolesAccess
     *
     * @param \Esolving\Eschool\UserBundle\Entity\Role $rolesAccess
     * @return User
     */
    public function addRolesAcces(\Esolving\Eschool\UserBundle\Entity\Role $rolesAccess) {
        $this->rolesAccess[] = $rolesAccess;

        return $this;
    }

    /**
     * Remove rolesAccess
     *
     * @param \Esolving\Eschool\UserBundle\Entity\Role $rolesAccess
     */
    public function removeRolesAcces(\Esolving\Eschool\UserBundle\Entity\Role $rolesAccess) {
        $this->rolesAccess->removeElement($rolesAccess);
    }

    /**
     * Get rolesAccess
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getRolesAccess() {
        return $this->rolesAccess;
    }

    /**
     * Add students
     *
     * @param \Esolving\Eschool\UserBundle\Entity\Student $students
     * @return User
     */
    public function addStudent(\Esolving\Eschool\UserBundle\Entity\Student $students) {
        $this->students[] = $students;

        return $this;
    }

    /**
     * Remove students
     *
     * @param \Esolving\Eschool\UserBundle\Entity\Student $students
     */
    public function removeStudent(\Esolving\Eschool\UserBundle\Entity\Student $students) {
        $this->students->removeElement($students);
    }

    /**
     * Get students
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getStudents() {
        return $this->students;
    }

    /**
     * Add teachers
     *
     * @param \Esolving\Eschool\UserBundle\Entity\Teacher $teachers
     * @return User
     */
    public function addTeacher(\Esolving\Eschool\UserBundle\Entity\Teacher $teachers) {
        $this->teachers[] = $teachers;

        return $this;
    }

    /**
     * Remove teachers
     *
     * @param \Esolving\Eschool\UserBundle\Entity\Teacher $teachers
     */
    public function removeTeacher(\Esolving\Eschool\UserBundle\Entity\Teacher $teachers) {
        $this->teachers->removeElement($teachers);
    }

    /**
     * Get teachers
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getTeachers() {
        return $this->teachers;
    }

    /**
     * Add fathers
     *
     * @param \Esolving\Eschool\UserBundle\Entity\Father $fathers
     * @return User
     */
    public function addFather(\Esolving\Eschool\UserBundle\Entity\Father $fathers) {
        $this->fathers[] = $fathers;

        return $this;
    }

    /**
     * Remove fathers
     *
     * @param \Esolving\Eschool\UserBundle\Entity\Father $fathers
     */
    public function removeFather(\Esolving\Eschool\UserBundle\Entity\Father $fathers) {
        $this->fathers->removeElement($fathers);
    }

    /**
     * Get fathers
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getFathers() {
        return $this->fathers;
    }

}