<?php
/**
 * Created by PhpStorm.
 * User: Kurt
 * Date: 14.03.2018
 * Time: 09:33
 */

class DBConnection{

    public $servername = "localhost";
    public $username = "root";
    public $password = "gibbiX12345";
    public $dbname = "video";

    private $SQLStatement = array();
    private $WhereList = array();
    private $JoinTabelleList = array();
    private $JoinTabelleTypeList = array();
    private $UseJoin = false;
    private $UseWhere = false;


    public function SelectItem($Item, $Attribut){
        $conn = new mysqli($this->servername, $this->username, $this->password, $this->dbname);
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        $sql = 'select '."$Attribut".' from items where name = "'.$Item.'";';
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                return $row[$Attribut];
            }
        } else {
            return "";
        }
        $conn->close();
        return "";
    }

    public function __construct()
    {
        $this->SQLStatement = array();
        $this->WhereList = array();
        $this->JoinTabelleList = array();
        $this->JoinTabelleTypeList = array();

    }

//-----------------------------------------------------------//

    public function Mode($Mode){
        if($Mode == "select"|$Mode == "Select"){
            $this->SQLStatement["Mode"] = "select ";
            return $this;
        }elseif ($Mode == "insert"|$Mode == "Insert"){
            $this->SQLStatement["Mode"] = "insert ";
            return $this;
        }elseif ($Mode == "delete"|$Mode == "Delete"){
            $this->SQLStatement["Mode"] = "delete ";
            return $this;
        }elseif ($Mode == "update"|$Mode == "Update"){
            $this->SQLStatement["Mode"] = "update ";
            return $this;
        }else{
            return null;
        }
    }

    public function Attribut($Attribut){
        $this->SQLStatement["Attribut"] = $Attribut;
        return $this;
    }

    public function fromTabelle($fromTabelle){
        $this->SQLStatement["fromTabelle"] = " from ".$fromTabelle;
        return $this;
    }

//-------------------------Join------------------------------//

    public function Join($Join){
        if ($this->UseJoin == false){
            $this->UseJoin = true;
        }
        if($Join == "inner"| $Join == "Inner"){
            $this->JoinTabelleTypeList["Join"] = "inner";
            return $this;
        }elseif ($Join == "left"| $Join == "Left"){
            $this->JoinTabelleTypeList["Join"] = "left";
            return $this;
        }elseif ($Join == "right"| $Join == "Right"){
            $this->JoinTabelleTypeList["Join"] = "right";
            return $this;
        }elseif ($Join == "full"| $Join == "Full"){
            $this->JoinTabelleTypeList["Join"] = "full";
            return $this;
        }else{
            return null;
        }
    }

    public function JoinTabelle($JoinTabelle){
        static $i;
        ++$i;
        $this->JoinTabelleList[$i] = $JoinTabelle;
        return $this;
    }

    public function JoinStatement(){
        if ($this->UseJoin == true){
            $Statement = " ". implode ( ", " , $this->JoinTabelleTypeList." ".$this->JoinTabelleList);
            return $Statement;
        }else{
            return " ";
        }

    }

//-------------------------Where-----------------------------//

    public function Where($Where){
        if ($this->UseWhere == false){
            $this->UseWhere = true;
        }
        static $i;
        ++$i;
        $this->WhereList[$i] = '"'.$Where.'"';
        return $this;
    }

    public function WhereCeck(){
        if ($this->UseWhere == true){
            return " where ";
        }else{
            return " ";
        }
    }

    public function WhereStatement(){
        if ($this->UseWhere == true) {
            $Statement = $this->WhereCeck() . " " . implode(" and ", $this->WhereList);
            return $Statement;
        }else{
            return " ";
        }
    }

//------------------------SQLExe-----------------------------//

    public function SQLExe(){
        $conn = new mysqli($this->servername, $this->username, $this->password, $this->dbname);
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        static $sql;
        if($this->SQLStatement["Mode"] == "select "){
            $sql = $this->SQLStatement["Mode"]
                .$this->SQLStatement["Attribut"]
                .$this->SQLStatement["fromTabelle"]
                .$this->JoinStatement()
                .$this->WhereStatement()
                .";";
        }

        echo $sql;
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo $row[$this->SQLStatement["Attribut"]];
                return $row[$this->SQLStatement["Attribut"]];
            }
        } else {
            return "";
        }
        $conn->close();
        return "";
    }

}