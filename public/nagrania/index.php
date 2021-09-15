<?php

require_once '../../page.php';

$page = new page();
$page->setLayout(SUBPAGELAYOUT);
$pageInfo = $page->getPageInfo();

$str = '';

$records = $pageInfo['RECORDS'];
$recordsSetTemplate = $page->getTemplate('recordsSet');
$recordsSetItemTemplate = $page->getTemplate('recordsSetItem');
$recordsSetStripeTemplate = $page->getTemplate('recordsSetStripe');

foreach ($records as $recordsSet) {
    if ('Koncerty' == $recordsSet['NAME']) {
        $recordsSetStr = $recordsSetStripeTemplate;
    } else {
        $recordsSetStr = $recordsSetTemplate;
    }
    $recordsStr = '';
    foreach ($recordsSet['LINKS'] as $link) {
        $recordsSetItemStr = $recordsSetItemTemplate;
        $page->fillWithData($recordsSetItemStr, ['LINK' => $link]);
        $recordsStr .= $recordsSetItemStr;
    }

    strlen($recordsSet['COVER']) > 1 ? $coverPath = '<img src="'.$recordsSet['COVER'].'">' : $coverPath = '';

    $page->fillWithData(
        $recordsSetStr,
        ['NAME' => $recordsSet['NAME'],
            'YEAR' => $recordsSet['YEAR'],
            'COVER' => $coverPath,
            'RECORDS' => $recordsStr, ]
    );
    $str .= $recordsSetStr;
}

$page->show($str);