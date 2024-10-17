<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Analisador Léxico</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        textarea { width: 100%; height: 200px; }
        button { margin-top: 10px; }
        pre { background-color: #f4f4f4; padding: 10px; border: 1px solid #ccc; }
    </style>
</head>
<body>

<h1>Analisador Léxico</h1>
<form method="post">
    <label for="sourceCode">Digite o código fonte:</label>
    <textarea name="sourceCode" id="sourceCode"></textarea>
    <button type="submit">Analisar</button>
</form>

<?php 
    include("analisadores/analisadorLexico.php");
    include("analisadores/analisadorSintatico.php");
    $sourceCode = $_POST['sourceCode'] ?? '';
    $analisador = new AnalisadorLexico();

    try {
        // 1. Realiza a análise léxica
        $resultado = $analisador->lexer($sourceCode);
        $tokens = $resultado['tokens'];
        $analisador->tokens = [];
        $errosLexicos = $resultado['erros'];

        // 2. Exibe os tokens encontrados
        echo "<h2>Tokens Encontrados:</h2><pre>";
        foreach ($tokens as $token) {
        array_push($analisador->tokens, $token[2] ? $token[2] : $token[0]);
            echo ($token[2] ? "{$token[2]}" : $token[0]) . ": {$token[1]}\n";
        }
        echo "</pre>";

        // 3. Exibe erros léxicos, se houver
        if (!empty($errosLexicos)) {
            echo "<h2>Erros Léxicos Encontrados:</h2><pre>";
            foreach ($errosLexicos as $erro) {
                echo "$erro\n";
            }
            echo "</pre>";
        } else {
            $analisadorDR = new AnalisadorDR($analisador);
            if ($analisadorDR->Programa()) {
                echo "<h2>Análise Sintática: Sucesso</h2>";
            } else {
                echo "<h2>Análise Sintática: Falha</h2>";
                $errosSintaticos = $analisadorDR->getErros();
                if (!empty($errosSintaticos)) {
                    echo "<h3>Erros Sintáticos:</h3><pre>";
                    foreach ($errosSintaticos as $erro) {
                        echo "$erro\n";
                    }
                    echo "</pre>";
                }
            }
        }
    } catch (Exception $e) {
        echo "Erro: " . $e->getMessage();
    }
?>

</body>
</html>