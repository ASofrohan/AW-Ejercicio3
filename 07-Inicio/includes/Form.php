<?php
namespace es\ucm\fdi\aw;

abstract class Form
{
    private $formId;

    private $action;

    public function __construct($formId, $opciones = array() )
    {
        $this->formId = $formId;

        $opcionesPorDefecto = array( 'action' => null, );
        $opciones = array_merge($opcionesPorDefecto, $opciones);

        $this->action   = $opciones['action'];
        
        if ( !$this->action ) {
            $this->action = htmlentities($_SERVER['PHP_SELF']);
        }
    }
  
    public function gestiona()
    {   
        if ( ! $this->formularioEnviado($_POST) ) {
            return $this->generaFormulario();
        } else {
            $result = $this->procesaFormulario($_POST);
            if ( is_array($result) ) {
                return $this->generaFormulario($_POST, $result);
            } else {
                header('Location: '.$result);
                exit();
            }
        }  
    }

    protected function generaCamposFormulario($datosIniciales, $errores = array())
    {
        return '';
    }

    protected function procesaFormulario($datos)
    {
        return array();
    }
  
    private function formularioEnviado(&$params)
    {
        return isset($params['action']) && $params['action'] == $this->formId;
    } 

    private function generaFormulario(&$datos = array(), &$errores = array())
    {
        $htmlCamposFormularios = $this->generaCamposFormulario($datos, $errores);

        $htmlForm = <<<EOS
            <form method="POST" action="$this->action" id="$this->formId" >
                <input type="hidden" name="action" value="$this->formId" />
                $htmlCamposFormularios
            </form>
        EOS;
        return $htmlForm;
    }

    protected static function generaListaErroresGlobales($errores = array(), $classAtt='')
    {
        $html='';
        $clavesErroresGenerales = array_filter(array_keys($errores), function ($elem) {
            return is_numeric($elem);
        });

        $numErrores = count($clavesErroresGenerales);
        if ($numErrores > 0) {
            $html = "<ul class=\"$classAtt\">";
            if (  $numErrores == 1 ) {
                $html .= "<li>$errores[0]</li>";
            } else {
                foreach($clavesErroresGenerales as $clave) {
                    $html .= "<li>$errores[$clave]</li>";
                }
                $html .= "</li>";
            }
            $html .= '</ul>';
        }
        return $html;
    }

    protected static function createMensajeError($errores=array(), $idError='', $htmlElement='span', $atts = array())
    {
        $html = '';
        if (isset($errores[$idError])) {
            $att = '';
            foreach($atts as $key => $value) {
                $att .= "$key=$value";
            }
            $html = " <$htmlElement $att>{$errores[$idError]}</$htmlElement>";
        }

        return $html;
    }
}
?>