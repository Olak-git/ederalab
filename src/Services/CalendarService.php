<?php
namespace src\Services;

use src\traits\Hydrate;

class CalendarService
{
    use Hydrate;

    /**
     * @var string
     */
    private $year;

    /**
     * @var string
     */
    private $month;

    /**
     * @var array
     */
    private $weeks;

    public function __construct(string $year, string $month)
    {
        $year = (int)$year < 1960 ? '1960' : $year;
        $this->hydrate(['year' => $year, 'month' => $month]);
        $this->weeks = [];
        $this->setWeeks();
    }

    public function setYear(string $y): self
    {
        $this->year = $y;

        return $this;
    }

    public function getYear(): string
    {
        return $this->year;
    }

    public function setMonth(string $m): self
    {
        $this->month = $m;

        return $this;
    }

    public function getMonth(): string
    {
        return $this->month;
    }

    public function setWeeks(): self
    {
        $date = (new \DateTime($this->year . '-' . $this->month . '-01'));
        $fin = $date->format('t');

        $ind = 0;
        $new_round = true;
        for($a = 1; $a <= $fin; $a++) {
            $w = (new \DateTime($this->year . '-' . $this->month . '-' . $a))->format('w');
            if($new_round) {
                if($w > 1) {
                    for($b = 1; $b < $w; $b++) {
                        if(!isset($this->weeks[$ind][$b])) {
                            $this->weeks[$ind][$b] = null;
                        }
                    }
                }
                $new_round = false;
            }
            $this->weeks[$ind][$w] = $a < 10 ? '0' . $a : $a;
            if($w == 0) {
                $new_round = true;
                $ind++;
            }
        }

        return $this;
    }

    public function getWeeks(): array
    {
        $this->boor();
        return $this->weeks;
    }

    private function boor()
    {
        $first = $this->weeks[0];
        $lats_key = $this->getLastKey($first);
        if(count($first) == 1 && $lats_key == 0) {
            $this->weeks[0] = [
                1 => null,
                2 => null,
                3 => null,
                4 => null,
                5 => null,
                6 => null,
                0 => $first[0]
            ];
        }

        $last = array_pop($this->weeks);
        $lats_key = $this->getLastKey($last);

        if($lats_key > 0) {
            for($j = $lats_key + 1; $j <= 6; $j++) {
                $last[$j] = null;
            }
            $last[0] = null;
        }
        $this->weeks[] = $last;
    }
    
    private function getLastKey(array $arr)
    {
        $keys = array_keys($arr);
        return $keys[count($keys) - 1];
    }

    public function getNextYear()
    {
        if($this->month == 12) {
            return $this->year + 1;
        }
        return $this->year;
    }

    public function getNextMonth()
    {
        if($this->month < 12) {
            return $this->month + 1;
        } else {
            return 1;
        }
        return $this->month;
    }

    public function getPreviousYear()
    {
        if($this->month == 1) {
            return $this->year <= 1960 ? $this->year : $this->year - 1;
        }
        return $this->year;
    }

    public function getPreviousMonth()
    {
        if($this->month > 1) {
            return $this->month - 1;
        } else {
            return 12;
        }
        return $this->month;
    }

    public function getDate(string $day)
    {
        $day = (int)$day;
        $month = (int)$this->month;

        return $this->year . '-' . ($month < 10 ? '0' . $month : $month) . '-' . ($day < 10 ? '0' . $day : $day);
    }
}