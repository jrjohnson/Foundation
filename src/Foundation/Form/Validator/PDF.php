<?php
namespace Foundation\Form\Validator;
/**
 * Ensure the uploaded file is a pdf
 */
class PDF extends AbstractValidator{
  public function validate(\Foundation\Form\Input $input){
    //will get called even on an empty file so we have to check
    if(is_null($input->get($this->e->getName()))) return true;
    $validMimeTypes = array('application/pdf',
                            'application/pdf; charset=binary',
                            'application/x-pdf',
                            'application/acrobat',
                            'applications/vnd.pdf',
                            'text/pdf',
                            'text/x-pdf');
    $fileArr = $input->get($this->e->getName());
    //simplest check, however the type is sent by the browser and can be forged
    //octet-stream is the default mime type for any unknown binary file and is sent by some browsers for PDFs so check it here
    //Do this seperatly becuase it isn't really a valid mime types and shouldn't pass the file info check
    if(!\in_array($fileArr['type'], $validMimeTypes) AND !\in_array($fileArr['type'], array('application/octet-stream', 'binary/octet-stream'))){
      $this->addError("Your browser is reporting that this is a file of type {$fileArr['type']} which is not a valid PDF.");
      return false;
    }
    //obviously easily changed but check the extension
    $arr = explode('.', $fileArr['name']);
    $extension = array_pop($arr);
    if(strtolower($extension) != 'pdf'){
      $this->addError("This is a file has the extension .{$extension} .pdf is required.");
      return false;
    }
    $finfo = finfo_open(FILEINFO_MIME);
    $mimetype = finfo_file($finfo, $fileArr['tmp_name']);
    finfo_close($finfo);
    if(!\in_array($mimetype, $validMimeTypes)){
      $this->addError("This is a file of type {$mimetype} which is not a valid PDF.");
      return false;
    }
    return true;
  }
}
?>
