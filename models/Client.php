<?php

namespace app\models;

use yii\db\ActiveRecord;

class Client extends ActiveRecord
{
    public static function tableName()
    {
        return 'client';
    }

    public function rules()
    {
        return [
            [['name', 'cpf', 'gender'], 'required'],
            [['cep', 'street', 'number', 'city', 'state', 'complement', 'photo'], 'safe'],
            ['cpf', 'validateCpf'],
            ['cpf', 'unique', 'targetClass' => '\app\models\Client', 'message' => 'Este CPF já está cadastrado.'],
            ['gender', 'in', 'range' => ['M', 'F']],
        ];
    }

    public function validateCpf($attribute, $params)
    {   
        if (!self::is_valid_cpf($this->$attribute)) {
            $this->addError($attribute, 'CPF inválido.');
        }
    }

    public static function is_valid_cpf($cpf)
    {
        $cpf = preg_replace( '/[^0-9]/is', '', $cpf );
        
        if (strlen($cpf) != 11) {
            return false;
        }

        if (preg_match('/(\d)\1{10}/', $cpf)) {
            return false;
        }

        for ($t = 9; $t < 11; $t++) {
            for ($d = 0, $c = 0; $c < $t; $c++) {
                $d += $cpf[$c] * (($t + 1) - $c);
            }
            $d = ((10 * $d) % 11) % 10;
            if ($cpf[$c] != $d) {
                return false;
            }
        }

        return true;
    }
}
