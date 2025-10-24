<?php

class LeapModel
{

    public string $table;
    public LeapDB $db;
    public array $cols = [];
    public array $ignore_columns = [];
    public string $pk;
    public function __construct()
    {
        $this->db = new LeapDB();
        $this->pk = 'id' . $this->table;
        $this->fillDefault();
    }


    public static function WhereOne(string $where, array $params = [])
    {
        $w = $where;
        $db = new LeapDB();
        $modelClass = static::class;
        $model = new $modelClass();
        $query = "SELECT * FROM {$model->table} WHERE $w";
        $stmt = $db->connection->prepare($query);
        $stmt->execute($params);
        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
            $cols = [];
            foreach ($row as $key => $value) {
                if (in_array($key, $model->ignore_columns)) {
                    continue;
                }
                $cols[] = $key;
                $model->$key = $value;
            }
            $model->cols = $cols;
            return $model;
        }
    }


    public static function Delete(int $pk)
    {
        $db = new LeapDB();
        $modelClass = static::class;
        $model = new $modelClass();
        $query = "DELETE FROM {$model->table} WHERE {$model->pk}=$pk";
        $db->connection->exec($query);
    }


    public static function Where(string $where, array $params = [])
    {
        $db = new LeapDB();
        $modelClass = static::class;
        $model = new $modelClass();
        $query = "SELECT * FROM {$model->table} WHERE $where";
        $stmt = $db->connection->prepare($query);
        $stmt->execute($params);
        $rows = [];
        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
            $model = new $modelClass();
            $cols = [];
            foreach ($row as $key => $value) {
                if (in_array($key, $model->ignore_columns)) {
                    continue;
                }
                $cols[] = $key;
                $model->$key = $value;
            }
            $model->cols = $cols;
            $rows[] = $model;
        }
        return $rows;
    }


    public function loadFromRequest($request)
    {
        foreach ($this->cols as $col => $value) {
            if ($request->has($col)) {
                $this->$col = $request->load($col);
            }
        }
    }

    public function fillDefault()
    {
        foreach ($this->cols as $col => $meta) {
            if (!isset($this->$col) || $this->$col === null) {
                if ($meta['default'] !== NULL) {
                    if (stripos($meta['default'], 'nextval') !== false) {
                        continue; //skip serial default values
                    }
                    $this->$col = $meta['default'];
                } else {
                    switch ($meta['type']) {
                        case 'int':
                            $this->$col = 0;
                            break;
                        case 'string':
                            $this->$col = '';
                            break;
                        case 'bool':
                            $this->$col = false;
                            break;
                        default:
                            $this->$col = null;
                    }
                }
            }
        }
    }


    public function get($column)
    {
        //handle get/post/json request
        return $this->$column;
    }

    public function findAll()
    {
        $query = "SELECT * FROM {$this->table}";
        $stmt = $this->db->connection->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function raw($query)
    {
        $stmt = $this->db->connection->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function rawQuery($query)
    {
        return $this->raw($query);
    }

    public function fetchAll($columns = '*', $where = null, $order = null, $limit = null)
    {
        $query = "SELECT $columns FROM {$this->table}";
        if ($where) {
            $query .= " WHERE $where";
        }
        if ($order) {
            $query .= " ORDER BY $order";
        }
        if ($limit) {
            $query .= " LIMIT $limit";
        }
        $stmt = $this->db->connection->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function save()
    {
        $data = get_object_vars($this);
        //get the record with the pk

        if (array_key_exists($data['pk'], $data)) { //update
            $orig = $this->db->query("SELECT * FROM {$data['table']} WHERE {$data['pk']}='{$data[$data['pk']]}'");
            $orig = $orig[0];
            $update = [];
            foreach ($orig as $key => $val) {
                if ($data[$key] != $val) {
                    $update[$key] = $data[$key];
                }
            }
            $fields = [];
            foreach ($update as $key => $val) {
                $fields[] = "{$key}='{$val}'";
            }
            if (count($fields) == 0) {
                return true;
            }
            $sql = "UPDATE {$data['table']} SET " . implode(",", $fields) . " WHERE {$data['pk']}='{$data[$data['pk']]}'";
            $this->db->execute($sql);
        } else { //insert
            // get columns from information schema
            // prepare data
            $insert_data = [];
            foreach ($data['cols'] as $col => $params) {
                if (array_key_exists($col, $data) && $data[$col] !== null) {
                    if ($params['default'] = NULL && $data[$col] == '') {
                        $data[$col] = NULL;
                    }
                    $insert_data[$col] = $data[$col];
                }
            }

            if (count($insert_data) == 0) {
                throw new Exception("Couldn't find data to insert");
            }

            // SQL-Statement aufbauen
            $fields = array_keys($insert_data);
            $values = array_map(function ($v) {
                if ($v == '' || $v == NULL) {
                    return "NULL";
                } else {
                    return "'" . addslashes($v) . "'";
                }
            }, array_values($insert_data));

            $sql = "INSERT INTO {$data['table']} (" . implode(",", $fields) . ") VALUES (" . implode(",", $values) . ")";
            $this->db->execute($sql);

            //return new PK, if is serial
            if (isset($data['pk'])) {
                $id = $this->db->query("SELECT currval(pg_get_serial_sequence('{$data['table']}', '{$data['pk']}')) as id");
                $this->{$data['pk']} = $id[0]->id;
            }
        }
    }
}
