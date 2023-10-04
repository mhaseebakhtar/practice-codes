<?php
require_once 'DB.php';

$db = new DB();

$response = $db->create('testing_table', array('col1' => 'val1', 'col2' => 'val2', 'col3' => 'val3'));
echo '$response<pre>'; var_dump($response); echo '</pre>';

$response = $db->read('testing_table', array('col1' => 'val1'));
echo '$response<pre>'; print_r($response); echo '</pre>';

$response = $db->update('testing_table', array('col1' => 'val1', 'col2' => 'val2'), array('col3' => 'val3'));
echo '$response<pre>'; var_dump($response); echo '</pre>';

$response = $db->delete('testing_table', array('col1' => 'val1'));
echo '$response<pre>'; var_dump($response); echo '</pre>';