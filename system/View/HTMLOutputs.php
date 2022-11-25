<?php


class HTMLOutputs{


    protected static function getRating($posRev, $negRev = 0){

        $posRev = $posRev * 10;
        //  echo $posRev.'<br/>';
        if (($posRev > 0) && ($negRev > 0)) {
            if ($posRev == $negRev) {
                $p = 50;
            }
        }

        if (($posRev == 0) && ($negRev == 0)) {

            $p = 0;
        }

        if (($posRev > 0) && ($negRev == 0)) {

            $p = ((int)$posRev / 100) * 100;
        }

        return $p;
    }

    public static function renderRate($posRev, $negRev = 0){


        $p = ($posRev / 100) * 100;
        $p = $p * 10;

        $options = "data-pie='{\"percent\" : ".$p.", \"time\": 50}'";
        $output = '<div class="pie" '.$options.'></div>';
        return $output;
    }


    public static function renderRating($posRev, $negRev) : string
    {
        $p = self::getRating($posRev, $negRev);

        $output = '<div class="rating">
      <div class="stars">
        <div class="on" style="width: ' . $p . '%;"></div>
          <div class="live">
            <span data-rate="1"></span>
            <span data-rate="2"></span>
            <span data-rate="3"></span>
            <span data-rate="4"></span>
            <span data-rate="5"></span>
          </div>
        </div>
      </div>';
        return $output;
    }
}