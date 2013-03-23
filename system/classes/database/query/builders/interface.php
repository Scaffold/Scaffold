<?php defined('SCAFFOLD') or die;

/**
 * All DatabaseQueryBuilders must implement this interface.
 *
 * This allows us Scaffold to know how to use it.
 */
interface DatabaseQueryBuilderInterface {
    public function select();
    public function count();
    public function insert($table, $data);
    public function update();
    public function structure($table);
    public function delete();
}
