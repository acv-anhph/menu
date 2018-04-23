<?php

require_once 'nested.set.php';

$tree = new Nested_Set();

$data = array('name' => 'Sale Manager', 'slug' => 'sale-manager');

$tree->insert_node($data, 1);