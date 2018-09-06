<?php

class User
{
    private $db;
    private $user_utorid;
    private $user_role;
    private $user_email;
    private $user_active;


    public function __construct($utorid)
    {
        $this->setDb(new Database());
        $this->setUserUtorid($utorid);
        $this->populate();
    }

    public function isAdmin()
    {
        if ($this->getUserRole() == 'SysAdmin') {
            return true;
        } else {
            return false;
        }
    }
    public function isUser(){
        if($this->getUserUtorid()){
            return true;
        }else{
            return false;
        }
    }

    public function isActive(){
        if($this->getUserActive() == 1){
            return true;
        }
        else {
            return false;
        }
    }

    private function populate()
    {
        if ($row = mysqli_fetch_assoc($this->getDb()->qry("SELECT user_utorid, user_role, user_email, user_active FROM bazaar_db.user WHERE user_utorid = '{$this->getUserUtorid()}'"))) {
            $this->setUserRole($row['user_role']);
            $this->setUserEmail($row['user_email']);
            $this->setUserActive($row['user_active']);
        }else{
            $this->setUserUtorid(false);
        }

    }

    public function getUserUtorid()
    {
        return $this->user_utorid;
    }

    public function setUserUtorid($utorid)
    {
        $this->user_utorid = $utorid;
    }

    public function getUserRole()
    {
        return $this->user_role;
    }

    public function setUserRole($role_name)
    {
        $this->user_role = $role_name;
    }

    public function getDb()
    {
        return $this->db;
    }

    public function setDb($db)
    {
        $this->db = $db;
    }

    public function getUserEmail()
    {
        return $this->user_email;
    }

    public function setUserEmail($mem_email)
    {
        $this->user_email = $mem_email;
    }

    /**
     * @return mixed
     */
    public function getUserActive()
    {
        return $this->user_active;
    }

    /**
     * @param mixed $user_active
     */
    public function setUserActive($user_active)
    {
        $this->user_active = $user_active;
    }
}

?>