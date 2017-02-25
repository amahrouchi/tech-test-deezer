<?php
namespace models;

class User extends ActiveRecord
{
    protected $tableName = 'users';

    /**
     * Get the user's favorite songs
     * @return array
     */
    public function getSongs()
    {
        $query = 'SELECT S.*
                    FROM songs S
                    INNER JOIN users_songs US
                        ON S.song_id = US.song_id
                        AND US.user_id = :user_id';

        $statement = self::$pdo->prepare($query);
        $statement->execute([':user_id' => $this->get('user_id')]);

        return $statement->fetchAll(\PDO::FETCH_ASSOC);
    }
}
