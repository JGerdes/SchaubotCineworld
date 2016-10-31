<?php

namespace JGerdes\SchauBot\Entity;

/**
 * @Entity
 * @Table(name="sb_screening")
 */
class Screening {

	/**
	 * @Id @Column(type="integer")
	 * @GeneratedValue
	 */
	private $id;

	/**
	 * @Column(type="datetime", name="time")
	 */
	private $time;

	/**
	 * @Column(type="integer")
	 */
	private $hall;

	/** 
	 * @Column(type="integer", name="res_id") 
	 */
	private $resId;

	/**
     * @ManyToOne(targetEntity="Movie")
     * @JoinColumn(name="movie_id", referencedColumnName="id")
     */
    private $movie;


	public function getId() {
		return $this->id;
	}

	public function setId($id) {
		$this->id = $id;
	}


	public function getTime() {
		return $this->time;
	}

	public function setTime($time) {
		$this->time = $time;
	}

	public function getHall() {
		return $this->hall;
	}

	public function setHall($hall) {
		$this->hall = $hall;
	}

	public function getResId() {
		return $this->resId;
	}

	public function setResId($resId) {
		$this->resId = $resId;
	}

    /**
     * @return Movie
     */
    public function getMovie() {
		return $this->movie;
	}

	public function setMovie($movie) {
		$this->movie = $movie;
	}


	

}

?>