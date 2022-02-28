<?php

namespace App\Http\Controllers;

use App\Models\Song;
use App\Services\DocumentGenerator;
use DateTime;
use App\Http\Requests\GeneratorRequest;



class GeneratorController extends Controller
{


    public function zaiks(GeneratorRequest $request)
    {
        $OUTPUTLOCATION = 'generators/zaiks/';
        $INPUTTEMPLATE = 'generators/templates/ZAiKS-GZJ.docx';


        $name = $request->validated()['eventName'];
        $songs = Song::whereIn('id', $request->validated()['songs']);

        $fileName = 'GZJ-ZAiKS-';

        //Set file name
        if (strlen($name) > 1)
        {
            $fileName .= $name;
        } else {
        //If name was not given by user use date as file name
            $actualTimestamp = new DateTime();
            $actualTime = strftime('%e-%m-%y-%H-%M', $actualTimestamp->getTimestamp());
            $fileName .= $actualTime;
        }

        if (!empty($songs)) {

            try {
                $documentPath = DocumentGenerator::generateZaiks($songs,$fileName,$OUTPUTLOCATION,$INPUTTEMPLATE);
                return response()->download(public_path($documentPath));
            } catch (\Throwable $e) {
                return back()->withErrors(['generatorError'=> __('generator.default')]);
            }
        }

    }


}
