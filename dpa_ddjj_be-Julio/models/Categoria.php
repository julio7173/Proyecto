<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "categoria".
 *
 * @property int $id_categoria
 * @property string $nombre
 *
 * @property DeclaracionJuradaCategoria[] $declaracionJuradaCategorias
 */
class Categoria extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'categoria';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nombre'], 'required'],
            [['nombre'], 'string'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_categoria' => 'Id Categoria',
            'nombre' => 'Nombre',
        ];
    }

    /**
     * Gets query for [[DeclaracionJuradaCategorias]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDeclaracionJuradaCategorias()
    {
        return $this->hasMany(DeclaracionJuradaCategoria::class, ['id_categoria' => 'id_categoria']);
    }
}
