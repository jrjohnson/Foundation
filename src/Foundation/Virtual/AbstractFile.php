<?php
namespace Foundation\Virtual;
/**
 * An abstract file type
 * @package Foundation\Virtual
 */
abstract class AbstractFile implements File
{
  /**
   * The mimetype of file
   * @var string
   */
  protected $_mimeType;
  
  /**
   * The name of the file
   * @var string
   */
  protected $_name;
  
  /**
   * Constructor
   * @param string $name
   */
  public function __construct($name){
    $this->_name = $name;
    $this->_mimeType = false;
  }
  
  /**
   * Guess the mime type file contents
   * @param string $fileContents
   * @return string
   */
  protected function guessMimeType($fileContents){
    //first try a simple extension type
    $mimeTypes = array(
      'txt' => 'text/plain',
      'css' => 'text/css',
      'js' => 'application/javascript',
      'htm' => 'text/html',
      'html' => 'text/html',
      'xml' => 'application/xml',
      'swf' => 'application/x-shockwave-flash',
      'flv' => 'video/x-flv',
      'png' => 'image/png',
      'jpe' => 'image/jpeg',
      'jpeg' => 'image/jpeg',
      'jpg' => 'image/jpeg',
      'gif' => 'image/gif',
      'bmp' => 'image/bmp',
      'ico' => 'image/vnd.microsoft.icon',
      'tiff' => 'image/tiff',
      'tif' => 'image/tiff',
      'svg' => 'image/svg+xml',
      'svgz' => 'image/svg+xml',
      'pdf' => 'application/pdf'
    );
  
    $extension = strtolower(array_pop(explode('.',$this->getName())));
    if (array_key_exists($extension, $mimeTypes)) {
        return $mimeTypes[$extension];
    }
    
    //then attempt to detect the type from the context
    $finfo = new \finfo(FILEINFO_MIME);
    $mimetype = $finfo->buffer($fileContents);
    finfo_close($finfo);
    return $mimetype;
  }
  
  /**
   * @see File::getName
   */
  public function getName(){
    return $this->_name;
  }
  
  /**
   * Get the mime type
   * Guess it if we haven't set it yet.
   */
  public function getMimeType(){
    if(!$this->_mimeType) $this->_mimeType = $this->guessMimeType($this->getFileContents());
    return $this->_mimeType;
  }
  
  /**
   * Set the Mime type manually
   * @param string $mimeType
   */
  public function setMimeType($mimeType){
    $this->_mimeType = $mimeType;
  }
  
  /**
   * Output the file
   */
  public function output(){
    if (isset($_SERVER['HTTP_IF_MODIFIED_SINCE']) AND false !== $this->getLastModified() ){ 
      $headerLastModified = new \DateTime($_SERVER['HTTP_IF_MODIFIED_SINCE']);
      $headerLastModified->setTimezone(new \DateTimeZone('GMT'));
      if($headerLastModified == $this->getLastModified()){ //is the last modified time identical to the last time the file was modified)
        // This is a cached file send the file time back with a 304 Not Modified header
        header('Last-Modified: ' . $this->getLastModified()->format('D, d M Y H:i:s e'), true, 304);
        exit(0);
      }
    }
    if($this->getLastModified()) header('Last-Modified: ' . $this->getLastModified()->format('D, d M Y H:i:s e'));
    $contents = $this->getFileContents();
    header('Content-Type: ' . $this->getMimeType());
    header('Content-Disposition: attachment; filename='. $this->getName());
    header('Content-Transfer-Encoding: binary');
    header('Content-Length: ' . strlen($contents));
    print $contents;
    exit(0);
  }
}
?>