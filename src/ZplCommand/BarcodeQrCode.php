<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\ZplCommand;

use Janisvepris\ZplBuilder\Enum\QrErrorCorrection;
use Janisvepris\ZplBuilder\Enum\QrModel;
use Janisvepris\ZplBuilder\Util\ValueAssert;
use Janisvepris\ZplBuilder\ZplCommand;

readonly class BarcodeQrCode implements ZplCommand
{
    public const string COMMAND = '^BQ';

    /** Orientation, model, magnification. The orientation is fixed to normal by the spec. */
    public const string FORMAT = 'N,%s,%d';

    public const int MAX_MAGNIFICATION = 10;
    public const int MAX_MASK = 7;
    public const int MIN_MASK = 1;

    private ?QrErrorCorrection $errorCorrection;
    private int $magnification;
    private ?int $maskValue;
    private QrModel $model;

    public function __construct(
        QrModel $model,
        int $magnification,
        ?QrErrorCorrection $errorCorrection,
        ?int $maskValue,
    ) {
        ValueAssert::int($magnification, 1, self::MAX_MAGNIFICATION);

        if ($maskValue !== null) {
            ValueAssert::int($maskValue, self::MIN_MASK, self::MAX_MASK);
        }

        $this->model = $model;
        $this->magnification = $magnification;
        $this->errorCorrection = $errorCorrection;
        $this->maskValue = $maskValue;
    }

    public function __toString()
    {
        $base = self::COMMAND . sprintf(
            self::FORMAT,
            $this->model->value,
            $this->magnification,
        );

        if ($this->maskValue !== null) {
            $errorCorrection = $this->errorCorrection === null ? '' : $this->errorCorrection->value;

            return $base . ',' . $errorCorrection . ',' . $this->maskValue;
        }

        if ($this->errorCorrection !== null) {
            return $base . ',' . $this->errorCorrection->value;
        }

        return $base;
    }
}
