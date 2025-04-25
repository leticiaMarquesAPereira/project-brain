<?php
require_once __DIR__ . '/Login.php';
require_once __DIR__ . '/Pessoa.php';

class LoginDAO
{
  private $dbConn;

  public function __construct($dbConn)
  {
    $this->dbConn = $dbConn;
  }

  public function Logar(Pessoa $pessoa)
  {
    try {
      $cpfBusca = preg_replace('/[^0-9]/', '', $pessoa->getCpf());

      $query = "SELECT le.*, perfil.foto_perfil AS foto_perfil 
      FROM TESTE_LE le LEFT JOIN teste_perfil perfil ON le.cpf = perfil.cpf WHERE le.cpf = :pCPF";

      $query = $this->dbConn->prepare($query);
      $query->bindValue(':pCPF', $cpfBusca, PDO::PARAM_STR);

      if (!$query->execute()) {
        return false;
      }

      $resultados = $query->fetchAll(PDO::FETCH_ASSOC);

      if (count($resultados) === 1) {
        $usuario = $resultados[0];

        if (password_verify($pessoa->getSenha(), $usuario['senha'])) {
          return $usuario;
        }
      }

      return false;

    } catch (PDOException $e) {
      return false;
    }
  }
  public function registrarAcesso($cpfCadastro)
  {
    try {
      $sql = "SELECT 1 FROM teste_perfil WHERE TO_CHAR(CPF) = :pCPF";
      $stmt = $this->dbConn->prepare($sql);
      $stmt->bindValue(':pCPF', $cpfCadastro);
      $stmt->execute();

      $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
      $quantidade = count($resultado);
      error_log("Quantidade de registros encontrados: $quantidade");

      if (count($resultado) === 0) {
        // Se não existir, cria novo registro
        $insert = $this->dbConn->prepare(
          "INSERT INTO teste_perfil (ultimo_acesso, CPF)
           VALUES (SYSDATE, :pCPF)"
        );
        $insert->bindValue(':pCPF', $cpfCadastro);
        if (!$insert->execute()) {
          error_log("Erro ao inserir acesso: " . implode(" ", $insert->errorInfo()));
          return false;
        }
      } else {
        // Se existir, atualiza a data
        $update = $this->dbConn->prepare(
          "UPDATE teste_perfil 
           SET ultimo_acesso = SYSDATE 
           WHERE CPF = :pCPF"
        );
        $update->bindValue(':pCPF', $cpfCadastro);
        if (!$update->execute()) {
          error_log("Erro ao atualizar acesso: " . implode(" ", $update->errorInfo()));
          return false;
        }
      }

      return true;

    } catch (PDOException $e) {
      error_log("Erro ao registrar acesso: " . $e->getMessage());
      return false;
    }
  }
  public function buscarDadosUsuario($cpf)
  {
    try {
      $cpfBusca = preg_replace('/[^0-9]/', '', $cpf);

      $query = " SELECT le.nome, le.cpf, le.sexo, le.recado, perfil.foto AS foto_perfil FROM TESTE_LE le
      LEFT JOIN teste_perfil perfil ON TO_CHAR(le.cpf) = TO_CHAR(perfil.cpf) WHERE TO_CHAR(le.cpf) = :pCPF";

      $stmt = $this->dbConn->prepare($query);
      $stmt->bindValue(':pCPF', $cpfBusca, PDO::PARAM_STR);
      $stmt->execute();

      return $stmt->fetch(PDO::FETCH_ASSOC);

    } catch (PDOException $e) {
      error_log("Erro ao buscar dados do usuário: " . $e->getMessage());
      return false;
    }
  }
  public function atualizarPerfilUsuario($cpf, $nome, $msg, $fotoPerfil = null)
  {
    try {
      $this->dbConn->beginTransaction();
  
      // Atualiza nome e recado
      $query1 = "UPDATE TESTE_LE SET nome = :pNome, recado = :pMsg WHERE CPF = :pCpf";
      $stmt1 = $this->dbConn->prepare($query1);
      $stmt1->bindValue(':pNome', $nome);
      $stmt1->bindValue(':pMsg', $msg);
      $stmt1->bindValue(':pCpf', $cpf);
      $stmt1->execute();
  
      // Atualiza imagem se tiver
      if ($fotoPerfil !== null) {
        $query2 = "UPDATE TESTE_PERFIL SET foto_perfil = :pFoto WHERE CPF = :pCpf";
        $stmt2 = $this->dbConn->prepare($query2);
        $stmt2->bindValue(':pFoto', $fotoPerfil);
        $stmt2->bindValue(':pCpf', $cpf);
        $stmt2->execute();
      }
  
      $this->dbConn->commit();
      return true;
  
    } catch (PDOException $e) {
      $this->dbConn->rollBack();
      echo "Erro ao atualizar perfil do usuário: " . $e->getMessage();
      return false;
    }
  }

}