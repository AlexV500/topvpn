<?php

require_once V_PLUGIN_INCLUDES_DIR . 'topvpn/Model/TopVPNModel.php';
require_once V_PLUGIN_INCLUDES_DIR . 'os/Model/OSModel.php';
require_once V_CORE_LIB . 'View/HTMLOutputs.php';
require_once V_CORE_LIB . 'Public/PublicList.php';

class TopVPNPublicList extends PublicList{

    protected object $osModel;
    protected int $showTrigger = 0;
    protected int $showCount = 5;

    public function __construct($model, $dbTable)
    {
        parent::__construct($model, $dbTable);
    }

    public function init( array $atts = []) : object{

        $this->switchMultiLangMode($atts);
        $this->setOrderColumn('position');
        $this->setOrderDirection('ASC');
        $this->initRowsCount($this->activeMode);
        $this->setPaginationCount(8);
        $this->initPaginationConfig();
        $this->initRowsData($this->activeMode);
        $this->osModel = new OSModel('topvpn_os');
        return $this;
    }

    public function render() : string {
        $output = '';
        $logoPath = V_CORE_URL .'includes/images/vpn';
        $OSLogoPath = V_CORE_URL .'includes/images/os';
        $count = count($this->getRowsData());
        $output .= '<div class="">';
        if ($count > 0) {
            for ($i = 0; $i < $count; $i++) {
                $result = $this->getRowsData()[$i];
                $logo = $logoPath .'/'. $result['vpn_logo'];
                $osSystems = $this->osModel->getOSByVPNId($result['id']);
                $pos = $i + 1;
                $output .= '';
                if ($i == $this->showCount) {
                    $this->showTrigger = 1;
                    //    $output .= '<div id="theDIV" style="display: none;">';
                    $output .= '<div class="box">';
                }
                $output .= '<div class="rating-info d-block no-gutters mt-4 pt-4 pb-3 pl-2 pr-2 p-lg-4 list list-'.$pos.' pm-1">';
                //TOP STATUS
                $output .= $this->renderTopStatus($pos, $result['top_status_description']);

                //TOP STATUS END

                $output .= '<div class="row">';


                /**/$output .='';/**/

//                /*---*/$output .='<div class="col-auto text-center align-center d-none d-lg-flex flex-column justify-content-center">';
//                /*------*/$output .='<div class="arating">'.$pos.'</div>';
//                /*---*/$output .='</div>';

                /*---*/$output .='<div class="col">';
                /*------*/$output .='<div class="row">';
                /*---------*/$output .= '<div class="col-9 col-sm-9 column-1 logo-holder d-flex flex-column justify-content-top">';
                /*------------*/$output .='<span><img alt="' . $result['vpn_name'] . '" class="img-fluid max-240" src="' . $logo . '" alt="' . $result['vpn_name'] . '" title="' . $result['vpn_name'] . '"></span>';
                /*------------*/$output .='<p class="new_product_description d-none d-md-block mb-0 mr-lg-1 mr-xl-0 mt-2">';
                /*------------*/$output .=$result['short_description'];
                /*------------*/$output .='</p>';
                /*---------*/$output .='</div>';


                /*---------*/$output .='<div class="col-9 col-sm-9 column-2 features-holder mt-3 mt-md-0 mb-4 mb-md-0 pl-2 pt-lg-2 pt-xl-0 text-left">';
                /*------------*/$output .= $this->renderFeatures($result['features']);


                /*------------*/$output .='<div class="mt-3">';
                /*---------------*/$output .= '<span class="available-on-text">Available on:</span>';
                /*---------------*/foreach ((array)$osSystems as $y => $os) {
                    /*------------------*/$osLogo = $OSLogoPath .'/'. $os['os_logo'];
                    /*------------------*/$output .= '<img data-toggle="tooltip" src="' . $osLogo . '" alt="' . $os['os_name'] . '" title="' . $os['os_name'] . '" data-original-title="' . $os['os_name'] . '" class="os-icons-small"> ';
                    /*---------------*/}

                /*------------*/$output .= '</div>';

                if(trim($result['verdict'] !== '')){
                    /*---------*/$output .= '<div class="mt-3"><img class="padli" src="'.$this->imgPath.'/user-shield-24.png"> <span class="usinglink">'.$result['verdict'].'</span>';
                    /*---------*/$output .= '</div>';
                }

                /*---------*/$output .='</div>';
                /*---------*/$output .='<div class="col-6 col-sm-6 column-3 rating-info-holder d-flex flex-column justify-content-center">';
                /*------------*/$output .= '<div class="wss-scoreRow-772925660"><div class="wss-multiScoreText-3728111154">Privacy</div>
                                            <div class="wss-rt_container-1819568874">
                                            
                                            '.HTMLOutputs::renderRate(9.5).'
                                            </div>
                                            </div>
                                            
                                            <div class="wss-scoreRow-772925660">
                                            <div class="wss-multiScoreText-3728111154">Features</div>
                                            <div class="wss-rt_container-1819568874"> 
                                            '.HTMLOutputs::renderRate(9.8).'
                                            </div>
                                            </div>
                                            
                                            <div class="wss-scoreRow-772925660">
                                            <div class="wss-multiScoreText-3728111154">Value for money</div>
                                            <div class="wss-rt_container-1819568874">
                                            '.HTMLOutputs::renderRate(9.5).'
                                            </div>
                                            </div>
                                            
                                            <div class="wss-scoreRow-772925660">
                                            <div class="wss-multiScoreText-3728111154">User Score</div>
                                            <div class="wss-rt_container-1819568874">                                           
                                            
                                            '.HTMLOutputs::renderRate(9.9).'
                                            </div>
                                            </div>';
                /*---------*/$output .='</div>';
                /*---------*/$output .='<div class="col-6 col-sm-12 column-4 price-holder d-flex flex-column justify-content-center align-items-center text-center">';
                /*------------*/$output .='<div class="d-score">';
                /*---------------*/$output .= $result['rating'];
                /*------------*/$output .='</div>';
                /*------------*/$output .='<div class="d-rating">';
                /*---------------*/$output .='<span>'.$result['rating_description'].'</span>';
                /*------------*/$output .='</div>';
                /*------------*/$output .='<div class="pt-2 d-stars">';
                /*---------------*/$output .='<div class="star-ratings-sprite zoom8">';
                /*------------------*/$output .= $this->getRating($result['rating'], 0);
                /*---------------*/$output .='</div>';
                /*------------*/$output .='</div>';
                /*------------*/$output .='<div class="mb-3 prices">';
                /*---------------*/$output .='<span class="price"><span class="font-12">From</span> '.$result['price'].'&nbsp;USD</span>';
                /*------------------*/$output .='<button class="btn btn-danger margin-0-auto">Visit Website</button>';
                /*------------*/$output .='</div>';
                /*---------*/$output .='</div>';
                /*------*/$output .= '</div>';
                /*---*/$output .='</div>';

                $output .= '</div>';
                $output .= '</div>';

            }
            if($this->showTrigger == 1){
                $output .= '</div>';
            }
            $output .= '</div>';

            if($count > $this->showCount){
                $output .= '<div id="other-brokers-button-down" class="pt-5">';
                $output .= '<div class="row justify-content-center">
                <div class="text-center">
                    <button class="btn btn-tertiary mx-auto toggle"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-down" viewBox="0 0 16 16">
  <path fill-rule="evenodd" d="M8 1a.5.5 0 0 1 .5.5v11.793l3.146-3.147a.5.5 0 0 1 .708.708l-4 4a.5.5 0 0 1-.708 0l-4-4a.5.5 0 0 1 .708-.708L7.5 13.293V1.5A.5.5 0 0 1 8 1z"></path>
</svg><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-down" viewBox="0 0 16 16">
  <path fill-rule="evenodd" d="M8 1a.5.5 0 0 1 .5.5v11.793l3.146-3.147a.5.5 0 0 1 .708.708l-4 4a.5.5 0 0 1-.708 0l-4-4a.5.5 0 0 1 .708-.708L7.5 13.293V1.5A.5.5 0 0 1 8 1z"></path>
</svg><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-down" viewBox="0 0 16 16">
  <path fill-rule="evenodd" d="M8 1a.5.5 0 0 1 .5.5v11.793l3.146-3.147a.5.5 0 0 1 .708.708l-4 4a.5.5 0 0 1-.708 0l-4-4a.5.5 0 0 1 .708-.708L7.5 13.293V1.5A.5.5 0 0 1 8 1z"></path>
</svg>'.__('Other VPN').'<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-down" viewBox="0 0 16 16">
  <path fill-rule="evenodd" d="M8 1a.5.5 0 0 1 .5.5v11.793l3.146-3.147a.5.5 0 0 1 .708.708l-4 4a.5.5 0 0 1-.708 0l-4-4a.5.5 0 0 1 .708-.708L7.5 13.293V1.5A.5.5 0 0 1 8 1z"></path>
</svg><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-down" viewBox="0 0 16 16">
  <path fill-rule="evenodd" d="M8 1a.5.5 0 0 1 .5.5v11.793l3.146-3.147a.5.5 0 0 1 .708.708l-4 4a.5.5 0 0 1-.708 0l-4-4a.5.5 0 0 1 .708-.708L7.5 13.293V1.5A.5.5 0 0 1 8 1z"></path>
</svg><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-down" viewBox="0 0 16 16">
  <path fill-rule="evenodd" d="M8 1a.5.5 0 0 1 .5.5v11.793l3.146-3.147a.5.5 0 0 1 .708.708l-4 4a.5.5 0 0 1-.708 0l-4-4a.5.5 0 0 1 .708-.708L7.5 13.293V1.5A.5.5 0 0 1 8 1z"></path>
</svg></button>
                </div>
            </div>
            </div><br/>';


                $output .= '<div id="other-brokers-button-up" class="pt-5">';
                $output .= '<div class="row justify-content-center">
                <div class="text-center">
                    <button class="btn btn-tertiary mx-auto toggle"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-up" viewBox="0 0 16 16">
  <path fill-rule="evenodd" d="M8 15a.5.5 0 0 0 .5-.5V2.707l3.146 3.147a.5.5 0 0 0 .708-.708l-4-4a.5.5 0 0 0-.708 0l-4 4a.5.5 0 1 0 .708.708L7.5 2.707V14.5a.5.5 0 0 0 .5.5z"/>
</svg><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-up" viewBox="0 0 16 16">
  <path fill-rule="evenodd" d="M8 15a.5.5 0 0 0 .5-.5V2.707l3.146 3.147a.5.5 0 0 0 .708-.708l-4-4a.5.5 0 0 0-.708 0l-4 4a.5.5 0 1 0 .708.708L7.5 2.707V14.5a.5.5 0 0 0 .5.5z"/>
</svg><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-up" viewBox="0 0 16 16">
  <path fill-rule="evenodd" d="M8 15a.5.5 0 0 0 .5-.5V2.707l3.146 3.147a.5.5 0 0 0 .708-.708l-4-4a.5.5 0 0 0-.708 0l-4 4a.5.5 0 1 0 .708.708L7.5 2.707V14.5a.5.5 0 0 0 .5.5z"/>
</svg>'.__('Hide Other VPN').'<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-up" viewBox="0 0 16 16">
  <path fill-rule="evenodd" d="M8 15a.5.5 0 0 0 .5-.5V2.707l3.146 3.147a.5.5 0 0 0 .708-.708l-4-4a.5.5 0 0 0-.708 0l-4 4a.5.5 0 1 0 .708.708L7.5 2.707V14.5a.5.5 0 0 0 .5.5z"/>
</svg><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-up" viewBox="0 0 16 16">
  <path fill-rule="evenodd" d="M8 15a.5.5 0 0 0 .5-.5V2.707l3.146 3.147a.5.5 0 0 0 .708-.708l-4-4a.5.5 0 0 0-.708 0l-4 4a.5.5 0 1 0 .708.708L7.5 2.707V14.5a.5.5 0 0 0 .5.5z"/>
</svg><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-up" viewBox="0 0 16 16">
  <path fill-rule="evenodd" d="M8 15a.5.5 0 0 0 .5-.5V2.707l3.146 3.147a.5.5 0 0 0 .708-.708l-4-4a.5.5 0 0 0-.708 0l-4 4a.5.5 0 1 0 .708.708L7.5 2.707V14.5a.5.5 0 0 0 .5.5z"/>
</svg></button>
                </div>
            </div>
            </div><br/>';
            }
        }
        $this->render = $output;
        return $output;
    }

    protected function renderFeatures(string $features) : string{

        $output = '';
        $exploded = explode(';', $features);
//        echo '<pre>';
//        print_r($exploded);
//        echo '</pre>';
        if(count($exploded) == 0){
            return $output;
        }
        $output .= '<ul class="features">';
        for ($i = 0; $i < (count($exploded) - 1); $i++) {
            $y = $i;
            $string = $exploded[$i];
            $substrCount = strspn($string,'[red]');
            if($substrCount == 5){
                $string = substr($string, 5);
            } else {
                $y = $y + 1;
            }
            $output .= '<li class="feature-'.$y.'"><img class="padli s16x16" src="'.$this->imgPath.'/tick-3.png">';
            $output .= $string;
            $output .= '</li>';
        }
        $output .= '</ul>';
        return $output;
    }

    protected function renderRate(){

        $options = "data-pie='{\"percent\" : 75}'";
        $output = '<div class="pie" '.$options.'></div>';
        return $output;
    }

    protected function renderTopStatus( int $pos, string $statusDescr) : string{

        $output = '';
        $status = '';
        $class = '';
        $ribbonClass = '';
        if(trim($statusDescr) !== ''){
            $status = '<span class="status-descr">'. $statusDescr .'</span>';
        }
        if($pos <= 3) {
            $class = 'vendor-'.$pos;
            $ribbonClass = 'ribbon-number-'.$pos;
        } else {
            $class = 'vendor-';
            $ribbonClass = 'ribbon-number';
        }
        $output .= '<div class="d-none d-md-block text-left">';
        $output .= '<div class="'.$class.'">';
        $output .= '<div class="vendor__position">' . $pos . $status.'</div>';
        $output .= '</div>';

//        if(trim($statusDescr) !== '') {
//            $output .= '<div class="'.$ribbonClass.'"><h2>' . $statusDescr . '</h2></div>';
//        }
        $output .= '</div>';
        return $output;
    }

    protected function getRating($posRev, $negRev)
    {
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