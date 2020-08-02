<?php
namespace gitzw\site\control;

use gitzw\GZ;
use gitzw\site\data\Site;
use gitzw\site\data\Security;

/**
 * 
 * $hash = password_hash($password, PASSWORD_DEFAULT);
 *
 */
class LoginControl extends DefaultPageControl {
    
    private $userName = '';
    private $userNameError = FALSE;
    private $passwordError = FALSE;
    private $message = '';
    
    function __construct() {
        $this->setContentFile(GZ::TEMPLATES.'/login.php');
        $this->addStylesheet('/css/login.min.css');
    }
    
    public function renderPage() {
        if (Site::get()->requestMethod() == 'POST') {
            $this->userName = $_POST['username'];
            $password = $_POST['password'];
            $this->userNameError = empty($this->userName);
            $this->passwordError = empty($password);
            if ($this->userNameError or $this->passwordError) {
                $this->message = 'Please fill in missing fields';
                parent::renderPage();
                return;
            }
            $user = Security::get()->getUserbyName($this->userName);
            if (empty($user) or !$user->verifyPass($password)) {
                $this->message = 'Unknown username or password';
                parent::renderPage();
                return;
            }
            $clientIp = Site::get()->clientIp();
            if (!$user->hasIp($clientIp)) {
                $this->message = 'Client IP '.$clientIp.
                    ' not associated with user '.$user->getName();
                parent::renderPage();
                return;
            }
            Security::get()->startSession($user);
            Site::get()->redirect('');
            return;
        }
        parent::renderPage();
    }
    
    protected function getUserName() : string {
        return $this->userName;
    }
    
    protected function hasUserNameError() : bool {
        return $this->userNameError;
    }
    
    protected function hasPasswordError() : bool {
        return $this->passwordError;
    }
    
    protected function getMessage() : string {
        return $this->message;
    }
    
}

