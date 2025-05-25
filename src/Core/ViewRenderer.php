<?php
namespace App\Core;

class ViewRenderer
{
    private string $layoutPath;
    private array $cssAssets = [];
    private array $jsAssets = [];

    public function __construct(string $layoutPath = __DIR__ . '/../../views/layout.phtml')
    {
        $this->layoutPath = $layoutPath;
    }

    public function render(string $path, array $params = []): void
    {
        extract($params);
        $asset = fn($path) => $this->asset($path);
        $addCss = fn($path) => $this->addCss($path);
        $addJs = fn($path) => $this->addJs($path);
        $getCss = fn() => $this->getCssTags();
        $getJs = fn() => $this->getJsTags();

        ob_start();
        require __DIR__ . '/../../views/' . $path . '.phtml';
        $content = ob_get_clean();

        require $this->layoutPath;
    }

    public function asset(string $path): string
    {
        return $path; 
    }

    public function addCss(string $path): void
    {
        $this->cssAssets[] = $this->asset($path);
    }

    public function addJs(string $path): void
    {
        $this->jsAssets[] = $this->asset($path);
    }

    public function getCssTags(): string
    {
        return implode("\n", array_map(fn($css) => "<link rel=\"stylesheet\" href=\"$css\">", $this->cssAssets));
    }

    public function getJsTags(): string
    {
        return implode("\n", array_map(fn($js) => "<script src=\"$js\"></script>", $this->jsAssets));
    }
}
