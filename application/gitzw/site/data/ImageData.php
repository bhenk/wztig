<?php
namespace gitzw\site\data;

use gitzw\GZ;
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
    const OPTIONS = ['default'=>'_d', 'exact'=>'_e', 'maxwidth'=>'_w', 'maxheight'=>'_h', 'max'=>'_m'];
    
    /**
     * The directory name for derived images.
     * 
     * @var string
     */
    const STORAGE_DIR = 'derived';
    
    private $imgFile;
    private $imgSize;
    private $imgExists = true;
    
    /**
     * Construct new ImageData for the given filename.
     * If $imgFile is NULL will look at $dataFile.
     * 
     * @param string $imgFile absolute path of the original image file, maybe null
     * @param string $dataFile relative path in data/images
     */
    function __construct(?string $imgFile, string $dataFile = NULL) {
    	if (is_null($imgFile)) {
    		if (!empty($dataFile)) {
    			$this->imgFile = GZ::DATA.'/images/'.$dataFile;
    		}
    	} else {
        	$this->imgFile =$imgFile;
    	}
        if (!file_exists($this->imgFile)) {
        	$this->imgExists = false;
        	$this->imgFile = GZ::DATA.'/images/no_image/Broken-image-01.jpg';
        }
    }
    
    /**
     * Does the file exist.
     * 
     * @return bool
     */
    public function imgExists() : bool {
    	return $this->imgExists;
    }
    
    /**
     * Get the absolute path to this image file.
     * 
     * @return string
     */
    public function getImgFile() : string {
    	return $this->imgFile;
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
    
    /**
     * Get the absolute path for the derived image.
     * 
     * @param string $postfix postfix will be attached to the basename of this root image
     * @throws Exception if we cannot create directories
     * @return string the absolute path for the derived image
     */
    public function getFilename(string $postfix) : string {
    	$derivedDir = Site::get()->documentRoot().'/img/derived';
    	// $derivedDir = '/Users/ecco/git2/wztig/application/public_html/img/derived';
    	// GZ::DATA.'/images/hnq/2020/xyz/_DSC123.jpg'
    	$path = substr(dirname($this->imgFile), strlen(GZ::DATA.'/images/'));
    	$parts = explode('.', basename($this->imgFile));
    	$newFile = $derivedDir.'/'.$path.'/'.$parts[0].$postfix.'.'.$parts[1];
    	$newPath = dirname($newFile);
    	if (!file_exists($newPath)) {
    		if (!mkdir($newPath, 0777, TRUE)) {
    			throw new Exception('Cannot create new path: '.$newPath);
    		}
    	}
    	return $newFile;
    }
    
    /**
     * Get the url for a derived image.
     * 
     * @param string $postfix
     * @return string
     */
    public function getImgLocation(string $postfix) : string {
        return substr($this->getFilename($postfix), strlen(Site::get()->documentRoot()));
    }
    
    /**
     * Resize the image according to given specifications.
     * 
     * @param int $width desired width
     * @param int $height desired height
     * @param string $resizeOption 'default'=>'_d', 'exact'=>'_e', 'maxwidth'=>'_w', 'maxheight'=>'_h'
     * @throws Exception for invallid resize options
     * @return array with <ul><li>'filename'=>absolute path for the derived image,</li>
     * 						<li>'location'=>the url for a derived image,</li>
     * 						<li>'size'=>gd <code>getimagesize</code></li></ul>
     * 
     */
    public function resize(int $width, int $height, string $resizeOption='default') : array {
        if (is_null(self::OPTIONS[strtolower($resizeOption)])) {
            throw new Exception('Invalid resizeOption: '.$resizeOption);
        }
        if ($resizeOption == 'max') {
        	$this->getImgSize();
        	$width = $this->imgSize[0];
        	$height = $this->imgSize[1];
        	$resizeOption = 'default';
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

