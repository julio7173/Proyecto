<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "declaracion_jurada_categoria".
 *
 * @property int $id
 * @property int $id_declaracion_jurada
 * @property int $id_categoria
 * @property float $remuneracion
 *
 * @property Categoria $categoria
 * @property DeclaracionJurada $declaracionJurada
 */
class DeclaracionJuradaCategoria extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'declaracion_jurada_categoria';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_declaracion_jurada', 'id_categoria', 'remuneracion'], 'required'],
            [['id_declaracion_jurada', 'id_categoria'], 'default', 'value' => null],
            [['id_declaracion_jurada', 'id_categoria'], 'integer'],
            [['remuneracion'], 'number'],
            [['id_categoria'], 'exist', 'skipOnError' => true, 'targetClass' => Categoria::class, 'targetAttribute' => ['id_categoria' => 'id_categoria']],
            [['id_declaracion_jurada'], 'exist', 'skipOnError' => true, 'targetClass' => DeclaracionJurada::class, 'targetAttribute' => ['id_declaracion_jurada' => 'id_declaracion_jurada']],
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
            'id_categoria' => 'Id Categoria',
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
     * Gets query for [[DeclaracionJurada]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDeclaracionJurada()
    {
        return $this->hasOne(DeclaracionJurada::class, ['id_declaracion_jurada' => 'id_declaracion_jurada']);
    }
}
