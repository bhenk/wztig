<?php
namespace gitzw\site\data;

use gitzw\GZ;
use gitzw\site\logging\Log;
use function PHPUnit\Framework\isEmpty;

class Security extends JsonData {
    
    private static $instance;
    
    public static function get() : Security {
        if (is_null(self::$instance)) {
            Log::log()->info('Instantiating Security'."\n");
            self::$instance = new Security();
        }
        return self::$instance;
    }
    
    
    private $users = array();
    private $sessionUser;
    private $canLogin;
    
    
    function __construct() {
        $authData = $this->load();
        foreach ($authData['users'] as $name=>$data) {
            $user = new User($name, $data);
            $this->users[$name] = $user;
        }
    }
    
    public function canLogin() : bool {
        if (!isset($this->canLogin)) {
            $this->canLogin = !empty($this->getUsersByIp());
        }
        return $this->canLogin;
    }
    
    public function hasAccess() : bool {
        return NULL !== $this->getSessionUser();
    }
    
    public function getUsersByIp(string $ip = NULL) : array {
        if (empty($ip)) {
            $ip = Site::get()->clientIp();
        }
        $maybeUsers = array();
        foreach (array_values($this->users) as $user) {
            if ($user->hasIp($ip)) {
                array_push($maybeUsers, $user);
            }
        }
        return $maybeUsers;
    }
    
    public function getUserbyName(string $name) {
        return $this->users[$name];
    }
    
    public function jsonSerialize() {
        return ['users'=>$this->users];
    }
    
    public function getFile() : string {
        return GZ::DATA.'/auth.json';
    }
    
    public function startSession(User $user) {
        $this->sessionUser = $user;
        $this->sessionUser->setLastLogin(date('Y-m-d H:i:s'));
        $this->persist();
        session_start();
        $_SESSION["loggedin"] = TRUE;
        $_SESSION["username"] = $user->getName();
        $_SESSION["full_name"] = $user->getFullName();
        $_SESSION["client_ip"] = Site::get()->clientIp();
    }
    
    public function endSession() {
        $this->sessionUser = NULL;
        $_SESSION = array();
        session_destroy();
    }
    
    public function getSessionUser() : ?User {
        if (empty($this->sessionUser)) {
            if (!isset($_SESSION["loggedin"]) or $_SESSION["loggedin"] != TRUE) {
                return NULL;
            }
            if (Site::get()->clientIp() != $_SESSION["client_ip"]) {
                Log::log()->critical("Session hijacked", 
                    ['username'=>$_SESSION["username"],
                        'session_client_ip'=>$_SESSION["client_ip"],
                        'current_client_ip'=>Site::get()->clientIp()
                    ]);
                $this->endSession();
                return NULL;
            }
            $this->sessionUser = $this->getUserbyName($_SESSION["username"]);
        }
        return $this->sessionUser;
    }
}

