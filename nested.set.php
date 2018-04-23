<?php

class Nested_Set
{
    protected $_connect;
    protected $_data;
    protected $_parent_id;
    protected $_table = 'menu';

    public function __construct()
    {
        $link = mysqli_connect('localhost', 'root', '', 'menu');
        if (!$link) {
            die('could not connect: ' . mysqli_error($link));
        } else {
            $this->_connect = $link;
        }
    }

    public function insert_node($data, $parent = 1, $option = null)
    {
        $this->_data = $data;
        $this->_parent_id = $parent;

        switch ($option['position'] || !$option) {
            case 'right' :
                $this->insert_right();
                break;
            case 'left' :
                $this->insert_left();
                break;
            case 'before' :
                $this->insert_before($option['node_id']);
                break;
            case 'after' :
                $this->insert_after($option['node_id']);
                break;
        }
    }

    public function get_node_info($id)
    {
        $sql = 'SELECT * from ' . $this->_table . ' WHERE id = ' . $id;
        $result = mysqli_query($this->_connect, $sql);
        $node = mysqli_fetch_assoc($result);

        return $node;
    }

    protected function insert_right()
    {
        $parent_info = $this->get_node_info($this->_parent_id);
        $parent_right = $parent_info['rgt'];

        $sql_left = 'UPDATE ' .$this->_table . ' SET lft = (lft + 2) WHERE lft > ' . $parent_right;
        mysqli_query($this->_connect, $sql_left);
        $sql_right = 'UPDATE ' .$this->_table . ' SET rgt = (rgt + 2) WHERE rgt >= ' . $parent_right;
        mysqli_query($this->_connect, $sql_right);

        $data = $this->_data;
        $data['parent'] = $this->_parent_id;
        $data['lft'] = $parent_right;
        $data['rgt'] = $parent_right + 1;
        $data['level'] = $parent_info['level'] + 1;

        $newQuey = $this->createInsertQuery($data);

        $sqlInsert = 'INSERT INTO ' . $this->_table
            . "(" . $newQuey['cols'] . ") "
            . " VALUES(" . $newQuey['vals'] . ")";

        mysqli_query($this->_connect, $sqlInsert);
    }

    protected function insert_left()
    {

    }

    protected function insert_before($node)
    {

    }

    protected function insert_after($node)
    {

    }

    protected function createInsertQuery($data = null){
        if(count($data)>0 ){
            $cols = '';
            $vals = '';
            $i = 1;
            foreach ($data as $key => $value){
                if($i == 1){
                    $cols .= "`" . $key . "`";
                    $vals .= "'" . $value . "'";
                }else{
                    $cols .= ",`" . $key . "`";
                    $vals .= ",'" . $value . "'";
                }
                $i++;
            }

            $newQuery['cols'] =  $cols;
            $newQuery['vals'] =  $vals;
        }
        return $newQuery;
    }
}