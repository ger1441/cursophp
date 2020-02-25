<?php
class RetirarDineroDelCajero {
    private $cajero =  "8523 Guapi";
    private $numeroDocumento;
    private $nameSession;
    private $documento = [
        '252423' => 'Maria',
        '10075658' => 'Pepito Perez'
    ];

    public function __construct($identificacion){
        foreach ($this->documento as $key => $value) {
            if ($identificacion == $key) {
                $this->nameSession = $value;
                $this->numeroDocumento = $key;
                echo "Bienvenida $value a tu cajero de retiro <br><br>";
            }
        }
    }

    public function __destruct(){
        echo "Destuyendo la sesión de $this->nameSession <br>";
        echo "Sesión cerrada exitosamente";
    }

    public function __call($metodo, $parametros){
        $mensaje = "Método inaccesible: -> $metodo, Parámetros -> ";
        foreach ($parametros as $parametro) {
            $mensaje .= "$parametro ' ";
        }
        echo "$mensaje <br>";
    }

    public function __set($propiedad, $valor){
        return $this->$propiedad = $valor;
    }

    public function __get($propiedad){
        if (property_exists($this, $propiedad)) {
            return $this->$propiedad;
        }
    }

    public function __isset($propiedad){
        return isset($this->$propiedad);
    }

    public function __unset($propiedad){
        unset($this->$propiedad);
    }

}

$cliente = new RetirarDineroDelCajero('10075658');
echo "<br><br>";
$cliente->numeroDocumento = 2547896;
echo "La propiedad numeroDocumento existe: ". isset($cliente->numeroDocumento)."<br>";
unset($cliente->numeroDocumento);
echo "Comprobando si numeroDocumento existe: ".var_dump($cliente);
echo "<br><br>";