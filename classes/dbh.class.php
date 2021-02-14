<?php
    
    /**
     * Connection to the database by using PDO
     * SQL connection
     */
    class Dbh {
        private $dbServerName;
        private $dbUserName;
        private $dbPassword;
        private $dbName;
        private $charSet;


        public function connect(){
            $this->dbServerName = 'localhost';
            $this->dbUserName = 'root';
            $this->dbPassword ='root';//For XAMAPP it is empty, For MAMP it is 'root'
            $this->dbName = 'myDatabase';
            $this->charSet = 'utf8mb4';

            try {
                $dsn = 'mysql:host='.$this->dbServerName.';dbname='.$this->dbName.';charset='.$this->charSet;
                $pdo = new PDO($dsn, $this->dbUserName, $this->dbPassword);
                $pdo -> setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
                return $pdo;
            }catch(\Exception $error){
                return 'Connection Failed: -> '.$error->getMessage();
            }
            
        }
    }