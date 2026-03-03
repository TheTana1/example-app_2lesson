<?php


namespace App;

enum MusicGenre: string
{
    case POP = 'pop';
    case ROCK = 'rock';
    case HIP_HOP = 'hip_hop';
    case RAP = 'rap';
    case JAZZ = 'jazz';
    case BLUES = 'blues';
    case CLASSICAL = 'classical';
    case ELECTRONIC = 'electronic';
    case HOUSE = 'house';
    case TECHNO = 'techno';
    case RNB = 'rnb';
    case METAL = 'metal';
    case REGGAE = 'reggae';
    case COUNTRY = 'country';
    case SOUNDTRACK = 'soundtrack';

    public function label(): string
    {
        return match ($this) {
            self::POP => 'Поп',
            self::ROCK => 'Рок',
            self::HIP_HOP => 'Хип-хоп',
            self::RAP => 'Рэп',
            self::JAZZ => 'Джаз',
            self::BLUES => 'Блюз',
            self::CLASSICAL => 'Классическая музыка',
            self::ELECTRONIC => 'Электронная музыка',
            self::HOUSE => 'Хаус',
            self::TECHNO => 'Техно',
            self::RNB => 'R&B',
            self::METAL => 'Метал',
            self::REGGAE => 'Регги',
            self::COUNTRY => 'Кантри',
            self::SOUNDTRACK => 'Саундтрек',
        };
    }

    public static function options(): array
    {
        return array_map(
            fn($case) => [
                'value' => $case->value,
                'label' => $case->label(),
            ],
            self::cases()
        );
    }
    public static function values(): array
    {
        return array_column(self::cases(), 'value'  );
    }
    public static function getLabel(string $value): string
    {
        $genre = self::tryFrom($value);
        return $genre ? $genre->label() : 'Неизвестный жанр';
    }
}
