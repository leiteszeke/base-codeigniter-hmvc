<?php
    class StringsHelper {
        private $ci;

        public function __construct() {
            $this->ci   =& get_instance();
        }

        public function quitarTildes($string){
            $str = $string;

            $str = str_replace('á', 'a', $str);
            $str = str_replace('é', 'e', $str);
            $str = str_replace('í', 'i', $str);
            $str = str_replace('ó', 'o', $str);
            $str = str_replace('ú', 'u', $str);
            $str = str_replace('Á', 'A', $str);
            $str = str_replace('É', 'E', $str);
            $str = str_replace('Í', 'I', $str);
            $str = str_replace('Ó', 'O', $str);
            $str = str_replace('Ú', 'U', $str);

            return $str;
        }
    }