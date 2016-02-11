<?php

namespace we\ClientBundle\Entity;

/**
 * Tag
 */
class Tag
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var integer
     */
    private $photo_id;

    /**
     * @var string
     */
    private $value;

    /**
     * @var \we\ClientBundle\Entity\Photo
     */
    private $photo;


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
     * Set photoId
     *
     * @param integer $photoId
     *
     * @return Tag
     */
    public function setPhotoId($photoId)
    {
        $this->photo_id = $photoId;

        return $this;
    }

    /**
     * Get photoId
     *
     * @return integer
     */
    public function getPhotoId()
    {
        return $this->photo_id;
    }

    /**
     * Set value
     *
     * @param string $value
     *
     * @return Tag
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Get value
     *
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Set photo
     *
     * @param \we\ClientBundle\Entity\Photo $photo
     *
     * @return Tag
     */
    public function setPhoto(\we\ClientBundle\Entity\Photo $photo = null)
    {
        $this->photo = $photo;

        return $this;
    }

    /**
     * Get photo
     *
     * @return \we\ClientBundle\Entity\Photo
     */
    public function getPhoto()
    {
        return $this->photo;
    }
}

