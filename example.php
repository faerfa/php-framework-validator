<?php
declare(strict_types=1);

use framework\validator\constraints\Email;
use framework\validator\constraints\NotEmpty;
use framework\validator\Validation;
use framework\validator\ValidationException;

spl_autoload_register(function ($class) {
    $class = str_replace('\\', DIRECTORY_SEPARATOR, $class);
    $filePath = sprintf("%s%s.php", __DIR__ . DIRECTORY_SEPARATOR, str_replace("framework/validator/", "src/", $class));
    if (is_readable($filePath)) {
        require_once $filePath;
        if (class_exists($class)) {
            return true;
        }
    }
    return false;
});

class SignUpParams
{
    /**
     * 密码
     * @var string
     */
    #[NotEmpty]
    #[Email]
    public string $email;

    /**
     * 密码
     * @var string
     */
    #[NotEmpty]
    public string $password;

}

$signUpParams = new SignUpParams();
$signUpParams->email = "";
$signUpParams->password = "";

try {
    Validation::object($signUpParams);
} catch (ValidationException $e) {
    var_dump($e->getMessage());
}