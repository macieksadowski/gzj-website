<?php

class Database
{
    private $host;
    private $user;
    private $password;
    private $name;

    function __construct($Host,$User,$Password,$Name)
    {
        $this->host = $Host;
        $this->user = $User;
        $this->password = $Password;
        $this->name = $Name;
    }
    function getDBName()
    {
        return $this->name;
    }
    function getDBHost()
    {
        return $this->host;
    }

    /**
     *  This function is used to get data from DB. It opens the connection,performs query and closes the connection. 
     * @param $query SQL query to send 
     * @return data array or error code
     */
    function getFromDB($query)
    {
        //Estabilish new connection with DB
        $DBconnection = @new mysqli($this->host,$this->user,$this->password,$this->name);
        if ($DBconnection->connect_errno!=0)
        {
            return 1;
        }
        else
        {
            //Get data from Database and save as array
            if($results = @$DBconnection->query($query))
            {
                $entriesNum = $results->num_rows;
                if($entriesNum > 0)
                {
                    
                    $entriesArray = $results->fetch_all(MYSQLI_ASSOC);
                    $results->free_result();
                }
                else
                {
                    return 2;
                }	
                
            }
            
            $DBconnection->close();
            if(isset($entriesArray)) return $entriesArray;
        }
    }

    /**
     *  This function is used to get data from the DB. Additionally, it sets error code variable in the session instead of returning it
     * @param $query SQL query to send 
     * @param $returnPath
     * @return data array
     */
    function getFromDBShowErrors($query,$returnPath = false)
    {
        $result = $this->getFromDB($query);
        if(!is_array($result))
        {
            $_SESSION['errors'][$result] = TRUE;
            if($returnPath != false)
            {
                header('location: '.$returnPath);
                exit();
            }

        }
        else return $result;
    }

    function sendToDB($query)
    {
        //Estabilish new connection with DB
        $DBconnection = @new mysqli($this->host,$this->user,$this->password,$this->name);
        if ($DBconnection->connect_errno!=0)
        {
            return 1;
        }
        else
        {
            //Send data from Database
            if($results = $DBconnection->query($query))
            {
                
            }
            else
            {
                return 2;
            }	
            $DBconnection->close();
            return 0;
        }
    }

    /**
     *  This function is used to send data to the DB. Additionally, it sets error code or  success  variable in the session instead of returning it  
     * @param $query SQL query to send 
     * @param $successCommunicate
     * @param $returnPathSuccess
     * @param $returnPathError
     */
    function sendToDBshowResult($queries,$successCommunicate,$returnPathSuccess = false,$returnPathError = false)
    {
        if(!is_array($queries))
        {
            $queries = [$queries];
        }
        foreach($queries as $query)
        {
            $result = $this->sendToDB($query);
            if($result != 0)
            {
                $_SESSION['errors'][$result] = TRUE;
                break;
            }
        }
		if($result == 0)
		{
			resetAllErrorFlags();
            $_SESSION['success'] = $successCommunicate;
            if($returnPathSuccess != false)
            {
                header('location: '.$returnPathSuccess);
                exit();
            }
		}
        if($returnPathError != false)
        {
            header('location: '.$returnPathError);
            exit();
        }
    }

    function performLogin($login,$password)
    {
        $DBconnection = @new mysqli($this->host,$this->user,$this->password,$this->name);
        //If connection fails show error communicate
        if ($DBconnection->connect_errno!=0)
        {
            return 1;
        }
        else
        {
            $login = htmlentities($login, ENT_QUOTES, "UTF-8");
            
            //Search if in DB exists user with given username
            if($results = @$DBconnection->query(sprintf("SELECT * FROM users WHERE user='%s'",mysqli_real_escape_string($DBconnection,$login))))
            {
                $usersFound = $results->num_rows;
                $DBconnection->close();
                //If username is found, check if password is correct
                if($usersFound > 0)
                {
                    $row = $results->fetch_assoc();
                    
                    //If password is correct redirect to client panel
                    if(password_verify($password,$row['pass']))
                    {
                        $results->free_result();
                        $id = $row['id'];
                        $username = $row['user'];
                        return array($id,$username);
                    }
                    //Else return to login page and show error message
                    else
                    {
                        return 3;
                    }	
                }
                //Else return to login page and show error message
                else
                {
                    return 4;
                }		
            }
            
        }
    }
	

}


?>