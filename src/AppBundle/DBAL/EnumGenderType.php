<?php

namespace AppBundle\DBAL;

class EnumGenderType extends EnumType
{
    protected $name = 'enum_gender_type';

    protected $values = array('male', 'female');
}
