<?php
namespace Facebook\Form\Validator;

use Zend\Validator\AbstractValidator;
use Zend\Validator\Exception;
use Zend\Validator\Regex;

class TextareaValidator extends AbstractValidator
{

    const INVALID_PATTERN = 'invalid pattern';
    const STRING_EMPTY = "string empty";
    const STRING_LENGTH_MIN = "string too short";
    const STRING_LENGTH_MAX = "string too long";

    protected static $regexValidator;

    protected $messageTemplates = array(
        self::INVALID_PATTERN => "pole zawiera niedozwolony ciąg znaków",
        self::STRING_EMPTY => "pole jest puste",
        self::STRING_LENGTH_MIN => "pole jest zbyt krótkie wymagane minimum 3 znaki",
        self::STRING_LENGTH_MAX => "pole jest zbyt długie wymagane maximum 500 znaków"
    );

    /**
     * @param  mixed $value
     * @return bool
     * @throws Exception\RuntimeException If validation of $value is impossible
     */
    public function isValid($value)
    {

        if (strlen($value) < 3) {
            $this->error(self::STRING_LENGTH_MIN);
            return false;
        }

        if (strlen($value) > 500) {
            $this->error(self::STRING_LENGTH_MAX);
            return false;
        }

        if (!is_string($value)) {
            $this->error(self::INVALID_PATTERN);
            return false;
        }

        $this->setValue((string) $value);
        if (empty($this->getValue())) {
            $this->errorr(self::STRING_EMPTY);
            return false;
        }

        if (static::$regexValidator == null) {
            static::$regexValidator = new Regex(['pattern' => "/^[a-zA-Z0-9,.-_ ]*$/"]);
        }

        if (!static::$regexValidator->isValid($this->getValue())) {
            $this->error(self::INVALID_PATTERN);
            return false;
        }
        return true;
    }
}