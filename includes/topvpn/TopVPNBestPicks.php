<?php
require_once V_CORE_LIB . 'Public/PublicList.php';
require_once V_PLUGIN_INCLUDES_DIR . 'topvpn/Model/TopVPNModel.php';

class TopVPNBestPicks extends PublicList{

    protected array $bestVPN;

    public function __construct($model, $dbTable, array $atts = [])
    {
        parent::__construct($model, $dbTable, $atts);
    }

    public function init() : object{

        $this->switchMultiLangMode();
        $this->setLimitCount(1);
        $this->getBestRatingVPN('rating', 'DESC');
        $this->getBestRatingVPN('feautures_score', 'DESC');
        $this->getBestRatingVPN('value_for_money_score', 'DESC');
        $this->getBestRatingVPN('privacy_score', 'DESC');
        $this->getBestRatingVPN('user_score', 'DESC');
        return $this;
    }

    public function render() : string {

        $output = '';
        $output .= '<div class="features">';
        $output .= '<div class="container">';
        $output .= '<div class="row justify-content-center">
            <div class="col-lg-6">
                <div class="tt-section-heading text-center mt-4 mb-5">
                    <h2 class="">Our VPN Picks for 2023</h2>
                    <p class="tt-section-subhead">Here are January\'s <a href="/best-vpn/">best VPN services by category</a>, carefully tested and reviewed by our VPN experts.</p>
                </div>
            </div>
        </div>';
        $output .= '<div class="row d-flex justify-content-between">';
        /*---*/$output .= '<div class="col-lg-3 col-md-6">';
        /*------*/$output .= '<div class="feature">';
        /*---------*/ $output .= '<div class="entry-logo"><a href="' . $this->bestVPN['rating'][0]['vpn_sys_name'] . '/" alt="Logo"><img src="' . VPN_LOGO_PATH . $this->bestVPN['rating'][0]['vpn_logo'] . '" height="35px" alt="Logo"></a></div>';
        /*---------*/ $output .= '<h5>Best VPN by user rating</h5>';
        /*---------*/ // $output .= '<a class="tt-read-more" href="' . $this->bestVPN['rating'][0]['vpn_sys_name'] .'">Go To Review <i class="fa fa-arrow-right"></i></a>';
        /*---------*/ $output .= '<a class="btn btn-warning btn-xsm" href="' . $this->bestVPN['rating'][0]['vpn_sys_name'] .'" role="button">View more...</a>&nbsp';
        /*---------*/ $output .= '<a class="btn btn-tertiary btn-xsm" href="" role="button">Visit site</a>';
        /*------*/$output .= '</div>';
        /*---*/$output .= '</div>';

        /*---*/$output .= '<div class="col-lg-3 col-md-6">';
        /*------*/$output .= '<div class="feature">';
        /*---------*/ $output .= '<div class="entry-logo"><a href="' . $this->bestVPN['feautures_score'][0]['vpn_sys_name'] . '/" alt="Logo"><img src="' . VPN_LOGO_PATH . $this->bestVPN['feautures_score'][0]['vpn_logo'] . '" height="35px" alt="Logo"></a></div>';
        /*---------*/ $output .= '<h5>Best VPN by feature score</h5>';
        /*---------*/ // $output .= '<a class="tt-read-more" href="' . $this->bestVPN['feautures_score'][0]['vpn_sys_name'] .'">Go To Review <i class="fa fa-arrow-right"></i></a>';
        /*---------*/ $output .= '<a class="btn btn-warning btn-xsm" href="' . $this->bestVPN['feautures_score'][0]['vpn_sys_name'] .'" role="button">View more...</a>&nbsp';
        /*---------*/ $output .= '<a class="btn btn-tertiary btn-xsm" href="" role="button">Visit site</a>';
        /*------*/$output .= '</div>';
        /*---*/$output .= '</div>';

        /*---*/$output .= '<div class="col-lg-3 col-md-6">';
        /*------*/$output .= '<div class="feature">';
        /*---------*/ $output .= '<div class="entry-logo"><a href="' . $this->bestVPN['value_for_money_score'][0]['vpn_sys_name'] . '/" alt="Logo"><img src="' . VPN_LOGO_PATH . $this->bestVPN['value_for_money_score'][0]['vpn_logo'] . '" height="35px" alt="Logo"></a></div>';
        /*---------*/ $output .= '<h5>Best VPN by Value For Money</h5>';
        /*---------*/ // $output .= '<a class="tt-read-more" href="' . $this->bestVPN['value_for_money_score'][0]['vpn_sys_name'] .'">Go To Review <i class="fa fa-arrow-right"></i></a>';
        /*---------*/ $output .= '<a class="btn btn-warning btn-xsm" href="' . $this->bestVPN['value_for_money_score'][0]['vpn_sys_name'] .'" role="button">View more...</a>&nbsp';
        /*---------*/ $output .= '<a class="btn btn-tertiary btn-xsm" href="" role="button">Visit site</a>';
        /*------*/$output .= '</div>';
        /*---*/$output .= '</div>';

        /*---*/$output .= '<div class="col-lg-3 col-md-6">';
        /*------*/$output .= '<div class="feature">';
        /*---------*/ $output .= '<div class="entry-logo"><a href="' . $this->bestVPN['privacy_score'][0]['vpn_sys_name'] . '/" alt="Logo"><img src="' . VPN_LOGO_PATH . $this->bestVPN['privacy_score'][0]['vpn_logo'] . '" height="35px" alt="Logo"></a></div>';
        /*---------*/ $output .= '<h5>Best VPN by Privacy Score</h5>';
        /*---------*/ // $output .= '<a class="tt-read-more" href="' . $this->bestVPN['privacy_score'][0]['vpn_sys_name'] .'">Go To Review <i class="fa fa-arrow-right"></i></a>';
        /*---------*/ $output .= '<a class="btn btn-warning btn-xsm" href="' . $this->bestVPN['privacy_score'][0]['vpn_sys_name'] .'" role="button">View more...</a>&nbsp';
        /*---------*/ $output .= '<a class="btn btn-tertiary btn-xsm" href="" role="button">Visit site</a>';
        /*------*/$output .= '</div>';
        /*---*/$output .= '</div>';

        /*---*/$output .= '<div class="col-lg-3 col-md-6">';
        /*------*/$output .= '<div class="feature">';
        /*---------*/ $output .= '<div class="entry-logo"><a href="' . $this->bestVPN['user_score'][0]['vpn_sys_name'] . '/" alt="Logo"><img src="' . VPN_LOGO_PATH . $this->bestVPN['user_score'][0]['vpn_logo'] . '" height="35px" alt="Logo"></a></div>';
        /*---------*/ $output .= '<h5>Best VPN by User Score</h5>';
        /*---------*/ // $output .= '<a class="tt-read-more" href="' . $this->bestVPN['user_score'][0]['vpn_sys_name'] .'">Go To Review <i class="fa fa-arrow-right"></i></a>';
        /*---------*/ $output .= '<a class="btn btn-warning btn-xsm" href="' . $this->bestVPN['user_score'][0]['vpn_sys_name'] .'" role="button">View more...</a>&nbsp';
        /*---------*/ $output .= '<a class="btn btn-tertiary btn-xsm" href="" role="button">Visit site</a>';
        /*------*/$output .= '</div>';
        /*---*/$output .= '</div>';

        /*---*/$output .= '<div class="col-lg-3 col-md-6">';
        /*------*/$output .= '<div class="feature">';
        /*---------*/  $output .= $this->bestVPN['rating'][0]['vpn_name'];
        /*------*/$output .= '</div>';
        /*---*/$output .= '</div>';

        /*---*/$output .= '<div class="col-lg-3 col-md-6">';
        /*------*/$output .= '<div class="feature">';
        /*---------*/  $output .= $this->bestVPN['rating'][0]['vpn_name'];
        /*------*/$output .= '</div>';
        /*---*/$output .= '</div>';

        /*---*/$output .= '<div class="col-lg-3 col-md-6">';
        /*------*/$output .= '<div class="feature">';
        /*---------*/  $output .= $this->bestVPN['rating'][0]['vpn_name'];
        /*------*/$output .= '</div>';
        /*---*/$output .= '</div>';
        $output .= '</div>';
        $output .= '</div>';
        $output .= '</div>';
        $output .= '<div class="container">';
        $output .= '<div id="" class="">
                <div class="row justify-content-center pt-5 pb-5">
                <div class="text-center">
                    <a href="/top-10-vpn-rating/" class="btn btn-tertiary mx-auto toggle">
                    View All VPNs Comparison <i class="fa fa-arrow-right"></i>
                    </a>
                </div>
            </div>
            </div>
            </div>';


        $this->render = $output;
        return $output;
    }

    protected function getBestRatingVPN($column, $direction){

        $this->getModel()->setOrderColumn($column);
        $this->getModel()->setOrderDirection($direction);
        $this->bestVPN[$column] = $this->getModel()->getAllRows(true, false, true);
        return $this;
    }
}