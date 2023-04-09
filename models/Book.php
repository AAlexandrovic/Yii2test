<?php

namespace app\models;

use yii\db\ActiveRecord;

class Book extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName(): string
    {
        return 'books';
    }

    /**
     * @inheritdoc
     */
    public function rules(): array
    {
        return [
            [['title', 'author', 'genre'], 'required'],
            [['title', 'author', 'genre'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'title' => 'Название книги',
            'author' => 'Автор книги',
            'genre' => 'Жанр',
        ];
    }

    /**
     * Принимаем массив и разбиваем его на части
     *
     * @param array $array
     * @return array
     */
    public function chunk(array $array): array
    {
        return array_chunk($array, 2);
    }

    /**
     * Производим валидацию полученный данных и если всё ок, то сохраняем их в таблицу
     *
     * @return bool|null
     */
    public function send(): ?bool
    {
        if (!$this->validate()) {
            return null;
        }
        return $this->save();
    }
}
