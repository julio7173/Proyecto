<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "usuario".
 *
 * @property int $id_usuario
 * @property string|null $paterno
 * @property string|null $materno
 * @property string|null $nombre1
 * @property string|null $nombre2
 * @property string|null $email
 * @property string|null $ci
 * @property string $password
 * @property string $token
 *
 * @property DeclaracionJurada[] $declaracionJuradas
 */
class Usuario extends \yii\db\ActiveRecord implements \yii\web\IdentityInterface
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'usuario';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['paterno', 'materno', 'nombre1', 'nombre2', 'email', 'ci', 'password', 'token'], 'string'],
            [['password', 'token'], 'required'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_usuario' => 'Id Usuario',
            'paterno' => 'Paterno',
            'materno' => 'Materno',
            'nombre1' => 'Nombre1',
            'nombre2' => 'Nombre2',
            'email' => 'Email',
            'ci' => 'Ci',
            'password' => 'Password',
            'token' => 'Token',
        ];
    }

    /**
     * Gets query for [[DeclaracionJuradas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDeclaracionJuradas()
    {
        return $this->hasMany(DeclaracionJurada::class, ['fk_usuario' => 'id_usuario']);
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id)
    {
       // return isset(self::$users[$id]) ? new static(self::$users[$id]) : null;
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        $user = Usuario::findOne(['token' => $token]);
        if ($user) {
            // Evita mostrar el token de usuario
            $user->token = null;
            // Almacena el usuario en Yii::$app->user->identity
            return new static($user);
        }
        return null; // Almacena null en Yii::$app->user->identity

    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        /*foreach (self::$users as $user) {
            if (strcasecmp($user['username'], $username) === 0) {
                return new static($user);
            }
        }

        return null;*/
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->id_usuario;
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey()
    {
        return $this->authKey;
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey)
    {
        return $this->authKey === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return $this->password === $password;
    }
}
