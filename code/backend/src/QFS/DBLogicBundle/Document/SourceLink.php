<?php


namespace QFS\DBLogicBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 *  * @MongoDB\Document(repositoryClass="QFS\DBLogicBundle\Repository\SourceLinkRepository")
 */
class SourceLink
{

    /**
     * @MongoDB\Id
     */
    protected $id;

    /**
     * @MongoDB\Field(type="string")
     */
    protected $resource;

    /**
     * @MongoDB\Field(type="string")
     */
    protected $hash;

    /**
     * @MongoDB\Field(type="string")
     */
    protected $link;

    /**
     * @MongoDB\Field(type="int")
     */
    protected $date;

    /**
     * @MongoDB\Field(type="bool")
     */
    protected $is_delete;

    /**
     * @MongoDB\Field(type="bool")
     */
    protected $is_added;

    /**
     * @return mixed
     */
    public function getResource()
    {
        return $this->resource;
    }

    /**
     * @param mixed $resource
     */
    public function setResource($resource)
    {
        $this->resource = $resource;
    }

    /**
     * @return mixed
     */
    public function getHash()
    {
        return $this->hash;
    }

    /**
     * @param mixed $hash
     */
    public function setHash($hash)
    {
        $this->hash = $hash;
    }

    /**
     * @return mixed
     */
    public function getLink()
    {
        return $this->link;
    }

    /**
     * @param mixed $link
     */
    public function setLink($link)
    {
        $this->link = $link;
    }

    /**
     * @return mixed
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param mixed $date
     */
    public function setDate($date)
    {
        $this->date = $date;
    }

    /**
     * @return mixed
     */
    public function getIsDelete()
    {
        return $this->is_delete;
    }

    /**
     * @param mixed $is_delete
     */
    public function setIsDelete($is_delete)
    {
        $this->is_delete = $is_delete;
    }

    /**
     * @return mixed
     */
    public function isAdded()
    {
        return $this->is_added;
    }

    /**
     * @param mixed $is_added
     */
    public function setIsAdded($is_added)
    {
        $this->is_added = $is_added;
    }

    /**
     * Get id
     *
     * @return id $id
     */
    public function getId()
    {
        return $this->id;
    }


    /**
     * Get isAdded
     *
     * @return bool $isAdded
     */
    public function getIsAdded()
    {
        return $this->is_added;
    }
}
