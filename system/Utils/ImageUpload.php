<?php
require_once V_CORE_LIB . 'Utils/Result.php';

class ImageUpload{

    public static function imageUpload($fieldName, $path){

        $returnData = [];

        if (isset($_FILES[$fieldName])) {
// Получаем нужные элементы массива "image"
            $fileTmpName = $_FILES[$fieldName]['tmp_name'];
            $errorCode = $_FILES[$fieldName]['error'];
// Проверим на ошибки
            if ($errorCode !== UPLOAD_ERR_OK || !is_uploaded_file($fileTmpName)) {
                // Массив с названиями ошибок
                $errorMessages = [
                    UPLOAD_ERR_INI_SIZE   => 'Размер файла превысил значение upload_max_filesize в конфигурации PHP.',
                    UPLOAD_ERR_FORM_SIZE  => 'Размер загружаемого файла превысил значение MAX_FILE_SIZE в HTML-форме.',
                    UPLOAD_ERR_PARTIAL    => 'Загружаемый файл был получен только частично.',
                    UPLOAD_ERR_NO_FILE    => 'Файл не был загружен.',
                    UPLOAD_ERR_NO_TMP_DIR => 'Отсутствует временная папка.',
                    UPLOAD_ERR_CANT_WRITE => 'Не удалось записать файл на диск.',
                    UPLOAD_ERR_EXTENSION  => 'PHP-расширение остановило загрузку файла.',
                ];
                // Зададим неизвестную ошибку
                $unknownMessage = 'При загрузке файла произошла неизвестная ошибка.';
                // Если в массиве нет кода ошибки, скажем, что ошибка неизвестна
                $outputMessage = isset($errorMessages[$errorCode]) ? $errorMessages[$errorCode] : $unknownMessage;
                // Выведем название ошибки
                return Result::setResult('error', $outputMessage, '');
            } else {
                // Создадим ресурс FileInfo
                $fi = finfo_open(FILEINFO_MIME_TYPE);
                // Получим MIME-тип
                $mime = (string) finfo_file($fi, $fileTmpName);
                // Проверим ключевое слово image (image/jpeg, image/png и т. д.)
                if (strpos($mime, 'image') === false)
                    return Result::setResult('error', 'Можно загружать только изображения!', '');

                // Результат функции запишем в переменную
                $image = getimagesize($fileTmpName);

                // Зададим ограничения для картинок
                $limitBytes  = 1024 * 1024 * 5;
                $limitWidth  = 1280;
                $limitHeight = 768;

                // Проверим нужные параметры
                if (filesize($fileTmpName) > $limitBytes) return Result::setResult('error', 'Размер изображения не должен превышать 5 Мбайт!', '');
                if ($image[1] > $limitHeight)             return Result::setResult('error', 'Высота изображения не должна превышать 768 точек!', '');
                if ($image[0] > $limitWidth)              return Result::setResult('error', 'Ширина изображения не должна превышать 1280 точек!', '');

                // Сгенерируем новое имя файла через функцию getRandomFileName()
                $name = self::getRandomFileName($fileTmpName);

                // Сгенерируем расширение файла на основе типа картинки
                $extension = image_type_to_extension($image[2]);

                // Сократим .jpeg до .jpg
                $format = str_replace('jpeg', 'jpg', $extension);

                // Переместим картинку с новым именем и расширением в папку /upload
                if (!move_uploaded_file($fileTmpName, $path . $name . $format)) {
                    return Result::setResult('error', 'При записи изображения на диск произошла ошибка!', '');
                }
                return Result::setResult('ok', 'Картинка успешно загружена!', $name);
            }
        } else return Result::setResult('no_file', 'Файлов изображений не было.', '');
    }

    public static function getRandomFileName($path)
    {
        $path = $path ? $path . '/' : '';
        do {
            $name = md5(microtime() . rand(0, 9999));
            $file = $path . $name;
        } while (file_exists($file));

        return $name;
    }
}