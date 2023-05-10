<?php
require_once V_CORE_LIB . 'Model/AbstractModel.php';
require_once V_CORE_LIB . 'Model/Relations/ManyToMany.php';
require_once V_CORE_LIB . 'Model/DTO/QueryParamsDTO.php';
require_once V_CORE_LIB . 'Model/DTO/ResultDataDTO.php';
require_once V_CORE_LIB . 'Utils/Validator.php';
require_once V_CORE_LIB . 'Utils/Result.php';
require_once V_CORE_LIB . 'Utils/SquareBracketsChecker.php';
require_once V_CORE_LIB . 'Utils/BracketsParser.php';

class TopVPNModel extends AbstractModel{

    private array $keyManyToManyFields = [
        'device' =>[
            'pivot_table_name' => 'topvpn_vpn_device',
            'this_key_name' => 'vpn_id',
            'that_key_name' => 'device_id'],
//        'os' =>[
//            'pivot_table_name' => 'topvpn_vpn_os',
//            'this_key_name' => 'vpn_id',
//            'that_key_name' => 'os_id'],
        'streaming' =>[
            'pivot_table_name' => 'topvpn_vpn_streaming',
            'this_key_name' => 'vpn_id',
            'that_key_name' => 'streaming_id'],
        'payments' =>[
            'pivot_table_name' => 'topvpn_vpn_payments',
            'this_key_name' => 'vpn_id',
            'that_key_name' => 'payments_id'],
        'location' =>[
            'pivot_table_name' => 'topvpn_vpn_location',
            'this_key_name' => 'vpn_id',
            'that_key_name' => 'location_id']
    ];

    public array $additionalCompData = [];

    public function __construct(string $dbTable)
    {
        parent::__construct($dbTable);
    }

    public function getAllRows(bool $activeMode = true, bool $paginationMode = true, bool $sqlLimitMode = false){
        $rowsData = parent::getAllRows($activeMode, $paginationMode, $sqlLimitMode);
        $limitCount = $this->getLimitCount();
        foreach ($rowsData as &$rowData) {
            $rowData['rating'] = $this->getAverageRating($rowData);
        }
        if($sqlLimitMode == false){
            usort($rowsData, function($a, $b) {
                return $b['rating'] <=> $a['rating'];
            });

            if($limitCount > 0){
                return array_slice($rowsData, 0, $limitCount);
            }
        }

        return $rowsData;
    }

    public function getAverageRating($rowData){
        $weights = array(0.3, 0.3, 0.2, 0.1, 0.05, 0.05);
        $rating = $this->weightedAverage([
            $rowData['overall_speed'],
            $rowData['privacy_score'],
            $rowData['feautures_score'],
            $rowData['streaming_rate'],
            $rowData['torrenting_rate'],
            $rowData['easy_to_use'],
        ], $weights);
        return round($rating, 1);
    }

    public function weightedAverage($nums, $weights) {
        $sum = 0;

        for ($y=0; $y < count($nums); $y++){
            $sum += $weights[$y] * $nums[$y];
        }
        return $sum / array_sum($weights);
    }

    public function addRow( array $data) : object{

        $validate = (new Validator)->checkLength($data['vpn_name'], 3, 30, 'vpn_name', 'Имя', true)
            ->checkLength($data['vpn_sys_name'], 3, 30, 'vpn_sys_name', 'Системное имя', true);

        if($validate->getResultStatus() == 'error'){
            return Result::setResult('error', $validate->getResultMessage(), $data);
        }

        $logoResult = $this->checkFileAndUpload('vpn_logo', VPN_LOGO_PATH_FILE);
        if ($logoResult->getResultStatus() === 'ok') {
            $data['vpn_logo'] = $logoResult->getResultData();
        }
        $this->resultMessages->addResultMessage($this->getNameOfClass(), $logoResult->getResultStatus(), $logoResult->getResultMessage());
        $screenResult = $this->checkFileAndUpload('screen', VPN_SCREEN_PATH_FILE);
        if ($screenResult->getResultStatus() === 'ok') {
            $data['screen'] = $screenResult->getResultData();
        }
        $this->resultMessages->addResultMessage($this->getNameOfClass(), $screenResult->getResultStatus(), $screenResult->getResultMessage());
        $data['position'] = $this->getMaxPosition() + 1;
        $recordedRow = $this->insertRow($data);

        if ($recordedRow['last_insert_id'] > 0) {
            $recordedRow = ManyToMany::addManyToOne($this->keyManyToManyFields, $recordedRow, $data);
        } else {
            $this->resultMessages->addResultMessage($this->getNameOfClass(), 'error', 'Ошибка записи в Б.Д.');
            return Result::setResult('error', $this->resultMessages->getResultMessages($this->getNameOfClass()), $data);
        }
        $this->resultMessages->addResultMessage($this->getNameOfClass(), 'ok', 'VPN добавлен успешно!');
        return Result::setResult('ok', $this->resultMessages->getResultMessages($this->getNameOfClass()), $recordedRow);
    }

    public function editRow($id, $data) : object
    {
        $resultMessages = new ResultMessages();
        $validate = (new Validator)->checkLength($data['vpn_name'], 3, 30, 'vpn_name', 'Имя', true)
            ->checkLength($data['vpn_sys_name'], 3, 30, 'vpn_sys_name', 'Системное имя', true);

        if($validate->getResultStatus() == 'error'){
            return Result::setResult('error', $validate->getResultMessage(), $data);
        }

       // $this->UnlinkAllUnusedImages('vpn_logo', $path);

        $logoResult = $this->checkFileAndUpload('vpn_logo', VPN_LOGO_PATH_FILE);
        if ($logoResult->getResultStatus() !== 'no_file') {
            if ($logoResult->getResultStatus() === 'ok') {
                echo $logoResult->getResultData();
                $data['vpn_logo'] = $logoResult->getResultData();
            }
            $this->resultMessages->addResultMessage($this->getNameOfClass(), $logoResult->getResultStatus(), $logoResult->getResultMessage());
        }
        $screenResult = $this->checkFileAndUpload('screen', VPN_SCREEN_PATH_FILE);
        if ($screenResult->getResultStatus() !== 'no_file') {
            if ($screenResult->getResultStatus() === 'ok') {
                $data['screen'] = $screenResult->getResultData();
            }
            $this->resultMessages->addResultMessage($this->getNameOfClass(), $screenResult->getResultStatus(), $screenResult->getResultMessage());
        }
        $updatedRow = $this->updateRow($id, $data);
//        echo '<pre>';
//        print_r($updatedRow);
//        echo '</pre>';
        if(count($updatedRow) > 0){
            $updatedRow = ManyToMany::editManyToOne($this->keyManyToManyFields, $updatedRow, $id, $data);
        } else {
            $this->resultMessages->addResultMessage($this->getNameOfClass(), 'error', 'Ошибка записи в Б.Д.');
            return Result::setResult('error', $resultMessages->getResultMessages($this->getNameOfClass()), $data);
        }
        $this->resultMessages->addResultMessage($this->getNameOfClass(), 'ok', 'VPN '. $updatedRow['vpn_name'] .' изменен успешно!');
        return Result::setResult('ok', $this->resultMessages->getResultMessages($this->getNameOfClass()), $updatedRow);
    }

    public function deleteRow($data) : object{

        $id = $data['id'];

        $path = V_PLUGIN_INCLUDES_DIR . 'images/vpn/';
        $deleteImgResult = $this->checkFileAndUnlink($data, 'vpn_logo', $path);
        $this->resultMessages->addResultMessage($this->getNameOfClass(), $deleteImgResult->getResultStatus(), $deleteImgResult->getResultMessage());
        $deleteManyToOne = ManyToMany::deleteManyToOne($this->keyManyToManyFields, $id);
        if($deleteManyToOne->getResultStatus() == 'error'){
            return Result::setResult('error', $deleteManyToOne->getResultMessage('ManyToMany'), '');
        } else {
            $this->resultMessages->addResultMessage($this->getNameOfClass(), 'ok', $deleteManyToOne->getResultMessage('ManyToMany'));
        }
        if($this->removeRow($id) !== ''){
            $this->resultMessages->addResultMessage($this->getNameOfClass(), 'error', 'Ошибка записи в Б.Д.');
            return Result::setResult('error', $this->resultMessages->getResultMessages($this->getNameOfClass()), '');
        }
        $this->resultMessages->addResultMessage($this->getNameOfClass(), 'ok', $data['vpn_name'].' удален успешно');
        return Result::setResult('ok', $this->resultMessages->getResultMessages($this->getNameOfClass()), '');
    }

    public function getRowsByRelId(string $keyManyToMany, int $id, bool $paginationMode = true, bool $sqlLimitMode = false) : array{

        $queryParamsDTO = $this->getSqlQueryParams($paginationMode, $sqlLimitMode);
        $orderSql = $queryParamsDTO->getOrderSql();
        $paginatSql = $queryParamsDTO->getPaginatSql();
        $multiLangMode = $queryParamsDTO->getMultiLangMode();

        if($keyManyToMany == ''){
            return [];
        }
        if($keyManyToMany == 'os'){
            $sql = "SELECT $this->dbTable.*
                              FROM $this->dbTable
                              INNER JOIN {$this->prefix}topvpn_vpn_os ON ({$this->dbTable}.id={$this->prefix}topvpn_vpn_os.vpn_id)    
                              INNER JOIN {$this->prefix}topvpn_os ON ({$this->prefix}topvpn_os.id = {$this->prefix}topvpn_vpn_os.os_id)
                              WHERE {$this->prefix}topvpn_os.id = $id AND {$this->dbTable}.active = 1 $multiLangMode $orderSql $paginatSql";

        }

        if($keyManyToMany == 'device') {
            $sql = "SELECT $this->dbTable.*
                              FROM $this->dbTable
                              INNER JOIN {$this->prefix}topvpn_vpn_device ON ({$this->dbTable}.id={$this->prefix}topvpn_vpn_device.vpn_id)    
                              INNER JOIN {$this->prefix}topvpn_device ON ({$this->prefix}topvpn_device.id = {$this->prefix}topvpn_vpn_device.device_id)
                              WHERE {$this->prefix}topvpn_device.id = $id AND {$this->dbTable}.active = 1 $multiLangMode $orderSql $paginatSql";
        }
        if($keyManyToMany == 'streaming'){
            $sql = "SELECT $this->dbTable.*
                              FROM $this->dbTable
                              INNER JOIN {$this->prefix}topvpn_vpn_streaming ON ({$this->dbTable}.id={$this->prefix}topvpn_vpn_streaming.vpn_id)    
                              INNER JOIN {$this->prefix}topvpn_streaming ON ({$this->prefix}topvpn_streaming.id = {$this->prefix}topvpn_vpn_streaming.streaming_id)
                              WHERE {$this->prefix}topvpn_streaming.id = $id AND {$this->dbTable}.active = 1 $multiLangMode $orderSql $paginatSql";

        }
        if($keyManyToMany == 'location'){
            $sql = "SELECT $this->dbTable.*
                              FROM $this->dbTable
                              INNER JOIN {$this->prefix}topvpn_vpn_location ON ({$this->dbTable}.id={$this->prefix}topvpn_vpn_location.vpn_id)    
                              INNER JOIN {$this->prefix}topvpn_location ON ({$this->prefix}topvpn_location.id = {$this->prefix}topvpn_vpn_location.location_id)
                              WHERE {$this->prefix}topvpn_location.id = $id AND {$this->dbTable}.active = 1 $multiLangMode $orderSql $paginatSql";

        }

//        if ($this->multiLangMode) {
//            $sql = "SELECT $this->dbTable.*
//                              FROM $this->dbTable
//                              INNER JOIN {$this->prefix}topvpn_vpn_os ON ({$this->dbTable}.id={$this->prefix}topvpn_vpn_os.vpn_id)
//                              INNER JOIN {$this->prefix}topvpn_os ON ({$this->prefix}topvpn_os.id = {$this->prefix}topvpn_vpn_os.os_id)
//                              WHERE {$this->prefix}topvpn_os.id = $id AND {$this->dbTable}.active = 1 AND {$this->dbTable}.lang = '{$this->lang}' $orderSql $paginatSql";
//        } else {
//            $sql = "SELECT $this->dbTable.*
//                              FROM $this->dbTable
//                              INNER JOIN {$this->prefix}topvpn_vpn_os ON ({$this->dbTable}.id={$this->prefix}topvpn_vpn_os.vpn_id)
//                              INNER JOIN {$this->prefix}topvpn_os ON ({$this->prefix}topvpn_os.id = {$this->prefix}topvpn_vpn_os.os_id)
//                              WHERE {$this->prefix}topvpn_os.id = $id AND {$this->dbTable}.active = 1 $orderSql $paginatSql";
//        }
//        echo $sql;

        $rowsData = $this->wpdb->get_results($sql, ARRAY_A);

        $limitCount = $this->getLimitCount();

        foreach ($rowsData as &$rowData) {
            $rowData['rating'] = $this->getAverageRating($rowData);
        }
        if($sqlLimitMode == false){
            usort($rowsData, function($a, $b) {
                return $b['rating'] <=> $a['rating'];
            });

            if($limitCount > 0){
                return array_slice($rowsData, 0, $limitCount);
            }
        }


        return $rowsData;
    }

    public function countAllRowsByRelId(string $keyManyToMany, int $id){

        $queryParamsDTO = $this->getSqlQueryParams(false, false);
        $multiLangMode = $queryParamsDTO->getMultiLangMode();

        if($keyManyToMany == ''){
            return [];
        }
        if($keyManyToMany == 'os'){
            $sql = "SELECT $this->dbTable.*
                              FROM $this->dbTable
                              INNER JOIN {$this->prefix}topvpn_vpn_os ON ({$this->dbTable}.id={$this->prefix}topvpn_vpn_os.vpn_id)    
                              INNER JOIN {$this->prefix}topvpn_os ON ({$this->prefix}topvpn_os.id = {$this->prefix}topvpn_vpn_os.os_id)
                              WHERE {$this->prefix}topvpn_os.id = $id AND {$this->dbTable}.active = 1 $multiLangMode";

        }

        if($keyManyToMany == 'device') {
            $sql = "SELECT $this->dbTable.*
                              FROM $this->dbTable
                              INNER JOIN {$this->prefix}topvpn_vpn_device ON ({$this->dbTable}.id={$this->prefix}topvpn_vpn_device.vpn_id)    
                              INNER JOIN {$this->prefix}topvpn_device ON ({$this->prefix}topvpn_device.id = {$this->prefix}topvpn_vpn_device.device_id)
                              WHERE {$this->prefix}topvpn_device.id = $id AND {$this->dbTable}.active = 1 $multiLangMode";
        }

        if($keyManyToMany == 'streaming') {
            $sql = "SELECT $this->dbTable.*
                              FROM $this->dbTable
                              INNER JOIN {$this->prefix}topvpn_vpn_streaming ON ({$this->dbTable}.id={$this->prefix}topvpn_vpn_streaming.vpn_id)    
                              INNER JOIN {$this->prefix}topvpn_streaming ON ({$this->prefix}topvpn_streaming.id = {$this->prefix}topvpn_vpn_streaming.streaming_id)
                              WHERE {$this->prefix}topvpn_streaming.id = $id AND {$this->dbTable}.active = 1 $multiLangMode";
        }

        if($keyManyToMany == 'location') {
            $sql = "SELECT $this->dbTable.*
                              FROM $this->dbTable
                              INNER JOIN {$this->prefix}topvpn_vpn_location ON ({$this->dbTable}.id={$this->prefix}topvpn_vpn_location.vpn_id)    
                              INNER JOIN {$this->prefix}topvpn_location ON ({$this->prefix}topvpn_location.id = {$this->prefix}topvpn_vpn_location.streaming_id)
                              WHERE {$this->prefix}topvpn_location.id = $id AND {$this->dbTable}.active = 1 $multiLangMode";
        }

        $this->wpdb->get_results($sql);
        return $this->wpdb->num_rows;
    }


    public function combineAdditionalData(array $rowsData, array $additionalData): object
    {
        $sort = true;

        if(count($additionalData) == 0){
            return (new ResultDataDTO($rowsData));
        }

        $additionalCompData = [];
        foreach ($rowsData as &$rowData) {
            $additionalRating = false;
            $rowData['additional_position'] = 0;
        //    $rowData['rating_features_k'] = $this->transformRatingFeatures($rowData);
        //    $rowData['rating'] = $this->getAverageRating($rowData);
        //    echo $rowData['rating'].'| ';
            $matchedAdditionalData = array_filter($additionalData, function($rowAdditional) use ($rowData) {
                return $rowData['id'] === $rowAdditional['foreign_id'] && $rowAdditional['active'] == 1;
            });

//            if($rowData['id'] === $additionalData['foreign_id'] && $additionalData['active'] == 1){
//                $matchedAdditionalData = true;
//            }

            if (!empty($matchedAdditionalData)) {
                $rowAdditional = reset($matchedAdditionalData);
//                echo '<pre>';
//                print_r($rowAdditional);
//                echo '</pre>';
                $exploded = explode(';', $rowAdditional['rating_features']);

                $additRatingSum = 0;
                foreach ($exploded as $string) {
                    $checker = new SquareBracketsChecker($string);
                    $checker->removeSquareBrackets();
                    $exploded2 = explode(':', trim($checker->getString()));
                //    echo $exploded2[1].' ';
                    if($checker->getMatched()){
                        $bracketsParser = new BracketsParser(trim($exploded2[0]));
                        $bracketsParser->extractTextInBrackets();
                        $cleaned = $bracketsParser->getCleaned();
                        $extracted = $bracketsParser->getExtracted();
                        $additionalCompData[$cleaned]['info'] = $extracted;
                        $additionalCompData[$cleaned][$rowData['id']] = trim($exploded2[1]);
                    }
                    if($checker->getRatingCountMatched()) {
                        if (is_numeric(trim($exploded2[1]))) {
                            $sort = true;
                            //    $additRatingSum += trim($exploded2[1]);

                            $weights = array(0.2, 0.2, 0.2, 0.2, 0.1, 0.05, 0.05);
                            $additionalRating = $this->weightedAverage([
                                $exploded2[1],
                                $rowData['overall_speed'],
                                $rowData['privacy_score'],
                                $rowData['feautures_score'],
                                $rowData['streaming_rate'],
                                $rowData['torrenting_rate'],
                                $rowData['easy_to_use']
                            ], $weights);
                        }
                    }
                }

            //    $rowAdditional['rating'] = $additRatingSum / count($exploded);
            //    echo $rowAdditional['rating'].'|| ';
                $rowData = array_merge($rowData, array_filter([
                    'additional_position' => $rowAdditional['add_position'] ?? 0,
                    'top_status_description' => $rowAdditional['top_status_description'] ?? null,
                    'short_description' => $rowAdditional['short_description'] ?? null,
                    'features' => $rowAdditional['features'] ?? null,
                    'rating' => $additionalRating ?? null,
                    'rating_description' => $rowAdditional['rating_description'] ?? null,
                    'rating_features_k' => $rowAdditional['rating_features'] ?? null,
                ]));
            }

        //    echo $rowData['rating'].'||| ';
        }
//        echo '<pre>';
//        print_r($additionalCompData);
//        echo '</pre>';
        if($sort) {
            usort($rowsData, function ($a, $b) {
                return $b['rating'] <=> $a['rating'];
            });
        }

//        usort($rowsData, function($a, $b) {
//            if($a->rating > $b->rating) {
//                return 1;
//            }
//            elseif($a->rating < $b->rating) {
//                return -1;
//            }
//            else {
//                return 0;
//            }
//        });
        
//        echo '<pre>';
//        print_r($rowsData);
//        echo '</pre>';
        return (new ResultDataDTO($rowsData))->setAdditionalResultData($additionalCompData, 'compare');
    }

    private function transformRatingFeatures($rowData): string
    {
        $ratingFeatures = '';
        $ratingFields = [
            'overall_speed' => 'Overall speed',
            'privacy_score' => 'Privacy & Logging',
            'feautures_score' => 'Security & Features',
            'value_for_money_score' => 'Value for money',
            'easy_to_use' => 'Ease of Use',
        ];
        foreach ($ratingFields as $key => $value) {
            if ($rowData[$key] > 0) {
                $ratingFeatures .= "$value: {$rowData[$key]};";
            }
        }
        return $ratingFeatures;
    }

    private function countAverageRate(array $rowData): float
    {
        $rowData = array_filter($rowData, function($value) {
            return $value !== 0;
        });
        $count = count($rowData);
        $sum = array_sum($rowData);
        return $count === 0 ? 0 : ($sum / $count);
    }


//    public function combineAdditionalData(array $rowsData, array $additionalData): array
//    {
//
////        echo '<pre>';
////        print_r($additionalData);
////        echo '</pre>';
//        foreach ($rowsData as &$rowData) {
//            $rowData['additional_position'] = 0;
//            $rowData['rating_features_k'] = $this->transformRatingFeatures($rowData);
//            $rowData['rating'] = $this->countAverageRate($rowData['overall_speed'] + $rowData['privacy_score']
//                + $rowData['feautures_score'] + $rowData['value_for_money_score'] + $rowData['easy_to_use']);
//            foreach ($additionalData as $rowAdditional) {
//                if ($rowData['id'] === $rowAdditional['foreign_id']) {
//                    if($rowAdditional['active'] == 1){
//
//                        if($rowAdditional['rating_features'] !== ''){
//                            $exploded = explode(';', $rowAdditional['rating_features']);
//                            for ($i = 0; $i < (count($exploded) - 1); $i++) {
//                                $string = trim($exploded[$i]);
//                                $exploded2 = explode(':', $string);
//                                if (is_double(trim($exploded2[0])) or is_int(trim($exploded2[0]))) {
//                                    $additRatingSum += trim($exploded2[0]);
//                                }
//                            }
//                            $rowAdditional['rating'] = $additRatingSum / (count($exploded) - 1);
//                        } else {
//
//                            $rowAdditional['rating_features'] = null;
//                            $rowAdditional['rating'] = null;
//                        }
//
//                        $rowData = array_merge($rowData, array_filter([
//                            'additional_position' => $rowAdditional['add_position'] ?? 0,
//                            'top_status_description' => $rowAdditional['top_status_description'] ?? null,
//                            'short_description' => $rowAdditional['short_description'] ?? null,
//                            'features' => $rowAdditional['features'] ?? null,
//                            'rating' => $rowAdditional['rating'] ?? null,
//                            'rating_description' => $rowAdditional['rating_description'] ?? null,
//                            'rating_features_k' => $rowAdditional['rating_features'] ?? null,
//                        ]));
//
//                    }
//                }
//            }
//        }
//        usort($rowsData, function($a, $b) {
//            if($a->rating > $b->rating) {
//                return 1;
//            }
//            elseif($a->rating < $b->rating) {
//                return -1;
//            }
//            else {
//                return 0;
//            }
//        });
//        return $rowsData;
//    }
//
//    private function transformRatingFeatures($rowData){
//
//        $ratingFeatures = '';
//        if($rowData['overall_speed'] > 0){
//            $ratingFeatures .= 'Overall speed: '.$rowData['overall_speed'].';';
//        }
//        if($rowData['privacy_score'] > 0){
//            $ratingFeatures .= 'Privacy & Logging: '.$rowData['privacy_score'].';';
//        }
//        if($rowData['feautures_score'] > 0){
//            $ratingFeatures .= 'Security & Features: '.$rowData['feautures_score'].';';
//        }
//        if($rowData['value_for_money_score'] > 0){
//            $ratingFeatures .= 'Value for money: '.$rowData['value_for_money_score'].';';
//        }
//        if($rowData['easy_to_use'] > 0){
//            $ratingFeatures .= 'Ease of Use: '.$rowData['easy_to_use'].';';
//        }
//        return $ratingFeatures;
//    }
//
//    private function countAverageRate($rowData){
//        array_filter($rowData, function($value) {
//            return $value !== 0;
//        });
//        $count = count($rowData);
//        $sum = array_sum($rowData);
//        return ($sum) / $count;
//    }

    public function sortWithAdditionalData(array $rowsData)
    {
        $additionalRows = [];
        $sortedRows = [];

        // Сортируем дополнительные строки в отдельный массив
        foreach ($rowsData as $rowData) {
            if ($rowData['additional_position'] > 0) {
                $additionalRows[] = $rowData;
            } else {
                $sortedRows[] = $rowData;
            }
        }

        // Сортируем основной массив строк и вставляем дополнительные строки
        foreach ($additionalRows as $additionalRow) {
            $position = $additionalRow['additional_position'] - 1;
            array_splice($sortedRows, $position, 0, [$additionalRow]);
        }

        return $sortedRows;
    }

}