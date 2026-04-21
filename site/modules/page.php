<?php

class Page {
    private $template;

    /**
     * Конструктор
     * @param string $template путь к шаблону
     */
    public function __construct($template) {
        if (!file_exists($template)) {
            throw new Exception("Шаблон не найден: " . $template);
        }

        $this->template = $template;
    }

    /**
     * Рендер страницы
     * @param array $data данные для подстановки
     */
public function Render($data) {
    extract($data);

    ob_start();
    include $this->template;
    echo ob_get_clean();
    }
}