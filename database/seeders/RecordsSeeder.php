<?php

namespace Database\Seeders;

use App\Models\RecordSet;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RecordsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $records = [
            ['name'=> 'Koncerty',
            'year' => '',
            'cover'=> '',
            'links'=>[
                '6UEAk718Jj8',
                'mN0ysLCSsKo',
                'G3BdfPO5zOc',
                'So9S2GJ6Zzk',
                'B_kN_r4lcZQ'
                ]
            ],
            ['name'=> 'GOK Komorniki',
            'year' => '2020',
            'cover'=> '',
            'links'=>[
                "bAzYbewpu1g",
                "hq9EyVA_kqo",
                "NocJOb6GUCU",
                "_B23zY1FKp0"
                ]
            ],
            ['name'=> 'Jaśminowy',
            'year' => '2020',
            'cover'=> 'jasminowy.png',
            'links'=>[
                "1xWZxVqCRUk",
                "81IrrlGEHe0",
                "4gNheQl13ME"
                ]
            ],
            ['name'=> 'Orle',
            'year' => '2019',
            'cover'=> 'orle-gniazdo.jpg',
            'links'=>[
                "SIMkkIljSEU",
                "SoAPrzVIBRc",
                "EcYMdS350To"
                ]
            ],
            ['name'=> 'DASH',
            'year' => '2019',
            'cover'=> 'dash.png',
            'links'=>[
                "LcCcjjztNQQ",
                "Qr7p-mDae3A",
                "GTm3TjtgIQQ"
                ]
            ],
            ['name'=> 'Pretekst',
            'year' => '2018',
            'cover'=> 'pretekst.png',
            'links'=>[
                "AfejRjo1q-0",
                "3bSeKNfsCV0"
                ]
            ]
        ];

        foreach ($records as $record) {
            $id = DB::table('record_sets')->insertGetId([
                'name'=> $record['name'],
                'year' => $record['year'],
                'cover'=> $record['cover']
            ]);
            foreach ($record['links'] as $link) {
                DB::table('record_links')->insert([
                    'url' => $link,
                    'record_set_id' => $id
                ]);
            }
        }
    }
}
