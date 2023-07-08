<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "informacion_fechas".
 *
 * @property int $id_fecha
 * @property string $fecha
 * @property string $mes
 * @property string $gestion
 * @property int $fk_usuario
 *
 * @property Usuario $fkUsuario
 */
class InformacionFechas extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'informacion_fechas';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['fecha', 'mes', 'gestion', 'fk_usuario'], 'required'],
            [['fecha'], 'safe'],
            [['fk_usuario'], 'default', 'value' => null],
            [['fk_usuario'], 'integer'],
            [['mes'], 'string', 'max' => 20],
            [['gestion'], 'string', 'max' => 4],
            [['fk_usuario'], 'exist', 'skipOnError' => true, 'targetClass' => Usuario::class, 'targetAttribute' => ['fk_usuario' => 'id_usuario']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_fecha' => 'Id Fecha',
            'fecha' => 'Fecha',
            'mes' => 'Mes',
            'gestion' => 'Gestion',
            'fk_usuario' => 'Fk Usuario',
        ];
    }

    /**
     * Gets query for [[FkUsuario]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFkUsuario()
    {
        return $this->hasOne(Usuario::class, ['id_usuario' => 'fk_usuario']);
    }
}
