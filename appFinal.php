<?php

class CajeroAutomatico {
    //variables principales
    private $identificacion_cajero;
    private $saldo_cajero;
    private $numero_operaciones;
    private $lista_cuentas;

    //funcion principal de __construct
    public function __construct($identificacion, $saldo, $cuentas) {
        $this->identificacion_cajero = $identificacion;
        $this->saldo_cajero = $saldo;
        $this->numero_operaciones = 0;
        $this->lista_cuentas = $cuentas;
    }

    //funcion de ingreso al cajero, validando numero de la cuenta del usuario y la contraseña
    public function ingresarCajero($cuenta, $contrasena) {
        if (isset($this->lista_cuentas[$cuenta]) && $this->lista_cuentas[$cuenta]['contraseña'] === $contrasena) {
            $_SESSION['cuenta_seleccionada'] = $cuenta;
            echo "Ingreso al cajero exitoso. Cuenta seleccionada: $cuenta<br>";
        } else {
            echo "Cuenta o contraseña incorrecta. Por favor, intente nuevamente.<br>";
        }
    }

    //funcion para actualizar el saldo del cajero
    public function actualizarSaldoCajero($monto) {
        $this->saldo_cajero += $monto;
    }

    //funcion para actualizar el numero de operaciones que se han realizado en el cajero
    public function actualizarNumeroOperaciones() {
        $this->numero_operaciones++;
    }

    //funcion de retirar el dinero del cajero
    public function retirar($monto) {
        $cuenta_seleccionada = $_SESSION['cuenta_seleccionada'];
        $saldoInicial = $this->lista_cuentas[$cuenta_seleccionada]['saldo'];

        if ($monto > 0 && isset($this->lista_cuentas[$cuenta_seleccionada])) {
            $saldo_cuenta = $this->lista_cuentas[$cuenta_seleccionada]['saldo'];

            if ($monto <= $saldo_cuenta && $monto <= $this->saldo_cajero) {
                $saldo_cuenta -= $monto;
                $this->lista_cuentas[$cuenta_seleccionada]['saldo'] = $saldo_cuenta;
                $this->saldo_cajero -= $monto;

                echo "Su saldo es $saldoInicial <br> ";
                echo "Usted retiro exitosamente: $monto.<br> Nuevo saldo de la cuenta: $saldo_cuenta.<br>";
            } else {
                echo "No es posible realizar el retiro. Verifique el saldo de la cuenta y el saldo disponible en el cajero.<br>";
            }
        } else {
            echo "Monto inválido o cuenta no seleccionada. Por favor, intente nuevamente.<br>";
        }
    }

    //funcion de transferencia de dinero a otra cuenta
    public function transferir($cuenta_destino, $monto) {
        $cuenta_seleccionada = $_SESSION['cuenta_seleccionada'];
        $cuenta = $this->lista_cuentas[$cuenta_destino]['nombre_cliente'];
        
        if (isset($this->lista_cuentas[$cuenta_seleccionada]) && isset($this->lista_cuentas[$cuenta_destino])) {
            $saldo_cuenta_origen = $this->lista_cuentas[$cuenta_seleccionada]['saldo'];
            $saldo_cuenta_destino = $this->lista_cuentas[$cuenta_destino]['saldo'];

            if ($monto > 0 && $monto <= $saldo_cuenta_origen && $monto <= $this->saldo_cajero) {
                $saldo_cuenta_origen -= $monto;
                $saldo_cuenta_destino += $monto;

                $this->lista_cuentas[$cuenta_seleccionada]['saldo'] = $saldo_cuenta_origen;
                $this->lista_cuentas[$cuenta_destino]['saldo'] = $saldo_cuenta_destino;
                // $this->saldo_cajero -= $monto;

                echo "Usted va a pasar $monto <br>";
                
                echo "Transferencia exitosa.  la cuenta de $cuenta <br>";
            } else {
                echo "No es posible realizar la transferencia. Verifique el saldo de la cuenta y el saldo disponible en el cajero.<br>";
            }
        } else {
            echo "Cuenta de origen o cuenta de destino inválidas. Por favor, intente nuevamente.<br>";
        }
    }

    //funcion de la consulta del saldo de la cuenta
    public function consulta() {
        $cuenta_seleccionada = $_SESSION['cuenta_seleccionada'];

        if (isset($this->lista_cuentas[$cuenta_seleccionada])) {
            $saldo_cuenta = $this->lista_cuentas[$cuenta_seleccionada]['saldo'];
            echo "Saldo actual de tu cuenta: $saldo_cuenta<br>";
        } else {
            echo "No se ha seleccionado ninguna cuenta. Por favor, ingrese al cajero primero.<br>";
        }
    }

    //funcion del cierre de operaciones que cuenta cuantas operaciones se realizaron
    public function cierreOperaciones() {
        echo "Número de operaciones realizadas: $this->numero_operaciones<br>";
        echo "Saldo final del cajero: $this->saldo_cajero<br>";
    }
}

// Cuentas de lo usuarios (simula base de datos)
$cuentas = array(
    123456 => array(
        "nombre_cliente" => "Juan Perez",
        "contraseña" => "clave123",
        "saldo" => 1000
    ),
    234567 => array(
        "nombre_cliente" => "Maria Gomez",
        "contraseña" => "clave456",
        "saldo" => 5000
    ),
    345678 => array(
        "nombre_cliente" => "Pedro Rodriguez",
        "contraseña" => "clave789",
        "saldo" => 2500
    )
);

// Inicializar el objeto cajero con sus respectivos argumentos 
$cajero = new CajeroAutomatico("CAJ01", 10000, $cuentas);


// Estas son las operaciones que se van a realizar (simula un formulario en el front end)
$cajero->ingresarCajero(234567, "clave456");
$cajero->actualizarNumeroOperaciones();
$cajero->retirar(200);
$cajero->actualizarSaldoCajero(0);
$cajero->actualizarNumeroOperaciones();
$cajero->transferir(345678, 800);
$cajero->actualizarSaldoCajero(0);
$cajero->actualizarNumeroOperaciones();
$cajero->consulta();
$cajero->actualizarNumeroOperaciones();
$cajero->cierreOperaciones();

?>
