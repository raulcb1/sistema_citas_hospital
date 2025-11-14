<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Consulta de Afiliado</title>
</head>
<body>
<?php
// Verificar si la extensión SOAP está habilitada
if (extension_loaded('soap')) {
    echo "La extensión SOAP está habilitada en este servidor.";
} else {
    echo "La extensión SOAP no está habilitada en este servidor.";
}
?>

    <h1>Consulta de Afiliado</h1>
    <form method="post">
        <label for="documento">Número de Documento:</label>
        <input type="text" id="documento" name="documento" required>
        <input type="submit" value="Consultar">
    </form>

    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Recibir los datos del formulario
        $documento = $_POST["documento"];

        // Configurar el cliente SOAP
        $wsdl = 'http://dpidesalud.minsa.gob.pe/sis/afiliado/v1.0/afiliado?wsdl';
        $client = new SoapClient($wsdl, array('trace' => 1));

        // Estructura de datos para la solicitud
        $datos_solicitud = '<soapenv:Envelope xmlns:SOAP-ENV="http://schemas.xmlsoap.org/soap/envelope/" xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/">
          <soapenv:header>
            <afil:afiliadoSis xmlns:afil="http://dpidesalud.minsa.gob.pe/sis/afiliado/v1.0/afiliado">
              <afil:tiDocumento>1</afil:tiDocumento>
              <afil:nuDocumento>' . $documento . '</afil:nuDocumento>
              <op2 xsi:type="xsd:integer">400</op2>
            </afil:afiliadoSis>
          </soapenv:header>
        </soapenv:Envelope>';

        // Llamar al método del servicio SOAP con la solicitud
        $result = $client->__doRequest($datos_solicitud, $wsdl, 'ObtenerAfiliado', SOAP_1_1);

        // Manejar la respuesta
        echo "<h2>Respuesta del Servicio SOAP:</h2>";
        echo "<pre>";
        print_r($result);
        echo "</pre>";
    }
    ?>
</body>
</html>