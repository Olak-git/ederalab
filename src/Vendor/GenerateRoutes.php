<?php
namespace src\Vendor;

class GenerateRoutes
{
    private const PATH = '../src/Vendor/';

    public function files1()
    {
        $this->files('Files1');
    }

    public function files2()
    {
        $this->files('Files2');
    }

    private function files($class)
    {
        $file = 'src/Vendor/' . $class . '.php';
        $fp = fopen($file, 'w+');
        
        fputs($fp, '<?php'."\r\n");
        fputs($fp, 'namespace src\Vendor;'."\r\n\r\n");
        fputs($fp, 'class ' . $class ."\r\n");
        fputs($fp, '{'."\r\n");
        fputs($fp, '    public function get' . $class . '()' ."\r\n");
        fputs($fp, '    {' . "\r\n");
        fputs($fp, '        return [' . "\r\n");
        $dir = './';
        $dossier = opendir($dir);
        while($file = readdir($dossier)) {
            if($file !== '.' && $file !== '..' && strtolower(pathinfo($file, PATHINFO_EXTENSION)) == 'php')
            {
                fputs($fp, '            \'' . $file . '\',' ."\r\n");
            }
        }
        closedir($dossier);
        fputs($fp, '        ];' . "\r\n");
        fputs($fp, '    }' . "\r\n");
        fputs($fp, '}'."\r\n");
        fclose($fp);
    }

    public function routes()
    {
        $file = 'Routes.php';
        $fp = fopen(self::PATH . $file, 'w+');

        // $file = 'src/Vendor/Routes.php';
        // $fp = fopen($file, 'w+');
        fputs($fp, '<?php'."\r\n");
        fputs($fp, 'namespace src\Vendor;'."\r\n\r\n");
        fputs($fp, 'class Routes'."\r\n");
        fputs($fp, '{'."\r\n");
        fputs($fp, '    private const USE_ROUTE = true;'."\r\n\r\n");
        $dir = './';
        $dossier = opendir($dir);
        while($file = readdir($dossier)) {
            if($file !== '.' && 
                $file !== '..' && 
                    strtolower(pathinfo($file, PATHINFO_EXTENSION)) == 'php' && 
                        $file !== 'base.php' && 
                            $file !== 'autoload.php' && 
                                $file !== 'footer.php' && 
                                    $file !== 'async.php')
            {
                $gf = fopen($file, 'rb');
                // $line1 = fgets($gf);
                // $line2 = fgets($gf);
                // $line3 = fgets($gf);
                // $name = trim(str_replace('/', '', $line2));
                // $route = trim(str_replace('//', '', $line3));
                // fclose($gf);

                fseek($gf, strlen(fgets($gf)));
                $name = trim(str_replace('/', '', fgets($gf)));
                fclose($gf);

                if(preg_match('#^(name\s*:)#i', $name)) {
                    $name = preg_replace('#name\s*:\s*#', '', $name);
                    if($name !== '') {
                        $f = ucwords(str_replace(['.php', '-'], ['', ' '], $file));
                        $f = str_replace(' ', '', $f);
                        fputs($fp, '    public function ' . $name . '()' ."\r\n");
                        fputs($fp, '    {' . "\r\n");
                        fputs($fp, '        return \'' . $file . '\';' . "\r\n");
                        fputs($fp, '    }' . "\r\n\r\n");
                    }

                    // $route = preg_replace('#route\s*:\s*#', '', $route);
                    // if($name !== '') {
                    //     $f = ucwords(str_replace(['.php', '-'], ['', ' '], $file));
                    //     $f = str_replace(' ', '', $f);
                    //     fputs($fp, '    public function get_rewrite_' . $name . '()' ."\r\n");
                    //     fputs($fp, '    {' . "\r\n");
                    //     fputs($fp, '        return \'' . $route . '\';' . "\r\n");
                    //     fputs($fp, '    }' . "\r\n\r\n");
                    // }
                }
            }
        }
        closedir($dossier);

        fputs($fp, '    public function assets()' ."\r\n");
        fputs($fp, '    {' . "\r\n");
        fputs($fp, '        return "../assets";' . "\r\n");
        fputs($fp, '    }' . "\r\n");


        fputs($fp, '    public function path(string $name, array $param = null, string $route = null)' ."\r\n");
        fputs($fp, '    {' . "\r\n");
        fputs($fp, '        if(method_exists($this, $name))' . "\r\n");
        fputs($fp, '        {' . "\r\n");

        fputs($fp, '            if(self::USE_ROUTE && $route !== null) {' . "\r\n");
        fputs($fp, '                return $route;' . "\r\n");
        fputs($fp, '            } else {' . "\r\n");
        fputs($fp, '                $s = \'\';' . "\r\n");
        fputs($fp, '                if(null !== $param && !empty($param)) {' . "\r\n");
        fputs($fp, '                    $count = count($param) - 1;' . "\r\n");
        fputs($fp, '                    $ind = 0;' . "\r\n");
        fputs($fp, '                    $s .= \'?\';' . "\r\n");
        fputs($fp, '                    foreach($param as $k => $v) {' . "\r\n");
        fputs($fp, '                        $s .= $k . \'=\' . $v;' . "\r\n");
        fputs($fp, '                        if($ind < $count) {' . "\r\n");
        fputs($fp, '                            $s .= \'&amp;\';' . "\r\n");
        fputs($fp, '                        }' . "\r\n");
        fputs($fp, '                        $ind++;' . "\r\n");
        fputs($fp, '                    }' . "\r\n");

        fputs($fp, '                }' . "\r\n");
        fputs($fp, '                return $this->$name() . $s;' . "\r\n");
        fputs($fp, '            }' . "\r\n");

        fputs($fp, '        }' . "\r\n");
        fputs($fp, '    }' . "\r\n");

        fputs($fp, '}'."\r\n");
        fclose($fp);
    }
}