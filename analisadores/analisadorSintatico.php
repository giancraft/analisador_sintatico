<?php
include("analisadorLexico.php");

class AnalisadorSintatico {
    private $posicaoAtual;
    public $tokens;
    public $erros;

    public function __construct($tokens) {
        $this->tokens = $tokens;
        $this->posicaoAtual = 0;
        $this->erros = [];
    }

    // Função para verificar o token atual e avançar se for o esperado
    private function term($esperado) {
        if ($this->tokenAtual() && $this->tokenAtual()[1] == $esperado) {
            $this->avancar();
            return true;
        }
        $this->erro("Esperado '$esperado'");
        return false;
    }

    // Retorna o token atual
    private function tokenAtual() {
        return $this->tokens[$this->posicaoAtual] ?? null;
    }

    // Avança para o próximo token
    private function avancar() {
        $this->posicaoAtual++;
    }

    // Função de erro
    private function erro($mensagem) {
        $this->erros[] = "Erro sintático: $mensagem na linha {$this->tokenAtual()[3]}, coluna {$this->tokenAtual()[4]}";
    }

    // Função que verifica vazio (lambda)
    public function vazio() {
        return true;
    }

    // <Programa> ::= programa identificador ap <Lista_var> fp achave <Lista_comandos> fchave;
    public function Programa() {
        return $this->term('programa') && $this->term('identificador') && $this->term('(') &&
               $this->Lista_var() && $this->term(')') && $this->term('{') && 
               $this->Lista_comandos() && $this->term('}');
    }

    // <Lista_var> ::= <Var> <Lista_var> | î;
    public function Lista_var() {
        return ($this->Var() && $this->Lista_var()) || $this->vazio();
    }

    // <Var> ::= <Tipo> identificador;
    public function Var() {
        return $this->Tipo() && $this->term('identificador');
    }

    // <Tipo> ::= int | char | String;
    public function Tipo() {
        return $this->term('int') || $this->term('char') || $this->term('String');
    }

    // <Lista_comandos> ::= <If> <Lista_comandos>| <Else> <Lista_comandos>| <Switch> <Lista_comandos>| î;
    public function Lista_comandos() {
        return $this->If() && $this->Lista_comandos() ||
               $this->Else() && $this->Lista_comandos() ||
               $this->Switch() && $this->Lista_comandos() ||
               $this->vazio();
    }

    // <If> ::= if ap identificador <Comparadores> <Atributos> fp achave <Comandos> fchave;
    public function If() {
        return $this->term('if') && $this->term('(') && $this->term('identificador') &&
               $this->Comparadores() && $this->Atributos() && $this->term(')') &&
               $this->term('{') && $this->Comandos() && $this->term('}');
    }

    // <Else> ::= else achave <Comandos> fchave;
    public function Else() {
        return $this->term('else') && $this->term('{') && $this->Comandos() && $this->term('}');
    }

    // <Switch> ::= switch ap identificador fp achave <Casos> fchave;
    public function Switch() {
        return $this->term('switch') && $this->term('(') && $this->term('identificador') &&
               $this->term(')') && $this->term('{') && $this->Casos() && $this->term('}');
    }

    // <Casos> ::= <Caso> <Casos> | î;
    public function Casos() {
        return $this->Caso() && $this->Casos() || $this->vazio();
    }

    // <Caso> ::= case <Atributos> dp <Comandos>;
    public function Caso() {
        return $this->term('case') && $this->Atributos() && $this->term(':') && $this->Comandos();
    }

    // <Comparadores> ::= igual | menor | maior | maiorigual | menorigual | diferente;
    public function Comparadores() {
        return $this->term('==') || $this->term('<') || $this->term('>') ||
               $this->term('>=') || $this->term('<=') || $this->term('!=');
    }

    // <Comandos> ::= <Atribuicao> | <Incremento> | <Operacao>;
    public function Comandos() {
        return $this->Atribuicao() || $this->Incremento() || $this->Operacao();
    }

    // <Atribuicao> ::= identificador igual <Atributos> pv;
    public function Atribuicao() {
        return $this->term('identificador') && $this->term('=') && $this->Atributos() && $this->term(';');
    }

    // <Atributos> ::= caracter | string | numero | identificador;
    public function Atributos() {
        return $this->term('caractere') || $this->term('string') || $this->term('numero') || $this->term('identificador');
    }

    // <Incremento> ::= identificador <Tip_incremento> pv
    public function Incremento() {
        return $this->term('identificador') && $this->Tip_incremento() && $this->term(';');
    }

    // <Tip_incremento> ::= maismais | menosmenos;
    public function Tip_incremento() {
        return $this->term('++') || $this->term('--');
    }

    // <Operacao> ::= identificador <Operador> <Atributos> pv;
    public function Operacao() {
        return $this->term('identificador') && $this->Operador() && $this->Atributos() && $this->term(';');
    }

    // <Operador> ::= mais | menos | barra | mult;
    public function Operador() {
        return $this->term('+') || $this->term('-') || $this->term('/') || $this->term('*');
    }

    public function analisar() {
        $this->Programa();

        if ($this->tokenAtual()) {
            $this->erro("Token inesperado '{$this->tokenAtual()[1]}'");
        }

        return $this->erros;
    }
}
?>
