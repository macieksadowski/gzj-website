<?php

namespace App\Http\Controllers;

use App\Models\Song;
use App\Models\Member;
use App\Services\DocumentGenerator;
use DateTime;
use App\Http\Requests\ZaiksGeneratorRequest;
use App\Http\Requests\ContractGeneratorRequest;



class GeneratorController extends Controller
{

    public static $ZAIKS_TEMPLATE = 'generators/templates/ZAiKS-GZJ.docx';
    public static $ZAIKS_OUTPUT_LOCATION = 'generators/zaiks/';

    public static $CONTRACTS_TEMPLATE = 'generators/templates/contracts/';
    public static $CONTRACTS_TYPE_DZIELO = 'dzielo';
    public static $CONTRACTS_TYPE_ZLECENIE = 'zlecenie';
    public static $CONTRACTS_OUTPUT_LOCATION = 'generators/contracts/';

    public function zaiks(ZaiksGeneratorRequest $request)
    {

        $name = $request->validated()['eventName'];
        $songs = Song::whereIn('id', $request->validated()['songs'])->get();

        $fileName = self::setFileName('ZAiKS',$name);

        if (!empty($songs)) {

            try {
                $documentPath = self::$ZAIKS_TEMPLATE;
                $documentPath = DocumentGenerator::generateZaiks($songs,$fileName,self::$ZAIKS_OUTPUT_LOCATION,self::$ZAIKS_TEMPLATE);
                return response()->download(($documentPath));
            } catch (\Throwable $e) {
                return back()->withErrors(['generatorError'=> __('generator.default') . ' : '. $documentPath]);
            }
        }

    }

    public function contract(ContractGeneratorRequest $request)
    {

        $name = $request->validated()['fileName'];
        $contractType = $request->validated()['contractType'];

        $inputTemplate = self::$CONTRACTS_TEMPLATE.$contractType.'-GZJ.docx';

        $fileName = self::setFileName($contractType,$name);

        try {
            $member = Member::where('id', $request->validated()['member_id'])->first();
            $documentPath = self::$CONTRACTS_TEMPLATE;
            $documentPath = DocumentGenerator::generateContract($member,$fileName,self::$CONTRACTS_OUTPUT_LOCATION,$inputTemplate);
            return response()->download(($documentPath));
        } catch (\Throwable $e) {
            return back()->withErrors(['generatorError'=> __('generator.default') . ' : '. $documentPath]);
        }



    }

    public static function setFileName($prefix,$name)
    {
        $fileName = 'GZJ-'.$prefix.'-';

        //Set file name
        if (strlen($name) > 1)
        {
            $fileName .= $name;
        }
        else
        {
        //If name was not given by user use date as file name
            $actualTimestamp = new DateTime();
            $actualTime = strftime('%e-%m-%y-%H-%M', $actualTimestamp->getTimestamp());
            $fileName .= $actualTime;
        }

        $fileName .= '.docx';
        return $fileName;
    }


}
