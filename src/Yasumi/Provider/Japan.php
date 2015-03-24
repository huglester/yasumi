<?php
/*
 * This file is part of the Yasumi package.
 *
 * Copyright (c) 2015 AzuyaLabs
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Yasumi\Provider;

use Carbon\Carbon;
use Yasumi\Holiday;

/**
 * Provider for all holidays in the Japan.
 */
class Japan extends AbstractProvider
{
    /**
     * The gradient parameter of the approximate expression to calculate equinox day
     */
    const EQUINOX_GRADIENT = 0.242194;

    /**
     * The initial parameter of the approximate expression to calculate vernal equinox day from 1900 to 1979
     */
    const VERNAL_EQUINOX_PARAM_1979 = 20.8357;

    /**
     * The initial parameter of the approximate expression to calculate vernal equinox day from 1980 to 2099
     */
    const VERNAL_EQUINOX_PARAM_2099 = 20.8431;

    /**
     * The initial parameter of the approximate expression to calculate vernal equinox day from 2100 to 2150
     */
    const VERNAL_EQUINOX_PARAM_2150 = 21.8510;

    /**
     * The initial parameter of the approximate expression to calculate autumnal equinox day from 1851 to 1899
     */
    const AUTUMNAL_EQUINOX_PARAM_1899 = 22.2588;

    /**
     * The initial parameter of the approximate expression to calculate autumnal equinox day from 1900 to 1979
     */
    const AUTUMNAL_EQUINOX_PARAM_1979 = 23.2588;

    /**
     * The initial parameter of the approximate expression to calculate autumnal equinox day from 1980 to 2099
     */
    const AUTUMNAL_EQUINOX_PARAM_2099 = 23.2488;

    /**
     * The initial parameter of the approximate expression to calculate autumnal equinox day from 2100 to 2150
     */
    const AUTUMNAL_EQUINOX_PARAM_2150 = 24.2488;

    /**
     * Initialize holidays for Japan.
     */
    public function initialize()
    {
        $this->timezone = 'Asia/Tokyo';

        /*
         * New Year's Day. New Year's Day is held on January 1st and established since 1948.
         */
        if ($this->year >= 1948) {
            $holiday = new Holiday('newYearsDay', ['en_US' => 'New Year\'s Day', 'ja_JP' => '元日'],
                Carbon::create($this->year, 1, 1, 0, 0, 0, $this->timezone), $this->locale);
            $this->addHoliday($holiday);
        }

        /**
         * National Foundation Day. National Foundation Day is held on February 11th and established since 1966.
         */
        if ($this->year >= 1966) {
            $holiday = new Holiday('nationalFoundationDay', ['en_US' => 'National Foundation Day', 'ja_JP' => '建国記念の日'],
                Carbon::create($this->year, 2, 11, 0, 0, 0, $this->timezone), $this->locale);
            $this->addHoliday($holiday);
        }

        /**
         * Showa Day. Showa Day is held on April 29th and established since 2007.
         */
        if ($this->year >= 2007) {
            $this->addHoliday(new Holiday('showaDay', ['en_US' => 'Showa Day', 'ja_JP' => '昭和の日'],
                Carbon::create($this->year, 4, 29, 0, 0, 0, $this->timezone), $this->locale));
        }

        /**
         * Constitution Memorial Day. Constitution Memorial Day is held on May 3rd and established since 1948.
         */
        if ($this->year >= 1948) {
            $this->addHoliday(new Holiday('constitutionMemorialDay',
                ['en_US' => 'Constitution Memorial Day', 'ja_JP' => '憲法記念日'],
                Carbon::create($this->year, 5, 3, 0, 0, 0, $this->timezone), $this->locale));
        }

        /**
         * Children's Day. Children's Day is held on May 5th and established since 1948.
         */
        if ($this->year >= 1948) {
            $this->addHoliday(new Holiday('childrensDay', ['en_US' => 'Children\'s Day', 'ja_JP' => '子供の日'],
                Carbon::create($this->year, 5, 5, 0, 0, 0, $this->timezone), $this->locale));
        }

        /**
         * Mountain Day. Mountain Day is held on August 11th and established since 2016.
         */
        if ($this->year >= 2016) {
            $this->addHoliday(new Holiday('mountainDay', ['en_US' => 'Mountain Day', 'ja_JP' => '山の日'],
                Carbon::create($this->year, 8, 11, 0, 0, 0, $this->timezone), $this->locale));
        }

        /**
         * Culture Day. Culture Day is held on November 11th and established since 1948.
         */
        if ($this->year >= 1948) {
            $this->addHoliday(new Holiday('cultureDay', ['en_US' => 'Culture Day', 'ja_JP' => '文化の日'],
                Carbon::create($this->year, 11, 3, 0, 0, 0, $this->timezone), $this->locale));
        }

        /**
         * Labor Thanksgiving Day. Labor Thanksgiving Day is held on November 23rd and established since 1948.
         */
        if ($this->year >= 1948) {
            $this->addHoliday(new Holiday('laborThanksgivingDay',
                ['en_US' => 'Labor Thanksgiving Day', 'ja_JP' => '勤労感謝の日'],
                Carbon::create($this->year, 11, 23, 0, 0, 0, $this->timezone), $this->locale));
        }

        /**
         * Emperors Birthday. The Emperors Birthday is on December 23rd and celebrated as such since 1989.
         * Prior to the death of Emperor Hirohito in 1989, this holiday was celebrated on April 29. See also "Shōwa Day".
         */
        if ($this->year >= 1948) {
            $this->addHoliday(new Holiday('emperorsBirthday', ['en_US' => 'Emperors Birthday', 'ja_JP' => '天皇誕生日'],
                Carbon::create($this->year, 12, 23, 0, 0, 0, $this->timezone), $this->locale));
        }

        $this->calculateVernalEquinoxDay();
        $this->calculateComingOfAgeDay();
        $this->calculateGreeneryDay();
        $this->calculateMarineDay();
        $this->calculateRespectForTheAgeDay();
        $this->calculateHealthAndSportsDay();
        $this->calculateAutumnalEquinoxDay();
        $this->calculateSubstituteHolidays();
    }

    /**
     * Calculate Vernal Equinox Day.
     *
     * This national holiday was established in 1948 as a day for the admiration
     * of nature and the love of living things. Prior to 1948, the vernal equinox was an imperial ancestor worship
     * festival called Shunki kōrei-sai (春季皇霊祭).
     *
     * @see http://www.h3.dion.ne.jp/~sakatsu/holiday_topic.htm (in Japanese)
     */
    private function calculateVernalEquinoxDay()
    {
        $day = null;
        if ($this->year < 1948) {
            $day = null;
        } elseif ($this->year >= 1948 && $this->year <= 1979) {
            $day = floor(self::VERNAL_EQUINOX_PARAM_1979 + self::EQUINOX_GRADIENT * ($this->year - 1980) - floor(($this->year - 1983) / 4));
        } elseif ($this->year <= 2099) {
            $day = floor(self::VERNAL_EQUINOX_PARAM_2099 + self::EQUINOX_GRADIENT * ($this->year - 1980) - floor(($this->year - 1980) / 4));
        } elseif ($this->year <= 2150) {
            $day = floor(self::VERNAL_EQUINOX_PARAM_2150 + self::EQUINOX_GRADIENT * ($this->year - 1980) - floor(($this->year - 1980) / 4));
        } elseif ($this->year > 2150) {
            $day = null;
        }

        if ( ! is_null($day)) {
            $this->addHoliday(new Holiday('vernalEquinoxDay', ['en_US' => 'Vernal Equinox Day', 'ja_JP' => '春分の日'],
                Carbon::create($this->year, 3, $day, 0, 0, 0, $this->timezone), $this->locale));
        }
    }

    /**
     * Calculate Coming of Age Day.
     *
     * Coming of Age Day was established after 1948 on January 15th. After 2000 it was changed to be the second monday
     * of January.
     */
    private function calculateComingOfAgeDay()
    {
        $date = null;
        if ($this->year >= 2000) {
            $date = new Carbon('second monday of january ' . $this->year);
        } elseif ($this->year >= 1948) {
            $date = Carbon::create($this->year, 1, 15, 0, 0, 0, $this->timezone);
        }
        if ( ! is_null($date)) {
            $this->addHoliday(new Holiday('comingOfAgeDay', ['en_US' => 'Coming of Age Day', 'ja_JP' => '成人の日'], $date,
                $this->locale));
        }
    }

    /**
     * Calculates Greenery Day.
     *
     * Greenery Day was established from 1989 on April 29th. After 2007 it was changed to be May 4th.
     */
    private function calculateGreeneryDay()
    {
        $date = null;
        if ($this->year >= 2007) {
            $date = Carbon::create($this->year, 5, 4, 0, 0, 0, $this->timezone);
        } elseif ($this->year >= 1989) {
            $date = Carbon::create($this->year, 4, 29, 0, 0, 0, $this->timezone);
        }
        if ( ! is_null($date)) {
            $this->addHoliday(new Holiday('greeneryDay', ['en_US' => 'Greenery Day', 'ja_JP' => '緑の日'], $date,
                $this->locale));
        }
    }

    /**
     * Calculates Marine Day.
     *
     * Marine Day was established since 1996 on July 20th. After 2003 it was changed to be the third monday of July.
     */
    private function calculateMarineDay()
    {
        $date = null;
        if ($this->year >= 2003) {
            $date = new Carbon('third monday of july ' . $this->year);
        } elseif ($this->year >= 1996) {
            $date = Carbon::create($this->year, 7, 20, 0, 0, 0, $this->timezone);
        }
        if ( ! is_null($date)) {
            $this->addHoliday(new Holiday('marineDay', ['en_US' => 'Marine Day', 'ja_JP' => '海の日'], $date,
                $this->locale));
        }
    }

    /**
     * Calculates Respect for the Age Day.
     *
     * Respect for the Age Day was established since 1996 on September 15th. After 2003 it was changed to be the third
     * monday of September.
     */
    private function calculateRespectForTheAgeDay()
    {
        $date = null;
        if ($this->year >= 2003) {
            $date = new Carbon('third monday of september ' . $this->year);
        } elseif ($this->year >= 1996) {
            $date = Carbon::create($this->year, 9, 15, 0, 0, 0, $this->timezone);
        }
        if ( ! is_null($date)) {
            $this->addHoliday(new Holiday('respectfortheAgedDay',
                ['en_US' => 'Respect for the Age Day', 'ja_JP' => '敬老の日'], $date, $this->locale));
        }
    }

    /**
     * Calculates Health And Sports Day.
     *
     * Health And Sports Day was established since 1966 on October 10th. After 2000 it was changed to be the second
     * monday of October.
     */
    private function calculateHealthAndSportsDay()
    {
        $date = null;
        if ($this->year >= 2000) {
            $date = new Carbon('second monday of october ' . $this->year);
        } elseif ($this->year >= 1996) {
            $date = Carbon::create($this->year, 10, 10, 0, 0, 0, $this->timezone);
        }
        if ( ! is_null($date)) {
            $this->addHoliday(new Holiday('healthandSportsDay', ['en_US' => 'Health And Sports Day', 'ja_JP' => '体育の日'],
                $date, $this->locale));
        }
    }

    /**
     * Calculate Autumnal Equinox Day.
     *
     * This national holiday was established in 1948 as a day on which to honor
     * one's ancestors and remember the dead. Prior to 1948, the autumnal equinox was an imperial ancestor worship
     * festival called Shūki kōrei-sai (秋季皇霊祭).
     *
     * @see http://www.h3.dion.ne.jp/~sakatsu/holiday_topic.htm (in Japanese)
     */
    private function calculateAutumnalEquinoxDay()
    {
        $day = null;
        if ($this->year < 1948) {
            $day = null;
        } elseif ($this->year >= 1948 && $this->year <= 1979) {
            $day = floor(self::AUTUMNAL_EQUINOX_PARAM_1979 + self::EQUINOX_GRADIENT * ($this->year - 1980) - floor(($this->year - 1983) / 4));
        } elseif ($this->year <= 2099) {
            $day = floor(self::AUTUMNAL_EQUINOX_PARAM_2099 + self::EQUINOX_GRADIENT * ($this->year - 1980) - floor(($this->year - 1980) / 4));
        } elseif ($this->year <= 2150) {
            $day = floor(self::AUTUMNAL_EQUINOX_PARAM_2150 + self::EQUINOX_GRADIENT * ($this->year - 1980) - floor(($this->year - 1980) / 4));
        } elseif ($this->year > 2150) {
            $day = null;
        }

        if ( ! is_null($day)) {
            $this->addHoliday(new Holiday('autumnalEquinoxDay', ['en_US' => 'Autumnal Equinox Day', 'ja_JP' => '秋分の日'],
                Carbon::create($this->year, 9, $day, 0, 0, 0, $this->timezone), $this->locale));
        }
    }

    /**
     * Calculate the substitute holidays.
     *
     * Generally if a national holiday falls on a Sunday, the holiday is observed the next working day (not being
     * another holiday).
     */
    private function calculateSubstituteHolidays()
    {
        // Get initial list of holiday dates
        $dates = $this->getHolidayDates();

        // Loop through all holidays
        foreach ($this->getHolidays() as $shortName => $date) {
            // Exclude these holidays from substitution
            if (in_array($shortName, ['vernalEquinoxDay', 'autumnalEquinoxDay'])) {
                continue;
            }

            // If holidays falls on a Sunday
            if ($date->dayOfWeek === 0) {
                if ($this->year >= 2007) {
                    // Find next week day (not being another holiday)
                    while (in_array($date, $dates)) {
                        $date = $date->addDay();
                        continue;
                    }
                } elseif ($date >= '1973-04-12') {
                    $date = $date->addDay();
                    if (in_array($date, $dates)) {
                        continue;
                    }
                } else {
                    continue;
                }

                // Add a new holiday that is substituting the original holiday
                if ( ! is_null($date)) {
                    $substituteHoliday = new Holiday('substituteHoliday:' . $shortName, [
                        'en_US' => $date->translations['en_US'] . ' Observed',
                        'ja_JP' => '振替休日 (' . $date->translations['ja_JP'] . ')'
                    ], $date, $this->locale);

                    $this->addHoliday($substituteHoliday);
                }
            }
        }
    }
}