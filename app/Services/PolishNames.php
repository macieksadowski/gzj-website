<?php

namespace App\Services;


class PolishNames
{
    public static function GetTownNameDeclined($name)
    {
        return PolishNames::getFromWebsite($name,"http://nlp.actaforte.pl:8080/Nomina/Miejscowosci?nazwa=",'/Miejscownik.*?<b>(.*?)<\/b>/m');

    }

    public static function getSurnameDeclined($surname,$gender) {
        return PolishNames::getFromWebsite($surname,'http://nlp.actaforte.pl:8080/Nomina/Nazwiska?nazwisko='.$surname.'&typ='.$gender,'/Miejscownik.*?<b>(.*?)<\/b>/m');
    }

    public static function getNameDeclined($name) {

        return PolishNames::getFromWebsite($name,'http://nlp.actaforte.pl:8080/Nomina/ImionaNazwiska','/<form .*<table.*<table.*<p>(.*)?<\/p>/m',true);

    }

    private static function getFromWebsite($name, $url, $re,$post = false) {
        $cURLConnection = curl_init();


        if($post) {
            curl_setopt($cURLConnection, CURLOPT_URL,'http://nlp.actaforte.pl:8080/Nomina/ImionaNazwiska');
            curl_setopt($cURLConnection, CURLOPT_POST, true);
            curl_setopt($cURLConnection, CURLOPT_POSTFIELDS,'ncase=inst&ndata='.urlencode($name) ) ;
            curl_setopt($cURLConnection, CURLOPT_RETURNTRANSFER, true);
        } else {
            curl_setopt($cURLConnection, CURLOPT_URL,$url.urlencode($name));
            curl_setopt($cURLConnection, CURLOPT_RETURNTRANSFER, true);

        }

        $server_output = curl_exec($cURLConnection);

        curl_close ($cURLConnection);

        if (!empty($server_output)) {

            preg_match($re, $server_output,$matches);

        }

        return empty($matches)?$name:$matches[1];
    }


}
