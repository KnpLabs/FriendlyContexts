<?php

include sprintf('%s/../../../vendor/autoload.php', __DIR__);

class Request
{
    public function getMethod()
    {
        return $_SERVER['REQUEST_METHOD'];
    }

    public function getQueries()
    {
        return $_POST;
    }

    public function getHeaders()
    {
        return getallheaders();
    }
}

$guesser = new Knp\FriendlyExtension\Http\HttpContentTypeGuesser();
$request = new Request;
$method = $request->getMethod();
$headers = $request->getHeaders();
$format = array_key_exists('format', $headers) ? $headers['format'] : 'html';

$file = sprintf('%s/%s.%s', __DIR__, strtolower($method), $format);

$response = null;

if ( false === file_exists($file)) {
    $response = new Guzzle\Http\Message\Response(404);
} else {
    $content = file_get_contents($file);
    $code = ('POST' === $method) ? 202 : 200;
    foreach ($_POST as $key => $value) {
        $content = str_replace(sprintf('%%%s%%', $key), $value, $content);
    }
    $response = new Guzzle\Http\Message\Response($code, null, $content);
    $response->setHeader('Content-type', current($guesser->guess($format)));
}

ob_start();
foreach (explode("\n", $response->getRawHeaders()) as $header) {
    header($header);
}
print $response->getBody();
ob_end_flush();

?>
