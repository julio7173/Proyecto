<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "declaracion_jurada".
 *
 * @property int $id_declaracion_jurada
 * @property string $fecha
 * @property string $mes
 * @property string $gestion
 * @property int $fk_usuario
 * @property string $estado 'PEN' = pendiente; 'DEC'=declarado
 *
 * @property DeclaracionJuradaCategoria[] $declaracionJuradaCategorias
 * @property DeclaracionJuradaInstitucion[] $declaracionJuradaInstitucions
 * @property Usuario $usuario
 */
class DeclaracionJurada extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'declaracion_jurada';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['fecha', 'mes', 'gestion', 'fk_usuario', 'estado'], 'required'],
            [['fecha'], 'safe'],
            [['fk_usuario'], 'default', 'value' => null],
            [['fk_usuario'], 'integer'],
            [['mes'], 'string', 'max' => 20],
            [['gestion'], 'string', 'max' => 4],
            [['estado'], 'string', 'max' => 3],
            [['fk_usuario'], 'exist', 'skipOnError' => true, 'targetClass' => Usuario::class, 'targetAttribute' => ['fk_usuario' => 'id_usuario']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_declaracion_jurada' => 'Id Declaracion Jurada',
            'fecha' => 'Fecha',
            'mes' => 'Mes',
            'gestion' => 'Gestion',
            'fk_usuario' => 'Fk Usuario',
            'estado' => 'Estado',
        ];
    }

    /**
     * Gets query for [[DeclaracionJuradaCategorias]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDeclaracionJuradaCategorias()
    {
        return $this->hasMany(DeclaracionJuradaCategoria::class, ['id_declaracion_jurada' => 'id_declaracion_jurada']);
    }

    /**
     * Gets query for [[DeclaracionJuradaInstitucions]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDeclaracionJuradaInstitucions()
    {
        return $this->hasMany(DeclaracionJuradaInstitucion::class, ['id_declaracion_jurada' => 'id_declaracion_jurada']);
    }

    /**
     * Gets query for [[FkUsuario]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUsuario()
    {
        return $this->hasOne(Usuario::class, ['id_usuario' => 'fk_usuario']);
    }

    /**
     * Aumentado manualmente
     * @return \yii\db\ActiveQuery
     */
    public function getCategorias()
    {
        return $this->hasMany(Categoria::class, ['id_categoria' => 'id_categoria'])
            ->viaTable('declaracion_jurada_categoria', ['id_declaracion_jurada' => 'id_declaracion_jurada']);
    }

}
