<?php

namespace app\components;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JwtComponent
{
    public $key;
    public $alg;

    public function __construct($key = 'VkeRNx6RMCa8fugIOQ3cTOBxYve6026di0MrlD6T1ko', $alg = 'HS256')
    {
        $this->key = $key;
        $this->alg = $alg;
    }

    /**
     * Cria um token JWT com dados e tempo de expiração especificados.
     *
     * @param array $data Dados a serem incluídos no payload do token.
     * @param int $expTime Tempo de validade do token em segundos.
     * @return string Token JWT codificado.
     */
    public function createToken($data, $expTime = 3600)
    {
        $time = time();
        $token = [
            'iss' => 'http://localhost:8000',
            'aud' => 'http://localhost:8000',
            'iat' => $time,
            'exp' => $time + $expTime,
            'data' => $data
        ];

        return JWT::encode($token, $this->key, $this->alg);
    }

    /**
     * Decodifica um token JWT.
     *
     * @param string $token Token JWT a ser decodificado.
     * @return object|null O objeto de payload decodificado ou null em caso de falha.
     */
    public function decodeToken($token)
    {
        try {
            return JWT::decode($token, new Key($this->key, $this->alg));
        } catch (\Exception $e) {
            return null;
        }
    }
}
