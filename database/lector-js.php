<?php
/**
 * lector-js.php — Lector de objetos JavaScript
 *
 * Los archivos del proyecto anterior guardan sus datos en objetos
 * JavaScript, que no son JSON: las claves van sin comillas, los textos
 * usan comillas simples y hay comentarios por medio.
 *
 * Un reemplazo con expresiones regulares se rompería con los apóstrofos
 * («la niña's...»), los emojis y los comentarios, así que se lee
 * carácter por carácter.
 *
 * Lo usan todos los scripts de migración.
 */

declare(strict_types=1);

final class LectorJs
{
    private string $s;
    private int $i = 0;
    private int $n;

    public function __construct(string $texto)
    {
        $this->s = $texto;
        $this->n = strlen($texto);
    }

    /** Lee el valor que empieza en la posición actual. */
    public function leer()
    {
        $this->saltarBlancos();
        return $this->valor();
    }

    private function saltarBlancos(): void
    {
        while ($this->i < $this->n) {
            $c = $this->s[$this->i];

            if ($c === ' ' || $c === "\t" || $c === "\n" || $c === "\r") {
                $this->i++;
                continue;
            }

            if ($c === '/' && $this->i + 1 < $this->n && $this->s[$this->i + 1] === '/') {
                while ($this->i < $this->n && $this->s[$this->i] !== "\n") {
                    $this->i++;
                }
                continue;
            }

            if ($c === '/' && $this->i + 1 < $this->n && $this->s[$this->i + 1] === '*') {
                $this->i += 2;
                while ($this->i + 1 < $this->n
                    && !($this->s[$this->i] === '*' && $this->s[$this->i + 1] === '/')) {
                    $this->i++;
                }
                $this->i += 2;
                continue;
            }

            break;
        }
    }

    private function valor()
    {
        $this->saltarBlancos();

        if ($this->i >= $this->n) {
            throw new RuntimeException('Final inesperado del texto.');
        }

        $c = $this->s[$this->i];

        if ($c === '{') { return $this->objeto(); }
        if ($c === '[') { return $this->arreglo(); }
        if ($c === "'" || $c === '"' || $c === '`') { return $this->cadena(); }

        return $this->literal();
    }

    private function objeto(): array
    {
        $this->i++;
        $obj = [];

        while (true) {
            $this->saltarBlancos();

            if ($this->i >= $this->n) {
                throw new RuntimeException('Objeto sin cerrar.');
            }
            if ($this->s[$this->i] === '}') {
                $this->i++;
                return $obj;
            }
            if ($this->s[$this->i] === ',') {
                $this->i++;
                continue;
            }

            $clave = ($this->s[$this->i] === "'" || $this->s[$this->i] === '"')
                ? $this->cadena()
                : $this->claveSimple();

            $this->saltarBlancos();
            if ($this->i < $this->n && $this->s[$this->i] === ':') {
                $this->i++;
            }

            $obj[$clave] = $this->valor();
        }
    }

    private function arreglo(): array
    {
        $this->i++;
        $arr = [];

        while (true) {
            $this->saltarBlancos();

            if ($this->i >= $this->n) {
                throw new RuntimeException('Arreglo sin cerrar.');
            }
            if ($this->s[$this->i] === ']') {
                $this->i++;
                return $arr;
            }
            if ($this->s[$this->i] === ',') {
                $this->i++;
                continue;
            }

            $arr[] = $this->valor();
        }
    }

    private function cadena(): string
    {
        $comilla = $this->s[$this->i];
        $this->i++;
        $out = '';

        while ($this->i < $this->n) {
            $c = $this->s[$this->i];

            if ($c === '\\' && $this->i + 1 < $this->n) {
                $sig = $this->s[$this->i + 1];
                $mapa = ['n' => "\n", 't' => "\t", 'r' => "\r",
                         '\\' => '\\', "'" => "'", '"' => '"', '`' => '`'];
                $out .= $mapa[$sig] ?? $sig;
                $this->i += 2;
                continue;
            }

            if ($c === $comilla) {
                $this->i++;
                return $out;
            }

            $out .= $c;      // los bytes UTF-8 pasan intactos
            $this->i++;
        }

        throw new RuntimeException('Texto sin cerrar.');
    }

    private function claveSimple(): string
    {
        $ini = $this->i;
        while ($this->i < $this->n && preg_match('/[A-Za-z0-9_$]/', $this->s[$this->i])) {
            $this->i++;
        }
        return substr($this->s, $ini, $this->i - $ini);
    }

    private function literal()
    {
        $ini = $this->i;
        while ($this->i < $this->n && strpos(",}]\n\r", $this->s[$this->i]) === false) {
            $this->i++;
        }

        $bruto = trim(substr($this->s, $ini, $this->i - $ini));

        if ($bruto === 'true')  { return true; }
        if ($bruto === 'false') { return false; }
        if ($bruto === 'null')  { return null; }
        if (is_numeric($bruto)) { return $bruto + 0; }

        return $bruto;
    }
}


/**
 * Extrae una declaración `const NOMBRE = ...` de un archivo HTML.
 *
 * @return mixed|null  El valor leído, o null si no está.
 */
function leerConstante(string $html, string $nombre)
{
    // Se busca la declaración al principio de una línea para no confundirla
    // con una mención dentro de otro código.
    if (!preg_match('/(?m)^\s*(?:const|let|var)\s+' . preg_quote($nombre, '/') . '\s*=\s*/', $html, $m, PREG_OFFSET_CAPTURE)) {
        return null;
    }

    $inicio = $m[0][1] + strlen($m[0][0]);

    try {
        return (new LectorJs(substr($html, $inicio)))->leer();
    } catch (Throwable $e) {
        return null;
    }
}
