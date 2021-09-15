<?php

function resetAllErrorFlags()
{
    if (isset($_SESSION['errors'])) {
        foreach ($_SESSION['errors'] as $key => $error) {
            $_SESSION['errors'][$key] = false;
        }
    }
}

//Function to convert polish PESEL number (personal ID number) to date of birth in single string format
//Attention: works for dates between years 1900 and 2099
function PESELtoDate($pesel)
{
    $dateOfBirth = substr($pesel, 4, 2);
    $dateOfBirth .= '.';
    $month = (int) substr($pesel, 2, 2);

    if ($month > 20) {
        $month = $month - 20;

        $dateOfBirth .= $month;
        $dateOfBirth .= '.20';
        $dateOfBirth .= substr($pesel, 0, 2);
    } else {
        $dateOfBirth .= $month;
        $dateOfBirth .= '.19';
        $dateOfBirth .= substr($pesel, 0, 2);
    }
}

function generateDocumentFromTemplate($template, $parametersToReplace, $outputLocation, $outputFilename)
{
    if (!empty($parametersToReplace)) {
        //Make new MS Word file
        $zip = new ZipArchive();

        //Make a copy of template file
        if (!copy($template, $outputLocation.$outputFilename)) {
            return 6;
        }

        // Open copied file
        if (true !== $zip->open($outputLocation.$outputFilename, ZipArchive::CREATE)) {
            return 6;
        }

        // Open XML document file
        $xml = $zip->getFromName('word/document.xml');

        //Replace keywords in document with prepared values from array
        foreach ($parametersToReplace as $key => $value) {
            $xml = str_replace($key, $value, $xml);
        }

        // Save and close file
        if ($zip->addFromString('word/document.xml', $xml)) {
            $zip->close();

            return 0;
        }

        return 7;
    }
}