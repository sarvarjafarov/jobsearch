<?php

namespace App\Support;

use DOMDocument;
use DOMNode;
use Illuminate\Support\Str;

class JobDescriptionFormatter
{
    /**
     * Tags we keep when sanitizing upstream HTML.
     *
     * @var array<int, string>
     */
    protected const ALLOWED_TAGS = [
        'p',
        'ul',
        'ol',
        'li',
        'strong',
        'em',
        'b',
        'i',
        'br',
        'h3',
        'h4',
    ];

    /**
     * Sanitize HTML pulled from upstream job sources.
     */
    public static function sanitizeHtml(?string $html): string
    {
        if (! $html) {
            return '';
        }

        $html = str_replace('&nbsp;', ' ', $html);

        $document = new DOMDocument('1.0', 'UTF-8');
        libxml_use_internal_errors(true);
        $document->loadHTML('<div>'.$html.'</div>', LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
        libxml_clear_errors();

        $root = $document->documentElement;
        self::cleanNode($root);

        $output = '';
        foreach ($root->childNodes as $child) {
            $output .= $document->saveHTML($child);
        }

        return trim($output);
    }

    /**
     * Convert a stored description into safe HTML for rendering.
     */
    public static function toHtml(?string $content): string
    {
        $content = $content ?? '';

        if ($content === '') {
            return '';
        }

        if (self::looksLikeHtml($content)) {
            return self::sanitizeHtml($content);
        }

        return self::fromPlainText($content);
    }

    /**
     * Normalize multi-line plain text (manual jobs) into HTML blocks.
     */
    protected static function fromPlainText(string $text): string
    {
        $lines = preg_split("/\r\n|\r|\n/", trim($text));

        $paragraphBuffer = [];
        $listBuffer = [];
        $html = '';

        $flushParagraph = function () use (&$paragraphBuffer, &$html) {
            if ($paragraphBuffer) {
                $html .= '<p>'.self::escape(implode(' ', $paragraphBuffer)).'</p>';
                $paragraphBuffer = [];
            }
        };

        $flushList = function () use (&$listBuffer, &$html) {
            if ($listBuffer) {
                $html .= '<ul>'.implode('', array_map(fn ($item) => '<li>'.$item.'</li>', $listBuffer)).'</ul>';
                $listBuffer = [];
            }
        };

        foreach ($lines as $line) {
            $line = trim($line);

            if ($line === '') {
                $flushList();
                $flushParagraph();
                continue;
            }

            if (self::isHeading($line)) {
                $flushList();
                $flushParagraph();
                $html .= '<p><strong>'.self::escape(rtrim($line, ':')).'</strong></p>';
                continue;
            }

            if (self::isBulletLine($line)) {
                $flushParagraph();
                $listBuffer[] = self::escape(self::normalizeBullet($line));
                continue;
            }

            $paragraphBuffer[] = $line;
        }

        $flushList();
        $flushParagraph();

        return $html;
    }

    protected static function looksLikeHtml(string $value): bool
    {
        return Str::contains($value, '<') && Str::contains($value, '>');
    }

    protected static function isBulletLine(string $line): bool
    {
        return (bool) preg_match('/^([-*•·]|[\d]+\.)\s*/u', $line);
    }

    protected static function normalizeBullet(string $line): string
    {
        return trim(preg_replace('/^([-*•·]|[\d]+\.)\s*/u', '', $line));
    }

    protected static function isHeading(string $line): bool
    {
        $normalized = Str::lower(trim($line, ": \t"));

        $keywords = [
            'responsibilities',
            'responsibility',
            'requirements',
            'requirement',
            'qualifications',
            'skills',
            'benefits',
            'about role',
            'job description',
            'work conditions',
            'öhdəliklər',
            'tələblər',
            'vəzifələr',
        ];

        foreach ($keywords as $keyword) {
            if (Str::contains($normalized, $keyword)) {
                return true;
            }
        }

        return Str::endsWith($line, ':');
    }

    protected static function cleanNode(DOMNode $node): void
    {
        // iterate over children first so unwrapping nodes won't skip siblings
        $children = [];
        foreach ($node->childNodes as $child) {
            $children[] = $child;
        }

        foreach ($children as $child) {
            self::cleanNode($child);
        }

        if ($node->nodeType === XML_ELEMENT_NODE) {
            if (! in_array($node->nodeName, self::ALLOWED_TAGS, true)) {
                self::unwrapNode($node);
                return;
            }

            if ($node->hasAttributes()) {
                $attributes = [];
                foreach ($node->attributes as $attribute) {
                    $attributes[] = $attribute;
                }

                foreach ($attributes as $attribute) {
                    $node->removeAttributeNode($attribute);
                }
            }
        }
    }

    protected static function unwrapNode(DOMNode $node): void
    {
        $parent = $node->parentNode;

        if (! $parent) {
            return;
        }

        while ($node->firstChild) {
            $parent->insertBefore($node->firstChild, $node);
        }

        $parent->removeChild($node);
    }

    protected static function escape(string $value): string
    {
        return htmlspecialchars($value, ENT_QUOTES, 'UTF-8', false);
    }
}
