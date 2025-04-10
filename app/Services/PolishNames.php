<?php

namespace App\Services;


/**
 * This class is responsible for declining Polish names.
 */
class PolishNames
{
    /** Makes a request to the website and returns the declined form of the given town name. If the town name is not found, it returns the town name itself.
     * @param $name The town name to decline
     * @return string The declined form of the town name if found, the town name itself otherwise
     */
    public static function GetTownNameDeclined($name)
    {
        return PolishNames::getFromWebsite($name,"http://nlp.actaforte.pl:8080/Nomina/Miejscowosci?nazwa=",'/Miejscownik.*?<b>(.*?)<\/b>/m');

    }

    /**
     * @deprecated This method is deprecated and may be removed in future versions. Use getFullNameDeclined() instead. 
     * Makes a request to the website and returns the declined form of the given surname. If the surname is not found, it returns the surname itself.
     * @param $surname The surname to decline
     * @param $gender Determines which gender skould be used for the declined form. Allowed values are 'm' and 'f'
     * @return string The declined form of the surname if found, the surname itself otherwise
     */
    public static function getSurnameDeclined($surname,$gender) {
        return PolishNames::getFromWebsite($surname,'http://nlp.actaforte.pl:8080/Nomina/Nazwiska?nazwisko='.$surname.'&typ='.$gender,'/Miejscownik.*?<b>(.*?)<\/b>/m');
    }

    /**
     * @deprecated This method is deprecated and may be removed in future versions. Use getFullNameDeclined() instead.
     * Makes a request to the website and returns the declined form of the given name. If the name is not found, it returns the name itself.
     * @param $name The name to decline
     * @return string The declined form of the name if found, the name itself otherwise
     */
    public static function getNameDeclined($name) {

        return PolishNames::getFromWebsite($name,'http://nlp.actaforte.pl:8080/Nomina/ImionaNazwiska','/<form .*<table.*<table.*<p>(.*)?<\/p>/m',true);

    }

    public static function getFullNameDeclined($name) {
        $cURLConnection = curl_init();


        curl_setopt($cURLConnection, CURLOPT_URL,'http://nlp.actaforte.pl:8080/Nomina/ImionaNazwiska');
        curl_setopt($cURLConnection, CURLOPT_POST, true);
        //x-www-form-urlencoded: ncase=loc&ndata=$name
        curl_setopt($cURLConnection, CURLOPT_POSTFIELDS,'ncase=inst&ndata='.urlencode($name) ) ;
        curl_setopt($cURLConnection, CURLOPT_RETURNTRANSFER, true);

        $server_output = curl_exec($cURLConnection);

        curl_close ($cURLConnection);

        if (!empty($server_output)) {
            preg_match('/<p>(.*?)<\/p>/m', $server_output, $matches);
        }
    
        return empty($matches) ? $name : $matches[1];
    }

    /**
     * Function to convert polish PESEL number (personal ID number) to date of birth in single string format 
     */
    //generate function to convert PESEL to date of birth
    public static function peselToDate($pesel) {
        $dateOfBirth = substr($pesel, 4, 2);
        $dateOfBirth .= '.';
        $month = (int) substr($pesel, 2, 2);

        if ($month > 20) {
            $month = $month - 20;
            $year = '.20';
            $year .= substr($pesel, 0, 2);
        } else {
            $year = '.19';
            $year .= substr($pesel, 0, 2);
        }
        if ($month < 10) {
            $dateOfBirth .= '0';
        }
        $dateOfBirth .= $month;
        $dateOfBirth .= $year;
        
        return $dateOfBirth;
    }

    /** Makes a request to the website and returns the declined form of the given word. If the word is not found, it returns the word itself.
     * @param $word The word to decline
     * @param $url The url of the website to make the request
     * @param $re The regular expression to match the declined form
     * @return string The declined form of the word if found, the word itself otherwise
     */
    private static function getFromWebsite($word, $url, $re,$post = false) {
        $cURLConnection = curl_init();


        if($post) {
            curl_setopt($cURLConnection, CURLOPT_URL,'http://nlp.actaforte.pl:8080/Nomina/ImionaNazwiska');
            curl_setopt($cURLConnection, CURLOPT_POST, true);
            curl_setopt($cURLConnection, CURLOPT_POSTFIELDS,'ncase=inst&ndata='.urlencode($word) ) ;
            curl_setopt($cURLConnection, CURLOPT_RETURNTRANSFER, true);
        } else {
            curl_setopt($cURLConnection, CURLOPT_URL,$url.urlencode($word));
            curl_setopt($cURLConnection, CURLOPT_RETURNTRANSFER, true);

        }

        $server_output = curl_exec($cURLConnection);

        curl_close ($cURLConnection);

        if (!empty($server_output)) {

            preg_match($re, $server_output,$matches);

        }

        return empty($matches)?$word:$matches[1];
    }


}
