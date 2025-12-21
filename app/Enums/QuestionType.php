<?php

namespace App\Enums;

enum QuestionType: string
{
    case MULTIPLE_CHOICE = 'multiple_choice';
    case MULTI_SELECT = 'multi_select';
    case TRUE_FALSE = 'true_false';
    case OPEN = 'open';
}

