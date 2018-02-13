<?php

namespace app\models\forms;

use app\models\Projects;
use Gregwar\Image\Image;
use Intervention\Image\ImageManagerStatic as ImageManager;
use Intervention\Image\Gd\Font;
use Yii;
use yii\base\Model;
use yii\web\UploadedFile;

class UploadForm extends Model
{
    /**
     * @var UploadedFile
     */
    public $imgFile;
    public $imgPath;
    
    public $docFile;
    public $docPath;

    const TYPE_PAGES = 'pages';
    const TYPE_NEWS = 'news';
    const TYPE_GALLERY = 'gallery';
    const TYPE_EMAILS = 'emails';
    const TYPE_PROFILE = 'profile';
    const TYPE_ACTIONS = 'actions';
    const TYPE_REVIEWS = 'reviews';

    const SAVE_QUALITY = 100;
    const FONT_ALIAS = '@app/web/fonts/sourcesanspro/sourcesanspro.ttf';
    const FONT_BORDER_COLOR = '50,50,50,0.5';
    const FONT_COLOR = '255,255,255,0.85';
    
    protected $imgName = [];
    protected $docName = [];
    
    static protected $config_upload = [
        // Норма веса
        Projects::MAKEBODY_ID => [
            'protocol' => 'https',
            'domain' => 'xn----7sbbhp2bgisp.xn--p1ai',
            'symlink' => 'web-makebody',
            
            'upload_dir' => 'images',
            'view_dir' => 'images',

            'doc_upload_dir' => 'docs',
            'doc_view_dir' => 'docs',
        ],
        // Пансион в Раменском
        Projects::RAMENSKOE_ID => [
            'protocol' => 'https',
            'domain' => 'dom-prestarelyh-ramenskoe.ru',
            'symlink' => 'web-dp',
            
            'upload_dir' => 'images',
            'view_dir' => 'images',
            
            'doc_upload_dir' => 'docs',
            'doc_view_dir' => 'docs',
        ],
        // TCGroup
        Projects::TCGROUP_ID => [
            'protocol' => 'http',
            'domain' => 'tcgroup.ru',
            'symlink' => 'web-tcgroup',
            
            'upload_dir' => 'images',
            'view_dir' => 'images'
        ],
    ];

    static protected $MARK_TEXT;
    
    static protected $UPLOAD_DIR;
    static protected $VIEW_DIR;
    
    static protected $DOC_UPLOAD_DIR;
    static protected $DOC_VIEW_DIR;

    public function rules()
    {
        return [
            [['imgFile'], 'image', 'mimeTypes' => 'image/*',
             'maxFiles' => 10, 'maxSize' => (50 * pow(1024, 2))],
            
            [['docFile'], 'file', 'mimeTypes' => ['text/plain', 'application/pdf', 'application/msword', 'application/msexcell'],
             'maxFiles' => 10, 'maxSize' => (100 * pow(1024, 2))],
        ];
    }
    
    public function __construct(array $config = [])
    {
        $project = isset($config['project_id']) ? Projects::findOne($config['project_id']) : Projects::getCurrentModel();
        $conf = self::$config_upload[$project->id];

        $project_dir = Yii::getAlias("@app/web/{$conf['symlink']}");
            
        self::$MARK_TEXT = $project->mark_text;
            
        self::$UPLOAD_DIR = "{$project_dir}/{$conf['upload_dir']}";
        self::$VIEW_DIR = "/{$conf['symlink']}/{$conf['view_dir']}";
    
        self::$DOC_UPLOAD_DIR = "{$project_dir}/{$conf['doc_upload_dir']}";
        self::$DOC_VIEW_DIR = "/{$conf['symlink']}/{$conf['doc_view_dir']}";
    
        unset($config['project_id']);
        parent::__construct($config);
    }
    
    public static function getConfig() {
        return self::$config_upload[Projects::getCurrent()];
    }
    
    /**
     * относительный|абсолютный web-путь к фото
     *
     * @param $img_name - имя фото
     * @param string $type - тип фото
     * @param null $suffix - суффикс миниатюры
     * @param bool $is_full
     * @return string
     */
    public static function getSrc($img_name, $type = self::TYPE_PAGES, $suffix = null, $is_full = false) {
        $config = self::getConfig();
    
        if ($suffix) $img_name = preg_replace('/(\.[\w]+)$/i', $suffix . '$1', $img_name);
        $src = [$config['symlink'], $config['view_dir'], $type, $img_name];
        
        $res = '/' . implode('/', $src);
        if ($is_full) $res = $config['protocol'] . '://' . $config['domain'] . $res;
            
        return $res;
    }
    
    /**
     * относительный|абсолютный web-путь к документам
     * @param $doc_name - имя документа
     * @param bool $is_full
     * @return string
     */
    public static function getSrcDoc($doc_name, $is_full = false) {
        $config = self::getConfig();
        
        $src = [$config['doc_view_dir'], $doc_name];
        
        $res = '/' . implode('/', $src);
        if ($is_full) $res = '//' . $config['domain'] . $res;
        
        return $res;
    }
    
    /**
     * web-путь текущего файла
     * @return string
     */
    public function getImgPath() {    
        return $this->imgPath;
    }
    
    /**
     * web-путь текущего документа
     *
     * @param bool $full_path
     * @return mixed|string
     */
    public function getDocPath($full_path = false) {
        $config = self::getConfig();
        
        $doc_path = '/' . trim($this->docPath, '/');
        if ($full_path) $doc_path = '//' . $config['domain'] . $doc_path;
        
        return $doc_path;
    }
    
    public function getImageName() {
        return count($this->imgName) == 1 ? $this->imgName[0] : $this->imgName;
    }
    public function getDocName() {
        return count($this->docName) == 1 ? $this->docName[0] : $this->docName;
    }
    
    // удаление фото с миниатюрами
    public static function remove($src, $type = self::TYPE_PAGES) {
        $config = self::getConfig();
        $suffixes = ['', '_lg', '_md', '_sm', '_xs', '_xx'];
        
        // /photos/pages/page_123_xs.jpg => page_123.jpg
        $name = str_replace($suffixes, '', $src);
        $name = str_replace([$config['domain'], $config['view_dir'], $type], '', $name);
        $name = trim($name, '/');
        
        foreach ($suffixes AS $suffix) {
            $file_path = self::getSrc($name, $type, $suffix, true);
            if (file_exists($file_path)) unlink($file_path);
        }
    }
    // удаление документа
    public static function removeDoc($src) {
        $config = self::getConfig();
        
        // /docs/file.txt => file.txt
        $name = str_replace([$config['domain'], $config['doc_view_dir']], '', $src);
        $name = trim($name, '/');
        
        $file_path = self::getSrcDoc($name, true);
        if (file_exists($file_path)) unlink($file_path);
    }
    
    // загрузка документа
    public function uploadDoc()
    {
        if ($this->validate()) {
            /** @var $files UploadedFile[] */
            $files = $this->docFile;
            if (!is_array($files)) $files = [$files];
        
            foreach ($files AS $f) {
                $ext = $f->extension;
            
                $name = uniqid() . '.' . $ext;
                $path = self::$DOC_UPLOAD_DIR;
                $result_path = "{$path}/{$name}";
            
                $this->docName[] = $name;
                $this->docPath = self::getSrcDoc($name);
            
                // сохраняем документ
                $f->saveAs($result_path);
            }
        
            return true;
        } else {
            return false;
        }
    }
    
    // загрузка фото по типам
    public function upload($type = self::TYPE_PAGES)
    {
        if ($this->validate()) {
            /** @var $files UploadedFile[] */
            $files = $this->imgFile;
            if (!is_array($files)) $files = [$files];
            
            foreach ($files AS $f) {
                $ext = $f->extension;
    
                $name = uniqid() . '.' . $ext;
                $path = self::$UPLOAD_DIR . '/' . $type;
                $result_path = "{$path}/{$name}";
    
                $this->imgName[] = $name;
                $this->imgPath = self::getSrc($name, $type);

                // сохраняем основной файл
                $f->saveAs($result_path);

                // пути для миниатюр
                $path_img_lg = str_replace('.' . $ext, '_lg.' . $ext, $result_path);
                $path_img_md = str_replace('.' . $ext, '_md.' . $ext, $result_path);
                $path_img_sm = str_replace('.' . $ext, '_sm.' . $ext, $result_path);
                $path_img_xs = str_replace('.' . $ext, '_xs.' . $ext, $result_path);

                // создаем миниатюры
                Image::open($result_path)->cropResize(800, 800)->save($path_img_lg, 'guess', self::SAVE_QUALITY);
                Image::open($result_path)->cropResize(400, 400)->save($path_img_md, 'guess', self::SAVE_QUALITY);
                Image::open($result_path)->cropResize(200, 200)->save($path_img_sm, 'guess', self::SAVE_QUALITY);
                Image::open($result_path)->cropResize(100, 100)->save($path_img_xs, 'guess', self::SAVE_QUALITY);
                // уменьшаем оригинал
                Image::open($result_path)->cropResize(1200, 1200)->save($result_path, 'guess', self::SAVE_QUALITY);
                
                if (self::$MARK_TEXT) {
                    // подпись-маркер
                    $this->setTextMark(self::$MARK_TEXT, $result_path);
                    $this->setTextMark(self::$MARK_TEXT, $path_img_lg);
                    $this->setTextMark(self::$MARK_TEXT, $path_img_md);
  		            $this->setTextMark(self::$MARK_TEXT, $path_img_sm);
                }
            }

            return true;
        } else {
            return false;
        }
    }

    /**
     * установка текста-маркера с обводкой
     * сначала рисуем обводку по четырем сторонам, а потом основной текст
     *
     * @param $text
     * @param $img_path
     */
    public function setTextMark($text, $img_path) {
        $img = ImageManager::make($img_path);
        
        $img_width = $img->getWidth();
        $img_height = $img->getHeight();
        
        // определяем размер шрифта и отступы в зависимости от ширины фото
        // отрицательные числа для вертикального текста
        switch (true) {
            case ($img_width >= 800): $position = 25; $size = 36; break;
            case ($img_width >= 450): $position = 20; $size = 24; break;
	        case ($img_width >= 300): $position = 15; $size = 18; break;
            case ($img_width >= 200): $position = 10; $size = 14; break;
            
            case ($img_height >= 800): $position = 35; $size = -36; break;
            case ($img_height >= 450): $position = 30; $size = -24; break;
	        case ($img_height >= 300): $position = 20; $size = -18; break;
            case ($img_height >= 200): $position = 10; $size = -14; break;
            
            default: return;
        }
        
        $img->text($text, $img->getWidth() - $position, $img->getHeight() - $position + 1, function(Font $font) use ($size) {
            self::setFont($font, $size, self::FONT_BORDER_COLOR); // обводка снизу
        })->text($text, $img->getWidth() - $position, $img->getHeight() - $position - 1, function(Font $font) use ($size) {
            self::setFont($font, $size, self::FONT_BORDER_COLOR); // обводка сверху
        })->text($text, $img->getWidth() - $position - 1, $img->getHeight() - $position, function(Font $font) use ($size) {
            self::setFont($font, $size, self::FONT_BORDER_COLOR); // обводка слева
        })->text($text, $img->getWidth() - $position + 1, $img->getHeight() - $position, function(Font $font) use ($size) {
            self::setFont($font, $size, self::FONT_BORDER_COLOR); // обводка справа
        })->text($text, $img->getWidth() - $position, $img->getHeight() - $position, function(Font $font) use ($size) {
            self::setFont($font, $size, self::FONT_COLOR); // основной текст
        })->save();
        unset($img);
    }

    /**
     * установка настроек шрифта
     * @param Font $font
     * @param $font_size
     * @param $font_color
     */
    protected static function setFont(Font &$font, $font_size, $font_color) {
        $color = explode(',', $font_color); // '50,50,50,0.5' => array(50,50,50,0.5)
        if (count($color) == 1) $color = $font_color;

        $font->file(Yii::getAlias(self::FONT_ALIAS));
        if ($font_size < 0) $font->angle(-90);
        $font->size(abs($font_size));
        $font->color($color);
        $font->align('right');
        $font->valign('bottom');
    }
}
