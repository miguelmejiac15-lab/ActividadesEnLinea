<?php
/**
 * cabecera-simple.php — Cabecera para páginas de formulario
 *
 * La cabecera completa del sitio (navegación, menú de usuario) llega en
 * la Fase 2. Esta versión sirve a login, registro y páginas sueltas.
 *
 * Espera que la página defina $titulo antes de incluirla.
 */

if (!defined('RUTA_RAIZ')) {
    exit('Acceso directo no permitido.');
}

$titulo = $titulo ?? 'Actividades en Línea';
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= e($titulo) ?> · <?= e(ajuste('sitio_nombre', 'Actividades en Línea')) ?></title>
<link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@400;500;600;700&display=swap" rel="stylesheet">
<style>
    :root{--azul:#29b6f6;--naranja:#ff9800;--morado:#ab47bc;--verde:#4caf50;--rojo:#e53935;--oscuro:#2f3b52;--borde:#e2e8f0}
    *{margin:0;padding:0;box-sizing:border-box}
    body{font-family:'Fredoka',system-ui,sans-serif;background:#f8fafc;color:#445;line-height:1.55;
         min-height:100vh;display:flex;align-items:center;justify-content:center;padding:32px 16px}
    .caja{width:100%;max-width:460px;background:#fff;border-radius:24px;padding:36px;
          box-shadow:0 12px 40px rgba(47,59,82,.09)}
    .marca{text-align:center;margin-bottom:26px}
    .marca .logo{font-size:1.25rem;font-weight:700;letter-spacing:.01em}
    .marca .logo .a{color:var(--azul)} .marca .logo .b{color:var(--naranja)} .marca .logo .c{color:var(--morado)}
    .marca .testigo{color:#8b95a5;font-size:.75rem;margin-top:3px}
    h1{color:var(--oscuro);font-size:1.5rem;margin-bottom:5px}
    .sub{color:#8b95a5;font-size:.9rem;margin-bottom:22px}
    label{display:block;font-weight:600;color:var(--oscuro);font-size:.85rem;margin-bottom:5px}
    input{width:100%;padding:12px 14px;border:2px solid var(--borde);border-radius:12px;
          font-family:inherit;font-size:.95rem;margin-bottom:15px;background:#fcfdfe}
    input:focus{outline:none;border-color:var(--azul);background:#fff}
    .fila{display:flex;gap:12px}.fila>div{flex:1}
    .pista{font-size:.78rem;color:#8b95a5;margin:-10px 0 15px}
    button{width:100%;padding:15px;border:none;border-radius:50px;
           background:linear-gradient(135deg,var(--azul),var(--morado));color:#fff;
           font-family:inherit;font-size:1.02rem;font-weight:700;cursor:pointer;margin-top:8px}
    button:hover{filter:brightness(1.07)}
    .aviso{padding:13px 17px;border-radius:13px;margin-bottom:18px;font-size:.89rem}
    .aviso.mal{background:#ffebee;border-left:4px solid var(--rojo);color:#b71c1c}
    .aviso.ok{background:#e8f5e9;border-left:4px solid var(--verde);color:#1b5e20}
    .aviso.info{background:#e3f2fd;border-left:4px solid var(--azul);color:#0d47a1}
    .aviso ul{margin:5px 0 0 17px}
    .pie{text-align:center;margin-top:20px;font-size:.88rem;color:#8b95a5}
    .pie a{color:var(--azul);font-weight:600;text-decoration:none}
    .pie a:hover{text-decoration:underline}
    .campo-error{border-color:var(--rojo)}
    .error-texto{color:var(--rojo);font-size:.78rem;margin:-11px 0 14px}

    /* Los tres pasos de una compra. Solo salen cuando hay una compra en
       marcha: quien crea una cuenta gratis no está en ningún proceso y
       enseñarle una barra de pasos le haría creer que sí. */
    .pasos-compra{display:flex;gap:6px;list-style:none;padding:0;margin:0 0 24px;
                  font-size:.78rem;color:#8b95a5}
    .pasos-compra li{flex:1;display:flex;align-items:center;gap:6px;
                     padding:8px 6px;border-top:3px solid var(--borde)}
    .pasos-compra li span{display:grid;place-items:center;width:19px;height:19px;
                          border-radius:50%;background:var(--borde);color:#fff;
                          font-size:.7rem;font-weight:700;flex:none}
    .pasos-compra li.ahora{color:var(--oscuro);font-weight:600;border-top-color:var(--azul)}
    .pasos-compra li.ahora span{background:var(--azul)}
</style>
<?= etiquetaAnalitica() ?>
</head>
<body>
<div class="caja">

    <div class="marca">
        <div class="logo">
            ✏️ <span class="a">ACTIVIDADES</span> <span class="b">EN</span> <span class="c">LÍNEA</span>
        </div>
        <div class="testigo"><?= e(ajuste('sitio_testigo', '')) ?></div>
    </div>

    <?php foreach (mensajesPendientes() as $m): ?>
        <div class="aviso <?= e($m['tipo'] === 'error' ? 'mal' : $m['tipo']) ?>"><?= e($m['texto']) ?></div>
    <?php endforeach; ?>
