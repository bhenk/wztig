<?php
namespace gitzw\site\data;


class User implements \JsonSerializable {
    
    const FULL_NAME = 'full_name';
    const EMAIL = 'email';
    const HASHED = 'hashed';
    const ROLES = 'roles';
    const IPS = 'ips';
    const LAST_LOGIN = "last_login";
    
    private $name;
    private $fullName;
    private $email;
    private $hashed;
    private $roles = array();
    private $ips = array();
    private $lastLogin;
    
    function __construct(string $name, array $data) {
        $this->name = $name;
        $this->fullName = $data[self::FULL_NAME];
        $this->email = $data[self::EMAIL];
        $this->hashed = $data[self::HASHED];
        $this->roles = $data[self::ROLES];
        $this->ips = $data[self::IPS];
        $this->lastLogin = $data[self::LAST_LOGIN];
    }
    
    public function getFullName() : string {
        return $this->fullName;
    }

    public function getEmail() : ?string {
        return $this->email;
    }

    public function getHashed() : ?string {
        return $this->hashed;
    }

    public function getRoles() : array {
        return $this->roles;
    }

    public function getIps() {
        return $this->ips;
    }
    
    public function hasIp(string $ip) : bool {
        return in_array($ip, $this->ips);
    }

    public function getName() {
        return $this->name;
    }
    
    public function getLastLogin() : ?string {
        return $this->lastLogin;
    }
    
    public function setLastLogin(string $lastLogin) {
        $this->lastLogin = $lastLogin;
    }
    
    public function verifyPass(string $password) : bool {
        return password_verify($password, $this->hashed);
    }

    public function jsonSerialize() {
        return [
            self::FULL_NAME=>$this->fullName,
            self::EMAIL=>$this->email,
            self::HASHED=>$this->hashed,
            self::ROLES=>$this->roles,
            self::IPS=>$this->ips,
            self::LAST_LOGIN=>$this->lastLogin
        ];
    }
}

