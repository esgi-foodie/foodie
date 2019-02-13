<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Entity\Traits\TimestampableTrait;
use Doctrine\ORM\Mapping\UniqueConstraint;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Table(name="like_recipe",
 *     uniqueConstraints={
 *        @UniqueConstraint(name="uniq_user_like_recipe",
 *            columns={"liker_id", "recipe_id"})
 *    })
 * @UniqueEntity(
 *     fields={"host", "port"},
 *     errorPath="port",
 *     message="This port is already in use on that host."
 * )
 * @ORM\Entity(repositoryClass="App\Repository\LikeRepository")
 *
 */
class Like
{
    use TimestampableTrait;
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Recipe", inversedBy="likes",cascade={"persist"})
     * @ORM\JoinColumn(name="recipe_id", referencedColumnName="id",nullable=false)
     */
    private $recipe;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="likes")
     * @ORM\JoinColumn(nullable=false)
     */
    private $liker;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRecipe(): ?Recipe
    {
        return $this->recipe;
    }

    public function setRecipe(?Recipe $recipe): self
    {
        $this->recipe = $recipe;

        return $this;
    }

    public function getLiker(): ?User
    {
        return $this->liker;
    }

    public function setLiker(?User $liker): self
    {
        $this->liker = $liker;

        return $this;
    }
}
