<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "usuario_categoria".
 *
 * @property int $id_usuario
 * @property int $id_categoria
 * @property int $id
 * @property float $remuneracion
 *
 * @property Categoria $categoria
 * @property Usuario $usuario
 */
class UsuarioCategoria extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'usuario_categoria';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_usuario', 'id_categoria', 'remuneracion'], 'required'],
            [['id_usuario', 'id_categoria'], 'default', 'value' => null],
            [['id_usuario', 'id_categoria'], 'integer'],
            [['remuneracion'], 'number'],
            [['id_categoria'], 'exist', 'skipOnError' => true, 'targetClass' => Categoria::class, 'targetAttribute' => ['id_categoria' => 'id_categoria']],
            [['id_usuario'], 'exist', 'skipOnError' => true, 'targetClass' => Usuario::class, 'targetAttribute' => ['id_usuario' => 'id_usuario']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_usuario' => 'Id Usuario',
            'id_categoria' => 'Id Categoria',
            'id' => 'ID',
            'remuneracion' => 'Remuneracion',
        ];
    }

    /**
     * Gets query for [[Categoria]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCategoria()
    {
        return $this->hasOne(Categoria::class, ['id_categoria' => 'id_categoria']);
    }

    /**
     * Gets query for [[Usuario]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUsuario()
    {
        return $this->hasOne(Usuario::class, ['id_usuario' => 'id_usuario']);
    }
}
