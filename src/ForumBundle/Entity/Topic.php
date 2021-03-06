<?php

namespace ForumBundle\Entity;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Hateoas\Configuration\Annotation as Hateoas;
use Gedmo\Mapping\Annotation as Gedmo;
use JMS\Serializer\Annotation as JMS;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="ForumBundle\Repository\TopicRepository")
 * @ORM\Table(name="forum_topic")
 *
 * @Hateoas\Relation(
 *     "self",
 *     href = @Hateoas\Route(
 *         "get_forum_topic",
 *         parameters = { "id" = "expr(object.getId())" }
 *     )
 * )
 * @Hateoas\Relation(
 *     "forum",
 *     href = @Hateoas\Route(
 *         "get_forum_forum",
 *         parameters = { "id" = "expr(object.getForum().getId())" }
 *     )
 * )
 * @Hateoas\Relation(
 *     "posts",
 *     href = @Hateoas\Route(
 *         "get_forum_topic_posts",
 *         parameters = { "id" = "expr(object.getId())" }
 *     )
 * )
 */
class Topic
{
    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\Column(name="id", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @Assert\NotBlank
     * @Assert\Length(max=100)
     *
     * @ORM\Column(name="title", type="string", length=100)
     */
    protected $title;

    /**
     * @var string
     *
     * @Assert\Length(max=100)
     *
     * @ORM\Column(name="description", type="string", length=100, nullable=true)
     */
    protected $description;

    /**
     * @var DateTime
     *
     * @Gedmo\Timestampable(on="create")
     *
     * @ORM\Column(name="created_at", type="datetime")
     */
    protected $createdAt;

    /**
     * @var DateTime
     *
     * @Gedmo\Timestampable(on="change", field={"title", "description"})
     *
     * @ORM\Column(name="updated_at", type="datetime", nullable=true)
     */
    protected $updatedAt;

    /**
     * @var Forum
     *
     * @Assert\NotNull
     *
     * @ORM\ManyToOne(targetEntity="ForumBundle\Entity\Forum", cascade={"persist"}, inversedBy="topics")
     * @ORM\JoinColumn(nullable=false)
     *
     * @JMS\Exclude
     */
    protected $forum;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="ForumBundle\Entity\Post", cascade={"remove"}, mappedBy="topic")
     *
     * @JMS\Exclude
     */
    protected $posts;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->posts = new ArrayCollection();
    }

    /**
     * Gets the id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Sets the title.
     *
     * @param string $title
     *
     * @return self
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Gets the title.
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Sets the description.
     *
     * @param string $description
     *
     * @return self
     */
    public function setDescription($description = null)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Gets the description.
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Sets the creation date.
     *
     * @param DateTime $createdAt
     *
     * @return self
     */
    public function setCreatedAt(DateTime $createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Gets the creation date.
     *
     * @return DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Sets the last update date.
     *
     * @param DateTime $updatedAt
     *
     * @return self
     */
    public function setUpdatedAt(DateTime $updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Gets the last update date.
     *
     * @return DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Sets the forum.
     *
     * @param Forum $forum
     *
     * @return self
     */
    public function setForum(Forum $forum = null)
    {
        $this->forum = $forum;

        return $this;
    }

    /**
     * Gets the forum.
     *
     * @return Forum
     */
    public function getForum()
    {
        return $this->forum;
    }

    /**
     * Adds a post.
     *
     * @param Post $post
     *
     * @return self
     */
    public function addPost(Post $post)
    {
        $this->posts->add($post);
        $post->setTopic($this);

        return $this;
    }

    /**
     * Removes a post.
     *
     * @param Post $post
     *
     * @return self
     */
    public function removePost(Post $post)
    {
        $this->posts->removeElement($post);

        return $this;
    }

    /**
     * Gets all posts.
     *
     * @return ArrayCollection
     */
    public function getPosts()
    {
        return $this->posts;
    }
}
