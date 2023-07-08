<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "institucion".
 *
 * @property int $id_institucion
 * @property string $nombre
 *
 * @property DeclaracionJuradaInstitucion[] $declaracionJuradaInstitucions
 */
class Institucion extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'institucion';
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
            'id_institucion' => 'Id Institucion',
            'nombre' => 'Nombre',
        ];
    }

    /**
     * Gets query for [[DeclaracionJuradaInstitucions]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDeclaracionJuradaInstitucions()
    {
        return $this->hasMany(DeclaracionJuradaInstitucion::class, ['id_institucion' => 'id_institucion']);
    }
}
