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
    protected $primaryKey = [];

    /**
     * The table schema
     * @var array
     */
    private static $schema;

    /**
     * The data of the current ActiveRecord
     * @var array
     */
    protected $attributes;

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
     * Inits the table schema
     * @return $this
     */
    private function initSchema()
    {
        // Init table schema
        if (!isset(static::$schema))
        {
            $sql = "DESC $this->tableName";
            $statement = self::$pdo->query($sql);

            if ($statement === false)
            {
                throw new \RuntimeException("Unable to retrieve the schema of the table $this->tableName", 500);
            }

            $description = $statement->fetchAll(\PDO::FETCH_ASSOC);
            static::$schema = $description;
            foreach ($description as $field)
            {
                if ($field['Key'] === 'PRI')
                {
                    $this->primaryKey[] = $field['Field'];
                }
            }
        }

        return $this;
    }

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
}
