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

    public static function renderRateByType($rating, $type): string{

        if($type == 'main'){
            return self::renderRate($rating);
        }
        if($type == 'additional'){
            return self::renderRatingBar2($rating);
        }
        else return '';
    }

    public static function renderRate($posRev, $colorSlice = '#00a1ff'){

        $p = ($posRev / 100) * 100;
        $p = $p * 10;

        // $options = "data-pie='{\"percent\" : ".$p.", \"colorSlice\" : $colorSlice, \"time\": 50}'";
        $options = "data-pie='{\"percent\" : ".$p.", \"time\": 50}'";
        $output = '<div class="pie" '.$options.'></div>';
        return $output;
    }

    public static function renderAverageRate($posRev, $colorSlice = '#00a1ff'){

        $p = ($posRev / 100) * 100;
        $p = $p * 10;

        // $options = "data-pie='{\"percent\" : ".$p.", \"colorSlice\" : $colorSlice, \"time\": 50}'";
        $options = "data-pie='{\"percent\" : ".$p.", \"time\": 50, \"fontSize\" : \"2.5rem\", \"size\" : \"70\", \"stroke\" : \"8\", \"fontColor\" : \"#485151\"}'";
        $output = '<div class="pie-average" '.$options.'></div>';
        return $output;
    }

    public static function updatedAt(){
        $date = date("F, Y");
        return $date;
    }

    public static function renderCheckStatus( int $status) : string
    {
        if($status == 0){
          //  return '<i class="bi bi-x text-danger me-2"></i>';
            return '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-x text-danger" viewBox="0 0 16 16">
  <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z"/>
</svg>';
        }
        if($status == 1){
            return '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-check-lg text-success" viewBox="0 0 16 16">
  <path d="M12.736 3.97a.733.733 0 0 1 1.047 0c.286.289.29.756.01 1.05L7.88 12.01a.733.733 0 0 1-1.065.02L3.217 8.384a.757.757 0 0 1 0-1.06.733.733 0 0 1 1.047 0l3.052 3.093 5.4-6.425a.247.247 0 0 1 .02-.022Z"/>
</svg>';
        }
    }

    public static function renderFeatureIcon() : string
    {
        return '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-check-lg text-success" viewBox="0 0 16 16">
  <path d="M12.736 3.97a.733.733 0 0 1 1.047 0c.286.289.29.756.01 1.05L7.88 12.01a.733.733 0 0 1-1.065.02L3.217 8.384a.757.757 0 0 1 0-1.06.733.733 0 0 1 1.047 0l3.052 3.093 5.4-6.425a.247.247 0 0 1 .02-.022Z"/>
</svg>';

    }

    public static function renderRatingBar($rating){

        $output = '<span class="graph" data-value="'.$rating.'">
    <div class="graph__container">
    <span class="graph__item"></span>
    <span class="graph__item"></span>
    <span class="graph__item"></span>
    <span class="graph__item"></span>
    <span class="graph__item"></span>    
    </div>
    </span>';
       return $output;
    }

    public static function renderRatingBar2($rating){
        // $rating = $rating * 10;
        $p = self::getRating($rating);
        $colorStyle = 'bg-rating-bar-green';
        if($p < 90){
            $colorStyle = 'bg-warning';
        }
        if($p < 50){
            $colorStyle = 'bg-danger';
        }
        $output = '<div class="progress">
        <div class="progress-bar '.$colorStyle.'" role="progressbar" style="width: '.$p.'%" aria-valuenow="'.$p.'" aria-valuemin="0" aria-valuemax="100"></div>
        </div>';
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