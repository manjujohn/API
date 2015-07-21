<?php

require 'Slim/Slim.php';

\Slim\Slim::registerAutoloader();


$app = new \Slim\Slim(array(
    'debug' => true
));

$app->add(new \Slim\Middleware\HttpBasicAuthentication([
    "users" => [
        "httpwatch" => "foo",
        "user" => "password"
    ]
]));


$app->get('/', function() use($app) {
    $app->response->setStatus(200);
    echo "---Welcome---";

}); 


$app->post(
    '/post',
    function () {
      global $app;   
    
      $req = $app->request();
      $body = json_decode($req->getBody());
      
      if($body)
      {

        if (isset($body->token)) {
            foreach ($body as $column => $value) {
              $insert[] = "`{$column}` = :{$column}";
            }
            $setQuery = implode(', ', $insert);
  
            $sql=sprintf("UPDATE WebOrders SET %s WHERE token = :token",$setQuery);
              
            $stmt = getQuery($body,$sql);
            $stmt->bindParam("token", $body->token);
            try{
              $stmt->execute();
              $db = null;
              $result=array("status"=>"success" ,"token"=>$body->token);
              echo json_encode($result);
              $app->response->setStatus(200);
            } 
            catch(PDOException $e) {
              $app->response->setStatus(400);
              $error=array("error"=>"Invalid Request");
              echo json_encode($error);
            }
          
        }
        else
        {
            $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $charactersLength = strlen($characters);
            $token = '';
            for ($i = 0; $i < 10; $i++) {
                $token .= $characters[rand(0, $charactersLength - 1)];
            }
          
            foreach ($body as $column => $value) {
              $field[] = "`$column`";
              $values[] = ":$column";
           
            }
            $setField = implode(', ', $field);
            $setValue = implode(', ', $values);
            $sql = sprintf("INSERT INTO WebOrders (%s,token) VALUES ( %s ,'".$token."')",$setField,$setValue);
          
            $stmt=getQuery($body,$sql);
            try {
            $stmt->execute();
            $db = null;
            $result=array("status"=>"success" ,"token"=>$token);
            echo json_encode($result);
            $app->response->setStatus(200);
        
            } 
            catch(PDOException $e) {
              $app->response->setStatus(400);
              $error=array("error"=>"Invalid Request");
              echo json_encode($error);
            }
        
        }
     
      }
      else{
        $app->response->setStatus(400);
        $error=array("error"=>"No data or Invalid Request");
        echo json_encode($error);

      }

   
});

$app->run();
function getConnection() {

          $dbhost="localhost";
          $dbuser="root";
          $dbpass="password";
          $dbname="web";
          $dbh = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpass);
          $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
          return $dbh;
}

function getQuery($body,$sql){
   
            $db = getConnection();
            
            $stmt = $db->prepare($sql);

            if(isset($body->d__Address_From_Lift)){
              $stmt->bindParam("d__Address_From_Lift", $body->d__Address_From_Lift);
            }
            if(isset($body->d__Address_From_Stairs)){
               $stmt->bindParam("d__Address_From_Stairs", $body->d__Address_From_Stairs);
            }
            if(isset($body->d__Address_FromStreet)){
              $stmt->bindParam("d__Address_FromStreet", $body->d__Address_FromStreet);
            }
             if(isset($body->d__Address_FromSuburb)){
              $stmt->bindParam("d__Address_FromSuburb", $body->d__Address_FromSuburb);
            }
            if(isset($body->d__Address_To_Lift)){
              $stmt->bindParam("d__Address_To_Lift", $body->d__Address_To_Lift);
            }
            if(isset($body->d__Address_To_Stairs)){
              $stmt->bindParam("d__Address_To_Stairs", $body->d__Address_To_Stairs);
            }
            if(isset($body->d__Address_ToStreet)){
               $stmt->bindParam("d__Address_ToStreet", $body->d__Address_ToStreet);
            }
            if(isset($body->d__Address_ToSuburb)){
               $stmt->bindParam("d__Address_ToSuburb", $body->d__Address_ToSuburb);
            }
            if(isset($body->d__CalledTime)){
               $stmt->bindParam("d__CalledTime", $body->d__CalledTime);
            }
            if(isset($body->d__Email)){
              $stmt->bindParam("d__Email", $body->d__Email);
            }
            if(isset($body->d__FirstName)){
               $stmt->bindParam("d__FirstName", $body->d__FirstName);
            }
            if(isset($body->d__HowHearAbout)){
                $stmt->bindParam("d__HowHearAbout", $body->d__HowHearAbout);
            }
            if(isset($body->d__Items)){
              $stmt->bindParam("d__Items", $body->d__Items);
            }
            if(isset($body->d__LastName)){
              $stmt->bindParam("d__LastName", $body->d__LastName);
            }
            if(isset($body->d__NoDrivers)){
             $stmt->bindParam("d__NoDrivers", $body->d__NoDrivers);
            }
            if(isset($body->d__OtherNotes)){
              $stmt->bindParam("d__OtherNotes", $body->d__OtherNotes);
            }
            if(isset($body->d__Phone)){
              $stmt->bindParam("d__Phone", $body->d__Phone);
            }
            if(isset($body->d__RequestedDate)){
              $stmt->bindParam("d__RequestedDate", $body->d__RequestedDate);
            }
            if(isset($body->d__RequestedTime)){
              $stmt->bindParam("d__RequestedTime", $body->d__RequestedTime);           
            }
            if(isset($body->d__Status)){
               $stmt->bindParam("d__Status", $body->d__Status);
            }
            if(isset($body->ID_Constant)){
              $stmt->bindParam("ID_Constant", $body->ID_Constant);
            }
             if(isset($body->s__ToProcess)){
              $stmt->bindParam("s__ToProcess", $body->s__ToProcess);
            }
             if(isset($body->s__CreationTimestamp)){
              $stmt->bindParam("s__CreationTimestamp", $body->s__CreationTimestamp);

            }
       
    return $stmt;       
}




