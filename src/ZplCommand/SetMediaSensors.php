<?php

declare(strict_types=1);

namespace Janisvepris\ZplBuilder\ZplCommand;

use Janisvepris\ZplBuilder\Util\ValueAssert;
use Janisvepris\ZplBuilder\ZplCommand;

readonly class SetMediaSensors implements ZplCommand
{
    public const string COMMAND = '^SS';
    public const string FORMAT = '%03d,%03d,%03d,%04d';

    /** Maximum label length, in dots. */
    public const int MAX_LABEL_LENGTH = 32000;

    /** Maximum value for the percentage-based sensor parameters. */
    public const int MAX_SENSOR_VALUE = 100;

    private int $labelLength;
    private ?int $markLedSensing;
    private ?int $markMediaSensing;
    private ?int $markSensing;
    private int $media;
    private ?int $mediaLedIntensity;
    private int $ribbon;
    private ?int $ribbonLedIntensity;
    private int $web;

    public function __construct(
        int $web,
        int $media,
        int $ribbon,
        int $labelLength,
        ?int $mediaLedIntensity,
        ?int $ribbonLedIntensity,
        ?int $markSensing,
        ?int $markMediaSensing,
        ?int $markLedSensing,
    ) {
        ValueAssert::int($web, 0, self::MAX_SENSOR_VALUE);
        ValueAssert::int($media, 0, self::MAX_SENSOR_VALUE);
        ValueAssert::int($ribbon, 0, self::MAX_SENSOR_VALUE);
        ValueAssert::int($labelLength, 1, self::MAX_LABEL_LENGTH);

        foreach ([$mediaLedIntensity, $ribbonLedIntensity, $markSensing, $markMediaSensing, $markLedSensing] as $optional) {
            if ($optional !== null) {
                ValueAssert::int($optional, 0, self::MAX_SENSOR_VALUE);
            }
        }

        $this->web = $web;
        $this->media = $media;
        $this->ribbon = $ribbon;
        $this->labelLength = $labelLength;
        $this->mediaLedIntensity = $mediaLedIntensity;
        $this->ribbonLedIntensity = $ribbonLedIntensity;
        $this->markSensing = $markSensing;
        $this->markMediaSensing = $markMediaSensing;
        $this->markLedSensing = $markLedSensing;
    }

    public function __toString()
    {
        $output = self::COMMAND . sprintf(
            self::FORMAT,
            $this->web,
            $this->media,
            $this->ribbon,
            $this->labelLength,
        );

        $optional = [
            $this->mediaLedIntensity,
            $this->ribbonLedIntensity,
            $this->markSensing,
            $this->markMediaSensing,
            $this->markLedSensing,
        ];

        $lastSet = -1;
        foreach ($optional as $index => $value) {
            if ($value !== null) {
                $lastSet = $index;
            }
        }

        for ($i = 0; $i <= $lastSet; ++$i) {
            $value = $optional[$i];
            $output .= ',' . ($value === null ? '' : sprintf('%03d', $value));
        }

        return $output;
    }
}
