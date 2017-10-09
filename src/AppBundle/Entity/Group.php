<?php
namespace AppBundle\Entity;

use FOS\UserBundle\Model\Group as BaseRole;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\GroupRepository")
 * @ORM\Table(name="role")
 */
class Group extends BaseRole
{
    const ROLE_USER = 'ROLE_USER';
    const ROLE_STORE_KEEPER = 'ROLE_STORE_KEEPER';
    const ROLE_ADMIN = 'ROLE_ADMIN';
    const ROLE_ACCOUNTANT = 'ROLE_ACCOUNTANT';
    const ROLE_CLIENT = 'ROLE_CLIENT';
    
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @Assert\Length(max = 30)
     * @Assert\NotBlank()
     * @ORM\Column(name="code", type="string", length=30, nullable=false)
     */
    private $code;

    public function __construct($name, array $roles, $code)
    {
        parent::__construct($name, $roles);
        $this->code = $code;
    }


    /**
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param string $code
     */
    public function setCode($code)
    {
        $this->code = $code;
    }
}
