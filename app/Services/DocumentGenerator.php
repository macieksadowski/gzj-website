<?php

namespace App\Services;

use ZipArchive;

class DocumentGenerator
{

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
            $row = str_replace('TYTUL', $song->title, $row);
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
