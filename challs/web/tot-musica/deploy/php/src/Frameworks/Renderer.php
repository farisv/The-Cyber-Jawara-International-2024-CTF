<?php

declare(strict_types=1);

namespace App\Frameworks;

use Error;
use InvalidArgumentException;
use Psr\Http\Message\ResponseInterface;
use RuntimeException;
use Throwable;

class Renderer
{
    protected string $templatePath;

    /**
     * @var array<string, mixed>
     */
    protected array $attributes;

    protected string $layout;

    /**
     * @param string $templatePath
     * @param array<string, mixed> $attributes
     * @param string $layout
     */
    public function __construct(string $templatePath = '', array $attributes = [], string $layout = '')
    {
        $this->templatePath = rtrim($templatePath, '/\\') . '/';
        $this->attributes = $attributes;
        $this->setLayout($layout);
    }

    /**
     * @param ResponseInterface $response
     * @param string $template
     * @param array<string, mixed> $data
     * @param bool $sanitize
     *
     * @throws Throwable
     *
     * @return ResponseInterface
     */
    public function render(ResponseInterface $response, string $template, array $data = [], bool $sanitize = true): ResponseInterface
    {
        $output = $this->fetch($template, $data, true, $sanitize);
        $response->getBody()->write($output);
        return $response;
    }

    /**
     * @return string
     */
    public function getLayout(): string
    {
        return $this->layout;
    }

    /**
     * @param string $layout
     *
     * @throws \Exception
     *
     * @return void
     */
    public function setLayout(string $layout): void
    {
        if ($layout && !$this->templateExists($layout)) {
            throw new \Exception('Layout template "' . $layout . '" does not exist');
        }

        $this->layout = $layout;
    }

    /**
     * @return array<string, mixed>
     */
    public function getAttributes(): array
    {
        return $this->attributes;
    }

    /**
     * @param array<string, mixed> $attributes
     *
     * @return void
     */
    public function setAttributes(array $attributes): void
    {
        $this->attributes = $attributes;
    }

    /**
     * @param string $key
     * @param mixed $value
     *
     * @return void
     */
    public function addAttribute(string $key, $value): void
    {
        $this->attributes[$key] = $value;
    }

    /**
     * @param string $key
     *
     * @return bool|mixed
     */
    public function getAttribute(string $key)
    {
        if (!isset($this->attributes[$key])) {
            return false;
        }

        return $this->attributes[$key];
    }

    /**
     * @return string
     */
    public function getTemplatePath(): string
    {
        return $this->templatePath;
    }

    /**
     * @param string $templatePath
     */
    public function setTemplatePath(string $templatePath): void
    {
        $this->templatePath = rtrim($templatePath, '/\\') . '/';
    }

    /**
     * @param string $template
     * @param array<string, mixed> $data
     * @param bool $useLayout
     * @param bool $sanitize
     *
     * @return string
     */
    public function fetch(string $template, array $data = [], bool $useLayout = false, bool $sanitize = true): string
    {
        $output = $this->processTemplate($template, $data, $sanitize);
        if ($this->layout && $useLayout) {
            $data['content'] = $output;
            $output = $this->processTemplate($this->layout, $data, $sanitize);
        }

        return $output;
    }

    protected function processTemplate(string $templateContent, array $data, $sanitize = true): string
    {
        try {
            $templateContent = file_get_contents($this->templatePath . $templateContent);
            foreach ($data as $k => $v) {
                if($sanitize){
                    $v = htmlentities($v, ENT_NOQUOTES);
                }
                $templateContent = str_replace("{{" . $k . "}}", $v, $templateContent);
            }

            $tokens = $this->tokenizeTemplate($templateContent);

            foreach ($tokens as $token) {
                $functionName = $token['function'];
                $arguments = $token['arguments'];
                $fullMatch = $token['full_match'];

                $result = $this->handleTemplateFunction($functionName, $arguments);

                $templateContent = str_replace($fullMatch, $result, $templateContent);
            }
            return $templateContent;
        } catch (\Exception $th) {
        }
        
    }

    /**
     * @param string $template
     *
     * @return bool
     */
    public function templateExists(string $template): bool
    {
        $path = $this->templatePath . $template;
        return is_file($path) && is_readable($path);
    }

    /**
     * @param string $template
     *
     * @return array
     */
    protected function tokenizeTemplate(string $template): array
    {
        $pattern = '/\{\{\s*(\w+)\s*\((('
                 . '\'(?:\\\\\'|[^\'])*\'|'
                 . '"(?:\\\\"|[^"])*"|'
                 . '[^\'")])*?)\)\s*\}\}/s';

        preg_match_all($pattern, $template, $matches, PREG_SET_ORDER);
        $tokens = [];
        
        foreach ($matches as $match) {
            $functionName = $match[1];
            $arguments = $match[2];

            // Parse arguments
            $args = $this->parseArguments($arguments);

            $tokens[] = [
                'function' => $functionName,
                'arguments' => $args,
                'full_match' => $match[0],
            ];
        }

        return $tokens;
    }

    /**
     * @param string $template
     *
     * @return array
     */
    protected function parseArguments(string $arguments): array
    {
        $args = [];
        $length = strlen($arguments);
        $i = 0;

        while ($i < $length) {
            while ($i < $length && ctype_space($arguments[$i])) {
                $i++;
            }

            if ($i >= $length) {
                break;
            }

            if ($arguments[$i] === "'" || $arguments[$i] === '"') {
                $quote = $arguments[$i];
                $i++;
                $start = $i;
                $escaped = false;
                $arg = '';

                while ($i < $length) {
                    $char = $arguments[$i];

                    if ($char === '\\' && !$escaped) {
                        $escaped = true;
                        $i++;
                        continue;
                    }

                    if ($char === $quote && !$escaped) {
                        break;
                    }

                    $arg .= $char;
                    $escaped = false;
                    $i++;
                }

                $args[] = $arg;
                $i++;
            } else {
                $start = $i;
                
                
                while ($i < $length && $arguments[$i] !== ',' && $arguments[$i] !== ')') {
                    $i++;
                }
                
                $arg = trim(substr($arguments, $start, $i - $start));
                $args[] = $arg;
            }

            while ($i < $length && (ctype_space($arguments[$i]) || $arguments[$i] === ',')) {
                $i++;
            }
        }

        return $args;
    }
    
    /**
     * @param string $functionName
     * @param array $arguments
     *
     * @return string
     */
    protected function handleTemplateFunction(string $functionName, array $arguments): string
    {
        switch ($functionName) {
            case 'include':
                $includedTemplate = $arguments[0] ?? '';
                return $includedTemplate;
            case 'lower':
                $text = $arguments[0] ?? '';
                return strtoupper($text);
            case 'upper':
                $text = $arguments[0] ?? '';
                return strtoupper($text);
            // Add more cases for other functions
            default:
                // Optionally handle unknown functions
                return '';
        }
    }
}