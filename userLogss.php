<?php
require "functions.php";

class ActivityLog{

    // DB Connection
    private $db;    
    // Log Table Name
    private $userlogs = "site_activity_log_automation_tbl";
    // DB Prefix
    private $db_prefix;

    function __construct($dbhost="", $dbuser = "", $dbpassword = "", $dbname = "", $db_prefix =""){
        // Check if DB credentials is empty
        if(empty($dbhost) || empty($dbuser) || empty($dbname)){
            throw new ErrorException("Database Credentials cannot be empty!");
            exit;
        }
        // SEt DB Prefix to log db table
        $this->db_prefix = $db_prefix;
        if(!empty($this->db_prefix)){
            $this->db_prefix .= "_";
        }
        $this->userlogs = $this->db_prefix . $this->userlogs;

        // Connect to the database
        try{
            $this->db = new MySQLi($dbhost, $dbuser, $dbpassword, $dbname);
        }catch(Exception $e){
            throw new ErrorException($e->getMessage());
            exit;
        }

        // Check if Log Table already exists, otherwise, create table
        try{
            $tbl_described =  $this->db->query("DESCRIBE `{$this->db_prefix}`");
        }catch(Exception $e){
            $this->create_tbl();
        }
        
    }

    /**
     * Log Table Creation
     */
    function create_tbl(){
        $sql = "CREATE TABLE IF NOT EXISTS `{$this->userlogs}`
            (
                `id` bigint(30) PRIMARY KEY AUTO_INCREMENT,
                `userID` bigint(30) NOT NULL,        
                `ip` varchar(25) NOT NULL,        
                `url` text NOT NULL,        
                `action` text NOT NULL,        
                `created_at` datetime NOT NULL DEFAULT current_timestamp()        
            )ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci
        ";
        try{
            $table_create = $this->db->query($sql);
        }catch(Exception $e){
            throw new ErrorException($e->getMessage());
            exit;
        }
    }

    /**
     * Sanitize values
     * @param mixed any
     */
    function sanitize_value($value){
        if(!is_numeric($value) && empty($value)){
            if( is_object($value) && is_array($value) ){
                $value = json_encode($value);
            }else{
                $value = addslashes(htmlspecialchars($value));
            }
        }
        return $value;
    }

    /**
     * Insert Activity Log
     * @param array [userID, ip, url, action] 
     *  
     */
    public function log( $data = [] ){
        if(empty($data)){
            throw new ErrorException("Log data are required!");
            exit;
        }

        
        $params_values = [];
        $params_format = [];
        $query_values  = [];

        foreach($data as $k => $v){
            $v = $this->sanitize_value($v);
            if(!empty($v)){
                if(is_numeric($v)){
                    $fmt = "d";
                }else{
                    $fmt = "s";
                }
                $query_values[] = "`{$k}`";
                $params_values[] = $v;
                $params_format[] = $fmt;
            }
        }

        if(empty($query_values)){
            throw new ErrorException("All Log data provided are empty or invalid!");
            exit;
        }

        $sql = "INSERT INTO `{$this->userlogs}` (".implode(",", $query_values).") VALUES (".( implode( ",", str_split( str_repeat( "?", count( $query_values ) ) ) ) ).")";

        $stmt = $this->db->prepare($sql);

        $fmts = implode("", $params_format);

        $stmt->bind_param($fmts, ...$params_values);

        $executed = $stmt->execute();

        if($executed){

           $resp = [
                "status" => "success"
           ];

        }else{

            $resp = [
                "status" => "error",
                "sql" => $sql,
                "queries" => $query_values,
                "formats" => $fmts,
                "values" => $params_values,
           ];

        }
        return $resp;
    }

    /**
     * Log Data 
     * @param $userID mixed|int User ID
     * @param $action  str       Action Data
     */
    public function setAction($userID= "", $action = ""){
        $data = [];

        extract($_SERVER);
        $data['ip'] = $REMOTE_ADDR;
        $data['url'] = (empty($HTTPS) ? 'http' : 'https') . "://{$HTTP_HOST}{$REQUEST_URI}";
        $data["userID"] = $userID;
        $data["action"] = addslashes(htmlspecialchars($action));

        return $this->log($data);
    }

    /**
     * Get All Logs
     */
    public function getLogs(){

        $query = $this->db->query("SELECT * FROM `{$this->userlogs}` order by `id` desc");
        $result = $query->fetch_all(MYSQLI_ASSOC);

        return $result;
    }

    function __destruct()
    {
        if($this->db){
            $this->db->close();
        }
    }
}
    
