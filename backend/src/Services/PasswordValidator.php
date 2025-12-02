<?php
namespace App\Services;
class PasswordValidator
{
    public function validarPassword(string $pass)
    {
        if (empty($pass))
            throw new \Exception("Password vacío.", -1);
        if (strlen($pass) < 8)
            throw new \Exception("Longitud inválida.", -2);
        if (!preg_match('/[A-Z]/', $pass))
            throw new \Exception("Debe contener una mayúscula.", -3);
        if (!preg_match('/[a-z]/', $pass))
            throw new \Exception("Debe contener una minúscula.", -4);
        if (!preg_match('/[0-9]/', $pass))
            throw new \Exception("Debe contener un número.", -5);
        if (!preg_match('/[\W]/', $pass))
            throw new \Exception("Debe contener un carácter especial.", -6);

        return true;
    }
}