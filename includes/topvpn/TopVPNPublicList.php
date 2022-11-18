<?php

require_once V_PLUGIN_INCLUDES_DIR . 'topvpn/Model/TopVPNModel.php';
require_once V_PLUGIN_INCLUDES_DIR . 'os/Model/OSModel.php';
require_once V_CORE_LIB . 'Public/PublicList.php';

class TopVPNPublicList extends PublicList{

    protected object $osModel;

    public function __construct($model, $dbTable)
    {
        parent::__construct($model, $dbTable);
    }

    public function init( array $atts = []) : object{

        $this->switchMultiLangMode($atts);
        $this->setOrderColumn('position');
        $this->setOrderDirection('ASC');
        $this->initRowsCount($this->activeMode);
        $this->setPaginationCount();
        $this->initPaginationConfig();
        $this->initRowsData($this->activeMode);
        $this->osModel = new OSModel('topvpn_os');
        return $this;
    }

    public function render() : string {
        $output = 'Test';
        $logoPath = V_CORE_URL .'includes/images/vpn';
        $OSLogoPath = V_CORE_URL .'includes/images/os';
        $output .= '<div class="mg-posts-sec-inner">';
        if (count($this->getRowsData()) > 0) {
            for ($i = 0; $i < count($this->getRowsData()); $i++) {
                $result = $this->getRowsData()[$i];
                $logo = $logoPath .'/'. $result['vpn_logo'];
                $osSystems = $this->osModel->getOSByVPNId($result['id']);
                $output .= '<div class="row">';
                $output .= /**/'<div class="mg-posts-sec-post">';

                /**/$output .='';/**/

                /*---*/$output .='<div class="col-auto text-center align-center d-none d-lg-flex flex-column justify-content-center">';
                /*------*/$output .='<div class="arating"><img class="img-fluid" src="images/up.png">1</div>';
                /*---*/$output .='</div>';

                /*---*/$output .='<div class="col">';
                /*------*/$output .='<div class="row">';
                /*---------*/$output .= '<div class="col-12 col-sm-12 col-lg-3 text-center logo-holder align-center d-flex flex-column justify-content-center">';
                /*------------*/$output .='<span><img alt="' . $result['vpn_name'] . '" class="img-fluid max-240" src="' . $logo . '" alt="' . $result['vpn_name'] . '" title="' . $result['vpn_name'] . '"></span>';
                /*---------*/$output .='</div>';


                /*---------*/$output .='<div class="col-12 col-sm-12 col-lg-5 features-holder mt-3 mt-md-0 mb-4 mb-md-0 text-left">';
                /*------------*/$output .= $this->renderFeatures($result['features']);
                /*---------*/$output .='</div>';

                /*---------*/$output .='<div class="mt-3">';
                /*------------*/$output .= '<span class="available-on-text">Available on:</span>';
                /*------------*/foreach ((array)$osSystems as $y => $os) {
                    /*---------------*/$osLogo = $OSLogoPath .'/'. $os['os_logo'];
                    /*---------------*/$output .= '<img data-toggle="tooltip" src="' . $osLogo . '" alt="' . $os['os_name'] . '" title="' . $os['os_name'] . '" data-original-title="' . $os['os_name'] . '" class="os-icons-small"> ';
                    /*------------*/}

                /*---------*/$output .= '</div>';

                if(trim($result['verdict'] !== '')){
                    /*---------*/$output .= '<div class="mt-3"><img class="padli" src="pics/user-shield-24.png"> <span class="usinglink">'.$result['verdict'].'</span>';
                    /*---------*/$output .= '</div>';
                }

                /*---------*/$output .='<div class="col-6 col-sm-12 col-lg-2 rating-info-holder d-flex flex-column justify-content-center align-items-center text-center">';
                /*------------*/$output .='<div class="d-score">';
                /*---------------*/$output .= $result['rating'];
                /*------------*/$output .='</div>';
                /*------------*/$output .='<div class="d-rating">';
                /*---------------*/$output .='<span>Best Overall</span>';
                /*------------*/$output .='</div>';
                /*------------*/$output .='<div class="pt-2 d-stars">';
                /*---------------*/$output .='<div class="star-ratings-sprite zoom8">';
                /*------------------*/$output .= $this->getRating($result['rating'], 0);
                /*---------------*/$output .='</div>';
                /*------------*/$output .='</div>';
                /*---------*/$output .='</div>';
                /*---------*/$output .='<div class="col-6 col-sm-12 col-lg-2 price-holder d-flex flex-column justify-content-center align-items-center text-center">';
                /*------------*/$output .='<div class="mb-3 prices">';
                /*---------------*/$output .='<span class="price"><span class="font-12">From</span> '.$result['price'].'&nbsp;USD</span>';
                /*------------------*/$output .='<button class="btn btn-visit margin-0-auto">Visit Website</button>';
                /*------------*/$output .='</div>';
                /*---------*/$output .='</div>';
                /*------*/$output .= '</div>';
                /*---*/$output .='</div>';
                /**/$output .= '</div>';
                $output .= '</div>';
            }
            $output .= '</div>';
        }
        $this->render = $output;
        return $output;
    }

    protected function renderFeatures(string $features) : string{

        $output = '';
        $exploded = explode(';', $features);
        if(count($exploded) == 0){
            return $output;
        }
        $output .= '<ul class="features">';
        for ($i = 0; $i < count($exploded); $i++) {
            $y = $i;
            $string = $exploded[$i];
            $substrCount = strspn($string,'[red]');
            if($substrCount == 5){
                $string = substr($string, 5);
            } else {
                $y = $y + 1;
            }
            $output .= '<li class="feature-'.$y.'"><img class="padli s16x16" src="pics/tick-3.png">';
            $output .= $string;
            $output .= '</li>';
        }
        $output .= '</ul>';
        return $output;
    }

    protected function getRating($posRev, $negRev)
    {
        $posRev = $posRev * 100;
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

        return $this->renderRating($p);
    }

    protected function renderRating($p) : string
    {
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