<?php

/**
 * Created by PhpStorm.
 * User: webste52
 * Date: 3/24/2015
 * Time: 11:12 AM
 */
class TransQQid
{

    private $connection;
    private $error;

    /**
     * @param $type
     */
    public function __construct($type)
    {
        if ($type == 'utorauth') {
            if (!$this->conUAuth()) {
                // todo anything relying on the connection goes here
            }
        } else if ($type == 'conBB') {
            if (!$this->conBB()) {
                // todo anything relying on the connection goes here
            }
        }
    }

    /**
     * @param $localFile
     * @param $remoteFile
     * @return bool
     */
    public function sendFile($localFile, $remoteFile)
    {
        if ($this->getConnection()) {
            $sendCSV = ssh2_scp_send($this->getConnection(), $localFile, $remoteFile, 0644);
            if (!$sendCSV) {
                $this->setError('Sending Failed');
                return false;
            } else {
                //echo "Transfer to UTORAUTH SUCCESSFUL!";
                return true;
            }
        } else {
            $this->setError('Connection non-existent');
            return false;
        }
    }

    /**
     * @param $localFile
     * @param $remoteFile
     * @return bool
     */
    public function recvFile($localFile, $remoteFile)
    {
        if ($this->getConnection()) {
            $recvCSV = ssh2_scp_recv($this->getConnection(), $remoteFile, $localFile);
            if ($recvCSV) {
                return $recvCSV;
            } else {
                $this->setError("Unable to receive file.");
                return false;
            }
        } else {
            $this->setError('Connection non-existent');
            return false;
        }
    }

    /**
     * @return bool
     */
    private function conUAuth()
    {
        $this->setConnection(ssh2_connect('batch.auth.utoronto.ca', 22));
        if ($this->getConnection()) {
            if (ssh2_auth_password($this->getConnection(), 'REDACTED', 'REDACTED')) {
                return true;
            } else {
                $this->setError('Connection established, Authentification failed');
                return false;
            }
        } else {
            $this->setError('Could not establish ssh connection');
            return false;
        }
    }

    /**
     * @return bool
     */
    private function conBB()
    {
        $this->setConnection(ssh2_connect('142.150.183.147', 22));
        if ($this->getConnection()) {
            if (ssh2_auth_password($this->getConnection(), 'REDACTED', 'REDACTED!')) {
                $this->setConnection(ssh2_sftp($this->getConnection()));
                return true;
            } else {
                $this->setError('Connection established, Authentification failed');
                return false;
            }
        } else {
            $this->setError('Could not establish ssh connection');
            return false;
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

    /**
     * @return mixed
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * @param mixed $error
     */
    public function setError($error)
    {
        $this->error = $error;
    }

} 