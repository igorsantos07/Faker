<?php

namespace Faker\Provider\pt_BR;

class Company extends \Faker\Provider\Company
{
    const PERSON_CNPJ_FORMAT_MASKED = 'cnpj_format_masked';
    const PERSON_CNPJ_FORMAT_PLAIN = 'cnpj_format_plain';

    protected static $formats = array(
        '{{lastName}} {{companySuffix}}',
        '{{lastName}}-{{lastName}}',
        '{{lastName}} e {{lastName}}',
        '{{lastName}} de {{lastName}}',
        '{{lastName}}, {{lastName}} e {{lastName}}'
    );

    protected static $companySuffix = array('e Filho', 'e Filha', 'e Filhos', 'e Associados', 'e Flia.', 'SRL', 'SA', 'S. de H.');

    /**
     * A random CNPJ number.
     * @link http://en.wikipedia.org/wiki/CNPJ
     * @link http://pt.wikipedia.org/wiki/CNPJ#Algoritmo_de_Valida.C3.A7.C3.A3o
     *
     * @todo: implement algorithm to generate only valids CNPJs.
     *
     * @param string $format If the number should have dots/slashes/dashes or not.
     * @return string
     */
    public function cnpj($format = self::PERSON_CNPJ_FORMAT_MASKED)
    {
        $number = $this->generator->numerify('########0001');
        $number .= $this->verifierDigit($number);
        $number .= $this->verifierDigit($number);

        if ($format === self::PERSON_CNPJ_FORMAT_MASKED) {
            $number = vsprintf('%d%d.%d%d%d.%d%d%d/%d%d%d%d-%d%d', str_split($number));
        }

        return $number;
    }

    /**
     * @param $numbers
     * @return int
     */
    protected function verifierDigit($numbers)
    {
        $length = strlen($numbers);
        $second_algorithm = $length >= 12;
        $verifier = 0;

        for ($i = 1; $i <= $length; $i++) {
            if (!$second_algorithm) {
                $multiplier = $i + 1;
            } else {
                $multiplier = ($i >= 9) ? $i - 7 : $i + 1;
            }
            $verifier += $numbers[$length - $i] * $multiplier;
        }

        $verifier = 11 - ($verifier % 11);
        if ($verifier >= 10) {
            $verifier = 0;
        }

        return $verifier;
    }

}
