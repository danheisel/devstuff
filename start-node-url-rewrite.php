<?php

function csv_to_array($filename = '', $delimiter = ',') {
    if (!file_exists($filename) || !is_readable($filename))
        return FALSE;

    $header = NULL;
    $data = array();
    if (($handle = fopen($filename, 'r')) !== FALSE) {
        while (($row = fgetcsv($handle, 1000, $delimiter)) !== FALSE) {
            if (!$header)
                $header = $row;
            else
                $data[] = array_combine($header, $row);
        }
        fclose($handle);
    }
    return $data;
}

$keys = csv_to_array('url-match.csv');
$nodes = csv_to_array('two-column.csv');
foreach ($nodes as $node) {
    $content = $node['content'];
    $rightcallout = $node['right_callout'];
    $url = $node['start_url'];
    foreach ($keys as $key) {
        $dor = $key['dor'];
        $nid = $key['nid'];
        if (strpos($content, $dor) !== false) {
           $content = str_ireplace($dor, $nid, $content);
        }
        if ($rightcallout != '') {
            if (strpos($rightcallout, $dor) !== false) {
                $content = str_ireplace($dor, $nid, $rightcallout);
            }
        }
    }
    echo '<h2>' . $url . '</h2>';
    echo $content . '<br><br>';
}