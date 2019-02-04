<?php
/**
 * Created by PhpStorm.
 * User: Luan Maik
 * Date: 25/01/2019
 * Time: 17:38
 */

namespace App\Service;


use mikehaertl\wkhtmlto\Image;
use mikehaertl\wkhtmlto\Pdf;

class WkService
{
    const PATH_WKHTMLTOPDF      = __DIR__ . '/../../../vendor/h4cc/wkhtmltopdf-amd64/bin/wkhtmltopdf-amd64';
    const PATH_WKHTMLTOIMAGE    = __DIR__ . '/../../../vendor/h4cc/wkhtmltoimage-amd64/bin/wkhtmltoimage-amd64';


    const TYPE_PDF = 'PDF';
    const TYPE_PNG = 'PNG';
    const TYPE_JPG = 'JPG';

    protected $type;

    protected $wkhtmltox;

    protected $options = [
        'commandOptions' => [
            'useExec' => true,
            'procEnv' => [
                'LANG' => 'en_US.utf-8'
            ]
        ]
    ];

    /**
     * WkService constructor.
     * @param $TYPE
     */
    public function __construct(string $TYPE)
    {
        $this->type = $TYPE;

        //Define a instância de conversão
        switch ($TYPE)
        {
           case self::TYPE_PDF:
               $this->wkhtmltox = new Pdf();
               $this->wkhtmltox->binary = self::PATH_WKHTMLTOPDF;
               $this->setOption('margin-top', '15mm');
               $this->setOption('margin-bottom', '15mm');
               $this->setOption('margin-left', '15mm');
               $this->setOption('margin-right', '15mm');
               break;
            case self::TYPE_PNG:
            case self::TYPE_JPG:
               $this->wkhtmltox = new Image();
               $this->wkhtmltox->binary = self::PATH_WKHTMLTOIMAGE;
               $this->setOption('format', strtolower($TYPE));
               break;
           default:
               throw new \InvalidArgumentException("The format informed is inválid. Given '{$TYPE}'. The valids formats are PDF, PNG and JPG.");
               break;
        }
    }


    /**
     * Gera o arquivo
     *
     * @param null $filename
     * @param bool $inline
     * @return bool
     * @throws \Exception
     */
    public function generate($filename = null, $inline = false) : bool
    {
        $this->wkhtmltox->setOptions($this->options);

        if (!$this->wkhtmltox->send($filename, $inline)) {
            throw new \Exception($this->wkhtmltox->getError());
        }

        return true;
    }


    /**
     * @return array
     */
    public function getOptions(): array
    {
        return $this->options;
    }

    /**
     * @param array $options
     */
    public function setOptions(array $options)
    {
        $this->options = $options;
    }


    /**
     * @param string $option
     * @param $value
     */
    public function setOption(string $option, $value = null)
    {
        if($value === null){
            $this->options[] = $option;
        }else{
            $this->options[$option] = $value;
        }
    }



    public function addPage(string $url_or_html)
    {
        switch ($this->type)
        {
            case self::TYPE_PDF:
                $this->wkhtmltox->addPage($url_or_html);
                break;
            case self::TYPE_PNG:
            case self::TYPE_JPG:
                $this->wkhtmltox->setPage($url_or_html);
                break;
        }
    }


    /**
     * @return Image|Pdf
     */
    public function getWkhtmltox()
    {
        return $this->wkhtmltox;
    }

}