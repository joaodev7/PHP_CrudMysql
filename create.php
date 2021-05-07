<?php
// Include config file
require_once "config.php";
 
// Define variables and initialize with empty values
$cliente = $solicitacao = $cpf_cnpj = "";
$cliente_err = $solicitacao_err = $cpf_cnpj_err = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
    // Validate name
    $input_cliente = trim($_POST["cliente"]);
    if(empty($input_cliente)){
        $cliente_err = "Please enter a name.";
    } elseif(!filter_var($input_cliente, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z\s]+$/")))){
        $cliente_err = "Please enter a valid name.";
    } else{
        $cliente = $input_cliente;
    }
    
    // Validate solicitacao
    $input_solicitacao = trim($_POST["solicitacao"]);
    if(empty($input_solicitacao)){
        $solicitacao_err = "Please enter an solicitacao.";     
    } else{
        $solicitacao = $input_solicitacao;
    }
    
    // Validate cpf/cnpj
    $input_cpf_cnpj = trim($_POST["cpf_cnpj"]);
    if(empty($input_cpf_cnpj)){
        $cpf_cnpj_err = "Please enter the cpf_cnpj amount.";     
    } elseif(!ctype_digit($input_cpf_cnpj)){
        $cpf_cnpj_err = "Please enter a positive integer value.";
    } else{
        $cpf_cnpj = $input_cpf_cnpj;
    }
    
    // Check input errors before inserting in database
    if(empty($name_err) && empty($solicitacao_err) && empty($cpf_cnpj_err)){
        // Prepare an insert statement
        $sql = "INSERT INTO MBA_ALOC (cliente, solicitacao, cpf_cnpj) VALUES (:cliente, :solicitacao, :cpf_cnpj)";
 
        if($stmt = $pdo->prepare($sql)){
            // Bind variables to the prepared statement as parameters
            $stmt->bindParam(":cliente", $param_cliente);
            $stmt->bindParam(":solicitacao", $param_solicitacao);
            $stmt->bindParam(":cpf_cnpj", $param_cpf_cnpj);
            
            // Set parameters
            $param_cliente = $cliente;
            $param_solicitacao = $solicitacao;
            $param_cpf_cnpj = $cpf_cnpj;
            
            // Attempt to execute the prepared statement
            if($stmt->execute()){
                // Records created successfully. Redirect to landing page
                header("location: index.php");
                exit();
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
        }
         
        // Close statement
        unset($stmt);
    }
    
    // Close connection
    unset($pdo);
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Record</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .wrapper{
            width: 600px;
            margin: 0 auto;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <h2 class="mt-5">Create Record</h2>
                    <p>Please fill this form and submit to add employee record to the database.</p>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="form-group">
                            <label>Cliente</label>
                            <input type="text" name="cliente" class="form-control <?php echo (!empty($cliente_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $cliente; ?>">
                            <span class="invalid-feedback"><?php echo $cliente_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>solicitacao</label>
                            <textarea name="solicitacao" class="form-control <?php echo (!empty($solicitacao_err)) ? 'is-invalid' : ''; ?>"><?php echo $solicitacao; ?></textarea>
                            <span class="invalid-feedback"><?php echo $solicitacao_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>cpf_cnpj</label>
                            <input type="text" name="cpf_cnpj" class="form-control <?php echo (!empty($cpf_cnpj_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $cpf_cnpj; ?>">
                            <span class="invalid-feedback"><?php echo $cpf_cnpj_err;?></span>
                        </div>
                        <input type="submit" class="btn btn-primary" value="Enviar">
                        <a href="index.php" class="btn btn-secondary ml-2">Cancelar</a>
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>