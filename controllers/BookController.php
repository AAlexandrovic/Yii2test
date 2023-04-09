<?php

namespace app\controllers;

use yii\db\Exception;
use yii\rest\ActiveController;
use app\models\Book;
use Yii;


/**
 * Контроллер всех api методов
 */
class BookController extends ActiveController
{
    /**
     * @var Book
     */
    public $modelClass = 'app\models\Book';

    /**
     * Прописал методы на всякий случай в роутах и здесь наверно излишне
     *
     * @return array[]
     */
    protected function verbs()
    {
        return [
            'setbooks' => ['GET'],
            'setbook' => ['GET'],
            'deletebook' => ['DELETE'],
            'addbok' => ['POST'],
            'updatebook' => ['PUT', 'PATCH'],
        ];
    }

    /**
     * Получаем постраничный список всех книг либо исключения
     *
     * @return array|string
     */
    public function actionSetbooks()
    {

        $books = $this->modelClass::find()->all();

        $request = Yii::$app->request->get('page');
        $request = (integer)$request;

        if (!$request || !is_numeric($request)) {
            return 'page not choose';
        }

        $chunk = count((new Book())->chunk($books));

        if ($request > $chunk) {
            return 'this page not found';
        }

        return [
            'books' => (new Book())->chunk($books)[$request - 1]
        ];
    }

    /**
     * Получаем инофрмацию о книге по id через запрос
     *
     * @return Book|null
     */
    public function actionSetbook()
    {
        $test = Yii::$app->urlManager->parseRequest(Yii::$app->request);

        return $this->modelClass::findOne($test[1]['id']);
    }

    /**
     * Удаляет книгу по id
     *
     * @return string
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionDeletebook()
    {
        $test = Yii::$app->urlManager->parseRequest(Yii::$app->request);

        $book = $this->modelClass::findOne($test[1]['id']);

        $book->delete();
        return 'success';

    }

    /**
     * Добавляем новую книгу все поля обязательны для заполнения поля title, author, genre
     *
     * @return Book|true[]
     */
    public function actionAddbook()
    {
        $books = new Book();
        $books->load(Yii::$app->request->bodyParams, '');

        if ($books->send()) {
            return [
                'success' => true,
            ];
        } else {
            return $books;
        }
    }

    /**
     * Обновляем одно или несколько полей книги здесь непохо было бы прописать какой-то вывод с добавленными полями, но в случае неудачи ошибка
     * показывается, а в случае успеха возвращается 1, что в общем не так и плохо:)
     *
     * @throws Exception
     */
    public function actionUpdatebook()
    {
        $test = Yii::$app->urlManager->parseRequest(Yii::$app->request);

        $params = Yii::$app->request->bodyParams;

        foreach ($params as $key => $value) {
            $sql = Yii::$app->db->createCommand("Update books SET " . $key . "='" . $value . "' WHERE id=" . $test[1]['id'])->execute();
        }
        return $sql;
    }
}
