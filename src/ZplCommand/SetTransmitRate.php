<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\ZplCommand;

use Janisvepris\ZplBuilder\Enum\TransmitPower;
use Janisvepris\ZplBuilder\Util\BoolToStr;
use Janisvepris\ZplBuilder\ZplCommand;

readonly class SetTransmitRate implements ZplCommand
{
    public const string COMMAND = '^WR';
    public const string FORMAT = '%s,%s,%s,%s,%s';

    private TransmitPower $power;
    private bool $rate1;
    private bool $rate11;
    private bool $rate2;
    private bool $rate5_5;

    public function __construct(
        bool $rate1,
        bool $rate2,
        bool $rate5_5,
        bool $rate11,
        TransmitPower $power,
    ) {
        $this->rate1 = $rate1;
        $this->rate2 = $rate2;
        $this->rate5_5 = $rate5_5;
        $this->rate11 = $rate11;
        $this->power = $power;
    }

    public function __toString()
    {
        return self::COMMAND . sprintf(
            self::FORMAT,
            BoolToStr::conv($this->rate1),
            BoolToStr::conv($this->rate2),
            BoolToStr::conv($this->rate5_5),
            BoolToStr::conv($this->rate11),
            $this->power->value,
        );
    }
}
