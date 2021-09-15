<?php

require_once '../../page.php';

require_once SITE_ROOT.'/fbProxy.php';

$page = new page();
$page->setLayout(SUBPAGELAYOUT);
$pageInfo = $page->getPageInfo();

$fbProxy = new fbProxy();
$actualEvents = $fbProxy->getActualEvents();
setlocale(LC_ALL, 'pl_PL.UTF-8');

$str = '<div class="event-grid">{{EVENTS}}</div>';

$eventItemTemplate = $page->getTemplate('eventItem');
$eventsStr = '';

if (empty($actualEvents)) {
    $eventsStr = '
		<div class="title">
			<h1>BRAK NADCHODZĄCYCH KONCERTÓW</h1>
		</div>
	';
} else {
    foreach ($actualEvents as $key => $event) {
        $eventStr = $eventItemTemplate;

        $date = strftime('%A %e.%m.%yr. godz.%H:%M', $event['start_time']->getTimestamp());

        $ticketInfo = '';
        $re = '/wstęp wolny/miu';
        preg_match_all($re, $event['description'], $matches, PREG_SET_ORDER, 0);
        if (true == $event['is_online']) {
            $ticketInfo = '<div class="ticketInfo">Wydarzenie online</div>';
        } elseif (isset($event['ticket_uri'])) {
            $ticketInfo = '<button><a href="'.$event['ticket_uri'].'" target="_blank">Kup Bilet</a></button>';
        } elseif (!empty($matches)) {
            $ticketInfo = '<div class="ticketInfo">Wstęp wolny</div>';
        } else {
            $ticketInfo = '<div class="ticketInfo">Bilety do nabycia u&nbsp;organizatora</div>';
        }

        $placeLocation = '';
        if (isset($event['place']['location'])) {
            $placeLocation = $event['place']['location']['city'].', '.$event['place']['location']['street'];
        }

        page::fillWithData($eventStr, [
            'COVER' => $event['cover']['source'],
            'NAME' => $event['name'],
            'DATE' => $date,
            'TICKET_INFO' => $ticketInfo,
            'PLACE_NAME' => $event['place']['name'],
            'PLACE_LOCATION' => $placeLocation,
        ]);

        $eventsStr .= $eventStr;
    }
}

page::fillWithData($str, ['EVENTS' => $eventsStr]);

$page->show($str);