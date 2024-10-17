<?php
include("automato/automato.php");

class AnalisadorLexico {
    
    public $tokens;

    function criaAutomatos() {

        // Autômato para tipos de dados
        $tipos = new Automato(
            'q0',
            ['q1'],
            [
                'q0' => [
                    'i' => 'q2',  // int
                    'c' => 'q5',  // char
                    'f' => 'q8',  // float
                    'a' => 'q12'  // array
                ],
                'q2' => ['n' => 'q3'],
                'q3' => ['t' => 'q1'], // Reconhece "int"
                
                'q5' => ['h' => 'q6'],
                'q6' => ['a' => 'q7'],
                'q7' => ['r' => 'q1'], // Reconhece "char"
                
                'q8' => ['l' => 'q9'],
                'q9' => ['o' => 'q10'],
                'q10' => ['a' => 'q11'],
                'q11' => ['t' => 'q1'], // Reconhece "float"
                
                'q12' => ['r' => 'q13'],
                'q13' => ['r' => 'q14'],
                'q14' => ['a' => 'q15'],
                'q15' => ['y' => 'q1']  // Reconhece "array"
            ]
        );

        // Autômato para palavras reservadas
        $palavrasReservadas = new Automato(
            'q0',
            ['q2', 'q25', 'q27', 'q51'],
            [
                'q0' => [
                    's' => 'q1', 'f' => 'q3', 'e' => 'q6', 'v' => 'q7',
                    'i' => 'q8', 'l' => 'q11','p' => 'q62','r' => 'q80', 'S' => 'q26', 'F' => 'q28', 'E' => 'q31',
                    'V' => 'q38', 'I' => 'q40', 'L' => 'q46', 'P' => 'q65',
                ],
                'q1' => ['e' => 'q2'],
                'q2' => ['n' => 'q23'], 
                'q3' => ['a' => 'q4'], 
                'q4' => ['c' => 'q5'],
                'q5' => ['a' => 'q2'], 
                'q6' => ['n' => 'q9', 's' => 'q52'],
                'q7' => ['a' => 'q17'], 
                'q8' => ['m' => 'q13'],
                'q9' => ['q' => 'q10'],
                'q10' => ['u' => 'q19'], 
                'q11' => ['e' => 'q12'], 
                'q12' => ['i' => 'q2'], 
                'q13' => ['p' => 'q14'],
                'q14' => ['r' => 'q15'],
                'q15' => ['i' => 'q16'],
                'q16' => ['m' => 'q18'],
                'q17' => ['r' => 'q2'],
                'q18' => ['a' => 'q2'],
                'q19' => ['a' => 'q20'],
                'q20' => ['n' => 'q21'],
                'q21' => ['t' => 'q22'],
                'q22' => ['o' => 'q2'], 
                'q23' => ['a' => 'q24'],
                'q24' => ['o' => 'q25'],
                'q26' => ['E' => 'q27'],
                'q27' => ['N' => 'q49'],
                'q28' => ['A' => 'q29'],
                'q29' => ['C' => 'q30'],
                'q30' => ['A' => 'q27'],
                'q31' => ['N' => 'q32', 'S' => 'q57'],
                'q32' => ['Q' => 'q33'],
                'q33' => ['U' => 'q34'],
                'q34' => ['A' => 'q35'],
                'q35' => ['N' => 'q36'],
                'q36' => ['T' => 'q37'],
                'q37' => ['O' => 'q27'],
                'q38' => ['A' => 'q39'],
                'q39' => ['R' => 'q27'],
                'q40' => ['M' => 'q41'],
                'q41' => ['P' => 'q42'],
                'q42' => ['R' => 'q43'],
                'q43' => ['I' => 'q44'],
                'q44' => ['M' => 'q45'],
                'q45' => ['A' => 'q27'],
                'q46' => ['E' => 'q47'],
                'q47' => ['I' => 'q48'],
                'q48' => ['A' => 'q27'],
                'q49' => ['A' => 'q50'],
                'q50' => ['O' => 'q51'],
                'q52' => ['c' => 'q53'],
                'q53' => ['r' => 'q54'],
                'q54' => ['e' => 'q55'],
                'q55' => ['v' => 'q56'],
                'q56' => ['a' => 'q2'],
                'q57' => ['C' => 'q58'],
                'q58' => ['R' => 'q59'],
                'q59' => ['E' => 'q60'],
                'q60' => ['V' => 'q61'],
                'q61' => ['A' => 'q27'],
                'q62' => ['a' => 'q63', 'r' => 'q68'],
                'q63' => ['r' => 'q64'],
                'q64' => ['a' => 'q2'],
                'q65' => ['A' => 'q66', 'R' => 'q74'],
                'q66' => ['R' => 'q67'],
                'q67' => ['A' => 'q2'],
                'q68' => ['o' => 'q69'],
                'q69' => ['g' => 'q70'],
                'q70' => ['r' => 'q71'],
                'q71' => ['a' => 'q72'],
                'q72' => ['m' => 'q73'],
                'q73' => ['a' => 'q2'],
                'q74' => ['O' => 'q75'],
                'q75' => ['G' => 'q76'],
                'q76' => ['R' => 'q77'],
                'q77' => ['A' => 'q78'],
                'q78' => ['M' => 'q79'],
                'q79' => ['A' => 'q2'],
                'q80' => ['e' => 'q81'],
                'q81' => ['t' => 'q82'],
                'q82' => ['o' => 'q83'],
                'q83' => ['r' => 'q84'],
                'q84' => ['n' => 'q85'],
                'q85' => ['o' => 'q2'],

            ]
        );

        // Autômato para identificadores
        $identificador = new Automato(
            'q0',
            ['q1'],
            [
                'q0' => array_merge(array_fill_keys(range('a', 'z'), 'q1'), array_fill_keys(range('A', 'Z'), 'q1')),
                'q1' => array_merge(array_fill_keys(range('a', 'z'), 'q1'), array_fill_keys(range('A', 'Z'), 'q1'), array_fill_keys(range('0', '9'), 'q1')),
            ]
        );

        // Autômato para constantes
        $constante = new Automato(
            'q0',
            ['q1'],
            [
                'q0' => array_fill_keys(range('0', '9'), 'q1'),
                'q1' => array_fill_keys(range('0', '9'), 'q1'),
            ]
        );

        // Autômato para operadores
        $operadores = new Automato(
            'q0',
            ['q1', 'q3', 'q120'],
            [
                'q0' => [
                    '==' => 'q120', '=' => 'q120', '+' => 'q1', '-' => 'q1', '*' => 'q1', '/' => 'q1', 
                    '(' => 'q1', ')' => 'q1', '[' => 'q1', ']' => 'q1', '{' => 'q1', '}' => 'q1', 
                    '.' => 'q1', ',' => 'q1', ';' => 'q1', '!' => 'q120', '"' => 'q1', "'" => 'q1',
                    ':' => 'q1', '<' => 'q120', '>' => 'q120', '!=' => 'q120', '<=' => 'q120', '>=' => 'q120'
                ],
                'q120' => ['=' => 'q1']
            ]
        );

        

        return [
            'PALAVRARESERVADA' => $palavrasReservadas,
            'IDENTIFICADOR' => $identificador,
            'CONSTANTE' => $constante,
            'OPERADOR' => $operadores,
            'TIPO' => $tipos
        ];
    }

    function lexer($sourceCode) {
        $tokens = [];
        $automatos = $this->criaAutomatos();
        $length = strlen($sourceCode);
        $i = 0;
        $erros = [];
        
        $linha = 1;
        $coluna = 1;

        // Mapas para descrições
        $operadorDescricao = [
            '(' => 'ABRE_PARENTESES',
            ')' => 'FECHA_PARENTESES',
            '{' => 'ABRE_CHAVES',
            '}' => 'FECHA_CHAVES',
            '[' => 'ABRE_COLCHETE',
            ']' => 'FECHA_COLCHETE',
            '+' => 'SOMA',
            '-' => 'SUBTRACAO',
            '*' => 'MULTIPLICACAO',
            '/' => 'DIVISAO',
            '%' => 'MODULO',
            '=' => 'ATRIBUICAO',
            '==' => 'IGUAL',
            '!=' => 'DIFERENTE',
            '<' => 'MENOR_QUE',
            '>' => 'MAIOR_QUE',
            '<=' => 'MENOR_OU_IGUAL',
            '>=' => 'MAIOR_OU_IGUAL',
            '!' => 'NEGACAO',
            '.' => 'PONTO',
            ',' => 'VIRGULA',
            ';' => 'PONTO_E_VIRGULA',
            ':' => 'DOIS_PONTOS',
            '"' => 'ASPAS_DUPLAS',
            "'" => 'ASPAS_SIMPLES',
        ];
    
        // Definição das descrições das palavras reservadas
        $palavraReservadaDescricao = [
            'var' => 'VARIABLE',
            'se' => 'IF',
            'senao' => 'ELSE',
            'enquanto' => 'WHILE',
            'para' => 'FOR',
            'faca' => 'DO',
            'imprima' => 'PRINT',
            'leia' => 'READ',
            'escreva' => 'WRITE',
            'programa' => 'PROGRAM',
            'retorno' => 'RETURN',
            'VAR' => 'VARIABLE',
            'SE' => 'IF',
            'SENAO' => 'ELSE',
            'ENQUANTO' => 'WHILE',
            'PARA' => 'FOR',
            'FACA' => 'DO',
            'IMPRIMA' => 'PRINT',
            'LEIA' => 'READ',
            'ESCREVA' => 'WRITE',
            'PROGRAMA' => 'PROGRAM',
        ];

        $descricaoTipos = [
            'int' => 'INT',
            'char' => 'CHAR',
            'float' => 'FLOAT',
            'array' => 'ARRAY',
        ];

        while ($i < $length) {
            // Ignorar espaços e quebras de linha
            if (ctype_space($sourceCode[$i])) {
                if ($sourceCode[$i] === "\n") {
                    $linha++;
                    $coluna = 1;
                } else {
                    $coluna++;
                }
                $i++;
                continue;
            }

            $word = '';

            // Identificar operadores e pontuações
            if ($sourceCode[$i] == '=' || $sourceCode[$i] == '!' || $sourceCode[$i] == '<' || $sourceCode[$i] == '>') {
                // Para operadores compostos, como '==' ou '!='
                $word .= $sourceCode[$i ];
                $i++;
                $coluna++;
                if ($i < $length && $sourceCode[$i] == '=') {
                    $word .= $sourceCode[$i];
                    $i++;
                    $coluna++;
                }
            } else {
                // Para outros operadores e símbolos
                $word .= $sourceCode[$i];
                $i++;
                $coluna++;
            }

            // Identificar palavras (identificadores, palavras reservadas)
            if (ctype_alpha($sourceCode[$i-1])) {
                while ($i < $length && ctype_alnum($sourceCode[$i])) {
                    $word .= $sourceCode[$i];
                    $i++;
                    $coluna++;
                }

                if (isset($descricaoTipos[$word])) {
                    // Se for um tipo, adicione como um token de tipo
                    $tokens[] = [
                        'TIPO',
                        $word,
                        $descricaoTipos[$word],
                        'linha' => $linha,
                        'coluna' => $coluna
                    ];
                    $word = ''; // Limpa a palavra atual para evitar adição duplicada
                    continue; // Continue para o próximo caractere
                }
            }
            // Identificar constantes numéricas
            elseif (ctype_digit($sourceCode[$i-1])) {
                while ($i < $length && ctype_digit($sourceCode[$i])) {
                    $word .= $sourceCode[$i];
                    $i++;
                    $coluna++;
                }
            }

            $found = false;

            // Verificar o token correspondente nos autômatos
            foreach ($automatos as $token => $automato) {
                if ($automato->executa($word)) {
                    $descricao = '';
                    if ($token == 'TIPO') {
                        $descricao = $descricaoTipos[$word];
                    } elseif ($token == 'PALAVRARESERVADA') {
                        $descricao = $palavraReservadaDescricao[$word];
                    } elseif ($token == 'OPERADOR') {
                        $descricao = $operadorDescricao[$word];
                    }
                    $tokens[] = [$token, $word, $descricao, $linha, $coluna - strlen($word)];
                    $found = true;
                    break;
                }
            }

            if (!$found) {
                $erros[] = "Erro léxico: token desconhecido '$word' na linha $linha, coluna $coluna";
            }
        }

        return ['tokens' => $tokens, 'erros' => $erros];
    }
}