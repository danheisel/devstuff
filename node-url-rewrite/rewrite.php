<?php

session_start();

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

if (!empty($_POST)) {
    $keyscsv = ($_POST['keyfilename']);
    $nodescsv = ($_POST['nodefilename']);
    $nodesfolder = 'exported-nodes';
    $keysfolder = 'keys';
    $keys = csv_to_array($keysfolder . '/' . $keyscsv);
    $nodes = csv_to_array($nodesfolder . '/' . $nodescsv);
    $updatednodes = array();
    foreach ($nodes as $node) {
        $content = $node['content'];
        $rightcallout = $node['right_callout'];
        $url = $node['start_url'];
        foreach ($keys as $key) {
            $dor = $key['start_url'];
            $nid = $key['nid'];
            $lowerdor = strtolower($dor);
            $lowercontent = strtolower($content);
            $lowerrightcallout = strtolower($rightcallout);

            if (strpos($lowercontent, $lowerdor) !== false) {
                $updatedcontent = str_ireplace($lowerdor, $nid, $content);
            }
            if ($rightcallout != '') {
                if (strpos($lowerrightcallout, $lowerdor) !== false) {
                    $updatedrightcallout = str_ireplace($lowerdor, $nid, $rightcallout);
                }
            }
        }
        $node['content'] = $updatedcontent;
        $node['right_callout'] = $updatedrightcallout;
        $updatednodes[] = $node;
    }
    $layout = basename($nodescsv, '.csv');
    $fileName = 'updated-' . $layout . '-nodes.csv';
    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
    header('Content-Description: File Transfer');
    header("Content-type: text/csv");
    header("Content-Disposition: attachment; filename={$fileName}");
    header("Expires: 0");
    header("Pragma: public");

    $fh = @fopen('php://output', 'w');

    $headerDisplayed = false;

    foreach ($updatednodes as $data) {
        // Add a header row if it hasn't been added yet
        if (!$headerDisplayed) {
            // Use the keys from $data as the titles
            fputcsv($fh, array_keys($data));
            $headerDisplayed = true;
        }

        // Put the data into the stream
        fputcsv($fh, $data);
    }
// Close the file
    fclose($fh);
// Make sure nothing else is sent, our file is done
    exit;
} else {
    header('Location: index.php');
}

