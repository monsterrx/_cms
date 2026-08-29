<?php

namespace App\Support;

use DOMComment;
use DOMDocument;
use DOMElement;
use DOMNode;

final class RichTextSanitizer
{
    /** @var array<int, string> */
    private const ALLOWED_TAGS = [
        'a', 'b', 'blockquote', 'br', 'em', 'h2', 'h3', 'i', 'img', 'li', 'ol', 'p', 's', 'strong', 'u', 'ul',
    ];

    /** @var array<int, string> */
    private const REMOVED_WITH_CONTENT = ['iframe', 'object', 'script', 'style', 'svg'];

    public function sanitize(?string $html): string
    {
        if (trim((string) $html) === '') {
            return '';
        }

        $document = new DOMDocument('1.0', 'UTF-8');
        $previous = libxml_use_internal_errors(true);
        $document->loadHTML(
            '<?xml encoding="UTF-8"><div id="rich-text-root">'.$html.'</div>',
            LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD
        );
        libxml_clear_errors();
        libxml_use_internal_errors($previous);

        $root = $document->getElementById('rich-text-root');
        if (! $root) {
            return '';
        }

        foreach (iterator_to_array($root->childNodes) as $child) {
            $this->sanitizeNode($child);
        }

        return collect(iterator_to_array($root->childNodes))
            ->map(static fn (DOMNode $node): string => $document->saveHTML($node) ?: '')
            ->implode('');
    }

    private function sanitizeNode(DOMNode $node): void
    {
        if ($node instanceof DOMComment) {
            $node->parentNode?->removeChild($node);

            return;
        }

        if (! $node instanceof DOMElement) {
            return;
        }

        $tag = strtolower($node->tagName);
        if (in_array($tag, self::REMOVED_WITH_CONTENT, true)) {
            $node->parentNode?->removeChild($node);

            return;
        }

        foreach (iterator_to_array($node->childNodes) as $child) {
            $this->sanitizeNode($child);
        }

        if (! in_array($tag, self::ALLOWED_TAGS, true)) {
            $parent = $node->parentNode;
            if (! $parent) {
                return;
            }

            while ($node->firstChild) {
                $parent->insertBefore($node->firstChild, $node);
            }
            $parent->removeChild($node);

            return;
        }

        foreach (iterator_to_array($node->attributes) as $attribute) {
            $allowed = match ($tag) {
                'a' => ['href', 'target'],
                'img' => ['alt', 'height', 'src', 'title', 'width'],
                default => [],
            };

            if (! in_array(strtolower($attribute->name), $allowed, true)) {
                $node->removeAttribute($attribute->name);
            }
        }

        if ($tag === 'a') {
            $this->sanitizeLink($node);
        } elseif ($tag === 'img') {
            $this->sanitizeImage($node);
        }
    }

    private function sanitizeLink(DOMElement $link): void
    {
        $href = trim($link->getAttribute('href'));
        $scheme = strtolower((string) parse_url($href, PHP_URL_SCHEME));

        if ($href === '' || ($scheme !== '' && ! in_array($scheme, ['http', 'https', 'mailto', 'tel'], true))) {
            $link->removeAttribute('href');
        }

        if ($link->getAttribute('target') === '_blank') {
            $link->setAttribute('rel', 'noopener noreferrer');
        } else {
            $link->removeAttribute('target');
        }
    }

    private function sanitizeImage(DOMElement $image): void
    {
        $source = trim($image->getAttribute('src'));
        $scheme = strtolower((string) parse_url($source, PHP_URL_SCHEME));
        $safeRelativePath = str_starts_with($source, '/') && ! str_starts_with($source, '//');

        if ($source === '' || (! $safeRelativePath && ! in_array($scheme, ['http', 'https'], true))) {
            $image->parentNode?->removeChild($image);

            return;
        }

        foreach (['width', 'height'] as $dimension) {
            $value = $image->getAttribute($dimension);
            if ($value !== '' && ! ctype_digit($value)) {
                $image->removeAttribute($dimension);
            }
        }

        $image->setAttribute('loading', 'lazy');
    }
}
