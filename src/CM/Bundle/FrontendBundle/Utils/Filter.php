<?php

namespace CM\Bundle\FrontendBundle\Utils;

class Filter {

	private $fanPageId = null;
	private $fanPageName = '';
	private $tags = '';
	private $text = '';
	private $qualification = '';
	private $rango = '';
	
	public function getFanPageId() {
		return $this->fanPageId;
	}

	public function setFanPageId($fanPageId) {
		$this->fanPageId = $fanPageId;
	}
	
	public function setFanPageName($fanPageName) {
		$this->fanPageName = $fanPageName;
	}
	public function getFanPageName() {
		return $this->fanPageName;
	}

	public function getTags() {
		return $this->tags;
	}

	public function setTags($tags) {
		$this->tags = $tags;
	}

	public function getText() {
		return $this->text;
	}

	public function setText($text) {
		$this->text = $text;
	}

	public function getRango() {
		return $this->rango;
	}

	public function setRango($rango) {
		$this->rango = $rango;
	}

	public function getQualification() {
		return $this->qualification;
	}

	public function setQualification($qualification) {
		$this->qualification = $qualification;
	}


	public function clear() {
		$this->fanPageId = null;
		$this->tags = array();
		$this->text = '';
		$this->qualification = '';
	}

	public function toArray() {
		$filters = array();
		$filters['fan_page'] = $this->fanPageId;
		if($this->tags != '') {
			$filters['tags'] = $this->tags;
		}
		if($this->text != '') {
			$filters['messages'] = $this->text;
		}
		if($this->qualification != '') {
			$filters['calificacion'] = $this->qualification;
		}
		return $filters;
	}
	
	
	public function toArrayPost() {
		$filters = array();
		$filters['fanPage'] = intval($this->fanPageId);
		
		if($this->rango != '') {
			$filters['rango'] = $this->rango;
		}

		if($this->text != '') {
			// $filters['message'] = "\"".$this->text."\"";
			// $filters['type'] = "\"".$this->text."\"";
			// $filters['caption'] = "\"".$this->text."\"";
			// $filters['description'] = "\"".$this->text."\"";
			// $filters['link'] = "\"".$this->text."\"";
			// $filters['story'] = "\"".$this->text."\"";
			// $filters['nameExtra'] = "\"".$this->text."\"";
			$filters['stringDeBusqueda'] = "\"".$this->text."\"";

		}
		
		if($this->qualification != '') {
			$filters['qualification'] = $this->qualification;
		}
		
		return $filters;
	}
}