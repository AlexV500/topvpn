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
        $this->getBestRatingVPN('overall_speed', 'DESC');
        $this->getBestRatingVPN('streaming_rate', 'DESC');
        $this->getBestRatingVPN('torrenting_rate', 'DESC');
        $this->getBestRatingVPN('gaming_rate', 'DESC');
        return $this;
    }

    public function render() : string {

        $output = '';
        $colorStyleH5 = 'color: rgba(217,55,64,0.88)';
    //    $colorStyleH5 = 'color: rgba(6,65,171,0.85)';
        $output .= '<div class="features">';
        $output .= '<div class="container">';
        $output .= '<div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="tt-section-heading text-center mt-4 mb-5">
                    <h2 class="">Our VPN Picks for 2023</h2>
                    <p class="tt-section-subhead">The best VPN services are presented here for various evaluation criteria</p>
                    <div class="popup" onclick="togglePopup(\'disclaimerPopup\')">Advertiser Disclosure
  <span class="popuptext" id="disclaimerPopup">To keep Top10VPN a free online resource, we receive advertising/referral fees when you buy a VPN through outlinks on this page. This impacts the score, location, prominence and order in which a VPN service appears. Our extensive tests of each VPN, and how it compares with other VPNs in different countries and/or for specific purposes, are also factored in. We do not feature every VPN product on the market. Listings on this page do not imply endorsement. To learn more, see</span>
</div>
                </div>
            </div>
        </div>';
        $output .= '<div class="row d-flex justify-content-between">';
        /*---*/$output .= '<div class="col-lg-3 col-md-6">';
        /*------*/$output .= '<div class="feature">';
        /*---------*/ $output .= '<div class="entry-logo"><a href="' . $this->bestVPN['rating'][0]['vpn_sys_name'] . '/" alt="Logo"><img src="' . VPN_LOGO_PATH . $this->bestVPN['rating'][0]['vpn_logo'] . '" height="35px" alt="Logo"></a></div>';
        /*---------*/ $output .= '<h5>Best VPN by <span style="'.$colorStyleH5.'">Average Rating</span></h5>';
        /*---------*/ // $output .= '<a class="tt-read-more" href="' . $this->bestVPN['rating'][0]['vpn_sys_name'] .'">Go To Review <i class="fa fa-arrow-right"></i></a>';
        /*---------*/ $output .= '<a class="btn btn-warning btn-xsm" href="' . $this->bestVPN['rating'][0]['vpn_sys_name'] .'" role="button">View more...</a>&nbsp';
        /*---------*/ $output .= '<a class="btn btn-tertiary btn-xsm" href="" role="button">Visit site</a>';
        /*------*/$output .= '</div>';
        /*---*/$output .= '</div>';

        /*---*/$output .= '<div class="col-lg-3 col-md-6">';
        /*------*/$output .= '<div class="feature">';
        /*---------*/ $output .= '<div class="entry-logo"><a href="' . $this->bestVPN['overall_speed'][0]['vpn_sys_name'] . '/" alt="Logo"><img src="' . VPN_LOGO_PATH . $this->bestVPN['overall_speed'][0]['vpn_logo'] . '" height="35px" alt="Logo"></a></div>';
        /*---------*/ $output .= '<h5>Best VPN by <span style="'.$colorStyleH5.'">Overall Speed</span></h5>';
        /*---------*/ // $output .= '<a class="tt-read-more" href="' . $this->bestVPN['user_score'][0]['vpn_sys_name'] .'">Go To Review <i class="fa fa-arrow-right"></i></a>';
        /*---------*/ $output .= '<a class="btn btn-warning btn-xsm" href="' . $this->bestVPN['overall_speed'][0]['vpn_sys_name'] .'" role="button">View more...</a>&nbsp';
        /*---------*/ $output .= '<a class="btn btn-tertiary btn-xsm" href="" role="button">Visit site</a>';
        /*------*/$output .= '</div>';
        /*---*/$output .= '</div>';

        /*---*/$output .= '<div class="col-lg-3 col-md-6">';
        /*------*/$output .= '<div class="feature">';
        /*---------*/ $output .= '<div class="entry-logo"><a href="' . $this->bestVPN['feautures_score'][0]['vpn_sys_name'] . '/" alt="Logo"><img src="' . VPN_LOGO_PATH . $this->bestVPN['feautures_score'][0]['vpn_logo'] . '" height="35px" alt="Logo"></a></div>';
        /*---------*/ $output .= '<h5>Best VPN by <span style="'.$colorStyleH5.'">Feature Score</span></h5>';
        /*---------*/ // $output .= '<a class="tt-read-more" href="' . $this->bestVPN['feautures_score'][0]['vpn_sys_name'] .'">Go To Review <i class="fa fa-arrow-right"></i></a>';
        /*---------*/ $output .= '<a class="btn btn-warning btn-xsm" href="' . $this->bestVPN['feautures_score'][0]['vpn_sys_name'] .'" role="button">View more...</a>&nbsp';
        /*---------*/ $output .= '<a class="btn btn-tertiary btn-xsm" href="" role="button">Visit site</a>';
        /*------*/$output .= '</div>';
        /*---*/$output .= '</div>';

        /*---*/$output .= '<div class="col-lg-3 col-md-6">';
        /*------*/$output .= '<div class="feature">';
        /*---------*/ $output .= '<div class="entry-logo"><a href="' . $this->bestVPN['privacy_score'][0]['vpn_sys_name'] . '/" alt="Logo"><img src="' . VPN_LOGO_PATH . $this->bestVPN['privacy_score'][0]['vpn_logo'] . '" height="35px" alt="Logo"></a></div>';
        /*---------*/ $output .= '<h5>Best VPN by <span style="'.$colorStyleH5.'">Privacy Score</span></h5>';
        /*---------*/ // $output .= '<a class="tt-read-more" href="' . $this->bestVPN['privacy_score'][0]['vpn_sys_name'] .'">Go To Review <i class="fa fa-arrow-right"></i></a>';
        /*---------*/ $output .= '<a class="btn btn-warning btn-xsm" href="' . $this->bestVPN['privacy_score'][0]['vpn_sys_name'] .'" role="button">View more...</a>&nbsp';
        /*---------*/ $output .= '<a class="btn btn-tertiary btn-xsm" href="" role="button">Visit site</a>';
        /*------*/$output .= '</div>';
        /*---*/$output .= '</div>';

        /*---*/$output .= '<div class="col-lg-3 col-md-6">';
        /*------*/$output .= '<div class="feature">';
        /*---------*/ $output .= '<div class="entry-logo"><a href="' . $this->bestVPN['value_for_money_score'][0]['vpn_sys_name'] . '/" alt="Logo"><img src="' . VPN_LOGO_PATH . $this->bestVPN['value_for_money_score'][0]['vpn_logo'] . '" height="35px" alt="Logo"></a></div>';
        /*---------*/ $output .= '<h5>Best VPN by <span style="'.$colorStyleH5.'">Value For Money</span></h5>';
        /*---------*/ // $output .= '<a class="tt-read-more" href="' . $this->bestVPN['value_for_money_score'][0]['vpn_sys_name'] .'">Go To Review <i class="fa fa-arrow-right"></i></a>';
        /*---------*/ $output .= '<a class="btn btn-warning btn-xsm" href="' . $this->bestVPN['value_for_money_score'][0]['vpn_sys_name'] .'" role="button">View more...</a>&nbsp';
        /*---------*/ $output .= '<a class="btn btn-tertiary btn-xsm" href="" role="button">Visit site</a>';
        /*------*/$output .= '</div>';
        /*---*/$output .= '</div>';

        /*---*/$output .= '<div class="col-lg-3 col-md-6">';
        /*------*/$output .= '<div class="feature">';
        /*---------*/ $output .= '<div class="entry-logo"><a href="' . $this->bestVPN['streaming_rate'][0]['vpn_sys_name'] . '/" alt="Logo"><img src="' . VPN_LOGO_PATH . $this->bestVPN['streaming_rate'][0]['vpn_logo'] . '" height="35px" alt="Logo"></a></div>';
        /*---------*/ $output .= '<h5>Best VPN by <span style="'.$colorStyleH5.'">Streaming Rate</span></h5>';
        /*---------*/ // $output .= '<a class="tt-read-more" href="' . $this->bestVPN['user_score'][0]['vpn_sys_name'] .'">Go To Review <i class="fa fa-arrow-right"></i></a>';
        /*---------*/ $output .= '<a class="btn btn-warning btn-xsm" href="' . $this->bestVPN['streaming_rate'][0]['vpn_sys_name'] .'" role="button">View more...</a>&nbsp';
        /*---------*/ $output .= '<a class="btn btn-tertiary btn-xsm" href="" role="button">Visit site</a>';
        /*------*/$output .= '</div>';
        /*---*/$output .= '</div>';

        /*---*/$output .= '<div class="col-lg-3 col-md-6">';
        /*------*/$output .= '<div class="feature">';
        /*---------*/ $output .= '<div class="entry-logo"><a href="' . $this->bestVPN['torrenting_rate'][0]['vpn_sys_name'] . '/" alt="Logo"><img src="' . VPN_LOGO_PATH . $this->bestVPN['torrenting_rate'][0]['vpn_logo'] . '" height="35px" alt="Logo"></a></div>';
        /*---------*/ $output .= '<h5>Best VPN by <span style="'.$colorStyleH5.'">Torrenting Rate</span></h5>';
        /*---------*/ // $output .= '<a class="tt-read-more" href="' . $this->bestVPN['user_score'][0]['vpn_sys_name'] .'">Go To Review <i class="fa fa-arrow-right"></i></a>';
        /*---------*/ $output .= '<a class="btn btn-warning btn-xsm" href="' . $this->bestVPN['torrenting_rate'][0]['vpn_sys_name'] .'" role="button">View more...</a>&nbsp';
        /*---------*/ $output .= '<a class="btn btn-tertiary btn-xsm" href="" role="button">Visit site</a>';
        /*------*/$output .= '</div>';
        /*---*/$output .= '</div>';

        /*---*/$output .= '<div class="col-lg-3 col-md-6">';
        /*------*/$output .= '<div class="feature">';
        /*---------*/ $output .= '<div class="entry-logo"><a href="' . $this->bestVPN['gaming_rate'][0]['vpn_sys_name'] . '/" alt="Logo"><img src="' . VPN_LOGO_PATH . $this->bestVPN['gaming_rate'][0]['vpn_logo'] . '" height="35px" alt="Logo"></a></div>';
        /*---------*/ $output .= '<h5>Best VPN by <span style="'.$colorStyleH5.'">Gaming Rate</span></h5>';
        /*---------*/ // $output .= '<a class="tt-read-more" href="' . $this->bestVPN['user_score'][0]['vpn_sys_name'] .'">Go To Review <i class="fa fa-arrow-right"></i></a>';
        /*---------*/ $output .= '<a class="btn btn-warning btn-xsm" href="' . $this->bestVPN['gaming_rate'][0]['vpn_sys_name'] .'" role="button">View more...</a>&nbsp';
        /*---------*/ $output .= '<a class="btn btn-tertiary btn-xsm" href="" role="button">Visit site</a>';
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