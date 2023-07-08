<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "usuario_institucion".
 *
 * @property int $id
 * @property int $id_usuario
 * @property int $id_institucion
 * @property float $total_ganado
 * @property string $cargo
 * @property string $tipo
 *
 * @property Institucion $institucion
 * @property Usuario $usuario
 */
class UsuarioInstitucion extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'usuario_institucion';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_usuario', 'id_institucion', 'total_ganado', 'cargo', 'tipo'], 'required'],
            [['id_usuario', 'id_institucion'], 'default', 'value' => null],
            [['id_usuario', 'id_institucion'], 'integer'],
            [['total_ganado'], 'number'],
            [['cargo'], 'string'],
            [['tipo'], 'string', 'max' => 1],
            [['id_institucion'], 'exist', 'skipOnError' => true, 'targetClass' => Institucion::class, 'targetAttribute' => ['id_institucion' => 'id_institucion']],
            [['id_usuario'], 'exist', 'skipOnError' => true, 'targetClass' => Usuario::class, 'targetAttribute' => ['id_usuario' => 'id_usuario']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_usuario' => 'Id Usuario',
            'id_institucion' => 'Id Institucion',
            'total_ganado' => 'Total Ganado',
            'cargo' => 'Cargo',
            'tipo' => 'Tipo',
        ];
    }

    /**
     * Gets query for [[Institucion]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getInstitucion()
    {
        return $this->hasOne(Institucion::class, ['id_institucion' => 'id_institucion']);
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
