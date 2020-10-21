<?php
/**
 * Created by IntelliJ IDEA.
 * User: sheershoff
 * Date: 11/23/15
 * Time: 9:59 PM
 */
namespace app\migrations; // use whatever you need to include in your migrations

use Yii;

class Migration extends \yii\db\Migration
{
    /**
     * @var string
     */
    protected $tableOptions;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        switch ($this->db->driverName) {
            case 'mysql':
            case 'pgsql':
            case 'sqlite':
                $this->tableOptions = null;
                break;
            default:
                throw new \RuntimeException('Your database is not supported!');
        }
    }

    public function dropColumn($table, $column)
    {
        if($this->db->driverName == 'sqlite'){
            $ts = $this->db->getTransaction();
            if(!$ts) $this->execute('BEGIN TRANSACTION;');
            $tmp_table = $this->db->getTableSchema($table,true)->fullName.'_'.substr(md5(time().rand(0,65535)),0,8);
            $columns = $this->db->getTableSchema($table,true)->columns;
            unset($columns[$column]);
            $columns = array_map(function($e){
                return $e->dbType;
            },$columns);
            $this->createTable($tmp_table,$columns);
            $this->execute('INSERT INTO '.$tmp_table.' SELECT '.implode(',',array_keys($columns)).' FROM '.$table);
            $this->dropTable($table);
            $this->execute('ALTER TABLE '.$tmp_table.' RENAME TO '.$table);
            if(!$ts) $this->execute('COMMIT;');
        }else{
            parent::dropColumn($table, $column);
        }
    }

    public function renameColumn($table, $name, $newName)
    {
        if($this->db->driverName == 'sqlite'){
            $ts = $this->db->getTransaction();
            if(!$ts) $this->execute('BEGIN TRANSACTION;');
            $tmp_table = $this->db->getTableSchema($table,true)->fullName.'_'.substr(md5(time().rand(0,65535)),0,8);
            $columns = $this->db->getTableSchema($table,true)->columns;
            $columns[$newName] = $columns[$name];
            unset($columns[$name]);
            $columns = array_map(function($e){
                return $e->dbType;
            },$columns);
            $select_statement = str_replace('`' . $newName . '`', '`' . $name . '` as `' . $newName . '`', '`' . implode('`, `', array_keys($columns)) . '`');
            $this->createTable($tmp_table,$columns);
            $this->execute('INSERT INTO '.$tmp_table.' SELECT '.$select_statement.' FROM '.$table);
            $this->dropTable($table);
            $this->execute('ALTER TABLE '.$tmp_table.' RENAME TO '.$table);
            if(!$ts) $this->execute('COMMIT;');
        }else{
            parent::renameColumn($table, $name, $newName);
        }
    }

    public function alterColumn($table, $column, $type)
    {
        if($this->db->driverName == 'sqlite'){
            $ts = $this->db->getTransaction();
            if(!$ts) $this->execute('BEGIN TRANSACTION;');
            $tmp_table = $this->db->getTableSchema($table,true)->fullName.'_'.substr(md5(time().rand(0,65535)),0,8);
            $columns = $this->db->getTableSchema($table,true)->columns;
            $columns = array_map(function($e){
                return $e->dbType;
            },$columns);
            $columns[$column] = $type;
            $this->createTable($tmp_table,$columns);
            $this->execute('INSERT INTO '.$tmp_table.' SELECT '.implode(',',array_keys($columns)).' FROM '.$table);
            $this->dropTable($table);
            $this->execute('ALTER TABLE '.$tmp_table.' RENAME TO '.$table);
            if(!$ts) $this->execute('COMMIT;');
        }else{
            parent::renameColumn($table, $column, $type);
        }
    }

    public function addForeignKey ( $name, $table, $columns, $refTable, $refColumns, $delete = null, $update = null ) {
        if($this->db->driverName == 'sqlite'){
            echo "    x addForeignKey IGNORED because of sqlite";
        }else{
            parent::addForeignKey ( $name, $table, $columns, $refTable, $refColumns, $delete, $update );
        }
    }

}