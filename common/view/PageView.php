<?php


namespace common\view;

class PageView {
  
  private $metaTags = array();
  private $charset;
  
  public function __construct($charset = "utf-8") {
    $this->charset = $charset;
  }
  
  public function AddStyleSheet($href) {
    $this->metaTags[] = "<link rel='StyleSheet' href='$href' type='text/css'";
  }
  
  private function BuildHeadTags($isXML) {
    $end = ">";
    if ($isXML) {
      $end = "/>";
    }
    $retValue = "";
    foreach($this->metaTags as $tag) {
      $retValue .= $tag . "$end\n            ";
    }
    return $retValue;
  }
  
  public function GetHTMLPage($title, $body) {
    
    $head = $this->BuildHeadTags(false);
    
    $html = "
        <!DOCTYPE HTML SYSTEM>
        <html>
          <head>
            <title>$title</title>
            <meta http-equiv='content-type' content='text/html; charset=$this->charset'>
            $head
          </head>
          <body>
            $body
          </body>
        </html>";
        
    return $html;
  }
  
  public function GetXHTML10StrictPage(\common\view\Page $page) {
    $head = $this->BuildHeadTags(true);
    $xml = "
        <!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Strict//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd\"> 
        <html xmlns=\"http://www.w3.org/1999/xhtml\"> 
          <head> 
             <title>$page->title</title> 
             <meta http-equiv=\"content-type\" content=\"text/html; charset=$this->charset\" /> 
             $head
          </head> 
          <body>
            $page->body
          </body>
        </html>";
    return $xml;
  }
}
