<?php
namespace gitzw\site\data;

use gitzw\site\ext\ResizeImage;
use Exception;

/**
 * ImageData is capable of creating images of any desired size and providing the img-element
 * for them.
 * 
 * Required: php-extensions gd.
 * 
 */
class ImageData {
    
    /**
     * Allowed options for resizing.
     * 
     * @var array
     */
    const OPTIONS = ['default'=>'_d', 'exact'=>'_e', 'maxwidth'=>'_w', 'maxheight'=>'_h'];
    
    /**
     * The directory name for derived images.
     * 
     * @var string
     */
    const STORAGE_DIR = 'derived';
    
    private $imgFile;
    private $imgSize;
    
    /**
     * Construct new ImageData for the given filename.
     * 
     * @param string $imgFile file name of the original image
     */
    function __construct(string $imgFile) {
        $this->imgFile =$imgFile;
    }
    
    /**
     * Gets the complete array of data also gotten with php-extensions gd <code>getimagesize</code>.
     * 
     * @return array
     */
    public function getImgSize() : array {
        if (empty($this->imgSize)) {
            $this->imgSize = getimagesize($this->imgFile);
        }
        return $this->imgSize;
    }
    
    /**
     * Get the media type of the original image.
     * 
     * @return string
     */
    public function getMediaType() : string {
        return $this->getImgSize()['mime'];
    }
    
    /**
     * Gets an array with "width" and "height" of the original as keys.
     * 
     * @return array
     */
    public function getSize() : array {
        $this->getImgSize();
        return ['width'=>$this->imgSize[0], 'height'=>$this->imgSize[1]];
    }
    
    
    public function getFilename(string $postfix) : string {
        $dirname = dirname($this->imgFile);
        $storage = $dirname.DIRECTORY_SEPARATOR.self::STORAGE_DIR;
        if (!file_exists($storage)) {
            if (!mkdir($storage)) {
                throw new Exception('Cannot create storage: '.$storage);
            }
        }
        $parts = explode('.', basename($this->imgFile));
        return $storage.DIRECTORY_SEPARATOR.$parts[0].$postfix.'.'.$parts[1];
    }
    
    public function getImgLocation(string $postfix) : string {
        return substr($this->getFilename($postfix), strlen(Site::get()->documentRoot()));
    }
    
    public function resize(int $width, int $height, string $resizeOption='default') : array {
        if (is_null(self::OPTIONS[strtolower($resizeOption)])) {
            throw new Exception('Invalid resizeOption: '.$resizeOption);
        }
        $postfix = '_'.$width.'_'.$height.self::OPTIONS[strtolower($resizeOption)];
        $filename = $this->getFilename($postfix);
        $location = substr($this->getFilename($postfix), strlen(Site::get()->documentRoot()));
        if (!file_exists($filename)) {
            $ri = new ResizeImage($this->imgFile);
            $ri->resizeTo($width, $height, $resizeOption);
            $ri->saveImage($filename);
        }
        $size = getimagesize($filename);
        return ['filename'=>$filename, 'location'=>$location, 'size'=>$size];
    }
    
    public function getImgTag(int $width, int $height, string $alt='', string $resizeOption='default') {
        $data = $this->resize($width, $height, $resizeOption);
        // specifying width and height in tag will cause distortion of size when changing
        // orientation of device (with current css props).
        $tag = '<img src="'.
            $data['location'].'" alt="'.$alt.'">';
        return $tag;
    }
}

