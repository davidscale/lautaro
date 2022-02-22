<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Administrativos;

/**
 * Administrativo represents the model behind the search form of `app\models\Administrativos`.
 */
class Administrativo extends Administrativos
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['Id', 'Tipo_Documento', 'Fecha_nacimiento', 'Altura', 'Pais', 'Provincia', 'Localidad', 'Fecha_de_baja'], 'integer'],
            [['Apellido', 'Nombres', 'Nro_Documento', 'Sexo', 'Nacionalidad', 'Email', 'Telefono', 'Celular', 'Calle', 'Piso', 'Dpto.', 'Estado', 'Motivo_de_baja'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Administrativos::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'Id' => $this->Id,
            'Tipo_Documento' => $this->Tipo_Documento,
            'Fecha_nacimiento' => $this->Fecha_nacimiento,
            'Altura' => $this->Altura,
            'Pais' => $this->Pais,
            'Provincia' => $this->Provincia,
            'Localidad' => $this->Localidad,
            'Fecha_de_baja' => $this->Fecha_de_baja,
        ]);

        $query->andFilterWhere(['ilike', 'Apellido', $this->Apellido])
            ->andFilterWhere(['ilike', 'Nombres', $this->Nombres])
            ->andFilterWhere(['ilike', 'Nro_Documento', $this->Nro_Documento])
            ->andFilterWhere(['ilike', 'Sexo', $this->Sexo])
            ->andFilterWhere(['ilike', 'Nacionalidad', $this->Nacionalidad])
            ->andFilterWhere(['ilike', 'Email', $this->Email])
            ->andFilterWhere(['ilike', 'Telefono', $this->Telefono])
            ->andFilterWhere(['ilike', 'Celular', $this->Celular])
            ->andFilterWhere(['ilike', 'Calle', $this->Calle])
            ->andFilterWhere(['ilike', 'Piso', $this->Piso])
            ->andFilterWhere(['ilike', 'Dpto.', $this->Dpto.])
            ->andFilterWhere(['ilike', 'Estado', $this->Estado])
            ->andFilterWhere(['ilike', 'Motivo_de_baja', $this->Motivo_de_baja]);

        return $dataProvider;
    }
    public function beforeSave($insert) {

    // unix timestamp

    $time = strtotime($model->admission_date);


    // if you want a specific format

    $time = date("Y-m-d", strtotime($model->admission_date));


    // any other custom validations you need for your date time

    // e.g. isTheTimeOk($time);

    if (isTheTimeOk($time)) {

        $model->admission_date = $time;

        return parent::beforeSave($insert);

    }

    else {

        return false;

    }

}
}
