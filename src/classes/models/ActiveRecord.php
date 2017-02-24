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
     * Sets the PDO DB connection
     * @param \PDO $pdo
     */
    public static function setPDO(\PDO $pdo)
    {
        self::$pdo = $pdo;
    }
}
