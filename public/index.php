<?php

/**
 * Example Request
 *
 * $params = [
 *      format: 'PDF|JPG|PNG',
 *      pages = [
 *          '<html><body><h1>Page number 1</h1></body></html>',
 *          'https://google.com',
 *          '<html><body><h1>Page number 3</h1></body></html>'
 *      ],
 *      options: [
 *          'page-size'   => 'A4',
 *          'margin-top'    => '20mm',
 *          'orientation'   => 'Portrait',
 *          'header-html' => '<html><head></head><body><b>This is the header</b></body></html>',
 *          'footer-html' => '<p>Page: [page]/[topage]</p>',
 *      ]
 * ]
 *
 * Check the wkhtmltopdf documentation for mor options: https://wkhtmltopdf.org.
 *
 */



require_once __DIR__ . '/../vendor/autoload.php';

use \App\Service\WkService;


# Get JSON as a string
$json_str = file_get_contents('php://input');


if(empty($json_str)){
    echo jsonResponse(['success' => false, 'message' => "It's necessary define the json params."], 400);
    return;
}


# Get as an array
$json_array = json_decode($json_str, true);

if(empty($json_array)){
    echo jsonResponse(['success' => false, 'message' => "The json params looks is wrong format."], 400);
    return;
}

$params = $json_array;


try{
    /**
     * Instance the service
     */
    $wkService = new WkService(strtoupper($params['format']) ?? WkService::TYPE_PDF);

    /**
     * Validate pages param
     */
    if(!isset($params['pages']) || !is_array($params['pages']))
        throw new \InvalidArgumentException("It's necessary set the param 'pages' as array.");


    /**
     * Add pages in url and/or html format
     */
    foreach ($params['pages'] as $page)
    {
        $wkService->addPage($page);
    }


    /**
     * Add options
     */
    if(isset($params['options']) && is_array($params['options']))
    {
        foreach ($params['options'] as $option => $value)
        {
            if(is_string($option) and !empty($value)){
                $wkService->setOption($option, $value);
            }
            else{
                $wkService->setOption($value);
            }
        }
    }


    /**
     * Output pdf/image
     */
    $wkService->generate();
}
catch (\InvalidArgumentException $ex)
{
    echo jsonResponse(['success' => false, 'message' => $ex->getMessage()], 400);
}
catch (\Exception $ex)
{
    echo jsonResponse(['success' => false, 'message' => $ex->getMessage()], 500);
}



/**
 * Create response in Json format
 *
 * @param array $array
 * @param int $statusCode
 * @return string
 */
function jsonResponse(array $array, int $statusCode)
{
    header('Content-type:application/json;charset=utf-8');
    http_response_code($statusCode);
    return json_encode($array);
}
