<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class ImageRatioEnum extends Enum
{
    const _1x1 =   '1x1';
    const _5x4 =   '5x4';
    const _4x3 =   '4x3';
    const _3x2 =   '3x2';
    const _5x3 =   '5x3';
    const _16x9 =   '16x9';
    const _3x1 =   '3x1';
}
