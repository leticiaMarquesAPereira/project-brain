<?php

require_once __DIR__ . '/Pessoa.php';

class PessoaDAO
{
  private $dbConn;

  public function __construct($dbConn)
  {
    $this->dbConn = $dbConn;
  }

  // CREATE
  public function inserir(Pessoa $pessoa)
  {
    try {
      $query = "INSERT INTO TESTE_LE (nome, CPF, recado, sexo, anexo, data_cad, tipo_usuario, senha) 
                 VALUES (:pNome, :pCPF, :pRecado, :pSexo, :pAnexo, SYSDATE, DEFAULT, :pSenha)";

      $stmt = $this->dbConn->prepare($query);

      $stmt->bindValue(':pNome', $pessoa->getNome());
      $stmt->bindValue(':pCPF', $pessoa->getCpf());
      $stmt->bindValue(':pRecado', $pessoa->getMsg());
      $stmt->bindValue(':pSexo', $pessoa->getSexo());
      $stmt->bindValue(':pAnexo', $pessoa->getAnexo());
      $stmt->bindValue(':pSenha', $pessoa->getSenha());

      $stmt->execute();

      // Agora recupera o ID gerado pela trigger
      $queryId = "SELECT seq_teste_le.CURRVAL FROM DUAL";
      $stmtId = $this->dbConn->query($queryId);
      $idGerado = $stmtId->fetchColumn();
      
      return $idGerado;

    } catch (PDOException $e) {
      error_log("Erro ao inserir pessoa: " . $e->getMessage());
      return false;
    }
  }
  // DELETE
  public function excluir($cpf)
  {
    try {
      $query = "DELETE FROM TESTE_LE WHERE CPF = :pCPF";
      $stmt = $this->dbConn->prepare($query);
      $stmt->bindValue(':pCPF', $cpf);
      $stmt->execute();
      return true;

    } catch (PDOException $e) {
      echo "Erro ao excluir pessoa: " . $e->getMessage();
      return false;
    }
  }

  // UPDATE
  public function atualizar(Pessoa $pessoa)
  {
    try {
      // Query para atualizar o nome e a mensagem com base no CPF
      $query = "UPDATE TESTE_LE SET nome = :pNome, recado = :pRecado WHERE CPF = :pCPF";
      $stmt = $this->dbConn->prepare($query);

      $stmt->bindValue(':pNome', $pessoa->getNome());
      $stmt->bindValue(':pRecado', $pessoa->getMsg());
      $stmt->bindValue(':pCPF', $pessoa->getCpf());

      $stmt->execute();
      return true;

    } catch (PDOException $e) {
      echo "Erro ao atualizar pessoa: " . $e->getMessage();
      return false;
    }
  }

  // EXIBIR
  public function exibir(Pessoa $pessoa)
  {
    try {
      $sql = "SELECT nome, recado, sexo, data_cad FROM TESTE_LE WHERE 1 = 1";
      $binds = [];

      if (!empty($pessoa->getNome())) {
        $sql .= " AND upper(nome) LIKE upper(:pNome)";
        $binds[':pNome'] = '%' . $pessoa->getNome() . '%';
      }
      if (!empty($pessoa->getSexo())) {
        $sql .= " AND sexo = :pSexo";
        $binds[':pSexo'] = $pessoa->getSexo();
      }
      if (!empty($pessoa->getMsg())) {
        $sql .= " AND upper(recado) LIKE upper(:pMsg)";
        $binds[':pMsg'] = '%' . $pessoa->getMsg() . '%';
      }
      if (!empty($pessoa->getData())) {
        $sql .= " AND TRUNC(data_cad) = TRUNC(TO_DATE(:pData, 'YYYY-MM-DD'))";
        $binds[':pData'] = $pessoa->getData();
      }

      $stmt = $this->dbConn->prepare($sql);

      foreach ($binds as $token => $value) {
        $stmt->bindValue($token, $value);
      }
      if ($stmt->execute() !== false) {
        return $stmt->fetchAll(PDO::FETCH_OBJ);
      }
      return [];
    } catch (PDOException $e) {
      echo "Erro ao exibir: " . $e->getMessage();
      return [];
    }
  }

  private function preencherObjeto($obj)
  {
    var_dump($obj);
    return (new Pessoa($obj->nome, $obj->sexo, $obj->recado, $obj->data_cad));
  }
}