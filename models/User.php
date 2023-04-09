<?php

namespace app\models;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
use Yii;

class User extends ActiveRecord implements IdentityInterface
{

    public $username;
     const Status = 1;

    public static function tableName(): string
    {
        return 'user';
    }


    public function rules(): array
    {
        return [
            [['email', 'password'], 'required', 'on' => 'registration'],
            [['Admin'], 'integer'],
            [['email', 'password'], 'string', 'max' => 255],

            [['id', 'email'], 'required', 'on' => 'users'],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'email' => 'Email',
            'password' => 'Пароль',
            'Admin' => 'администратор',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id): User|IdentityInterface|null
    {
        return static::findOne($id);
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        foreach (self::$users as $user) {
            if ($user['accessToken'] === $token) {
                return new static($user);
            }
        }

        return null;
    }

    /**
     * @param string $email
     * @return static
     */
    public function findByUsername(string $email)
    {
        if(static::findOne(['email' => $email]))
        {
            return static::findOne(['email' => $email]);
        } else {
            return null;
        }
        //return static::findOne(['email' => $email]);
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey()
    {
//        return $this->authKey;
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey)
    {
       // return $this->authKey === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password);
    }

}
