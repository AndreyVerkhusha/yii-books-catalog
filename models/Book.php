<?php

namespace app\models;

use yii\base\InvalidConfigException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Exception;

/**
 * @property int $id
 * @property string $title
 * @property int $publish_year
 * @property string|null $description
 * @property string $isbn
 * @property string|null $image_path
 * @property int $created_at
 * @property int $updated_at
 *
 * @property int[] $author_ids
 * @property Author[] $authors
 */
class Book extends ActiveRecord
{
    public array $author_ids = [];
    public static function tableName(): string
    {
        return 'book';
    }

    public function rules(): array
    {
        return [
            [['isbn'], 'unique'],
            [['description'], 'string'],
            [['title', 'publish_year', 'isbn', 'author_ids'], 'required'],
            [['title'], 'string', 'max' => 255],
            [['isbn'], 'string', 'max' => 32],
            [['image_path'], 'string', 'max' => 255],
            [['publish_year'], 'integer', 'min' => 1000, 'max' => 2100],
            [['author_ids'], 'each', 'rule' => ['integer']],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'title' => 'Название',
            'publish_year' => 'Год выпуска',
            'description' => 'Описание',
            'isbn' => 'ISBN',
            'image_path' => 'Путь к фото',
            'created_at' => 'Создано',
            'updated_at' => 'Обновлено',
        ];
    }

    /**
     * @throws InvalidConfigException
     */
    public function getAuthors()
    {
        return $this->hasMany(Author::class, ['id' => 'author_id'])
            ->viaTable('book_author', ['book_id' => 'id']);
    }

    /**
     * @throws Exception
     */
    public function syncAuthors(): void
    {
        self::getDb()->createCommand()
            ->delete('book_author', ['book_id' => $this->id])
            ->execute();

        if ($this->author_ids === []) {
            return;
        }

        $rows = [];

        foreach ($this->author_ids as $authorId) {
            $rows[] = [
                $this->id,
                $authorId,
            ];
        }

        self::getDb()->createCommand()
            ->batchInsert(
                'book_author',
                ['book_id', 'author_id'],
                $rows
            )
            ->execute();
    }

    public function behaviors(): array
    {
        return [
            TimestampBehavior::class,
        ];
    }
}