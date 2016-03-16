 <?php
 class Mongo_Util{
 
    function getMongoDb($db_name,$collection){
        $url = "mongodb://".getenv("MONGODB_HOST").":".getenv("MONGODB_PORT");
        $this->mongo = new Mongo("$url");
        $db = $this->mongo->selectDB("{$db_name}");
        return $db->$collection;
    }

  }
 
 ?>