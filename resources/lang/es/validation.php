<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages.
    |
    */

    'accepted'             => 'Este campo debe ser aceptado.',
    'active_url'           => 'Este campo no es una URL válida.',
    'after'                => 'Este campo debe ser una fecha posterior a :date.',
    'after_or_equal'       => 'Este campo debe ser una fecha posterior o igual a :date.',
    'alpha'                => 'Este campo sólo debe contener letras.',
    'alpha_dash'           => 'Este campo sólo debe contener letras, números, guiones y guiones bajos.',
    'alpha_num'            => 'Este campo sólo debe contener letras y números.',
    'array'                => 'Este campo debe ser un conjunto.',
    'before'               => 'Este campo debe ser una fecha anterior a :date.',
    'before_or_equal'      => 'Este campo debe ser una fecha anterior o igual a :date.',
    'between'              => [
        'numeric' => 'Este campo tiene que estar entre :min - :max.',
        'file'    => 'Este campo debe pesar entre :min - :max kilobytes.',
        'string'  => 'Este campo tiene que tener entre :min - :max caracteres.',
        'array'   => 'Este campo tiene que tener entre :min - :max elementos.',
    ],
    'boolean'              => 'Este campo debe tener un valor verdadero o falso.',
    'confirmed'            => 'La confirmación de este campo no coincide.',
    'current_password'     => 'Contraseña incorrecta.',
    'date'                 => 'Este campo no es una fecha válida.',
    'date_equals'          => 'Este campo debe ser una fecha igual a :date.',
    'date_format'          => 'Este campo no corresponde al formato :format.',
    'different'            => 'Este campo y :other deben ser diferentes.',
    'digits'               => 'Este campo debe tener :digits dígitos.',
    'digits_between'       => 'Este campo debe tener entre :min y :max dígitos.',
    'dimensions'           => 'Las dimensiones de la imagen no son válidas.',
    'distinct'             => 'El campo contiene un valor duplicado.',
    'email'                => 'Este campo no es un correo válido.',
    'ends_with'            => 'El campo debe finalizar con uno de los siguientes valores: :values',
    'exists'               => 'Este campo es inválido.',
    'file'                 => 'El campo  debe ser un archivo.',
    'filled'               => 'El campo  es obligatorio.',
    'gt'                   => [
        'numeric' => 'El campo  debe ser mayor que :value.',
        'file'    => 'El campo  debe tener más de :value kilobytes.',
        'string'  => 'El campo  debe tener más de :value caracteres.',
        'array'   => 'El campo  debe tener más de :value elementos.',
    ],
    'gte'                  => [
        'numeric' => 'El campo  debe ser como mínimo :value.',
        'file'    => 'El campo  debe tener como mínimo :value kilobytes.',
        'string'  => 'El campo  debe tener como mínimo :value caracteres.',
        'array'   => 'El campo  debe tener como mínimo :value elementos.',
    ],
    'image'                => 'Este campo debe ser una imagen.',
    'in'                   => 'Este campo es inválido.',
    'in_array'             => 'El campo no existe en :other.',
    'integer'              => 'Este campo debe ser un número entero.',
    'ip'                   => 'Este campo debe ser una dirección IP válida.',
    'ipv4'                 => 'Este campo debe ser una dirección IPv4 válida.',
    'ipv6'                 => 'Este campo debe ser una dirección IPv6 válida.',
    'json'                 => 'El campo debe ser una cadena JSON válida.',
    'lt'                   => [
        'numeric' => 'El campo debe ser menor que :value.',
        'file'    => 'El campo debe tener menos de :value kilobytes.',
        'string'  => 'El campo debe tener menos de :value caracteres.',
        'array'   => 'El campo debe tener menos de :value elementos.',
    ],
    'lte'                  => [
        'numeric' => 'El campo debe ser como máximo :value.',
        'file'    => 'El campo debe tener como máximo :value kilobytes.',
        'string'  => 'El campo debe tener como máximo :value caracteres.',
        'array'   => 'El campo debe tener como máximo :value elementos.',
    ],
    'max'                  => [
        'numeric' => 'Este campo no debe ser mayor que :max.',
        'file'    => 'Este campo no debe ser mayor que :max kilobytes.',
        'string'  => 'Este campo no debe ser mayor que :max caracteres.',
        'array'   => 'Este campo no debe tener más de :max elementos.',
    ],
    'mimes'                => 'Este campo debe ser un archivo con formato: :values.',
    'mimetypes'            => 'Este campo debe ser un archivo con formato: :values.',
    'min'                  => [
        'numeric' => 'El tamaño  debe ser de al menos :min.',
        'file'    => 'El tamaño  debe ser de al menos :min kilobytes.',
        'string'  => 'Este campo debe contener al menos :min caracteres.',
        'array'   => 'Este campo debe tener al menos :min elementos.',
    ],
    'multiple_of'          => 'El campo debe ser múltiplo de :value',
    'not_in'               => 'Este campo es inválido.',
    'not_regex'            => 'El formato del campo  no es válido.',
    'numeric'              => 'Este campo debe ser numérico.',
    'password'             => 'La contraseña es incorrecta.',
    'present'              => 'El campo debe estar presente.',
    'regex'                => 'El formato  es inválido.',
    'required'             => 'El campo es obligatorio.',
    'required_if'          => 'El campo es obligatorio cuando :other es :value.',
    'required_unless'      => 'El campo es obligatorio a menos que :other esté en :values.',
    'required_with'        => 'El campo es obligatorio cuando :values está presente.',
    'required_with_all'    => 'El campo es obligatorio cuando :values están presentes.',
    'required_without'     => 'El campo es obligatorio cuando :values no está presente.',
    'required_without_all' => 'El campo es obligatorio cuando ninguno de :values está presente.',
    'prohibited' => 'El campo esta prohibido.',
    'prohibited_if' => 'El campo es prohibido cuando :other es :value.',
    'prohibited_unless' => 'El campo esta prohibido menos :other  en este valor :values.',
    'same'                 => 'Este campo y :other deben coincidir.',
    'size'                 => [
        'numeric' => 'El tamaño  debe ser :size.',
        'file'    => 'El tamaño  debe ser :size kilobytes.',
        'string'  => 'Este campo debe contener :size caracteres.',
        'array'   => 'Este campo debe contener :size elementos.',
    ],
    'starts_with'          => 'El campo debe comenzar con uno de los siguientes valores: :values',
    'string'               => 'El campo debe ser una cadena de caracteres.',
    'timezone'             => 'El  debe ser una zona válida.',
    'unique'               => 'El campo ya ha sido registrado.',
    'uploaded'             => 'Subir ha fallado.',
    'url'                  => 'El formato es inválido.',
    'uuid'                 => 'El campo debe ser un UUID válido.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'password' => [
            'min' => 'Esta contraseña debe contener más de :min caracteres',
        ],
        'email'    => [
            'unique' => 'Este correo ya ha sido registrado.',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap attribute place-holders
    | with something more reader friendly such as E-Mail Address instead
    | of "email". This simply helps us make messages a little cleaner.
    |
    */

    'attributes' => [
        'address'               => 'dirección',
        'age'                   => 'edad',
        'body'                  => 'contenido',
        'city'                  => 'ciudad',
        'content'               => 'contenido',
        'country'               => 'país',
        'date'                  => 'fecha',
        'day'                   => 'día',
        'description'           => 'descripción',
        'email'                 => 'correo electrónico',
        'excerpt'               => 'extracto',
        'first_name'            => 'nombre',
        'gender'                => 'género',
        'hour'                  => 'hora',
        'last_name'             => 'apellido',
        'message'               => 'mensaje',
        'minute'                => 'minuto',
        'mobile'                => 'móvil',
        'month'                 => 'mes',
        'name'                  => 'nombre',
        'password'              => 'contraseña',
        'password_confirmation' => 'confirmación de la contraseña',
        'phone'                 => 'teléfono',
        'price'                 => 'precio',
        'second'                => 'segundo',
        'sex'                   => 'sexo',
        'subject'               => 'asunto',
        'terms'                 => 'términos',
        'time'                  => 'hora',
        'title'                 => 'título',
        'username'              => 'usuario',
        'year'                  => 'año',
    ],
];
