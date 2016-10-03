<?php

namespace JGerdes\SchauBot\Entity;

/**
 * @Entity
 * @Table(name="sb_movies")
 */
class Movie {

	/**
     * @Id @Column(type="integer")
     * @GeneratedValue
     */
    private $id;

    /**
     * @Column(type="string", length=140, unique=true) 
     */
    private $title;

    /**
     * @Column(type="datetime", name="released_at")
     */
    private $releaseDate;

    /**
     * @Column(type="text")
     */
    private $description;

    /**
     * @Column(type="integer")
     */
    private $duration;

    /** 
     * @Column(type="integer", name="content_rating") 
     */
    private $contentRating;

    /**
     * @Column(type="boolean", name="is_3d")
     */
    private $is3D;

   	public function getId() {
		return $this->id;
	}
	public function getTitle() {
		return $this->title;
	}
	public function getReleaseDate() {
		return $this->releaseDate;
	}
	public function getDescription() {
		return $this->description;
	}
	public function getDuration() {
		return $this->duration;
	}
	public function getContentRating() {
		return $this->contentRating;
	}
	public function is3D() {
		return $this->is3D;
	}


}

?>