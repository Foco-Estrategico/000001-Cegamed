<?php
    class FTP{
        public $Server;
        public $Username;
        public $Password;

        function __construct(){
            $Conf = parse_ini_file("conf.ini");
            $this->Server = $Conf["serverFTP"];
            $this->Username = $Conf["userFTP"];
            $this->Password = $Conf["passFTP"];
            /* $this->Server = "192.168.1.8";
            $this->Username = "foco";
            $this->Password = "F9o7c5O3.,2017"; */
        }
        function Connect(){
            $conn_id = ftp_connect($this->Server) or die("could not connect to $this->Server");
            ftp_pasv($conn_id, true);
            return $conn_id;
        }
        function Login($ConnectionID){
            $ToReturn = false;
            if(@ftp_login($ConnectionID, $this->Username, $this->Password)){
               $ToReturn = true;
            }
            return $ToReturn;
        }
        function listDirectoryFiles($ConnectionID,$Directory){
            $Files = ftp_nlist($ConnectionID,$Directory);
            return $Files;
        }
        function uploadFile($ConnectionID,$RemotePath,$File){
            $Ret = ftp_nb_put($ConnectionID, $RemotePath, $File, FTP_BINARY);
            while ($Ret == FTP_MOREDATA) {
                // Continuar la carga...
                $Ret = ftp_nb_continue($ConnectionID);
            }
        }
        function uploadDirectory($ConnectionID,$RemotePath,$Directory){
            foreach (glob($Directory."/*.*") as $localFilename){
                $ArraylocalFilename = explode("/",$localFilename);
                $fileName = end($ArraylocalFilename);
                @ftp_chdir($ConnectionID, $RemotePath);
                $this->uploadFile($ConnectionID,ftp_pwd($ConnectionID)."/".$fileName,$localFilename);
            }
        }
        function downloadFile($ConnectionID,$LocalFile,$ServerFile){
            $ToReturn = false;
            if(ftp_get($ConnectionID, $LocalFile, $ServerFile, FTP_BINARY)) {
                $ToReturn = true;
            }
        }
        function CloseConnection($ConnectionID){
            ftp_close($ConnectionID);
        }
        function createSubDirs($ConnectionID,$ftp,$Dirs){
            @ftp_chdir($ConnectionID, $ftp);
            $parts = explode('/',$Dirs);
            foreach($parts as $part){
                if(!@ftp_chdir($ConnectionID, $part)){
                    ftp_mkdir($ConnectionID, $part);
                    ftp_chdir($ConnectionID, $part);
                    //ftp_chmod($ConnectionID, 0777, $part);
                }
            }
        }
    }
?>