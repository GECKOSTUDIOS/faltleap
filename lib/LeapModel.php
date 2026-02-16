<?php

declare(strict_types=1);

namespace FaltLeap;

use PDO;

class LeapModel
{
    public string $table;
    public string $schema = 'public'; // Default schema
    public LeapDB $db;
    public array $cols = [];
    public array $ignore_columns = [];
    public string $pk;

    // Query builder properties
    protected static ?LeapQueryBuilder $queryBuilder = null;

    public function __construct()
    {
        $this->db = new LeapDB();
        $this->pk = 'id' . $this->table;

        // Initialize all column properties to null to prevent undefined property warnings
        foreach ($this->cols as $columnName => $columnInfo) {
            if (!isset($this->$columnName)) {
                $this->$columnName = null;
            }
        }
    }

    /**
     * Get the full qualified table name with schema
     * @return string
     */
    public function getQualifiedTableName(): string
    {
        // Use getFullTableName() if available (from generated models), otherwise construct it
        if (method_exists($this, 'getFullTableName')) {
            return $this->getFullTableName();
        }
        return $this->schema . '.' . $this->table;
    }


    /**
     * Create a new query builder instance
     * @return LeapQueryBuilder
     */
    public static function Query(): LeapQueryBuilder
    {
        return new LeapQueryBuilder(static::class);
    }

    /**
     * Create a new query builder with join support
     * @param string $joinModel The model class name to join with
     * @param string|null $foreignKey Optional foreign key column (auto-detected if null)
     * @param string|null $joinType Type of join (INNER, LEFT, RIGHT) - defaults to LEFT
     * @return LeapQueryBuilder
     */
    public static function Join(string $joinModel, ?string $foreignKey = null, string $joinType = 'LEFT'): LeapQueryBuilder
    {
        $builder = new LeapQueryBuilder(static::class);
        return $builder->join($joinModel, $foreignKey, $joinType);
    }

    /**
     * Count records matching WHERE clause
     * @param string $where WHERE condition (optional)
     * @param array $params Parameters for the condition
     * @return int Number of matching records
     */
    public static function Count(string $where = "", array $params = []): int
    {
        $db = new LeapDB();
        $modelClass = static::class;
        $model = new $modelClass();
        $qualifiedTable = $model->getQualifiedTableName();

        $query = "SELECT COUNT(*) as count FROM {$qualifiedTable}";
        if ($where !== "") {
            $query .= " WHERE {$where}";
        }

        $stmt = $db->connection->prepare($query);
        $stmt->execute($params);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return (int)$result['count'];
    }

    /**
     * Sum a column's values
     * @param string $column Column to sum
     * @param string $where WHERE condition (optional)
     * @param array $params Parameters for the condition
     * @return float Sum of the column
     */
    public static function Sum(string $column, string $where = "", array $params = []): float
    {
        $db = new LeapDB();
        $modelClass = static::class;
        $model = new $modelClass();
        $qualifiedTable = $model->getQualifiedTableName();

        $query = "SELECT SUM({$column}) as total FROM {$qualifiedTable}";
        if ($where !== "") {
            $query .= " WHERE {$where}";
        }

        $stmt = $db->connection->prepare($query);
        $stmt->execute($params);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return (float)($result['total'] ?? 0);
    }

    /**
     * Average a column's values
     * @param string $column Column to average
     * @param string $where WHERE condition (optional)
     * @param array $params Parameters for the condition
     * @return float Average of the column
     */
    public static function Avg(string $column, string $where = "", array $params = []): float
    {
        $db = new LeapDB();
        $modelClass = static::class;
        $model = new $modelClass();
        $qualifiedTable = $model->getQualifiedTableName();

        $query = "SELECT AVG({$column}) as average FROM {$qualifiedTable}";
        if ($where !== "") {
            $query .= " WHERE {$where}";
        }

        $stmt = $db->connection->prepare($query);
        $stmt->execute($params);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return (float)($result['average'] ?? 0);
    }

    /**
     * Get maximum value of a column
     * @param string $column Column to get max value from
     * @param string $where WHERE condition (optional)
     * @param array $params Parameters for the condition
     * @return mixed Maximum value
     */
    public static function Max(string $column, string $where = "", array $params = [])
    {
        $db = new LeapDB();
        $modelClass = static::class;
        $model = new $modelClass();
        $qualifiedTable = $model->getQualifiedTableName();

        $query = "SELECT MAX({$column}) as maximum FROM {$qualifiedTable}";
        if ($where !== "") {
            $query .= " WHERE {$where}";
        }

        $stmt = $db->connection->prepare($query);
        $stmt->execute($params);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result['maximum'];
    }

    /**
     * Get minimum value of a column
     * @param string $column Column to get min value from
     * @param string $where WHERE condition (optional)
     * @param array $params Parameters for the condition
     * @return mixed Minimum value
     */
    public static function Min(string $column, string $where = "", array $params = [])
    {
        $db = new LeapDB();
        $modelClass = static::class;
        $model = new $modelClass();
        $qualifiedTable = $model->getQualifiedTableName();

        $query = "SELECT MIN({$column}) as minimum FROM {$qualifiedTable}";
        if ($where !== "") {
            $query .= " WHERE {$where}";
        }

        $stmt = $db->connection->prepare($query);
        $stmt->execute($params);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result['minimum'];
    }

    /**
     * Delete records matching WHERE clause
     * @param string $where WHERE condition
     * @param array $params Parameters for the condition
     * @return int Number of deleted rows
     */
    public static function DeleteWhere(string $where, array $params = []): int
    {
        $db = new LeapDB();
        $modelClass = static::class;
        $model = new $modelClass();
        $qualifiedTable = $model->getQualifiedTableName();

        $query = "DELETE FROM {$qualifiedTable} WHERE {$where}";
        $stmt = $db->connection->prepare($query);
        $stmt->execute($params);

        return $stmt->rowCount();
    }

    public static function WhereOne(string $where, array $params = [])
    {
        // Check if called from query builder
        if (static::$queryBuilder !== null) {
            $builder = static::$queryBuilder;
            static::$queryBuilder = null; // Reset
            return $builder->whereOne($where, $params);
        }

        $w = $where;
        $db = new LeapDB();
        $modelClass = static::class;
        $model = new $modelClass();
        $qualifiedTable = $model->getQualifiedTableName();
        $query = "SELECT * FROM {$qualifiedTable} WHERE $w";
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
        $qualifiedTable = $model->getQualifiedTableName();
        $query = "DELETE FROM {$qualifiedTable} WHERE {$model->pk}=$pk";
        $db->connection->exec($query);
    }


    public static function Where(string $where, array $params = [])
    {
        // Check if called from query builder
        if (static::$queryBuilder !== null) {
            $builder = static::$queryBuilder;
            static::$queryBuilder = null; // Reset
            return $builder->where($where, $params);
        }

        $db = new LeapDB();
        $modelClass = static::class;
        $model = new $modelClass();
        $qualifiedTable = $model->getQualifiedTableName();
        $query = "SELECT * FROM {$qualifiedTable} WHERE $where";
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


    public function loadFromRequest($request, $pk = null)
    {
        foreach ($this->cols as $col => $value) {
            // Skip ignored columns
            if (in_array($col, $this->ignore_columns)) {
                continue;
            }
            if ($request->has($col)) {
                $this->$col = $request->load($col);
            }
        }
        if ($pk) {
            $this->{$this->pk} = $pk;
        }
    }

    public function fillDefault()
    {
        foreach ($this->cols as $col => $meta) {
            if (!isset($this->$col) || $this->$col === null) {
                if ($meta['default'] !== null) {
                    // Skip auto-increment columns (those with nextval in default)
                    if (is_string($meta['default']) && stripos($meta['default'], 'nextval') !== false) {
                        continue;
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
        $qualifiedTable = $this->getQualifiedTableName();
        $query = "SELECT * FROM {$qualifiedTable}";
        $stmt = $this->db->connection->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function jkj($query)
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
        $qualifiedTable = $this->getQualifiedTableName();
        $query = "SELECT $columns FROM {$qualifiedTable}";
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
        $qualifiedTable = $this->getQualifiedTableName();

        if (array_key_exists($data['pk'], $data) && !empty($data[$data['pk']])) { //update
            $orig = $this->db->query("SELECT * FROM {$qualifiedTable} WHERE {$data['pk']}=:pk_value", [':pk_value' => $data[$data['pk']]]);
            $orig = $orig[0];
            $update = [];
            foreach ($orig as $key => $val) {
                // Skip ignored columns
                if (in_array($key, $this->ignore_columns)) {
                    continue;
                }
                // Check if key exists in data before comparing
                if (array_key_exists($key, $data) && $data[$key] != $val) {
                    $update[$key] = $data[$key];
                }
            }
            $fields = [];
            $params = [];
            foreach ($update as $key => $val) {
                if (is_bool($val)) {
                    // Check if the column type is int (for boolean stored as integer)
                    if (isset($data['cols'][$key]) && $data['cols'][$key]['type'] === 'int') {
                        $fields[] = "{$key}=" . ($val ? '1' : '0');
                    } else {
                        $fields[] = "{$key}=" . ($val ? 'true' : 'false');
                    }
                } elseif (is_null($val)) {
                    $fields[] = "{$key}=NULL";
                } else {
                    $fields[] = "{$key}=:{$key}";
                    $params[":{$key}"] = $val;
                }
            }
            if (count($fields) == 0) {
                return true;
            }
            $params[':pk_value'] = $data[$data['pk']];
            $sql = "UPDATE {$qualifiedTable} SET " . implode(",", $fields) . " WHERE {$data['pk']}=:pk_value";
            $this->db->execute($sql, $params);
        } else { //insert
            // get columns from information schema
            // prepare data
            $insert_data = [];
            foreach ($data['cols'] as $col => $params) {
                // Skip auto-increment columns (those with nextval in default)
                if (isset($params['default']) && is_string($params['default']) && stripos($params['default'], 'nextval') !== false) {
                    continue;
                }

                if (array_key_exists($col, $data) && $data[$col] !== null) {
                    if ($params['default'] == null && $data[$col] == '') {
                        $data[$col] = null;
                    }
                    $insert_data[$col] = $data[$col];
                }
            }

            if (count($insert_data) == 0) {
                throw new Exception("Couldn't find data to insert");
            }

            // SQL-Statement aufbauen
            $fields = array_keys($insert_data);
            $placeholders = [];
            $params = [];

            foreach ($insert_data as $key => $v) {
                if (is_bool($v)) {
                    // Check if the column type is int (for boolean stored as integer)
                    if (isset($data['cols'][$key]) && $data['cols'][$key]['type'] === 'int') {
                        $placeholders[] = $v ? '1' : '0';
                    } else {
                        $placeholders[] = $v ? 'true' : 'false';
                    }
                } elseif ($v === '' || $v === null) {
                    $placeholders[] = "NULL";
                } else {
                    $placeholders[] = ":{$key}";
                    $params[":{$key}"] = $v;
                }
            }

            $sql = "INSERT INTO {$qualifiedTable} (" . implode(",", $fields) . ") VALUES (" . implode(",", $placeholders) . ")";
            $this->db->execute($sql, $params);

            //return new PK, if is serial
            if (isset($data['pk'])) {
                // Table and column names come from model definition, so they're safe to use directly
                $id = $this->db->query("SELECT currval(pg_get_serial_sequence('{$qualifiedTable}', '{$data['pk']}')) as id");
                $this->{$data['pk']} = $id[0]['id'];
            }
        }
    }
}

/**
 * Query Builder class for handling joins and complex queries
 */
class LeapQueryBuilder
{
    protected string $baseModel;
    protected array $joins = [];
    protected LeapDB $db;
    protected array $selectColumns = [];
    protected array $whereClauses = [];
    protected array $whereParams = [];
    protected ?string $orderBy = null;
    protected ?int $limit = null;
    protected ?int $offset = null;
    protected array $groupBy = [];
    protected ?string $having = null;
    protected array $havingParams = [];
    protected ?string $baseTableAlias = null;

    public function __construct(string $baseModel)
    {
        $this->baseModel = $baseModel;
        $this->db = new LeapDB();
    }

    /**
     * Set an alias for the base table
     * @param string $alias Table alias (e.g., 'c', 'u', 'cr')
     * @return self
     */
    public function alias(string $alias): self
    {
        $this->baseTableAlias = $alias;
        return $this;
    }

    /**
     * Add a join to the query
     * @param string $joinModel Model class name to join OR raw table name with alias (e.g., 'table_name alias')
     * @param string|null $foreignKey Foreign key column or ON condition (e.g., 'base.id = alias.foreign_id')
     * @param string $joinType Type of join (INNER, LEFT, RIGHT)
     * @return self
     */
    public function join(string $joinModel, ?string $foreignKey = null, string $joinType = 'LEFT'): self
    {
        $baseInstance = new $this->baseModel();

        // Check if it's a raw join (contains space, indicating alias)
        if (strpos($joinModel, ' ') !== false) {
            // Raw join: "table_name alias"
            $parts = explode(' ', $joinModel, 2);
            $tableName = trim($parts[0]);
            $tableAlias = trim($parts[1]);

            $this->joins[] = [
                'model' => null,
                'table' => $tableName,
                'qualifiedTable' => $tableName,
                'foreignKey' => $foreignKey ?? '',
                'primaryKey' => null,
                'joinType' => strtoupper($joinType),
                'tableAlias' => $tableAlias,
                'isRaw' => true,
                'onCondition' => $foreignKey ?? ''
            ];
        } else {
            // Model-based join
            $joinInstance = new $joinModel();

            // Auto-detect foreign key if not provided
            if ($foreignKey === null) {
                // Convention: look for column named 'id{tablename}' in base model
                $foreignKey = $joinInstance->pk;
            }

            // Get qualified table name
            $qualifiedTable = method_exists($joinInstance, 'getQualifiedTableName')
                ? $joinInstance->getQualifiedTableName()
                : $joinInstance->schema . '.' . $joinInstance->table;

            $this->joins[] = [
                'model' => $joinModel,
                'table' => $joinInstance->table,
                'qualifiedTable' => $qualifiedTable,
                'foreignKey' => $foreignKey,
                'primaryKey' => $joinInstance->pk,
                'joinType' => strtoupper($joinType),
                'tableAlias' => strtolower($joinInstance->table),
                'isRaw' => false
            ];
        }

        return $this;
    }

    /**
     * Specify columns to select
     * @param string ...$columns Column names (e.g., 'col1', 'col2 as alias', 'table.col')
     * @return self
     */
    public function select(string ...$columns): self
    {
        $this->selectColumns = $columns;
        return $this;
    }

    /**
     * Add ORDER BY clause
     * @param string $orderBy Order by expression (e.g., 'col DESC', 'col1, col2 ASC')
     * @return self
     */
    public function orderBy(string $orderBy): self
    {
        $this->orderBy = $orderBy;
        return $this;
    }

    /**
     * Add LIMIT clause
     * @param int $limit Maximum number of rows
     * @return self
     */
    public function limit(int $limit): self
    {
        $this->limit = $limit;
        return $this;
    }

    /**
     * Add OFFSET clause
     * @param int $offset Number of rows to skip
     * @return self
     */
    public function offset(int $offset): self
    {
        $this->offset = $offset;
        return $this;
    }

    /**
     * Add GROUP BY clause
     * @param string ...$columns Columns to group by
     * @return self
     */
    public function groupBy(string ...$columns): self
    {
        $this->groupBy = $columns;
        return $this;
    }

    /**
     * Add HAVING clause (use with GROUP BY)
     * @param string $condition Having condition
     * @param array $params Parameters for the condition
     * @return self
     */
    public function having(string $condition, array $params = []): self
    {
        $this->having = $condition;
        $this->havingParams = $params;
        return $this;
    }

    /**
     * Add WHERE clause to the query builder
     * @param string $where WHERE condition
     * @param array $params Parameters for the condition
     * @return self
     */
    public function where(string $where, array $params = []): self
    {
        $this->whereClauses[] = $where;
        $this->whereParams = array_merge($this->whereParams, $params);
        return $this;
    }

    /**
     * Execute the query and return all results
     * @return array Array of hydrated models
     */
    public function get(): array
    {
        $query = $this->buildQuery();
        $allParams = array_merge($this->whereParams, $this->havingParams);

        $stmt = $this->db->connection->prepare($query);
        $stmt->execute($allParams);

        return $this->hydrateResults($stmt->fetchAll(PDO::FETCH_ASSOC));
    }

    /**
     * Execute the query and return first result
     * @return mixed Single hydrated model or null
     */
    public function first()
    {
        $this->limit(1);
        $results = $this->get();
        return count($results) > 0 ? $results[0] : null;
    }

    /**
     * Build the complete SQL query
     * @return string The SQL query
     */
    protected function buildQuery(): string
    {
        $baseInstance = new $this->baseModel();
        $baseTable = $baseInstance->table;
        $baseQualifiedTable = method_exists($baseInstance, 'getQualifiedTableName')
            ? $baseInstance->getQualifiedTableName()
            : $baseInstance->schema . '.' . $baseInstance->table;

        // Use alias if set, otherwise use table name
        $baseAlias = $this->baseTableAlias ?? $baseTable;

        // Build SELECT clause
        if (empty($this->selectColumns)) {
            // Default: select all columns from base and joined tables
            $selectParts = ["{$baseAlias}.*"];
            foreach ($this->joins as $join) {
                $alias = $join['tableAlias'];
                if ($join['isRaw'] ?? false) {
                    // For raw joins, just select alias.*
                    $selectParts[] = "{$alias}.*";
                } else {
                    $joinInstance = new $join['model']();
                    // Select all columns from joined table with alias prefix
                    foreach ($joinInstance->cols as $colName => $colMeta) {
                        $selectParts[] = "{$alias}.{$colName} as {$alias}__{$colName}";
                    }
                }
            }
            $selectClause = implode(', ', $selectParts);
        } else {
            // Use custom columns
            $selectClause = implode(', ', $this->selectColumns);
        }

        // Build JOIN clauses
        $joinClauses = [];
        foreach ($this->joins as $join) {
            $alias = $join['tableAlias'];
            $qualifiedTable = $join['qualifiedTable'];

            if ($join['isRaw'] ?? false) {
                // Raw join with custom ON condition
                $joinClauses[] = "{$join['joinType']} JOIN {$qualifiedTable} {$alias} ON {$join['onCondition']}";
            } else {
                // Model-based join
                $joinClauses[] = "{$join['joinType']} JOIN {$qualifiedTable} {$alias} ON {$baseAlias}.{$join['foreignKey']} = {$alias}.{$join['primaryKey']}";
            }
        }

        // Start building query - use alias after table name
        $query = "SELECT {$selectClause} FROM {$baseQualifiedTable} {$baseAlias}";

        // Add JOINs
        if (!empty($joinClauses)) {
            $query .= " " . implode(' ', $joinClauses);
        }

        // Add WHERE
        if (!empty($this->whereClauses)) {
            $query .= " WHERE " . implode(' AND ', $this->whereClauses);
        }

        // Add GROUP BY
        if (!empty($this->groupBy)) {
            $query .= " GROUP BY " . implode(', ', $this->groupBy);
        }

        // Add HAVING
        if ($this->having !== null) {
            $query .= " HAVING {$this->having}";
        }

        // Add ORDER BY
        if ($this->orderBy !== null) {
            $query .= " ORDER BY {$this->orderBy}";
        }

        // Add LIMIT
        if ($this->limit !== null) {
            $query .= " LIMIT {$this->limit}";
        }

        // Add OFFSET
        if ($this->offset !== null) {
            $query .= " OFFSET {$this->offset}";
        }

        return $query;
    }


    /**
     * Hydrate results into model instances with nested joined objects
     * @param array $rows Raw database rows
     * @return array Array of hydrated models
     */
    protected function hydrateResults(array $rows): array
    {
        $results = [];

        foreach ($rows as $row) {
            $baseInstance = new $this->baseModel();
            $cols = [];

            // Hydrate base model
            foreach ($row as $key => $value) {
                // Skip joined table columns (they have __ prefix)
                if (strpos($key, '__') === false) {
                    if (in_array($key, $baseInstance->ignore_columns)) {
                        continue;
                    }
                    $cols[] = $key;
                    $baseInstance->$key = $value;
                }
            }
            $baseInstance->cols = $cols;

            // Hydrate joined models as nested properties
            foreach ($this->joins as $join) {
                $alias = $join['tableAlias'];

                // Skip raw joins when using custom select (columns already in base object)
                if ($join['isRaw'] ?? false) {
                    continue;
                }

                $joinInstance = new $join['model']();
                $joinCols = [];

                foreach ($joinInstance->cols as $colName => $colMeta) {
                    $prefixedKey = "{$alias}__{$colName}";
                    if (array_key_exists($prefixedKey, $row)) {
                        $joinCols[] = $colName;
                        $joinInstance->$colName = $row[$prefixedKey];
                    }
                }

                $joinInstance->cols = $joinCols;

                // Add joined model as property using table name
                $baseInstance->$alias = $joinInstance;
            }

            $results[] = $baseInstance;
        }

        return $results;
    }
}
