<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SongSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $songs = array(
            array('id' => '1','title' => 'Granda','performer' => 'Monika Brodka','composer' => 'Monika Maria Brodka, Bartosz Piotr Dziedzic','text_author' => 'Antoni Radosław Łukasiewicz','created_at' => NULL,'updated_at' => NULL),
            array('id' => '2','title' => 'W dzień gorącego lata','performer' => 'Big-Day','composer' => 'Marcin Rafał Ciurapiński','text_author' => 'Marcin Rafał Ciurapiński','created_at' => NULL,'updated_at' => NULL),
            array('id' => '3','title' => 'Dla po nas pokoleń (Jaśniej)','performer' => 'Krzysztof Zalewski','composer' => 'Marcin Borys Bors,Andrzej Gienia Markowski, Krzysztof Zalewski-Brejdygant','text_author' => 'Krzysztof Zalewski-Brejdygant','created_at' => NULL,'updated_at' => NULL),
            array('id' => '4','title' => '[sic!]','performer' => 'Katarzyna Nosowska (Hey)','composer' => 'Jacek Chrzanowski','text_author' => 'Katarzyna Nosowska','created_at' => NULL,'updated_at' => NULL),
            array('id' => '5','title' => 'Początek','performer' => 'Męskie Granie Orkiestra 2018','composer' => 'Marcin Bartosz Macuk, Dawid Henryk Podsiadło, Aleksander Tomasz Świerkot, Krzysztof Zalewski-Brejdygant','text_author' => 'Dawid Henryk Podsiadło, Krzysztof Zalewski-Brejdygant','created_at' => NULL,'updated_at' => NULL),
            array('id' => '6','title' => 'Kiedyś cię znajdę','performer' => 'Reni Jusis','composer' => 'Michał Jerzy Przytuła, Reni Jusis','text_author' => 'Reni Jusis','created_at' => NULL,'updated_at' => NULL),
            array('id' => '7','title' => 'Takiego chłopaka','performer' => 'Micromusic','composer' => 'Natalia Danuta Grosiak, Dawid Konrad Korbaczyński','text_author' => 'Natalia Danuta Grosiak','created_at' => NULL,'updated_at' => NULL),
            array('id' => '8','title' => 'Bądź duży','performer' => 'Natalia Nykiel','composer' => 'Michał Stanisław Król, Natalia Nykiel','text_author' => 'Jacek Szymkiewicz','created_at' => NULL,'updated_at' => NULL),
            array('id' => '9','title' => 'Cool me down','performer' => 'Margaret','composer' => 'Linnea Marry Hansdotter Deb, Alex Arash Labaf, Alexander Dimitros Papaconstantinou, Viktor Arne Emanuel Svensson, Robert Erik Uhlmann, Anderz Wrethov','text_author' => 'Linnea Marry Hansdotter Deb, Alex Arash Labaf, Alexander Dimitros Papaconstantinou, Viktor Arne Emanuel Svensson, Robert Erik Uhlmann, Anderz Wrethov','created_at' => NULL,'updated_at' => NULL),
            array('id' => '10','title' => 'Komu auto, komu chatę (Ale wkoło jest wesoło)','performer' => 'Perfekt','composer' => 'Zbigniew Hołdys','text_author' => 'Zbigniew Hołdys','created_at' => NULL,'updated_at' => NULL),
            array('id' => '11','title' => 'Jadę na rowerze','performer' => 'Lech Janerka','composer' => 'Lech Andrzej Janerka ','text_author' => 'Lech Andrzej Janerka ','created_at' => NULL,'updated_at' => NULL),
            array('id' => '12','title' => 'Spadam, powoli spadam','performer' => 'COMA','composer' => 'Marcin Jerzy Kobza, Rafał Wojciech Matuszak,  Tomasz Andrzej Stasiak, Dominik Paweł Witczak, Piotr Rogucki','text_author' => 'Piotr Rogucki','created_at' => NULL,'updated_at' => NULL),
            array('id' => '13','title' => 'Na głowie mam kaktusa','performer' => 'Bovska','composer' => 'Magda Maria Grabowska-Wacławek, Jan Szymon Smoczyński','text_author' => 'Magda Maria Grabowska-Wacławek, Jan Szymon Smoczyński','created_at' => NULL,'updated_at' => NULL),
            array('id' => '14','title' => 'Hej, dziewczyno!','performer' => 'Ciabatta','composer' => 'Anna Wiśniewska Maciej Godek Bartosz Czapliński Michał Dębski Mateusz Wiśniewski','text_author' => 'Anna Wiśniewska Maciej Godek Bartosz Czapliński Michał Dębski Mateusz Wiśniewski','created_at' => NULL,'updated_at' => NULL),
            array('id' => '15','title' => 'Błąd','performer' => 'Łona & Webber','composer' => 'Andrzej Marek Mikosz','text_author' => 'Adam Bogumił Zieliński','created_at' => NULL,'updated_at' => NULL),
            array('id' => '16','title' => 'A wszystko to... bo ciebie kocham','performer' => 'Ich Troje/Die Toten Hosen','composer' => 'Andreas Frege','text_author' => 'Andreas Frege / Michał Wiśniewski','created_at' => NULL,'updated_at' => NULL),
            array('id' => '17','title' => 'Ezoteryczny Poznań','performer' => 'Pidżama Porno','composer' => 'Krzysztof Grabowski','text_author' => 'Krzysztof Grabowski','created_at' => NULL,'updated_at' => NULL),
            array('id' => '18','title' => '1996','performer' => 'T.Love','composer' => 'Maciej Tomasz Majchrzak','text_author' => 'Zygmunt Marek Staszczyk','created_at' => NULL,'updated_at' => NULL),
            array('id' => '19','title' => 'Znów jesteś sobą','performer' => 'Snowman','composer' => 'Kowalonek Michał Bernard','text_author' => 'Kowalonek Michał Bernard','created_at' => NULL,'updated_at' => NULL),
            array('id' => '20','title' => 'Miłość w Zakopanem','performer' => 'Sławomir','composer' => 'Zapała Sławomir Paweł','text_author' => 'Zapała Sławomir Paweł','created_at' => NULL,'updated_at' => NULL),
            array('id' => '21','title' => 'Weź nie pytaj','performer' => 'Paweł Domagała','composer' => 'Borowiecki Łukasz Piotr, Domagała Paweł Mariusz','text_author' => 'Domagała Paweł Mariusz','created_at' => NULL,'updated_at' => NULL),
            array('id' => '22','title' => 'Piątunio','performer' => 'Nocny Kochanek','composer' => 'Kazanowski Robert, Majstrak Arkadiusz Kamil Sokołowski Krzysztof','text_author' => 'Majstrak Arkadiusz Kamil, Sokołowski Krzysztof','created_at' => NULL,'updated_at' => NULL),
            array('id' => '23','title' => 'Nie ma fal','performer' => 'Dawid Podsiadło','composer' => 'Dziedzic Bartosz Piotr','text_author' => 'Podsiadło Dawid','created_at' => NULL,'updated_at' => NULL),
            array('id' => '24','title' => 'King Bruce -  Lee Karate Mistrz','performer' => 'Franek Kimono','composer' => 'Spol Andrzej','text_author' => 'Spol Andrzej','created_at' => NULL,'updated_at' => NULL),
            array('id' => '25','title' => 'Safari','performer' => 'Piotr Zioła','composer' => 'Juszczyszyn Marcin Grzegorz, Zioła Piotr Karol','text_author' => 'Durski Kamil Michał','created_at' => NULL,'updated_at' => NULL),
            array('id' => '26','title' => 'Na ulicach cichosza','performer' => 'Grzegorz Turnau','composer' => 'Turnau Grzegorz Jerzy','text_author' => 'Zabłocki Michał Wojciech','created_at' => NULL,'updated_at' => NULL),
            array('id' => '27','title' => 'Scenariusz dla moich sąsiadów','performer' => 'Myslovitz','composer' => 'Kuderski Jacek Tomasz, Kuderski Wojciech Arkadiusz, Myszor Przemysław Paweł, Powaga Wojciech Tomasz, Rojek Artur Marcin','text_author' => 'Kuderski Jacek Tomasz, Kuderski Wojciech Arkadiusz, Myszor Przemysław Paweł, Powaga Wojciech Tomasz, Rojek Artur Marcin','created_at' => NULL,'updated_at' => NULL),
            array('id' => '28','title' => 'Pola Ar','performer' => 'Łąki Łan','composer' => 'Chęć Michał Władysław, Jóźwik Jarosław, Koźbielski Piotr Antoni, Królik Bartosz Piotr,	Piotrowski Marek','text_author' => 'Dembowski Włodzimierz Michał','created_at' => NULL,'updated_at' => NULL),
            array('id' => '29','title' => 'Kocham cię, kochanie moje','performer' => 'Maanam','composer' => 'Jackowski Marek Norbert','text_author' => 'Jackowska Olga','created_at' => NULL,'updated_at' => NULL),
            array('id' => '30','title' => 'Supermenka','performer' => 'Kayah','composer' => 'Kayah','text_author' => 'Kayah','created_at' => NULL,'updated_at' => NULL),
            array('id' => '31','title' => 'Kiedy powiem sobie dość','performer' => 'O.N.A.','composer' => 'Grzegorz Bogdan Skawiński','text_author' => 'Agnieszka Barbara Chylińska','created_at' => NULL,'updated_at' => NULL),
            array('id' => '32','title' => 'My słowianie','performer' => 'Cleo & Donatan','composer' => 'Marek Witold Czamara','text_author' => 'Marek Witold Czamara, Joanna Klepko','created_at' => NULL,'updated_at' => NULL),
            array('id' => '33','title' => 'Mamona','performer' => 'Republika','composer' => 'Zbigniew Artur Krzywański, Grzegorz Ciechowski','text_author' => 'Grzegorz Ciechowski','created_at' => NULL,'updated_at' => NULL),
            array('id' => '34','title' => 'Mniej niż zero','performer' => 'Lady Pank','composer' => 'Borysewicz Jan Józef','text_author' => 'Mogielnicki Andrzej','created_at' => NULL,'updated_at' => NULL),
            array('id' => '35','title' => 'Gdy nie ma dzieci','performer' => 'Kult','composer' => 'Grudziński Janusz Bronisław','text_author' => 'Staszewski Kazimierz Piotr','created_at' => NULL,'updated_at' => NULL),
            array('id' => '36','title' => 'Wiosna','performer' => 'Organek','composer' => 'Staszewski Adam, Organek Tomasz, Markiewicz Robert Tomasz, Lewandowski Tomasz
          ','text_author' => 'Organek Tomasz','created_at' => NULL,'updated_at' => NULL)
          );

        DB::table('songs')->delete();
        foreach ($songs as $song) {
            DB::table('songs')->insert([
                'title' => $song['title'],
                'performer' => $song['performer'],
                'composer' => $song['composer'],
                'text_author' => $song['text_author']

            ]);
        }

    }
}
