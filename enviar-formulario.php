<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $destinatario = "info@cstiberica.es";
    $asunto = "Nueva solicitud desde la web de CST Ibérica";

    $nombre = htmlspecialchars(trim($_POST["nombre"] ?? ""));
    $empresa = htmlspecialchars(trim($_POST["empresa"] ?? ""));
    $email = htmlspecialchars(trim($_POST["email"] ?? ""));
    $telefono = htmlspecialchars(trim($_POST["telefono"] ?? ""));
    $compresor = htmlspecialchars(trim($_POST["compresor"] ?? ""));
    $mensaje = htmlspecialchars(trim($_POST["mensaje"] ?? ""));

    if (
        empty($nombre) ||
        empty($empresa) ||
        empty($email) ||
        empty($telefono) ||
        empty($compresor) ||
        empty($mensaje)
    ) {
        header("Location: gracias.html?error=campos");
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header("Location: gracias.html?error=email");
        exit;
    }

    if (!preg_match('/^(\+34\s?)?[6-9][0-9\s]{8,14}$/', $telefono)) {
        header("Location: gracias.html?error=telefono");
        exit;
    }

    $contenido = "
Nueva solicitud recibida desde la web:

Nombre: $nombre
Empresa: $empresa
Email: $email
Teléfono: $telefono
Marca/modelo del compresor: $compresor

Mensaje:
$mensaje
";

    $headers = "From: CST Ibérica <no-reply@cstiberica.es>\r\n";
    $headers .= "Reply-To: $email\r\n";
    $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

    if (mail($destinatario, $asunto, $contenido, $headers)) {
        header("Location: gracias.html");
        exit;
    } else {
        header("Location: gracias.html?error=envio");
        exit;
    }

} else {
    header("Location: index.html");
    exit;
}
?>