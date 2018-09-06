<?php

class Database
{
    private $db;
    private $type;

    public function __construct($type = 2)
    {
        $this->setType($type);
        $this->db_connect();
    }

    private function db_connect()
    {
        if ($this->getType() == 1) {
            $this->db = mysql_connect($server = 'REDACTED', $username = 'REDACTED', $password = 'REDACTED');
            $this->db->set_charset('utf-8');
            if (!$this->db) {
                die("Could not connect to database: " . $this->db->connect_error . " (" . $this->db->connect_errno . ")");
            }
        } else if ($this->getType() == 2) {
            $this->setDb(mysqli_connect($server = 'REDACTED', $username = 'REDACTED', $password = 'REDACTED', $db = 'REDACTED'));
            $this->db->set_charset('utf-8');
            if (!$this->db) {
                die("Could not connect to database: " . $this->db->connect_error . " (" . $this->db->connect_errno . ")");
            } else {
                echo "Successfully connected to Market!";
            }
        } else if ($this->getType() == 3) {
            $this->setDb(odbc_connect('REDACTED', 'REDACTED', 'REDACTED'));
            if (!$this->getDb()) {
                if (phpversion() < '4.0') {
                    exit("Connection Failed! Your PHP is old: . $php_errormsg");
                } else {
                    exit("<br>Connection Failed ODBC: " . odbc_errormsg());
                }
            }
        }

        return ($this->db);
    }

    public function qry($query)
    {
        if ($this->getType() == 1) {
            return mysql_query($query);
        } else if ($this->getType() == 2) {
            $result = $this->getDb()->query($query);
            //var_dump($query);
            if (!$result) {
                throw new Exception("<pre>" . mysqli_error($this->getDb()) . " [$query]</pre>");
            }
            return $result;
        } else if ($this->getType() == 3) {
            return odbc_exec($this->db, $query);
        }
    }

    public function close()
    {
        if ($this->getType() == 2) {
            $this->getDb()->close();
        } else if ($this->getType() == 3) {
            odbc_close($this->getDb());
        }
    }

    /************************************************
     *    ___     _          __  ___      _         *
     *   / __|___| |_ ___   / / / __| ___| |_ ___   *
     *  | (_ / -_)  _(_-<  / /  \__ \/ -_)  _(_-<   *
     *   \___\___|\__/__/ /_/   |___/\___|\__/__/   *
     ************************************************/
    /**
     * @return mixed
     */
    public function getDb()
    {
        return $this->db;
    }

    /**
     * @param mixed $db
     */
    public function setDb($db)
    {
        $this->db = $db;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }
}