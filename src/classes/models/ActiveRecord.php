<?php

namespace models;

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
     * @param int|array $id
     * @return bool
     */
    public function get($id)
    {
        if (is_scalar($id))
        {
            $key = reset(static::$primaryKey);
            $id = [$key => $id];
        }
        elseif (!is_array($id) || empty($id))
        {
            throw new \RuntimeException('Wrong ID in ActiveRecord class ' . get_called_class(), 500);
        }

        // Prepare the condition on the primary key
        $keyCondition = [];
        $paramToBind = [];
        foreach ($id as $field => $value)
        {
            $keyCondition[] = $field . ' = :' . $field;

            $binding = ':' . $field;
            $paramToBind[$binding] = $value;
        }
        $keyCondition = '(' . implode(' AND ', $keyCondition) . ')';

        // Get the data
        $sql = "SELECT * FROM $this->tableName WHERE " . $keyCondition . ' LIMIT 1';
        $statement = self::$pdo->prepare($sql);
        $statement->execute($paramToBind);
        $line = $statement->fetchAll(\PDO::FETCH_ASSOC);

        $this->attributes = isset($line[0]) ? $line[0] : [];

        return !empty($this->attributes);
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
        return static::$schema;
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
        if (!isset(static::$schema))
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
            $sql = "DESC $this->tableName";
            $statement = self::$pdo->query($sql);

            if ($statement === false)
            {
                throw new \RuntimeException("Unable to retrieve the schema of the table $this->tableName", 500);
            }

            $description = $statement->fetchAll(\PDO::FETCH_ASSOC);
            static::$schema = $description;

            // Retrieve primary key
            foreach ($description as $field)
            {
                if ($field['Key'] === 'PRI')
                {
                    static::$primaryKey[] = $field['Field'];
                }
            }
        }

        return $this;
    }
}
