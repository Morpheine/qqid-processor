<?php

//Shibboleth Login Script #1
class Member
{
    private $db;
    private $mem_id;
    private $mem_utorid;
    private $area_id;
    private $area_name;
    private $mem_lastseen;
    private $mem_role;

    public function __construct($member_id = false)
    {
        $this->setDb(new Database());
        if ($member_id) {
            if (!is_numeric($member_id)) {
                $this->setMemId($this->utorToWebD($member_id));
            } else {
                $this->setMemId($member_id);
            }
            if ($this->getMemId() != 999) {
                $this->populate($this->getMemId());
            }
        }
    }

    private function utorToWebD($utorid)
    {
        $s_utorid = !is_null($utorid) ? "'" . $this->getDb()->getDb()->real_escape_string($utorid) . "'" : "NULL";
        $result = $this->getDb()->qry("SELECT mem_id FROM outline_member WHERE mem_utorid = $s_utorid");
        $row = $result->fetch_assoc();
        if ($row['mem_id']) {
            return $row['mem_id'];
        } else {
            die("You do not have permission to use this resource");
        }
    }

    public function isAdmin()
    {
        if ($this->getMemRole() == 1) {
            return true;
        } else {
            return false;
        }
    }

    private function populate($member_id = false)
    {
        if ($member_id) {
            $result = $this->getDb()->qry("SELECT m.*, a.area_id, a.area_name FROM outline_member m LEFT JOIN outline_area a ON a.area_admin = m.mem_id WHERE m.mem_id = " . $this->getMemId());
            $row = $result->fetch_assoc();
            $this->setMemId($row['mem_id']);
            $this->setMemRole($row['mem_role']);
            $this->setAreaid($row['area_id']);
            $this->setAreaName($row['area_name']);
            $this->setMemLastseen($row['mem_lastseen']);
            if ($this->getMemId() != 999) {
                $this->setMemUtorid($row['mem_utorid']);
            }
        }
    }

    public function getMemRole()
    {
        return $this->mem_role;
    }

    public function setMemRole($role_id)
    {
        $this->mem_role = $role_id;
    }

    public function getMemUtorid()
    {
        return $this->mem_utorid;
    }

    public function setMemUtorid($utorid)
    {
        $this->mem_utorid = $utorid;
    }

    public function getMemId()
    {
        return $this->mem_id;
    }

    public function setMemId($mem_id)
    {
        $this->mem_id = $mem_id;
    }

    public function getDb()
    {
        return $this->db;
    }

    public function setDb($db)
    {
        $this->db = $db;
    }

    public function getAreaid()
    {
        return $this->area_id;
    }

    public function setAreaid($mem_group)
    {
        $this->area_id = $mem_group;
    }

    public function getAreaName()
    {
        return $this->area_name;
    }

    public function setAreaName($mem_group_name)
    {
        $this->area_name = $mem_group_name;
    }

    /**
     * @return mixed
     */
    public function getMemLastseen()
    {
        return $this->mem_lastseen;
    }

    /**
     * @param mixed $mem_lastseen
     */
    public function setMemLastseen($mem_lastseen)
    {
        $this->mem_lastseen = $mem_lastseen;
    }
}
?>