<?php
class db{
    private $handle;
    private $conf;
    private $sql;
    private $log;

    static public function instance()
    {
        static $obj;
        if(!is_object($obj)){
            $class = __CLASS__;
            $obj = new $class();
        }
        $obj->init();
        return $obj;
    }

    public function init()
    {
        $this->conf = require_once 'dbconf.php';
    }

    public function connect()
    {
        if(!$this->handle){
            $dsn = "mysql:host=".$this->conf['host'].';port='.$this->conf['port'].';dbname='.$this->conf['db'];
            $user = $this->conf['user'];
            $password = $this->conf['password'];
            $this->handle = new PDO($dsn,$user,$password);
            $this->handle->exec('SET NAMES UTF8');
        }
        return $this;
    }

    public function setDebug($info)
    {
        $this->log[] = $info;
    }

    public function getDebug()
    {
        return $this->log;
    }

    private function getInsertKeyValue($data)
    {
        $key = $value = "(";
        $keyTmp = $valTmp = "";
        foreach ($data as $k => $v){
            $keyTmp .= ",`$k`";
            $valTmp .= ",'".addslashes($v)."'";
        }
        $key .= substr($keyTmp,1).")";
        $value .= substr($valTmp,1).")";
        return array('key' => $key, 'value' => $value);
    }


    public function insert($table,$data)
    {
        $keyvalue = $this->getInsertKeyValue($data);
        $this->sql = "INSERT INTO ".$table. $keyvalue['key']." VALUES ".$keyvalue['value'];
        $this->setDebug($this->sql);
        $sth = $this->handle->prepare($this->sql);
        if(!$sth->execute()){
            $this->setDebug($sth->errorInfo());
            return false;
        }
        $last_insert_id = $this->handle->lastInsertID();
        return $last_insert_id;
    }

    public function findAll($sql)
    {
        $this->sql = $sql;
        $this->setDebug($this->sql);
        $sth = $this->handle->query($sql);
        if($sth === false){
            $this->setDebug($this->handle->errorInfo());
            return false;
        }
        $data = $sth->fetchAll(PDO::FETCH_ASSOC);
        return $data;
    }
}