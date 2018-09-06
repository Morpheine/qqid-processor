<?php

/**
 * Created by PhpStorm.
 * User: webste52
 * Date: 3/24/2015
 * Time: 11:12 AM
 */
class SSHConnect
{

    private $connection;

    public function __construct($type)
    {
        if($type == 'utorauth'){
            $this->conUAuth();
        }
    }

    private function conUAuth()
    {
        $this->setConnection(ssh2_connect('batch.auth.utoronto.ca', 22));
        if (!$this->setConnection(ssh2_connect('batch.auth.utoronto.ca', 22))) {
            if(ssh2_auth_password($this->getConnection(), 'REDACTED', 'REDACTED')){

            }else {
                die("Could not authenticate");
            }
        } else {
            die ("Could not establish ssh connection");

        }
    }


    /**
     * @return mixed
     */
    public function getConnection()
    {
        return $this->connection;
    }

    /**
     * @param mixed $connection
     */
    public function setConnection($connection)
    {
        $this->connection = $connection;
    }

} 