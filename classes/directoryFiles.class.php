<?php

    /**
     * It will search for all files on the server and search for possible files that were inserted by someone else
     */
    class DirectoryFiles extends Dbh{

        private $dbhTable = 'fileCheck';
        
        public $warningMessage = '';

        public $dangerWordsArr = array(
            '0' => 'curl_init',
            '1' => 'setopt',
            '2' => 'CURLOPT',
            '3' => 'unlink',
            '4' => 'user.ini',
            '5' => 'anonymousfox',
            '6' => 'FoxAutoV4',
            '7' => 'Xai Syndicate',
            '8' => 'ini_set("memory_limit",-1)',
            '9' => 'ini_set',
            '10' => 'memory_limit',
            '11' => 'adfender',
            '12' => 'proxy',
            '13' => 'all.s5h.net',
            '14' =>'b.barracudacentral.org',
            '15' =>'bl.spamcop.net',
            '16' =>'blacklist.woody.ch',
            '17' =>'bogons.cymru.com',
            '18' =>'cbl.abuseat.org',
            '19' =>'cdl.anti-spam.org.cn',
            '20' =>'combined.abuse.ch',
            '21' =>'db.wpbl.info',
            '22' =>'dnsbl-1.uceprotect.net',
            '23' =>'dnsbl-2.uceprotect.net',
            '24' =>'dnsbl-3.uceprotect.net',
            '25' =>'dnsbl.anticaptcha.net',
            '26' =>'dnsbl.dronebl.org',
            '27' =>'dnsbl.inps.de',
            '28' =>'dnsbl.sorbs.net',
            '29' =>'drone.abuse.ch',
            '30' =>'duinv.aupads.org',
            '31' =>'dul.dnsbl.sorbs.net',
            '32' =>'dyna.spamrats.com',
            '33' =>'dynip.rothen.com',
            '34' =>'http.dnsbl.sorbs.net',
            '35' =>'ips.backscatterer.org',
            '36' =>'ix.dnsbl.manitu.net',
            '37' =>'korea.services.net',
            '38' =>'misc.dnsbl.sorbs.net',
            '39' =>'noptr.spamrats.com',
            '40' =>'orvedb.aupads.org',
            '41' =>'pbl.spamhaus.org',
            '42' =>'proxy.bl.gweep.ca',
            '43' =>'psbl.surriel.com',
            '44' =>'relays.bl.gweep.ca',
            '45' =>'relays.nether.net',
            '46' =>'sbl.spamhaus.org',
            '47' =>'short.rbl.jp',
            '48' =>'singular.ttk.pte.hu',
            '49' =>'smtp.dnsbl.sorbs.net',
            '50' =>'socks.dnsbl.sorbs.net',
            '51' =>'spam.abuse.ch',
            '52' =>'spam.dnsbl.anonmails.de',
            '53' =>'spam.dnsbl.sorbs.net',
            '54' =>'spam.spamrats.com',
            '55' =>'spambot.bls.digibase.ca',
            '56' =>'spamrbl.imp.ch',
            '57' =>'spamsources.fabel.dk',
            '58' =>'ubl.lashback.com',
            '59' =>'ubl.unsubscore.com',
            '60' =>'virus.rbl.jp',
            '61' =>'web.dnsbl.sorbs.net',
            '62' =>'wormrbl.imp.ch',
            '63' =>'xbl.spamhaus.org',
            '64' =>'z.mailspike.net',
            '65' =>'zen.spamhaus.org',
            '66' =>'zombie.dnsbl.sorbs.net',
            '67' =>'PclZip',
            '68' =>'Akismet',
            '69' =>'postmarkapp',
            '70' =>'base64_decode',
            '71' =>'base32_decode',
            '72' =>'php.ini',
        );

        public $safeExtensionArr = array([
            'png',
            'jpg',
            'jpge',
            'gif',
            'css',
        ]);


        public function getDirPathArr(string $dir=__DIR__, &$returnPathArr = array()) {
            $files = scandir($dir);
        
            foreach ($files as $value) {
                $path = realpath($dir.'/'.$value);
                if (!is_dir($path)){
                    $returnPathArr[] = $path;
                }else if($value != ".." && $value != "."){
                    $this->getDirPathArr($path, $returnPathArr);
                    $returnPathArr[] = $path;
                }
            }
            return $returnPathArr;
        }




        public function dirCheck(array $filePathArr){
            
            foreach($filePathArr as $value){

                if(!$this->doesPathExistDBH($value)){

                    $this->warningMessage .= $this->insertFilesDBH($value);
                
                }
            }
                       
        }

        public function insertFilesDBH(string $filePath){
            
            $now = $this->getDate();
            
            $dangerWordsJSON = $this->getDangerWordsJSON($filePath);
            $isSafe = $this->isSafe($dangerWordsJSON);
            
            try{
                $query = "INSERT INTO $this->dbhTable SET 
                    filePath=?,
                    dangerWordsJSON=?,
                    date=?,
                    isSafe=?
                ";
                $stmt = $this->connect()->prepare($query);
                $stmt->execute([$filePath, $dangerWordsJSON, $now, $isSafe]);
                
                
            }catch(PDOException $e){
                //return $query.'Error'.$e->getMessage();
            } 

            if(!$isSafe){
                return '<p style="background:red; color:white; padding: 5px 5px;"> New File -> ('.$filePath.')  Maybe Not safe : '.$dangerWordsJSON.'</p>';
            }else{
                return '<p style="background:green; color:white; padding: 5px 5px;"> New File -> ('.$filePath.')  No Danger words found </p>';
            }
        }


        public function isSafe(string $dangerWordsJSON){
            $isSafe = true;
            $dangerArr = json_decode($dangerWordsJSON,TRUE);
            if(sizeof($dangerArr)>0){
                $isSafe = false;
            }
            return $isSafe;
        }
        

        public function isExtensionSafe(string $filePath){

            $isExtensionSafe = false;
            $extension = strtolower(end(explode('.',$filePath)));
            
            if(in_array($extension,$this->safeExtensionArr)){
                $isExtensionSafe = true;
            }
            return $isExtensionSafe;
        }



        public function getDangerWordsJSON(string $filePath){

            $dangerArr = array();


            if(!is_dir($filePath)){
                
                $fileContent = file_get_contents($filePath);

                foreach($this->dangerWordsArr as $value){
                    if(strpos($fileContent,$value) !==false){
                        $dangerArr[] = $value;
                    }
                }
            }
            
            $json = json_encode($dangerArr);
            return $json;
        }


        public function doesPathExistDBH(string $filePath){
            try{
                $query = "SELECT * FROM $this->dbhTable WHERE filePath=?";
                $stmt = $this->connect()->prepare($query);
                $stmt->execute([$filePath]);

                $doesPathExist = false;
                while($row = $stmt->fetch()){
                    $doesPathExist = true;
                    break;
                }
            }catch(PDOException $e){
                //echo $query.'Error'.$e->getMessage();
            }
            return $doesPathExist;
        }

        public function getRows(){
            try{
                $query = "SELECT * FROM $this->dbhTable";
                $stmt = $this->connect()->prepare($query);
                $stmt->execute();
                $rowArr = array();
                
                while($row = $stmt->fetch()){
                    $rowArr[] = $row;
                }
            }catch(PDOException $e){
                //echo $query.'Error'.$e->getMessage();
            }
            return $rowArr;
        }

        public function updateSafeDangerDBH(int $id, bool $isSafe){
            try{
                $query = "UPDATE ".$this->dbhTable." SET isSafe=? WHERE id=?";
                
                $stmt = $this->connect()->prepare($query);
                $stmt->execute([$isSafe, $id]);
            }catch(PDOException $e){
                //echo $query.'Error '.$e->getMessage();
            }
        }


        private function getDate(){
            date_default_timezone_set("Asia/Tokyo");
            $now = date("Y-m-d H:i:s");
            return $now;
        }

        public function getWarningMessage(){
            return $this->warningMessage;
        }
    }

?>