<?php

require_once 'nested.set.php';

$tree = new Nested_Set();

$data = array('name' => 'IT Manager', 'slug' => 'it-manager');

$tree->insert_node($data, 1, array('position' => 'after', 'node_id' => 7));