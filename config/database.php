<?php
/**
 * database.php — Conexión única a MySQL
 *
 * Una sola conexión PDO para toda la aplicación (patrón singleton
 * sencillo). Ningún otro archivo abre conexiones por su cuenta.
 *
 * Todas las consultas del proyecto usan sentencias preparadas: los datos
 * viajan separados del SQL, así que una entrada del usuario nunca puede
 * convertirse en instrucción. Es la defensa contra inyección SQL.
 */

declare(strict_types=1);

/**
 * Devuelve la conexión PDO, creándola la primera vez que se pide.
 */
function db(): PDO
{
    static $pdo = null;

    if ($pdo instanceof PDO) {
        return $pdo;
    }

    $cfg = $GLOBALS['__config_db'] ?? null;

    if (!is_array($cfg)) {
        throw new RuntimeException('La configuración de base de datos no está cargada.');
    }

    $dsn = sprintf(
        'mysql:host=%s;port=%d;dbname=%s;charset=%s',
        $cfg['host'],
        (int) $cfg['port'],
        $cfg['database'],
        $cfg['charset']
    );

    try {
        $pdo = new PDO($dsn, $cfg['usuario'], $cfg['password'], [
            // Los errores lanzan excepción en vez de pasar desapercibidos.
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            // Las filas llegan como arreglos asociativos.
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            // Sentencias preparadas reales del servidor, no emuladas:
            // el driver nunca reconstruye la consulta interpolando valores.
            PDO::ATTR_EMULATE_PREPARES   => false,
            PDO::ATTR_STRINGIFY_FETCHES  => false,
        ]);
    } catch (PDOException $e) {
        // El detalle va al log; al visitante solo un mensaje neutro.
        error_log('Fallo de conexión a MySQL: ' . $e->getMessage());

        http_response_code(503);
        if (defined('ES_DESARROLLO') && ES_DESARROLLO) {
            exit('No se pudo conectar a MySQL: ' . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8'));
        }
        exit('El servicio no está disponible en este momento. Intenta de nuevo en unos minutos.');
    }

    return $pdo;
}


/**
 * Ejecuta una consulta preparada y devuelve la sentencia.
 *
 * @param string $sql        SQL con marcadores (?, o :nombre)
 * @param array  $parametros Valores a enlazar
 */
function consultar(string $sql, array $parametros = []): PDOStatement
{
    $stmt = db()->prepare($sql);
    $stmt->execute($parametros);
    return $stmt;
}

/** Devuelve todas las filas de una consulta. */
function traerTodo(string $sql, array $parametros = []): array
{
    return consultar($sql, $parametros)->fetchAll();
}

/** Devuelve la primera fila, o null si no hay resultados. */
function traerUno(string $sql, array $parametros = []): ?array
{
    $fila = consultar($sql, $parametros)->fetch();
    return $fila === false ? null : $fila;
}

/** Devuelve el primer valor de la primera fila (útil para COUNT). */
function traerValor(string $sql, array $parametros = [])
{
    $valor = consultar($sql, $parametros)->fetchColumn();
    return $valor === false ? null : $valor;
}

/** Ejecuta un INSERT y devuelve el id generado. */
function insertar(string $sql, array $parametros = []): int
{
    consultar($sql, $parametros);
    return (int) db()->lastInsertId();
}

/** Ejecuta un UPDATE/DELETE y devuelve cuántas filas cambiaron. */
function ejecutar(string $sql, array $parametros = []): int
{
    return consultar($sql, $parametros)->rowCount();
}
