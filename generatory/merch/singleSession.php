<?php

class singleSession
{
    private $id;

    function __construct($id)
    {
        $this->id = $id;
    }
    
    function getId()
    {
        return $this->id;
    }

    function delete()
    {
        $query = 'DELETE FROM sales_session WHERE id = '.$this->id;
        $success = 'Usunięto sesję sprzedażową (id:'.$this->id.') !';
        $DBconnection->sendToDBshowResult($query,$success,'index.php','index.php#error');
    }

    function activate()
    {

    }

    function print()
    {
        
    }
}

?>