<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as BaseUser;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @ORM\Table(name="`users`")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Comment", mappedBy="user", orphanRemoval=true)
     */
    private $comments;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Article", mappedBy="user", orphanRemoval=true)
     */
    private $articles;

    public function __construct()
    {
        parent::__construct();
        $this->comments = new ArrayCollection();
        $this->articles = new ArrayCollection();
    }

    /**
     * @return Collection|Comment[]
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): self
    {
        if ( !$this->comments->contains($comment) ) {
            $this->comments[] = $comment;
            // set the *owning* side!
            $comment->setUser($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ( $this->comments->contains($comment) ) {
            $this->comments->removeElement($comment);
            // set the owning side to null (unless already changed)
            if ( $comment->getUser() === $this ) {
                $comment->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Article[]
     */
    public function getArticles(): Collection
    {
        return $this->articles;
    }

    public function addArticle(Article $article): self
    {
        if ( !$this->articles->contains($article) ) {
            $this->$article[] = $article;
            // set the *owning* side!
            $article->setUser($this);
        }

        return $this;
    }

    public function removeArticle(Article $article): self
    {
        if ( $this->articles->contains($article) ) {
            $this->articles->removeElement($article);
            // set the owning side to null (unless already changed)
            if ( $article->getUser() === $this ) {
                $article->setUser(null);
            }
        }

        return $this;
    }
}
