<?php

namespace models;

/**
 * Class ActiveRecord
 * Allow objects to be retrieved from and saved in DB
 * @package models
 */
abstract class ActiveRecord
{
    /**
     * The table name for the current ActiveRecord
     * @var string
     */
    protected $tableName;

    /**
     * The primary key
     * @var array
     */
    protected static $primaryKey = [];

    /**
     * The table schema
     * @var array
     */
    protected static $schema;

    /**
     * The data of the current ActiveRecord
     * @var array
     */
    protected $attributes = [];

    /**
     * The DB connection
     * @var \PDO
     */
    protected static $pdo;

    /**
     * ActiveRecord constructor.
     */
    public function __construct()
    {
        $this->initSchema();
    }

    /**
     * Gets the object attributes
     * @return array
     */
    public function getAttributes()
    {
        return $this->attributes;
    }

    /**
     * Sets object attributes
     * @param array $attributes
     * @return $this
     */
    public function setAttributes($attributes)
    {
        $this->attributes = $attributes;
        return $this;
    }

    /**
     * Returns an attribute
     * @param string $attribute
     * @return mixed|null
     */
    public function get($attribute)
    {
        return isset($this->attributes[$attribute]) ? $this->attributes[$attribute] : null;
    }

    /**
     * Sets an attribute in the current object
     * @param string $attribute
     * @param mixed  $value
     * @return $this
     */
    public function set($attribute, $value)
    {
        $this->attributes[$attribute] = $value;
        return $this;
    }

    /**
     * @param int|array $id
     * @return bool
     */
    public function load($id)
    {
        if (is_scalar($id))
        {
            $key = reset(self::$primaryKey[get_called_class()]);
            $id  = [$key => $id];
        }
        elseif (!is_array($id) || empty($id))
        {
            throw new \RuntimeException('Wrong ID in ActiveRecord class ' . get_called_class(), 500);
        }

        // Prepare the condition on the primary key
        $keyCondition = [];
        $paramToBind  = [];
        foreach ($id as $field => $value)
        {
            $keyCondition[] = $field . ' = :' . $field;

            $binding               = ':' . $field;
            $paramToBind[$binding] = $value;
        }
        $keyCondition = '(' . implode(' AND ', $keyCondition) . ')';

        // Get the data
        $sql       = "SELECT * FROM $this->tableName WHERE " . $keyCondition . ' LIMIT 1';
        $statement = self::$pdo->prepare($sql);
        $statement->execute($paramToBind);
        $line = $statement->fetchAll(\PDO::FETCH_ASSOC);

        $this->attributes = isset($line[0]) ? $line[0] : [];

        return !empty($this->attributes);
    }

    /**
     * Inserts the current object into the DB
     * @return int
     */
    public function insert()
    {
        // Builds query fields and bindings
        $bindings = [];
        $fields   = [];
        foreach ($this->attributes as $field => $value)
        {
            $fields[]               = "`$field`";
            $bindings[':' . $field] = $value;
        }

        $fields = '(' . implode(',', $fields) . ')';
        $values = '(' . implode(',', array_keys($bindings)) . ')';

        // Build INSERT query
        $query     = "INSERT INTO $this->tableName $fields VALUES $values";
        $statement = self::$pdo->prepare($query);
        $statement->execute($bindings);

        return $statement->rowCount();
    }

    /**
     * Deletes the current object in the DB
     * @return int
     */
    public function delete()
    {
        // Check primary key existence
        $primaryKey = isset(self::$primaryKey[get_called_class()]) ? self::$primaryKey[get_called_class()] : null;
        if (is_null($primaryKey))
        {
            throw new \RuntimeException('Unknown primary key for class ' . get_called_class());
        }

        // Build DELETE query parameters
        $bindings = [];
        $where    = [];
        foreach ($primaryKey as $key)
        {
            if (!isset($this->attributes[$key]))
            {
                throw new \RuntimeException("Missing primary key $key in class " . get_called_class());
            }

            $currentBinding            = ':' . $key;
            $bindings[$currentBinding] = $this->attributes[$key];
            $where[]                   = "$key = $currentBinding";
        }
        $where = implode(' AND ', $where);

        // Build DELETE query
        $sql       = "DELETE FROM $this->tableName WHERE $where";
        $statement = self::$pdo->prepare($sql);
        $statement->execute($bindings);

        return $statement->rowCount();
    }

    //-------------------------------------------//
    //------------------ STATIC -----------------//
    //-------------------------------------------//

    /**
     * Sets the PDO DB connection
     * @param \PDO $pdo
     */
    public static function setPDO(\PDO $pdo)
    {
        self::$pdo = $pdo;
    }

    /**
     * Get table schema
     * @return array
     */
    public static function getSchema()
    {
        return self::$schema;
    }

    //--------------------------------------------------------//
    //------------------ PRIVATE / PROTECTED -----------------//
    //--------------------------------------------------------//

    /**
     * Inits the table schema
     * @return $this
     */
    private function initSchema()
    {
        // Init table schema
        if (!isset(self::$schema[get_called_class()]))
        {
            // Check PDO
            if (empty(self::$pdo))
            {
                throw new \RuntimeException('No database connection found to init ActiveRecords', 500);
            }

            // Check table name
            if (empty($this->tableName))
            {
                throw new \RuntimeException('Undefined table name in class ' . get_called_class(), 500);
            }

            // Retrieve table schema
            $sql       = "DESC $this->tableName";
            $statement = self::$pdo->query($sql);

            if ($statement === false)
            {
                throw new \RuntimeException("Unable to retrieve the schema of the table $this->tableName", 500);
            }

            // Saves current table schema
            $description = $statement->fetchAll(\PDO::FETCH_ASSOC);
            foreach ($description as $item)
            {
                self::$schema[get_called_class()][$item['Field']] = $item;
            }

            // Retrieve primary key
            foreach ($description as $field)
            {
                if ($field['Key'] === 'PRI')
                {
                    self::$primaryKey[get_called_class()][] = $field['Field'];
                }
            }
        }

        return $this;
    }
}
