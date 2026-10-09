<?php
declare(strict_types=1);

// La raíz del sitio siempre muestra la landing pública, incluso si existe
// una sesión activa. El login redirige explícitamente al panel correspondiente.
require __DIR__ . '/landing.php';
