<?php
echo '<html>';
echo '<head>';
echo '<title>Start URL to Node Converter</title>';
echo '<style>';
include 'css/main.css';
echo '</style>';
echo '</head>';
echo '<body>';
echo '<div class="formdiv">';
echo '<h1>Formy Form to Replace Start URLs with Their New Nodes IDs</h1>';
echo '<form method="POST" action="rewrite.php">';
$nodesfolder = 'exported-nodes';
$keysfolder = 'keys';
$allkeysfiles = array_diff(scandir($keysfolder), array('.', '..'));
$allnodesfiles = array_diff(scandir($nodesfolder), array('.', '..'));
echo '<label>Select a Source for Your Nodes</label><br>';
echo '<select name="nodefilename" id ="nodefilename">';
foreach ($allnodesfiles as $nodesfileoption) {
    $nodessfileoption = htmlspecialchars($nodessfileoption);
    echo '<option value="' . $nodesfileoption . '">' . $nodesfileoption . '</option>';
}
echo '</select>';
echo '<br>';
echo '<label>Select a Key to Match Against</label><br>';
echo '<select name="keyfilename" id ="keyfilename">';
foreach ($allkeysfiles as $keyssfileoption) {
    $keyssfileoption = htmlspecialchars($keyssfileoption);
    echo '<option value="' . $keyssfileoption . '">' . $keyssfileoption . '</option>';
}
echo '</select>';
echo '<br>';
echo '<input type="submit">';
echo '</form>';
echo '</div>';
echo '</body>';
echo '</html>';
