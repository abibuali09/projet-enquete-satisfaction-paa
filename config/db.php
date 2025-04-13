<?php


class DB {

    private string $dsn = "mysql:host=localhost;dbname=db_port";
    private string $username = "";
    private string $password = "";
    private static ?PDO $connexion = null;

    private function connection(){

        if(self::$connexion == null)
        {
            $this->connexion = new PDO($this->dsn,$this->username,$this->password);
            $this->connexion->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION) ;
            $this->connexion->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE,PDO::FETCH_OBJ) ;
        }

        return $this->connexion;

    }

    public static function query(string $query, mixed $params=null){
        try 
        {
            $query = self::connection()->prepare($query);
            $query->execute($params);
            return $query;
        } 
        catch (PDOException $e) 
        {
            die("Echec de la requête SQL :".$e->getMessage());
        }
    }
    
}