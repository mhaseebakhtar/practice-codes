<?php
require_once 'DB.php';

$db = new DB();

$response = $db->table('testing_table')->insert(['col1' => 'val1', 'col2' => 'val2', 'col3' => 'val3']);
echo '$response<pre>'; var_dump($response); echo '</pre>';

$response = $db->table('testing_table')->select(['col1', 'col2'])->where(['col1' => 'val1'])->get();
echo '$response<pre>'; print_r($response); echo '</pre>';

$response = $db->table('testing_table')->select(['col1', 'col2'])->where(['col1' => 'val1'])->getAll();
echo '$response<pre>'; print_r($response); echo '</pre>';

$response = $db->table('testing_table')->where(['col3' => 'val3'])->update(array('col1' => 'val1', 'col2' => 'val2'));
echo '$response<pre>'; var_dump($response); echo '</pre>';

$response = $db->table('testing_table')->where(['col1' => 'val1'])->delete();
echo '$response<pre>'; var_dump($response); echo '</pre>';