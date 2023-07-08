<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "declaracion_jurada_institucion".
 *
 * @property int $id
 * @property int $id_declaracion_jurada
 * @property int $id_institucion
 * @property float $total_ganado
 * @property string $cargo
 * @property string $tipo
 *
 * @property DeclaracionJurada $declaracionJurada
 * @property Institucion $institucion
 */
class DeclaracionJuradaInstitucion extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'declaracion_jurada_institucion';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_declaracion_jurada', 'id_institucion', 'total_ganado', 'cargo', 'tipo'], 'required'],
            [['id_declaracion_jurada', 'id_institucion'], 'default', 'value' => null],
            [['id_declaracion_jurada', 'id_institucion'], 'integer'],
            [['total_ganado'], 'number'],
            [['cargo'], 'string'],
            [['tipo'], 'string', 'max' => 1],
            [['id_declaracion_jurada'], 'exist', 'skipOnError' => true, 'targetClass' => DeclaracionJurada::class, 'targetAttribute' => ['id_declaracion_jurada' => 'id_declaracion_jurada']],
            [['id_institucion'], 'exist', 'skipOnError' => true, 'targetClass' => Institucion::class, 'targetAttribute' => ['id_institucion' => 'id_institucion']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_declaracion_jurada' => 'Id Declaracion Jurada',
            'id_institucion' => 'Id Institucion',
            'total_ganado' => 'Total Ganado',
            'cargo' => 'Cargo',
            'tipo' => 'Tipo',
        ];
    }

    /**
     * Gets query for [[DeclaracionJurada]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDeclaracionJurada()
    {
        return $this->hasOne(DeclaracionJurada::class, ['id_declaracion_jurada' => 'id_declaracion_jurada']);
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
}
