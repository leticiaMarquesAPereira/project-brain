<?php

//Classe da tabela Teste_Le
class Pessoa {
  private $nome;
  private $cpf;
  private $msg;
  private $sexo;
  private $anexo;
  private $data;
  private $tipo_usuario;
  private $senhaHash;
  public $arrSex = array('f' => 'Feminino', 'm' => 'Masculino', 'n' => 'Prefiro nao dizer');

  function __construct($nome, $cpf, $anexo, $sexo, $msg, $data, $tipo_usuario, $senhaHash) {
    $this->nome = $nome;
    $this->cpf = $cpf;
    $this->msg = $msg;
    $this->sexo = $sexo;
    $this->anexo = $anexo;
    $this->data = $data;
    $this->tipo_usuario = $tipo_usuario;
    $this->senhaHash = $senhaHash;
  }

  public function getNome()
  {
    return $this->nome;
  }

  public function getCpf()
  {
    return $this->cpf;
  }

  public function getSexo()
  {
    return $this->sexo;
  }

  public function getMsg()
  {
    return $this->msg;
  }
  public function getAnexo()
  {
    return $this->anexo;
  }
  public function getData()
  {
    return $this->data;
  }
  public function getTipoUsuario()
  {
    return $this->tipo_usuario;
  }
  public function getSenha()
  {
    return $this->senhaHash;
  }
  public function setNome($nome)
  {
    $this->nome = $nome;
  }

  public function setCpf($cpf)
  {
    $this->cpf = $cpf;
  }

  public function setSexo($sexo)
  {
    $this->sexo = $sexo;
  }

  public function setMsg($msg)
  {
    $this->msg = $msg;
  }
  public function setAnexo($anexo)
  {
    $this->anexo = $anexo;
  }
  public function setData($data)
  {
    $this->data = $data;
  }
  public function setSenha($senha)
  {
    $this->senha = $senha;
  }
  

  // VERIFICA SE CPF EXISTE
  function cpfExiste($cpf, $dbConn) {
    try {
      // Query para buscar o CPF no banco de dados
      $query = "SELECT COUNT(*) FROM TESTE_LE WHERE CPF = :pCPF";
      $stmt = $dbConn->prepare($query);
      $stmt->bindValue(':pCPF', $cpf);
      $stmt->execute();

      // Retorna true se o CPF já existir
      return $stmt->fetchColumn() > 0;

    } catch (PDOException $e) {
      echo "Erro ao verificar CPF: " . $e->getMessage();
      return false;
    }
  }
}