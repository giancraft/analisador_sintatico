<?php
class AnalisadorDR {
    private $cont = 0;
    public $lexico;

    public function __construct(AnalisadorLexico $lexico) {
        $this->lexico = $lexico;
    }

    //<Programa> ::= programa IDENTIFICADOR ap <Lista_var> fp achave <Lista_comandos> fchave;
    public function Programa() {
        if ($this->term('PROGRAM') &&
            $this->term('IDENTIFICADOR') && 
            $this->term('ABRE_PARENTESES') && 
            $this->Lista_var() && 
            $this->term('FECHA_PARENTESES') && 
            $this->term('ABRE_CHAVES') && 
            $this->Lista_comandos() && 
            $this->term('FECHA_CHAVES')) {
            return true;
        }
        return false; // Retorna false se a sintaxe não corresponder
    }

    //<Lista_var> ::= <Var> <Lista_var> | î;
    public function Lista_var() {
        if ($this->Var()) {
            return $this->Lista_var(); // Chama recursivamente se encontrar uma variável
        }
        return $this->vazio(); // Retorna true se vazio
    }

    //<Var> ::= <Tipo> IDENTIFICADOR PONTO_E_VIRGULA;
    public function Var() {
        return $this->Tipo() && $this->term('IDENTIFICADOR') && $this->term('PONTO_E_VIRGULA');
    }

    //<Tipo> ::= int | char | float | array;
    public function Tipo() {
        return $this->term('INT') || $this->term('CHAR') || $this->term('FLOAT') || $this->term('ARRAY');
    }

    //<Lista_comandos> ::= <Comando> <Lista_comandos> | î;
    public function Lista_comandos() {
        if ($this->Comando()) {
            return $this->Lista_comandos();
        }
        return $this->vazio(); // Retorna true se vazio
    }

    //<Comando> ::= <Atribuicao> | <Leitura> | <Impressao> | <Retorno> | <ChamadaFuncao> | <If> | <For> | <While>;
    public function Comando() {
        return $this->Atribuicao() || 
               $this->Leitura() || 
               $this->Impressao() || 
               $this->Retorno() || 
               $this->ChamadaFuncao() || 
               $this->If() || 
               $this->For() || 
               $this->While();
    }

    //<Atribuicao> ::= IDENTIFICADOR ATRIBUICAO <Expressao> PONTO_E_VIRGULA;
    public function Atribuicao() {
        return $this->term('IDENTIFICADOR') && 
               $this->term('ATRIBUICAO') && 
               $this->Expressao() && 
               $this->term('PONTO_E_VIRGULA');
    }

    //<Leitura> ::= read ABRE_PARENTESES IDENTIFICADOR FECHA_PARENTESES PONTO_E_VIRGULA;
    public function Leitura() {
        return $this->term('READ') && 
               $this->term('ABRE_PARENTESES') && 
               $this->term('IDENTIFICADOR') && 
               $this->term('FECHA_PARENTESES') && 
               $this->term('PONTO_E_VIRGULA');
    }

    //<Impressao> ::= print ABRE_PARENTESES <Expressao> FECHA_PARENTESES PONTO_E_VIRGULA;
    public function Impressao() {
        return $this->term('PRINT') && 
               $this->term('ABRE_PARENTESES') && 
               $this->Expressao() && 
               $this->term('FECHA_PARENTESES') && 
               $this->term('PONTO_E_VIRGULA');
    }

    //<Retorno> ::= return <Expressao> PONTO_E_VIRGULA;
    public function Retorno() {
        return $this->term('RETURN') && 
               $this->Expressao() && 
               $this->term('PONTO_E_VIRGULA');
    }

    //<ChamadaFuncao> ::= IDENTIFICADOR ABRE_PARENTESES <Atributos> FECHA_PARENTESES PONTO_E_V IRGULA;
    public function ChamadaFuncao() {
        return $this->term('IDENTIFICADOR') && 
               $this->term('ABRE_PARENTESES') && 
               $this->Expressao() && 
               $this->term('FECHA_PARENTESES') &&
               $this->term('ABRE_CHAVES') &&
               $this->Comando() &&
               $this->term('FECHA_CHAVES');
    }

    //<If> ::= if ABRE_PARENTESES <Expressao> FECHA_PARENTESES achave <Comando> fchave;
    public function If() {
        return $this->term('IF') && 
               $this->term('ABRE_PARENTESES') && 
               $this->Expressao() && 
               $this->term('FECHA_PARENTESES') && 
               $this->term('ABRE_CHAVES') && 
               $this->Comando() && 
               $this->term('FECHA_CHAVES');
    }

    //<For> ::= for ABRE_PARENTESES <Atribuicao> <Expressao> PONTO_E_VIRGULA <Atribuicao> FECHA_PARENTESES achave <Comando> fchave;
    public function For() {
        return $this->term('FOR') && 
               $this->term('ABRE_PARENTESES') && 
               $this->Atribuicao() &&  
               $this->Expressao() && 
               $this->term('PONTO_E_VIRGULA') && 
               $this->Atribuicao() && 
               $this->term('FECHA_PARENTESES') && 
               $this->term('ABRE_CHAVES') && 
               $this->Comando() && 
               $this->term('FECHA_CHAVES');
    }

    //<While> ::= while ABRE_PARENTESES <Expressao> FECHA_PARENTESES achave <Comando> fchave;
    public function While() {
        return $this->term('WHILE') && 
               $this->term('ABRE_PARENTESES') && 
               $this->Expressao() && 
               $this->term('FECHA_PARENTESES') && 
               $this->term('ABRE_CHAVES') && 
               $this->Comando() && 
               $this->term('FECHA_CHAVES');
    }

    //<Expressao> ::= <Termo> ((SOMA | SUBTRACAO | OPERADOR_LOGICO) <Termo>)*;
    public function Expressao() {
        if ($this->Termo()) {
            while ($this->term('SOMA') || 
                $this->term('SUBTRACAO') || 
                $this->OperadorLogico()) {
                $this->Termo();
            }
            return true;
        }
        return false;
    }

    //<OperadorLogico> ::= IGUAL | DIFERENTE | MENOR_QUE | MAIOR_QUE | MENOR_OU_IGUAL | MAIOR_OU_IGUAL | NEGACAO;
    public function OperadorLogico() {
        return $this->term('IGUAL') || 
            $this->term('DIFERENTE') || 
            $this->term('MENOR_QUE') || 
            $this->term('MAIOR_QUE') || 
            $this->term('MENOR_OU_IGUAL') || 
            $this->term('MAIOR_OU_IGUAL') || 
            $this->term('NEGACAO');
    }

    //<Termo> ::= <Fator> ((MULTIPLICACAO | DIVISAO) <Fator>)*;
    public function Termo() {
        if ($this->Fator()) {
            while ($this->term('MULTIPLICACAO') || $this->term('DIVISAO')) {
                $this->Fator();
            }
            return true;
        }
        return false;
    }

    //<Fator> ::= IDENTIFICADOR | CONSTANTE | ABRE_PARENTESES <Expressao> FECHA_PARENTESES;
    public function Fator() {
        return $this->term('IDENTIFICADOR') || 
               $this->term('CONSTANTE') || 
               ($this->term('ABRE_PARENTESES') && $this->Expressao() && $this->term('FECHA_PARENTESES'));
    }

    private $erros = [];

    public function term($tk) {
        if ($this->cont < count($this->lexico->tokens) && $tk == $this->lexico->tokens[$this->cont]) {
            $this->cont++;
            return true;
        } else {
            $this->erros[] = "Erro: Esperado '$tk', encontrado '{$this->lexico->tokens[$this->cont]}'";
            return false;
        }
    }

    public function getErros() {
        return $this->erros;
    }

    public function vazio() {
        return true; // Representa produção vazia
    }
}
?>