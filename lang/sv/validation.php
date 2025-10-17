<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Líneas de idioma para validación
    |--------------------------------------------------------------------------
    |
    | Las siguientes líneas contienen los mensajes de error predeterminados
    | utilizados por la clase Validator de Laravel. Algunos de ellos tienen
    | múltiples versiones según las reglas (como max, min, etc.).
    |
    */

    'required' => 'El campo :attribute es obligatorio.',
    'email'    => 'El campo :attribute debe ser una dirección de correo válida.',
    'max' => [
        'string' => 'El campo :attribute no debe exceder los :max caracteres.',
    ],
    'min' => [
        'string' => 'El campo :attribute debe tener al menos :min caracteres.',
    ],
    'string' => 'El campo :attribute debe ser texto.',

    /*
    |--------------------------------------------------------------------------
    | Atributos personalizados
    |--------------------------------------------------------------------------
    |
    | Aquí puedes especificar nombres legibles para los atributos, de modo que
    | los mensajes de error sean más claros para el usuario final.
    |
    */

    'attributes' => [
        'name'    => 'Nombre',
        'email'   => 'Correo',
        'message' => 'Mensaje',
    ],

];
