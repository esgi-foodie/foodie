<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Entity\Traits\TimestampableTrait;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Annotations;




/**
 * @ORM\Entity(repositoryClass="App\Repository\RelationshipRepository")
 * @UniqueEntity(fields={"followed_id", "follower_id"})
 */
class Relationship
{
//    use TimestampableTrait;
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="followed")
     * @ORM\JoinColumn(name="follower_id", referencedColumnName="id")
     */
    private $followed;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="follower")
     * @ORM\JoinColumn(name="followed_id", referencedColumnName="id")
     */
    private $follower;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFollowed(): ?User
    {
        return $this->followed;
    }

    public function setFollowed(?User $followed): self
    {
        $this->followed = $followed;

        return $this;
    }

    public function getFollower(): ?User
    {
        return $this->follower;
    }

    public function setFollower(?User $follower): self
    {
        $this->follower = $follower;

        return $this;
    }
}
