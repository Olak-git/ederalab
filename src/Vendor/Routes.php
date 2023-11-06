<?php
namespace src\Vendor;

class Routes
{
    private const USE_ROUTE = true;

    public function home()
    {
        return 'index.php';
    }

    public function assets()
    {
        return "../assets";
    }
    public function path(string $name, array $param = null, string $route = null)
    {
        if(method_exists($this, $name))
        {
            if(self::USE_ROUTE && $route !== null) {
                return $route;
            } else {
                $s = '';
                if(null !== $param && !empty($param)) {
                    $count = count($param) - 1;
                    $ind = 0;
                    $s .= '?';
                    foreach($param as $k => $v) {
                        $s .= $k . '=' . $v;
                        if($ind < $count) {
                            $s .= '&amp;';
                        }
                        $ind++;
                    }
                }
                return $this->$name() . $s;
            }
        }
    }
}
