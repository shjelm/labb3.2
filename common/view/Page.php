<?php

  namespace common\view;

  class Page {
      
    public $title = "";
    public $body = "";
    
  	public function __construct($title, $body) {
  		$this->title = $title;
  		$this->body = $body;
  	}
	
    
    public function Merge(Page $otherPage) {
      $ret = new Page();
      
      $ret->title = $this->title . " " . $otherPage->title;
      $ret->body = $this->body . "\n" .$otherPage->body;
      
      return $ret;
    }
  }

