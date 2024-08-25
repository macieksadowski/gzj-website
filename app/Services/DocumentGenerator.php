<?php

namespace App\Services;

use ZipArchive;

/**
 * This class is responsible for generating MS Word documents from templates.
 */
class DocumentGenerator
{

    /**
     * This function generates a MS Word document with a fullfilled contract with given member data from an empty template file.
     *
     * @param Object $member Object with member data
     * @param String $fileName Name of generated file (without extension)
     * @param String $outputLocation Path  where generated file should be saved
     * @param String $inputTemplate Filepath to a template document (it has to be a MS Word .docx document)
     * @return String Path to ouptut file when succesfully generated or empty String otherwise
     */
    public static function generateContract($member, $fileName, $outputLocation, $inputTemplate)
    {
        //Conversion of PESEL number to Date of Birth String
        $birthDate = PolishNames::peselToDate($member->pesel);

        //Generate strings for replacing in template
        $keywords = [];

        if ('a' == substr($member->first_name, -1)) {
            $keywords['GODNOSC'] = 'Panią';
            $keywords['SUFIX'] = 'ą';
            $keywords['NAZWISKO_ODMIENIONE'] = PolishNames::getSurnameDeclined($member->last_name,'f');
        } else {
            $keywords['GODNOSC'] = 'Panem';
            $keywords['SUFIX'] = 'ym';
            $keywords['NAZWISKO_ODMIENIONE'] = PolishNames::getSurnameDeclined($member->last_name,'m');
        }

        $keywords['IMIE_ODMIENIONE'] = PolishNames::getNameDeclined($member->first_name);
        $keywords['IMIE'] = $member->first_name;
        $keywords['NAZWISKO'] = $member->last_name;
        $keywords['MIASTO_ODMIENIONE'] = PolishNames::GetTownNameDeclined($member->town);
        $keywords['MIASTO'] = $member->town;
        $keywords['KOD_POCZTOWY'] = $member->postal_code;
        $keywords['ULICA'] = $member->street;
        $keywords['NR_DOMU'] = $member->house_no;
        $keywords['NR_PESEL'] = $member->pesel;
        $keywords['MIEJSCE_URODZENIA_ODMIENIONE'] = PolishNames::GetTownNameDeclined($member->birth_place);
        $keywords['MIEJSCE_URODZENIA'] = $member->birth_place;
        $keywords['NR_KONTA'] = $member->account_no;
        $keywords['URZAD_SKARBOWY'] = $member->tax_office;
        $keywords['DATA_URODZENIA'] = $birthDate;

        $errorCode = DocumentGenerator::generateDocumentFromTemplate($inputTemplate, $keywords, $outputLocation, $fileName);
        if (!$errorCode) {
            return $outputLocation . $fileName;
        } else {
            return '';
        }
    }

    /**
     * This function generates a MS Word document with a fullfilled ZAiKS Table with given entries from an empty template file.
     *
     * @param Array $songs Array with entries with songs and their credits (composer, text author)
     * @param String $fileName Name of generated file (without extension)
     * @param String $outputLocation Path  where generated file should be saved
     * @param String $inputTemplate Filepath to a template document (it has to be a MS Word .docx document)
     * @return String Path to ouptut file when succesfully generated or empty String otherwise
     */
    public static function generateZaiks($songs, $fileName, $outputLocation, $inputTemplate)
    {

        $to_replace = '';
        $rowTemp = file_get_contents('generators/templates/row-template.txt');

        foreach ($songs as $key => $song) {
            $row = $rowTemp;
            $row = str_replace('ID', ($key + 1), $row);
            $row = str_replace('TYTUL', $song->title_official, $row);
            $row = str_replace('KOMPOZYTOR', $song->composer, $row);
            $row = str_replace('AUTOR', $song->text_author, $row);
            $to_replace .= $row;
        }

        $keywords = ['TO_REPLACE' => $to_replace];
        $errorCode = DocumentGenerator::generateDocumentFromTemplate($inputTemplate, $keywords, $outputLocation, $fileName);
        if (!$errorCode) {
            return $outputLocation . $fileName;
        } else {
            return '';
        }
    }

    /**
     * This function generates a MS Word document from given template.
     * It looks for keywords in template and replaces it with given values.
     *
     * @param String $template Filepath to a template document (it has to be a MS Word .docx document)
     * @param Array $parametersToReplace PHP array with keywords to replace in the template and corresponding values
     * @param String $outputLocation Path  where generated file should be saved
     * @param String $outputFilename Name of generated file (without extension)
     * @return void
     */
    public static function generateDocumentFromTemplate($template, $parametersToReplace, $outputLocation, $outputFilename)
    {

        //Make new MS Word file
        $zip = new ZipArchive();
 
        //Make a copy of template file
        copy($template, $outputLocation . $outputFilename);


        // Open copied file
        $zip->open($outputLocation . $outputFilename, ZipArchive::CREATE);

        // Open XML document file
        $xml = $zip->getFromName('word/document.xml');

        //Replace keywords in document with prepared values from array
        foreach ($parametersToReplace as $key => $value) {
            $xml = str_replace($key, $value, $xml);
        }

        // Save and close file
        if ($zip->addFromString('word/document.xml', $xml)) {
            $zip->close();
        }
    }
}
