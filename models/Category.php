<?php

namespace app\models;
use yii\db\ActiveRecord;

class Category extends ActiveRecord
{
    public static function tableName(): string
    {
        return 'category';
    }
    public function rules(): array
    {
        return [
            [['title','description'],'required'],
            [['title','description'],'string'],
        ];
    }

    public function attributeLabels(): array
    {
        return [
        'title' => 'Заголовок',
        'description' => 'Описание',
        ];
    }
}