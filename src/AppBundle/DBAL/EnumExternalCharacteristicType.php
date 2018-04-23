<?php

namespace AppBundle\DBAL;

class EnumExternalCharacteristicType extends EnumType
{
    protected $name = 'enum_external_characteristic_type';

    protected $values = array('int', 'string', 'choice');
}
